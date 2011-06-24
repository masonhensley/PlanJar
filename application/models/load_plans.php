<?php

class Load_plans extends CI_Model
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
       "SELECT plans.id, plans.time_of_day, plans.date, places.name, plan_categories.category
        FROM plans 
        LEFT JOIN places 
        ON plans.place_id=places.id 
        LEFT JOIN plan_categories
        ON plan_categories.id=plans.category_id
        WHERE plans.user_id=$user_id AND plans.date >= CURDATE()
        ORDER BY date ASC";

        // pull data
        $query_result = $this->db->query($query);
        $result = $query_result->result();
        return $result;
    }
}

?>
