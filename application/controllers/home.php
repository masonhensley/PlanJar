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
            $user_info = $this->ion_auth->get_user();

            // retrieve other useful variables for view
            $firstname = $user_info->first_name;
            $lastname = $user_info->last_name;

            // Lookup the groups by id.
            $this->load->model('load_groups');
            $joined_groups = $this->load_groups->get_groups(json_decode($user_info->joined_groups));
            $followed_groups = $this->load_groups->get_groups(json_decode($user_info->followed_groups));

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

    // load and return user event data
    public function loadMyEvents()
    {
        $this->load->database();

        // get user info from ion_auth
        $user_info = $this->ion_auth->get_user();
        $user_id = $user_info->id;

        // pull all user's current events
        $query =
                "SELECT plans.time_of_day, plans.plan_date, places.name 
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

    public function foo2()
    {
        $this->load->view('foo2_view');
    }

    public function foo3()
    {
        $this->load->view('foo3_view');
    }

    // Adds a plan entry to the database.
    public function submit_plan()
    {
        $this->load->database();
        $user = $this->ion_auth->get_user();
        $date = new DateTime();
        $date->add(new DateInterval('P' . $this->input->get('plan_day_group') . 'D'));

        $data = array(
            'id' => 'DEFAULT',
            'place_id' => $this->input->get('plan_location_id'),
            'user_id' => $user->id,
            'plan_date' => $date->format('Y-m-d'),
            'time_of_day' => $this->input->get('plan_time_group'),
            'category_id' => $this->input->get('plan_category_id')
        );
        
        // Add the place to the database if a Factual place was selected.
        if ($this->input->get('new_place_name') != '')
        {
            $query_string = "INSERT INTO places VALUES (DEFAULT, ?, ?, ?, ?)";
            $query = $this->db->query($query_string, array(
                        $this->input->get('new_place_name'),
                        $this->input->get('new_place_latitude'),
                        $this->input->get('new_place_longitude'),
                        $this->input->get('new_place_category')
                    ));

            // Overwrite the place id with the new place.
            $data['place_id'] = $this->db->insert_id();
        }

        $query = $this->db->insert('plans', $data);

        if ($query)
        {
            echo('success');
        } else
        {
            echo('error');
        }
    }

    // Returns chart data based on the selected groups and day
    public function get_group_day_data()
    {
        echo('<p>selected groups ^^' .
        var_dump($this->input->get('selected_groups')) .
        '</p><p>selected day: ' .
        $this->input->get('selected_day') .
        '</p>');
    }

    public function load_selected_plan_data()
    {
        $this->load->database();
        $plan = $this->input->get('plan_selected');
        $this->load->model('load_plans');
        $returnHtml = $this->load_plans->loadPlanData($plan);
        echo $returnHtml;
    }

    // Return a list of plans visible to the user.
    // This code is sweet
    // called from "visible_plans_functions.js"
    public function load_popular_locations()
    {
        $this->load->database();

        // this contains a list of ids for the groups selected
        $group_list = $this->input->get('selected_groups');
        $day = $this->input->get('selected_day');
        $user_id = $this->ion_auth->get_user()->id;
        
        $this->load->model('load_plans');
        $query = $this->load_plans->loadUserLocations($group_list, $day, $user_id);
        
        echo $query;
        echo "<hr/>";
        $query_result = $this->db->query($query);
        var_dump($query_result);
        
    }

    // Returns HTML for the list of the user's plans (right panel)
    public function get_my_plans()
    {
        $this->load->model('load_plans');
        $user_info = $this->ion_auth->get_user();
        $user_id = $user_info->id;
        $result = $this->load_plans->getPlans($user_id);
        ?>

        <ul class="active_plans">
            <?php
            foreach ($result as $plan)
            {
                // make easy to read variables
                $id = $plan->id;
                $name = $plan->name;
                $category = $plan->category;
                $time = $plan->time_of_day;
                $date_string1 = date('D', strtotime($plan->plan_date));
                ?> 

                <li class ="plan_content" plan_id="<?php echo $id; ?>" >
                    <?php echo $name . "  |  " . $date_string1; ?>
                </li>
            <?php } ?>
        </ul> <?php
    }

    // Updates the user's location
    public function update_user_location()
    {
        $new_lat = $this->input->get('latitude');
        $new_long = $this->input->get('longitude');

        $user = $this->ion_auth->get_user();

        $delta_distance = $this->_get_distance_between($user->latitude, $user->longitude, $new_lat, $new_long);
        if ($delta_distance > 10)
        {
            echo('prompt new location');
        } else
        {
            $result = $this->ion_auth->update_user($user->id, array(
                        'latitude' => $new_lat,
                        'longitude' => $new_long
                    ));
            if ($result)
            {
                echo('success');
            } else
            {
                echo('failed to update user location in profile');
            }
        }
    }

    private function _get_distance_between($lat0, $long0, $lat1, $long1)
    {
        return ((acos(sin($lat0 * pi() / 180) * sin($lat1 * pi() / 180)
                + cos($lat0 * pi() / 180) * cos($lat1 * pi() / 180) * cos(($long0 - $long1)
                        * pi() / 180)) * 180 / pi()) * 60 * 1.1515);
    }

}
    ?>
