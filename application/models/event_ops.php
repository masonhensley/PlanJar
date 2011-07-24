<?php

class Event_ops extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Accepts an associative array of data to create an event
    // Returns the event id
    public function create_event($data)
    {
        // Return the id if the event already exists
        $query_string = "SELECT * FROM events WHERE 
            title = ? AND place_id = ? AND date = ? AND time = ? AND privacy = ?";
        $query = $this->db->query($query_string, $data);
        if ($query->num_rows() > 0)
        {
            return $query->row()->id;
        }

        // Add the event
        $query_string = "INSERT IGNORE INTO events VALUES (DEFAULT, ?, ?, ?, ?, ?)";
        $query = $this->db->query($query_string, $data);

        return $this->db->insert_id();
    }

    // Adds the specified users to the invitation list of the specified event
    public function add_invitees($event_id, $user_id_list)
    {
        // Build the string containing the multiple entries to insert.
        $values_string = '';
        foreach ($user_id_list as $user_id)
        {
            $values_string .= "(DEFAULT, $event_id, $user_id), ";
        }

        // Only continue if there were results
        if ($values_string != '')
        {
            // Trim the trailing comma and space
            $values_string = substr($values_string, 0, -2);

            // Add all the notifications.
            $query_string = "INSERT IGNORE INTO event_invitees VALUES $values_string";
            $query = $this->db->query($query_string);
            
        }
    }

    // Echos HTML for a select input containing all the event names at the specified location and time
    public function get_events_for_plan($day_offset, $time, $place_id)
    {
        $date = new DateTime();
        $date->add(new DateInterval('P' . $day_offset . 'D'));

        $query_string = "SELECT id, title, privacy FROM events WHERE date = ? AND time = ? AND place_id = ? AND title <> ''";
        $query = $this->db->query($query_string, array($date->format('Y-m-d'), $time, $place_id));

        // Echo the select
        echo('<select id="plan_event_select" size="6" multiple="multiple">');

        // Echo the intermediate entries
        foreach ($query->result() as $row)
        {
            echo('<option value="' . $row->id . '" priv_type="' . $row->privacy . '">' . $row->title . '</option>');
        }

        // Close the select
        echo('</select>');
    }

}

?>
