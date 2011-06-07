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
        <div id="one_mult">
            <label for="sel_one">Select one</label>
            <input type="radio" id="sel_one" name="one_mult_group" onchange="reset_my_groups()"/>

            <label for="sel_mult">Select multiple</label>
            <input type="radio" id="sel_mult" name="one_mult_group" onchange="reset_my_groups()"/>
        </div>

        <div style="width:400px; height:600px">
            <ol id="my_groups">
                <?php
                for ($i = 1; $i < 10; ++$i)
                {
                    ?>
                    <li class="ui-widget-content">Item  <?php echo($i); ?></li>
                    <?php
                }
                ?>
                <?php
                for ($i = 1; $i < 10; ++$i)
                {
                    ?>
                    <li class="ui-widget-content">Item  <?php echo($i); ?></li>
                    <?php
                }
                ?>
            </ol>
        </div>
    </center>
</body>
</html>