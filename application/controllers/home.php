<?php
// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function index()
    {
        // if user is logged in, load home view, otherwise logout
        if ($this->ion_auth->logged_in())
        {
            $this->load->model('load_groups');
            $user_info = $this->ion_auth->get_user();

            // retrieve other useful variables for view
            $firstname = $user_info->first_name;
            $lastname = $user_info->last_name;
            $joined_groups = $this->load_groups->joined_groups();
            $followed_groups = $this->load_groups->followed_groups();
            // chay
            // Lookup the groups by id.
            $this->load->model('load_groups');

            // Pass the necessary information to the view.
            $this->load->view('home_view', array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'joined_groups' => $joined_groups,
                'followed_groups' => $followed_groups)
            );
        } else
        {
            $this->logout();
        }
    }

// logs user out and redirects to login page
    public function logout()
    {
        $this->ion_auth->logout();
        redirect('/login/');
    }

// load and return user plan data
    public function loadMyEvents()
    {
        $this->load->database();

// get user info from ion_auth
        $user_info = $this->ion_auth->get_user();
        $user_id = $user_info->id;

// pull all user's current events
        $query = "SELECT plans.time_of_day, plans.date, places.name 
        FROM plans 
        LEFT JOIN places 
        ON plans.place_id=places.id 
        WHERE plans.user_id=$user_id";

// pull data
        $query_result = $this->db->query($query);
        $row = $query_result->row();

        return $row;
    }

