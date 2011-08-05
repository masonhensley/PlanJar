<?php

class Load_friend_plans extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    function populate_plans($friend_id)
    {
        $this->_get_friend_plans($friend_id);
    }

    function _get_friend_plans($friend_id)
    {
        $user = $this->ion_auth->get_user();

        $query = "
            SELECT plans.id, events.date, events.time, events.title, plans.event_id, places.name
            FROM plans
            JOIN events ON events.id=plans.event_id AND events.date>=NOW()
            LEFT JOIN event_invitees ON event_invitees.event_id=events.id
            JOIN places ON events.place_id=places.id
            WHERE plans.user_id=$friend_id AND (events.privacy='open' OR event_invitees.user_id=$user->user_id
                ";
        $this->db->query($query);
    }

}

?>