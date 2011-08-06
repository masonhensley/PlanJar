<div id="create_plan_content" class="modal">
    <div class="title_bar">
        <!-- Dynamically loaded in js to display "Start a plan in <city>" -->
        <div class="text"></div>

        <input  type="button" id="cancel_plan"  style="float:right;" value="X"/>
    </div>

    <div id="plan_left"></div>

    <div id="plan_page_wrapper">
        <form id="plan_form" style="height: 100%;">
            <div class="plan_page_content" style="text-align: center;">
                <h3>Where do you want to go?</h3>
                <input type="text" id="plan_location" style="width: 200px; height: 30px;"/>

                <h3 style="margin-top: 5px;">When do you want to go?</h3>
                <div id="plan_day">
                    <div class="seven_days"><?php echo($plan_day_html); ?></div>
                    <div class="left_day_arrow"><</div>
                    <div class="right_day_arrow">></div>
                </div>

                <div id="plan_time">
                    <div plan_time="morning">Morning</div>
                    <div plan_time="afternoon">Afternoon</div>
                    <div plan_time="night">Night</div>
                    <div plan_time="late_night">Late night</div>
                </div>
            </div>

            <div class="plan_page_content" style="display: none;">
                <div class="left">
                    <div id="plan_events_title"></div>

                    <div id="plan_event_select_wrapper"></div>
                </div>

                <div class="right">
                    <div id="create_event_title">
                        Don't find anything you want to go to?<br/><br/>
                        <div id="just_going">just going</div>
                        <input type="button" id="close_new_event" value="Cancel" style="display: none"/>
                        <br/>

                        <input type="button" id="create_event" value="Start an event"/>
                    </div>

                    <div id="start_event_content" style="display: none;">
                        <div class="in-field_block" style="margin-left: auto; margin-right: auto; margin-bottom: 15px;">
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
                </div>

                <input type="button" id="submit_plan" value="Go" style="position: absolute; right: 0px; bottom: 0px;"/>
            </div>

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

    <div id="plan_right"></div>
</div>