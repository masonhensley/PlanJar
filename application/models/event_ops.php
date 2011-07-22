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
        $query_string = "INSERT IGNORE INTO events VALUES (DEFAULT, ?, ?, ?, ?, ?)";
        $query = $this->db->query($query_string, array(
                    $data->title,
                    $data->place_id,
                    $data->date,
                    $data->time,
                    $data->privacy
                ));

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
            $query_string = "INSERT IGNORE INTO notifications VALUES $values_string";
            $query = $this->db->query($query_string);
        }
    }

    // Echos HTML for a select input containing all the event names at the specified location and time
    public function get_events_for_plan($day_offset, $time, $place_id)
    {
        $date = new DateTime();
        $date->add(new DateInterval('P' . $day_offset . 'D'));

        $query_string = "SELECT id, title FROM events WHERE date = ? AND time = ? AND place_id = ?";
        $query = $this->db->query($query_string, array($date->format('Y-m-d'), $time, $place_id));

        // Echo the select
        echo('<select id="plan_event_select" size="6">');

        // Echo the intermediate entries
        foreach ($query->result() as $row)
        {
            echo('<option value="' . $row->id . '">' . $row->title . '</option>');
        }

        // Close the select
        echo('</select>');
    }

}
?>
