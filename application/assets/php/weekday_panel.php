<?php $days = array('Sun', 'Mon', 'Tues', 'Weds', 'Thurs', 'Fri', 'Sat'); ?>
<ul class="tabs">
    <li>
        <a href="<?php echo(date('w')); ?>">Today</a>
    </li>
    <li>
        <a href="<?php echo(date('w') + 1); ?>">Tom</a>
    </li>
    <?php
    for ($i = 2; $i < 7; ++$i)
    {
        ?>
        <li>
            <a href="#<?php echo((date('w') + $i) % 7); ?>"><?php echo($days[(date('w') + $i) % 7]); ?></a>
        </li>
        <?php
    }
    ?>
</ul>