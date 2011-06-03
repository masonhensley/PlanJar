<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">
<html>

    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-validate-1.5.5/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/formly.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/validate_functions.js"></script>
        <link rel=stylesheet href="/application/assets/css/login.css" type="text/css" >

        


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
                            <input name="username" type="text" class="username" id="username" value="username" />
                        </div>
                        <label for="password">Password</label>
                        <div class="div_texbox">
                            <input name="password" type="password" class="password" id="password" value="password" />
                        </div>
                        <div class="buuton_div">
                            <input name="Submit" type="button" value="Submit" class="buttons" />
                        </div>
                    </form>
                </fieldset>
                <br /><hr size="1"><br />
                <fieldset>
                    <legend>Personal details</legend>
                    <form action="pay.php" method="POST" class="form">
                        <label for="name">Name</label>
                        <div class="div_texbox">
                            <input name="name" type="text" class="textbox" id="name" value="John Doe" />
                        </div>
                        <label for="address">Address</label>
                        <div class="div_texbox">
                            <input name="address" type="text" class="textbox" id="address" value="12 main" />
                        </div>
                        <label for="city">City</label>
                        <div class="div_texbox">
                            <input name="city" type="text" class="textbox" id="city" value="Rochester" />
                        </div>
                        <label for="country">Country</label>
                        <div class="div_texbox">
                            <input name="country" type="text" class="textbox" id="country" value="United States" />
                        </div>
                        <div class="button_div">
                            <input name="Submit" type="button" value="Submit" class="buttons" />
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
