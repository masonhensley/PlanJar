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
            $home_view_data = loadMyEvents();
            
            $this->load->view('home_view', $home_view_data);
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

    // Checks the PlanJar Places database for matching places.
    // If none are found, check Google. Returns error otherwise.
    public function find_places()
    {
        include(APPPATH . 'assets/js/parker.php');
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
    
    function loadMyEvents()
    {
        $this->load->database();
        
        $user_info = $this->ion_auth->get_user();
        $user_id = $user_info->id;
        $user_name = $user_info->username;
       
        $query="SELECT plans.time_of_day, plans.date, places.name FROM plans LEFT JOIN places ON plans.place_id=places.place_id WHERE plans.user_id=?";
        
        $query_result = $this->db->query($query, array($user_id));
        $row = $query_result->row();
        
       return $row;
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
