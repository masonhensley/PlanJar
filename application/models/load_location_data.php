<?php
class Load_location_data extends CI_Model
{
    function showLocation($place_id, $date, $user_id)
    {
            $place_query = "SELECT id, name, category FROM places WHERE id=$place_id";
            $query_result = $this->db->query($place_query);
            $row = $query_result->row();
            
            $place_name = $row->name;
            $place_category = $row->category;
            
            $friend_query = "SELECT plans.place_id, plans.user_id, plans.date, plans.time_of_day, friends.follow_id FROM friends where user_id=$user_id 
            LEFT JOIN plans ON friends.follow_id=plans.user_id AND plans.date=$date
            LEFT JOIN places ON places.id=plans.place_id";
            
            $friend_query_result = $this->db->query($friend_query);
            
            $html_string = "$friend_query";
           
             return $html_string;
    }
}
?>
