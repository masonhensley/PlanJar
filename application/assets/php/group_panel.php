<div id="group_select_type">
    <div id="select_one_group">Select one</div>
    <div id="select_mult_groups">Select multiple</div>
</div>

<div class="groups_wrapper">

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
    <div class="selectable_group" group_id="friends">Following</div>
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

<input type="button" id="select_all_groups" value="Select all" style="position:relative; top: 5px;"/>
<input type="button" id="clear_all_groups" value="Clear all" style="position:relative; top: 5px;"/><br/><br/>
<a href="/dashboard/groups/suggested" style="color:#110055;font-weight:bold;" >Find groups</a><br/>
<a href="/dashboard/following/suggested" style="color:#110055;font-weight:bold;">Find friends</a><br/>
