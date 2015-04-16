<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include 'utils.php';
include 'db.php';
include 'sessionutils.php';
include 'validations.php';

if(!isSessionActive() || !enforceRBAC('customer')) {
	header("Location: ../view/login.html");
	die();
}

// Validate the entered code
$v = new validations();
$tan=$_POST["tan"];
if($v->tanMatch($tan)!=1) {
	mysql_close($con);
	$_SESSION['error']=3;
	header("Location: ../view/error.php");
	die();
}

// Check the transaction authentication code
$userid=$_SESSION['uid'];
$tanInSession=$_SESSION['tan'];
$clientPIN=$_SESSION['clientPIN'];
if (($_SESSION['tranauth']=='email') && ($tanInSession!=$tan)) {
	mysql_close($con);
	$_SESSION['error']=3;
	header("Location: ../view/error.php");
	die();
} elseif ($_SESSION['tranauth']=='scs') {
	$fileLoc = "/tmp/batchfile.txt";
	$result = shell_exec("java -jar ../securToken/securToken.jar $clientPIN $fileLoc $tan");
	if ($result=='false') {
		mysql_close($con);
		$_SESSION['error']=3;
		header("Location: ../view/error.php");
		die();
	}
} else {
	
}

// Call the C++ binary
$batchfile = $_SESSION['batchfile'];
$batchstring=shell_exec("../exec/parsing /tmp/$batchfile");
unlink("/tmp/$batchfile");
$jsonobj=json_decode($batchstring);
if (!$jsonobj) {
	mysql_close($con);
	$_SESSION['error']=6;
	header("Location: ../view/error.php");
	die();
}

// Process the response returned by the binary
$invalidaccounts=array();
if (checkBalance($userid, $jsonobj->sum)) {
	$src_account = getAccountNumber($userid);
	foreach ($jsonobj->transactions as $transaction) {
		$dst_userid = doesAccountExist($transaction->destacc);
		if (!dst_userid || $transaction->amount<=0 || $dst_userid==$userid) {
			array_push($invalidaccounts, $transaction->destacc);
		} else {
			submitTrans($src_account, $transaction->destacc, $transaction->amount, $userid, getUserId($transaction->destacc), "");
		}
	}
	if (count($invalidaccounts)>0) {
		mysql_close($con);
		$_SESSION['invalid_acc']=$invalidaccounts;
		header("Location: ../view/error_numbers.php");
		die();
	} else {
		mysql_close($con);
		$_SESSION['message']=1;
		header("Location: ../view/succes.php");
		die();
	}
} else {
	mysql_close($con);
	$_SESSION['error']=5;
	header("Location: ../view/error.php");
	die();
}
?>
