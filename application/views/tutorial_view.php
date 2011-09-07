<!doctype html>
<html>  
    <head>
        <title>PlanJar | Tutorial</title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="Learn how to use PlanJar.">

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/tutorial.css" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css' />
        <link type="text/css" rel=stylesheet href="/application/assets/css/aw-showcase.css"/>

        <!-- JS -->
        <script type="text/javascript" src="/application/assets/js/chartbeat_head.js"></script>
        <script type="text/javascript" src="/application/assets/js/google_analytics.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.3.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.aw-showcase.min.js"></script>

        <script type="text/javascript">
            $(function() {
                $('#showcase').awShowcase({
                    content_width: 500,
                    content_height: 400,
                    arrows: false,
                    continuous: true,
                    interval: 6000,
                    auto: true
                });
            });
        </script>
    </head>

    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <a href="/home" style="z-index: 1000;">
                    <img src='/application/assets/images/pj_logo_white_text.png' style="float: left; margin-left:30px; height:35px; position:relative; top:5px;"/>
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
            <div id="showcase" class="showcase">
                <div class="showcase-slide">
                    <!-- Put the slide content in a div with the class .showcase-content -->
                    <div class="showcase-content">
                        <!-- If the slide contains multiple elements you should wrap them in a div with the class
                        .showcase-content-wrapper. We usually wrap even if there is only one element,
                        because it looks better. -->
                        <div class="showcase-content-wrapper">
                            <img src="/application/assets/images/showcase/a.png"/>
                        </div>
                    </div>
                    <!-- Put the caption content in a div with the class .showcase-caption -->
                    <div class="showcase-caption">
                        The home view...
                    </div>
                </div>

                <div class="showcase-slide">
                    <!-- Put the slide content in a div with the class .showcase-content -->
                    <div class="showcase-content">
                        <!-- If the slide contains multiple elements you should wrap them in a div with the class
                        .showcase-content-wrapper. We usually wrap even if there is only one element,
                        because it looks better. -->
                        <div class="showcase-content-wrapper">
                            <img src="/application/assets/images/showcase/b.png"/>
                        </div>
                    </div>
                    <!-- Put the caption content in a div with the class .showcase-caption -->
                    <div class="showcase-caption">
                        We're all about the groups and networks you join and follow.
                    </div>
                </div>

                <div class="showcase-slide">
                    <!-- Put the slide content in a div with the class .showcase-content -->
                    <div class="showcase-content">
                        <!-- If the slide contains multiple elements you should wrap them in a div with the class
                        .showcase-content-wrapper. We usually wrap even if there is only one element,
                        because it looks better. -->
                        <div class="showcase-content-wrapper">
                            <img src="/application/assets/images/showcase/c.png"/>
                        </div>
                    </div>
                    <!-- Put the caption content in a div with the class .showcase-caption -->
                    <div class="showcase-caption">
                        This is a group overview. See trends for the selected group or network.
                    </div>
                </div>

                <div class="showcase-slide">
                    <!-- Put the slide content in a div with the class .showcase-content -->
                    <div class="showcase-content">
                        <!-- If the slide contains multiple elements you should wrap them in a div with the class
                        .showcase-content-wrapper. We usually wrap even if there is only one element,
                        because it looks better. -->
                        <div class="showcase-content-wrapper">
                            <img src="/application/assets/images/showcase/d.png"/>
                        </div>
                    </div>
                    <!-- Put the caption content in a div with the class .showcase-caption -->
                    <div class="showcase-caption">
                        To the right of the info pane are your plans.
                    </div>
                </div>

                <div class="showcase-slide">
                    <!-- Put the slide content in a div with the class .showcase-content -->
                    <div class="showcase-content">
                        <!-- If the slide contains multiple elements you should wrap them in a div with the class
                        .showcase-content-wrapper. We usually wrap even if there is only one element,
                        because it looks better. -->
                        <div class="showcase-content-wrapper">
                            <img src="/application/assets/images/showcase/e.png"/>
                        </div>
                    </div>
                    <!-- Put the caption content in a div with the class .showcase-caption -->
                    <div class="showcase-caption">
                        Click on a plan to see more info or to leave comments for other attendees
                    </div>
                </div>

                <div class="showcase-slide">
                    <!-- Put the slide content in a div with the class .showcase-content -->
                    <div class="showcase-content">
                        <!-- If the slide contains multiple elements you should wrap them in a div with the class
                        .showcase-content-wrapper. We usually wrap even if there is only one element,
                        because it looks better. -->
                        <div class="showcase-content-wrapper">
                            <img src="/application/assets/images/showcase/f.png"/>
                        </div>
                    </div>
                    <!-- Put the caption content in a div with the class .showcase-caption -->
                    <div class="showcase-caption">
                        Making a plan is simple, even if you don't know exactly when you want to go.
                    </div>
                </div>

                <div class="showcase-slide">
                    <!-- Put the slide content in a div with the class .showcase-content -->
                    <div class="showcase-content">
                        <!-- If the slide contains multiple elements you should wrap them in a div with the class
                        .showcase-content-wrapper. We usually wrap even if there is only one element,
                        because it looks better. -->
                        <div class="showcase-content-wrapper">
                            <img src="/application/assets/images/showcase/g.png"/>
                        </div>
                    </div>
                    <!-- Put the caption content in a div with the class .showcase-caption -->
                    <div class="showcase-caption">
                        Inviting friends to your events is simple.
                    </div>
                </div>
            </div>

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

        <!-- Chartbeat -->
        <script type="text/javascript" src="/application/assets/js/chartbeat_body.js"></script>
    </body>  
</html>
