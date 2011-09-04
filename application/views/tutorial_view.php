<!doctype html>
<html>  
    <head>
        <title>Tutorial</title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="Learn how to use PlanJar.">

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/tutorial.css" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    </head>

    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <a href="/home">
                    <img src='/application/assets/images/pj_logo_white_text.png' style="float: left; margin-left:30px; height:80%; position:relative; top:5px;"/>
                </a>
                <div class="top_links">
                    <?php
                    if ($this->ion_auth->logged_in())
                    {
                        ?>
                        <a href="/home" id="profile_link" style="position:absolute; top:11px; left:225px;">Home</a>
                        <a href='/auth/logout' id="profile_link" style="position:absolute; top: 11px; left: 293px;">Log Out</a>
                        <?php
                    }
                    ?>
                </div>  
                <div id="container"> 

                    <h2> Tutorial <span class="arrow"></span> </h2>

                    <div id="main">  

                        <h4> Home View 1<span class="arrow"></span> </h4>
                        <div id = "img">
                            <img src="/application/assets/images/tour/tour1.png" alt="tour 1"/>
                        </div> 

                        <h4> Home View 2 <span class="arrow"></span> </h4> 
                        <div id = "img">
                            <img src="/application/assets/images/tour/tour2.png" alt="tour 2"/>
                        </div>

                    </div>

                </div> 
                <div class="bottom_links">
                    <a href="/help" id="bottom_link">FAQ</a>
                    <a href="/tutorial" id="bottom_link">Tutorial</a>
                    <a href="/about" id="bottom_link">About Us</a>
                    <a href="/privacy" id="bottom_link">Privacy</a>
                </div>
            </div>
    </body>  
</html>