<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

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

        <!-- jQuery and jQuery UI -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <!-- Encapsulated JS files -->
        <script type="text/javascript" src="/application/assets/js/confirmDiv.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_tabs.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_following.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_followers.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_groups.js"></script>
        <script type="text/javascript" src="/application/assets/js/create_group_modal.js"></script>
        <script type="text/javascript" src="/application/assets/js/dashboard_notifications.js"></script>
        <script type="text/javascript" src="/application/assets/js/divSet.js"></script>
        <script type="text/javascript" src="/application/assets/js/invite_modal.js"></script>

        <!-- jQuery plugins -->
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.validate-1.8.1.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.tokeninput.js"></script>

        <!-- Function to select the appropriate tab (value passed from PHP) -->
        <script type="text/javascript">
            $(function() {
                // Load the data container from the URI
                $('.tab_container [assoc_div="<?php echo('#' . $initial_tab . '_content'); ?>"]').click();
            });
            
            // This function is used to show the suggested tab if specified in the URI.
            // I had to do it this way because the click handlers aren't ready to be called from here right away.
            // This function is called after the click handlers are defined (in the respective files).
            function show_suggested_init(content_div, object_to_click) {
                if ('<?php echo($suggested); ?>' == 'suggested' && content_div == '<?php echo('#' . $initial_tab . '_content'); ?>') {
                    $('<?php echo('#' . $initial_tab . '_content'); ?> ' + object_to_click).click();
                }
            }
        </script>

        <title>PlanJar | Dashboard</title>
    </head>
    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <div id="show_name"><?php echo " " . $firstname . " " . $lastname . "'s Dashboard"; ?></div>
                <a href="/home/"><div class ="top_right_link_outer"><div class="top_right_link_inner">Home</div></div></a>
                <a href="/home/"><div class ="top_right_link_outer"><div class="top_right_link_inner">Create Plan</div></div></a>
                <a href="/home/logout" ><div class ="top_right_link_outer"><div class="top_right_link_inner">Log out</div></div></a>
            </div>
        </div>
        <div class="container">
            <div class="tab_container">
                <div class="tab" assoc_div="#profile_content">Profile</div>
                <div class="tab" assoc_div="#groups_content">Groups</div>
                <div class="tab" assoc_div="#followers_content">Followers</div>
                <!--<div class="tab" assoc_div="#plans_content">Plans</div>-->
                <div class="tab" assoc_div="#following_content">Following</div>
                <div class="tab" assoc_div="#notifications_content">Notifications</div>
                <div class="tab" assoc_div="#settings_content">Settings</div>

                <div id="create_group">+ Create Group</div>
            </div>

            <div id="following_content" class="page_content" setup_func="following_setup">
                <?php include(APPPATH . '/assets/php/dashboard_following.php'); ?>
            </div>

            <div id="followers_content" class="page_content" setup_func="followers_setup">
                <?php include(APPPATH . '/assets/php/dashboard_followers.php'); ?>
            </div>

            <div id="groups_content" class="page_content" setup_func="groups_setup">
                <?php include(APPPATH . '/assets/php/dashboard_groups.php'); ?>
            </div>

            <div id="notifications_content" class="page_content" setup_func="notifications_setup">
                <?php include(APPPATH . '/assets/php/dashboard_notifications.php'); ?>
            </div>

            <div id="plans_content" class="page_content">

            </div>

            <div id="profile_content" setup_func="$.noop" class="page_content">
                <div class="profile_container">
                    <div class="profile_box">
                    </div>
                    <div class="profile_edit">
                    </div>
                </div>
            </div>

            <div id="settings_content" class="page_content"></div>
        </div>
        <?php include(APPPATH . 'assets/php/create_group_modal.php'); ?>
        <?php include(APPPATH . 'assets/php/invite_modal.php'); ?>
    </body>
</html>
