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

                    <legend>Enter site.</legend>
                    <form id="log_in" class="form">

                        <label for="li_email">E-mail</label>
                        <div class="div_texbox">
                            <input name="li_email" type="text" class="username" id="li_email" />
                        </div>

                        <label for="li_password">Password</label>
                        <div class="div_texbox">
                            <input name="li_password" type="password" class="password" id="password" />
                        </div>

                        <div class="button_div">
                            <input type="submit" class="buttons" value="Log In" /><br/>
                            <center><input type="checkbox" name="li_remember" value="1" />&nbsp;Keep me logged in.</center>
                        </div>

                        <div class ="error_message">
                            <!-- Errors will be displayed here -->
                            <ul id="li_error_list"></ul>
                        </div>

                    </form>
                </fieldset>

                <div><center><img src="/application/assets/images/Planjar logo.png" style="position:relative; top:50px;"  alt="PlanJar Logo"></center></div>
                
            </div>
            <div id="leftSide">
                <fieldset>
                    <legend>Not a member?  Sign up.   It's easy and free.</legend>

                    <form id="sign_up" action="/login/try_sign_up" method="get">
                        <label for="su_email_1">E-mail</label>
                        <div class="div_texbox">
                            <input name="su_email_1" type="text" class="textbox" value="you@domain.com" onfocus="if(this.value=='you@domain.com'){this.value='';}">
                        </div>

                        <label for="su_email_2">Re-enter E-mail</label>
                        <div class="div_texbox">
                            <input name="su_email_2" type="text" class="textbox" value="you@domain.com" onfocus="if(this.value=='you@domain.com'){this.value='';}">
                        </div>

                        <label for="su_password">Password</label>
                        <div class="div_texbox">
                            <input name="su_password" type="password" class="textbox" >
                        </div>

                        <label for="su_first_name">First Name</label>
                        <div class="div_texbox">
                            <input name="su_first_name" type="text" class="textbox" value="First name" onfocus="if(this.value=='First name'){this.value='';}">
                        </div>

                        <label for="su_last_name">Last Name</label>
                        <div class="div_texbox">
                            <input name="su_last_name" type="text" class="textbox" value="Last name" onfocus="if(this.value=='Last name'){this.value='';}">
                        </div>

                        <label for="su_school">School</label>
                        <div class="div_texbox">
                            <input name="su_school" type="text" class="textbox" value="Your school" onfocus="if(this.value=='Your school'){this.value='';}">
                        </div>

                        <label for="su_sex">Sex</label>
                        <div class="div_texbox" style="">
                            <select class="buttons_black" name="su_sex">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <label for="su_birthday">Birthday</label>
                        <div class="div_texbox">
                            <input name="su_birthday" type="text" class="textbox" value="mm/dd/yyyy" onfocus="if(this.value=='mm/dd/yyyy'){this.value='';}">
                        </div>

                        <label for="su_grad_year">Class of</label>
                        <div class="div_texbox">
                            <input name="su_grad_year" type="text" class="textbox" value="<?php echo date('Y') + 4; ?>" onfocus="if(this.value==<?php echo date('Y') + 4; ?>){this.value='';}">
                        </div>

                        <div class="button_div">
                            <center><input class="buttons" type="submit" value="Sign up"></center>
                        </div>

                        <div class="error_messages">
                            <!-- Errors will be displayed here -->
                            <ul id="su_error_list"></ul>
                        </div>

                    </form>
                </fieldset>
            </div>

            <div class="clear"></div>
        </div>
    </body>
</html>
