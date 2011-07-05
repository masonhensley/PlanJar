<input type="button" id="select_all_groups" value="Select all" style="position:relative; top: 5px;"/>
<input type="button" id="clear_all_groups" value="Clear all" style="position:relative; top: 5px;"/>
<div id="group_padding" style="width:100%; height:10px;"></div>
<div class="groups_wrapper">
    <div id="group_padding" style="width:100%; height:10px;"></div>
    <div class="group_label">Joined</div>
    <div id="group_padding" style="width:100%; height:10px;"></div>
    <?php
    foreach ($joined_groups as $group)
    {
        ?>
        <div class="selectable_group" group_id="<?php echo($group['id']); ?>">
            <?php echo($group['name']); ?>
        </div>
        <div id="group_padding" style="width:100%; height:10px;"></div>
        <?php
    }
    ?>
    <div class="group_label">Following</div>
    <div id="group_padding" style="width:100%; height:10px;"></div>
    <div class="selectable_group" group_id="friends">Friends</div>
    <div id="group_padding" style="width:100%; height:10px;"></div>
    <?php
    foreach ($followed_groups as $group)
    {
        ?>
        <div class="selectable_group" group_id="<?php echo($group['id']); ?>">
            <?php echo($group['name']); ?>
        </div>
        <div id="group_padding" style="width:100%; height:10px;"></div>
        <?php
    }
    ?>
</div>