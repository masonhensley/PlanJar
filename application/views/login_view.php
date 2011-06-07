<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">
<html>

    <head>
        <?php
        // Add the includes from js-css-includes.
        echo(add_includes());
        ?>

    </head>

    <body>
        <!-- this was pulled from Tableless forms -->
        <div id="container">
            <div id="rightside">
                <fieldset>

                    <legend>Enter site</legend>

                    <font color="purple" >
                        <div id="li_error" class ="error_message">
                            <!-- Errors will be displayed here -->
                        </div>
                    </font>

                    <form id="log_in" class="form">

                        <label for="li_email">E-mail</label>
                        <div class="div_texbox">
                            <input name="li_email" type="text" class="textbox" id="li_email" />
                        </div>

                        <label for="li_password">Password</label>
                        <div class="div_texbox">
                            <input name="li_password" type="password" class="textbox" id="password" />
                        </div>

                        <div class="button_div">
                            <input type="submit" class="buttons" value="Log In" /><br/>
                            <div style="position:relative; top:4px; font-family:Arial, Helvetica, sans-serif;"><center><input type="checkbox" name="li_remember" value="1" />&nbsp;Stay logged in</center></div>
                        </div>



                    </form>
                </fieldset>

                <div><center><img src="/application/assets/images/Planjar logo.png" style="position:relative; top:50px;"  alt="PlanJar Logo"></center></div>

            </div>
            <div id="leftSide">
                <fieldset>
                    <legend>Not a member?  Sign up.   It's easy and free.</legend>

                    <font color="purple" >
                        <div id="su_error" class="error_message">
                            <!-- Errors will be displayed here -->
                        </div>
                    </font>

                    <form id="sign_up" action="/login/try_sign_up" method="get">
                        <label for="su_email_1">E-mail</label>
                        <div class="div_texbox">
                            <input id="su_email_1" name="su_email_1" type="text" class="textbox">
                        </div>

                        <label for="su_email_2">Re-enter E-mail</label>
                        <div class="div_texbox">
                            <input name="su_email_2" type="text" class="textbox">
                        </div>

                        <label for="su_password">Password</label>
                        <div class="div_texbox" id="foo">
                            <input name="su_password" type="password" class="textbox">
                        </div>

                        <label for="su_first_name">First Name</label>
                        <div class="div_texbox">
                            <input name="su_first_name" type="text" class="textbox">
                        </div>

                        <label for="su_last_name">Last Name</label>
                        <div class="div_texbox">
                            <input name="su_last_name" type="text" class="textbox">
                        </div>

                        <label for="su_school">School</label>
                        <div class="div_texbox">
                            <input id="su_school" name="su_school" type="text" class="textbox" onblur="force_school()">
                        </div>
                        
                        <!-- Hidden input used to pass the school id to the server instead of the school name -->
                        <input type="hidden" id="su_school_id" name="su_school_id"/>
                        
                        <label for="su_sex">Sex</label>
                        <div class="div_texbox" style="">
                            <select class="buttons_black" name="su_sex">
                                <option value=""selected="selected"></option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <label for="su_birthday">Birthday</label>
                        <div id="su_birthday" class="div_texbox">
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

                        <label for="su_grad_year">Class of</label>
                        <div class="div_texbox">
                            <input name="su_grad_year" type="text" class="textbox">
                        </div>

                        <div class="button_div">
                            <center><input class="buttons" type="submit" value="Sign up"></center>
                        </div>

                    </form>



                </fieldset>
            </div>


            <div class="clear"></div>
        </div>
    </body>
</html>
