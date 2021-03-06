<?php

class Load_plan_data extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Returns an array containing privacy type and the HTML for the plan
    function display_plan_data($plan_id, $friend_plan)
    {
        // pull info for the plan
        $query = "SELECT events.id, plans.id AS plan_id, events.date, events.time, events.clock_time, events.title, events.privacy, events.originator_id, events.description, places.name, places.id AS place_id
            FROM plans LEFT JOIN events ON plans.event_id = events.id
            LEFT JOIN places ON events.place_id = places.id
            WHERE plans.id = $plan_id";

        // pull data
        $query_result = $this->db->query($query);
        $plan_row = $query_result->row();

        $data_array = $this->get_plan_data_array($plan_id, $plan_row);
        $plan_html = $this->get_plan_html($plan_row, $data_array, $friend_plan);

        return array(
            'data' => $data_array,
            'html' => $plan_html,
        );
    }

    function get_plan_data_array($plan_id, $plan_row)
    {
        // set the plan time
        $show_day = date("l", strtotime($plan_row->date));
        $show_date = date("F jS", strtotime($plan_row->date));
        $show_time = date("g:i a", strtotime($plan_row->clock_time));

        $time_string = '';
        if (!$plan_row->clock_time)
        {
            $time_string = "$show_day $plan_row->time, $show_date";
        } else
        {
            $time_string = "$show_day at $show_time, $show_date";
        }

        // get #attending, #male, #female
        $query = "SELECT event_id FROM plans WHERE id=$plan_id";
        $result = $this->db->query($query);
        $event_id = $result->row()->event_id;

        // select all the people attending the event
        $query = "SELECT DISTINCT user_meta.user_id, user_meta.sex FROM plans JOIN user_meta ON user_meta.user_id=plans.user_id WHERE plans.event_id=$event_id";
        $result = $this->db->query($query);

        $number_females = 0;
        $number_males = 0;

        foreach ($result->result() as $person_attending)
        {
            if ($person_attending->sex == 'male')
            {
                $number_males++;
            } else
            {
                $number_females++;
            }
        }

        $number_attending = $number_males + $number_females;

        // get #not responded
        $query = "
                SELECT DISTINCT user_meta.user_id
                FROM notifications
                JOIN user_meta ON notifications.user_id=user_meta.user_id
                LEFT JOIN school_data ON user_meta.school_id=school_data.id
                WHERE notifications.subject_id=$event_id AND notifications.type='event_invite' AND notifications.accepted=0
                ";
        $result = $this->db->query($query);
        $not_responded = $result->num_rows();
        
        // get number invited
        $query = "
                SELECT DISTINCT user_meta.user_id
                FROM notifications
                JOIN user_meta ON notifications.user_id=user_meta.user_id
                LEFT JOIN school_data ON user_meta.school_id=school_data.id
                WHERE notifications.subject_id=$event_id AND notifications.type='event_invite'
                ";
        $result = $this->db->query($query);
        $number_invited = $result->num_rows();

        if ($number_attending == 0)
        {
            $percent_male = 0;
            $percent_female = 0;
        } else
        {
            $percent_male = ($number_males / $number_attending) * 100;
            $percent_female = ($number_females / $number_attending) * 100;
        }
        if ($number_invited == 0)
        {
            $percent_attending = 0;
        } else
        {
            $percent_attending = ($number_attending / ($number_attending + $not_responded)) * 100;
        }

        // get originator name
        $query = "
        SELECT user_meta.first_name, user_meta.last_name FROM plans 
        JOIN events ON events.id=plans.event_id 
        LEFT JOIN user_meta ON user_meta.user_id=events.originator_id
        WHERE plans.id=$plan_id
        ";

        $result = $this->db->query($query);

        if ($result->row()->first_name == NULL)
        {
            $originator_name = 'n/a, no event title (just going)';
        } else
        {
            $originator_name = $result->row()->first_name . " " . $result->row()->last_name;
        }

        $this->load->helper('day_offset');
        $data_array = array(
            'not_responded' => $not_responded,
            'time_string' => $time_string,
            'number_invited' => $number_invited,
            'number_attending' => $number_attending,
            'originator_name' => $originator_name,
            'originator_id' => $plan_row->originator_id == $this->ion_auth->get_user()->id,
            'date' => get_day_offset($plan_row->date),
            'location_id' => $plan_row->place_id,
            'percent_attending' => $percent_attending,
            'percent_male' => $percent_male,
            'percent_female' => $percent_female,
            'privacy' => $plan_row->privacy,
            'event_id' => $plan_row->id
        );

        return $data_array;
    }

    // returns html for the selected plan
    function get_plan_html($plan_row, $data_array, $friend_plan)
    {
        $user = $this->ion_auth->get_user();

        $data_array = $this->make_date_readable($data_array);
        ob_start();
        // html to replace the data div
        ?>
        <div class="view_plan_location">
            View Location Info
        </div>

        <div class="plan_header">
            <font style="color:gray;">&nbsp;Plan: </font>
            <?php
            if ($plan_row->title != '')
            {
                ?><font style="color:black; font-size:25px; font-weight:bold;"><?php echo $plan_row->title; ?></font><br/>
                <?php
            } else
            {
                ?><font style="color:black; font-size:25px; font-weight:bold;"><?php echo $plan_row->name; ?></font><?php
        }
            ?>

        </div>
        <div class="info_and_graph_wrapper">
            <div class="plan_info_wrapper">

                <div class="plan_info">
                    <font style="color:gray">Location:</font> <font style="font-weight:bold;font-size:15px;">
                    <?php echo "@" . $plan_row->name; ?></font><br/>
                    <font style="color:gray">Created By: </font><font style="font-weight:bold;">
                    <?php echo $data_array['originator_name']; ?></font>
                    <br/>
                    <font style="color:gray">Time: </font> <font style="font-weight:bold;">
                    <?php echo str_replace('_', ' ', $data_array['time_string']); ?></font>
                    <br/><br/>
                    <font style="color:gray">Accepted </font><font style="font-weight:bold;">
                    <?php echo $data_array['number_attending']; ?></font>
                    &nbsp;&nbsp;
                    <font style="color:gray">Not Responded </font><font style="font-weight:bold;">
                    <?php echo $data_array['not_responded']; ?></font><div id="view_attendees" plan_id="<?php echo $plan_row->plan_id ?>">Guest List</div>
                    <br/><br/>
                    <font style="font-weight:bold;">Description</font>
                    <br/>
                    <font style="color:gray;"><?php
            if ($plan_row->description)
            {
                echo($plan_row->description);
            } else
            {
                        ?>
                        <i>No description</i>
                        <?php
                    }
                    ?></font>
                </div>
            </div>

            <div class="plan_graphs">
                <font style="position:absolute;top:23px;left:53px; color:gray; font-size:12px;">% of invitations accepted so far</font>
                <div class="attending_graph"></div>
                <div class="attending_data_container">
                    <div style="display:inline-block; width:12px; height:12px; background-color:blueviolet;"></div>
                    <div style="display:inline-block; font-weight:bold; font-size:12px;"><?php echo $data_array['percent_attending'] . "%"; ?></div>
                    <div style="display:inline-block; font-weight: bold; font-size:12px;">have accepted</div>
                </div>

                <font style="position:absolute;top:119px;left:68px; font-size:12px; color:gray;">guest gender breakdown</font>
                <div class="plan_gender_graph"></div>
                <div class="female_data_container">
                    <div style="display:inline-block; width:12px; height:12px; background-color:#E80C7A;"></div>
                    <div style="display:inline-block; font-weight:bold;font-size:12px;"><?php echo $data_array['percent_female'] . "%"; ?></div>
                    <div style="display:inline-block; font-size:12px; font-weight: bold;">female</div>
                </div>
                <div class="male_data_container">
                    <div style="display:inline-block; width:12px; height:12px; background-color:#3FA9F5;"></div>
                    <div style="display:inline-block; font-size:12px; font-weight:bold;font-size:12px;"><?php echo $data_array['percent_male'] . "%"; ?></div>
                    <div style="display:inline-block; font-size:12px; font-weight: bold;">male</div>
                </div>

            </div>
        </div>

        <?php
        if ($friend_plan != 'true')
        {
            // User's plan
            // Generate the invite people string
            $user_originator = $plan_row->originator_id == $this->ion_auth->get_user()->id;
            if ($plan_row->privacy != 'strict' || $user_originator)
            {
                ?>
                <div class="invite_people ipg">Invite people</div>

                <?php
            } else
            {
                ?><div style="font-size: 14px; position:absolute; bottom:10px; left:10px;">
                    This event has <b>strict</b> privacy settings. You can't invite anyone.</div>
                <?php
            }
            ?>
            <div class="delete_plan">Delete plan</div>
            <?php
        } else
        {
            // if viewing a friend's plan, figure out if you are already attending    
            $query = "SELECT events.id FROM plans JOIN events ON events.id=plans.event_id AND plans.event_id=$plan_row->id
                WHERE plans.user_id=$user->id";

            $result = $this->db->query($query);
            $already_attending = $result->row();

            // if you are already attending an event, you wont have the options to "add to plans"
            if (count($already_attending) < 1)
            {
                $already_attending = 0;
            } else
            {
                $already_attending = 1;
            }
            // Another user's plan
            if (!$already_attending)
            {
                ?>
                <div class="make_plan">Add to plans</div>
                <?php
            } else
            {
                ?>
                <div class="already_attending">You are attending</div>
                <div class="invite_people ipg">Invite people</div>
                <?php
            }
        }

        return ob_get_clean();
    }

    function make_date_readable($data_array)
    {
        // make the percentage readable
        if (strlen($data_array['percent_attending']) > 3)
        {
            $data_array['percent_attending'] = substr($data_array['percent_attending'], 0, 3);
            if (substr($data_array['percent_attending'], -1) == ".")
            {
                $data_array['percent_attending'] = substr($data_array['percent_attending'], 0, -1);
            }
        }
        if (strlen($data_array['percent_male']) > 3)
        {
            $data_array['percent_male'] = substr($data_array['percent_male'], 0, 3);
            if (substr($data_array['percent_male'], -1) == ".")
            {
                $data_array['percent_male'] = substr($data_array['percent_male'], 0, -1);
            }
        }
        if (strlen($data_array['percent_female']) > 3)
        {
            $data_array['percent_female'] = substr($data_array['percent_female'], 0, 3);
            if (substr($data_array['percent_female'], -1) == ".")
            {
                $data_array['percent_female'] = substr($data_array['percent_female'], 0, -1);
            }
        }
        return $data_array;
    }

}
?>
