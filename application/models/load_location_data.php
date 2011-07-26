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
        $place_info = $this->get_people_attending($place_id, $sql_date, $place_info);
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

    function get_people_attending($place_id, $sql_date, $place_info)
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT school_data.id, user_meta.sex FROM events 
            JOIN plans ON plans.event_id=events.id 
            JOIN user_meta ON user_meta.user_id=plans.user_id
            LEFT JOIN school_data ON school_data.id=user_meta.school_id AND school_data.id=$user->school_id
            WHERE events.place_id=$place_id AND events.date='$sql_date'";
        $result = $this->db->query($query); // pull school id, and gender from people attending
        
        $total_attending = $result->num_rows();
        $place_info['total_attending'] = $total_attending;
        
        $school = array();
        $males = 0;
        $females = 0;
        foreach ($result->result() as $result)
        {
            if($result->sex == 'male')
            {
                $males++;
            }else if($result->sex == 'female')
            {
                $females++;
            }
            $school[] = $result->id;
        }
        $place_info['males'] = $males;
        $place_info['females'] = $females;
       
        $number_of_schoolmates_attending = count($school);
        $place_info['school_attending'] = $number_of_schoolmates_attending;
        
        return $place_info;
    }

    function display_place_info($place_info) // name, lat, lon, category, distance
    {
        if (strlen($place_info['distance']) > 3)
        {
            $place_info['distance'] = substr($place_info['distance'], 0, 3);
        }
        
        var_dump($place_info);
        
        /*
        echo "<font style=\"font-weight:bold;\">" . $place_info['name'] . "</font>";
        echo "<br/><font style=\"color:gray;\">Category: " . $place_info['category'];
        echo "<br/>Distance from you: " . $place_info['distance'] . " miles";
        echo "<br/>Friends going: " . $place_info['number_of_friends'];
        echo "<br/>Schoolmates going: " .$place_info['schoolmates_going'];
        echo "<br/>% Male:  % Female:  </font>";
         * 
         */
    }

}

?>
