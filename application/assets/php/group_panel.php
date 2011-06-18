Filter by group
<hr/>

<input type="button" id="clear_all_groups" value="Clear all"/>
<input type="button" id="select_all_groups" value="Select all"/>

<div class="group_selectable_wrapper">
    <ul id="friends_group">
        <li class="ui-widget-content" group_id="friends">Friends</li>
    </ul>

    <ul id="joined_groups">
        <div class="group_label">Joined</div>

        <?php
        foreach ($joined_groups as $group)
        {
            ?>
            <li class="ui-widget-content" group_id="<?php echo($group['id']); ?>"><?php echo($group['name']); ?></li>
            <?php
        }
        ?>
    </ul>

    <ul id="followed_groups">
        <div class="group_label">Following</div>

        <?php
        foreach ($followed_groups as $group)
        {
            ?>
            <li class="ui-widget-content" group_id="<?php echo($group['id']); ?>"><?php echo($group['name']); ?></li>
            <?php
        }
        ?>
    </ul>
</div>