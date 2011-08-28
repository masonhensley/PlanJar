<html>
    <body>
        <h1>New Password for <?php echo $identity; ?></h1>

        <p>Your password has been reset to <b><?php echo $new_password; ?></b></p>

        <p>Click <?php echo(anchor('/dashboard/settings', 'here')); ?> to change your password.
    </body>
</html>