<?php

class Load_events extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getPlans($user_id)
    {
        $this->load->database();

        // pull all user's current events
        $query = 
       "SELECT plans.time_of_day, plans.date, places.name 
        FROM plans 
        LEFT JOIN places 
        ON plans.place_id=places.id 
        WHERE plans.user_id=$user_id";

        // pull data
        $query_result = $this->db->query($query);
        $row = $query_result->result();

        return $row;
    }
}

?>
