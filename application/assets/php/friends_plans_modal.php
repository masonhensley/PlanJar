<div id="friends_plans_panel" class="modal">
    <div class="title_bar">
        <div id="title_text">Friend list</div>
        <input  type="button" id="cancel_friends_panel"  style="float:right;" value="X"/>
    </div>
    <div class="friend_modal_content">
        <br/>
        Select a friend to view their upcoming plans
        <br/><hr/>
        <div class="friend_list">
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
    
    <div class="friend_plan_content" style="display:none;">
        
    </div>
</div> 