
<!-- This file is included in home_view.php and not called from the controller -->

<div class="groups_wrapper">
    <div class="city_tab" group_id="friends">
        Friends
    </div>
    <div class="city_tab" group_id="school">
        <?php
        echo $school;
        ?>
    </div>

    <hr/>

    <?php
    foreach ($joined_groups as $group)
    {
        ?>
        <div class="selectable_group" group_id="<?php echo($group['id']); ?>">
            <?php
            echo($group['name']);
            ?>
        </div>
        <?php
    }
    ?>

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
    <div id="group_select_type">
        <div id="select_one_group">one</div>
        <div id="select_mult_groups" style="margin-left:5px;">multiple</div>
    </div>
    <hr/>
    <div class="city_tab" group_id="current_location">
        Current Location
    </div>
    <hr/>
</div>
<a href="/dashboard/groups/suggested" style="color:#110055;font-weight:bold;" >Find groups</a><br/> 
<a href="/dashboard/following/suggested" style="color:#110055;font-weight:bold;">Find friends</a><br/>

