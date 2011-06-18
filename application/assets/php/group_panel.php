Filter by group
<hr/>

Select
<div id="one_mult" class="radio">
    <label for="sel_one">Standard</label>
    <input type="radio" id="sel_one" name="one_mult" checked="checked" onchange="toggle_group_select()"/>

    <label for="sel_mult">Toggle</label>
    <input type="radio" id="sel_mult" name="one_mult" onchange="toggle_group_select()"/>
</div>
<hr/>

<div class="group_selectable_wrapper">
    <ul id="friends_group">
        <li class="ui-widget-content">Friends</li>
    </ul>

    <ul id="joined_groups">
        <div class="group_label">Joined</div>

        <?php
        foreach ($joined_groups as $group)
        {
            echo('wufgiufubewiufgbueb');
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