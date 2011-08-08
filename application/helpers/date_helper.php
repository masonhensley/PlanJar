<?php

// Returns the day offset (number of days between today and the given date)
function get_day_offset($date)
{
    $cur_date = new DateTime();
    $cur_date->setTime(0, 0, 0);

    $new_date = new DateTime($date);

    $day_offset = $cur_date->diff($new_date);
    return $day_offset->format('%a');
}

?>
