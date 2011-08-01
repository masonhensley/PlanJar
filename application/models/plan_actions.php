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

        return $query_result;
    }

    // Returns an array containing privacy type and the HTML for the plan
    function load_plan_data($plan)
    {
        // pull all user's current events
        $query = "SELECT events.id, events.date, events.time, events.title, events.privacy, places.name
            FROM plans LEFT JOIN events ON plans.event_id = events.id
            LEFT JOIN places ON events.place_id = places.id
            WHERE plans.id = $plan";

        // pull data
        $query_result = $this->db->query($query);
        $row = $query_result->row();

        // populate variables
        $time = $row->time;

        // get rid of the "-"
        $time = str_replace("_", " ", $time);

        $date = $row->date;
        $date = date('m/d', strtotime($date));
        $name = $row->name;
        $title = $row->title;

        // Generate the invite people string
        if ($row->privacy != 'strict')
        {
            $invite_people = "<div class=\"invite_people\">Invite people</div>";
        } else
        {
            $invite_people = "<div style=\"font-size: 14px; float: right; line-height: 30px; margin-right: 10px;\">
                This event has <b>strict</b> privacy settings. You can't invite anyone.</div>";
        }

        // html to replace the data div
        $htmlString = "
        <div style=\"font-size:20px; width:100%; height:230px; color:darkblue; text-align: center;\">
        $title at $name | $time | $date
        </div><br/><br/>
        <div class=\"delete_plan_container\"style=\"font-size: 20px; text-align:left;\">
        <div class=\"delete_plan\">Delete Plan</div>
        $invite_people
        </div>";

        return array('privacy' => $row->privacy, 'html' => $htmlString, 'event_id' => $row->id);
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
            $query = $this->db->query($query_string, array('event_invite', $event_id));
        }

        // Delete the plan
        $query = "DELETE FROM plans WHERE plans.id = $plan";
        $this->db->query($query);

        return "<div id=\"container\" class=\"plan_deleted\">Plan Deleted</div>";
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
        $plans_result = $this->get_plans();
        ob_start(); // start the output buffer 

        if ($plans_result->num_rows() > 0)
        {
            foreach ($plans_result->result() as $plan)
            {
                // make easy to read variables
                $id = $plan->id;
                $place_name = $plan->name;
                $title = $plan->title;
                $time = $plan->time;
                $date = date('l', strtotime($plan->date));
                ?>
                <div class="active_plans"> 
                    <?php
                    if ($date_organizer != $date)
                    {
                        ?>
                        <font style="font-size:16px; color:gray;"><?php echo $date; ?><br/></font>
                        <?php
                    }
                    $date_organizer = $date;
                    ?>
                        <div class ="plan_content" plan_id="<?php echo $id; ?>">
                        <?php
                        if ($title != '')
                        {
                            ?>
                        <font style="font-weight:bold;"><?php echo $title; ?></font><br/>
                        <font style="color:darkgray";><?php echo $place_name; ?></font>
                            <?php
                        } else
                        {
                            echo "<b>$place_name</b>";
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        } else
        {
            ?>
            <font style="font-style:italic;">Nothing to show</font><br/><br/>
            <?php
        }
        return ob_get_clean();
    }

}
?>
