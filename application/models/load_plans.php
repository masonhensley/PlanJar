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
       "SELECT plans.id, plans.time_of_day, plans.plan_date, places.name, plan_categories.category
        FROM plans 
        LEFT JOIN places 
        ON plans.place_id=places.id 
        LEFT JOIN plan_categories
        ON plan_categories.id=plans.category_id
        WHERE plans.user_id=$user_id AND plans.plan_date >= CURDATE()
        ORDER BY plan_date ASC";

        // pull data
        $query_result = $this->db->query($query);
        $result = $query_result->result();
        return $result;
    }
    
    function loadPlanData($plan)
    {
        // pull all user's current events

        $query = "SELECT plans.id, plans.time_of_day, plans.plan_date, places.name, plan_categories.category
        FROM plans 
        LEFT JOIN places 
        ON plans.place_id=places.id 
        LEFT JOIN plan_categories
        ON plan_categories.id=plans.category_id
        WHERE plans.id=$plan";

        // pull data
        $query_result = $this->db->query($query);

        // initialize plan information
        $time_of_day;
        $date;
        $name;

        foreach ($query_result->result() as $row)
        {
            // populate variables
            $time_of_day = $row->time_of_day;
            // get rid of the "-"
            $time_of_day = str_replace("_", " ", $time_of_day);

            $date = $row->plan_date;
            $date = date('m/d', strtotime($date));
            $name = $row->name;
            $category = $row->category;
        }

        // html to replace the data div
        $htmlString = "
        <div><font color=\"purple\" size=\"30px\">
        $category at $name <br/>
        $time_of_day <br/>
        $date 

        </div>";

        return $htmlString;
    }
    
    function loadUserLocations($group_list, $day, $user_id)
    {
        // this converts the selected day to the equivalent sql representation
        $date = new DateTime();
        $date->add(new DateInterval('P' . $day . 'D'));
        $return_date = $date->format('Y-m-d');
        $index = 0;  // index used to access $group_list
            
        $condition_clause = "";
        $query = "";

        if (isset($group_list[0]))
        {
            $query .= "SELECT friends.user_id, friends.follow_id, groups.joined_users, plans.place_id, plans.plan_date, plans.time_of_day, plans.category_id
            FROM friends, groups
            LEFT JOIN plans";

            //plans.user_id=friends.follow_id OR groups.joined_users
            if (in_array("friends", $group_list))
            {
                $condition_clause .= " plans.user_id=friends.follow_id";
                if (count($group_list) > 1)
                {
                    $condition_clause .= " OR ";
                }
            }

            while (isset($group_list[$index]))
            {
                if ($group_list[$index] != "friends")
                {
                    $condition_clause .= "groups.id=" . $group_list[$index];
                    if (count($group_list) - 1 != $index)
                    {
                        $condition_clause .= " OR ";
                    }
                }
                $index++;
            }

            $query .= " ON $condition_clause
            LEFT JOIN places
            ON places.id=plans.place_id
            WHERE friends.user_id=$user_id
            AND $return_date=plans.plan_date";
        }
        
        return $query;
    }
}

?>
