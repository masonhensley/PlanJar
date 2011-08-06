<?php

class Day_sets extends CI_Model
{

    // Returns a string containing the necessary HTML for the day tab set in the home view
    function home_set($offset)
    {
        $date = new DateTime();
        $date->add(new DateInterval('P' . $offset . 'D'));

        $return_string = '';
        for ($i = 0; $i < 7; ++$i)
        {
            if ($offset == 0 && $i == 0)
            {
                $display_date = 'Today';
            } else
            {
                $display_date = $date->format('D - j');
            }

            $return_string .= '<div class="day" day_offset="' . ($offset + $i) . '">' . $display_date . '</div>';

            $date->add(new DateInterval('P1D'));
        }

        return $return_string;
    }

    // Returns a string containing the necessary HTML for the day tab set in the plan panel
    function plan_set($offset)
    {
        $off_date = new DateTime();
        $off_date->setTime(0, 0, 0);
        $off_date->add(new DateInterval('P' . $offset . 'D'));

        ob_start();
        for ($i = 0; $i < 7; ++$i)
        {
            if ($offset == 0)
            {
                $day_text = 'Today';
            } else
            {
                $day_text = $date->format('D - j');
            }
            ?>
            <div plan_day="<?php echo($i); ?>"><?php echo($day_text); ?></div>
            <?php
            $off_date->add(new DateInterval('P1D'));
        }
        return ob_get_clean();
    }

}
?>
