<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/dashboard.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/eggplant/theme.css" type="text/css" />

        <!-- jQuery and jQuery UI -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <!-- Encapsulated JS files -->
        <script type="text/javascript" src="/application/assets/js/dashboard_tabs_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/friends_dashboard_functions.js"></script>

        <title>PlanJar | Dashboard</title>
    </head>
    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">     
            </div>
        </div>
        <div class="container">
            <div class="tab_container">
                <div class="tab" assoc_div="#friends_content">Friends</div>
                <div class="tab" assoc_div="#groups_content">Groups</div>
                <div class="tab" assoc_div="#plans_content">Plans</div>
                <div class="tab" assoc_div="#profile_content">Profile</div>
                <div class="tab" assoc_div="#settings_content">Settings</div>
            </div>

            <div id="friends_content" class="page_content">
                <div class="left">
                    <div class="radio">
                        <center>
                            <label for="friends_following">Following</label>
                            <input type="radio" id="friends_following" value="following" name="following_follower_group"/>
                            <label for="friends_followers">Followers</label>
                            <input type="radio" id="friends_followers" value="followers" name="following_follower_group"/>
                        </center>
                    </div>

                    <div id="find_friends">
                        <div class="in-field_block">
                            <label for="friend_search"></label>
                            <input type="text" id="friend_search" />
                        </div>
                    </div>
                    <br/>
                    <hr/>

                    <div class="friends_list">
                    </div>
                </div>
                <div class="right">
                </div>
            </div>
            <div id="groups_content" class="page_content"></div>
            <div id="plans_content" class="page_content"></div>
            <div id="profile_content" class="page_content"></div>
            <div id="settings_content" class="page_content"></div>
        </div>
    </body>
</html>
