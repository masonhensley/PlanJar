<?php require_once("assets/includes/functions.php"); ?>
<?php include("assets/includes/settings.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $cs_title;?></title>

<link rel="apple-touch-icon" href="<?php echo $cs_apple_icon;?>"/>
<link rel="shortcut icon" href="<?php echo $cs_favicon;?>"/>

<link rel="stylesheet" type="text/css" href="assets/css/style_<?php echo $cs_style;?>.css"/>

<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script> -->
<script type="text/javascript" src="assets/js/jquery-1.4.4.min.js"></script>

<script src="assets/js/cufon-yui.js" type="text/javascript"></script>
<script src="assets/js/cufon-font.js" type="text/javascript"></script>
<script type="text/javascript">
    Cufon.replace('h1, h2'); // Works without a selector engine
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
						<img src="assets/images/ajax-loader-<?php if($cs_style == 'light') echo 'light'; else echo 'dark';?>.gif" class="loader" alt="loading" />
					</form>
				</div>
			<!-- /SUBSCRIBE FORM -->
			
		</div>
		<!-- CONTAINER -->
		
		<!-- FOOTER -->
		<div id="footer">
			<p>&copy; <?php echo date('Y',time());?> - All Right Reserved. || Contact us at: contact@pushplans.com</p>
		
		</div>
		<!-- FOOTER -->
		
	</div>
	<!-- /WRAPPER -->
	
<!-- TWITTER TWEETS SCRIPT  -->
<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $cs_twitter_account; ?>.json?callback=twitterCallback2&amp;count=<?php echo $cs_tweets;?>"></script>
<script type="text/javascript" src="assets/js/jquery.coming_soon.js"></script>
</body>
</html>