<?php
include 'db.php';
include 'utils.php';
include 'sessionutils.php';
include 'validations.php';

if(!isSessionActive() || !enforceRBAC('customer')) {
	header("Location: ../view/login.html");
	die();
}

// Validate the file size
$v = new validations();
if (!$v->checkFilesize($_FILES["batchfile"]["tmp_name"])) {
	mysql_close($con);
	$_SESSION['error']=9;
	header("Location: ../view/error.php");
	die();
}

// Pre-processing of file.
$userid=$_SESSION['uid'];
$row = getTransAuthMode($userid);
$filename = substr(sha1(rand()), 0, 7).".txt";
move_uploaded_file($_FILES["batchfile"]["tmp_name"], "/tmp/$filename");
$_SESSION['batchfile']=$filename;

// Check the tan authentication mode
if ($row[0] == 'email') {
	for($i=0; $i<100; $i++) {
		$tan_seq = rand (1, 100);
		$result = mysql_query ( "SELECT tan,expired from tan_numbers where seq_number=$tan_seq and user_id=$userid" );
		$row = mysql_fetch_array ( $result );
		if ($row [1] == 0) {
			$_SESSION['tan']=$row[0];
			$_SESSION['tranauth']='email';
			break;
		}
	}
} else {
	$_SESSION['tranauth']='scs';
	$_SESSION['clientPIN']=$row[1];
}
mysql_close($con);
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="../view/login.css">
</head>

<body>
	<h4 align="center">Welcome user!</h4>
	<section id="landingPage">
		<h3>TAN Entry</h3>
		<form method="post" class="minimal" action="fileupload.php">
			<table cellpadding="0" cellspacing="0" border="0" width="90%">
				<tr>
					<td><label for="username">
							<?php 
								if ($_SESSION['tranauth']=='email') {
									echo "Enter your tan corresponding to the number '$tan_seq':</br>";
								} else {
									echo "Enter the secure code generated by your SCS:</br>";
								}
							?>
							<input type="text" name="tan" class="landingText" id="tan"
							required="required" maxlength="15" />
					</label></td>
				</tr>
			</table>
			<button type="submit" class="btn-minimal">Send</button>
		</form>
	</section>
</body>
</html>
