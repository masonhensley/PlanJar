<html>
    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>

        <script type="text/javascript">
            $(function() {
                $( "#tabs" ).tabs({
                    collapsible: true
                });
            });
        </script>
    </head>

    <body >
    <center>
        <div class="container" >

            <div class="left" >
                <div class="left_top">

                </div>
                <div class="left_bottom">
                    <h2>Filter by group</h3>
                        <hr/>

                        <h3>Select</h3>
                        <div id="one_mult">
                            <label for="sel_one">one</label>
                            <input type="radio" id="sel_one" name="one_mult" checked="checked" onchange="toggle_group_select()"/>

                            <label for="sel_mult">multiple</label>
                            <input type="radio" id="sel_mult" name="one_mult" onchange="toggle_group_select()"/>
                        </div>
                        <hr/>
                        <br/>

                        <ul id="my_groups">
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
                        </ul>
                </div>
            </div>

            <div class="center" >
                <div class="center_top">

                </div>
                <div class="center_day">

                </div>
                <div class="center_graph">

                </div>
                <div class="center_board">

                </div>
            </div>

            <div class="right" >
                <div class="right_top">
                    <a href="/home/logout">Log out.</a>
                </div>

                <div class="right_bottom">

                </div>

            </div>

        </div>
    </center>
</body>
</html>
