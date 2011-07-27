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
        <br/><br/><br/><font style="font-size:20px; font-weight:bold; color:gray;">Select a group to see relevant information for <?php echo $display_day; ?></font>
        <?php
    }

    function on_current_location_selected()
    {
        echo "current location is selected";
    }

    function on_friends_selected()
    {
        echo "friends tab is selected";
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
