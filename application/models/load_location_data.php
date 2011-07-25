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
        var_dump($place_info);
    }
    
    function get_place_info($place_id)
    {
        $query = "SELECT name, latitude, longitude, category, 
                        ((ACOS(SIN($user->latitude * PI() / 180) * SIN(places.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(places.latitude * PI() / 180) * COS(($user->longitude - places.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance 
                        FROM places WHERE id=$place_id";
        $result = $this->db->query($query);
         $place_array = $result->row_array();
         return $place_array;
    }
    
}

?>
