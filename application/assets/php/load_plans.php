<div id="plans">
    <ul>
        <?php
        $tracker = 0;
        foreach ($result as $plan) {
            ?> 
            <li>
                <a href="<?php echo $tracker; ?>" plan_id="<?php echo $plan->id; ?>">
                    <div style="text-align: left; width:auto; height: auto; ">
                        <div id="day_display" style="width:100%; height: auto;"> 
                            <?php
                            echo $plan->name . "  |  ";
                            $date_string = date('D (d)', strtotime($plan->date));
                            echo $date_string;
                            ?>
                        </div>
                        <?php
                        echo "<p>";
                        $date_string = date('l', strtotime($plan->date));
                        echo $plan->category . "<br/>";
                        echo $date_string . " " . $plan->time_of_day;
                        echo "</p>";
                        ?>
                    </div>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
</div>