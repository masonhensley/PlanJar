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
            $this->load->view('home_view');
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

    // Checks the PlanJar POI database for matching POIs.
    // If none are found, checks Yahoo. Returns error otherwise.
    public function find_pois()
    {
        $this->load->database();

        $needle = $this->input->get('needle');
        $search_terms = explode(' ', $needle);

        $latitude = $this->input->get('latitude');
        $longitude = $this->input->get('longitude');

        $like_clauses = '';
        foreach ($search_terms as $term)
        {
            $like_clauses .= "`name` LIKE '%%" . $term . "%%' OR ";
        }
        $like_clauses = substr($like_clauses, 0, -4);

        // Check the PlanJar database. (Query string courtesy of Wells.)
        $query_string = "SELECT id, ((ACOS(SIN(? * PI() / 180) * SIN(`latitude` * PI() / 180) 
  + COS(? * PI() / 180) * COS(`latitude` * PI() / 180) * COS((? - `longitude`) 
  * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance, name, category 
  FROM `pois` WHERE ($like_clauses) ORDER BY distance ASC LIMIT ?";
        $query = $this->db->query($query_string, array($latitude, $latitude, $longitude, 10));

        // Return a JSON array.
        foreach ($query->result_array() as $row)
        {
            // Replace each category id with the name of the category.
            $query_string = "SELECT `category` FROM `poi_categories` WHERE `id` = ?";
            $sub_query = $this->db->query($query_string, $row['category']);
            $sub_row = $sub_query->result_array();
            //$row['category'] = $sub_row['category'];
            
            // Append to the return array.
            $return_array[] = $row;
            
            echo($row['category']);
            return;
        }

        // Check for no results.
        if (!isset($return_array))
        {
            echo($this->db->last_query());
        } else
        {
            echo(json_encode($return_array));
        }
    }

}

?>