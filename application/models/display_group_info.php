<?php

class Display_group_info extends CI_Model
{

    function _display_group_info($selected_groups, $day)  // being in this function ensures that $selected_groups is not NULL
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
            $this->on_school_selected();
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
        echo "current location is selected";
    }

    function on_friends_selected()
    {
        $this->load->model('load_locations');
        $friends = $this->load_locations->get_friend_ids();
        $friend_count = count($friend_ids);
        ?>
        <div class="data_box_top_bar">
            <div style="float:left;">
                <font style="font-size:30px; font-weight:bold;">Friends</font>
                <font style="font-size:30px; font-weight:bold; color:gray;">(<?php echo $friend_count; ?>)</font>
            </div>
        </div>

        <?php
    }

    function on_school_selected()
    {
        echo "school tab is selected";
    }

    function on_groups_selected()
    {
        echo "groups are selected";
    }

}
?>
