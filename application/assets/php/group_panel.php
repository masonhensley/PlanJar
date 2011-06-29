<input type="button" id="select_all_groups" value="Select all"/>
<input type="button" id="clear_all_groups" value="Clear all"/>

<div class="groups_wrapper">
    <div class="selectable_group" group_id="friends">Friends</div>

    <div class="group_label">Joined</div>

    <?php
    foreach ($joined_groups as $group)
    {
        ?>
        <div class="selectable_group" group_id="<?php echo($group['id']); ?>">
            <?php echo($group['name']); ?>
        </div>
        <?php
    }
    ?>

    <div class="group_label">Following</div>

    <?php
    foreach ($followed_groups as $group)
    {
        ?>
        <div class="selectable_group" group_id="<?php echo($group['id']); ?>">
            <?php echo($group['name']); ?>
        </div>
        <?php
    }
    ?>
</div>