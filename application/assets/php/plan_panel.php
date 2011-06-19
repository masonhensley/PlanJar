<div id="plan_content" title="Make a plan.">

    <form id="make_plan">

        <table>
            <tr>
                <td>
                <div class="in-field_block">
                    <label for="plan_location">Where are you going?</label>
                    <input type="text" id="plan_location" name="plan_location" class="textbox"/>
                </div>
            </td>
            <td>
                <div class="in-field_block">
                    <label for="plan_category">What are you doing?</label>
                    <input type="text" id="plan_category" name="plan_category" class="textbox"/>
                </div>
            </td>
            </tr>

            <tr><td><div style="height:20px; width:10px;"></div></td></tr>

            <tr>
                <td colspan="2">
            <center>
                When are you going?
            </center>
            </td>
            </tr>

            <tr>
                <td colspan="2">
            <center>
                <div id="plan_time" class="radio">
                    <label for="plan_morning">morning</label>
                    <input type="radio" id="plan_morning" value="morning" name="plan_time_group" />

                    <label for="plan_afternoon">afternoon</label>
                    <input type="radio" id="plan_afternoon" value="afternoon" name="plan_time_group" />

                    <label for="plan_night">night</label>
                    <input type="radio" id="plan_night" value="night" name="plan_time_group" />

                    <label for="plan_late_night">late night</lab
                    
                    
                    
                    
                    
                    el>
                    <input type="radio" id="plan_late_night" value="late_night" name="plan_time_group" />
                </div>
            </center>
            </td>
            </tr>

            <tr>
                <td colspan="2">
                    <div id="plan_day" class="radio">
                        <center>
                            <label for="0">Today - <?php echo(date('j')); ?></label>
                            <input type="radio" id="0" value="0" name="plan_day_group" />

                            <?php
                            $days = array('Sun', 'Mon', 'Tues', 'Weds', 'Thurs', 'Fri', 'Sat');
                            for ($i = 1; $i < 7; ++$i)
                            {
                                ?>

                                <label for="<?php echo($i); ?>">
                                    <?php
                                    // Format the displayed day name (e.g. Tue - 9).
                                    $day_name = $days[(date('w') + $i) % 7];
                                    $day_name .= ' - ' . (date('j') + $i);
                                    echo($day_name);
                                    ?>
                                </label>
                                <input type="radio" id="<?php echo($i); ?>" value="<?php echo($i); ?>" name="plan_day_group" />
                                <?php
                            }
                            ?>
                        </center>
                    </div>
                </td>
            </tr>
        </table>
        
        <input type="submit" value="Go"/>

        <input id="plan_location_id" name="plan_location_id" type="hidden"/>
        <input id="plan_location_name" name="plan_location_name" type="hidden"/>
        <input id="plan_category_id" name="plan_category_id" type="hidden"/>
        <input id="plan_category_name" name="plan_category_name" type="hidden"/>
    </form>
</div>