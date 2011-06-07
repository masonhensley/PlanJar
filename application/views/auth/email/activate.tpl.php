<html>
    <head>
        <title>Almost there</title>
        <?php
        // Add the includes from js-css-includes.
        echo(add_includes());
        ?>
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