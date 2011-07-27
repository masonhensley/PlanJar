<?php

class Display_group_info extends CI_Model
{

    function _display_group_info($selected_groups, $day, $school)  // being in this function ensures that $selected_groups is not NULL
    {
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


        if (!$selected_groups[0])
        {
            $this->on_nothing_selected($display_day);
        } else if ($selected_groups[0] == 'current_location')
        {
            $this->on_current_location_selected();
        } else if ($selected_groups[0] == 'friends')
        {
            $this->on_friends_selected();
        } else if ($selected_groups[0] == 'school')
        {
            $this->on_school_selected($school);
        } else
        {
            $this->on_groups_selected();
        }
    }

    function on_nothing_selected($display_day)
    {
        ?>
        <br/><br/><br/><font style="font-size:20px; font-weight:bold; color:gray;">Select a group on the left to see relevant information for <?php echo $display_day; ?>
        <br/><br/><br/>
        Select a plan on the right to view its information and invite people
        <br/><br/><br/>
        You can change the day using the panel below </font>
        <?php
    }

    function on_current_location_selected()
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
        var_dump($result_array);
    }

    function on_friends_selected()
    {
        $this->load->model('load_locations');
        $friends = $this->load_locations->get_friend_ids();
        $friend_count = count($friends);
        $query = "SELECT * FROM user_meta WHERE ";
        foreach($friends as $friend)
        {
            $query .= "user_meta.user_id=$friend OR ";
        }
        $query = substr($query, 0, -4);
        $result = $this->db->query($query);
        $result_array = $result->result_array();
        
        ?>
        <div class="data_box_top_bar">
            <div style="float:left;">
                <font style="font-size:30px; font-weight:bold;">Friends</font>
                <font style="font-size:30px; font-weight:bold; color:gray;">(<?php echo $friend_count; ?>)</font>
            </div>
        </div>

        <?php
    }

    function on_school_selected($school)
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
                <font style="font-size:30px; font-weight:bold;"><?php echo $school; ?></font>
                <font style="font-size:30px; font-weight:bold; color:gray;">(<?php echo $number_schoolmates; ?> on PlanJar)</font>
            </div>
        </div>
        <?php
        
    }

    function on_groups_selected()
    {
        echo "groups are selected";
    }

}
?>
