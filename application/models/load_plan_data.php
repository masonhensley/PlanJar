<?php

class Load_plan_data extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Returns an array containing privacy type and the HTML for the plan
    function display_plan_data($plan_id)
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
        $plan_html = $this->get_plan_html($plan_row, $data_array);

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
        $query = "SELECT user_meta.sex FROM plans JOIN user_meta ON plans.user_id=user_meta.user_id WHERE plans.id=$plan_id";
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
            $percent_attending = 0;
        } else
        {
            $percent_male = $number_males / $number_attending;
            $percent_female = $number_females / $number_attending;
            $percent_attending = $number_invited / $number_attending;
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
            'date' => get_day_offset($plan_row->date),
            'location_id' => $plan_row->place_id,
            'percent_attending' => $percent_attending,
            'percent_male' => $percent_male,
            'percent_female' => $percent_female);

        return $data_array;
    }

    // returns html for the selected plan
    function get_plan_html($plan_row, $data_array)
    {
        ob_start();
        // html to replace the data div
        ?>
        <div class="delete_plan">Delete Plan</div>
        <div class="view_plan_location">View Location Info</div>

        <div class="plan_header">
            <?php
            if ($plan_row->title != '')
            {
                ?><font style="color:black; font-size:20px; font-weight:bold;"><?php echo $plan_row->title; ?></font><br/>
                <?php
            } else
            {
                ?><font style="color:black; font-size:20px; font-weight:bold;"><?php echo $plan_row->name; ?></font><?php
        }
            ?>
            <hr/>
        </div>

        <div class="plan_info">
            <font style="color:gray">Location</font> <font style="font-weight:bold;font-size:15px;">
            <?php echo "@" . $plan_row->name; ?></font><br/>
            <font style="color:gray">Created By </font><font style="font-weight:bold;">
            <?php echo $data_array['originator_name']; ?></font><br/>
            <font style="color:gray">Time </font> <font style="font-weight:bold;">
            <?php echo $data_array['time_string']; ?></font>
            <br/><hr/><br/>
            <font style="color:gray">Invited </font><font style="font-weight:bold;">
            <?php echo $data_array['number_invited']; ?></font>
            &nbsp;&nbsp;&nbsp;
            <font style="color:gray">Accepted </font><font style="font-weight:bold;">
            <?php echo $data_array['number_attending']; ?></font>
        </div>

        <div class="plan_graphs">

            <div class="plan_gender_graph">

            </div>

            <div class="attending_graph">

            </div>

        </div>

        <?php
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

        return ob_get_clean();
    }

}
?>
