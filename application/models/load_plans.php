<!-- SELECT LOCATION JAVASCRIPT -->
<script type="text/javascript" src="/application/assets/js/delete_plan_button.js"></script>

<?php

class Load_plans extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getPlans($user_id)
    {
        $this->load->database();

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

    function loadPlanData($plan)
    {
        // pull all user's current events
        $query = "SELECT plans.id, plans.time_of_day, plans.date, places.name, plan_categories.category
        FROM plans 
        LEFT JOIN places 
        ON plans.place_id=places.id 
        LEFT JOIN plan_categories
        ON plan_categories.id=plans.category_id
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
            $category = $row->category;
        }

        // html to replace the data div
        $htmlString = "
        <div style=\"font-size:20px; width:100%; height:230px; color:darkblue; text-align: center;\">
        $category at $name | $time_of_day | $date
        </div><br/><br/>
        <div class=\"delete_plan_container\"style=\"font-size: 20px; text-align:left;\">
        <div class=\"delete_plan\">Delete Plan</div></div>";

        return $htmlString;
    }

    // function to delete plan from database
    function deletePlan($plan)
    {
        $query = "DELETE FROM plans WHERE plans.id=$plan";
        $this->db->query($query);
        $return_string = "<div id=\"container\" class=\"plan_deleted\">Plan Deleted</div>";

        return $return_string;
    }

}
?>
