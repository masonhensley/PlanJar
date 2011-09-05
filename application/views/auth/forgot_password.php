<html>
    <head>
        <title>PlanJar | Forgot Password</title>

        <link rel=stylesheet href="/application/assets/css/login.css" type="text/css" />
        <style type="text/css">
            .forgot_pass {
                width: 400px;
                padding-top:15%;
                font-family:Arial, Helvetica, sans-serif;
            }
        </style>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.3.min.js"></script>
        <script type="text/javascript">
            $(function() {
                $('form').submit(function() {
                    $.get('/auth/forgot_password', $('form').serialize(), function(data) {
                        if (data == 'success') {
                            alert('Check your email to reset your password. Redirecting...');
                            window.location.href = '/home';
                        } else {
                            alert(data);
                        }
                    });
                
                    return false; 
                });
            });
        </script>

        <script type="text/javascript" src="/application/assets/js/chartbeat_head.js"></script>
    </head>

    <body>
    <center>
        <div class="forgot_pass">
            <fieldset>
                <legend>Forgot password...</legend>
                <p>Please enter your email address so we can send you an email to reset your password.</p>
                <form>
                    <input type="email" name="email"/>
                    <input type="submit" value="Go"/>
                </form>
            </fieldset>
        </div>
    </center>

    <!-- Chartbeat -->
    <script type="text/javascript" src="/application/assets/js/chartbeat_body.js"></script>
</body>
</html>