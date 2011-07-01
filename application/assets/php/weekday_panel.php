<!-- This populates the day of the week tabs -->

<div class="day" day_offset="0"><div class="day_text">Today</div></div>

<?php
$date = new DateTime();
for ($i = 1; $i < 7; ++$i)
{
    $date->add(new DateInterval('P1D'));
    ?>
    <div class="day" day_offset="<?php echo($i); ?>"><div class="day_text"><?php echo($date->format('D - j')); ?></div></div>
    <?php
}
?>