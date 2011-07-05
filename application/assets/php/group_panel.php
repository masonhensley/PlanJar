<div id="group_padding" style="width:100%; height:10px;"></div>
<div class="radio">
    <label for="select_one_group">Select one</label>
    <input type="radio" id="select_one_group" name="select_one_mult_group" />
    <label for="select_mult_groups">Select multiple</label>
    <input type="radio" id="select_mult_groups" name="select_one_mult_group" />
</div>

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

<input type="button" id="select_all_groups" value="Select all" style="position:relative; top: 5px;"/>
<input type="button" id="clear_all_groups" value="Clear all" style="position:relative; top: 5px;"/>
