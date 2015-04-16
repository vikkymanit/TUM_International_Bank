<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
header("X-FRAME-OPTIONS: DENY");
include 'db.php';
include 'utils.php';
include 'sessionutils.php';

if(!isSessionActive() || !enforceRBAC('customer')) {
 	header("Location: ../view/login.html");
 	die();
}

$userid=$_SESSION['uid'];
//echo $userid;
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="../view/login.css">
<title>TUMonline Bank</title>
</head>

<body>
	<h4 align="center">Welcome user!</h4>
	<section id="landingPage">
		<form method="post" class="minimal"
			action="../controllers/deletecookie.php">
			<button type="submit" class="btn-minimal" style="float: right;">Logout</button>
		</form>
		<h3 align="center">Account status</h3>
		<?php 
			if(checkSCS($userid)==1)
						echo "<a href=\"TUMbankSCS.jar\">Download the SCS software</a>";
		?>
		<table align="center"
			style="width: 100%; border-spacing: 10px; padding: 20px; text-align: center">
			<tr>
				<th>Account</th>
				<th>Balance</th>
			</tr>
		<?php
		session_start ();
		$result = mysql_query ( "SELECT balance,account_num FROM accounts WHERE user_id=$userid" );
		$row = mysql_fetch_array ( $result );
		$account = $row [1];
		$_SESSION ['account'] = $account;
		if ($result) {
			echo "<tr><td>$row[1]</td><td>$row[0]</td></tr>";
		}
		?>
	</table>
	</section>
	<section id="landingPage">
		<h3 align="center">Transfer history</h3>
		<table align="center"
			style="width: 100%; border-spacing: 10px; padding: 20px; text-align: center">
			<tr>
				<th>Source User</th>
				<th>Source Account</th>
				<th>Destination User</th>
				<th>Destination Account</th>
				<th>Amount</th>
				<th>Date</th>
				<th>Description</th>
				<th>Status</th>
			</tr>
		<?php
		$result = mysql_query (getTransacQuery($userid));
		if ($result) {
			$i = 0;
			while ( ($row = mysql_fetch_array ( $result )) && ($i < 3) ) {
				echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td>$row[6]</td>";
				switch ($row [7]) {
					case 0 :
						echo "<td>Pending Approval</td>";
						break;
					case 1 :
						echo "<td>Approved</td>";
						break;
					case 2 :
						echo "<td>Rejected</td>";
						break;
				}
				echo "</tr>";
				$i ++;
			}
		}
		mysql_close ( $con )
		?>
	</table>
		<a href="fulltransactions.php">More</a>
	</section>
    
	<section id="landingPage">
		<h3>Single Transfer</h3>
		<form method="post" class="minimal"
			action="../controllers/dotransaction.php">
			<table cellpadding="0" cellspacing="0" border="0" width="90%">
				<tr>
					<td><label for="username"> Account No.:</br> <input type="text"
							name="account" class="landingText" id="account"
							required="required" maxlength="8"/>
					</label></td>
					<td><label for="password"> Amount:</br> <input type="text"
							name="amount" class="landingText" id="amount" required="required" maxlength="8"/>
					</label></td>
					<td><label for="username"> Description:</br> <input type="text"
							name="description" class="landingText" id="description" required="required" maxlength="30"/>
					</label></td>
				</tr>
			</table>
			<button type="submit" class="btn-minimal">Send</button>
		</form>
	</section>
	<section id="landingPage">
		<h3>Bulk Transfer</h3>
		<form method="post" class="minimal"
			action="../controllers/bulk_tan.php" enctype="multipart/form-data">
			<table cellpadding="0" cellspacing="0" border="0" width="90%">
				<tr>
					<td><label for="username"> File:</br> <input type="file"
							name="batchfile" class="landingText" id="batchfile" />
					</label></td>
					<td>
						<p>
							Please create a .txt file with your transactions with each line
							in the below format:</br> "Destination Account1","Amount1",</br>
							"Destination Account2","Amount2",</br>
						</p>
					</td>
				</tr>
			</table>
			<button type="submit" class="btn-minimal">Send</button>
		</form>
	</section>
</body>

</html>
