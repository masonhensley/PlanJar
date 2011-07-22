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
            if ($start == 0 && $i == 0)
            {
                $display_date = 'Today';
            } else
            {
                $display_date = $date->format('D - j');
            }

            $return_string .= '<div class="day" day_offset="' . ($start + $i) . '">' . $display_date . '</div>';

            $date->add(new DateInterval('P1D'));
        }

        $return_string .= '<div class="left_day_arrow"><</div>' .
                '<div class="right_day_arrow">></div>';

        return $return_string;
    }

    function plan_set($offset)
    {
        
    }

}
?>
