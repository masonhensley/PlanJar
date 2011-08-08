<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function index()
    {
        $foo = new DateTime('9:35 am');
        echo($foo->format('H:i:s'));
        
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
            $school = $this->load_groups->user_school();

            // Get the day tabs HTML
            $this->load->model('day_sets');
            $day_html = $this->day_sets->day_set(0);
            $plan_day_html = $this->day_sets->day_set(0, true);

            // Get the plan tabs HTML
            $this->load->model('plan_actions');
            $plans_html = $this->plan_actions->display_plans();

            // get friend names to populate the friend plan modal
            $friend_names = $this->get_friend_names();

            // get the upcoming events HTML
            //$this->load->model('load_coming_events'); //this entire function should be moved to populate when the DOM loads
            //$upcoming_event_html = $this->load_coming_events->load_events();
            // Pass the necessary information to the view.
            $this->load->view('home_view', array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'joined_groups' => $joined_groups,
                'followed_groups' => $followed_groups,
                'day_html' => $day_html,
                'plan_day_html' => $plan_day_html,
                'school' => $school,
                'plans_html' => $plans_html,
                'friend_names' => $friend_names)
            );
        } else
        {
            $this->logout();
        }
    }

    public function get_friend_names()
    {
        $this->load->model('load_locations');
        $friend_ids = $this->load_locations->get_friend_ids();

        $query = "SELECT user_id, first_name, last_name FROM user_meta WHERE ";
        foreach ($friend_ids as $id)
        {
            $query .= "user_id=$id OR ";
        }
        $query = substr($query, 0, -4);
        $result = $this->db->query($query);

        $name_array = array();

        foreach ($result->result() as $name)
        {
            $name_array[$name->user_id] = $name->first_name . " " . $name->last_name;
        }
        return $name_array;
    }

// logs user out and redirects to login page
    public function logout()
    {
        $this->ion_auth->logout();
        redirect('/login/');
    }

