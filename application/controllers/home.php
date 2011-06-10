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
        $query_string = "SELECT ((ACOS(SIN(? * PI() / 180) * SIN(`latitude` * PI() / 180) 
  + COS(? * PI() / 180) * COS(`latitude` * PI() / 180) * COS((? - `longitude`) 
  * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance, name, category 
  FROM `pois` WHERE (?) ORDER BY distance ASC LIMIT ?";
        $query = $this->db->query($query_string, array($latitude, $latitude, $longitude, $like_clauses, 10));
        
        // Return a JSON array.
        $return_array = array();
        foreach ($query->result() as $row) {
            $return_array[] = $row;
        }
        
        echo('results: ' . json_encode($return_array));
        
    }

}

?>