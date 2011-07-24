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
        $date = new DateTime();
        $sql_date = $date->add(new DateInterval('P' . $day . 'D')); // date to be used in sql queries
        $sql_date = $sql_date->format('Y-m-d');

        $display_day = "<font style=\"font-weight:bold\">$display_day</font>";
        if (!$selected_groups[0])
        {
            $this->on_nothing_selected($display_day);
        } else if ($selected_groups[0] == 'current_location')
        {
            $this->on_current_location_selected($display_day, $sql_date);
        } else if ($selected_groups[0] == 'friends')
        {
            $this->on_friends_selected($display_day, $sql_date);
        } else if ($selected_groups[0] == 'school')
        {
            $this->on_school_selected($display_day, $sql_date, $school);
        } else
        {
            $this->on_groups_selected($selected_groups);
        }
    }

    function on_nothing_selected($display_day)
    {
        echo "Use the <font style=\"color:navy; font-weight:bold;\">group panel</font> to select the type of information you want to see for $display_day<br/><hr/>";
    }

    function on_current_location_selected($display_day, $sql_date)
    {
        $user = $this->ion_auth->get_user();
        echo "Popular places near your <font style=\"color=blue;\">current location</font> for $display_day<br/><hr/>";

        $query = "SELECT places.id, places.name, places.category, 
                  ((ACOS(SIN($user->latitude * PI() / 180) * SIN(places.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(places.latitude * PI() / 180) * COS(($user->longitude - places.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance
                  FROM events
                  LEFT JOIN places ON places.id=events.place_id 
                  WHERE events.date='$sql_date'
                  ORDER BY distance ASC";
        $result = $this->db->query($query);
        $place_id_array = array();
        $place_name_array = array();
        foreach ($result->result() as $place)
        {
            $place_id_array[] = $place->id;
            $place_name_array[] = $place->name;
        }
        $this->display_location_tabs($place_id_array, $place_name_array);
    }

    function on_friends_selected($display_day, $sql_date)
    {
        echo "Popular places your friends are going $display_day<br/><hr/>";
        $friend_ids = $this->get_friend_ids(); // get an array of friend ids
        $query = "SELECT events.id, events.title, places.name FROM plans 
                  JOIN events ON plans.event_id=events.id AND events.date='$sql_date'
                  LEFT JOIN places ON events.place_id=places.id
                  WHERE (";
        foreach ($friend_ids as $id)
        {
            $query .= "plans.user_id=$id OR ";
        }
        $query = substr($query, 0, -4);
        $query .= ")";
        $result = $this->db->query($query);
        $place_id_array = array();
        $place_name_array = array();
        foreach ($result->result() as $place)
        {
            $place_id_array[] = $place->id;
            $place_name_array = $place->name;
        }
        $this->display_location_tabs($place_id_array, $place_name_array);
    }

    function on_school_selected($display_day, $sql_date, $school)
    {
        $user = $this->ion_auth->get_user();
        $school_id = $user->school_id;
        echo "Popluar places $school students are going $display_day<br/><hr/>";

        $query = "SELECT events.title, places.name, places.id 
                  FROM user_meta
                  LEFT JOIN plans ON plans.user_id=user_meta.user_id
                  JOIN events ON plans.event_id=events.id AND events.date='$sql_date'
                  JOIN places ON places.id=events.place_id
                  WHERE user_meta.school_id=$school_id";
        $result = $this->db->query($query);
        $place_name_array = array();
        $place_id_array = array();
        foreach ($result->result() as $place)
        {
            $place_id_array[] = $place->id;
            $place_name_array[] = $place->name;
        }
        $this->display_location_tabs($place_id_array, $place_name_array);
    }

    function on_groups_selected($group_list)
    {
        $group_name_array = $this->get_group_names($group_list);
        $header_string = $this->setup_groups_header($group_name_array);
        

       
        echo $header_string;
        $query = "";

        /*
          if (isset($group_ids_selected[$index]))
          {
          $id_array = $this->get_user_ids($user_id, $group_ids_selected, $id_array); // populate $id_array with the group member ids
          }
         * 
         */
    }

    // This function returns an array of friend user ids (if the friend tab is selected)
    function get_friend_ids()
    {
        $user = $this->ion_auth->get_user();
        $user_id = $user->id;
        $following_query = "SELECT follow_id FROM friends WHERE $user_id=user_id"; // selects all the people you are following
        $result = $this->db->query($following_query);

        $friend_query = "SELECT user_id FROM friends WHERE follow_id=$user_id AND (";
        foreach ($result->result() as $following_id)
        {
            $friend_query .= "user_id=$following_id->follow_id OR ";
        }
        $friend_query = substr($friend_query, 0, -4);
        $friend_query .= ")";
        $query_result = $this->db->query($friend_query);
        $friend_ids = array();
        foreach ($query_result->result() as $id)
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
    
    function setup_groups_header($group_name_array)
    {
        var_dump($group_name_array, count($group_name_array));
        
        $header_string = "Popular places people from ";
         if (count($group_name_array) == 1)
        {
            $header_string .= $group_name_array[0];
        } else if (count($group_name_array == 2))
        {
            $header_string .= $group_name_array[0] . " and " . $group_name_array[1];
        } else if (count($group_name_array) > 2)
        {
            $index = 0;
            foreach ($group_name_array as $group_name)
            {
                if (isset($group_name_array[$index + 1]))
                {
                    $header_string .= "$group_name, ";
                }else{
                    $header_string .= "and $group_name";
                }
                $index++;
            }
        }
        $header_string .= " are going";
        return $header_string;
    }

    function get_group_names($group_list)
    {
        $group_name_array = array();
        $query = "SELECT name FROM groups WHERE ";
        foreach ($group_list as $group_id)
        {
            $query .= "id=$group_id OR ";
        }
        $query = substr($query, 0, -4);
        $result = $this->db->query($query);
        foreach ($result->result() as $group_name)
        {
            $group_list[] = $group_name->name;
        }
        return $group_list;
    }

    function display_location_tabs($place_id_array, $place_name_array)
    {
        var_dump($place_name_array);
    }

}
?>
<script type="text/javascript" src="/application/assets/js/location_tabs.js"></script>