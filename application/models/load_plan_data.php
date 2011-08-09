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
        $query = "SELECT events.id, events.date, events.time, events.title, events.privacy, events.originator_id, places.name, places.id AS place_id
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
        $time_string = ""; // fix this later!
        // get #attending, #male, #female
        $query = "SELECT event_id FROM plans WHERE id=$plan_id";
        $result = $this->db->query($query);
        $event_id = $result->row()->event_id;

        // select all the people attending the event
        $query = "SELECT user_meta.sex FROM plans JOIN user_meta ON user_meta.user_id=plans.user_id WHERE plans.event_id=$event_id";
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

        // get #invited
        $query = "
            SELECT event_invites.user_id FROM plans 
            JOIN events ON plans.event_id=events.id
            JOIN event_invites ON events.id=event_invites.event_id
            WHERE plans.id=$plan_id
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
            $percent_attending = ($number_attending / $number_invited) * 100;
        }

        // get originator name
        $query = "
        SELECT user_meta.first_name, user_meta.last_name FROM plans 
        JOIN events ON events.id=plans.event_id 
        JOIN user_meta ON user_meta.user_id=events.originator_id
        WHERE plans.id=$plan_id
        ";

        $result = $this->db->query($query);
        $originator_name = $result->row()->first_name . " " . $result->row()->last_name;

        $this->load->helper('day_offset');
        $data_array = array(
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
        $data_array = $this->make_date_readable($data_array);
        ob_start();
        // html to replace the data div
        ?>
        <div class="view_plan_location">
            View Location Info
        </div>

        <div class="plan_header">
            <font style="color:gray;">Plan: </font>
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

        <div class="plan_info">

            <font style="color:gray">Location</font> <font style="font-weight:bold;font-size:15px;">
            <?php echo "@" . $plan_row->name; ?></font><br/>
            <font style="color:gray">Created By </font><font style="font-weight:bold;">
            <?php echo $data_array['originator_name']; ?></font><br/>
            <font style="color:gray">Time </font> <font style="font-weight:bold;">
            <?php echo $data_array['time_string']; ?></font>
            <br/><hr/>
            <font style="color:gray">Invited </font><font style="font-weight:bold;">
            <?php echo $data_array['number_invited']; ?></font>
            &nbsp;&nbsp;&nbsp;
            <font style="color:gray">Accepted </font><font style="font-weight:bold;">
            <?php echo $data_array['number_attending']; ?></font><br/>
            <font style="color:gray">Description</font>
        </div>

        <div class="plan_graphs">

            <div style="position:absolute; width:12px; height:12px; background-color:#E80C7A;top:157px; left:133px;"></div>
            <div style="position:absolute; width:12px; height:12px; background-color:#3FA9F5;top:157px;left:25px;"></div>
            <div style="position:absolute; width:12px; height:12px; background-color:blueviolet;top:47px;left:25px;"></div>

            <div style="position:absolute;font-weight:bold;font-size:12px;top:47px; left:40px;"><?php echo $data_array['percent_attending'] . "%"; ?></div>
            <div style="position:absolute;font-weight:bold;font-size:12px;top:157px;left:40px;"><?php echo $data_array['percent_male'] . "%"; ?></div>
            <div style="position:absolute;font-weight:bold;font-size:12px;top:157px;left:147px;"><?php echo $data_array['percent_female'] . "%"; ?></div>

            <div style="position:absolute;top:154px; left:69px;font-weight: bold;">male</div>
            <div style="position:absolute;top:154px;left:175px; font-weight: bold;">female</div>
            <div style="position:absolute; top:44px;left:68px;font-weight: bold;">have accepted so far</div>



            <div class="plan_gender_graph">

            </div>

            <div class="attending_graph">

            </div>

        </div>

        <?php
        if (!$friend_plan)
        {
            // User's plan
            // Generate the invite people string
            $user_originator = $plan_row->originator_id == $this->ion_auth->get_user()->id;
            if ($plan_row->privacy != 'strict' || $user_originator)
            {
                ?>
                <div class="invite_people">Invite people</div>

                <?php
            } else
            {
                ?><div style="font-size: 14px; position:absolute; bottom:10px; right:10px;">
                    This event has <b>strict</b> privacy settings. You can't invite anyone.</div>
                <?php
            }
            ?>
            <div class="delete_plan"></div>
            <?php
        } else
        {
            // Another user's plan
            ?>
            <div class="make_plan">Make a plan here</div>
            <?php
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
