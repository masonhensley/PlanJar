<html>
    <head>
        <title>Almost there</title>
        <link rel=stylesheet href="/application/assets/css/login.css" type="text/css" />
    </head>

    <body>
        <div class="almost_there">
            <fieldset>
                <legend>Activate your account (<?php echo $identity; ?>)</legend>
                <p>Please click this link to
                    <?php echo anchor('auth/activate/' . $id . '/' . $activation, 'activate your account'); ?>.</p>
            </fieldset>
        </div>
    </body>
</html>