// Checks the PlanJar Places database for matching places.
    public function find_places()
    {
        $this->load->database();

        $needle = $this->input->get('needle');
        $search_terms = explode(' ', $needle);

        $latitude = $this->input->get('latitude');
        $longitude = $this->input->get('longitude');

        $like_clauses = '';
        foreach ($search_terms as $term)
        {
            $term = $this->db->escape_like_str($term);
            $like_clauses .= "places.name LIKE '%%$term%%' AND ";
        }
        $like_clauses = substr($like_clauses, 0, -5);

// Check the PlanJar database. (Query string courtesy of Wells.)
        $query_string = "SELECT places.id, ((ACOS(SIN(? * PI() / 180) * SIN(places.latitude * PI() / 180) 
  + COS(? * PI() / 180) * COS(places.latitude * PI() / 180) * COS((? - places.longitude) 
  * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance, places.name, places.category 
  FROM places WHERE ($like_clauses) ORDER BY distance ASC LIMIT ?";
        $query = $this->db->query($query_string, array($latitude, $latitude, $longitude, 10));

// Return a JSON array.
        foreach ($query->result_array() as $row)
        {
// Append to the return array.
            $return_array[] = $row;
        }

// Check for no results.
        if (!isset($return_array))
        {
            echo(json_encode(array('count' => 0)));
        } else
        {
            // Return a JSON array with count and data members.
            echo(json_encode(array('count' => count($return_array), 'data' => $return_array)));
        }
    }

    // Checks the plan cotegories with the server.
    public function find_plan_categories()
    {
        $this->load->database();

        $needle = $this->input->get('needle');
        $search_terms = explode(' ', $needle);

        $like_clauses = '';
        foreach ($search_terms as $term)
        {
            $term = $this->db->escape_like_str($term);
            $like_clauses .= "`category` LIKE '%%$term%%' OR ";
        }
        $like_clauses = substr($like_clauses, 0, -4);

// Check the PlanJar database.
        $query_string = "SELECT id, category FROM plan_categories WHERE $like_clauses LIMIT 10";
        $query = $this->db->query($query_string);

// Return a JSON array.
        foreach ($query->result_array() as $row)
        {
// Append to the return array.
            $return_array[] = $row;
        }

// Check for no results.
        if (!isset($return_array))
        {
            echo('no results');
        } else
        {
            echo(json_encode($return_array));
        }
    }

// For Mason to fuck with...
    public function foo()
    {
        $this->load->view('foo_view');
    }

    // Adds a plan entry to the database, creates an event if necessary, and invites and notifies users if required.
    public function submit_plan()
    {
        $this->load->database();

        $user = $this->ion_auth->get_user();
        $date = new DateTime();
        $date->add(new DateInterval('P' . $this->input->get('plan_day') . 'D'));

        $data = array(
            'id' => 'DEFAULT',
            'place_id' => $this->input->get('plan_location_id'),
            'user_id' => $user->id,
            'date' => $date->format('Y-m-d'),
            'time_of_day' => $this->input->get('plan_time'),
            'title' => $this->input->get('plan_title'),
            'event_id' => $this->input->get('event_id')
        );

        // Capture and process the invite lists
        $invited_users = explode(',', $this->input->get('invite_plan_user'));
        var_dump($invited_users);
        if ($invited_users == false)
        {
            $invited_users = array();
        }

        $invited_groups = explode(',', $this->input->get('invite_plan_group'));
        if ($invited_groups == false)
        {
            $invited_groups = array();
        }

        // Handle privacy settings
        $privacy = $this->input->get('privacy');
        if ($privacy != 'none')
        {
            // Privacy settings enabled. Add an event_id (newly created event)
            $this->load->model('event_ops');
            $data['event_id'] = $this->event_ops->create_event($privacy, $invited_users, $invited_groups);
        } else
        {
            // No privacy settings. Continue creating a plan as normal.
            $data['event_id'] = NULL;
        }

        // Add the place to the database if a Factual place was selected.
        if ($this->input->get('new_place_name') != '')
        {
            // If the Factual ID was already in the database, use the ID of that entry instead of creating a new place.
            $query_string = "SELECT id FROM places WHERE factual_id = ?";
            $query = $this->db->query($query_string, array($this->input->get('new_place_factual_id')));
            if ($query->num_rows() > 0)
            {
                $row = $query->row();
                $data['place_id'] = $row->id;
            } else
            {
                // Add the new place.
                $query_string = "INSERT INTO places VALUES (DEFAULT, ?, ?, ?, ?, ?)";
                $query = $this->db->query($query_string, array(
                            $this->input->get('new_place_factual_id'),
                            $this->input->get('new_place_name'),
                            $this->input->get('new_place_latitude'),
                            $this->input->get('new_place_longitude'),
                            $this->input->get('new_place_category')
                        ));

                // Overwrite the place id with the new place.
                $data['place_id'] = $this->db->insert_id();
            }
        }

        // Add the plan.
        $query = $this->db->insert('plans', $data);

        // Invite people and groups if necessary.
        if (count($invited_users) > 0)
        {
            $this->load->model('notification_ops');
            $this->notification_ops->notify_users($invited_users, $data['date'], 'plan_invite', $this->db->insert_id());
        }

        if (count($invited_groups) > 0)
        {
            $this->load->model('notification_ops');
            $this->notification_ops->notify_joined_groups($invited_groups, $data['date'], 'plan_invite', $this->db->insert_id());
        }

        // Success
        echo('success');
    }

    // Returns chart data based on the selected groups and day
    public function get_group_day_data()
    {
        $selected_groups = $this->input->get('selected_groups');
        $selected_day = $this->input->get('selected_day');

        if ($selected_groups)
        {
            var_dump($selected_groups);
            echo('Selected groups ^^<br/>Selected day: ' . $selected_day);
        } else
        {
            echo('Select groups on the left to see more information.');
        }
    }

    public function load_selected_plan_data()
    {
        $this->load->database();
        $plan = $this->input->get('plan_selected');
        $this->load->model('load_plans');
        $return = $this->load_plans->loadPlanData($plan);
        echo $return;
    }

    // permanently deletes plan
    public function delete_plan()
    {
        $this->load->database();
        $plan = $this->input->get('plan_selected');
        $this->load->model('load_plans');
        $return_str = $this->load_plans->deletePlan($plan);
        echo $return_str;
    }

    // Return a list of plans visible to the user.
    // This code is sweet
    // called from "home_functions.js"
    public function load_popular_locations()
    {
        // load the database and model
        $this->load->database();
        $this->load->model('load_locations');

        // this contains a list of ids for the groups selected
        $group_list = $this->input->get('selected_groups');
        $day = $this->input->get('selected_day');
        $user_id = $this->ion_auth->get_user()->id;

        $this->load_locations->loadUserLocations($group_list, $day, $user_id);
    }

    public function show_location_data()
    {
        $this->load->database();
        $this->load->model('load_location_data');
        $user = $this->ion_auth->get_user();
        $user_id = $user->id;
        $place_id = $this->input->get('place_id');
        $date = $this->input->get('date');
        $return_string = $this->load_location_data->showLocation($place_id, $date, $user_id);

        echo $return_string;
    }

    // Returns HTML for the list of the user's plans (right panel)
    public function get_my_plans()
    {
        $this->load->model('load_plans');
        $user_info = $this->ion_auth->get_user();
        $user_id = $user_info->id;
        $result = $this->load_plans->getPlans($user_id);
        $date_organizer = "lol";
        ?>

        <div class="active_plans"><?php
        foreach ($result as $plan)
        {
            // make easy to read variables
            $id = $plan->id;
            $name = $plan->name;
            $title = $plan->title;
            $time = $plan->time_of_day;
            $date = date('l', strtotime($plan->date));
            if ($date_organizer != $date)
            {
                echo "<hr>";
                echo $date . "<br>";
                echo "<hr>";
            }
            $date_organizer = $date;
            echo "<div class =\"plan_content\" plan_id=\"$id\" >";
            echo $name;
            echo "</div>";
            echo "<div id=\"plan_padding\" style =\"width:100%; height:10px;\"></div>";
        }
        echo "</div>";
    }

    // Update the user's location
    public function update_user_location()
    {
        $new_lat = $this->input->get('latitude');
        $new_long = $this->input->get('longitude');

        $user = $this->ion_auth->get_user();
        $delta_distance = $this->_get_distance_between($user->latitude, $user->longitude, $new_lat, $new_long);

        if ($this->input->get('auto') == 'false')
        {
            $this->ion_auth->update_user($user->id, array(
                'latitude' => $new_lat,
                'longitude' => $new_long));
            echo('success');
        } else if ($delta_distance > 20)
        {
            $this->ion_auth->update_user($user->id, array(
                'latitude' => $new_lat,
                'longitude' => $new_long));
            echo("We have adjusted your location by $delta_distance miles. Please change your location if this seems off.");
        } else
        {
            echo('success');
        }
    }

    private function _get_distance_between($lat0, $long0, $lat1, $long1)
    {
        return ((acos(sin($lat0 * pi() / 180) * sin($lat1 * pi() / 180)
                + cos($lat0 * pi() / 180) * cos($lat1 * pi() / 180) * cos(($long0 - $long1)
                        * pi() / 180)) * 180 / pi()) * 60 * 1.1515);
    }

    // Returns a set of 7 weekday tabs based on the supplied parameter.
    public function get_weekday_tab_set()
    {
        $start = $this->input->get('starting_offset');

        $date = new DateTime();
        $date->add(new DateInterval('P' . $start . 'D'));

        for ($i = 0; $i < 7; ++$i)
        {
            if ($start == 0 && $i == 0)
            {
                $display_date = 'Today';
            } else
            {
                $display_date = $date->format('D - j');
            }

            echo('<div class="day" day_offset="' . ($start + $i) . '"><div class="day_text">' . $display_date . '</div></div>');
            $date->add(new DateInterval('P1D'));
        }
    }

    // Returns a list of people following the user (used for inviting people in a plan)
    public function get_followers_invite()
    {
        $needle = trim($this->input->get('needle'));
        if ($needle != '')
        {
            $user = $this->ion_auth->get_user();

            // Break into search terms
            $needle_array = explode(' ', $needle);

            // Generate query strings to cross-reference all needle terms with the first and last names in the db
            $needle_where = '';
            foreach ($needle_array as $cur_needle)
            {
                $needle_where .= "(user_meta.first_name LIKE '%%$cur_needle%%' OR " .
                        "user_meta.last_name LIKE '%%$cur_needle%%') AND ";
            }

            // Trim the end of the string
            if (count($needle_array) > 0)
            {
                $needle_where = substr($needle_where, 0, -5);
            }

            $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name
                    FROM friends LEFT JOIN user_meta ON friends.user_id = user_meta.user_id
                    WHERE ($needle_where) AND friends.follow_id = ?";

            $query = $this->db->query($query_string, array($user->id));

            // Echo the results
            $return_array = array();
            foreach ($query->result() as $row)
            {
                $return_array[] = array(
                    'id' => $row->user_id,
                    'name' => $row->first_name . ' ' . $row->last_name
                );
            }

            echo(json_encode($return_array));
        }
    }

    // Returns a list of people following the user (used for inviting people in a plan)
    public function get_groups_invite()
    {
        $needle = trim($this->input->get('needle'));
        if ($needle != '')
        {
            $user = $this->ion_auth->get_user();

            // Break into search terms
            $needle_array = explode(' ', $needle);

            // Generate query strings to cross-reference all needle terms with the first and last names in the db
            $needle_where = '';
            foreach ($needle_array as $cur_needle)
            {
                $needle_where .= "groups.name LIKE '%%$cur_needle%%' AND ";
            }

            // Trim the end of the string
            if (count($needle_array) > 0)
            {
                $needle_where = substr($needle_where, 0, -5);
            }

            $query_string = "SELECT groups.id, groups.name
                    FROM group_relationships LEFT JOIN groups ON group_relationships.group_id = groups.id
                    WHERE ($needle_where) AND group_relationships.user_joined_id = ?";

            $query = $this->db->query($query_string, array($user->id));

            // Echo the results
            $return_array = array();
            foreach ($query->result() as $row)
            {
                $return_array[] = array(
                    'id' => $row->id,
                    'name' => $row->name
                );
            }

            echo(json_encode($return_array));
        }
    }

}
?>
