<?php
// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
// Parent constructor
        parent::__construct();


// Redirect if not logged in
        if (!$this->ion_auth->logged_in())
        {
            redirect('login');
        }
    }

    public function index($action_type = '', $action_arg = '')
    {
        $this->load->model('load_groups');
        $this->load->model('load_profile');
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
            'friend_names' => $friend_names,
            'action_type' => $action_type,
            'action_arg' => $action_arg,
            'user_id' => $user_info->id,)
        );
    }

    public function get_friend_names()
    {
        $this->load->model('load_locations');
        $friend_ids = $this->load_locations->get_friend_ids();

        $name_array = array();
        if (count($friend_ids) > 0)
        {
            $query = "SELECT user_id, first_name, last_name FROM user_meta WHERE ";
            foreach ($friend_ids as $id)
            {
                $query .= "user_id=$id OR ";
            }
            $query = substr($query, 0, -4);
            $result = $this->db->query($query);

            foreach ($result->result() as $name)
            {
                $name_array[$name->user_id] = $name->first_name . " " . $name->last_name;
            }
        }
        return $name_array;
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
            FROM places LEFT JOIN place_categories ON place_categories.id = places.category_id
            WHERE MATCH (places.name) AGAINST (? IN BOOLEAN MODE)
            HAVING distance <= 30
            ORDER BY distance ASC LIMIT ?";
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
        if ($event_id == '' || !$event_id)
        {
// Event data
            $date = new DateTime();
            $date->add(new DateInterval('P' . $this->input->get('plan_day') . 'D'));
            $clock_time = $this->input->get('plan_clock_time');
            if ($clock_time != '')
            {
                $clock_time = new DateTime($clock_time);
                $clock_time = $clock_time->format('H:i:00');
            } else
            {
                $clock_time = NULL;
            }

            $event_data = array(
                'title' => trim($this->input->get('event_title')),
                'place_id' => $this->input->get('plan_location_id'),
                'date' => $date->format('Y-m-d'),
                'time' => $this->input->get('plan_time'),
                'clock_time' => $clock_time,
                'privacy' => $privacy,
                'originator_id' => $this->ion_auth->get_user()->id,
                'description' => trim($this->input->get('plan_description'))
            );

// Eliminate meaningless fields for a "just going" event
            if ($event_data['title'] == '')
            {
                unset($event_data['clock_time']);
                unset($event_data['originator_id']);
            }

// Add the place to the PlanJar database if a Factual place was selected.
            if ($this->input->get('new_place_factual_id') != '')
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
            $event_id = $this->event_ops->create_event($event_data, $new_event);
        }

// Plan data
        $plan_data = array(
            $this->ion_auth->get_user()->id,
            $event_id
        );

// Add the plan and echo the results
        $this->load->model('plan_actions');
        echo($this->plan_actions->add_plan($plan_data, $new_event));
    }

    public function load_selected_plan_data()
    {
        $plan_id = $this->input->get('plan_selected');
        $friend_plan = $this->input->get('friend_plan');

        $this->load->model('load_plan_data');
        $return_array = $this->load_plan_data->display_plan_data($plan_id, $friend_plan);

        echo json_encode($return_array);
    }

    public function plan_comments()
    {
        $plan_id = $this->input->get('plan_id');
        $this->load->model('load_plan_comments');
        $comments_html = $this->load_plan_comments->display_comments($plan_id);
        echo $comments_html;
    }

    public function submit_comment()
    {
        $plan_id = $this->input->get('plan_id');
        $comment = trim($this->input->get('comment'));
        $comment = mysql_real_escape_string($comment);

// get the event_id
        $query = "SELECT event_id FROM plans WHERE id=$plan_id";
        $result = $this->db->query($query);
        $row = $result->row();
        $event_id = $row->event_id;

// insert into comments
        $user = $this->ion_auth->get_user();
        $query = "
            INSERT INTO plan_comments 
            (event_id, comment, user_id, time)
            VALUES ($event_id, '$comment', $user->id, NOW())
            ";
        $this->db->query($query);
    }

    public function delete_comment()
    {
        $comment_id = $this->input->get('comment_id');
        $query = "
                DELETE FROM plan_comments WHERE id=$comment_id
                ";
        $this->db->query($query);
    }

    public function attending_list()
    {
        $plan_id = $this->input->get('plan_id');
        $this->load->model('load_attending_list');
        $this->load_attending_list->_display_attending_list($plan_id);
    }

    public function awaiting_list()
    {
        $plan_id = $this->input->get('plan_id');
        $this->load->model('load_attending_list');
        $this->load_attending_list->_display_awaiting_list($plan_id);
    }

