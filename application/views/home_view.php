<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/eggplant/theme.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/token-input-facebook.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/start_plan_modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/divSet.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/in-field_labels.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/invite_modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/group_info.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/graphs.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/plan_conflict_modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/selectable_event.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/location_data.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/add_location_modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/plan_info.css" type="text/css" />

        <!-- Google Font
        <link href='http://fonts.googleapis.com/css?family=Vollkorn|Ubuntu' rel='stylesheet' type='text/css'>-->

        <!-- jQuery and jQuery UI -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <!-- Google Maps API -->
        <script src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false" type="text/javascript"></script>

        <!-- Encapsulated JS files -->
        <script type="text/javascript" src="/application/assets/js/date.js"></script>
        <script type="text/javascript" src="/application/assets/js/divSet.js"></script>
        <script type="text/javascript" src="/application/assets/js/confirmDiv.js"></script>
        <script type="text/javascript" src="/application/assets/js/map_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/groups_panel_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/start_plan_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/change_location.js"></script>
        <script type="text/javascript" src="/application/assets/js/info_map_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/day_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/invite_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/graph_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_conflict_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/add_location_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/view_friends_plans.js"></script>
        <script type="text/javascript" src="/application/assets/js/feedback.js"></script>
        <script type="text/javascript" src="/application/assets/js/spin.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_attending_panel.js"></script>

        <!-- D3 plugin -->
        <script type="text/javascript" src="/application/assets/js/d3.js"></script>

        <!-- notifications plugin -->
        <script type="text/javascript" src="/application/assets/js/badger/badger.js"></script>
        <!-- notifications css -->
        <link rel=stylesheet href="/application/assets/js/badger/badger.css" type="text/css" />

        <!-- jQuery plugins -->
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.validate-1.8.1.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.tokeninput.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.timeentry.min.js"></script>

        <title>PlanJar | Home</title>

        <!-- Mixpanel --><script type="text/javascript">var mpq=[];mpq.push(["init","ccd5fd6c9626dca4f5a3b019fc6c7ff4"]);(function(){var a=document.createElement("script");a.type="text/javascript";a.async=true;a.src=(document.location.protocol==="https:"?"https:":"http:")+"//api.mixpanel.com/site_media/js/api/mixpanel.js";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)})();</script><!-- End Mixpanel -->

        <!-- Google Analytics -->
        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-23115103-4']);
            _gaq.push(['_setDomainName', '.planjar.com']);
            _gaq.push(['_trackPageview']);
            
            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
            
        </script>

    </head>
    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <img src='/application/assets/images/pj_logo_white_text.png' style="float: left; margin-left:22px; height:80%; position:relative; top:5px;"/>
                <div class="top_links">
                    <div id="show_name">Welcome, <?php echo " " . $firstname . " " . $lastname; ?></div>
                    <!--<a href="/dashboard/" id="dashboard link">dashboard &middot;</a>-->
                    <a href="/dashboard/profile" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Profile</div></div></a>
                    <a href="/dashboard/groups" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Groups</div></div></a>
                    <a href="/dashboard/friends" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Friends</div></div></a>
                    <a href="/dashboard/following" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Following</div></div></a>
                    <a href="/dashboard/notifications" id="profile_link"><div class="top_right_link_outer" id="notifications"><div class="top_right_link_inner">Notifications</div></div></a>
                    <a href="/dashboard/settings" ><div class ="top_right_link_outer"><div class="top_right_link_inner">Settings</div></div></a>
                </div>
            </div>
        </div>
        <div class="tab_bar">
            <div class="data_tab tab_selected" assoc_div="#info_content">Info</div>
            <div class="data_tab" assoc_div="#map_content">Map</div>
        </div>
        <div class ="container">
            <?php
            if ($this->ion_auth->get_user()->group_id == 1)
            {
                ?>
                <a href="/admin_dashboard" style="position:absolute; color:darkgray; top:-37px; right:78px;">Admin Dashboard</a>
                <a href="/home/logout" style="position:absolute; color:darkgray; top:-37px; right:15px;">Log Out</a>
                <?php
            } else
            {
                ?>
                <a href="/home/logout" style="position:absolute; color:darkgray; top:-37px; right:78px;">Log Out</a>
                <?php
            }
            ?>
            <div class="view_friends_plans">Friends' plans</div>
            <div  id="create_plan">Make a plan</div>
            <div class ="left">
                <div class="location_container">
                    <div id="using_location">Using location:</div>
                    <a href="#" id="change_location" >Change location</a>
                </div>

                <?php include(APPPATH . 'assets/php/load_group_panel.php'); ?>
            </div>
            <div class ="center">
                <div class="data_container_wrapper" style="display: block;">
                    <div id="info_content" class ="data_container" style="display: block;"></div>
                    <div id="map_content" class ="data_container">
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
                    <!--
                    <img src="http://placehold.it/125x125">
                    <img src="http://placehold.it/125x125">
                    <img src="http://placehold.it/280x125">
                    <img src="http://placehold.it/280x100">
                    -->
                </div>
            </div>
            <div class ="right">
                <font style="font-weight:bold; color:gray;  font-size:20px;">Your Plans</font><br/>

                <hr/>
                <div class="plans_wrapper">
                    <?php echo($plans_html); ?>
                </div>
            </div>
        </div>

        <div class="bottom_links">
            <a href="/help" id="profile_link">Help & FAQ</a>
            <a href="/about" id="profile_link">About</a>
            <a href="/privacy" id="profile_link">Privacy</a>
            <a href="http://blog.planjar.com/" id="profile_link">Blog</a>
        </div>

        <div id="plan_attending_panel" class="modal" style="left:43%; top:19%;">
            <div class="title_bar">
                <b>Attending List</b>
                <input  type="button" id="cancel_friends_panel"  style="float:right;" value="X"/>
            </div>
            <div id="attending_modal_content">
                <div class="attending_list">
                </div>
            </div>
        </div>

        <?php include(APPPATH . 'assets/php/friends_plans_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/start_plan_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/invite_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/plan_conflict_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/add_location_modal.php'); ?>

        <script type="text/javascript">
            var _sf_async_config={uid:27655,domain:"testing.pagodabox.com"};
            (function(){
                function loadChartbeat() {
                    window._sf_endpt=(new Date()).getTime();
                    var e = document.createElement('script');
                    e.setAttribute('language', 'javascript');
                    e.setAttribute('type', 'text/javascript');
                    e.setAttribute('src',
                    (("https:" == document.location.protocol) ? "https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" : "http://static.chartbeat.com/") +
                        "js/chartbeat.js");
                    document.body.appendChild(e);
                }
                var oldonload = window.onload;
                window.onload = (typeof window.onload != 'function') ?
                    loadChartbeat : function() { oldonload(); loadChartbeat(); };
            })();
            
        </script>

    </body>
</html>
