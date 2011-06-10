<div id="plan_content" title="Make a plan.">

    <form id="make_plan">

        <table>
            <tr>
                <td>
                    Start typing the name of where you want to go.
                    If we don't find your location, don't worry about it.
                </td>
                <td>
                    Start typing what you plan on doing.
                    Choose an option from the menu.
                </td>
            </tr>

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
                    Choose the time of day.
                </td>
            </tr>

            <tr>
                <td colspan="2">
            <center>
                <div id="plan_time" class="radio">
                    <label for="plan_morning">-</label>
                    <input type="radio" id="plan_morning" name="plan_time_group" />

                    <label for="plan_afternoon">morning</label>
                    <input type="radio" id="plan_afternoon" name="plan_time_group" />

                    <label for="plan_night">-</label>
                    <input type="radio" id="plan_night" name="plan_time_group" />

                    <label for="plan_night1">afternoon</label>
                    <input type="radio" id="plan_night1" name="plan_time_group" />

                    <label for="plan_night2">-</label>
                    <input type="radio" id="plan_night2" name="plan_time_group" />

                    <label for="plan_night3">night</label>
                    <input type="radio" id="plan_night3" name="plan_time_group" />

                    <label for="plan_night4">-</label>
                    <input type="radio" id="plan_night4" name="plan_time_group" />
                </div>
            </center>
            </td>
            </tr>

            <tr>
                <td colspan="2">
                    <div id="plan_day" class="radio">
                        <label for="0">Today - <?php echo(date('j')); ?></label>
                        <input type="radio" id="0" name="plan_day_group" />

                        <label for="1">Tom - <?php echo(date('j') + 1); ?></label>
                        <input type="radio" id="0" name="plan_day_group" />

                        <?php
                        $days = array('Sun', 'Mon', 'Tues', 'Weds', 'Thurs', 'Fri', 'Sat');
                        for ($i = 2; $i < 7; ++$i)
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
                            <input type="radio" id="<?php echo($i); ?>" name="plan_day_group" />
                            <?php
                        }
                        ?>
                    </div>
                </td>
            </tr>
        </table>

        <input id="plan_location_id" name="plan_location_id" type="hidden"/>
        <input id="plan_category_id" name="plan_category_id" type="hidden"/>
    </form>
</div>