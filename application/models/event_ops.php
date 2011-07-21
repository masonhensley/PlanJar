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
    public function get_events_for_plan($day_offset, $time)
    {
        $date = new DateTime();
        $date->add(new DateInterval('P' . $day_offset . 'D'));

        $query_string = "SELECT id, title FROM events WHERE date = ? AND time = ?";
        $query = $this->db->query($query_string, array($dat->format('Y-m-d'), $time));

        // Echo the select
        echo('<select id="plan_event_select size="6">');
        echo('<option value="" selected="selected"><no plan title></option>');

        // Echo the intermediate entries
        foreach ($query->result as $row)
        {
            echo('<option value="' . $row->id . '">' . $row->title . '</option>');
        }

        // Close the select
        echo('</select>');
    }

}
?>
