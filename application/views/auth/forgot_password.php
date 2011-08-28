<html>
    <head>
        <title>PlanJar | Forgot Password</title>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript">
            $(function() {
                $('form').submit(function() {
                    $.get('/auth/forgot_password', $('form').serialize(), function(data) {
                        if (data == 'success') {
                            alert('Check your email to reset your password. Redirecting...');
                            window.location.href = '/login';
                        } else {
                            alert(data);
                        }
                    });
                
                    return false; 
                });
            });
        </script>
    </head>

    <body>
        <h1>Forgot Password</h1>
        <p>Please enter your email address so we can send you an email to reset your password.</p>

        <form>
            <input type="email" name="email"/>
            <input type="submit" value="Go"/>
        </form>
    </body>
</html>