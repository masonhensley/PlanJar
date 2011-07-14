<div id="create_plan_content">
    <div class="draggable_title_bar">
        <div style="float: left">Make a plan</div>
        <div style="float: right">
            <input type="button" id="cancel_plan" value="X"/>
        </div>
    </div>

    <form id="make_plan">

        <table style="width: 100%">
            <tr>
                <td>
                    <div class="in-field_block">
                        <label for="plan_location">Where are you going?</label>
                        <input type="text" id="plan_location" name="plan_location" class="textbox"/>
                    </div>
                </td>
                <td>
                    <div class="in-field_block" style="float: right;">
                        <label for="plan_title">Title (optional)</label>
                        <input type="text" id="plan_title" name="plan_title" class="textbox"/>
                    </div>
                </td>
            </tr>
        </table>

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

        <input id="plan_location_id" name="plan_location_id" type="hidden"/>
        <input id="plan_location_name" type="hidden"/>
        <input id="new_place_name" name="new_place_name" type="hidden"/>
        <input id="new_place_category" name="new_place_category" type="hidden"/>
        <input id="new_place_latitude" name="new_place_latitude" type="hidden"/>
        <input id="new_place_longitude" name="new_place_longitude" type="hidden"/>
        <input id="new_place_factual_id" name="new_place_factual_id" type="hidden"/>

        <hr/>
        <input type="button" id="invite_to_plan" value="Invite people and groups"/>
        <input type="button" id="close_invite_plan_content" value="X"/>

        <div id="invite_plan_content">
            <h3>Invite people</h3>
            <input type="text" id="invite_plan_user"/>

            <h3>Invite groups</h3>
            <input type="text" id="invite_plan_group"/>
        </div>

        <hr/>
        <input type="button" id="show_plan_privacy" value="Add privacy settings"/>
        <input type="button" id="hide_plan_privacy" value="X"/>

        <div id="plan_privacy_content">
            <h3>Privacy options</h3>
            <div id="privacy_wrapper">
                <div priv_val="none">None</div>
                <div priv_val="strict">Fixed invitation list</div>
                <div priv_val="open">Invitees can invite others</div>
            </div>
        </div>

        <hr/>
        <input type="submit" value="Go"/>


    </form>
</div>