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
        $plan_row = $query_result->row();

        $data_array = $this->get_plan_data_array($plan_id, $plan_row);
        $plan_html = $this->get_plan_html($plan_row);

        return array(
            'data' => $data_array,
            'html' => $plan_html,
        );
    }

    function get_plan_data_array($plan_id, $plan_row)
    {
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
        } else
        {
            $percent_male = ($number_males / $number_attending) * 100;
            $percent_female = ($number_females / $number_attending) * 100;
        }

        $data_array = array(
            'number_attending' => $number_attending,
            'number_invited' => $number_invited,
            'number_males' => $number_males,
            'number_females' => $number_females,
            'percent_male' => $percent_male,
            'percent_female' => $percent_female);

        return $data_array;
    }

    // returns html for the selected plan
    function get_plan_html($plan_row)
    {
        ob_start();
        // html to replace the data div
        ?>
        <div class="delete_plan">Delete Plan</div>
        <div class="plan_header">
            <?php
            var_dump($plan_row);
            ?>
        </div>
        <div class="plan_info">
        </div>

        <div class="plan_graphs">

            <div class="plan_gender_graph">

            </div>

            <div class="attending_graph">

            </div>

        </div>

        <br/><br/>

        <?php
        // Generate the invite people string
        $user_originator = $plan_row->originator_id == $this->ion_auth->get_user()->id;
        if ($plan_row->privacy != 'strict' || $user_originator)
        {
            ?>
            <div class="invite_people">Invite people</div>
            <div class="view_plan_location"><?php echo($plan_row->name); ?></div>
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
