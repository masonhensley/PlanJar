<?php

class Place_ops extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Accepts an associative array of data to create a place
    // Returns the PlanJar place id
    function add_factual_place($data)
    {
        // If the Factual ID is already in the database, return the ID of that entry instead of creating a new place.
        $query_string = "SELECT id FROM places WHERE factual_id = ?";
        $query = $this->db->query($query_string, array($data['factual_id']));
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->id;
        } else
        {
            // Get the category id
            $query_string = "SELECT id FROM place_categories WHERE category = ?";
            $query = $this->db->query($query_string, array($data['category']));

            // Add the new place.
            unset($data['category']);
            $data['category_id'] = $query->row()->id;
            $query = $this->db->insert('places', $data);

            return $this->db->insert_id();
        }
    }

    // Adds a place created by the user
    // Returns the place id
    function add_user_place($data)
    {
        $query = $this->db->insert('places', $data);
        
        return $this->db->insert_id();
    }

}

?>
