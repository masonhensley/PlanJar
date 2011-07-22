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
        // Event data
        $event_data = array(
            'title' => $this->input->get('plan_title'),
            'place_id' => $this->input->get('plan_location_id'),
            'date' => $date->format('Y-m-d'),
            'time' => $this->input->get('plan_time'),
            'privacy' => $this->input->get('privacy')
        );

        // Add the place to the PlanJar database if a Factual place was selected.
        if ($this->input->get('new_place_name') != '')
        {
            $place_data = array(
                'factual_id' => $this->input->get('new_place_factual_id'),
                'name' => $this->input->get('new_place_name'),
                'latitude' => $this->input->get('new_place_latitude'),
                'longitude' => $this->input->get('new_place_longitude'),
                'category' => $this->input->get('new_place_category')
            );

            $this->load->model('place_ops');
            $event_data['place_id'] = $this->place_ops->add_factual_place($place_data);
        }

        // Plan data
        $this->load->model('event_ops');
        $plan_data = array(
            'user_id' => $this->ion_auth->get_user()->id,
            'event_id' => $this->event_ops->create_event($event_data)
        );

        // Add the plan and store the id
        $this->load->model('plan_actions');
        $plan_id = $this->plan_actions->add_plan($plan_data);

        // Capture the invite lists
        $invited_users = explode(',', $this->input->get('invite_plan_users'));
        $invited_groups = explode(',', $this->input->get('invite_plan_groups'));
        if ($invited_users[0] == '')
        {
            $invited_users = array();
        }
        if ($invited_groups[0] == '')
        {
            $invited_groups = array();
        }

        // Add invitees if necessary.
        if ($event_data['privacy'] != 'open')
        {
            $this->event_ops->add_invitees($plan_data['event_id'], $invited_users, $invited_groups);
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
            $this->notification_ops->notify_users($invited_users, 'plan_invite', $this->db->insert_id());
        }

        if (count($invited_groups) > 0)
        {
            $this->load->model('notification_ops');
            $this->notification_ops->notify_joined_groups($invited_groups, 'plan_invite', $this->db->insert_id());
        }

        // Success
        echo('success');
    }

    public function load_selected_plan_data()
    {
        $plan = $this->input->get('plan_selected');
        $this->load->model('plan_actions');
        $return = $this->plan_actions->load_plan_data($plan);
        echo $return;
    }

    // permanently deletes plan
    public function delete_plan()
    {
        $plan = $this->input->get('plan_selected');
        $this->load->model('plan_actions');
        $return_str = $this->plan_actions->delete_plan($plan);
        echo $return_str;
    }

    // Return a list of location tabs based on the groups selected
    // called from data_box_functions.js
    public function load_location_tabs()
    {
        // load the model
        $this->load->model('load_locations');

        // this contains a list of ids for the groups selected
        $group_list = $this->input->get('selected_groups');
        $day = $this->input->get('selected_day');


        $user_id = $this->ion_auth->get_user()->id;
        if (isset($group_list[0])) // when a group is selected. populate the location tabs
        {
            $this->load_locations->loadUserLocations($group_list, $day, $user_id);
        }
    }

    // this function populates the data box for when a group or location is selected
    public function load_data_box()
    {
        $group_list = $this->input->get('selected_groups');
        $day = $this->input->get('selected_day');

        if (count($group_list) == 0)
        {
            $this->load->model('display_default_home_info');
            $this->display_default_home_info->setup_default_view($day);
        } else if (count($group_list) > 0)
        {
            $this->load->model('display_group_info');
            $this->display_group_info->_display_group_info($day);
        }
    }

    // this function is called when a location tab is clicked to display its information
    public function show_location_data()
    {
        $this->load->model('load_location_data');
        $user = $this->ion_auth->get_user();
        $user_id = $user->id;
        $place_id = $this->input->get('place_id');
        $date = $this->input->get('date');
        $return_string = $this->load_location_data->display_location_info($place_id, $date, $user_id);

        echo $return_string;
    }

    // Returns HTML for the list of the user's plans (right panel)
    public function get_my_plans()
    {
        $this->load->model('plan_actions');
        $this->load->model('load_plan_panel');

        $user_info = $this->ion_auth->get_user();
        $user_id = $user_info->id;
        $result = $this->plan_actions->get_plans($user_id);
        $this->load_plan_panel->display_plans($result);
    }

    // Update the user's location
    public function update_user_location()
    {
        $new_lat = $this->input->get('latitude');
        $new_long = $this->input->get('longitude');

        $user = $this->ion_auth->get_user();
        $delta_distance = $this->_get_distance_between($user->latitude, $user->longitude, $new_lat, $new_long);

        if ($this->input->get('auto') == 'false' || $user->latitude == NULL)
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
        ?>


        <?php
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
        ?> 
        <div class="left_day_arrow"><</div>
        <div class="right_day_arrow">></div>

        <?php
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

    // Returns HTML for a select input containing all the event names at the specified location and time
    public function get_events_for_plan()
    {
        $this->load->model('event_ops');
        $this->event_ops->get_events_for_plan($this->input->get('day'), $this->input->get('time'), $this->input->get('place_id'));
    }

}
?>
