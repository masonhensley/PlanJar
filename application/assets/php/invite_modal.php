<div id="invite_modal" class="modal">
    <div class="title_bar">
        Invite people
        <div style="float: right;">
            <input type="button" id="close_invite_modal" value="X"/>
        </div>
    </div>

    <div id="invite_followers_list_wrapper">
        <div class="header">Your followers</div>

        <div id="invite_followers_list"></div>
    </div>

    <div id="invite_groups_list_wrapper">
        <div class="header">Your joined groups</div>

        <div id="invite_groups_list"></div>
    </div>

    Search for people in <?php echo($school); ?>
    <input type="text" id="search_in_school"/>

    <input type="button" id="send_invites" value="Invite"/>
</div>