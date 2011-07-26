<?php

class Load_coming_events extends CI_Model
{

    function load_events()
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT places.id, places.name, events.date, plans.event_id,
                        ((ACOS(SIN($user->latitude * PI() / 180) * SIN(places.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(places.latitude * PI() / 180) * COS(($user->longitude - places.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance
                    FROM events
                    LEFT JOIN event_invitees ON event_invitees.event_id=events.id
                    JOIN plans ON (plans.event_id=events.id AND events.privacy='open') OR (plans.user_id=event_invitees.user_id)
                    JOIN places ON places.id=events.place_id
                    WHERE events.date>NOW()
                    ORDER BY date ASC";
        //$this->db->query($query);
        /*
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
         * 
         */
        var_dump($query);
    }

}
?>
