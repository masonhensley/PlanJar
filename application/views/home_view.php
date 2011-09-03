<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

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
        <link rel=stylesheet href="/application/assets/css/gradients.css" type="text/css" />

        <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>

        <!-- Google Font
        <link href='http://fonts.googleapis.com/css?family=Vollkorn|Ubuntu' rel='stylesheet' type='text/css'>-->

        <!-- jQuery and jQuery UI -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.3.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <!-- Google Maps API -->
        <script src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false" type="text/javascript"></script>

        <!-- Spinner plugin-->
        <script type="text/javascript" src="/application/assets/js/spin.js"></script>

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
        <script type="text/javascript" src="/application/assets/js/view_group_list.js"></script>
        <script type="text/javascript" src="/application/assets/js/info_map_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/day_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/invite_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/graph_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_conflict_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/add_location_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/view_friends_plans.js"></script>
        <script type="text/javascript" src="/application/assets/js/feedback.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_attending_panel.js"></script>
        <script type="text/javascript" src="/application/assets/js/find_places.js"></script>


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

        <script type="text/javascript">
            if ('<?php echo($action_type); ?>' == 'show_location') {
                // Check the id
                $.get('/home/check_location_id', {id: '<?php echo($action_arg); ?>'}, function(data) {
                    if (data == 'success') {
                        found_location = '<?php echo($action_arg); ?>';
                        display_info();
                    }
                });
            }
        </script>

        <title>PlanJar | Home</title>

        <!-- Google Analytics again, out of the view -->
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
                <a href="/home"><img src='/application/assets/images/beta3_white_text.png' style="float: left; margin-left:18px; height:84%; position:relative; top:7px;"/></a>
                <a href="/dashboard/profile"><div id="show_name">Welcome, <?php echo " " . $firstname . " " . $lastname; ?></div></a>
                <a href="/dashboard/profile">
                    <?php echo($this->load_profile->insert_profile_picture($this->ion_auth->get_user()->id, 40, 'position:absolute; top:0px;left:431px;')); ?>
                </a>
                <a href="/dashboard/profile" id="profile_link"><div class ="top_right_link_outer">Profile</div></a>
                <a href="/dashboard/groups" id="profile_link"><div class ="top_right_link_outer">Groups</div></a>
                <a href="/dashboard/friends" id="profile_link"><div class ="top_right_link_outer">Friends</div></a>
                <a href="/dashboard/following" id="profile_link"><div class ="top_right_link_outer">Following</div></a>
                <a href="/dashboard/notifications" id="profile_link"><div class="top_right_link_outer" id="notifications">Notifications</div></a>
                <a href="/dashboard/settings" ><div class ="top_right_link_outer">Settings</div></a>
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
                <a href="/admin_dashboard" style="position:absolute; top:-37px; right:78px;">Admin Dashboard</a>
                <a href="/auth/logout" style="position:absolute; top:-37px; right:15px;">Log Out</a>
                <?php
            } else
            {
                ?>
                <a href="/auth/logout" style="position:absolute; top:-37px; right:78px;">Log Out</a>
                <?php
            }
            ?>
            <div id="find_places">Find places</div>
            <div class="view_friends_plans">Friends' plans</div>
            <div  id="create_plan">Make a plan</div>

            <div id="home_data_spinner" style="position:absolute; left:174px; top:31px;"></div>
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
                <div class="bottom_right_section">
                    <iframe src="http://www.facebook.com/plugins/like.php?app_id=132900086806376&amp;href=http%3A%2F%2Fwww.facebook.com%2Fplanjar&amp;send=false&amp;layout=button_count&amp;width=125&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:99px; height:21px; margin-top: 16px;margin-left:19px;" allowTransparency="true"></iframe>
                    <a href="http://twitter.com/share" class="twitter-share-button" data-text="Join the new social network and see graphical trends of what Vanderbilt students are doing" data-count="horizontal" data-via="planjar">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
                </div>
                <div class="comment_box">
                    <textarea id="comment_area" name="comments" cols="30" rows="3"  maxlength="100"></textarea>
                    <div class="submit_comment">Submit</div>
                </div>
                <div class="plan_comments"></div>
            </div>
            <div id="home_plan_spinner" style="position:absolute; right:180px; top:19px;"></div>
            <div class ="right">
                <font style="font-weight:bold; color:gray;  font-size:20px;">Your Plans</font><br/>
                <hr/>
                <div class="plans_wrapper">
                    <?php echo($plans_html); ?>
                </div>
            </div>
        </div>

        <div class="bottom_links">
            <a href="/help" id="bottom_link">FAQ</a>
            <a href="/tutorial" id="bottom_link">Tutorial</a>
            <a href="/about" id="bottom_link">About Us</a>
            <a href="/privacy" id="bottom_link">Privacy</a>
            <!--<a href="http://blog.planjar.com/" id="profile_link">Blog</a>-->
        </div>

        <div id="plan_attending_panel" class="modal" style="left:43%; top:19%;">
            <div class="title_bar">
                <b>Guest List</b>
                <input  type="button" id="cancel_attending_panel"  style="float:right;" value="&times;"/>
            </div>
            <div class="guest_list_button_selected attending_button">Attending</div>
            <div class="awaiting_button">Not Responded</div>
            <div id="awaiting_reply" style="margin-top:25px; display:none;">
                <div id="awaiting_list" style="max-height:282px;overflow:auto;width:380px;">
                </div>
            </div>
            <div id="attending_modal_content" style="margin-top:25px;">
                <div class="attending_list" style="max-height:282px;overflow:auto;width:380px;">
                </div>
            </div>
        </div>

        <div id="group_member_panel" class="modal" style="left:43%; top:19%;">
            <div class="title_bar">
                <b>Group Members</b>
                <input type="button" id="cancel_group_member_panel" style="float:right;" value="&times;" />
            </div>
            <div id="group_member_content">
                <div class="member_list" style="max-height: 344px; overflow: auto; width:366px;">
                </div>    
            </div>
        </div>

        <div id="plans_made_here_modal" class="modal" style="left:43%; top:19%; width:280px;text-align:center;z-index:1000;">
            <div class="title_bar">
                <b>Plans made at this location</b>
                <input  type="button" id="cancel_location_plan_panel"  style="float:right;" value="&times;"/>
            </div>
            <div id="plans_made_here_list" style="max-height:300px;overflow:auto;"></div>
        </div>

        <?php include(APPPATH . 'assets/php/friends_plans_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/start_plan_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/invite_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/plan_conflict_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/add_location_modal.php'); ?>
    </body>
</html>
