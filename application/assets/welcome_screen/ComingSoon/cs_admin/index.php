<?php require_once("assets/includes/connection/session.php"); ?>
<?php require_once("assets/includes/connection/connection.php"); ?>
<?php require_once("assets/includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("assets/includes/cs_msgs.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Coming Soon Admin Panel</title>

<link rel="apple-touch-icon" href=""/>
<link rel="shortcut icon" href=""/>

<link rel="stylesheet" type="text/css" href="assets/css/style.css"/>

<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script> -->
<script type="text/javascript" src="assets/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.coming_soon.js"></script>

<script src="assets/js/cufon-yui.js" type="text/javascript"></script>
<script src="assets/js/cufon-font.js" type="text/javascript"></script>
<script type="text/javascript">
    Cufon.replace('h1, h2'); // Works without a selector engine
</script>

</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		
		<!-- HEADER -->
		<div id="header">
			<h1>Coming Soon Admin Panel</h1>
			
			<div id="info">
				<p>Wellcome, <span><?php echo $_SESSION['username'];?></span></p>
				<ul>
					<li><a href="cs_logout.php">Logout</a></li>
				</ul>
				
			</div>
		
		</div>
		<!-- /HEADER -->
		
		<!-- CONTAINER -->
		<div id="container">
			<?php
				switch($_GET['page']){
				case "members":
				    include "assets/includes/members.php";
				break;
				default:
				    include "assets/includes/members.php";
				break;
				}
			?>		
		</div>		
		<!-- /CONTAINER -->
		
	</div>
	<!-- /WRAPPER -->
</body>
</html>
<?php
//Close database connection
if(isset($connection)){
	mysql_close($connection);
}
?>