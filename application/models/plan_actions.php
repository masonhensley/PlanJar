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
    }

    // Accepts an array containing plan data
    // Returns the plan details
    function add_plan($data, $originator = false, $privacy = '')
    {
        $event_id = $data[1];

        // Get the privacy setting if it wasn't supplied
        if ($privacy == '')
        {
            $query_string = "SELECT event_d FROM plans WHERE "
            
            $query_string = "SELECT privacy FROM events WHERE id = ?";
            $query = $this->db->query($query_string, array($this->db->insert_id()));

            $privacy = $query->row()->privacy;
        } else
        {
            // Add the plan
            $query_string = "INSERT IGNORE INTO plans VALUES (DEFAULT, ?, ?)";
            $query = $this->db->query($query_string, $data);
        }

        // Check if the user already has plans to that place at that time
        $plan_check = $this->unique_plan($event_id);
        if ($plan_check === true)
        {
            return json_encode(array(
                        'status' => 'success',
                        'originator' => $originator,
                        'event_id' => $event_id,
                        'privacy' => $privacy));
        } else
        {
            // Pre-existing plan. Return HTML for two options
            $this->load->model('event_ops');
            $choice_data = $this->event_ops->get_events_for_choice($event_id, $plan_check);
            return json_encode(array_merge(
                                    array('status' => 'conflict'), $choice_data, array('originator' => $originator)));
        }
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
                $todays_date = date('N');

                if (date('N', strtotime($plan->date)) == $todays_date)
                {
                    $date = "Today";
                } else
                {
                    $date = date('l', strtotime($plan->date));
                }
                ?>
                <div class="active_plans"> 
                    <?php
                    if ($date_organizer != $date)
                    {
                        ?>
                        <font style="font-size:11px; margin-left: -140px; color:gray;"><?php echo $date; ?><br/></font>
                        <?php
                    }
                    $date_organizer = $date;

                    // Day offset
                    $cur_date = new DateTime();
                    $cur_date->setTime(0, 0, 0);
                    $new_date = new DateTime($plan->date);
                    $day_offset = $cur_date->diff($new_date);
                    $day_offset = $day_offset->format('%a');
                    ?>
                    <div class ="plan_content" plan_id="<?php echo $id; ?>">
                        <?php
                        if ($title != '')
                        {
                            ?>
                            <font style="font-weight:bold;"><?php echo $title; ?></font><br/>
                            <font style="color:darkgray;"><?php echo "@" . $place_name; ?></font>
                            <?php
                        } else
                        {
                            echo "<b>@" . $place_name . "</b>";
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        } else
        {
            ?>
            <font style="font-style:italic;">No plans yet</font><br/><br/>
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
            AND events.id <> ?";
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
