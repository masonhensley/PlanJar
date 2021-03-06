<?php

class Load_coming_events extends CI_Model
{
    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    function load_events()
    {
        $user = $this->ion_auth->get_user();
        // change later
        $query = "SELECT places.id, places.name, events.date, plans.event_id, events.title, plans.user_id,
                        ((ACOS(SIN($user->latitude * PI() / 180) * SIN(places.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(places.latitude * PI() / 180) * COS(($user->longitude - places.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance
                    FROM events
                    LEFT JOIN event_invites ON event_invites.event_id=events.id
                    JOIN plans ON (plans.event_id=events.id AND events.privacy='open') OR (plans.user_id=event_invites.user_id)
                    JOIN places ON places.id=events.place_id
                    WHERE events.date>NOW() AND events.date<NOW()+5
                    HAVING distance<15
                    ORDER BY date ASC";
        $result = $this->db->query($query);
        $place_array = array();
        $place_id_array = array();
        foreach ($result->result() as $place)
        {
            if (!isset($place_array[$place->id]))
            {
                $place_array[$place->id] = $place->name;
            }
            $place_id_array[] = $place->id;
        }
        $result = $result->result();
        $return_string = $this->display_event_tabs($place_id_array, $place_array, $result);        
        
        return $return_string;
    }

    function display_event_tabs($place_id_array, $place_array, $result)
    {
        
        $return_string = "<div class=\"display_message\">Popular <font style=\"color:purple; font-weight:bold;\">Places</font><br/>";
        //$return_string .= var_dump($result);
        $return_string .= "(select to view info)</div>";
        if (count($place_id_array) > 0)
        {
            $place_id_array = array_count_values($place_id_array);
            asort($place_id_array);
            $place_id_array = array_reverse($place_id_array, TRUE);
            $number_tracker = 1;
            foreach ($place_id_array as $place_id => $count)
            {
                $return_string .= "<div class=\"event_tab\" place_id=\"$place_id\"><div class=\"number\">$number_tracker</div>";
                $return_string .= "<font style=\"font-weight:bold;\">$place_array[$place_id]</font><br/>";
                $return_string .= "<font style=\"font-weight:bold;color:lightgray; font-size:13px;\">$count upcoming plans</font></div>";
                $number_tracker++;
            }
        } else
        {
            $return_string .= "<div class =\"no_places_to_show\"><br/>Nothing to show</div>";
        }
        return $return_string;
    }

}
?>