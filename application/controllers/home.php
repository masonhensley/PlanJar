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
            // load plans by id
            $this->load->model('load_plans');
            $user_info = $this->ion_auth->get_user();
            $user_id = $user_info->id;
            $result = $this->load_plans->getPlans($user_id);

            // Lookup the groups by id.
            $this->load->model('load_groups');
            $joined_groups = $this->load_groups->get_groups(json_decode($user_info->joined_groups));
            $followed_groups = $this->load_groups->get_groups(json_decode($user_info->followed_groups));

            // Pass the necessary information to the view.
            $this->load->view('home_view', array(
                'result' => $result,
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
                "SELECT plans.time_of_day, plans.date, places.name 
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
  * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance, places.name, place_categories.category 
  FROM places LEFT JOIN place_categories ON places.category_id=place_categories.id
        WHERE ($like_clauses) ORDER BY distance ASC LIMIT ?";
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
            echo('error');
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

    public function get_location_by_id()
    {
        $this->load->database();
        $query_string = "SELECT name FROM places WHERE id = ? LIMIT 1";
        $query = $this->db->query($query_string, array($this->input->get('id')));

        if ($query->num_rows() == 0)
        {
            // Return an error if no entries come up.
            echo('error');
        } else
        {
            // Return the first result.
            $row = $query->row_array();
            echo($row['name']);
        }
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
            'date' => $date->format('Y-m-d'),
            'time_of_day' => $this->input->get('plan_time_group'),
            'category_id' => $this->input->get('plan_category_id')
        );

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

    public function get_plan_data()
    {
        $this->load->database();
        $plan = $this->input->get('plan_selected');

        // pull all user's current events

        $query =
                "SELECT plans.id, plans.time_of_day, plans.date, places.name, plan_categories.category
        FROM plans 
        LEFT JOIN places 
        ON plans.place_id=places.id 
        LEFT JOIN plan_categories
        ON plan_categories.id=plans.category_id
        WHERE plans.id=$plan";

        // pull data
        $query_result = $this->db->query($query);
        $result = $query_result->result();
        echo $result->name;
    }

    // Return a list of plans visible to the user.
    public function get_visible_plans()
    {
        $this->load->database();

        // Get a list of users based on the selected groups.
        $user = $this->ion_auth->get_user();
        $user_list = $this->input->get('selected_groups');
        if ($user_list)
        {
            $friends_key = array_search('friends', $user_list);
            if ($friends_key !== false)
            {
                unset($user_list[$friends_key]);
                $user_list = array_merge($user_list, json_decode($user->following));
            }
        }

        echo(var_dump($user_list));
    }

}

?>
