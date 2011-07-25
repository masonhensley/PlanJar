<?php

class Load_location_data extends CI_Model
{
    function display_location_info($place_id, $date, $selected_groups)
    {
        $place_info = $this->get_place_info($place_id);
        $this->load->model('load_locations');
        $group_names_selected = $this->load_locations->get_group_names($selected_groups);
        var_dump($place_info, $group_names_selected);
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
