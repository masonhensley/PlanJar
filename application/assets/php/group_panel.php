Filter by group
<hr/>

Select
<div id="one_mult">
    <label for="sel_one">Standard</label>
    <input type="radio" id="sel_one" name="one_mult" checked="checked" onchange="toggle_group_select()"/>

    <label for="sel_mult">Toggle</label>
    <input type="radio" id="sel_mult" name="one_mult" onchange="toggle_group_select()"/>
</div>
<hr/>

<ul id="my_groups">
    <li class="ui-widget-content">Friends</li>

    <div class="group_label">Joined</div>
    <?php
    for ($i = 1; $i < 5; ++$i)
    {
        ?>
        <li class="ui-widget-content">
            <input type="button">Group <?php echo($i); ?></input>
        </li>
        <?php
    }
    ?>

    <div class="group_label">Following</div>
    <?php
    for ($i = 1; $i < 5; ++$i)
    {
        ?>
        <li class="ui-widget-content">Group <?php echo($i); ?></li>
        <?php
    }
    ?>
</ul>