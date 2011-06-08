<html>
    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

        <link type="text/css" rel=stylesheet href="/application/assets/css/home.css"/>
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>
    </head>

    <body>
    <center>
        Select
        <div id="one_mult">
            <label for="sel_one">one</label>
            <input type="radio" id="sel_one" name="one_mult" checked="checked" onchange="toggle_group_select()"/>

            <label for="sel_mult">multiple</label>
            <input type="radio" id="sel_mult" name="one_mult" onchange="toggle_group_select()"/>
        </div>
        groups

        <div style="width:200px; height:600px">
            <br/>
            <ol id="my_groups">
                <li class="ui-widget-content">Friends</li>

                <div class="group_label">Joined</div>
                <?php
                for ($i = 1; $i < 5; ++$i)
                {
                    ?>
                    <li class="ui-widget-content">Group <?php echo($i); ?></li>
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
            </ol>
        </div>
    </center>
</body>
</html>