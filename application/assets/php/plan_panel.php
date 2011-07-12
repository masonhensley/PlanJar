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
                    <div id="plan_time">
                        <div value="morning">Morning</div>
                        <div value="afternoon">Afternoon</div>
                        <div value="night">Night</div>
                        <div value="late_night">Late night</div>
                    </div>
                </center>
                </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div id="plan_day">
                            <div value="0">Today</div>

                            <?php
                            $date = new DateTime();
                            for ($i = 1; $i < 7; ++$i)
                            {
                                $date->add(new DateInterval('P1D'));
                                ?>
                                <div value="<?php echo($i); ?>"><?php echo($date->format('D - j')); ?></div>
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
            <input id="new_place_factual_id" name="new_place_factual_id" type="hidden"/>
        </form>
    </center>
</div>