// Checks the PlanJar Places database for matching places.
    public function find_places()
    {
        $needle = trim($this->input->get('needle'));

        $latitude = $this->input->get('latitude');
        $longitude = $this->input->get('longitude');

        // Check the PlanJar database. (Query string courtesy of Wells.)
        $query_string = "SELECT places.id, places.latitude, places.longitude, places.name, place_categories.alias AS category,
            ((ACOS(SIN(? * PI() / 180) * SIN(places.latitude * PI() / 180) 
            + COS(? * PI() / 180) * COS(places.latitude * PI() / 180) * COS((? - places.longitude) 
            * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance
            FROM places JOIN place_categories ON place_categories.id = places.category_id
            WHERE MATCH (places.name) AGAINST (? IN BOOLEAN MODE) ORDER BY distance ASC LIMIT ?";
        $query = $this->db->query($query_string, array($latitude, $latitude, $longitude, str_replace(' ', '* ', $needle) . '*', 10));

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

    // Adds a plan entry to the database and creates an event if necessary
    // Returns the event id
    public function submit_plan()
    {
        $event_id = $this->input->get('plan_event_id');
        $privacy = $this->input->get('privacy');

        // Create a new event if one wasn't selected
        $new_event = false;
        if ($event_id == '')
        {
            // Event data
            $date = new DateTime();
            $date->add(new DateInterval('P' . $this->input->get('plan_day') . 'D'));
            $event_data = array(
                'title' => $this->input->get('event_title'),
                'place_id' => $this->input->get('plan_location_id'),
                'date' => $date->format('Y-m-d'),
                'time' => $this->input->get('plan_time'),
                'privacy' => $privacy,
                'originator_id' => $this->ion_auth->get_user()->id
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

            // Update event id with the new event
            $this->load->model('event_ops');
            $existing_event = $this->event_ops->check_event($event_data);

            if ($existing_event === false)
            {
                // Create an event
                $new_event = true;
                $event_id = $this->event_ops->create_event($event_data);
            } else
            {
                // Use the existing event
                $event_id = $existing_event;
            }


            // Add the user to the invite list
            $this->event_ops->add_invitees($event_id, array($this->ion_auth->get_user()->id));
        }

        // Plan data
        $plan_data = array(
            'user_id' => $this->ion_auth->get_user()->id,
            'event_id' => $event_id
        );

        // Add the plan
        $this->load->model('plan_actions');
        $this->plan_actions->add_plan($plan_data);

        // Check if the user already has plans to that place at that time
        $plan_check = $this->plan_actions->unique_plan($event_id);
        if ($plan_check === true)
        {
            echo(json_encode(array('status' => 'success', 'originator' => $new_event, 'event_id' => $event_id)));
        } else
        {
            // Pre-existing plan. Return HTML for two options
            $this->load->model('event_ops');
            $choice_data = $this->event_ops->get_events_for_choice($event_id, $plan_check);
            echo(json_encode(array_merge(
                            array('status' => 'conflict'), $choice_data, array('originator' => $new_event))));
        }
    }

    public function load_selected_plan_data()
    {
        $plan_id = $this->input->get('plan_selected');
        $this->load->model('load_plan_data');
        $return_array = $this->load_plan_data->display_plan_data($plan_id);
        echo json_encode($return_array);
    }

    public function get_notification_popup()
    {
        $user_id = $this->ion_auth->get_user()->id;
        $query = "SELECT id FROM notifications WHERE user_id=$user_id AND viewed=0";
        $result = $this->db->query($query);
        $number_notifications = $result->num_rows();
        echo $number_notifications;
    }

    // permanently deletes plan 
    public function delete_plan()
    {
        $plan = $this->input->get('plan_selected');
        $this->load->model('plan_actions');
        $this->plan_actions->delete_plan($plan);
    }

    public function load_friend_plans()
    {
        $friend_id = $this->input->get('friend_id');
        $this->load->model('load_friend_plans');
        $this->load_friend_plans->populate_plans($friend_id);
    }

    // Return a list of location tabs based on the groups selected
    // called from data_box_functions.js
    public function load_location_tabs()
    {
        $this->load->model('load_locations');
        $group_list = $this->input->get('selected_groups'); // this contains a list of ids for the groups selected
        $day = $this->input->get('selected_day');
        $user_id = $this->ion_auth->get_user();
        $user_id = $user_id->id;
        $school = $this->_get_user_school();
        $this->load_locations->load_relevant_locations($group_list, $day, $user_id, $school);
    }

    private function _get_user_school()
    {
        $query_string = "SELECT school FROM school_data WHERE id = ?";
        $query = $this->db->query($query_string, array($this->ion_auth->get_user()->school_id));
        return $query->row()->school;
    }

    // this function populates the data box for when a group or location is selected
    public function load_data_box()
    {
        $selected_groups = $this->input->get('selected_groups');
        $day = $this->input->get('selected_day');
        $filter = $this->input->get('filter');

        $this->load->model('display_group_template');
        $school = $this->_get_user_school();
        $return_array = $this->display_group_template->_display_group_info($selected_groups, $day, $school, $filter);
        echo json_encode($return_array);
    }

    // this function is called when a location tab is clicked to display its information
    public function show_location_data()
    {
        $this->load->model('load_location_data');
        $place_id = $this->input->get('place_id');
        $add_back_button = $this->input->get('back_button');

        $date = $this->input->get('date');

        $selected_groups = $this->input->get('selected_groups');
        $return_array = $this->load_location_data->_display_location_info($place_id, $date, $selected_groups, $add_back_button);
        echo json_encode($return_array);
    }

    public function show_event_data()
    {
        $this->load->model('load_event_data');
        $place_id = $this->input->get('place_id');
        $this->load_event_data->display($place_id);
    }

    // Returns HTML for the list of the user's plans (right panel)
    public function get_my_plans()
    {
        $this->load->model('plan_actions');
        echo ($this->plan_actions->display_plans());
    }

    // Update the user's location
    public function update_user_location()
    {
        $new_lat = $this->input->get('latitude');
        $new_long = $this->input->get('longitude');

        $user = $this->ion_auth->get_user();
        $delta_distance = round($this->_get_distance_between($user->latitude, $user->longitude, $new_lat, $new_long), 2);

        if ($this->input->get('auto') == 'false' || $user->latitude == NULL || $user->longitude == NULL)
        {
            // Runs when the user location information is missing or when the location is manually changed
            $this->ion_auth->update_user($user->id, array(
                'latitude' => $new_lat,
                'longitude' => $new_long));
            echo(json_encode(array('status' => 'silent')));
        } else if ($delta_distance > 20)
        {
            // Runs when auto updating the location and the max distance is met
            $this->ion_auth->update_user($user->id, array(
                'latitude' => $new_lat,
                'longitude' => $new_long));

            $return_array = array('status' => 'adjusted',
                'text' => "We have adjusted your location by $delta_distance miles. Please change your location if this seems off.");
            echo(json_encode($return_array));
        } else
        {
            // Returns the user's profile location if the distance offset is not met.
            $return_array = array('status' => 'from_profile',
                'loc' => array($user->latitude, $user->longitude));
            echo(json_encode($return_array));
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
        $this->load->model('day_sets');
        echo($this->day_sets->day_set($this->input->get('starting_offset'), $this->input->get('plan_set')));
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
                    FROM friend_relationships LEFT JOIN user_meta ON friend_relationships.user_id = user_meta.user_id
                    WHERE ($needle_where) AND friend_relationships.follow_id = ?";

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

    // Returns HTML for divSet buttons containing names of the user's followers
    public function get_followers_divset()
    {
        $this->load->model('follow_ops');

        echo('<table>');

        $begin_row = true;
        foreach ($this->follow_ops->get_followers_tuples() as $tuple)
        {
            if ($begin_row)
            {
                // Add a table row
                echo('<tr>');
            }

            // Td body
            echo('<td>');
            echo('<div class="invite_followers_divset" user_id="' . $tuple['id'] . '">');
            echo($tuple['name']);
            echo('</div>');
            echo('</td>');

            if (!$begin_row)
            {
                // Close the table row
                echo('</tr>');
            }
            $begin_row = !$begin_row;
        }

        echo('</table>');
    }

    // Returns HTML for divSet buttons containing names of the user's joined gorups
    public function get_joined_groups_divset()
    {
        $this->load->model('group_ops');

        foreach ($this->group_ops->get_joined_groups_tuples() as $tuple)
        {
            echo('<div class="invite_groups_divset" group_id="' . $tuple['id'] . '">');
            echo($tuple['name']);
            echo('</div>');
        }
    }

    public function search_school_users()
    {
        $needle = trim($this->input->get('needle'));
        if ($needle != '')
        {
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
            $needle_where = substr($needle_where, 0, -5);

            $query_string = "SELECT user_id, first_name, last_name
                    FROM user_meta WHERE ($needle_where) AND school_id = ? AND user_id <> ?";
            $query = $this->db->query($query_string, array($this->ion_auth->get_user()->school_id,
                $this->ion_auth->get_user()->id));

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

    // Invites and notifies the given users and groups
    public function invite_people()
    {
        // Capture vars
        $user_ids = $this->input->get('user_ids');
        if (!$user_ids)
        {
            $user_ids = array();
        }
        $group_ids = $this->input->get('group_ids');
        if (!$group_ids)
        {
            $group_ids = array();
        }

        $this->load->model('group_ops');
        $user_ids = array_merge($user_ids, $this->group_ops->get_users($group_ids));

        $subject_id = $this->input->get('subject_id');
        $subject_type = $this->input->get('subject_type');

        // Handle the different subject types
        if ($subject_type == 'event')
        {
            $this->load->model('event_ops');
            $this->event_ops->add_invitees($subject_id, $user_ids);
            $notif_type = 'event_invite';
        } else if ($subject_type == 'group')
        {
            $notif_type = 'group_invite';
        }

        // Send notifications
        $this->load->model('notification_ops');
        $this->notification_ops->notify($user_ids, $notif_type, $subject_id);

        echo('success');
    }

    // Resolves the conflict between the given two events (user trying to go to two events
    // at the same place at the same time)
    public function resolve_plan_conflict()
    {
        // Get the plan to discard
        $query_string = "SELECT id FROM plans WHERE event_id = ? AND user_id = ?";
        $query = $this->db->query($query_string, array(
            $this->input->get('discard_event'),
            $this->ion_auth->get_user()->id
                ));


        // Discard the plan
        $this->load->model('plan_actions');
        $this->plan_actions->delete_plan($query->row()->id);
    }

    // Returns 'available' or an error message if the event name is already in use
    public function check_preexisting_event()
    {
        // Capture the input
        $needle = $this->input->get('needle');
        $plan_time = $this->input->get('plan_time');
        $plan_date = new DateTime();
        $plan_date->add(new DateInterval('P' . $this->input->get('plan_day') . 'D'));
        $plan_date = $plan_date->format('Y-m-d');
        $place_id = $this->input->get('place_id');

        // Check for a new place (impossible to have pre-existing events)
        if ($place_id == 'factual')
        {
            // No event
            echo('available');
            return;
        }

        $query_string = "SELECT * FROM events WHERE title = ? AND date = ? AND time = ?";
        $query = $this->db->query($query_string, array(
            $needle,
            $plan_date,
            $plan_time
                ));

        if ($query->num_rows() > 0)
        {
            // Pre-existing event
            echo("There's already an event with that title. Note that, because of privacy settings, the event may not actually be visible to you.");
        } else
        {
            // No event
            echo('available');
        }
    }

    // Returns a JSON list as needed by the new place category search
    public function search_place_categories()
    {
        $needle = trim($this->input->get('needle'));

        if ($needle != '')
        {
            $needle_array = explode(' ', $needle);

            $where_clause = '';
            foreach ($needle_array as $cur_needle)
            {
                $where_clause .= "category LIKE '%$cur_needle%' AND ";
            }
            $where_clause = substr($where_clause, 0, -5);

            $query_string = "SELECT id, category
            FROM place_categories
            WHERE $where_clause";
            $query = $this->db->query($query_string);

            // Create the return array
            $return_array = array();
            foreach ($query->result() as $row)
            {
                $return_array[] = array(
                    'id' => $row->id,
                    'category' => $row->category
                );
            }

            echo(json_encode($return_array));
        }
    }

    // Adds a location to the database
    // Returns the new place id and name for daisy chaining into the make plan modal
    public function add_location()
    {
        $this->load->model('place_ops');
        $data = array(
            'name' => $this->input->get('new_location_name'),
            'latitude' => $this->input->get('new_location_latitude'),
            'longitude' => $this->input->get('new_location_longitude'),
            'category_id' => $this->input->get('new_location_category_id')
        );

        $place_id = $this->place_ops->add_user_place($data);

        echo(json_encode(array('id' => $place_id, 'name' => $data['name'])));
    }

}

?>
