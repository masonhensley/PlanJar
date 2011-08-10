<?php

class Event_ops extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Accepts an associative array of data to create an event
    // Returns the event id of the event or a pre-existing event with the same values
    public function create_event($data)
    {
        // Rectify the time
        if ($data['clock_time'] == '')
        {
            unset($data['clock_time']);
        }

        // Get pre-existing events
        $broad_data = clone $data;
        unset($broad_data['clock_time']);
        unset($broad_data['privacy']);
        unset($broad_data['originator_id']);

        $query_string = "SELECT id FROM events ";
        $query_string .= $this->db->or_where($query_string, $data);
        $query = $this->db->query($query_string);

        if ($query->num_rows() > 0)
        {
            // Return the existing id
            return $query->row()->id;
        } else
        {
            // Add the event and return the id
            $query_string = $this->db->insert_string('events', $data);
            $query_string = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query_string);
            $query = $this->db->query($query_string);

            return $this->db->insert_id();
        }
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
            $query_string = "INSERT IGNORE INTO event_invites VALUES $values_string";
            $query = $this->db->query($query_string);
        }
    }

    // Echos HTML for a selection containing all the event names at the specified location and time
    public function get_events_for_plan($day_offset, $time, $place_id)
    {
        $date = new DateTime();
        $date->add(new DateInterval('P' . $day_offset . 'D'));

        $query_string = "SELECT DISTINCT events.id, events.title, events.privacy
            FROM events LEFT JOIN event_invites ON events.id = event_invites.event_id
            WHERE events.date = ? AND events.time = ? AND events.place_id = ? AND events.title <> ''
            AND (event_invites.user_id = ? OR events.privacy = 'open')";
        $query = $this->db->query($query_string, array(
            $date->format('Y-m-d'),
            $time,
            $place_id,
            $this->ion_auth->get_user()->id));

        // Echo the event entries
        if ($query->num_rows() > 0)
        {
            ?>
            <div id="plan_events_title">Here's what's already going on...</div>
            <?php
            foreach ($query->result() as $row)
            {
                $id = $row->id;
                $privacy = $row->privacy;
                $title = $row->title;
                $event_text = "$title ($privacy)";
                echo("<div class=\"selectable_event\" event_id=\"$id\" priv_type=\"$privacy\" event_name=\"$title\">$event_text</div>");
            }
        }
    }

    // Returns HTML and data for a selection containing both events to which the user has a plan
    public function get_events_for_choice($event0, $event1)
    {
        $query_string = "SELECT events.id, events.title, events.privacy, events.date, events.time, events.originator_id, places.name
            FROM events JOIN places ON events.place_id = places.id
            WHERE events.id = ? OR events.id = ?";
        $query = $this->db->query($query_string, array($event0, $event1));

        // Create the selectable HTML
        $return_array['html'] = '';
        foreach ($query->result() as $row)
        {
            $id = $row->id;
            $privacy = $row->privacy;
            $title = $row->title;
            if ($title == '')
            {
                $event_text = 'Just going';
            } else
            {
                $event_text = $title . " ($privacy)";
            }
            $originator = $row->originator_id;
            $originator = $originator == $this->ion_auth->get_user()->id;
            $return_array['html'] .= "<div class=\"selectable_event\" event_id=\"$id\" priv_type=\"$privacy\" originator=\"$originator\">$event_text</div>";
        }

        // Create the title text
        $place = $query->row()->name;
        $day = new DateTime($query->row()->date);
        $now = new DateTime();
        $time = $query->row()->time;
        $return_array['title_text'] = "You have plans to two events at<br/><b>$place</b><br/>";

        if ($day->format('Y-m-d') == $now->format('Y-m-d'))
        {
            // Today
            if ($time == 'morning' || $time == 'afternoon')
            {
                $return_array['title_text'] .= 'this ' . $time;
            } else if ($time == 'night')
            {
                $return_array['title_text'] .= 'tonight';
            } else
            {
                $return_array['title_text'] .= 'late night tonight';
            }
        } else
        {
            // Any other day
            $day = $day->format('D - j');

            if ($time == 'late_night')
            {
                $return_array['title_text'] .= 'late into the night';
            } else
            {
                $return_array['title_text'] .= 'the ' . $time;
            }
            $return_array['title_text'] .= ' of <b>' . $day . '</b>';
        }

        $return_array['title_text'] .= '.<br/>Choose which one you want to go to.';

        return $return_array;
    }

    // Deletes an event
    function delete_event($event_id)
    {
        // Get all people with plans to the event
        $query_string = "SELECT id FROM plans WHERE event_id = ? AND user_id <> ?";
        $query = $this->db->query($query_string, array($event_id, $this->ion_auth->get_user()->id));

        // Delete the event if there is less than one attendee (the current user)
        if ($query->num_rows() == 0)
        {
            $query_string = "DELETE FROM events WHERE id = ?";
            $query = $this->db->query($query_string, array($event_id));

            // Delete all relevant invites
            $query_string = "DELETE FROM event_invites WHERE event_id = ?";
            $query = $this->db->query($query_string, array($event_id));

            // Delete all relevant notifications
            $query_string = "DELETE FROM notifications WHERE type = ? AND subject_id = ?";
            $query = $this->db->query($query_string, array('event_invite', $event_id));
        }
    }

}
?>
