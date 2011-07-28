<?php

class Display_group_template extends CI_Model
{

    function _display_group_info($selected_groups, $day, $school)  // being in this function ensures that $selected_groups is not NULL
    {
        if (!$day)
        {
            $day = 0;
        }

        if ($selected_groups[0] == 'current_location')
        {
            $data_array = $this->get_current_location_data();
        } else if ($selected_groups[0] == 'friends')
        {
            $data_array = $this->get_friend_data();
        } else if ($selected_groups[0] == 'school')
        {
            $data_array = $this->get_school_data($school);
        } else // when groups are selected
        {
            $data_array = $this->get_selected_group_data($selected_groups);
        }
        return $this->get_groups_template($selected_groups, $day);
    }

    function get_selected_group_data($selected_groups)
    {
        $query = "SELECT * FROM group_relationships
                    JOIN user_meta ON user_meta.user_id=group_relationships.user_joined_id
                    WHERE ";
        foreach ($selected_groups as $group_id)
        {
            $query .= "group_relationships.group_id=$group_id OR ";
        }
        $query = substr($query, 0, -4); // contains information for all the users in the selected groups
        $result = $this->db->query($query);
        $result_array = $result->result_array();
        // Data to be returned
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

        // query for all the plans that people in the groups have made
        $plan_query = "SELECT places.name, places.id, user_meta.sex FROM plans 
                            JOIN user_meta ON plans.user_id=user_meta.user_id
                            JOIN events ON events.id=plans.event_id AND events.date>DATE_ADD(NOW(), INTERVAL -3 DAY) AND events.date<DATE_ADD(NOW(), INTERVAL 3 DAY)
                            JOIN places ON places.id=events.place_id
                            WHERE ";
        foreach ($user_ids as $id)
        {
            $plan_query .= "plans.user_id=$id OR ";
        }
        $plan_query = substr($plan_query, 0, -4);
        var_dump($plan_query);
        //$result = $this->db->query($plan_query);
    }

    function get_current_location_data()
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT *,
                        ((ACOS(SIN($user->latitude * PI() / 180) * SIN(user_meta.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(user_meta.latitude * PI() / 180) * COS(($user->longitude - user_meta.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance 
                        FROM user_meta
                        HAVING distance<15";
        $result = $this->db->query($query);
        $result_array = $result->result_array();
        $total_near_by = $result->num_rows();
        ?>
        <div class="data_box_top_bar">
            <div style="float:left;">
                <font style="font-size:30px;color:gray; font-weight:bold;">Network: </font>
                <font style="font-size:30px; font-weight:bold;">Current Location</font>
                <br/><font style="font-size:20px; font-weight:bold; color:gray;">(<?php echo $total_near_by; ?> people within 15 miles)</font>
            </div>
        </div>
        <?php
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
        ?>
        <div class="data_box_top_bar">
            <div style="float:left;">
                <font style="font-size:30px;color:gray; font-weight:bold;">Network: </font>
                <font style="font-size:30px; font-weight:bold;">Friends</font>
                <font style="font-size:30px; font-weight:bold; color:gray;">(<?php echo $friend_count; ?>)</font>
            </div>
        </div>
        <?php
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
        ?>
        <div class="data_box_top_bar">
            <div style="float:left;">
                <font style="font-size:30px;color:gray; font-weight:bold;">Network: </font>
                <font style="font-size:30px; font-weight:bold;"><?php echo $school; ?></font>
            </div>
        </div>
        <?php
    }

    function get_groups_template($selected_groups, $day)
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
                </font></div></div>
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
