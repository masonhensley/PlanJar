<?php

class Display_group_template extends CI_Model
{

    function _display_group_info($selected_groups, $day, $school)  // being in this function ensures that $selected_groups is not NULL
    {
        if (!$day)
        {
            $day = 0; // reformat the day if it is null
        }
        $date = new DateTime();
        $sql_date = $date->add(new DateInterval('P' . $day . 'D')); // date to be used in sql queries
        $sql_date = $sql_date->format('Y-m-d'); // this date is sql friendly
        $format_type = ""; // this is used to distinguish between the different types of display formats
        // determine format type and retrieve data for selected group(s) and store it in $data_array
        if ($selected_groups[0] == 'current_location')
        {
            $data_array = $this->get_current_location_data($sql_date); // get information for current location
            $format_type .= "current_location";
        } else if ($selected_groups[0] == 'friends')
        {
            $data_array = $this->get_friend_data($sql_date); // get information for friends
            $format_type .= "friends";
        } else if ($selected_groups[0] == 'school')
        {
            $data_array = $this->get_school_data($school, $sql_date);  // get information for school
            $format_type .= "school";
        } else // when groups are selected
        {
            $data_array = $this->get_selected_group_data($selected_groups, $sql_date);  // get information for groups
            $format_type .= "groups";
        }
        // return an array(2) that will be json encoded and sent to the browser for graph animation

        return array('html' => $this->get_group_template($format_type, $selected_groups, $day, $data_array),
            'data' => $data_array);
    }

    function get_friend_data($sql_date)
    {
        $this->load->model('load_locations');
        $user_ids = $this->load_locations->get_friend_ids(); // get all the ids of your friends

        $return_array = array(); // data to be returned
        $number_males = 0;
        $number_females = 0;
        $males_going_out = 0;
        $females_going_out = 0;
        $total_people = count($user_ids);

        $query = "SELECT sex FROM user_meta WHERE ";
        foreach ($user_ids as $friend_id)
        {
            $query .= "user_id=$friend_id OR ";
        }
        $query = substr($query, 0, -4);
        $result = $this->db->query($query);

        foreach ($result->result() as $person)
        {
            if ($person->sex == 'male')
            {
                $number_males++;
            } else
            {
                $number_females++;
            }
        }

        $return_array['total_males'] = $number_males;
        $return_array['total_females'] = $number_females;

        // query for number of girls and boys going out on the date selected
        $return_array = $this->get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females);
        // query for all the plans that people in the groups have made for the surrounding week
        $return_array = $this->get_surrounding_day_info($return_array, $user_ids, $sql_date);

