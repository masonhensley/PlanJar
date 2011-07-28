<!-- CSS file is /assets/css/create_group_modal.css -->

<div id="create_group_content" class="modal">
    <div class="title_bar">
        Create Group
        <input type="button" id="cancel_group_creation" style="float:right; position:relative; bottom:2px;" value="X"/>
    </div>
    <form id="create_group">
        <div class="in-field_block input_style">
            <label for="group_name">Group name</label>
            <input type="text" id="group_name" name="group_name" class="textbox"/>
        </div>

        <div class="in-field_block input_style" style="height: auto;">
            <label for="group_description">Group description</label>
            <textarea name="group_description" id="group_description" cols="40" rows="5"></textarea>
        </div>

        <div id="group_privacy_wrapper">
            <div priv_type="open">
                Anyone can join
            </div>
            <div priv_type="loose">
                Invitees must invite new members
            </div>
        </div>

        <label><input type="radio" name="location_source" value="school"/>Associate this group with the <?php echo($user_school); ?> network.</label>
        <br/>
        <label><input type="radio" name="location_source" value="current"/>Associate this group with your general location</label>

        <input type="button" id="submit_create_group" value="Go" style="float:right;" />
    </form>
</div>