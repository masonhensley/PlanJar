<div id="plan_content">

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

            <tr>
                <td>
                    <div id="plan_time" class="radio" style="height:39px; width:200px">
                        <label for="plan_morning">Morning</label>
                        <input type="radio" id="plan_morning" name="plan_time_group" />

                        <label for="plan_afternoon">Afternoon</label>
                        <input type="radio" id="plan_afternoon" name="plan_time_group" />

                        <label for="plan_night">Night</label>
                        <input type="radio" id="plan_night" name="plan_time_group" />
                    </div>
                </td>
            </tr>
            
            <tr>
                <td>
                    Choose the time of day.
                </td>
            </tr>
        </table>

        <input id="plan_location_id" name="plan_location_id" type="hidden"/>
        <input id="plan_category_id" name="plan_category_id" type="hidden"/>
    </form>
</div>