        return $return_array;
    }

    function get_school_data($school, $sql_date)
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT user_meta.user_id, user_meta.sex FROM user_meta 
        JOIN school_data ON school_data.id=user_meta.school_id 
        WHERE user_meta.school_id=$user->school_id";
        $result = $this->db->query($query);

        // Data to be returned
        $return_array = array();
        $number_males = 0;
        $number_females = 0;
        $males_going_out = 0;
        $females_going_out = 0;
        $total_people = $result->num_rows();
        $user_ids = array();

        foreach ($result->result() as $person)
        {
            $user_ids[] = $person->user_id;
            if ($person->sex == 'male')
            {
                $number_males++;
            } else
            {
                $number_females++;
            }
        }

        $return_array['total_males'] = $number_males;
        $return_array['total_females'] = $number_females;

        // query for number of girls and boys going out on the date selected 
        $return_array = $this->get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females);
        // query for all the plans that people in the groups have made for the surrounding week
        $return_array = $this->get_surrounding_day_info($return_array, $user_ids, $sql_date);

        return $return_array;
    }

    function get_selected_group_data($selected_groups, $sql_date)
    {
        // first get all the ids of people in the groups
        $query = "SELECT user_meta.user_id, user_meta.sex FROM group_relationships
                    JOIN user_meta ON user_meta.user_id=group_relationships.user_joined_id
                    WHERE ";
        foreach ($selected_groups as $group_id)
        {
            $query .= "group_relationships.group_id=$group_id OR ";
        }
        $query = substr($query, 0, -4); // contains information for all the users in the selected groups
        $result = $this->db->query($query);

        // Data to be returned
        $return_array = array();
        $number_males = 0;
        $number_females = 0;
        $males_going_out = 0;
        $females_going_out = 0;
        $total_people = $result->num_rows();
        $user_ids = array();

        foreach ($result->result() as $person)
        {
            $user_ids[] = $person->user_id;
            if ($person->sex == 'male')
            {
                $number_males++;
            } else
            {
                $number_females++;
            }
        }

        $return_array['total_males'] = $number_males;
        $return_array['total_females'] = $number_females;

        // query for number of girls and boys going out on the date selected
        $return_array = $this->get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females);
        // query for all the plans that people in the groups have made for the surrounding week
        $return_array = $this->get_surrounding_day_info($return_array, $user_ids, $sql_date);

        return $return_array;
    }

    function get_current_location_data($sql_date)
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT user_meta.user_id, user_meta.sex,
                        ((ACOS(SIN($user->latitude * PI() / 180) * SIN(user_meta.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(user_meta.latitude * PI() / 180) * COS(($user->longitude - user_meta.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance 
                        FROM user_meta
                        HAVING distance<15";
        $result = $this->db->query($query);

        // data to be returned
        $return_array = array();
        $total_people = $result->num_rows();
        $number_males = 0;
        $number_females = 0;
        $user_ids = array();
        foreach ($result->result() as $person)
        {
            $user_ids[] = $person->user_id;
            if ($person->sex == 'male')
            {
                $number_males++;
            } else
            {
                $number_females++;
            }
        }

        $return_array['total_males'] = $number_males;
        $return_array['total_females'] = $number_females;

        $return_array = $this->get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females); // fill return array with percentage information
        $return_array = $this->get_surrounding_day_info($return_array, $user_ids, $sql_date);

        return $return_array;
    }

    function get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females)
    {
        // query for number of girls and boys going out on the date selected
        $result = "";

        $girl_boy_query = "SELECT user_meta.sex FROM plans 
                            JOIN user_meta ON plans.user_id=user_meta.user_id
                            JOIN events ON events.id=plans.event_id AND events.date='$sql_date'
                            WHERE ";
        foreach ($user_ids as $id)
        {
            $girl_boy_query .= "plans.user_id=$id OR ";
        }
        $girl_boy_query = substr($girl_boy_query, 0, -4);
        $result = $this->db->query($girl_boy_query);

        $males_going_out = 0;
        $females_going_out = 0;

        foreach ($result->result() as $person)
        {
            if ($person->sex == 'male')
            {
                $males_going_out++;
            } else
            {
                $females_going_out++;
            }
        }

        $return_array['males_going_out'] = $males_going_out;
        $return_array['females_going_out'] = $females_going_out;

        $percent_total_goingout = 0;
        $percent_males_goingout = 0;
        $percent_females_goingout = 0;

        if ($total_people != 0)
        {
            $percent_total_goingout = ($males_going_out + $females_going_out) / $total_people;
        }
        if ($number_males != 0)
        {
            $percent_males_goingout = $males_going_out / $number_males;
        }
        if ($number_females != 0)
        {
            $percent_females_goingout = $females_going_out / $number_females;
        }

        $return_array['percent_total_going_out'] = $percent_total_goingout;
        $return_array['percent_males_going_out'] = $percent_males_goingout;
        $return_array['percent_females_going_out'] = $percent_females_goingout;
        $return_array['selected_date'] = $sql_date;

        return $return_array;
    }

    function get_surrounding_day_info($return_array, $user_ids, $sql_date)
    {
        // query for all the plans that people in the groups have made for the surrounding week
        $recent_plans_query = "SELECT events.date FROM plans 
                            JOIN user_meta ON plans.user_id=user_meta.user_id
                            JOIN events ON events.id=plans.event_id 
                            AND events.date>=DATE_ADD('$sql_date', INTERVAL -2 DAY) AND events.date<DATE_ADD('$sql_date', INTERVAL 4 DAY)
                            JOIN places ON places.id=events.place_id
                            WHERE ";
        foreach ($user_ids as $id)
        {
            $recent_plans_query .= "plans.user_id=$id OR ";
        }
        $recent_plans_query = substr($recent_plans_query, 0, -4);
        $recent_plans_query .= " ORDER BY date ASC";
        $result = $this->db->query($recent_plans_query);
        $plan_dates = array();

        $date_tracker = new DateTime($sql_date);
        $date_tracker->modify('-2 day');

        for ($i = 0; $i < 7; $i++)
        {
            $plan_dates[$date_tracker->format('Y-m-d')] = 0;
            $date_tracker->modify('+1 day');
        }

        foreach ($result->result() as $plan)
        {
            $date = new DateTime($plan->date);
            $date = $date->format('Y-m-d');
            $plan_dates[$date]++;
        }

        // Convert the plan dates array entries from <'Y-m-D': count> to <'date': 'Y-m-D', 'count': count>
        $keys = array_keys($plan_dates);
        $conversion_array = array();
        foreach ($keys as $key)
        {
            $conversion_array[] = array('date' => $key, 'count' => $plan_dates[$key]);
        }

        // Return
        $return_array['plan_dates'] = $conversion_array;
        return $return_array;
    }

    function get_group_template($format_type, $selected_groups, $day, $data_array)
    {
        $top_display = ""; // this contains the text for the header
        $s = ""; // this will make anything plural that needs to be for groups selected
        
        if ($format_type == 'friends')
        {
            $top_display = "Friends"; // you can use data_array to find total number of friends
        } else if ($format_type == 'current_location')
        {
            $top_display .= "Current Location";
        } else if ($format_type == 'school')
        {
            $top_display .= "School";
        } else if ($format_type == 'groups')
        {
            if(count($selected_groups)>1)
            {
                $s = "s";
            }
            $this->load->model('load_locations');
            $group_names = $this->load_locations->get_group_names($selected_groups);
            foreach ($group_names as $group)
            {
                $top_display .= $group . ", ";
            }
            $top_display = substr($top_display, 0, -2);
        }

        $date = new DateTime();
        $month = $big_display_day = $date->add(new DateInterval('P' . $day . 'D'));
        $big_display_day = $big_display_day->format('D');

        ob_start();
        ?>
        <div class="data_box_top_bar">
            <div style="float:left;">
                <font style="color:darkgray;">Selected:</font> <font style="font-size:15px; font-weight:bold;"><?php echo " " . $top_display; ?></font> 
            </div>
        </div>
        <div class="group_graph_top_left" >
            <font style="font-weight:bold;">Statistics</font><br/>
            <?php ?>
        </div>
        <div class="group_graph_top_right">
        </div>
        <div class="group_graph_bottom_right">
            <font style="font-size:120px; color:lightblue;"><?php echo $big_display_day; ?></font>
        </div>
        <div class="group_graph_bottom_left">
            
            <div class="total_percent_container">
                <?php echo $data_array['percent_total_going_out'] ."% "?><font style="color:darkgray"> of people in selected group<?php echo $s;  ?> are going out</font>
            </div>
            <?php echo $data_array['percent_males_going_out'] ."% "?><font style="color:darkgray">of males in group<?php echo $s;  ?> are going out</font>
            <div class="male_percent_container">
            </div>
            <?php echo $data_array['percent_females_going_out'] ."% "?><font style="color:darkgray;">of females in group<?php echo $s;  ?> are going out</font>
            <div class="female_percent_container">
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

}
?>
