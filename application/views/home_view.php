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
                $( "#tabs" ).tabs();
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
                    <!-- Weekday panel 
                    <?php //include(APPPATH . 'assets/html/weekday_panel.html'); ?> -->
                    
                    
                        <ul class="tabs">
                            <li> <a href="#tabs-1">Monday</a></li>
                            <li><a href="#tabs-2">Tuesday</a></li>
                            <li><a href="#tabs-3">Wednesday</a></li>
                            <li><a href="#tabs-4">Thursday</a></li>
                            <li> <a href="#tabs-5">Friday</a></li>
                            <li><a href="#tabs-6">Saturday</a></li>
                            <li><a href="#tabs-7">Sunday</a></li>
                        </ul>
                    
                </div>
                <div class="center_graph">

                    <div id="tabs-1">Monday</div>
                    <div id="tabs-2">Tuesday</div>
                    <div id="tabs-3">Wednesday</div>
                    <div id="tabs-4">Thursday</div>
                    <div id="tabs-5">Friday</div>
                    <div id="tabs-6">Saturday</div>
                    <div id="tabs-7">Sunday</div>

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
