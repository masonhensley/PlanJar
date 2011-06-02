<?php require_once("connection/connection.php"); ?>
<?php require_once("functions.php"); ?>
<?php require_once("settings.php"); ?>
<?php

if(!$_POST) exit;
    
    $error = ''; //errors variable
    
    ///////////////////////////////////////////////////
   	// DATABASE DATA
    
    $email = $_POST['email']; 
    $join_date = time();
    
    ///////////////////////////////////////////////////
    
    ///////////////////////////////////////////////////
    // VALIDATION
    
    if(trim($email) == '') {
    	$error .= $cs_validation_01;
    } elseif(!isEmail($email)) {
    	$error .= $cs_validation_02;
    }
    
    ///////////////////////////////////////////////////
    
    // If there are errors
    if($error != '') { 
    	echo $error;
	
	} else {
		 // Check if email already exists
		 		
    	if(check_email_already_exist($email)) {
    		
    		//Perform query
    		$query = "INSERT INTO members (
	    	email, join_date
	    	) VALUES (
	    	    '{$email}', {$join_date}
	    	)";
	    	
	    	$result = mysql_query($query, $connection);
	    	    		
	    	if($result) {
		 		
		 		echo $cs_msg_01;
		 		
	    	} else {
	    	    //Errors!
	    	    echo $cs_msg_02;

	    	}
  
    	} else {
    	
    		$query = "DELETE FROM members WHERE email = '{$email}' LIMIT 1";
		
			$result = mysql_query($query, $connection);
			confirm_query($result);
			if(mysql_affected_rows() == 1) {
	    		// Success
	    		echo $cs_msg_03;
			} else {
	    		// Failed
	   			echo $cs_msg_04;
			}
    	
    	}
	}
?>
<?php
//Close database connection
if(isset($connection)){
	mysql_close($connection);
}
?>