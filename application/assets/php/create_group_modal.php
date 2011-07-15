<!-- CSS file is /assets/css/create_group_modal.css -->

<div class="create_group_content">
    <div class="create_group_top_bar">
        Create Group
        <input type="button" id="cancel_group_creation" style="float:right; position:relative; bottom:2px;" value="X"/>
    </div>
    <div class="create_group_middle">
        <div class="in-field_block input_style"><label for="group_name">Group name</label>
            <input type="text" id="group_name" name="group_name" class="textbox"/></div>

        <div class="in-field_block input_style"><label for="city">City</label>
            <input type="text" id="city" name="city" class="textbox"/></div>

        <div class="in-field_block input_style"><label for="school">College (optional)</label>
            <input type="text" id="school" name="school" class="textbox"/></div>

        <div class="in-field_block input_style"><label for="group_description">Group description</label>
            <textarea name="group_description" cols="40" rows="5">
            </textarea>
        </div><br/>
        
        <div class="divset" style="float:left;">
            Anyone can join
        </div>
        
        <div class="divset">
            Members can invite
        </div>
        
        <div class="divset">
            Certain members can invite
        </div>

        <div class="create_group_privacy">

        </div>

        <div class ="create_group_bottom">

        </div>
    </div>