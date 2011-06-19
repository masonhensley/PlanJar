<!-- this populates the tabs with right right days -->

<ul class="weekdays">
    <li>
        <a href="0">Today</a>
    </li>

    <?php
    $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
    for ($i = 1; $i < 7; ++$i)
    {
        ?>
        <li>
            <a href="<?php echo($i); ?>"><?php
    // Format the displayed day name (e.g. Tue - 9).
    $day_name = $days[(date('w') + $i) % 7];
    $day_name .= ' - ' . (date('j') + $i);
    echo($day_name);
        ?>
            </a>
        </li>
        <?php
    }
    ?>
</ul>