<?php

class Load_locations extends CI_Model
{

    function load_relevant_locations($selected_groups, $day, $user_id)
    {
        $date;
        $return_date;
        $id_array = array();
        if ($day) // this converts the selected day to the equivalent sql representation
        {
            $date = new DateTime();
            $date->add(new DateInterval('P' . $day . 'D'));
            $return_date = $date->format('Y-m-d');
        }
        var_dump($selected_groups, $day);
        
        // handle
        if (!$selected_groups[0])
        {
            $this->on_nothing_selected();
        } else if ($selected_groups[0] == 'current_location')
        {
            $this->on_current_location_selected();
        } else if ($selected_groups[0] == 'friends')
        {
            $this->on_friends_selected();
        } else
        {
            $this->on_groups_selected($selected_groups);
        }
    }

    function on_nothing_selected()
    {
        echo "no one is selected";
    }

    function on_current_location_selected()
    {
        $user = $this->ion_auth->get_user();
        
        echo "Most popular locations near lat:$user->latitude lon:$user->longitude<br/>";
    }

    function on_friends_selected()
    {
        echo "friends tab is selected";
    }

    function on_groups_selected($group_list)
    {

        echo "groups are selected";
        $id_array = array(); // an array of all the user ids that will be included in the pull
        $index = 0;  // index used to access $group_list
        $group_ids_selected = array();
        while (isset($group_list[$index]))
        {
            $group_ids_selected[] = $group_list[$index]; // populates an array of selected group ids
            $index++;
        }

        // if there are groups selected, generate a query to pull all user ids joined in the selected groups
        $index = 0; // reinitialize index
        $user_ids = null;
        $id_array;
        if (isset($group_ids_selected[$index]))
        {
            //$id_array = $this->get_user_ids($user_id, $group_ids_selected, $id_array); // populate $id_array with the group member ids               
        }

        if (isset($id_array[0]))
        {
            //$location_ids = $this->get_evaluated_plans($id_array, $return_date);  // populate $location_ids with relevent locations
            //$this->load_tabs($location_ids, $id_array, $return_date);
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

    // this function updates the $id_array with members joined in groups selected
    function get_user_ids($user_id, $group_ids_selected, $id_array)
    {
        $group_query = "SELECT user_joined_id FROM group_relationships WHERE";
        foreach ($group_ids_selected as $id)
        {
            $group_query .= " group_id=$id OR";
        }
        $group_query = substr($group_query, 0, strlen($group_query) - 3);  // trim off the last "OR" before querying
        $query_result = $this->db->query($group_query);

        // generate the list of user ids from the
        foreach ($query_result->result() as $row)
        {
            if ($row->user_joined_id != $user_id)
            {
                if (isset($row->user_joined_id))
                {
                    $id_array[] = $row->user_joined_id;
                }
            }
        }
        return $id_array;
    }

    // this function takes $id_aray and pulls all relevent plans for the specified day
    function get_evaluated_plans($id_array, $return_date)
    {
        // generate query to pull relevant locations for the groups selected
        // this query will select all plans that user ids contained in $id_array have for the specified day
        $plan_query = "SELECT plans.place_id, plans.user_id, plans.date, plans.time_of_day, plans.title, plans.event_id, places.id, places.name
                FROM plans
                LEFT JOIN places ON plans.place_id=places.id
                WHERE plans.date='$return_date' AND (";

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
        return $location_ids;
    }

    function load_tabs($location_ids, $id_array, $return_date)
    {
        $plan_tracker = 1; // keeps track of what plan number is being used
        $friend_count = 0;

        foreach ($location_ids as $id => $plan) // for each location, figure out how many of the people in $id_array are going (acquaintances)
        {
            $number_of_friends_query = "SELECT user_id, place_id FROM plans WHERE (";
            foreach ($id_array as $ids)
            {
                $number_of_friends_query .= "user_id=$ids OR "; // contsruct the "or" clauses to check all user ids for everything selected
            }
            $number_of_friends_query = substr($number_of_friends_query, 0, strlen($number_of_friends_query) - 4); // This cuts off the last "OR" and adds ")"
            $number_of_friends_query .= ")";
            $number_of_friends_query .= " AND place_id=$id AND date='$return_date'";

            $result = $this->db->query($number_of_friends_query);
            $count = $result->num_rows();
            ?>
            <div class = "location_tab_shown" place_id="<?php echo $id; ?>" date="<?php echo $return_date; ?>">
                <div id="number_rank">
                    <?php echo $plan_tracker;
                    $plan_tracker++; ?></div><?php
            echo $plan;
            echo "<br/>$count people in selected groups are attending";
                    ?>
            </div>
            <?php
        }
    }

}
?>
<script type="text/javascript" src="/application/assets/js/location_tabs.js"></script>