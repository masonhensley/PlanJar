<!-- CSS file is /assets/css/create_group_modal.css -->

<div class="create_group_content">
    <div class="create_group_top_bar">
        Create Group
        <input type="button" id="cancel_group_creation" style="float:right; position:relative; bottom:2px;" value="X"/>
    </div>
    <form id="create_group">
        <div class="create_group_middle">
            <div class="in-field_block input_style"><label for="group_name">Group name</label>
                <input type="text" id="group_name" name="group_name" class="textbox"/></div>

            <div class="in-field_block input_style"><label for="group_description">Group description</label>
                <textarea name="group_description" id="group_description" cols="40" rows="5"></textarea>
            </div>
        </div>


        <div class ="create_group_bottom">
            <div class="divset" id="select_me" style="float:left;">
                Anyone can join
            </div>
            <div class="divset">
                Invitees must invite new members
            </div>

            <div class="invite_wrapper">
                <div class="left">
                    Invite people
                    <input type="text" id="group_invite_user" name="group_invite_user"/>
                </div>

                <div class="right">
                    Invite groups
                    <input type="text" id="group_invite_group" name="group_invite_group"/>
                </div>
            </div>

            <label><input type="radio" name="location_source" value="school"/>Associate this group with the <?php echo($user_school); ?> network.</label>
            <br/>
            <label><input type="radio" name="location_source" value="current"/>Associate this group with your general location</label>

            <input type="submit" class="submit_create_group" value="Go" style="float:right;" />
        </div>
    </form>
</div>