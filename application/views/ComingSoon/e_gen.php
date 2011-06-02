<?php
function genRandomStringPreAt() {
    $length = mt_rand(8,12);
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $real_lenght = strlen($characters) - 1 ;
    $string = "email_" ;    
    for ($p = 0; $p < $length-6; $p++) {
        $string .= $characters[mt_rand(0, $real_lenght)];
    }
    return $string;
}

function genRandomStringPostAt() {
    $length = mt_rand(8,12);
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $real_lenght = strlen($characters) - 1 ;
    $string = "site_" ;    
    for ($p = 0; $p < $length-5; $p++) {
        $string .= $characters[mt_rand(0, $real_lenght)];
    }
    return $string;
}

//echo genRandomStringPreAt()."@".genRandomStringPostAt().".com";
//echo strtotime("1990-03-08");


function genRandQueries($num) {
	for($i=0; $i < $num; $i++) {
		
		$first_name = genRandomStringPreAt();
		$second_name = genRandomStringPostAt();
		$email = $first_name."@".$second_name.".com";
		$status = mt_rand(0,2);
		$join_date = time();
		//$join_date = mt_rand(86400,99);
		
		$query = "INSERT INTO members (
	    email, join_date
	    ) VALUES (
	        '{$email}', {$join_date}
	    );";
	    
	    echo $query."<br/><br/>";
	}
}

echo genRandQueries(100);

?>