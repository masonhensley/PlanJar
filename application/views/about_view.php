<!doctype html>
<html>  
    <head>  
        <title>About us</title> 

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="Read about PlanJar's founders.">

        <!-- CSS -->
        <link rel="stylesheet" href="/application/assets/css/about.css" type="text/css" />
        <link href="http://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet" type="text/css">
    </head>  

    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <a href="/home">
                    <img src='/application/assets/images/pj_logo_white_text.png' style="float: left; margin-left:45px; height:35px; position:relative; top:5px;"/>
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
            </div>
        </div>
        <div id="container">
            <h2> About Our Team <span class="arrow"></span> </h2> 
            <h4>Mason Hensley, Director of Black Ops, CEO & Co-Founder <span class="arrow_right"></span> </h4>
            <div class ="person">
                <div class ="info">
                    Mason Hensley is Co-Founder and CEO of PlanJar.  Prior to founding PlanJar, he briefly worked at the energy efficiency team at the Tennessee Valley Authority. <br> <br>
                    Mason holds a bachelor’s degree in Biomedical Engineering from Vanderbilt University; while there, he dabbled a little in Financial Economics. He’s also an Eagle Scout.<br/>
                    <a href="http://twitter.com/masonhensley" class="twitter-follow-button" data-show-count="false">Follow @bossier330</a>
                    <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
                    <a rel="author" href="https://plus.google.com/u/1/106727975968254892658/about">
                        <img src="http://www.google.com/images/icons/ui/gprofile_button-32.png" width="32" height="32">
                    </a>
                </div>
                <div class ="pic">
                    <img src="/application/assets/images/mason_bio.jpg" style="width:100%; height:100%;"/>
                </div>
            </div>
            <h4>Parker Bossier, Director of Tactical Ops, Software Architect & Co-Founder &#3232;_&#3232;<span class="arrow_right"></span> </h4>
            <div class ="person">
                <div class ="info">
                    Parker runs the engineering team with Wells (currently the only other member of said team) and directs the site's architecture and development.
                    Prior to this venture, Parker worked in IT for the legal sector and designed the VSVS online application as VSVS IT Chair.
                    <br/><br/>
                    Academically, Parker expects to graduate from Vanderbilt University in 2012 with a bachelor's degree in Computer Science and Math.<br/>
                    <a href="http://twitter.com/bossier330" class="twitter-follow-button" data-show-count="false">Follow @bossier330</a>
                    <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
                    <a rel="author" href="https://plus.google.com/u/1/113752633780932035775/posts">
                        <img src="http://www.google.com/images/icons/ui/gprofile_button-32.png" width="32" height="32">
                    </a>
                </div>
                <div class ="pic">
                    <img src="/application/assets/images/parker_bio.jpg" style="width:100%; height:100%;" />
                </div>
            </div>
            <h4> Wells Johnston, Director of Field Ops, Software Architect & Co-Founder ¯\_(ツ)_/¯<span class="arrow_right"></span> </h4>
            <div class ="person">
                <div class ="info">
                    Wells runs PlanJar’s engineering team with Parker, and directs the site architecture and development.  Before PlanJar, Wells worked as a
                    programmer at Moontoast.com, as well as a freelance web developer. <br><br>
                    Outside of PlanJar, Wells is currently pursuing a Bachelor of Science in both Computer Science and Mathematics from Vanderbilt University.<br/>
                    <a href="http://twitter.com/wellsjohnston" class="twitter-follow-button" data-show-count="false">Follow @bossier330</a>
                    <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
                    <a rel="author" href="https://plus.google.com/u/1/107114366163444332724/posts">
                        <img src="http://www.google.com/images/icons/ui/gprofile_button-32.png" width="32" height="32">
                    </a>
                </div>
                <div class ="pic">
                    <img src="/application/assets/images/wells_bio.jpg"/>
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
    </body>  
</html>  
