<?php

class Day_sets extends CI_Model
{

    // Returns a string containing the necessary HTML for the day tab set in the home view
    // If $plan is true, the returned divs have class plan_day
    function day_set($offset, $plan = false)
    {
        $date = new DateTime();
        $date->add(new DateInterval('P' . $offset . 'D'));

        ob_start();
        for ($i = 0; $i < 7; ++$i)
        {
            if ($offset == 0 && $i == 0)
            {
                $display_date = 'Today';
            } else
            {
                $display_date = $date->format('D - j');
            }

            $plan_day = $plan ? 'plan_day' : 'day';
            ?>
            <div class="<?php echo($plan_day); ?>" day_offset="<?php echo($offset + $i); ?>"><?php echo($display_date); ?></div>
            <?php
            $date->add(new DateInterval('P1D'));
        }
        ?>
        <div class="left_day_arrow"><</div>
        <div class="right_day_arrow">></div>
        <?php
        return ob_get_clean();
    }

}
?>
