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
            $term = $this->db->escape_like_str($term);
            $like_clauses .= "`name` LIKE '%%$term%%' OR ";
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
            $query_string = "SELECT `category` FROM `poi_categories` WHERE `id` = ? LIMIT 1";
            $sub_query = $this->db->query($query_string, array($row['category']));
            $sub_row = $sub_query->row_array();
            $row['category'] = $sub_row['category'];

            // Append to the return array.
            $return_array[] = $row;
        }

        // Check for no results.
        if (!isset($return_array))
        {
            // Search the Yahoo API.
            $data = array(
            'location' => $latitude . ' ' . $longitude,
                'name' => 'needle'
            );
            $response = http_get('https://maps.googleapis.com/maps/api/place/search/json', array('timeout' => 2), $data);
            echo($response);
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
        $query_string = "SELECT `id`, `category` FROM `plan_categories` WHERE $like_clauses LIMIT 10";
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

}

?>