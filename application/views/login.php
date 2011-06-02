<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    
    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-validate-1.5.5/jquery.validate.min.js"></script>
        <style type="text/css">
            * { font-family: Verdana; font-size: 96%; }
            label { width: 10em; float: left; }
            label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
            p { clear: both; }
            .submit { margin-left: 12em; }
            em { font-weight: bold; padding-right: 1em; vertical-align: top; }
        </style>
        <script>
            $(document).ready(function(){
                $("#commentForm").validate();
            });
        </script>

    </head>
    
    <body>


        <form class="cmxform" id="commentForm" method="get" action="">
            <fieldset>
                
                <p>
                    <label for="cname">Name</label>
                    <em>*</em><input id="cname" name="name" size="25" class="required" minlength="2" />
                </p>
                <p>
                    <label for="cemail">E-Mail</label>
                    <em>*</em><input id="cemail" name="email" size="25"  class="required email" />
                </p>
                <p>
                    <input class="submit" type="submit" value="Submit"/>
                </p>
            </fieldset>
        </form>
    </body>
</html>