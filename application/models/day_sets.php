<?php

class Day_sets extends CI_Model
{

    // Returns a string containing the necessary HTML for the day tab set in the home view
    function home_set($offset)
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
            ?>
            <div class="day" day_offset="<?php echo($offset + $i); ?>"><?php echo($display_date); ?></div>
            <?php
            $date->add(new DateInterval('P1D'));
        }
        ?>
        <div class="left_day_arrow"><</div>
        <div class="right_day_arrow">></div>
        <?php
        return ob_get_clean();
    }

    // Returns a string containing the necessary HTML for the day tab set in the plan panel
    function plan_set($offset)
    {
        $date = new DateTime();
        $date->setTime(0, 0, 0);
        $date->add(new DateInterval('P' . $offset . 'D'));

        ob_start();
        for ($i = 0; $i < 7; ++$i)
        {
            if ($offset == 0 && $i == 0)
            {
                $day_text = 'Today';
            } else
            {
                $day_text = $date->format('D - j');
            }
            ?>
            <div plan_day="<?php echo($i); ?>"><?php echo($day_text); ?></div>
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
