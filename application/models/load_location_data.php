<?php
class Load_location_data extends CI_Model
{
    function showLocation($place_id, $date)
    {
            $query = "SELECT id, name, category FROM places WHERE id=$place_id";
            var_dump($query);
            $query_result = $this->db->query($query);
            $row = $query_result->row();
         
            $place_name = $row->name;
            $place_category = $row->category;
            $html_string = "$place_name and $place_category";
             return $html_string;
    }
}
?>
