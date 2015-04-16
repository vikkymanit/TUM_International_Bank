<?php
require_once 'DbConnector.php';
include 'tan.php';
include 'sessionutils.php';
include 'validations.php';
$v=new validations();
if (! isSessionActive () || ! enforceRBAC ( 'employee' )) {
	header ( "Location: ../view/login.html" );
	die ();
}
$userid = $_SESSION ['uid'];

$db = new DbConnector ();
$values = array ();
$values1 = array ();
$valuesx = array ();
$t = time ();
$timestamp = date ( 'Y-m-d H:i:s', $t );

$sql = "Update users set is_active = 1   where user_id in (";
$sql1 = "Update users set activation_date = '$timestamp' where user_id in (";
$sqldelete = "Delete from users where user_id in (";
$counter=0; 
 
   

//print_r ( $_POST );
foreach ( $_POST as $key => $value ) {
	if ($_POST [$key] == "Accept") {
		$values [] = $key;
		// echo $key;
	} elseif ($_POST [$key] == "Decline") {
		$values1 [] = $key;
	}
}
$values = implode ( ',', $values );
$values .= ')';

$values1 = implode ( ',', $values1 );
$values1 .= ')';

$sql .= $values;
$sql1 .= $values;
$sqldelete .= $values1;



$db->execQuery ( $sql );
$db->execQuery ( $sql1 );
$db->execQuery ( $sqldelete );
// Create a user account with balance 0 and send mail to user.

foreach ( $_POST as $key => $value ) {

	$counter++;
	if ($_POST [$key] == "Accept") {
		$initamt= $_POST [$counter-1];
		if($v->amountMatch($initamt)!=1)
		{
			$initamt=0;
		}
		echo$initamt;
		$accno = mt_rand(0, 99999999);
		$createaccount = "Insert INTO accounts(user_id,balance,account_num) VALUES('$key',$initamt,'$accno');";
		$db->execQuery ( $createaccount );
		
		$emailid = "Select email from users where user_id = '$key';";
		$email = $db->execQuery ( $emailid );
		$tranauthresult = $db->execQuery ( "SELECT tranauth FROM users WHERE user_id = '$key';" );
		$tranauth = mysqli_fetch_array($tranauthresult);
		
		while ($em=mysqli_fetch_array($email)) {
			if ($tranauth[0] == 'email') {
				genTAN($key, $accno, $em[0], -1);
			} else {
				$clientPIN = mt_rand(100000, 999999);
				$result = $db->execQuery("UPDATE users SET clientPIN='$clientPIN' WHERE user_id='$key'");
				genTAN($key, $accno, $em[0], $clientPIN);
			}
		}
	}
}

$db->closeConnection ();
header ( 'Location: employeelanding.php' );

?>
