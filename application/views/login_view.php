<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>PlanJar | Login or sign up</title>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.validate-1.8.1.js"></script>
        <script type="text/javascript" src="/application/assets/js/login.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <link rel=stylesheet href="/application/assets/css/in-field_labels.css" type="text/css" />

        <link type="text/css" rel=stylesheet href="/application/assets/css/login.css"/>
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>

        <!-- Mixpanel --><script type="text/javascript">var mpq=[];mpq.push(["init","ccd5fd6c9626dca4f5a3b019fc6c7ff4"]);(function(){var a=document.createElement("script");a.type="text/javascript";a.async=true;a.src=(document.location.protocol==="https:"?"https:":"http:")+"//api.mixpanel.com/site_media/js/api/mixpanel.js";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)})();</script><!-- End Mixpanel -->

    </head>
    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <div id="li_error" class ="error_message"><!-- Errors will be displayed here --></div>     
                <img src='/application/assets/images/pj_logo_white_text.png' style="float: left; margin-left:30px; height:50%; position:relative; top:5px;"/>
                <div class="top_links">
                    <form id="log_in">
                        <div class="in-field_block log_in_bar" style="display:inline-block;">
                            <label for="li_email">Email</label>
                            <input id="li_email" name="li_email" type="email" class="textbox" />
                        </div>
                        <div class="in-field_block log_in_bar" style="display:inline-block; margin-left:10px;">
                            <label for="li_password">Password</label>
                            <input id="li_password" name="li_password" type="password" class="textbox" />
                        </div>
                        <br/>
                        <font style="font-family:Arial, Helvetica, sans-serif; color:white;">Stay logged in</font>
                        <input type="checkbox" name="li_remember" value="1" style="margin-top:13px" />
                        <input type="submit" class="buttons" value="Log In" style="margin-left:87px;" />
                    </form>
                </div>  
            </div>
        </div>






        <div id="container">


            <div id="leftside">
                <h5> PlanJar shows you what groups of people are doing around you. Join them or do something else to your liking. It's up to you! <span class="arrow"></span> </h5>

                <div id="left_title">

                </div>

                <div id="left_video">
                    video
                </div>  

            </div>


            <div id="rightside">

                <div id="right_inner">
                    <h6>Not a member?  Sign up for free.<span class="arrow"></span></h6>
                    <font color="purple" >
                        <div id="su_error" class="error_message">
                            <!-- Errors will be displayed here -->
                        </div>
                    </font>
                    <center>
                        <form id="sign_up">
                            <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                            <div class="div_texbox">
                                <div class="in-field_block">
                                    <label for="su_email_1">University email</label>
                                    <input id="su_email_1" name="su_email_1" type="email" class="textbox">
                                </div>
                            </div>
                            <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                            <div class="div_texbox">
                                <div class="in-field_block">
                                    <label for="su_email_2">Confirm email</label>
                                    <input id="su_email_2" name="su_email_2" type="email" class="textbox">
                                </div>
                            </div>
                            <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                            <div class="div_texbox">
                                <div class="in-field_block">
                                    <label for="su_password">Password</label>
                                    <input id="su_password" name="su_password" type="password" class="textbox">
                                </div>
                            </div>
                            <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                            <div class="div_texbox">
                                <div class="in-field_block">
                                    <label for="su_first_name">First name</label>
                                    <input id="su_first_name" name="su_first_name" type="text" class="textbox">
                                </div>
                            </div>
                            <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                            <div class="div_texbox">
                                <div class="in-field_block">
                                    <label for="su_last_name">Last name</label>
                                    <input id="su_last_name" name="su_last_name" type="text" class="textbox">
                                </div>
                            </div>
                            <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                            <div class="div_texbox">
                                <div class="in-field_block">
                                    <label for="su_grad_year">Graduation year (yyyy)</label>
                                    <input id="su_grad_year" name="su_grad_year" type="text" class="textbox">
                                </div>
                            </div>
                            <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                            <div class="div_texbox"><font style="opacity:1; font: normal 18px Arial;
                                                          color: #999;">&nbsp;Birthday</font>
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
                            <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                            <div class="div_texbox">
                                <select name="su_sex">
                                    <option value="" selected="selected">Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                            <div class="button_div">
                                <div style="position:relative; top:5px;">
                                    <input class="buttons" type="submit" value="Sign up">
                                </div>
                            </div>
                            <div id="padding" style="position:relative; width:260px; height:7px;"></div>
                        </form>
                    </center>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <a href="http://mixpanel.com/f/partner"><img src="http://mixpanel.com/site_media/images/partner/badge_blue.png" alt="Web Analytics" /></a>

    <div id="footer">
        <!-- Begin Footer Menu -->
        <ul id="footer_menu">
            <li><a href="#"><span>Home</span></a></li>
            <li><a href="#">Services</a>
            <li><a href="#">Portfolio</a>
            <li><a href="#">Friends</a>
            <li><a href="#">Blog</a>
            <li><a href="#">Testimonials</a>
            <li><a href="#">Contact</a>
        </ul>
    </div>
</div>




</body>
</html>
