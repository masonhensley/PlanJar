<?php

class Display_group_info extends CI_Model
{

    function _display_group_info($selected_groups, $day)  // being in this function ensures that $selected_groups is not NULL
    {
        
        $this->load->model('load_locations');
        $display_day = $this->load_locations->get_day($day);
        
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
        echo "<br/>Select a group to see relevent information for $display_day";
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
