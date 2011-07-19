<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">
<html>

    <head>
        <title>PlanJar | Login or sign up</title>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.validate-1.8.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/login.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>

        <link type="text/css" rel=stylesheet href="/application/assets/css/login.css"/>
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>
    </head>

    <body>

        <div id="container">

            <div style="position:relative; width:650px; margin-left: auto; margin-right: auto; height:1000px;">
                <div id="leftside">

                    <fieldset>

                        <legend>Enter site</legend>

                        <font color="purple" >
                            <div id="li_error" class ="error_message">
                                <!-- Errors will be displayed here --></div>
                        </font>
                        <center>
                            <form id="log_in" class="form">

                                <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                                <div class="div_texbox">
                                    <div class="in-field_block">
                                        <label for="li_email">Email</label>
                                        <input id="li_email" name="li_email" type="email" class="textbox"/>
                                    </div>
                                </div>
                                <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                                <div class="div_texbox">
                                    <div class="in-field_block">
                                        <label for="li_password">Password</label>
                                        <input id="li_password" name="li_password" type="password" class="textbox"/>
                                    </div>
                                </div>
                                <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                                <div class="button_div">
                                    <div style="position:relative; top:5px;">
                                        <input type="submit" class="buttons" value="Log In" />
                                        <input type="checkbox" name="li_remember" value="1" />&nbsp;<font style="font-family:Arial, Helvetica, sans-serif;">Stay logged in</font>
                                    </div>
                                </div>

                            </form>
                        </center>
                    </fieldset>
                    <div style="position:relative; width:338px; top:23px; text-align: center;"><center><img src="/application/assets/images/Planjar_logo.png" alt="PlanJar Logo"></center></div>


                </div>
                <div id="rightside">
                    <fieldset>
                        <legend>Not a member?  Sign up for free.</legend>

                        <font color="purple" >
                            <div id="su_error" class="error_message">
                                <!-- Errors will be displayed here -->
                            </div>
                        </font>

                        <center>
                            <form id="sign_up" class="form">

                                <div id="padding" style="position:relative; width:260px; height:10px;"></div>
                                <div class="div_texbox">
                                    <div class="in-field_block">
                                        <label for="su_email_1">Email</label>
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
                                        <label for="su_school">Choose your school</label>
                                        <input id="su_school" name="su_school" type="text" class="textbox">
                                    </div>
                                </div>

                                <input type="hidden" id="su_school_id" name="su_school_id"/>
                                <input type="hidden" id="su_school_name" name="su_school_name"/>

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
                                        <option value="female">Other</option>
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
                    </fieldset>

                </div>

                <div class="clear"></div>
            </div>

        </div>
    </body>
</html>
