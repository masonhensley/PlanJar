<!-- This populates the day of the week tabs -->

<div class="day" day_offset="0">Today</div>

<?php
$date = new DateTime();
for ($i = 1; $i < 7; ++$i)
{
    $date->add(new DateInterval('P1D'));
    ?>
    <div class="day" day_offset="<?php echo($i); ?>"><?php echo($date->format('D-j')); ?></div>
    <?php
}
?>
</div>