// display the people in a group in a modal
    public function group_member_list()
    {
        $group_id = $this->input->get('group_id');
        $this->load->model('load_attending_list');
        $this->load_attending_list->_display_group_members($group_id);
    }

    public function get_notification_popup()
    {
        $user_id = $this->ion_auth->get_user()->id;
        $query = "SELECT notifications.id FROM notifications
                LEFT JOIN events ON notifications.subject_id = events.id AND notifications.type = 'event_invite'
                WHERE notifications.user_id = $user_id AND notifications.viewed = 0
                AND (notifications.type <> 'event_invite' OR events.date >= CURDATE())";
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
        $selected_place_id = $this->input->get('place_id');
        $user_id = $this->ion_auth->get_user();
        $user_id = $user_id->id;
        $school = $this->_get_user_school();
        $this->load_locations->load_relevant_locations($group_list, $day, $user_id, $school, $selected_place_id);
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
        $back_to_plan = $this->input->get('back_to_plan');
        $back_to_groups = $this->input->get('back_to_groups');
        $back_to_search = $this->input->get('back_to_search');
        $date = $this->input->get('date');

        $selected_groups = $this->input->get('selected_groups');
        $return_array = $this->load_location_data->_display_location_info($place_id, $date, $selected_groups, $back_to_plan == 'true', $back_to_groups == 'true', $back_to_search == 'true');
        echo json_encode($return_array);
    }

    public function location_plans_made_here()
    {
        $place_id = $this->input->get('place_id');
        $this->load->model('load_friend_plans');
        echo $this->load_friend_plans->get_location_plans($place_id);
    }

// Returns HTML for the list of the user's plans (right panel)
    public function get_my_plans()
    {
        $this->load->model('plan_actions');
        echo $this->plan_actions->display_plans();
    }

// Update the user's location
    public function update_user_location()
    {
        $user = $this->ion_auth->get_user();

        $new_lat = $this->input->get('latitude');
        $new_long = $this->input->get('longitude');
        $city = $this->input->get('city');

        if ($new_lat === false)
        {
// Only update the city (no coords passed)
            $this->ion_auth->update_user($user->id, array('city_state' => $this->input->get('city')));
            return;
        }

        $delta_distance = round($this->_get_distance_between($user->latitude, $user->longitude, $new_lat, $new_long), 2);

        if ($this->input->get('auto') == 'false' || $user->latitude == NULL || $user->longitude == NULL)
        {
// Runs when the user location information is missing or when the location is manually changed
            $update_array = array(
                'latitude' => $new_lat,
                'longitude' => $new_long
            );
            if ($city !== false)
            {
                $update_array['city_state'] = $city;
            }
            $this->ion_auth->update_user($user->id, $update_array);
            echo(json_encode(array('status' => 'silent',
                'city_state' => $user->city_state
            )));
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
                'loc' => array($user->latitude, $user->longitude),
                'city_state' => $user->city_state
            );
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

            $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name
                    FROM friend_relationships LEFT JOIN user_meta ON friend_relationships.user_id = user_meta.user_id
                    WHERE MATCH(user_meta.first_name, user_meta.last_name) AGAINST (? IN BOOLEAN MODE)
                    AND friend_relationships.follow_id = ?";
            $query = $this->db->query($query_string, array(str_replace(' ', '* ', $needle) . '*', $user->id));

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
        $this->notification_ops->notify($user_ids, $group_ids, $notif_type, $subject_id);

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
        $needle = trim($this->input->get('needle'));
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

        $query_string = "SELECT * FROM events WHERE title = ? AND date = ? AND time = ? AND place_id = ? AND title <> ''";
        $query = $this->db->query($query_string, array(
            $needle,
            $plan_date,
            $plan_time,
            $place_id
                ));

        if ($query->num_rows() > 0)
        {
// Pre-existing event
            echo("There's already an event with that title. Note that, because of privacy settings, the event may not actually be visible to you. Try another title.");
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
            'category_id' => NULL
        );

        $place_id = $this->place_ops->add_user_place($data);

        echo(json_encode(array('id' => $place_id, 'name' => $data['name'])));
    }

    public function make_plan_by_event()
    {
        $event_id = $this->input->get('event_id');
        $privacy = $this->input->get('privacy');

        $this->load->model('plan_actions');
        echo($this->plan_actions->add_plan(array(
            $this->ion_auth->get_user()->id,
            $event_id)));
    }

