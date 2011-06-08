<html>
    <head>
        <title>PlanJar | Home</title>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.simplemodal.1.4.1.min.js"></script>


        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>

    </head>

    <body>
    <center>
        <div class="container" >

            <div class="left" >
                <div class="left_top">
                    logo goes here
                </div>
                <div class="left_bottom">
                    <!-- Group panel -->
                    <?php include(APPPATH . 'assets/php/group_panel.php'); ?>
                </div>
            </div>

            <div class="center" >
                <div class="center_top">

                    <div class ="center_top_left">

                    </div>

                    <div class="center_top_right">
                        <!-- New plan panel -->
                        <input type="button" id="make_a_plan" value="Make a plan."/>

                        <div id="plan_content">
                            <?php include(APPPATH . 'assets/php/plan_panel.php'); ?>
                        </div>

                        <!-- preload the images -->
                        <div style='display:none'>
                            <img src='/application/assets/images/x.png' alt='' />
                        </div>
                    </div>
                </div>
                <div class="center_day">

                    <!-- Add the markup for the tabs, starting with today. -->
                    <?php include(APPPATH . 'assets/php/weekday_panel.php'); ?>

                </div>
                <div class="center_graph">

                    <div id = "tabs" class="tabs-bottom">

                        <ul>
                            <li><a href="#tabs-1">Map</a></li>
                            <li><a href="#tabs-2">Data</a></li>
                        </ul>

                        <div id="tabs-1" style="width: 555px; height:250px;">
                            <div id="map" style="width:555px; height:250px;"></div>
                        </div>

                        <div id="tabs-2" style="background-color: purple;  width: 555px; height:250px; ">

                        </div>

                    </div>
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
