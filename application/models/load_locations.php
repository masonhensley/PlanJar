<?php

class Load_locations extends CI_Model
{

    function loadUserLocations($group_list, $day, $user_id)
    {
        // this converts the selected day to the equivalent sql representation
        $date = new DateTime();
        $date->add(new DateInterval('P' . $day . 'D'));
        $return_date = $date->format('Y-m-d');
        $index = 0;  // index used to access $group_list
        $id_array = array(); // an array of all the user ids that will be included in the pull

        if (isset($group_list[0])) //  group_list is a list of group ids selected
        {
            // first get a list of ids to find plans with and append it to the id_array
            if (in_array("friends", $group_list))
            {
               $id_array = $this->get_friend_ids($user_id, $id_array); // adds user ids to $id_array
            }

            // next generate the query for a list of ids for all the people in the groups selected
            $group_ids_selected = array();
            while (isset($group_list[$index]))
            {
                if ($group_list[$index] != "friends") // ignore the friends tab as it is already dealt with
                {
                    $group_ids_selected[] = $group_list[$index]; // populate an array of selected group ids
                }
                $index++;
            }        
            
            // if there are groups selected, generate a query to pull all user ids joined in the selected groups
            $index = 0; // reinitialize index
            $user_ids = null;
            if (isset($group_ids_selected[$index]))
            {
                $id_array = $this->get_user_ids($group_ids_selected, $id_array);
                
            }

            // generate query to pull relevant locations for the groups selected
            $plan_query = "SELECT plans.place_id, plans.user_id, plans.plan_date, plans.time_of_day, plans.category_id, places.id, places.name
                FROM plans
                LEFT JOIN places ON plans.place_id=places.id
                WHERE plans.plan_date='$return_date' AND (";

            foreach ($id_array as $id)
            {
                $plan_query .= "plans.user_id=$id OR "; // contsruct the "or" clauses to check all user ids for everything selected
            }

            $plan_query = substr($plan_query, 0, strlen($plan_query) - 4); // This cuts off the last "OR" and adds ")"
            $plan_query .= ")";
            $evaluated_plans = $this->db->query($plan_query);
            $evaluated_plans = $evaluated_plans->result();

            $location_ids = array();  // Use this variable to store the location ids that are shown to prevent duplicates
            foreach ($evaluated_plans as $plan)
            {
                if (!in_array($plan->place_id, $location_ids))
                {
                    $location_ids["$plan->place_id"] = "$plan->name";
                }
            }

            $plan_tracker = 1; // keeps track of what plan number
            $friend_count = 0;
            foreach ($location_ids as $id => $plan)
            {
                $number_of_friends_query = "SELECT user_id, place_id FROM plans WHERE (";
                foreach ($id_array as $ids)
                {
                    $number_of_friends_query .= "user_id=$ids OR "; // contsruct the "or" clauses to check all user ids for everything selected
                }
                $number_of_friends_query = substr($number_of_friends_query, 0, strlen($number_of_friends_query) - 4); // This cuts off the last "OR" and adds ")"
                $number_of_friends_query .= ")";
                $number_of_friends_query .= " AND place_id=$id AND plan_date='$return_date'";
                $result = $this->db->query($number_of_friends_query);
                $count = $result->num_rows();
                ?>
                <div class = "plan_shown"><div id="number_rank" style="border: 1px solid black; border-left: none; float:left; width:15px; height:100%; text-align: center">
                        <?php echo $plan_tracker;
                        $plan_tracker++; ?></div><?php
                echo "<hr/>";
                echo $plan;
                echo "<br/>$count attending";
                echo "<br/><hr/>";
                        ?>
                </div>
                <?php
            }
        }
    }

    // This function returns an array of friend user ids (if the friend tab is selected)
    function get_friend_ids($user_id, $id_array)
    {
        $return_id_array = $id_array;
        $friend_query = "SELECT follow_id FROM friends WHERE user_id=$user_id";
        $query_result = $this->db->query($friend_query);
        foreach ($query_result->result() as $row)
        {
            $return_id_array[] = $row->follow_id;
        }
        return $return_id_array;
    }
    
    function get_user_ids($group_ids_selected, $id_array)
    {
        $group_query = "SELECT user_joined_id FROM group_relationships WHERE";
        foreach($group_ids_selected as $id)
        {
            $group_query .= " group_id=$id OR";
        }
        $group_query = substr($group_query, 0, strlen($group_query) - 4);  // trim off the last "OR" before querying
        $query_result = $this->db->query($group_query);
        
        // generate the list of user ids from the
        foreach($query_result->result() as $row)
        {
            $id_array[] = $row->user_joined_id;
        }
        
         return $id_array;
    }
}
?>
