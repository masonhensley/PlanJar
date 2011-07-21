<div id="create_plan_content">
    <div class="draggable_title_bar" style="width: 90%; float: left;">
        Start a plan
    </div>
    <input  type="button" id="cancel_plan"  style="float:right; position:relative; z-index: 1000;" value="X"/>

    <form id="plan_form">
        <div id="plan_page_wrapper">
            <div class="plan_page_content">
                <div class="in-field_block">
                    <label for="plan_location">Where are you going?</label>
                    <input type="text" id="plan_location" name="plan_location" class="textbox"/>
                </div>
            </div>

            <div class="plan_page_content" style="display: none;">
                When are you going?

                <div id="plan_day">
                    <div plan_day="0">Today</div>

                    <?php
                    $date = new DateTime();
                    for ($i = 1; $i < 7; ++$i)
                    {
                        $date->add(new DateInterval('P1D'));
                        ?>
                        <div plan_day="<?php echo($i); ?>"><?php echo($date->format('D - j')); ?></div>
                        <?php
                    }
                    ?>
                </div>

                <div id="plan_time">
                    <div plan_time="morning">Morning</div>
                    <div plan_time="afternoon">Afternoon</div>
                    <div plan_time="night">Night</div>
                    <div plan_time="late_night">Late night</div>
                </div>
            </div>

            <div class="plan_page_content" style="display: none;">
                Here's what's happening.

                <div id="privacy_wrapper">
                    Privacy options
                    <br/>
                    <div priv_val="none">None</div>
                    <div priv_val="strict">Fixed invitation list</div>
                    <div priv_val="loose">Invitees can invite others</div>
                </div>
            </div>

            <div class="plan_page_content" style="display: none;">
                <div id="plan_invite_wrapper">
                    <div id="invite_plan_left">
                        Invite people
                        <input type="text" id="invite_plan_user" name="invite_plan_user"/>
                    </div>

                    <div id="invite_plan_right">
                        Invite groups
                        <input type="text" id="invite_plan_group" name="invite_plan_group"/>
                    </div>
                </div>

                <input type="button" id="submit_plan" value="Start a plan"/>
            </div>

            <input id="plan_location_id" name="plan_location_id" type="hidden"/>
            <input id="plan_location_name" type="hidden"/>
            <input id="new_place_name" name="new_place_name" type="hidden"/>
            <input id="new_place_category" name="new_place_category" type="hidden"/>
            <input id="new_place_latitude" name="new_place_latitude" type="hidden"/>
            <input id="new_place_longitude" name="new_place_longitude" type="hidden"/>
            <input id="new_place_factual_id" name="new_place_factual_id" type="hidden"/>
        </div>
    </form>
</div>
