<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- jQuery and jQuery UI -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <!-- Google Maps API -->
        <script src="http://maps.google.com/maps/api/js?libraries=places&sensor=false" type="text/javascript"></script>

        <!-- Encapsulated JS files -->
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/groups_panel_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/day_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_modal_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/map_data_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/change_location_functions.js"></script>

        <!-- jQuery plugins -->
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/validation-1.8.1/jquery.validate.min.js"></script>

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/eggplant/theme.css" type="text/css" />

        <title>PlanJar | Home</title>
    </head>
    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <div id="show_name" style="float:left; position:relative; ">Welcome, <?php echo " " . $firstname . " " . $lastname; ?></div>
                <div id="using_location" style="float:left; position:relative; top:15px;">Using location:</div>
                <div class="top_links">
                    <a href="#" id="change_location" style="color:white; text-decoration: none;">Change location</a>
                    <a href="#" id="view_current_location" style="color: white; text-decoration: none;">View current location</a>
                    <a href="/home/logout" style="">Log out</a>
                </div>

            </div>
        </div>
        <div class ="container">
            <div class ="left">
                <div class="left_header">
                    My Groups
                </div>

                <?php include(APPPATH . 'assets/php/group_panel.php'); ?>
            </div>
            <div class ="center">
                <div class="tab_bar">
                    <div class="data_tab" assoc_div="#group_data">Group Data</div>
                    <div class="data_tab" assoc_div="#plan_data">Plan Data</div>
                    <div class="data_tab" assoc_div="#location_data">Location Data</div>
                    <div class="data_tab" assoc_div="#map_data">Map</div>
                </div>

                <div class="data_container_wrapper">
                    <div id="group_data" class ="data_container">Select groups on the left to see more information.</div>
                    <div id="plan_data" class ="data_container">Select a plan on the right to see more information.</div>
                    <div id="location_data" class ="data_container">Select a location below to see more information.</div>
                    <div id="map_data" class ="data_container">
                        <?php include(APPPATH . 'assets/php/change_location_panel.php'); ?>
                        <div id="map"></div>
                    </div>
                </div>

                <div class="days_panel">
                    <div class="left_day_arrow"><</div>
                    <div class="right_day_arrow">></div>
                    <div class="seven_days"></div>
                </div>
                <div class="top_left_plans">Popular Locations</div>
                <div class="ad_box">
                    <img src="/application/assets/images/Planjar_logo.png" style="position:relative; top:20px; width:85%; height:90%;"/>
                </div>

                <div class="bottom_right_plans"></div>              
            </div>
            <div class ="right">
                <div class ="right_header">
                    My Plans <input type="button" id="create_plan" value="+"/>
                </div>
                <div class="plans_wrapper"></div>
            </div>
        </div>
        <?php include(APPPATH . 'assets/php/plan_panel.php'); ?>
    </body>
</html>
