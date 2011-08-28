<html>
    <head>
        <title>PlanJar | Forgot Password</title>
    </head>

    <body>
        <h1>Forgot Password</h1>
        <p>Please enter your email address so we can send you an email to reset your password.</p>

        <form action="<?php echo(base_url() . 'auth/forgot_password'); ?>" method="post">
            <input type="email" name="email"/>
            <input type="submit" value="Go"/>
        </form>
    </body>
</html>