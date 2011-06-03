<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">
<html>

    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-validate-1.5.5/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/formly.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/sign_up_functions.js"></script>
        <script type="text/javascript" src="/application/assets/js/birthday-picker-1.2.min.js"></script>
        <link rel=StyleSheet href="/application/assets/css/login.css" type="text/css" >




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
                <h1>Please complete the form below</h1>
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
                        <div class="buuton_div">
                            <input name="Submit" type="button" value="Log In" class="buttons" />
                        </div>
                    </form>
                </fieldset>
                <br /><hr size="1"><br />
                <fieldset>
                    <legend>Not a member? Sign up.  It's easy and free.</legend>
                    <form id="sign_up">
                        <label for="email_1">E-mail</label>
                        <div class="div_texbox">
                            <input id="su_email_1" name="su_email_1" type="text" class="textbox" value="you@domain.com" onfocus="if(this.value=='you@domain.com'){this.value='';}">
                        </div>

                        <label for="email_2">Re-enter E-mail</label>
                        <div class="div_texbox">
                            <input id="su_email_2" name="su_email_2" type="text" class="textbox" value="you@domain.com" onfocus="if(this.value=='you@domain.com'){this.value='';}">
                        </div>

                        <label for="password">Password</label>
                        <div class="div_texbox">
                            <input id="su_password" name="password" type="password" class="textbox" value="password" onfocus="if(this.value=='password'){this.value='';}">
                        </div>

                        <label for="first_name">First Name</label>
                        <div class="div_texbox">
                            <input id="su_first_name" name="first_name" type="text" class="textbox" value="First name" onfocus="if(this.value=='First name'){this.value='';}">
                        </div>

                        <label for="last_name">Last Name</label>
                        <div class="div_texbox">
                            <input id="su_last_name" name="last_name" type="text" class="textbox" value="Last name" onfocus="if(this.value=='Last name'){this.value='';}">
                        </div>

                        <label for="school">School</label>
                        <div class="div_texbox">
                            <input id="su_school" name="school" type="text" class="textbox" value="School" onfocus="if(this.value=='Your school'){this.value='';}">
                        </div>

                        <label for="sex">Sex</label>
                        <div class="div_texbox">
                            <select id="su_sex" name="sex">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <label for="birthday">Birthday</label>
                        <div class="div_texbox">
                            <input id="su_birthday" name="birthday" type="text" class="textbox" value="03/30/1990" onfocus="if(this.value=='03/30/1990'){this.value='';}">
                        </div>
                        
                        <label for="grad_year">Class of</label>
                        <div class="div_texbox">
                            <input id="su_grad_year" name="grad_year" type="text" class="textbox" value="javascript: function(){return getYear()+4;}" onfocus="if(this.value==getYear()+4){this.value='';}">
                        </div>

                        <div class="button_div">
                            <input name="sign_up" type="button" value="Sign up" class="buttons">
                        </div>
                    </form>
                </fieldset>
            </div>
            <div id="rightSide">
                <p><u>This is the right side div that can be used for showing info's in order to help the visitor.</u> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                </p></div>
            <div class="clear"></div>
        </div>

    </body>
</html>
