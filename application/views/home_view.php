<html>
    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>

        <script type="text/javascript">
            $(document).ready(function() {

                //When page loads...
                $(".tab_content").hide(); //Hide all content
                $("ul.tabs li:first").addClass("active").show(); //Activate first tab
                $(".tab_content:first").show(); //Show first tab content

                //On Click Event
                $("ul.tabs li").click(function() {

                    $("ul.tabs li").removeClass("active"); //Remove any "active" class
                    $(this).addClass("active"); //Add "active" class to selected tab
                    $(".tab_content").hide(); //Hide all tab content

                    var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
                    $(activeTab).fadeIn(); //Fade in the active ID content
                    return false;
                });

            });
        </script>
    </head>

    <body >
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

                </div>
                <div class="center_day">
                    <!-- Weekday panel 
                    <?php //include(APPPATH . 'assets/html/weekday_panel.html'); ?> -->

                    <!-- Add the markup for the tabs, starting with today. -->
                    <?php $days = array('Sun', 'Mon', 'Tues', 'Weds', 'Thurs', 'Fri', 'Sat'); ?>
                    <ul class="tabs">
                        <li>
                            <a href="#<?php echo($days[date('w')]); ?>">Today</a>
                        </li>
                        <li>
                            <a href="#<?php echo($days[date('w') + 1]); ?>">Tom</a>
                        </li>
                        <?php
                        for ($i = 2; $i < 7; ++$i)
                        {
                            ?>
                            <li>
                                <a href="#<?php echo($days[(date('w') + $i) % 7]); ?>"><?php echo($days[(date('w') + $i) % 7]); ?></a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>

                </div>
                <div class="center_graph">

                    <div id="Sun" class="tab_content"></div>
                    <div id="Mon" class="tab_content"></div>
                    <div id="Tues" class="tab_content"></div>
                    <div id="Weds" class="tab_content"></div>
                    <div id="Thurs" class="tab_content"></div>
                    <div id="Fri" class="tab_content"></div>
                    <div id="Sat" class="tab_content"></div>

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
