<!-- This content is loaded when the page loads. $joined_groups variable comes from the home.php controller -->

<div class="groups_wrapper">

    <div class="city_tab">
        Use Current Location
    </div>
    
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
<div id="group_select_type">
    <div id="select_one_group">Select one</div>
    <div id="select_mult_groups">Select multiple</div>
</div>
<br/>
<a href="/dashboard/groups/suggested" style="color:#110055;font-weight:bold;" >Find groups</a><br/>
<a href="/dashboard/following/suggested" style="color:#110055;font-weight:bold;">Find friends</a><br/>
