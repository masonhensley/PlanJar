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

        if ($selected_groups[0] == 'current_location')
        {
            $data_array = $this->get_current_location_data(); // get information for current location
            $format_type .= "current_location";
        } else if ($selected_groups[0] == 'friends')
        {
            $data_array = $this->get_friend_data(); // get information for friends
            $format_type .= "friends";
        } else if ($selected_groups[0] == 'school')
        {
            $data_array = $this->get_school_data($school);  // get information for school
            $format_type .= "school";
        } else // when groups are selected
        {
            $data_array = $this->get_selected_group_data($selected_groups, $sql_date);  // get information for groups
            $format_type .= "groups";
        }
        return $this->get_group_template($format_type, $selected_groups, $day, $data_array);
    }

    function get_selected_group_data($selected_groups, $sql_date)
    {
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

        $return_array = $this->get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females); // query for number of girls and boys going out on the date selected
        $return_array = $this->get_surrounding_day_info($return_array, $user_ids);  // query for all the plans that people in the groups have made for the surrounding week

        return $return_array;
    }

    function get_current_location_data()
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
        $return_array = $this->get_surrounding_day_info($return_array, $user_ids);
        
        return $return_array;
    }

    function get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females)
    {
        // query for number of girls and boys going out on the date selected
        $girl_boy_query = "SELECT user_meta.sex FROM plans 
                            JOIN user_meta ON plans.user_id=user_meta.user_id
                            JOIN events ON events.id=plans.event_id AND events.date=$sql_date
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

        $percent_total_goingout = ($males_going_out + $females_going_out) / $total_people;
        $percent_males_goingout = $males_going_out / $number_males;
        $percent_females_goingout = $females_going_out / $number_females;

        $return_array['percent_total_going_out'] = $percent_total_goingout;
        $return_array['percent_males_going_out'] = $percent_males_goingout;
        $return_array['percent_females_going_out'] = $percent_females_goingout;

        return $return_array;
    }

    function get_surrounding_day_info($return_array, $user_ids)
    {
        // query for all the plans that people in the groups have made for the surrounding week
        $recent_plans_query = "SELECT events.date FROM plans 
                            JOIN user_meta ON plans.user_id=user_meta.user_id
                            JOIN events ON events.id=plans.event_id AND events.date>DATE_ADD(NOW(), INTERVAL -3 DAY) AND events.date<DATE_ADD(NOW(), INTERVAL 4 DAY)
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
        foreach ($result->result() as $plan)
        {
            $plan_dates[] = $plan->date;
        }
        $plan_dates = array_count_values($plan_dates);
        $return_array['plan_dates'] = $plan_dates;

        return $return_array;
    }

    function get_friend_data()
    {
        $this->load->model('load_locations');
        $friends = $this->load_locations->get_friend_ids();
        $friend_count = count($friends);
        $query = "SELECT * FROM user_meta WHERE ";
        foreach ($friends as $friend)
        {
            $query .= "user_meta.user_id=$friend OR ";
        }
        $query = substr($query, 0, -4);
        $result = $this->db->query($query);
        $result_array = $result->result_array();
    }

    function get_school_data($school)
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT * FROM user_meta 
        JOIN school_data ON school_data.id=user_meta.school_id 
        WHERE user_meta.school_id=$user->school_id";

        $result = $this->db->query($query);
        $row = $result->row();
        $number_schoolmates = $result->num_rows();
        $total_enrollment = $row->total_enrollment;
        $result_array = $result->result_array();
    }

    function get_group_template($format_type, $selected_groups, $day, $data_array)
    {
        $return_array = array();
        if (!$day)
        {
            $day = 0;
        }
        $this->load->model('load_locations');
        $display_day = $this->load_locations->get_day($day);

        if ($day == 0)
        {
            $display_day = "today";
        }

        //$this->load->model('load_locations');
        //$group_names = $this->load_locations->get_group_names($selected_groups);
        //ob_start();
        ?>
        <div class="data_box_top_bar">
            <div style="float:left;">
                <font style="font-size:20px;color:black;font-weight:bold;">
                Groups go here.
                </font>
            </div>
        </div>
        <div class="group_graph_top_left" >
        </div>
        <div class="group_graph_top_right">
            <div class="percent_container">
            </div>
            <div class="percent_container">
            </div>
            <div class="percent_container">
            </div>
        </div>
        <div class="group_graph_bottom_right">
        </div>
        <div class="group_graph_bottom_middle">
        </div>
        <div class="group_graph_bottom_left">
        </div>
        <?php
        //$return_array['html'] = ob_get_clean();
        //return $return_array;
    }

}
?>
