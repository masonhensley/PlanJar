<!-- SELECT LOCATION JAVASCRIPT -->
<script type="text/javascript" src="/application/assets/js/delete_plan_button.js"></script>

<?php

class Plan_actions extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_plans($user_id)
    {
        // pull all user's current events
        $query =
                "SELECT plans.id, plans.date, plans.time_of_day, date, plans.title, plans.event_id, places.name
        FROM plans
        LEFT JOIN places
        ON plans.place_id=places.id
        WHERE plans.user_id=$user_id AND plans.date >= CURDATE()
        ORDER BY date ASC";

        // pull data
        $query_result = $this->db->query($query);
        $result = $query_result->result();
        return $result;
    }

    function load_plan_data($plan)
    {
        // pull all user's current events
        $query = "SELECT plans.id, plans.date, plans.time_of_day, plans.title, plans.event_id, places.name
        FROM plans
        LEFT JOIN places
        ON plans.place_id=places.id
        WHERE plans.id=$plan";

        // pull data
        $query_result = $this->db->query($query);

        // initialize plan information
        $time_of_day;
        $date;
        $name;

        foreach ($query_result->result() as $row)
        {
            // populate variables
            $time_of_day = $row->time_of_day;
            // get rid of the "-"
            $time_of_day = str_replace("_", " ", $time_of_day);

            $date = $row->date;
            $date = date('m/d', strtotime($date));
            $name = $row->name;
            $title = $row->title;
        }

        // html to replace the data div
        $htmlString = "
        <div style=\"font-size:20px; width:100%; height:230px; color:darkblue; text-align: center;\">
        $title at $name | $time_of_day | $date
        </div><br/><br/>
        <div class=\"delete_plan_container\"style=\"font-size: 20px; text-align:left;\">
        <div class=\"delete_plan\">Delete Plan</div></div>";

        return $htmlString;
    }

    // function to delete plan from database
    function delete_plan($plan)
    {
        $query = "DELETE FROM plans WHERE plans.id=$plan";
        $this->db->query($query);
        $return_string = "<div id=\"container\" class=\"plan_deleted\">Plan Deleted</div>";

        return $return_string;
    }

    // Copies the specified plan and sets the originator as the passed user id
    function copy_plan($plan_id, $user_id)
    {
        // et the existing plan
        $query_string = "SELECT * FROM plans WHERE id = ?";
        $query = $this->db->query($query_string, array($plan_id));
        $row = $query->row_array();

        // Change to the argument user id
        $row['user_id'] = $user_id;

        // Insert the new plan.
        $query = $this->db->insert('plans', $row);
    }

}
?>
