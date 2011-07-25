<?php

class Load_location_data extends CI_Model
{
    function display_location_info($place_id, $date, $selected_groups)
    {   
        $place_info = $this->get_place_info($place_id); // selects the name, lat, lon, category, and distance of the location
        $people_attending_info = $this->get_people_attending_info($place_id);
        
        $this->display_place_info($place_info);
        
    }
    
    function get_place_info($place_id)
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT name, latitude, longitude, category, 
                        ((ACOS(SIN($user->latitude * PI() / 180) * SIN(places.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(places.latitude * PI() / 180) * COS(($user->longitude - places.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance 
                        FROM places WHERE id=$place_id";
        $result = $this->db->query($query);
         $place_array = $result->row_array();
         return $place_array;
    }
    
    function get_people_attending_info($place_id)
    {
        
    }
    
    function display_place_info($place_info) // name, lat, lon, category, distance
    {
        if(strlen($place_info['distance']) > 3)
        {
            $place_info['distance'] = substr($place_info['distance'], 0, 3);
        }
        echo "<font style=\"font-weight:bold;\">" .$place_info['name'] ."</font>";
        echo "<br/><font style=\"color:gray;\">Category: " .$place_info['category'];
        echo "<br/>Distance from your location: " .$place_info['distance'] ." miles</font>";
    }
    
}

?>
