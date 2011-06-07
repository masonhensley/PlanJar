<html>
    <body>
        <div style="width:75%">
            <fieldset style="border:1px solid #000000; padding:10px; background-color: white;">
                <legend style="font-family:Arial, Helvetica, sans-serif; font-size: 120%;
                        letter-spacing: -1px; font-weight: bold; line-height: 1.1; color:#fff;
                        background: #990099; border: 1px solid #333; padding: 2px 6px;">
                    Activate your account (<?php echo $identity; ?>)
                </legend>
                <p>Please click this link to
                    <font color="white">
                    <?php echo anchor('auth/activate/' . $id . '/' . $activation, 'activate your account'); ?>.</p>
                </font>
            </fieldset>
        </div>
    </body>
</html>