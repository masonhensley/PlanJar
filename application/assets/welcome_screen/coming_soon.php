<?php $this->load->helper('url'); require_once(base_url . "/application/assets/welcome_screen/assets/includes/functions.php"); ?>
<?php include("assets/includes/settings.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo $cs_title; ?></title>

        <link rel="apple-touch-icon" href="<?php echo $cs_apple_icon; ?>"/>
        <link rel="shortcut icon" href="<?php echo $cs_favicon; ?>"/>

        <link rel="stylesheet" type="text/css" href="application/views/assets/css/style_<?php echo($cs_style); ?>.css"/>

        <script type="text/javascript" src="assets/js/jquery-1.6.1.min"</script>

        <script src="assets/js/cufon-yui.js" type="text/javascript"></script>
        <script src="assets/js/cufon-font.js" type="text/javascript"></script>
        <script type="text/javascript">
            Cufon.replace('h1, h2'); // Works without a selector engine
        </script>

        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-23115103-1']);
            _gaq.push(['_setDomainName', 'none']);
            _gaq.push(['_setAllowLinker', true]);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>

    </head>

    <body>
        <!-- WRAPPER -->
        <div id="wrapper">

            <!-- CONTAINER -->
            <div id="container">


                <!-- LEFT SIDE -->
                <div id="left_side">
                    <div style="text-align: center;">
                        <img src="assets/images/g3930.png" />
                    </div>
                </div>
                <!-- /LEFT SIDE -->

                <!-- RIGHT SIDE -->
                <div id="right_side">
                    <h2>Some Tweets</h2>
                    <!-- TWEETS -->
                    <div id="twitter_div">            
                        <h2 style="display: none;" >Twitter Updates</h2>
                        <ul id="twitter_update_list"><li>	</li></ul>
                    </div>
                    <!-- /TWEETS -->

                </div>
                <!-- /RIGHT SIDE -->

                <!-- SUBSCRIBE FORM -->
                <div id="subscribe">
                    <form id="subscribe_form" method="post" action="assets/includes/subscribe.php">
                        <input type="text" name="email" id="email" value="" />
                        <input type="submit" name="subscribe" id="subscribe_btn" value=""/>
                        <img src="assets/images/ajax-loader-<?php if ($cs_style == 'light')
    echo 'light'; else
    echo 'dark'; ?>.gif" class="loader" alt="loading" />
                    </form>
                </div>
                <!-- /SUBSCRIBE FORM -->

            </div>
            <!-- CONTAINER -->

            <!-- FOOTER -->
            <div id="footer">
                <p>&copy; <?php echo date('Y', time()); ?> - All Right Reserved. A Jarof inc. Product || Contact us at: contactus@planjar.com</p>

            </div>
            <!-- FOOTER -->

        </div>
        <!-- /WRAPPER -->

        <!-- TWITTER TWEETS SCRIPT  -->
        <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
        <script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $cs_twitter_account; ?>.json?callback=twitterCallback2&amp;count=<?php echo $cs_tweets; ?>"></script>
        <script type="text/javascript" src="assets/js/jquery.coming_soon.js"</script>
    </body>
</html>