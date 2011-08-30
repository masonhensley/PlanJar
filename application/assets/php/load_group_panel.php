<div style="position:relative; top:-20px;">
    <div class="groups_wrapper">
        <font style="font-weight:bold;color:gray;font-size:20px;">Networks</font><br/>
        <font style="color:gray;">(select one)</font>
        <br/><hr/>
        <div class="network_tab" group_id="current_location">
            Current Location
        </div>
        <div class="network_tab" group_id="friends">
            Friends
        </div>
        <div class="network_tab" group_id="school">
            <?php
            echo $school;
            ?>
        </div>
        <a href="/dashboard/groups"><font style="font-weight:bold;color:gray;font-size:20px;">Groups</font></a><br/>
        <font style="color:gray;">(select one or multiple)</font>
        <br/><hr/>
        <?php
        if (count($joined_groups) + count($followed_groups) > 1)
        {
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
        }else{
            ?>
        <br/>
        <i>Not following any groups yet</i><br/><br/>
        <a href="/dashboard/groups/suggested" style="color:navy;font-weight:bold;" >Search for groups, or make your own!</a>
        <br/><br/> 
        <?php
        }
            ?>
        <div id="group_select_type">
            <div id="select_one_group" style="width:50px;">one</div>
            <div id="select_mult_groups" style="margin-left:5px;width:50px;">multiple</div>
        </div>
        <hr/>
    </div>
    <br/>
    <a href="/dashboard/groups/suggested" style="color:#57A8E2;font-weight:bold;" >Find groups</a><br/> 
    <a href="/dashboard/following/suggested" style="color:#57A8E2;font-weight:bold;">Find people</a>
</div>