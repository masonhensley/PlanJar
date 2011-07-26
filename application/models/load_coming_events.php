<?php

class Load_coming_events extends CI_Model
{

    function load_events($selected_groups)
    {
        if (!$selected_groups[0])
        {
            $this->on_nothing_selected();
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
            $this->on_groups_selected($selected_groups);
        }
    }

    function on_nothing_selected()
    {
        echo "<hr/>This panel will populate with <font style=\"font-weight:bold;color:navy;\">upcoming events</font>";
        echo "based on the <font style=\"color:navy; font-weight:bold;\">groups</font> selected";
    }

    function on_current_location_selected()
    {
        
    }

    function on_friends_selected()
    {
        
    }

    function on_school_selected()
    {
        
    }

    function on_groups_selected($selected_groups)
    {
        
    }

}

?>
