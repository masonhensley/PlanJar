<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/help.css" type="text/css" />
        <link rel=stylesheet href="/application/assets/css/eggplant/theme.css" type="text/css" />

        <!-- jQuery and jQuery UI -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <title>PlanJar | Helpo</title>

        <script src='http://planjar.helpjuice.com/orange-juice/iFrameHeightJuicer.js'></script>
  
    </head>
    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <img src='/application/assets/images/pj_logo_white_text.png' style="float: left; margin-left:30px; height:80%; position:relative; top:5px;"/>
                <div class="top_links">
                    <div id="show_name">Welcome, <?php echo " " . $firstname . " " . $lastname; ?></div>
                    <!--<a href="/dashboard/" id="dashboard link">dashboard &middot;</a>-->
                    <a href="/dashboard/profile" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Profile</div></div></a>
                    <a href="/dashboard/groups" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Groups</div></div></a>
                    <a href="/dashboard/followers" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Followers</div></div></a>
                    <a href="/dashboard/following" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Following</div></div></a>
                    <a href="/home/logout" ><div class ="top_right_link_outer"><div class="top_right_link_inner">Log out</div></div></a>
                </div>
            </div>
        </div>

        <div class ="container">

            <div class ="center">
                <div class="data_container_wrapper" style="display: block;">
                     success
        <iframe src='http://planjar.helpjuice.com/' class='helpjuice-autoframe' width='710px' height='850px' frameborder='0'></iframe> 
                    </div>


                </div>
               </div>
            
        </div>
        <div class="bottom_links">
            Bottom link content will go in here; links, names, contact info, etc. it will be epic sauce
        </div>
    </body>
</html>

