<div id="invite_modal" class="modal">
    <div class="title_bar">
        Invite people
        <div style="float: right;">
            <input type="button" id="close_invite_modal" value="X"/>
        </div>
    </div>

    <div class="title"></div>

    <div id="invite_followers_list_wrapper">
        <div class="header">Your followers</div>

        <div id="invite_followers_list"></div>
    </div>

    <div id="invite_groups_list_wrapper">
        <div class="header">Your joined groups</div>

        <div id="invite_groups_list"></div>
    </div>

    <div id="search_in_school_wrapper">
        <div class="header">
            Search for people in <?php echo($school); ?>
        </div>
        <input type="text" id="search_in_school"/>
    </div>

    <input type="button" id="send_invites" value="Invite"/>
    <input type="button" id="close_invite_modal_2" value="Close"/>

    <input type="hidden" id="invite_subject_type"/>
    <input type="hidden" id="invite_subject_id"/>
    <input type="hidden" id="invite_priv_type"/>
</div>