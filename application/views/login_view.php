<!doctype html>
<html>
    <head>
        <title>PlanJar | Login or sign up</title>

        <meta name="description" content="PlanJar is a location-based event planning web app currently exclusively for students with Vanderbilt email addresses.">

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/in-field_labels.css" type="text/css" />
        <link type="text/css" rel=stylesheet href="/application/assets/css/login.css"/>
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>
        <link type="text/css" rel=stylesheet href="/application/assets/css/aw-showcase.css"/>

        <!-- JS -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.3.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.validate-1.8.1.js"></script>
        <script type="text/javascript" src="/application/assets/js/login.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/chartbeat_head.js"></script>
        <script type="text/javascript" src="/application/assets/js/google_analytics.js"></script>
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
                <div id="li_error" class ="error_message"><!-- Errors will be displayed here --></div>
                <img src='/application/assets/images/pj_logo_white_text.png' style="float: left; margin-left:30px; height:50%; position:relative; top:5px;"/>

                <div class="top_links">
                    <?php
                    if (!$using_ie)
                    {
                        ?>  
                        <form id="log_in">
                            <div class="in-field_block" style="display:inline-block;">
                                <label for="li_email">Email</label>
                                <input id="li_email" name="email" type="email" class="textbox" />
                            </div>
                            <div class="in-field_block" style="display:inline-block; margin-left:10px;">
                                <label for="li_password">Password</label>
                                <input id="li_password" name="password" type="password" class="textbox" />
                            </div>
                            <br/>
                            <font style="font-family:Arial, Helvetica, sans-serif; color:white;">Stay logged in</font>
                            <input type="checkbox" name="remember" value="1" style="margin-top:13px" />
                            <input type="submit" class="buttons" value="Log In" style="margin-left:87px;" />
                            <div style="margin-left:9px; display:inline-block;margin-top:12px;"><?php echo(anchor('auth/forgot_password', 'Forgot password')); ?></div>
                        </form>
                        <?php
                    } else
                    {
                        ?>
                        <font style="color:white;position:absolute; right:100px;width:535px; top:4px;">Some important features on PlanJar do not work in Internet Explorer.  We apologize for the inconvenience, but do yourself a favor and install 
                        <a href="http://www.google.com/chrome" style="color:lightblue;">Google Chrome </a>, 
                        <a href="http://sjc.mozilla.com/en-US/firefox/new/" style="color:lightblue;">Mozilla Firefox</a>, or 
                        <a href="http://www.apple.com/safari/download/" style="color:lightblue;">Safari</a>.  Your entire experience on the internet will be better.</font>
                        <?php
                    }
                    ?>
                </div>  

            </div>
        </div>
        <div id="container">
            <div id="leftside">
                <div class="go_dores_message">
                    Welcome, <font style="color:#DCD77E;">Vanderbilt</font>. Go 'Dores!
                </div>
                <h5>Make plans with friends while anonymously influencing your larger social circles<span class="arrow"></span> </h5>
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
                            This is the main view, which provides an interface to see graphical information of people's plans
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
                            The groups you join and follow are on the left panel.  Select one to see more information about what they are doing.
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
                            A group overview shows you both trends for the selected group or network, and the top locations they are going by day.
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
                            To the right are the plans you have made.
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
                            Click on a plan to see detailed information about it, or to leave comments for other attendees.
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
                            Inviting friends, groups, and schoolmates to your plans is easy.
                        </div>
                    </div>
                </div>
            </div>
            <div id="rightside">
                <div id="right_inner">
                    <h3>Not a member?  Sign up for free.<span class="arrow"></span></h3>
                    <font color="purple" >
                    <div id="su_error" class="error_message" style="text-align:center; color:red;">
                        <!-- Errors will be displayed here -->
                    </div>
                    </font>
                    <center>
                        <form id="sign_up">
                            <div class="div_texbox">
                                <div class="in-field_block" style="margin-top:12px;">
                                    <label for="su_email_1">University email</label>
                                    <input id="su_email_1" name="su_email_1" type="email" class="textbox">
                                </div>
                            </div>
                            <div class="div_texbox">
                                <div class="in-field_block" style="margin-top:12px;">
                                    <label for="su_email_2">Confirm email</label>
                                    <input id="su_email_2" name="su_email_2" type="email" class="textbox">
                                </div>
                            </div>

                            <div class="div_texbox">
                                <div class="in-field_block" style="margin-top:12px;">
                                    <label for="su_password">Password</label>
                                    <input id="su_password" name="su_password" type="password" class="textbox">
                                </div>
                            </div>

                            <div class="div_texbox">
                                <div class="in-field_block" style="margin-top:12px;">
                                    <label for="su_first_name">First name</label>
                                    <input id="su_first_name" name="su_first_name" type="text" class="textbox">
                                </div>
                            </div>

                            <div class="div_texbox">
                                <div class="in-field_block" style="margin-top:12px;">
                                    <label for="su_last_name">Last name</label>
                                    <input id="su_last_name" name="su_last_name" type="text" class="textbox">
                                </div>
                            </div>

                            <div class="div_texbox">
                                <div class="in-field_block" style="margin-top:12px;">
                                    <label for="su_grad_year">Graduation year (yyyy)</label>
                                    <input id="su_grad_year" name="su_grad_year" type="text" class="textbox">
                                </div>
                            </div>

                            <div class="div_texbox" style="margin-top:12px;">
                                <font style="opacity:1; font: normal 18px Arial;
                                      color: #999;">&nbsp;Birthday
                                </font>
                                <select name="su_month">
                                    <option value=""selected="selected">Month</option>
                                    <option value="1">Jan</option>
                                    <option value="2">Feb</option>
                                    <option value="3">Mar</option>
                                    <option value="4">Apr</option>
                                    <option value="5">May</option>
                                    <option value="6">Jun</option>
                                    <option value="7">July</option>
                                    <option value="8">Aug</option>
                                    <option value="9">Sept</option>
                                    <option value="10">Oct</option>
                                    <option value="11">Nov</option>
                                    <option value="12">Dec</option>
                                </select>
                                <select name="su_day">
                                    <option value="" selected="selected">Day</option>
                                    <?php
                                    for ($i = 1; $i <= 31; ++$i)
                                    {
                                        echo("<option value=\"" . $i . "\">" . $i . "</option>");
                                    }
                                    ?>
                                </select>

                                <select name="su_year">
                                    <option value="" selected="selected">Year</option>
                                    <?php
                                    for ($i = date('Y') - 13; $i >= date('Y') - 105; --$i)
                                    {
                                        echo("<option value=\"" . $i . "\">" . $i . "</option>");
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="div_texbox" style="margin-top:10px;">
                                <select name="su_sex">
                                    <option value="" selected="selected">Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>                            
                            <div class="button_div">
                                <div style="margin-top:10px; margin-bottom:10px;">
                                    <input class="buttons" type="submit" value="Sign up">
                                </div>
                            </div>
                        </form>
                    </center>
                </div>
                <div class="browser_message">
                    <font style="color:gray;">For the site to work properly, please make sure you are using the most recent version of 
                    <a href="http://www.google.com/chrome" style="color:navy;">Google Chrome</a>, 
                    <a href="http://sjc.mozilla.com/en-US/firefox/new/" style="color:navy;">Mozilla Firefox</a>, or 
                    <a href="http://www.apple.com/safari/download/" style="color:navy;">Safari</a></font>.
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
