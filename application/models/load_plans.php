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
        <div><font color=\"purple\" size=\"15px\">
        $category at $name <br/>
        $time_of_day <br/>
        $date </div>";

        return $htmlString;
    }

    function loadUserLocations($group_list, $day, $user_id)
    {
        // this converts the selected day to the equivalent sql representation
        $date = new DateTime();
        $date->add(new DateInterval('P' . $day . 'D'));
        $return_date = $date->format('Y-m-d');
        $index = 0;  // index used to access $group_list
        $id_array = array(); // an array of all the user ids that will be included in the pull
        
        if (isset($group_list[0]))
        {
            // first get a list of ids to find plans with and append it to the id_array
            if (in_array("friends", $group_list))
            {
                $friend_query = "SELECT follow_id FROM friends WHERE user_id=$user_id";
                $query_result = $this->db->query($friend_query);
                foreach ($query_result->result() as $row)
                {
                    $id_array[] = $row->follow_id;
                }
            }
            // next generate the query for a list of ids for all the people in the groups selected
            $group_ids_selected = array();
            while (isset($group_list[$index]))
            {
                if ($group_list[$index] != "friends")
                {
                    $group_ids_selected[] = $group_list[$index];
                }
                $index++;
            }

            $index = 0; // reinitialize index
            if (isset($group_ids_selected[$index]))
            {
                $group_query = "SELECT joined_users FROM groups WHERE ";
                while (isset($group_ids_selected[$index]))
                {
                    $group_query .= "id=$group_ids_selected[$index]";
                    if (count($group_ids_selected) - 1 != $index)
                    {
                        $group_query .= " OR ";
                    }
                    $index++;
                }
                $query_result = $this->db->query($group_query);
                var_dump($group_query);
                foreach($query_result->result() as $row)
                {
                    $row = json_decode($row->joined_users);
                    var_dump($row);
                }
            }
        }
        //var_dump($id_array);
    }
}
?>
