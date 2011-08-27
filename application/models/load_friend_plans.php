<?php

class Load_friend_plans extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    function populate_plans($friend_id)
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
            }
        }

        echo $plans_html;
    }

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

                if (in_array($plan->id, $plan_ids_shown))
                {
                    // make easy to read variables
                    $plan_ids_shown[] = $plan->id;

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
                        $date = date('l (jS)', strtotime($plan->date));
                    }
                    ?>
                    <div class="active_plans"> 
                        <?php
                        if ($date_organizer != $date)
                        {
                            ?>
                            <font style="font-size:11px; margin-left: -114px; color:gray;"><?php echo $date; ?><br/></font>
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
            <br/><hr/><font style="font-style:italic;">No plans yet</font><br/><br/>
            <?php
        }
        return ob_get_clean();
    }

}
?>
