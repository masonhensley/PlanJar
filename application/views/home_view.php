<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/eggplant/theme.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/token-input.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/plan_modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/divset.css" type="text/css" />

        <!-- jQuery and jQuery UI -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <!-- Google Maps API -->
        <script src="http://maps.google.com/maps/api/js?libraries=places&sensor=false" type="text/javascript"></script>

        <!-- Encapsulated JS files -->
        <script type="text/javascript" src="/application/assets/js/divset.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/groups_panel_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/day_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/change_location.js"></script>
        <script type="text/javascript" src="/application/assets/js/map_data_functions.js"></script>

        <!-- jQuery plugins -->
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.validate-1.8.1.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.tokeninput.js"></script>

        <title>PlanJar | Home</title>
    </head>
    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <img src='/application/assets/images/pj_logo_white_text.png' style="float: left; margin-left:30px; height:80%; position:relative; top:5px;"/>
                <div class ="top_panel_left">
                    <div id="show_name">Welcome, <?php echo " " . $firstname . " " . $lastname; ?></div>
                    <div id="location_conainer_top">
                        <div id="using_location">Using location:</div>
                    </div>
                </div>

                <div class="top_links">
                    <a href="/dashboard/" id="dashboard link">Dashboard &middot;</a>
                    <a href="#" id="change_location" >Change location &middot;</a>
                    <a href="/home/logout" >Log out</a>
                </div>

            </div>
        </div>
        <div class="tab_bar">
            <div class="data_tab" assoc_div="#group_data">Group Data</div>
            <div class="data_tab" assoc_div="#plan_data">Plan Data</div>
            <div class="data_tab" assoc_div="#location_data">Location Data</div>
            <div class="data_tab" assoc_div="#map_data">Map</div>
        </div>
        <div class ="container">
            <div class ="left">
                <div class="left_header">
                </div>

                <?php include(APPPATH . 'assets/php/group_panel.php'); ?>
            </div>
            <div class ="center">


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
                    
                    <div class="seven_days"></div>
                </div>
                <div class="top_left_plans">Popular Locations</div>
                <div class="ad_box">
                    <!-- <img src="/application/assets/images/Planjar_logo.png" style="position:relative; top:20px; width:85%; height:90%;"/> -->
                </div>

                <div class="bottom_right_plans"></div>              
            </div>
            <div class ="right">
                <div class ="right_header">
                    Plans <input type="button" id="create_plan" value="+"/>
                </div>
                <div class="plans_wrapper"></div>
            </div>
        </div>
        <?php include(APPPATH . 'assets/php/start_plan_modal.php'); ?>
        <div class="bottom_links">
            Bottom link content will go in here; links, names, contact info, etc. it will be epic sauce
        </div>
    </body>
</html>
