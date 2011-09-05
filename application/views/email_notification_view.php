<html>
    <head>
        <style type="text/css">
            .wrapper {
                width: 450px;
                height: auto;
                background-color: #ECECEC;
                font-family: helvetica;
                font-size: 14pt;
            }

            .wrapper img {
                padding: 15px;
            }

            .content {
                padding: 15px;
                margin-bottom: 50px;
            }

            .bottom_links {
                font-size: 6pt;
                padding: 15px;
            }

            #user_img_wrapper {
                float: right;
            }
        </style>
    </head>

    <body>
        <div class="wrapper">
            <a href="<?php echo(base_url()); ?>">
                <img src="<?php echo(base_url() . APPPATH . 'assets/images/logo_email_beta.png'); ?>"/>
                <?php if (isset($image))
                { ?>
                    <div style="float: right;"><?php echo($image); ?></div>
                <?php } ?>

            </a>
            <hr/>

            <div class="content">
                <?php
                echo($notif_text);
                if (!isset($skip_notif))
                {
                    ?>
                    <br/><br/>
                    Click <?php echo(anchor('dashboard/notifications', 'here')); ?> to respond.
                    <?php
                }
                ?>
                <br/><br/><br/><br/>
                This information in this email was up-to-date as of<br/>
                <?php echo(date('g:i a') . ' on ' . date('l, F jS Y')); ?>.
            </div>

            <hr/>
            <div class="bottom_links">
                <?php echo(anchor('', 'PlanJar | Home')); ?>
                <br/>--<br/>
                Want to change which email notifications you receive?
                Click <?php echo(anchor('dashboard/settings', 'here')); ?> to change your email settings.
                <br/>--<br/>
                <?php
                if (!isset($skip_unsub))
                {
                    ?>
                    Don't want to receive ANY emails from PlanJar?
                    Click <?php echo(anchor("home/unsub/$unsubscribe_id", 'here')); ?> to unsubscribe.
                    <br/>--<br/>
                    <?php
                }
                ?>
                Feel free to contact the CEO directly at <a href="mailto:feedback@planjar.com">feedback@planjar.com</a>.
            </div>
        </div>
    </body>
</html>