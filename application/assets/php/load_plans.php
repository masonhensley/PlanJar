<div class="plans_wrapper">
    <ul id="active_plans">
        <?php
        $id = $plan->id;
        $name = $plan->name;
        $category = $plan->category;
        $time = $plan->time_of_day;
        $date_string1 = date('D (d)', strtotime($plan->date));
        $date_string2 = date('l', strtotime($plan->date));

        foreach ($result as $plan)
        {
            ?> 
            <li class ="plan_content">
                <?php
                echo $name . "  |  " . $date_string1;
                echo "<hr/>";
                echo "<p>";
                echo $category . "<br/>";
                echo $date_string2 . " " . $time;
                echo "</p>";
                ?>
            </li>
        <?php } ?>
    </ul>
</div>
