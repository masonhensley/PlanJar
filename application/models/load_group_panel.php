
<!-- This file is included in home_view.php and not called from the controller -->

<div class="groups_wrapper">
    <font style="font-weight:bold;color:navy; font-size:20px">Group Panel</font><br/><hr/>
    <font style="font-weight:bold;color:#A7A7A7;">Networks</font><br/><hr/>
    <font style="font-weight:bold;">
    <div class="city_tab" group_id="friends">
        Friends
    </div>
    <div class="city_tab" group_id="school">
        <?php
        echo $school;
        ?>
    </div>
    <div class="city_tab" group_id="current_location">
        Current Location
    </div>
    </font>
    <hr/>
    <font style="font-weight:bold;color:#A7A7A7;">Groups following</font><br/><hr/>

    <?php
    foreach ($joined_groups as $group)
    {
        ?>
        <div class="selectable_group" group_id="<?php echo($group['id']); ?>">
            <font style="font-weight:bold;">
            <?php
            echo($group['name']);
            ?>
            </font></div>
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
        <div id="select_one_group" style="width:50px;">one</div>
        <div id="select_mult_groups" style="margin-left:5px;width:50px;">multiple</div>
    </div>
    <hr/>
</div>
<a href="/dashboard/groups/suggested" style="color:#57A8E2;font-weight:bold;" >Find groups</a><br/> 
<a href="/dashboard/following/suggested" style="color:#57A8E2;font-weight:bold;">Find people</a><br/>

