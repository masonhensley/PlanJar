<div id="create_plan_content">
    <div class="draggable_title_bar">
        <div style="float: left">Make a plan</div>
        <div style="float: right">
            <input type="button" id="cancel_plan" value="X"/>
        </div>
    </div>

    <center>
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

                <tr>
                    <td colspan="2">
                        <br/><br/>
                        When are you going?
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

                        <label for="plan_late_night">late night</label>
                        <input type="radio" id="plan_late_night" value="late_night" name="plan_time_group" />
                    </div>
                </center>
                </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div id="plan_day" class="radio">
                            <label for="plan_day_0">Today - <?php echo(date('j')); ?></label>
                            <input type="radio"  id="plan_day_0" value="0" name="plan_day_group" />

                            <?php
                            $date = new DateTime();
                            for ($i = 1; $i < 7; ++$i)
                            {
                                $date->add(new DateInterval('P1D'));
                                ?>
                                <label for="plan_day_<?php echo($i); ?>"><?php echo($date->format('D - j')); ?></label>
                                <input type="radio" id="plan_day_<?php echo($i); ?>"value="<?php echo($i); ?>" name="plan_day_group" />
                                <?php
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            </table>

            <input type="submit" value="Go"/>

            <input id="plan_location_id" name="plan_location_id" type="hidden"/>
            <input id="plan_location_name" type="hidden"/>
            <input id="plan_category_id" name="plan_category_id" type="hidden"/>
            <input id="plan_category_name" type="hidden"/>
            <input id="new_place_name" name="new_place_name" type="hidden"/>
            <input id="new_place_category" name="new_place_category" type="hidden"/>
            <input id="new_place_latitude" name="new_place_latitude" type="hidden"/>
            <input id="new_place_longitude" name="new_place_longitude" type="hidden"/>
        </form>
    </center>
</div>