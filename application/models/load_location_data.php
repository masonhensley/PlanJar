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
            var_dump($place_name, $place_category);
    }
}
?>
