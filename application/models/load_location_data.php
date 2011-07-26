<?php

class Load_location_data extends CI_Model
{

    function display_location_info($place_id, $day, $selected_groups)
    {
        if (!$day)  // set the day correctly if null
        {
            $day = 0;
        }
        $date = new DateTime();
        $sql_date = $date->add(new DateInterval('P' . $day . 'D')); // date to be used in sql queries
        $sql_date = $sql_date->format('Y-m-d');
        $place_info = $this->get_place_info($place_id); // selects the name, lat, lon, category, and distance of the location
        $number_friends_attending = $this->get_friends_attending($place_id, $sql_date);
        $place_info['number_of_friends'] = $number_friends_attending;

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

    function get_friends_attending($place_id, $date)
    {
        $this->load->model('load_locations');
        $friend_ids = $this->load_locations->get_friend_ids();
        // first find the number of friends attending
        $number_friends_query = "SELECT * FROM plans 
            JOIN events ON plans.event_id=events.id AND events.date='$date' AND events.place_id=$place_id
            WHERE ";
        foreach ($friend_ids as $id)
        {
            $number_friends_query .= "plans.user_id=$id OR ";
        }
        $number_friends_query = substr($number_friends_query, 0, -4);

        $result = $this->db->query($number_friends_query);
        $number_of_friends = $result->num_rows();
        return $number_of_friends;
    }

    function display_place_info($place_info) // name, lat, lon, category, distance
    {
        if (strlen($place_info['distance']) > 3)
        {
            $place_info['distance'] = substr($place_info['distance'], 0, 3);
        }
        echo "<font style=\"font-weight:bold;\">" . $place_info['name'] . "</font>";
        echo "<br/><font style=\"color:gray;\">Category: " . $place_info['category'];
        echo "<br/>Distance from your location: " . $place_info['distance'] . " miles</font>";
        echo "<br/>Number of friends going: " .$place_info['number_of_friends'];
    }

}

?>
