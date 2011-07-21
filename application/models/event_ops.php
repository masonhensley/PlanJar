<?php

class Event_ops extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    public function create_event($privacy, $user_id_list, $group_id_list)
    {
        $query_string = "INSERT INTO events VALUES (DEFAULT, ?)";
        $query = $this->db->query($query_string, array($privacy));

        $this->add_invitees($this->db->insert_id(), $user_id_list, $group_id_list);

        return $this->db->insert_id();
    }

    public function add_invitees($event_id, $user_id_list, $group_id_list)
    {
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

    // Echos HTML for a select input containing all the event names at the specified location and time
    public function get_events($day, $time)
    {
        //$query_string = "SELECT "
        ?>
        <select size="6">
            <option>Just attending</option>
            <option>Raging</option>
            <option>2 4 2uesday</option>
            <option>Another event</option>
        </select>

        <?php

    }

}
?>
