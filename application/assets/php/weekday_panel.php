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
            <a href="#<?php echo($i); ?>"><?php echo($days[(date('w') + $i) % 7]); ?></a>
        </li>
        <?php
    }
    ?>
</ul>