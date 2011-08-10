<div id="create_plan_content" class="modal">
    <div class="title_bar">
        <!-- Dynamically loaded in js to display "Start a plan in <city>" -->
        <div class="text"></div>

        <input  type="button" id="cancel_plan"  style="float:right;" value="X"/>
    </div>

    <form id="plan_form" style="height: 100%;">
        <div id="plan_place_time_wrapper">
            <h3>Where do you want to go?</h3>
            <div>
                <input type="text" id="plan_location" style="width: 200px; height: 30px;"/>
            </div>

            <h3 style="margin-top: 5px;">When do you want to go?</h3>
            <div id="plan_day">
                <?php echo($plan_day_html); ?>
            </div>

            <div class="right_day_arrow divset">></div>
            <div class="left_day_arrow divset"><</div>

            <div style="margin-left: auto; margin-right: auto;">
                <div id="plan_time">
                    <div plan_time="morning">Morning</div>
                    <div plan_time="afternoon">Afternoon</div>
                    <div plan_time="night">Night</div>
                    <div plan_time="late_night">Late night</div>
                </div>

                <b style="float: left; line-height: 30px;">or</b>

                <div class="in-field_block" style="float: left; margin-left: 10px;">
                    <label for="plan_clock_time">Type a time (e.g. 9:30 pm)</label>
                    <input type="text" id="plan_clock_time" name="plan_clock_time"/>
                </div>
            </div>
        </div>

        <hr/>
        <div id="plan_place_location_buttons" style="width: 100%; text-align: right;">
            <input type="button" id="create_event" value="Create an event"/>
            <input type="button" id="just_go" value="Just go"/>
        </div>

        <div id="plan_events_wrapper">
            <div id="plan_event_select_wrapper"></div>

            <div class="in-field_block" style="margin-left: auto; margin-right: auto;">
                <label for="event_title">Title</label>
                <input type="text" id="event_title" name="event_title"/>
            </div>

            <div id="plan_privacy_wrapper">
                Privacy options<br/>

                <div priv_val="open">None</div>
                <div priv_val="strict">Fixed invitation list</div>
                <div priv_val="loose">Invitees can invite others</div>
            </div>
        </div>

<!--        <input type="button" id="submit_plan" value="Go" style="position: absolute; right: 0px; bottom: 0px;"/>-->

        <input id="plan_location_id" name="plan_location_id" type="hidden"/>
        <input id="plan_location_name" type="hidden"/>
        <input id="new_place_name" name="new_place_name" type="hidden"/>
        <input id="new_place_category" name="new_place_category" type="hidden"/>
        <input id="new_place_latitude" name="new_place_latitude" type="hidden"/>
        <input id="new_place_longitude" name="new_place_longitude" type="hidden"/>
        <input id="new_place_factual_id" name="new_place_factual_id" type="hidden"/>
        <input id="plan_event_id" name="plan_event_id" type="hidden" />
    </form>
</div>
</div>