// Returns the place name and location of each plan on the same day
    public function get_plans_coords()
    {
        $plan_id = $this->input->get('plan_id');

        $this->load->model('plan_actions');
        echo($this->plan_actions->get_plan_coords($plan_id));
    }

// Returns HTML for an autocomplete box
    public function show_place_search()
    {
        ?>
        <div style="height: 10px; width: 100%"></div>
        <input type="text" id="search_for_places"/>

        <img src="/application/assets/images/Planjar_logo.png" style="margin-top: 17px;"/>
        <?php
    }

// Unsubscribe the user from all email notifications
    public function unsub($id)
    {
        $query_string = "SELECT user_id FROM unsubscribe WHERE alias = ?";
        $query = $this->db->query($query_string, array($id));
        if ($query->num_rows() > 0)
        {
// Get the user id
            $user_id = $query->row()->user_id;

// Remove all email settings
            $user = $this->ion_auth->get_user($id);
            $this->ion_auth->update_user($user_id, array(
                'follow_notif' => 0,
                'group_invite' => 0,
                'join_group_request' => 0,
                'event_invite' => 0));

            echo('You have been successfully unsubscribed.');
        } else
        {
            show_404();
        }
    }

    public function check_location_id()
    {
        $query = $this->db->query("SELECT id FROM places WHERE id = ?", array($this->input->get('id', true)));
        echo(($query->num_rows() > 0) ? 'success' : 'error');
    }

// Sends emails to the given people to join PlanJar
    public function invite_to_planjar()
    {
        $email_list = $this->input->get('email_list');

        if ($email_list != '')
        {
            $email_list = explode(',', $email_list);
            $this->load->library('email');
            $user = $this->ion_auth->get_user();
            $user_name = $user->first_name . ' ' . $user->last_name;

// Create the image string
            $this->load->model('load_profile');
            ob_start();
            $this->load_profile->insert_profile_picture($user->id, 33);
            $image = ob_get_clean();
            foreach ($email_list as $email)
            {
                if (!$this->ion_auth->username_check($email))
                {
                    $this->email->clear();
                    $this->email->from('noreply@planjar.com', 'PlanJar');
                    $this->email->to($email);
                    $this->email->subject("$user_name has invited you to PlanJar");
                    $this->email->message($this->load->view('invite_by_email_view', array(
                                'inviter' => $user_name,
                                'image' => $image), true));
                    $this->email->send();
                }
            }
        }
    }

    // The following 2 functions are to get and set the value of the tip_closed column of user_meta
    public function get_show_tip()
    {
        if ($this->ion_auth->get_user()->tip_closed == '0')
        {
            echo('show');
        }
    }

    public function set_show_tip()
    {
        $value = $this->input->get('value');
        $this->ion_auth->update_user($this->ion_auth->get_user()->id, array('tip_closed' => $value));
    }

}
?>
