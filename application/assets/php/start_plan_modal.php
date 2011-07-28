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
                <h3>Where are you going?</h3>
                <input type="text" id="plan_location" style="width: 200px; height: 30px;"/>

                <h3 style="margin-top: 5px;">When are you going?</h3>
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
                <div id="plan_events_title"></div>

                <div style="width: 100%; height: auto;">
                    <div id="plan_event_select_wrapper"></div>

                    <div id="new_event_side">
                        <input type="button" id="create_event" value="Start an event"/>

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
                </div>

                <div id="plan_submit_wrapper">
                    <input type="button" id="submit_plan" value="Go"/>
                </div>
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