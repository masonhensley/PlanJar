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
                        <font style="float:left;">
                        <?php
                        echo "Our advanced reverse-geocoding algorithm has determined your location to be: ";
                        echo $city . ", " . $state . "<br/>";
                        ?><font style="font-size:10px;"><?php echo "And now your address is in our database.  WHAT THE FUCK YOU GONA DO ABOUT IT?"; ?></font>
                        </font>
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
                    <div id = "map_data_tabs" class="tabs-bottom">
                        <ul>
                            <li><a href="#map_tab">Map</a></li>
                            <li><a href="#data_tab">Data</a></li>
                        </ul>

                        <!-- Google Map Div -->
                        <div id="map_tab" style="width:555px; height:250px;" >
                            <div id="map" style="width:555px; height:250px;"></div>
                        </div>

                        <div id="data_tab" style="background-color: white;  width: 555px; height:250px;">
                            <img style="width:555px; height:250px;" src="http://farm1.static.flickr.com/172/412815146_eaa71e212f.jpg" />
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
                <div id="myplans" class="right_bottom">
                    <div style="width:auto; height:33px; text-align: center;">
                        <font>Plans</font>
                    </div>

                    <!-- this function loads the user events into the right panel -->
                    <div id="plans">
                        <ul>
                            <?php
                            $tracker = 0;
                            foreach ($result as $plan) {
                                ?> 
                                <li>
                                    <a href="<?php echo $tracker; ?>">
                                        <div style="text-align: left; width:auto; height: auto; ">
                                            <div id="day_display" style="width:100%; height: auto;"> 
                                                <?php
                                                echo $plan->name . "  |  ";
                                                $date_string = date('D', strtotime($plan->date));
                                                echo $date_string;
                                                ?>
                                            </div>
                                            <?php
                                            echo "<p>";
                                            $date_string = date('l', strtotime($plan->date));
                                            echo $plan->category . "<br/>";
                                            echo $date_string . " " . $plan->time_of_day;
                                            echo "</p>";
                                            ?>
                                        </div>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>

                    <!--
                    var_dump($result);
                    $plans = $result;
                    include(APPPATH . 'assets/php/load_plans.php');
                    ?> -->
                </div>
            </div>

        </div>
    </center>
</body>
</html>
