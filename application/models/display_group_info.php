<?php

class Display_group_info extends CI_Model
{
    
    function _display_group_info($selected_groups, $day)  // being in this function ensures that $selected_groups is not NULL
    {
        if($selected_groups[0] == 'current_location')
        {
            $this->on_current_location_selected();
        }else if($selected_groups[0] == 'friends')
        {
            $this->on_friends_selected();
        }else{
            $this->on_groups_selected();
        }
        
    }
    
    function on_current_location_selected()
    {
        
    }
    
    function on_friends_selected()
    {
        
    }
    
    function on_groups_selected()
    {
        
    }
}

?>
