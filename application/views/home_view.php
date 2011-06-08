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
                    <!-- Group panel -->
                    <?php include(APPPATH . 'assets/php/group_panel.php'); ?>
                </div>
            </div>

            <div class="center" >
                <div class="center_top">

                </div>
                <div class="center_day">
                    <!-- Weekday panel -->
                    <?php include(APPPATH . 'assets/html/weekday_panel.html'); ?>
                </div>
                <div class="center_graph">
                    <div id="tabs-1">
                        <p><strong>Monday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Tuesday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Wednesday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Thursday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Friday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Saturday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Sunday Information</strong></p>
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
                    <div id="tabs-1">
                        <p><strong>Monday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Tuesday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Wednesday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Thursday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Friday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Saturday Information</strong></p>
                    </div>
                    <div id="tabs-1">
                        <p><strong>Sunday Information</strong></p>
                    </div>
                </div>

            </div>

        </div>
    </center>
</body>
</html>
