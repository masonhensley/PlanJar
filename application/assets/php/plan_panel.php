<form id="make_plan">
    Make a plan:
    <br/>
    <div class="in-field_block">
        <label for="plan_location">Where are you going?</label>
        <input type="text" id="plan_location" name="plan_location" class="textbox"/>
        <br/>
        <p>Start typing, and we'll try to guess what you're looking for.</p>
        <p>Can't find it? Just type in the name and keep going.</p>
    </div>
    
    <div style="width:50px; height:10px; float:left"></div>

    <div class="in-field_block">
        <label for="plan_description">What are you doing?</label>
        <input type="text" id="plan_description" class="textbox"/>
        <br/>
        <p>Start typing what you plan to do.</p>
    </div>

    <select name="day" style="float:right">
        <option value="" selected="selected">What day?</option>
        <option value="0">Today - <?php echo(date('j')); ?></option>
        <option value="1">Tom - <?php echo(date('j') + 1); ?></option>
        <?php
        $days = array('Sun', 'Mon', 'Tues', 'Weds', 'Thurs', 'Fri', 'Sat');
        for ($i = 2; $i < 7; ++$i)
        {
            ?>
            <option value="<?php echo($i); ?>">
                <?php
                // Format the displayed day name (e.g. Tue - 9).
                $day_name = $days[(date('w') + $i) % 7];
                $day_name .= ' - ' . (date('j') + $i);
                echo($day_name);
                ?>
            </option>
            <?php
        }
        ?>
    </select>
    
    <input type="submit" value="Make a plan"/>
</form>