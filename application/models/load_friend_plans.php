<?php

class Load_friend_plans extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    function populate_plans($friend_id) // populate friends plans or all plans
    {
        $user = $this->ion_auth->get_user();

        if ($friend_id != 'all')
        {
            $query = "
            SELECT DISTINCT plans.id, events.date, events.time, events.title, plans.event_id, places.name
            FROM plans
            JOIN events ON events.id=plans.event_id AND events.date>=CURDATE()
            LEFT JOIN event_invites ON event_invites.event_id=events.id
            JOIN places ON events.place_id=places.id
            WHERE plans.user_id=$friend_id AND (events.privacy='open' OR event_invites.user_id=$user->user_id)
            ORDER BY date ASC
                ";
            $result = $this->db->query($query);
            $plans_html = $this->_populate_friend_plans($result, $friend_id);
        } else
        {
            // if the all button is selected
            $this->load->model('load_locations');
            $friend_ids = $this->load_locations->get_friend_ids();
            if (count($friend_ids) > 0)
            {
                $query = "
            SELECT DISTINCT plans.id, events.date, events.time, events.title, plans.event_id, places.name
            FROM plans
            JOIN events ON events.id=plans.event_id AND events.date>=CURDATE()
            LEFT JOIN event_invites ON event_invites.event_id=events.id
            JOIN places ON events.place_id=places.id
            WHERE (events.privacy='open' OR event_invites.user_id=$user->user_id) AND (
                    ";
                foreach ($friend_ids as $friend_id)
                {
                    $query .= "plans.user_id=$friend_id OR ";
                }
                $query = substr($query, 0, -4);
                $query .= ") ORDER BY date ASC";

                $result = $this->db->query($query);
                $plans_html = $this->_populate_friend_plans($result, 'all');
            } else
            {
                $plans_html = '<i>No upcoming plans.</i>';
            }
        }

        echo $plans_html;
    }

    // returns plans made at a location visible to the user
    function get_location_plans($place_id)
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT DISTINCT events.date, plans.id, plans.event_id, events.time, events.title, places.name
                  FROM places
                  JOIN events ON events.place_id=places.id AND events.date>=CURDATE()
                  JOIN plans ON plans.event_id=events.id
                  LEFT JOIN event_invites ON event_invites.event_id=events.id
                  WHERE places.id=$place_id AND (events.privacy='open' OR event_invites.user_id=$user->user_id)
                  ORDER BY events.date ASC
                  ";
        $result = $this->db->query($query);

        if ($result->num_rows > 0)
        {
            $plans_html = $this->_populate_location_plans($result, $result->row()->name);
        } else
        {
            $name_query = $this->db->query("SELECT name FROM places WHERE id = ?", array($place_id));
            $plans_html = $this->_populate_location_plans($result, $name_query->row()->name);
        }

        return $plans_html;
    }

    // populates modal with plans
    function _populate_friend_plans($plans_result, $friend_id)
    {
        ob_start(); // start the output buffer
        ?>
        <div class="friend_plan_back_button">
            Back 
        </div>
        <br/><br/>
        <?php
        if ($friend_id == 'all')
        {
            ?>
            <font style="font-size:18px;font-weight:bold; color:navy;text-align:center;"><?php echo "Upcoming Friends' Plans";
            ?></font> <?php
        } else
        {
            $friend_name = $this->ion_auth->get_user($friend_id)->first_name;
            ?>
            <font style="font-size:18px;font-weight:bold; color:navy;text-align:center;"><?php echo " " . $friend_name . "'s Plans";
            ?></font> <?php
        }
        ?>

        <?php
        if ($plans_result->num_rows() > 0)
        {
            $date_organizer = "";
            $plan_ids_shown = array();

            foreach ($plans_result->result() as $plan)
            {

                if (!in_array($plan->event_id, $plan_ids_shown))
                {
                    // make easy to read variables
                    $plan_ids_shown[] = $plan->event_id; // make sure events aren't duplicated

                    $id = $plan->id;
                    $place_name = $plan->name;
                    $title = $plan->title;
                    $time = $plan->time;
                    $todays_date = date('F j Y');

                    if (date('F j Y', strtotime($plan->date)) == $todays_date)
                    {
                        $date = "Today";
                    } else
                    {
                        $date = date('l (M jS)', strtotime($plan->date));
                    }
                    ?>
                    <div class="active_plans"> 
                        <?php
                        if ($date_organizer != $date)
                        {
                            ?>
                            <div class="plan_date_container">
                                <font style="font-size:11px; color:gray;"><?php echo $date; ?><br/></font>
                            </div>
                            <?php
                        }
                        $date_organizer = $date;

                        $this->load->helper('day_offset');
                        $day_offset = get_day_offset($plan->date);
                        ?>
                        <div class ="friend_plan_content" plan_id="<?php echo $id; ?>" day_offset="<?php echo($day_offset); ?>">
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
            }
        } else
        {
            ?>
            <br/><hr/><font style="font-style:italic;color:gray; position: relative;top:3px;">No plans yet</font><br/><br/>
            <?php
        }
        return ob_get_clean();
    }

    function _populate_location_plans($plans_result, $place_name)
    {
        ob_start(); // start the output buffer
        ?>
        <div class="display_message">
            <font style="color: gray;">Events happening at
            <b style="color: #2D4853;"><?php echo($place_name); ?></b>
            this week
            </font>
        </div>
        <?php
        if ($plans_result->num_rows() > 0)
        {
            $date_organizer = "";
            $plan_ids_shown = array();

            foreach ($plans_result->result() as $plan)
            {

                if (!in_array($plan->event_id, $plan_ids_shown))
                {
                    // make easy to read variables
                    $plan_ids_shown[] = $plan->event_id; // make sure events aren't duplicated

                    $id = $plan->id;
                    $place_name = $plan->name;
                    $title = $plan->title;
                    $time = $plan->time;
                    $todays_date = date('F j Y');

                    if (date('F j Y', strtotime($plan->date)) == $todays_date)
                    {
                        $date = "Today";
                    } else
                    {
                        $date = date('l (M jS)', strtotime($plan->date));
                    }
                    ?>
                    <div class="active_plans">
                        <?php
                        if ($date_organizer != $date)
                        {
                            ?>
                            <div class="plan_date_container">
                                <font style="font-size:11px;  color:gray;"><?php echo $date; ?><br/></font>
                            </div>
                            <?php
                        }
                        $date_organizer = $date;

                        $this->load->helper('day_offset');
                        $day_offset = get_day_offset($plan->date);
                        ?>
                        <div class ="location_plan_content" plan_id="<?php echo $id; ?>" day_offset="<?php echo($day_offset); ?>">
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
            }
        } else
        {
            ?>
            <br/><font style="font-style:italic;color:gray; position: relative;top:3px;">No plans yet</font><br/><br/>
            <?php
        }
        return ob_get_clean();
    }

}
?>
