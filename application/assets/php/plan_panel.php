<form id="make_plan">
    Make a plan:
    <br/>
    <div class="in-field_block">
        <label for="plan_location">Where are you going?</label>
        <input type="text" id="plan_location" name="plan_location" class="textbox"/>
    </div>

    <div class="in-field_block">
        <label for="plan_description">What are you doing?</label>
        <input type="text" id="plan_description" class="textbox"/>
    </div>

    <select name="day" style="float:left">
        <option value="" selected="selected">What day?</option>
        <option value="0">Today</option>
        <option value="1">Tom</option>
        <?php
        $days = array('Sun', 'Mon', 'Tues', 'Weds', 'Thurs', 'Fri', 'Sat');
        for ($i = 2; $i < 7; ++$i)
        {
            ?>
            <option value="<?php echo($i); ?>">
                <?php
                // Format the displayed day name (e.g. Tue - 9).
                $day_name = $days[(date('w') + $i) % 7];
                $day_name .= ' - ' . date('j') + $i;
                echo($day_name);
                ?>
            </option>
            <?php
        }
        ?>
    </select>
</form>