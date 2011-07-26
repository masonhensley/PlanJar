<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/eggplant/theme.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/token-input-facebook.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/start_plan_modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/divset.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/in-field_labels.css" type="text/css" />

        <!-- jQuery and jQuery UI -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <!-- Google Maps API -->
        <script src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false" type="text/javascript"></script>

        <!-- Encapsulated JS files -->
        <script type="text/javascript" src="/application/assets/js/divset.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/groups_panel_functions.js"></script>

        <script type="text/javascript" src="/application/assets/js/start_plan_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/change_location.js"></script>
        <script type="text/javascript" src="/application/assets/js/data_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/data_box_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/day_tabs.js"></script>

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
                <div class="top_links">
                    <div id="show_name">Welcome, <?php echo " " . $firstname . " " . $lastname; ?></div>
                    <!--<a href="/dashboard/" id="dashboard link">dashboard &middot;</a>-->
                    <a href="/dashboard/profile" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Profile</div></div></a>
                    <a href="/dashboard/groups" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Groups</div></div></a>
                    <a href="/dashboard/followers" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Followers</div></div></a>
                    <a href="/dashboard/following" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Following</div></div></a>
                    <a href="/home/logout" ><div class ="top_right_link_outer"><div class="top_right_link_inner">Log out</div></div></a>
                </div>
            </div>
        </div>
        <div class="tab_bar">
            <div class="data_tab tab_selected" assoc_div="#group_data">Group Data</div>
            <div class="data_tab" assoc_div="#location_data">Location Data</div>
            <div class="data_tab" assoc_div="#map_data">Map</div>
            <div class="data_tab" assoc_div="#plan_data">Plan Data</div>
        </div>

        <div class ="container">

            <a href="/dashboard/notifications" id="notifications_link">Notifications</a>
            <div class ="left">
                <div class="location_container">
                    <div id="using_location">Using location:</div>
                    <a href="#" id="change_location" >Change location</a>
                </div>

                <?php include(APPPATH . 'models/load_group_panel.php'); ?>
            </div>
            <div class ="center">
                <div class="data_container_wrapper" style="display: block;">
                    <div id="group_data" class ="data_container" style="display: block;"></div>
                    <div id="plan_data" class ="data_container"></div>
                    <div id="location_data" class ="data_container"></div>
                    <div id="map_data" class ="data_container" style="display: block;'">
                        <?php include(APPPATH . 'assets/php/change_location_panel.php'); ?>
                        <div id="map"></div>
                    </div>
                </div>
            <div class="days_panel">

                    <div class="seven_days">
                        <?php echo($day_html); ?>
                    </div>
                </div>
                <div class="suggested_locations">
                </div>
                <div class="upcoming_events">
                </div>
            </div>
            <div class ="right">
                <div class ="right_header">
                    Plans <input type="button" id="create_plan" value="+"/>
                </div>
                <div class="plans_wrapper"><?php echo($plans_html); ?></div>
            </div>
        </div>
        <?php include(APPPATH . 'assets/php/start_plan_modal.php'); ?>
        <div class="bottom_links">
            Bottom link content will go in here; links, names, contact info, etc. it will be epic sauce
        </div>
    </body>
</html>
