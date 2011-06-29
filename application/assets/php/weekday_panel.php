<!-- this populates the tabs with right right days -->

<div id="day_tabs">
    <div class="day_tab" day_offset="0">Today</div>

    <?php
    $date = new DateTime();
    for ($i = 1; $i < 7; ++$i)
    {
        $date->add(new DateInterval('P1D'));
        ?>
        <div class="day_tab" day_offset="<?php echo($i); ?>"><?php echo($date->format('D - j')); ?></div>
        <?php
    }
    ?>
</div>