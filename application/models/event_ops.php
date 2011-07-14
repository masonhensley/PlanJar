<?php

class Event_ops extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    public function create_event($title, $privacy)
    {
        $this->load->database();
        $query_string = "INSERT INTO groups VALUES (DEFAULT, ?, ?)";
        $query = $this->db->query($query_string, array($title, $privacy));

        return $this->db->insert_id();
    }

    public function add_invitees($event_id, $user_id_list, $group_id_list)
    {
        $this->load->database();

        // User invites
        foreach ($user_id_list as $user_id)
        {
            $query_string = "INSERT INTO event_relationships VALUES (DEFAULT, ?, DEFAULT, ?)";
            $query = $this->db->query($query_string, array($event_id, $user_id));
        }

        // Group invites
        foreach ($group_id_list as $group_id)
        {
            $query_string = "INSERT INTO event_relationships VALUES (DEFAULT, ?, ?, DEFAULT)";
            $query = $this->db->query($query_string, array($event_id, $group_id));
        }
    }

}

?>
