<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">
<html>

    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-validate-1.5.5/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/formly.min.js"></script>
        <link rel="stylesheet" href="/application/assets/css/formly.min.css" type="text/css" />    

        <style type="text/css">
            * { font-family: Verdana; font-size: 96%; }
            label { width: 10em; float: left; }
            label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
            p { clear: both; }
            .submit { margin-left: 12em; }
            em { font-weight: bold; padding-right: 1em; vertical-align: top; }
        </style>

        <script>
            
            // Formly javascript
            $(document).ready(function()
            { 
                $('#ContactInfo').formly({'theme':'Dark'}, function(e)
                { $('.callback').html(e); });
            });
       

            // Run when then DOM is loaded
            $(document).ready(function(){

                // Initialize the validate plugin.
                $("#commentForm").validate({
                    submitHandler: function(form) {
                        $(form).ajaxSubmit();
                    },

                    invalidHandler: function(form, validator) {
                        var errors = validator.numberOfInvalids();
                        if (errors) {
                            var message = errors == 1
                                ? 'You missed 1 field. It has been highlighted'
                            : 'You missed ' + errors + ' fields. They have been highlighted';
                            $("div.error span").html(message);
                            $("div.error").show();
                        } else {
                            $("div.error").hide();
                        }
                    }
                });
            });
        </script>


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

        <form id="ContactInfo" width="600px" title="Member sign up">
            <input type="text" name="first_name" place="Your first name" size="30" /> 
            <input type="text" name="last_name" place="Your last name" size="30" style="margin-left:10px;" />
            <input type="text" name="email" validate="email" place="Email address" size="30" />
            <input type="text" name="website" place="Your website" size="30" pre-fix="http://" validate="http" style="margin-left:10px;" />
            <select id="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <input type="radio" name="membership" value="new" style="margin-left:10px;" /> New member
            <input type="radio" name="membership" value="existing" /> Existing member 
            <input type="password" name="pword" require="true" label="Password" place="Password" />
            <input type="password" name="pwordm" match="pword" label="Password" place="Re-type password" />
            <input type="checkbox" name="agree" require="true" label="Terms" value="agree" /> I agree to the terms
            <input type="submit" value="Sign up" /><input type="reset" value="Clear" /> 
        </form>

    </body>
</html>
