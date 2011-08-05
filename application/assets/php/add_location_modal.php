<div id="add_location_modal" class="modal">
    <form id="new_location_form">
        <div class="title_bar">
            Add a location
            <input type="button" id="close_add_location" value='X' style="float: right;"/>
        </div>

        <div class="left">
            <div class="in-field_block">
                <label for="new_location_name">Name</label>
                <input type="text" id="new_location_name" name="new_location_name"/>
            </div>
        </div>

        <div class="right">
            <div class="in-field_block">
                <label for="new_location_category">Category</label>
                <input type="text" id="new_location_category" name="new_location_category"/>
            </div>
        </div>

        <div id="new_location_map"></div>

        <div class="left">
            <input type="text" id="new_location_latitude" name="new_location_latitude"/>
            <input type="text" id="new_location_longitude" name="new_location_longitude"/>
        </div>

        <div class="right">

        </div>

        <input type="hidden" id="new_location_category_id" name="new_location_category_id"/>
    </form>
</div>