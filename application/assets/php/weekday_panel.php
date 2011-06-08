<?php $days = array('Sun', 'Mon', 'Tues', 'Weds', 'Thurs', 'Fri', 'Sat'); ?>
<ul class="tabs">
    <li>
        <a href="<?php echo(date('w')); ?>">Today</a>
    </li>
    <li>
        <a href="<?php echo(date('w') + 1); ?>">Tom</a>
    </li>
</ul>