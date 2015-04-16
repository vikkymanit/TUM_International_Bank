<?php
require_once 'DbConnector.php';


function genUserid(){
	$db = new DbConnector;
	$flag = 1;
	while($flag){
		$userid = mt_rand(10000,1000000000);
		$result = $db->execQuery("SELECT user_id FROM users WHERE user_id = '$userid'");
		if(mysqli_num_rows($result) == 0){
			$flag = 0;
			break;
		}

	}
	$db->closeConnection();
	return $userid;
}
?>