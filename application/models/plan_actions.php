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

        ob_start();
        // html to replace the data div
        ?>

        <div class="plan_body">
            <?php
            echo $title;
            echo $name;
            echo $time;
            echo $date;
            ?>
        </div><br/><br/>
        <div class="delete_plan" style="position:absolute; bottom:0px; left:0px; ">Delete Plan</div>
        <?php
        // Generate the invite people string
        if ($row->privacy != 'strict')
        {
            ?><div class="invite_people" style="position:absolute; bottom:0px; right:0px;">Invite people</div><?php
        } else
        {
            ?><div style="font-size: 14px; position:absolute; bottom:10px; right:10px;">
                This event has <b>strict</b> privacy settings. You can't invite anyone.</div>
            <?php
        }

        return array('privacy' => $row->privacy, 'html' => ob_get_clean(), 'event_id' => $row->id);
    }

    // function to delete plan from database
    function delete_plan($plan)
    {
        // Get the associated event
        $query_string = "SELECT event_id FROM plans WHERE id = ?";
        $query = $this->db->query($query_string, array($plan));
        $event_id = $query->row()->event_id;

        // Delete the event (necessary checks are inside)
        $this->load->model('event_ops');
        $this->event_ops->delete_event($event_id);

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
        $query_string = "INSERT INTO plans VALUES (DEFAULT, ?, ?)";
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
                        <br/><font style="font-size:16px; color:gray;"><?php echo $date; ?><br/></font>
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
                            <font style="color:darkgray;"><?php echo $place_name; ?></font>
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

    // Returns true if the user has no plan to another event at the given place at the given time
    // Returns the prior plan id otherwise
    // Don't forget to use === for the return value
    function unique_plan($event_id)
    {
        // Get the event info
        $query_string = "SELECT place_id, date, time FROM events WHERE id = ?";
        $query = $this->db->query($query_string, array($event_id));
        $event_row = $query->row();

        // Get the list of plans to the given location at the given time
        $query_string = "SELECT events.id
            FROM plans JOIN events
            ON plans.event_id = events.id
            WHERE plans.user_id = ? AND events.date = ? AND events.time = ? AND events.place_id = ?
            AND events.title <> '' AND events.id <> ?";
        $query = $this->db->query($query_string, array(
                    $this->ion_auth->get_user()->id,
                    $event_row->date,
                    $event_row->time,
                    $event_row->place_id,
                    $event_id
                ));

        if ($query->num_rows() > 0)
        {
            // Prior plan. Return the event id
            return $query->row()->id;
        } else
        {
            // No prior plans
            return true;
        }
    }

}
?>
