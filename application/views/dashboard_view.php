<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/dashboard.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/eggplant/theme.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/dashboard_notifications.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/create_group_modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/token-input-facebook.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/divSet.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/dashboard_groups.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/in-field_labels.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/invite_modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/selectable_event.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/plan_conflict_modal.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/dashboard_settings.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/imgareaselect-default.css" type="text/css" />

        <!-- jQuery and jQuery UI -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <!-- Encapsulated JS files -->
        <script type="text/javascript" src="/application/assets/js/confirmDiv.js"></script>
        <script type="text/javascript" src="/application/assets/js/view_group_list.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_profile.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_following.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_friends.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_groups.js"></script>
        <script type="text/javascript" src="/application/assets/js/create_group_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_notifications.js"></script>
        <script type="text/javascript" src="/application/assets/js/divSet.js"></script>
        <script type="text/javascript" src="/application/assets/js/invite_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/plan_conflict_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/feedback.js"></script>
        <script type="text/javascript" src="/application/assets/js/spin.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_settings.js"></script>

        <!-- jQuery plugins -->
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.validate-1.8.1.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.tokeninput.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.form.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.imgareaselect.min.js"></script>

        <!-- Function to select the appropriate tab (value passed from PHP) -->
        <script type="text/javascript">
            $(function() {
                // Load the data container from the URI
                show_data_container('#<?php echo($initial_tab); ?>_content', '<?php echo($action_arg); ?>');
            });
            
            // This function is used to show the suggested tab if specified in the URI.
            // I had to do it this way because the click handlers aren't ready to be called from here right away.
            // This function is called after the click handlers are defined (in the respective files).
            function show_suggested_init(content_div, object_to_click) {
                //                if ('<?php echo($suggested); ?>' == 'suggested' && content_div == '<?php echo('#' . $initial_tab . '_content'); ?>') {
                //                    $('<?php echo('#' . $initial_tab . '_content'); ?> ' + object_to_click).click();
                //                }
            }
        </script>

        <title>PlanJar | Dashboard</title>

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
                <a href="/home"><img src='/application/assets/images/beta3_white_text.png' style="float: left; height:80%; position:relative; top:5px;"/></a>
                <div id="show_name"><?php echo " " . $firstname . " " . $lastname . "'s Dashboard"; ?></div>
                <a href="/auth/logout" ><div class ="top_right_link_outer"><div class="top_right_link_inner">Log out</div></div></a>
                <a href="/home/"><div class ="top_right_link_outer"><div class="top_right_link_inner">Home</div></div></a>
            </div>
        </div>
        <div class="container">
            <div class="tab_container">
                <div class="tab" assoc_div="#profile_content">Profile</div>
                <div class="tab" assoc_div="#groups_content">Groups</div>
                <div class="tab" assoc_div="#friends_content">Friends</div>
                <div class="tab" assoc_div="#following_content">Following</div>
                <div class="tab" assoc_div="#notifications_content">Notifications</div>
                <div class="tab" assoc_div="#settings_content">Settings</div>

                <div id="create_group">+ Create Group</div>
            </div>

            <div id="following_content" class="page_content" setup_func="following_setup">
                <?php include(APPPATH . '/assets/php/dashboard_following.php'); ?>
            </div>

            <div id="friends_content" class="page_content" setup_func="followers_setup">
                <?php include(APPPATH . '/assets/php/dashboard_friends.php'); ?>
            </div>

            <div id="groups_content" class="page_content" setup_func="groups_setup">
                <?php include(APPPATH . '/assets/php/dashboard_groups.php'); ?>
            </div>

            <div id="notifications_content" class="page_content" setup_func="notifications_setup">
                <?php include(APPPATH . '/assets/php/dashboard_notifications.php'); ?>
            </div>

            <div id="settings_content" class="page_content" setup_func="settings_setup">
                <?php include(APPPATH . '/assets/php/dashboard_settings.php'); ?>
            </div>

            <div id="profile_content" setup_func="setup_profile" class="page_content">
                <div id="load_profile_spinner" style="position:absolute;top:20px;left:540px;"></div>
                <div class="profile_container">
                    <div class="profile_box">
                    </div>
                </div>
            </div>

            <div id="settings_content" class="page_content"></div>
        </div>
        <div id="group_member_panel" class="modal" style="left:43%; top:19%;">
            <div class="title_bar">
                <b>Group Members</b>
                <input type="button" id="cancel_group_member_panel" style="float:right;" value="X" />
            </div>
            <div id="group_member_content">
                <div class="member_list" style="max-height: 344px; overflow: auto; width:366px;">
                </div>    
            </div>
        </div>
        <?php include(APPPATH . 'assets/php/create_group_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/invite_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/plan_conflict_modal.php'); ?>
    </body>
</html>
