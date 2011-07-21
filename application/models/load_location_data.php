<?php

class Load_location_data extends CI_Model
{

    function display_location_info($place_id, $date, $user_id)
    {
        $place_query = "SELECT id, name, category FROM places WHERE id=$place_id"; // get the name and category for the place_id and store them
        $query_result = $this->db->query($place_query);
        $row = $query_result->row();
        $place_name = $row->name;
        $place_category = $row->category;

        $number_friends_attending = $this->getNumberFriends($user_id, $place_id, $date);
        $html_string = $this->generateHTML($number_friends_attending, $place_name, $place_category);

        return $html_string;
    }

    function getNumberFriends($user_id, $place_id, $date)
    {
        $friend_query = "SELECT follow_id FROM friends WHERE user_id=$user_id";
        $friend_query = $this->db->query($friend_query);
        $query = "SELECT plans.user_id FROM plans WHERE plans.place_id=$place_id AND plans.date=$date AND (";

        foreach ($friend_query->result() as $row)
        {
            $query .= "user_id=$row->follow_id OR ";
        }
        $query = substr($query, 0, strlen($query) - 4);  // trim off the last "OR" before querying
        $query .= ")";
        $result = $this->db->query($query);
        $number_friends = $result->num_rows();
        return $number_friends;
    }

    function generateHTML($number_friends_attending, $place_name, $place_category)
    {
        $html = "
          <div class = \"location_data\">
            $place_name<br/>
            $place_category<br/>
            $number_friends_attending friends attending <br/>
          </div> 
            ";
        return $html;
    }

}

?>
