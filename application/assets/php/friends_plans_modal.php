<div id="friends_plans_panel" class="modal">
    <div class="title_bar">
        Friend list
        <input  type="button" id="cancel_plan"  style="float:right;" value="X"/>
    </div>
    <br/>
    Select a friend to view their upcoming plans
    <br/>
    <hr/>
    <div class="friend_list">
        <?php
        foreach ($friend_names as $name)
        {
            ?>
            <div class="friend_tab">
                <?php
                echo $name;
                ?>
            </div>
            <?php
        }
        ?>
    </div>
</div> 