<div class="plans_wrapper">
    <ul class="active_plans">
        <?php
        foreach ($result as $plan)
        {
            // make easy to read variables
            // not all variables are used
            $id = $plan->id;
            $name = $plan->name;
            $category = $plan->category;
            $time = $plan->time_of_day;
            $date_string1 = date('D', strtotime($plan->date));
            
            ?> 
            <li class ="plan_content" plan_id="<?php echo $id; ?>" >
                <?php
                echo $name . "  |  " . $date_string1;
                ?>
            </li>
        <?php } ?>
    </ul>
</div>
