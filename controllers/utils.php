<?php
include 'db.php';

function doesAccountExist($account) {
	$result = mysql_query("SELECT user_id from accounts where account_num='$account'");
	$row = mysql_fetch_array($result);
	if ($row) {
		return $row[0];
	} else {
		return false;
	}
}

function checkBalance($userid, $amount) {
	$result = mysql_query("SELECT * from accounts where user_id='$userid'");
	$row = mysql_fetch_array($result);
	if ($row[0]<$amount) {
		return false;
	} else {
		return true;
	}
}

function submitTrans($src_account, $dst_account, $amount, $userid, $dst_userid, $description) {
	if ($amount>10000) {
		mysql_query("INSERT INTO transactions (user_id, source_account, dst_userid, destination_account, amount, description, is_approved) values ('$userid', '$src_account', '$dst_userid', '$dst_account', '$amount', '$description', 0)");
	} else {
		mysql_query("INSERT INTO transactions (user_id, source_account, dst_userid, destination_account, amount, description, is_approved) values ('$userid', '$src_account', '$dst_userid', '$dst_account', '$amount', '$description', 1)");
		mysql_query("UPDATE accounts SET balance=balance-$amount where account_num=$src_account");
		mysql_query("UPDATE accounts SET balance=balance+$amount where account_num=$dst_account");
	}
}

// Get the transaction authentication mode.
function getTransAuthMode($userid) {
	$query = "SELECT tranauth,clientPIN FROM users WHERE user_id='$userid'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row;
}

function getUserId($accountnum) {
	$result = mysql_query("SELECT user_id from accounts where account_num=$accountnum");
	$row = mysql_fetch_array($result);
	return $row[0];
}

function getAccountNumber($userid) {
	$result = mysql_query("SELECT account_num from accounts where user_id=$userid");
	$row = mysql_fetch_array($result);
	return $row[0];
}

function checkEmployee($userid) {
    $result = mysql_query("SELECT * FROM users WHERE user_id =$userid AND role = 'employee'");
	if(mysql_num_rows($result) != 1)
    header("Location: ../view/login.html");
}

function getTransacQuery($userid) {
	$TRANSAC_QUERY = " SELECT u1.username AS src_userid, transactions.source_account AS src_account, u2.username AS dst_userid, transactions.destination_account AS dst_account, transactions.amount, transactions.creation_date, transactions.description, transactions.is_approved FROM users AS u1, transactions, users AS u2 WHERE transactions.user_id = u1.user_id AND transactions.dst_userid = u2.user_id AND (transactions.user_id = @@@ OR transactions.dst_userid = @@@) ORDER BY creation_date DESC";
	$query = str_replace("@@@", $userid, $TRANSAC_QUERY); 
	return str_replace("@@@", $userid, $TRANSAC_QUERY);
}

function checkSCS($userid) {
    $result = mysql_query("SELECT * FROM users WHERE user_id =$userid AND tranauth = 'scs'");
	if(mysql_num_rows($result) == 1)
		return 1;
	else
		return 0;
			
}
?>
