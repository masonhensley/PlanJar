<div id="friends_plans_panel" class="modal" style="left:20%; top:17%;">
    <div class="title_bar">
        Friends
        <input  type="button" id="cancel_friends_panel"  style="float:right;" value="&times;"/>
    </div>
    <div id="friend_modal_content">
        <br/>
        Select a friend to view their upcoming plans, or view all of your friends' upcoming plans
        <br/><hr/>
        <div class="friend_list" style="max-height:500px; overflow:auto;">
            <div class="friend_tab" user_id="all">All Upcoming Plans</div><hr/>
            <?php
            foreach ($friend_names as $id => $name)
            {
                ?>
                <div class="friend_tab" user_id="<?php echo $id; ?>">
                    <?php
                    echo $name;
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    
    <div id="friend_plan_list" style="display:none; position:relative;">
    </div>
</div> 