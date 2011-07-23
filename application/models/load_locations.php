<?php

class Load_locations extends CI_Model
{

    function load_relevant_locations($selected_groups, $day, $user_id, $school)
    {
        // when the page first loads, the javascript can't get the attribute in time, so it is set to 0
        if (!$day)
        {
            $day = 0;
        }
        $display_day = $this->get_day($day); // shows the day selected in correct format

        if (!$selected_groups[0])
        {
            $this->on_nothing_selected($display_day);
        } else if ($selected_groups[0] == 'current_location')
        {
            $this->on_current_location_selected($display_day);
        } else if ($selected_groups[0] == 'friends')
        {
            $this->on_friends_selected($display_day);
        } else if ($selected_groups[0] == 'school')
        {
            $this->on_school_selected($display_day, $school);
        } else
        {
            $this->on_groups_selected($selected_groups);
        }
    }

    function on_nothing_selected($display_day)
    {
        echo "Use the left panel to select the type of information you want to see for $display_day<br/><hr/>";
    }

    function on_current_location_selected($display_day)
    {
        $user = $this->ion_auth->get_user();
        echo "Popular places near your <font style=\"color=blue;\">current location</font> for $display_day<br/><hr/>";
    }

    function on_friends_selected($display_day)
    {
        echo "Popular places your friends are attending $display_day<br/><hr/>";
        $friend_ids = $this->get_friend_ids();
    }

    function on_school_selected($display_day, $school)
    {
        $user = $this->ion_auth->get_user();
        echo "Popluar places $school students are attending $display_day<br/><hr/>";
    }

    function on_groups_selected($group_list)
    {

        echo "Popular places attended by  are selected";

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
            $id_array = $this->get_user_ids($user_id, $group_ids_selected, $id_array); // populate $id_array with the group member ids               
        }
    }

    // This function returns an array of friend user ids (if the friend tab is selected)
    function get_friend_ids()
    {
        $user = $this->ion_auth->get_user();
        $user_id = $user->id;
        $following_query = "SELECT follow_id FROM friends WHERE $user_id=user_id"; // selects all the people you are following
        $result = $this->db->query($following_query);
        
        $friend_query = "SELECT user_id FROM friends WHERE follow_id=$user_id AND (";
        foreach($result->result() as $following_id)
        {
            $friend_query .= "user_id=$following_id->follow_id OR ";
        }
        $friend_query = substr($friend_query, 0, -4);
        $friend_query .= ")";
        $query_result = $this->db->query($friend_query);
        $friend_ids = array();
        foreach($query_result->result() as $id)
        {
            $friend_ids[] = $id->user_id;
        }
        return $friend_ids;
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

    function get_day($day)
    {
        $date = new DateTime();
        $date->add(new DateInterval('P' . $day . 'D'));
        $display_day = $date->format('l');
        if ($day == 0)
        {
            $display_day .= " (today)";
        }
        return $display_day;
    }

}
?>
<script type="text/javascript" src="/application/assets/js/location_tabs.js"></script>