<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">
<html>

    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/jquery.validity-1.1.1/jquery.validity.js"></script>
        <script type="text/javascript" src="/application/assets/js/sign_up_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/birthday-picker-1.2.min.js"></script>
        <link rel=stylesheet href="/application/assets/css/login.css" type="text/css">
        <link rel=stylesheet href="/application/assets/jquery.validity-1.1.1/jquery.validity.css" type="text/css">
        
        




        <!-- AJAX object is created here -->

        <script type="text/javascript">
            function loadXMLDoc()
            {
                var xmlhttp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                        document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET","ajax_info.txt",true);
                xmlhttp.send();
            }
        </script>




    </head>

    <body>


        <!-- this was pulled from Tableless forms -->
        <div id="container">
            <div id="top">
                <h1>Sign in to PlanJar</h1>
            </div>
            <div id="leftSide">
                <fieldset>
                    <legend>Login details</legend>
                    <form action="login.php" method="POST" class="form">
                        <label for="username">Username</label>
                        <div class="div_texbox">
                            <input onfocus="if(this.value=='username'){this.value='';}" name="username" type="text" class="username" id="email" value="username" />
                        </div>
                        <label for="password">Password</label>
                        <div class="div_texbox">
                            <input name="password" type="password" class="password" id="password" value="password" />
                        </div>
                        <div class="button_div">
                            <input name="Submit" type="submit" class="buttons" value="Log In" />
                        </div>
                    </form>
                </fieldset>
                <br /><br /><div id="sign_up_errors"><ul id="error_list"></ul></div>
                <fieldset>
                    <legend>Not a member?  Sign up.   It's easy and free.</legend>
                    <form id="sign_up">
                        <label for="su_email_1">E-mail</label>
                        <div class="div_texbox">
                            <input id="su_email_1" name="su_email_1" type="text" class="textbox" value="you@domain.com" onfocus="if(this.value=='you@domain.com'){this.value='';}">
                        </div>

                        <label for="su_email_2">Re-enter E-mail</label>
                        <div class="div_texbox">
                            <input id="su_email_2" name="su_email_2" type="text" class="textbox" value="you@domain.com" onfocus="if(this.value=='you@domain.com'){this.value='';}">
                        </div>

                        <label for="su_password">Password</label>
                        <div class="div_texbox">
                            <input id="su_password" name="su_password" type="password" class="textbox" >
                        </div>

                        <label for="su_first_name">First Name</label>
                        <div class="div_texbox">
                            <input id="su_first_name" name="su_first_name" type="text" class="textbox" value="First name" onfocus="if(this.value=='First name'){this.value='';}">
                        </div>

                        <label for="su_last_name">Last Name</label>
                        <div class="div_texbox">
                            <input id="su_last_name" name="su_last_name" type="text" class="textbox" value="Last name" onfocus="if(this.value=='Last name'){this.value='';}">
                        </div>

                        <label for="su_school">School</label>
                        <div class="div_texbox">
                            <input id="su_school" name="su_school" type="text" class="textbox" value="Your school" onfocus="if(this.value=='Your school'){this.value='';}">
                        </div>

                        <label for="su_sex">Sex</label>
                        <div class="div_texbox" style="">
                            <select id="su_sex" name="su_sex">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <label for="su_birthday">Birthday</label>
                        <div class="div_texbox">
                            <input id="su_birthday" name="su_birthday" type="text" class="textbox" value="mm/dd/yyyy" onfocus="if(this.value=='mm/dd/yyyy'){this.value='';}">
                        </div>

                        <label for="su_grad_year">Class of</label>
                        <div class="div_texbox">
                            <input id="su_grad_year" name="su_grad_year" type="text" class="textbox" value="<?php echo date('Y') + 4; ?>" onfocus="if(this.value==<?php echo date('Y') + 4; ?>){this.value='';}">
                        </div>

                        <div class="button_div">
                            <input class="buttons" type="submit" value="Sign up">
                        </div>
                    </form>
                </fieldset>
            </div>
            <div class="clear"></div>
        </div>

    </body>
</html>
