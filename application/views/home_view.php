<html>
    <head>
        <title>PlanJar | Home</title>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script src="http://maps.google.com/maps/api/js?libraries=places&sensor=false" type="text/javascript"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>

        <!-- Load Yahoo api -->
        <script type="text/javascript" src="http://api.maps.yahoo.com/ajaxymap?v=3.8&appid=5CXRiH44"></script>

        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>

    </head>

    <body>
    <center>
        <div class="container">

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
                        <font style="float:left;"><p>your location data:</p></font>
                    </div>

                    <div class="center_top_right">
                        <!-- New plan panel -->
                        <input type="button" id="make_a_plan" style="top:50%;" value="Make a plan"/>
                        <?php include(APPPATH . 'assets/php/plan_panel.php'); ?>
                    </div>

                </div>

                <div class="center_day">
                    <!-- Add the markup for the tabs, starting with today. -->
                    <?php include(APPPATH . 'assets/php/weekday_panel.php'); ?>
                </div>

                <div class="center_graph">
                    <div id = "tabs" class="tabs-bottom">
                        <ul>
                            <li><a href="#tabs-2">Data</a></li>
                            <li><a href="#tabs-1">Map</a></li> 
                        </ul>
                        <div id="tabs-1" style="width:555px; height:250px;" >
                            <div id="map" style="width:555px; height:250px;">
                            </div>
                        </div>
                        <div id="tabs-2" style="background-color: white;  width: 555px; height:250px; ">

                        </div>
                    </div>
                </div>
                <div class="center_board">
                    <!-- Event list -->
                    <?php include(APPPATH . 'assets/php/events_panel.php'); ?>
                </div>
            </div>

            <div class="right" >
                <div class="right_top">
                    <a href="/home/logout">Log out.</a>
                </div>
                <div class="right_bottom">
                    <div style="width:auto; height:auto; text-align: center;">
                        Plans
                    </div>
                    <!-- this function loads the user events into the right panel -->
                    <?php
                    foreach ($result as $plan)
                    {
                        ?> 
                        <div style="border: 2px solid #000; font-size: 12px; text-align: left; width:auto; height: auto; ">
                            <div id="day_display" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000; width:100%; height: auto; text-align: center;">
                                <?php
                                echo $plan->name . "  |  ";
                                $date_string = date('D', strtotime($plan->date));
                                echo $date_string;
                                ?>
                            </div>
                                <?php
                                echo "<p>";
                                $date_string = date('l', strtotime($plan->date));
                                echo $date_string . " " . $plan->time_of_day;
                                echo "</p>";
                                ?>
                        </div>
                            <?php
                        }
                        ?>

                </div>
            </div>

        </div>
    </center>
</body>
</html>
