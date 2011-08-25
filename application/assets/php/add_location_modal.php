<div id="add_location_modal" class="modal">
    <form id="new_location_form">
        <div class="title_bar" style="margin-bottom: 5px;">
            Add a location
            <input type="button" id="close_add_location" value='X' style="float: right;"/>
        </div>

        <div class="in-field_block" style="margin-left: auto; margin-right: auto;">
            <label for="new_location_name">Name</label>
            <input type="text" id="new_location_name" name="new_location_name"/>
        </div>

        <div style="text-align: center; width: 100%; margin-top: 15px;">Drag the marker to the correct location.</div>

        <div id="new_location_map"></div>

        <div class="left" style="text-align: center;">
            Selected coordinates.<br/>Feel free to edit them.<br/>
            <input type="text" id="new_location_latitude" name="new_location_latitude"/>
            <input type="text" id="new_location_longitude" name="new_location_longitude"/>
        </div>

        <div class="right" style="text-align: center;">
            <input type="button" id="submit_location" value="Make a place"/>
        </div>

        <input type="hidden" id="new_location_category_id" name="new_location_category_id"/>
    </form>
</div>