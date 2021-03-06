<?php

class Load_locations extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

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

        $display_day = "<font style=\"font-weight:bold;\">$display_day</font>"; // left this for convenience
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
            $this->on_groups_selected($selected_groups, $sql_date, $display_day);
        }
    }

    function on_nothing_selected($display_day)
    {
        ob_start();
        ?>
        <div class="display_message">
            <font style="color:gray;">Select a group or network on the left<br/>
            to see where they are going</font>.
        </div>
        <?php
        echo(json_encode(array(
            'html' => ob_get_clean(),
            'coords_array' => array()
        )));
    }

    function on_current_location_selected($display_day, $sql_date)
    {
        $user = $this->ion_auth->get_user();
        $display_message = "<font style=\"color:gray;\">Popular <a href=\"#\" id=\"places_link\">places</a> near your</font> <font style=\"color:green;\">Current Location</font> ";
        $display_message .= "for <font style=\"font-weight:bold;\">$display_day</font>";


        // query to pull all plans from people within 15 miles from your current location
        $query = "SELECT DISTINCT places.id, places.name, events.title, places.latitude, places.longitude, plans.id AS plan_id, events.id AS event_id
            FROM (SELECT user_id, ((ACOS(SIN($user->latitude * PI() / 180) * SIN(user_meta.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(user_meta.latitude * PI() / 180) * COS(($user->longitude - user_meta.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance FROM user_meta HAVING distance < 15)new_users
                JOIN plans ON new_users.user_id=plans.user_id
                JOIN events ON plans.event_id=events.id AND events.date='$sql_date'
                JOIN places ON events.place_id=places.id";


        $result = $this->db->query($query);

        $place_array = array();
        $place_id_array = array();
        foreach ($result->result() as $place)
        {
            $place_array[$place->id] = array($place->name, $place->latitude, $place->longitude);
            $place_id_array[] = $place->id;
        }
        $this->display_location_tabs($display_message, $place_id_array, $place_array);
    }

    function on_friends_selected($display_day, $sql_date)
    {
        $friend_ids = $this->get_friend_ids(); // get an array of friend ids
        $place_id_array = array();
        $place_array = array();

        if (count($friend_ids) > 0)
        {
            $display_message = "Popular <a href=\"#\" id=\"places_link\">places</a> your <font style=\"color:green;\">Friends</font> ";
            $display_message .= "are going <br/><font style=\"font-weight:bold;\">$display_day</font>";

            $query = "SELECT DISTINCT places.id, events.title, places.name, places.latitude, places.longitude, plans.id AS plan_id, events.id AS event_id
                  FROM plans 
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



            foreach ($result->result() as $place)
            {
                $place_array[$place->id] = array($place->name, $place->latitude, $place->longitude);
                $place_id_array[] = $place->id;
            }
        } else
        {
            $display_message = "You don't have any <a href=\"/dashboard/friends\" class=\"find_link\">friends</a> yet";
        }

        $this->display_location_tabs($display_message, $place_id_array, $place_array);
    }

    function on_school_selected($display_day, $sql_date, $school)
    {
        $user = $this->ion_auth->get_user();
        $school_id = $user->school_id;
        $display_message = "Popular <a href=\"#\" id=\"places_link\">places</a> <font style=\"color:green;\">$school</font> ";
        $display_message .= "students are going <font style=\"font-weight:bold;\">$display_day</font>";

        $query = "SELECT DISTINCT events.title, places.name, places.id, places.latitude, places.longitude, plans.id AS plan_id, events.id AS event_id
                  FROM user_meta
                  JOIN plans ON plans.user_id=user_meta.user_id
                  LEFT JOIN events ON plans.event_id=events.id AND events.date='$sql_date'
                  JOIN places ON places.id=events.place_id
                  WHERE user_meta.school_id=$school_id";
        $result = $this->db->query($query);

        $place_array = array();
        $place_id_array = array();
        foreach ($result->result() as $place)
        {
            $place_array[$place->id] = array($place->name, $place->latitude, $place->longitude);
            $place_id_array[] = $place->id;
        }
        $this->display_location_tabs($display_message, $place_id_array, $place_array);
    }

    function on_groups_selected($group_list, $sql_date, $display_day)
    {
        $group_name_array = $this->get_group_names($group_list);
        $display_message = $this->setup_groups_header($group_name_array, $display_day);
        $query_helper = "";

        foreach ($group_list as $group_id)
        {
            $query_helper .= "group_relationships.group_id=$group_id OR ";
        }
        $query_helper = substr($query_helper, 0, -4);

        $query = "
            SELECT DISTINCT places.name, places.id AS place_id, events.title, plans.id, places.latitude, places.longitude, events.id AS an_event_id
            FROM 
                (SELECT user_meta.user_id FROM group_relationships 
                JOIN user_meta 
                ON group_relationships.user_joined_id=user_meta.user_id
                WHERE $query_helper)new_user
            JOIN plans ON plans.user_id=new_user.user_id
            JOIN events ON plans.event_id=events.id AND events.date='$sql_date'
            JOIN places ON places.id=events.place_id
            ";

        $query = substr($query, 0, -4);
        $result = $this->db->query($query);

        $place_array = array();
        $place_id_array = array();
        foreach ($result->result() as $place)
        {
            $place_array[$place->place_id] = array($place->name, $place->latitude, $place->longitude);
            $place_id_array[] = $place->place_id;
        }

        $this->display_location_tabs($display_message, $place_id_array, $place_array);
    }

    // This function returns an array of friend user ids (if the friend tab is selected)
    function get_friend_ids($limit_to_active = false)
    {
        $user = $this->ion_auth->get_user();
        $user_id = $user->id;
        if (!$limit_to_active)
        {
            $following_query = "SELECT follow_id FROM friend_relationships WHERE user_id = $user_id"; // selects all the people you are following
        } else
        {
            $following_query = "SELECT DISTINCT friend_relationships.follow_id FROM friend_relationships
                    JOIN plans ON friend_relationships.follow_id = plans.user_id
                    JOIN events ON plans.event_id = events.id AND events.date >= CURDATE()
                    WHERE friend_relationships.user_id = $user_id";
        }
        $result = $this->db->query($following_query);

        $friend_query = "SELECT user_id FROM friend_relationships WHERE follow_id=$user_id AND (";
        foreach ($result->result() as $following_id)
        {
            $friend_query .= "user_id=$following_id->follow_id OR ";
        }

        if ($result->num_rows() > 0)
        {
            $friend_query = substr($friend_query, 0, -4);
        } else
        {
            $friend_query .= "0";
        }
        $friend_query .= ")";

        $query_result = $this->db->query($friend_query);
        $friend_ids = array();
        foreach ($query_result->result() as $id)
        {
            $friend_ids[] = $id->user_id;
        }
        return $friend_ids;
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

    function setup_groups_header($group_name_array, $display_day)
    {
        $header_string = "Popular <a href=\"#\" id=\"places_link\">places</a> people from ";
        $number = count($group_name_array);
        if ($number == 1)
        {
            $header_string .= "<font style=\"color:orange;\">" . $group_name_array[0] . "</font>";
        } else if ($number == 2)
        {
            $header_string .= "<font style=\"color:orange;\">" . $group_name_array[0];
            $header_string .= "</font> and <font style=\"color:orange;\">" . $group_name_array[1] . "</font>";
        } else if ($number > 2)
        {
            $index = 0;
            foreach ($group_name_array as $group_name)
            {
                if (isset($group_name_array[$index + 1]))
                {
                    $header_string .= "<font style=\"color:orange;\">$group_name</font>, ";
                } else
                {
                    $header_string .= "and <font style=\"color:orange;\">$group_name</font>";
                }
                $index++;
            }
        }
        $header_string .= " are going $display_day";
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
        $group_name_list = array();
        foreach ($result->result() as $group_name)
        {
            $group_name_list[] = $group_name->name;
        }
        return $group_name_list;
    }

    // Returns html for the locations as well as associated coordinates
    function display_location_tabs($display_message, $place_id_array, $place_array)
    {
        // Establish the list of coordinates
        $coords_array = array();

        ob_start();
        ?> 
        <div class="display_message">
            <?php echo $display_message; ?>
        </div>
        <?php
        if (count($place_id_array) > 0)
        {
            $place_id_array = array_count_values($place_id_array);
            asort($place_id_array);
            $place_id_array = array_reverse($place_id_array, TRUE);
            $number_tracker = 1;
            ?>
            <div class="location_tabs">
                <?php
                foreach ($place_id_array as $place_id => $count)
                {

                    $coords_array[] = array_merge($place_array[$place_id], array($number_tracker)); // this is used for the coordinates
                    ?>
                    <div class="location_tab" place_id="<?php echo $place_id; ?>">
                        <div class="number">
                            <?php echo $number_tracker; ?>
                        </div>
                        <font style="font-weight:bold;"> <?php echo $place_array[$place_id][0]; ?></font><br/>
                        <font style="font-weight:bold;color:gray; font-size:13px;">selected group has made 
                        <?php
                        echo $count;
                        if ($count > 1)
                        {
                            echo " plans ";
                        } else
                        {
                            echo " plan ";
                        }
                        ?>
                        here</font><br/>
                    </div>
                    <?php
                    $number_tracker++;
                }
                ?>
            </div>
            <?php
        } else
        {
            ?>
            <div class ="no_places_to_show" style="border-bottom: 1px solid lightgray;">
                <br/>Nothing to show<br/><br/>
            </div>
            <?php
        }

        echo(json_encode(array(
            'html' => ob_get_clean(),
            'coords_array' => $coords_array
        )));
    }

}
?>
