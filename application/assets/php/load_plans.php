<div class="plans_wrapper">
    <ul id="active_plans">
        <?php
        foreach ($result as $plan)
        {
            // make easy to read variables
            $id = $plan->id;
            $name = $plan->name;
            $category = $plan->category;
            $time = $plan->time_of_day;
            $date_string1 = date('D (d)', strtotime($plan->date));
            $date_string2 = date('l', strtotime($plan->date));
            ?> 
            <li class ="plan_content">
                <?php
                echo $name . "  |  " . $date_string1;
                echo "<hr/>";
                echo $category . "<br/>";
                echo $date_string2 . " " . $time;
                ?>
            </li>
        <?php } ?>
    </ul>
</div>
