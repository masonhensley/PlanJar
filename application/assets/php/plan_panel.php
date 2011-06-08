<form id="make_plan">
    Make a plan:
    <br/>
    <div class="in-field_block">
        <label for="plan_location">Where are you going?</label>
        <input type="text" id="plan_location" name="plan_location" class="textbox"/>
    </div>

    <div class="in-field_block">
        <label for="plan_description">What are you doing?</label>
        <input type="text" name="plan_description" class="textbox"/>
    </div>

    <select name="day">
        <option value="" selected="selected">What day?</option>
        <option value="0" selected="selected">Today</option>
        <option value="1" selected="selected">Tomorrow</option>
        <?php
        $days = array('Sun', 'Mon', 'Tues', 'Weds', 'Thurs', 'Fri', 'Sat');
        for ($i = 2; $i < 7; ++$i)
        {
            ?>
            <option value="<?php echo($i); ?>">
                <?php echo($days[(date('w') + $i) % 7]); ?>
            </option>
            <?php
        }
        ?>
    </select>
</form>