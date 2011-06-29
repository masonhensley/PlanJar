<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- jQuery and jQuery UI -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <!-- Encapsulated JS files -->
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/groups_panel_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/day_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_modal_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/map_data_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_tabs.js"></script>

        <!-- jQuery plugins -->
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/validation-1.8.1/jquery.validate.min.js"></script>

        <!-- Google -->
        <script src="http://maps.google.com/maps/api/js?libraries=places&sensor=false" type="text/javascript"></script>

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />

        <title></title>
    </head>
    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <div class ="planjar_log_box">
                </div>
            </div>
        </div>
        <div class ="container">
            <div class ="left">
                <div class="left_header">
                    My Groups
                </div>
            </div>
            <div class ="center">
                <div class="tab_bar">
                    <div class ="map_tab">Map</div>
                    <div class="group_data_tab">Group Data</div>
                    <div class="plan_data_tab">Plan Data</div>
                </div>
                <div id="map_data_tab" class ="data_container"></div>
                <div id="group_data_tab" class ="data_container"></div>
                <div id="plan_data_tab" class ="data_container"></div>
                
                <div class="days_panel">
                    <div class="left_day_arrow"></div>
                    <div class="right_day_arrow"></div>
                    <?php include(APPPATH . '/assets/php/weekday_panel.php'); ?>
                </div>
                <div class="top_left_plans"></div>
                <div class="ad_box">
                    <img src="/application/assets/images/Planjar_logo.png" style="position:relative; top:20px; width:85%; height:90%;"/>
                </div>
                <div class="bottom_left_plans"></div>
                <div class="bottom_right_plans"></div>                
            </div>
            <div class ="right">
                <div class ="right_header">
                    My Plans
                </div>
                <div class="plans_wrapper">

                </div>
            </div>
        </div>
    </body>
</html>
