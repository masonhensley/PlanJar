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
                <p>
                    <b><?php echo($inviter); ?></b> has invited you to join PlanJar!
                </p>
                <p>
                    What is PlanJar? In a nutshell, PlanJar lets you follow your favorite people and groups,
                    and see graphical trends of where they are going to help plan your social calendar.
                </p>
                <p>
                    Want to learn more? Click <?php echo(anchor('login', 'this link')); ?> to sign up!
                </p>
            </div>

            <hr/>
            <div class="bottom_links">
                Feel free to contact the CEO directly at <a href="mailto:feedback@planjar.com">feedback@planjar.com</a>.
            </div>
        </div>
    </body>
</html>