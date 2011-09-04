<!doctype html>
<html>
    <head>
        <title>PlanJar | Login or sign up</title>

        <meta name="description" content="PlanJar is a location-based event planning web app currently exclusively for students with Vanderbilt email addresses.">

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/in-field_labels.css" type="text/css" />
        <link type="text/css" rel=stylesheet href="/application/assets/css/login.css"/>
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>

        <!-- JS -->
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.3.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.validate-1.8.1.js"></script>
        <script type="text/javascript" src="/application/assets/js/login.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/chartbeat_head.js"></script>
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
                        Hey, we are sorry, but some important features on PlanJar do not work in Internet Explorer. We apologize for the inconvenience, but do yourself a favor and install Google Chrome or Mozilla Firefox, your entire experience on the internet will be better for it.
                        <?php
                    }
                    ?>
                </div>  

            </div>
        </div>
        <div id="container">
            <div id="leftside" style="height:75px;">
                <h5><!-- PlanJar shows what groups of people are doing around you-->PlanJar is being released Monday September 5<span class="arrow"></span> </h5>
                <div id="left_title">
                </div>
                <div id="left_video" style="display:none;">
                </div>  
            </div>
            <div id="rightside">
                <div id="right_inner">
<!--                    <h3>Not a member?  Sign up for free.<span class="arrow"></span></h3>-->
                    <h3>Signup will open when PlanJar is released.<span class="arrow"></span></h3>
                    <font color="purple" >
                    <div id="su_error" class="error_message" style="text-align:center; color:red;">
                        <!-- Errors will be displayed here -->
                    </div>
                    </font>
                    <center>
                        <form id="sign_up" style="display: none;">
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
