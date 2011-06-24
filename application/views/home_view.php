<?php
$user_place_info = (unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR'])));
$city = $user_place_info['geoplugin_city'];
$state = $user_place_info['geoplugin_regionCode'];
?>

<html>
    <head>
        <title>PlanJar | Home</title>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/groups_panel_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_modal_functions.js"></script>
        <script src="http://maps.google.com/maps/api/js?libraries=places&sensor=false" type="text/javascript"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/map_data_functions.js"></script>

        <!-- include plan tab code -->
        <script type="text/javascript" src="/application/assets/js/plan_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/day_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/validation-1.8.1/jquery.validate.min.js"></script>

        <!-- Load GeoPlugin api -->
        <script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>

        <!-- Load visible plans code -->
        <script type="text/javascript" src="/application/assets/js/visible_plans_functions.js"></script>
        
        <script type="text/javascript" src="/application/assets/js/change_location_functions.js"></script>


    </head>

    <body>
    <center>
        <div class="container">

            <div class="left" >
                <div class="left_top">
                    <img src="/Planjar logo.png" style="height:100px; width:80px;" />
                </div>
                <div class="left_bottom">
                    <!-- Group panel -->
                    <?php include(APPPATH . 'assets/php/group_panel.php'); ?>
                </div>
            </div>

            <div class="center" >

                <div class="center_top">

                    <div class ="center_top_left">
                        <font style="float:left;">
                        <?php
                        echo $city . ", " . $state . "<br/>";
                        ?><font style="font-size:10px;"><?php echo "And now your address is in our database.  WHAT THE FUCK YOU GONA DO ABOUT IT?"; ?></font>
                        </font>
                    </div>

                    <div class="center_top_right">
                        <!-- New plan panel -->
                        <?php include(APPPATH . 'assets/php/plan_panel.php'); ?>
                    </div>

                </div>

                <div class="center_day">
                    <!-- Add the markup for the tabs, starting with today. -->
                    <?php include(APPPATH . 'assets/php/weekday_panel.php'); ?>
                </div>

                <!--                <div class="center_graph">-->
                <div id = "map_data_tabs" class="tabs-bottom">
                    <ul>
                        <li><a href="#map_tab">Map</a></li>
                        <li><a href="#data_tab">Group Data</a></li>
                        <li><a href="#plan_data_tab">Plan Data</a></li>
                    </ul>

                    <!-- Google Map Div -->
                    <div id="map_tab" style="width:555px; height:250px;" ></div>

                    <div id="data_tab" style="background-color: white; color: black; width: 555px; height:250px;">
                        <p>Select at least one group on the left to see more detailed information.</p>
                    </div>

                    <div id="plan_data_tab" style="background-color: white; color:black; width: 555px; height:250px;">
                        <p>Select one of your plans on the right to see more detailed information.</p>
                    </div>

                </div>
                <!--                </div>-->

                <!--Account for the tabs overflowing below the div-->
                <div style="height:31px; width: 100%; background-color: red"></div>

                <!-- List of all plans visible given user parameters -->
                <div id="visible_plans_panel" class="center_board"></div>

            </div>

            <div class="right" >
                <div class="right_top">
                    <br/>Welcome, <?php echo $firstname . " " . $lastname ?><br/>
                    
                    <!-- Change location modal -->
                    <?php include(APPPATH . 'assets/php/change_location_panel.php'); ?>
                    
                    <a href="/home/logout">Log out.</a>
                </div>
                <div id="myplans" class="right_bottom">
                    <div style="width:auto; height:33px; text-align: center;">
                        <font>Plans</font>
                    </div>

                    <!-- Where the plans go -->
                    <div class="plans_wrapper"></div>

                </div>
            </div>

        </div>
    </center>
</body>
</html>
