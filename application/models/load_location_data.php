<?php

class Load_location_data extends CI_Model
{
    function display_location_info($place_id, $date, $selected_groups)
    {   
        $place_info = $this->get_place_info($place_id);
         if ($selected_groups[0] == 'current_location')
        {
            
        } else if ($selected_groups[0] == 'friends')
        {
            
        } else if ($selected_groups[0] == 'school')
        {
            
        } else
        {
            
        }
    }
    
    function get_place_info($place_id)
    {
        $query = "SELECT name, latitude, longitude, category FROM places WHERE id=$place_id";
        $result = $this->db->query($query);
         $place_array = $result->row_array();
         return $place_array;
    }
    
}

?>
