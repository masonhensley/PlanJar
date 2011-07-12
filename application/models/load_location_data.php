<?php
class Load_location_data extends CI_Model
{
    function showLocation($place_id, $date)
    {
            $query = "SELECT id, name, category FROM places WHERE id=$place_id";
            $query_result = $this->db->query($query);
            $result = $query_result->result();
            $place_name = $result->name;
            $place_category = $result->category;
            $html_string = "$place_name and $place_category";
            return $html_string;
    }
}
?>
