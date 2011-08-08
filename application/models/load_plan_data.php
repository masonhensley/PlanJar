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
        // pull all user's current events
        $query = "SELECT events.id, events.date, events.time, events.title, events.privacy, events.originator_id, places.name
            FROM plans LEFT JOIN events ON plans.event_id = events.id
            LEFT JOIN places ON events.place_id = places.id
            WHERE plans.id = $plan_id";

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
        <div class="delete_plan" style="position:absolute; top:0px; right:0px; ">Delete Plan</div>
        <?php
        // Generate the invite people string
        $user_originator = $row->originator_id == $this->ion_auth->get_user()->id;
        if ($row->privacy != 'strict' || $user_originator)
        {
            ?><div class="invite_people" style="position:absolute; bottom:0px; right:0px;">Invite people</div><?php
        } else
        {
            ?><div style="font-size: 14px; position:absolute; bottom:10px; right:10px;">
                This event has <b>strict</b> privacy settings. You can't invite anyone.</div>
            <?php
        }

        return array(
            'privacy' => $row->privacy,
            'html' => ob_get_clean(),
            'event_id' => $row->id,
            'originator' => $user_originator);
    }

}
?>