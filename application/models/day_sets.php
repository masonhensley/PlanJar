<?php

class Day_sets extends CI_Model
{

    function home_set($offset)
    {
        $date = new DateTime();
        $date->add(new DateInterval('P' . $offset . 'D'));

        for ($i = 0; $i < 7; ++$i)
        {
            if ($start == 0 && $i == 0)
            {
                $display_date = 'Today';
            } else
            {
                $display_date = $date->format('D - j');
            }

            echo('<div class="day" day_offset="' . ($start + $i) . '"><div class="day_text">' . $display_date . '</div></div>');
            $date->add(new DateInterval('P1D'));
        }
        ?> 
        <div class="left_day_arrow"><</div>
        <div class="right_day_arrow">></div>

        <?php
    }

    function plan_set($offset)
    {
        
    }

}
?>
