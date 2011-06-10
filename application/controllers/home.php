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
            // fill array with information about user events
            $home_events_data = $this->loadMyEvents();
           var_dump($home_events_data);
            
            $this->load->view('home_view', $home_events_data);
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

        $user_info = $this->ion_auth->get_user();
        $user_id = $user_info->id;
        $user_name = $user_info->username;

        $query = "SELECT plans.time_of_day, plans.date, places.name FROM plans LEFT JOIN places ON plans.place_id=places.id WHERE plans.user_id=?";

        $query_result = $this->db->query($query, array($user_id));
        $row = $query_result->row();

        var_dump($row);
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
            $like_clauses .= "places.name LIKE '%%$term%%' OR ";
        }
        $like_clauses = substr($like_clauses, 0, -4);

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
            echo('none');
        } else
        {
            echo(json_encode($return_array));
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

}

?>
