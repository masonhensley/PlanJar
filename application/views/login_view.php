<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">
<html>

    <head>
        <title>PlanJar | Login or sign up</title>
        <?php
        // Add the includes from js-css-includes.
        echo(add_includes());
        ?>

    </head>

    <body>
        <!-- this was pulled from Tableless forms -->
        <div id="container">

            <div style="position:relative; width:650px; margin-left: auto; margin-right: auto; height:1000px;">
                <div id="leftside">

                    <fieldset>

                        <legend>Enter site</legend>

                        <font color="purple" >
                            <div id="li_error" class ="error_message">
                                <!-- Errors will be displayed here -->
                            </div>
                        </font>

                        <form id="log_in" class="form">

                            <div class="div_texbox">
<!--                                <p>
                                    <label for="li_email">Email</label><br>-->
                                <input id="li_email" name="li_email" type="text" class="textbox" id="li_email" />
                                <!--                                </p>-->
                            </div>

                            <div class="div_texbox">
<!--                                <p>
                                    <label for="li_password">Password</label>-->
                                <input name="li_password" type="password" class="textbox" id="password" />
                                <!--                            </p>-->
                            </div>

                            <div class="button_div">
                                <div style="position:relative; top:5px;">
                                    <input type="submit" class="buttons" value="Log In" />
                                    <input type="checkbox" name="li_remember" value="1" />&nbsp;<font style="font-family:Arial, Helvetica, sans-serif;">Stay logged in</font>
                                </div>
                            </div>

                        </form>

                    </fieldset>
                    <div style="position:relative; width:292px; text-align: center;"><center><img src="/application/assets/images/Planjar logo.png" alt="PlanJar Logo"></center></div>


                </div>
                <div id="rightside">
                    <fieldset>
                        <legend>Not a member?  Sign up for free.</legend>

                        <font color="purple" >
                            <div id="su_error" class="error_message">
                                <!-- Errors will be displayed here -->
                            </div>
                        </font>

                        <form id="sign_up" action="/login/try_sign_up" method="get">

                            <div class="div_texbox">
                                <input id="su_email_1" name="su_email_1" type="text" class="textbox">
                            </div>

                            <div class="div_texbox">
                                <input name="su_email_2" type="text" class="textbox">
                            </div>

                            <div class="div_texbox" id="foo">
                                <input name="su_password" type="password" class="textbox">
                            </div>

                            <div class="div_texbox">
                                <input name="su_first_name" type="text" class="textbox">
                            </div>

                            <div class="div_texbox">
                                <input name="su_last_name" type="text" class="textbox">
                            </div>

                            <div class="div_texbox">
                                <input id="su_school" name="su_school" type="text" class="textbox" onblur="force_school()">
                            </div>

                            <!-- Hidden input used to pass the school id to the server instead of the school name -->
                            <input type="hidden" id="su_school_id" name="su_school_id"/>

                            <div class="div_texbox" style="">
                                <select class="buttons_black" name="su_sex">
                                    <option value="" selected="selected"></option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>

                            <div class="div_texbox">
                                <select name="su_month">
                                    <option value=""selected="selected"></option>
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
                                    <option value="" selected="selected"></option>
                                    <?php
                                    for ($i = 1; $i <= 31; ++$i)
                                    {
                                        echo("<option value=\"" . $i . "\">" . $i . "</option>");
                                    }
                                    ?>
                                </select>

                                <select name="su_year">
                                    <option value="" selected="selected"></option>
                                    <?php
                                    for ($i = date('Y') - 13; $i >= date('Y') - 105; --$i)
                                    {
                                        echo("<option value=\"" . $i . "\">" . $i . "</option>");
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="div_texbox">
                                <input name="su_grad_year" type="text" class="textbox">
                            </div>

                            <div class="button_div">
                                <div style="position:relative; top:5px;">
                                    <input class="buttons" type="submit" value="Sign up">
                                </div>
                            </div>

                        </form>

                    </fieldset>

                </div>

                <div class="clear"></div>
            </div>

        </div>
    </body>
</html>
