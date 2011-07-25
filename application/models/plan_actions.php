<?php

class Plan_actions extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Returns an array of result row objects representing the user's plans
    function get_plans()
    {
        $user_id = $this->ion_auth->get_user()->id;

        $query =
                "SELECT plans.id, events.date, events.time, events.title, plans.event_id, places.name
         FROM plans
         LEFT JOIN events ON plans.event_id=events.id
         LEFT JOIN places ON places.id=events.place_id
         WHERE plans.user_id=$user_id AND events.date >= CURDATE()
         ORDER BY events.date ASC";

        $query_result = $this->db->query($query);

        return $query_result->result();
    }

    function load_plan_data($plan)
    {
        // pull all user's current events
        $query = "SELECT plans.id, events.date, events.time, events.title, places.name
            FROM plans LEFT JOIN events ON plans.event_id = events.id
            LEFT JOIN places ON events.place_id = places.id
            WHERE plans.id = $plan";

        // pull data
        $query_result = $this->db->query($query);

        // initialize plan information
        $time_of_day;
        $date;
        $name;

        foreach ($query_result->result() as $row)
        {
            // populate variables
            $time = $row->time;
            // get rid of the "-"
            $time = str_replace("_", " ", $time);

            $date = $row->date;
            $date = date('m/d', strtotime($date));
            $name = $row->name;
            $title = $row->title;
        }

        // html to replace the data div
        $htmlString = "
        <div style=\"font-size:20px; width:100%; height:230px; color:darkblue; text-align: center;\">
        $title at $name | $time | $date
        </div><br/><br/>
        <div class=\"delete_plan_container\"style=\"font-size: 20px; text-align:left;\">
        <div class=\"delete_plan\">Delete Plan</div></div>";

        return $htmlString;
    }

    // function to delete plan from database
    function delete_plan($plan)
    {
        // Get the associated event
        $query_string = "SELECT event_id FROM plans WHERE id = ?";
        $query = $this->db->query($query_string, array($plan));
        $event_id = $query->row()->event_id;

        // Get all people with plans to the event
        $query_string = "SELECT id FROM plans WHERE event_id = ?";
        $query = $this->db->query($query_string, array($event_id));

        // Delete the event if there is only one attendee (the current user)
        if ($query->num_rows() == 1)
        {
            $query_string = "DELETE FROM events WHERE id = ?";
            $query = $this->db->query($query_string, array($event_id));
            var_dump($this->db->last_query());

            // Delete all relevant invites
            $query_string = "DELETE FROM event_invitees WHERE event_id = ?";
            $query = $this->db->query($query_string, array($event_id));

            // Delete all relevant notifications
            $query_string = "DELETE FROM notifications WHERE type = ? AND subject_id = ?";
            $query = $this->db->query($query_string, array('plan_invite', $event_id));
        }

        // Delete the plan
        $query = "DELETE FROM plans WHERE plans.id = $plan";
        $this->db->query($query);

        return "<div id=\"container\" class=\"plan_deleted\">Plan Deleted</div>";
    }

    // Copies the specified plan and sets the originator as the passed user id
    function copy_plan($plan_id, $user_id)
    {
        // Get the existing plan
        $query_string = "SELECT * FROM plans WHERE id = ?";
        $query = $this->db->query($query_string, array($plan_id));
        $row = $query->row_array();

        // Change to the argument user id and remove the previous plan id
        $row['user_id'] = $user_id;
        unset($row['id']);
        
        var_dump($row);

        // Insert the new plan.
        $query = $this->db->insert('plans', $row);
    }

    // Accepts an associative array containing plan data
    // Returns the plan id
    function add_plan($data)
    {
        // Return the id if the plan already exists
        $query_string = "SELECT * FROM plans WHERE user_id = ? AND event_id = ?";
        $query = $this->db->query($query_string, $data);
        if ($query->num_rows() > 0)
        {
            return $query->row()->id;
        }

        // Add the plan
        $query_string = "INSERT IGNORE INTO plans VALUES (DEFAULT, ?, ?)";
        $query = $this->db->query($query_string, $data);

        return $this->db->insert_id();
    }

    // Returns an HTML string for the plan panel on the right
    function display_plans()
    {
        $date_organizer = "";
        $return_string = '<div class="active_plans">';
        foreach ($this->get_plans() as $plan)
        {
            // make easy to read variables
            $id = $plan->id;
            $name = $plan->name;
            $title = $plan->title;
            $time = $plan->time;
            $date = date('l', strtotime($plan->date));
            
            if ($date_organizer != $date)
            {
                $return_string .= "<hr>$date<br><hr>";
            }
            $date_organizer = $date;
            
            $return_string .= "<div class =\"plan_content\" plan_id=\"$id\" >";
            $return_string .= $name;
            $return_string .= "</div>";
            $return_string .= "<div id=\"plan_padding\" style =\"width:100%; height:10px;\"></div>";
        }
        $return_string .= "</div>";

        return $return_string;
    }

}
?>
