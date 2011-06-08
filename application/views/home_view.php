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
                            <li> <a href="#monday">Monday</a></li>
                            <li><a href="#tuesday">Tuesday</a></li>
                            <li><a href="#wednesday">Wednesday</a></li>
                            <li><a href="#thursday">Thursday</a></li>
                            <li> <a href="#friday">Friday</a></li>
                            <li><a href="#saturday">Saturday</a></li>
                            <li><a href="#sunday">Sunday</a></li>
                        </ul>
                    
                </div>
                <div class="center_graph">

                    <div id="monday" class="tab_content"></div>
                    <div id="tuesday" class="tab_content">Yo</div>
                    <div id="wednesday" class="tab_content"></div>
                    <div id="thursday" class="tab_content"></div>
                    <div id="friday" class="tab_content"></div>
                    <div id="saturday" class="tab_content"></div>
                    <div id="sunday" class="tab_content"></div>

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
