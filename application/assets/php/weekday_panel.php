<ul class="tabs">
    <li>
        <a href="0">Today</a>
    </li>
    <li>
        <a href="1">Tom</a>
    </li>
    <?php
    $days = array('Sun', 'Mon', 'Tues', 'Weds', 'Thurs', 'Fri', 'Sat');
    for ($i = 2; $i < 7; ++$i)
    {
        ?>
        <li>
            <a href="#<?php echo($i); ?>"><?php
    // Format the displayed day name (e.g. Tue - 9).
    $day_name = $days[(date('w') + $i) % 7];
    $day_name .= ' - ' . date('j');
    echo($day_name);
        ?>
            </a>
        </li>
        <?php
    }
    ?>
</ul>