<div id="friends_plans_panel" class="modal" style="left:43%; top:19%;">
    <div class="title_bar">
        Friends
        <input  type="button" id="cancel_friends_panel"  style="float:right;" value="X"/>
    </div>
    <div id="friend_modal_content">
        <br/>
        Select a friend to view their upcoming plans
        <br/><hr/>
        <div class="friend_list">
            <div class="friend_tab" user_id="all">All Upcoming Friends' Plans</div><hr/>
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