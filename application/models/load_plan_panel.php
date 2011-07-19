<?php

class Load_plan_panel extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    function display_plans($user)
    {
        $date_organizer = "";
        ?>
        <div class="active_plans">
        <?php
        foreach ($user as $plan)
        {
            // make easy to read variables
            $id = $plan->id;
            $name = $plan->name;
            $title = $plan->title;
            $time = $plan->time_of_day;
            $date = date('l', strtotime($plan->date));
            if ($date_organizer != $date)
            {
                echo "<hr>";
                echo $date . "<br>";
                echo "<hr>";
            }
            $date_organizer = $date;
            echo "<div class =\"plan_content\" plan_id=\"$id\" >";
            echo $name;
            echo "</div>";
            echo "<div id=\"plan_padding\" style =\"width:100%; height:10px;\"></div>";
        }
        echo "</div>";
    }
}
?>
