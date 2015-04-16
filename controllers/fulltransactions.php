<?php
include 'db.php';
include 'utils.php';
include 'sessionutils.php';
header("X-FRAME-OPTIONS: DENY");
$roles = array('customer', 'employee');
if(!isSessionActive() || !enforceRBACmulti($roles)) {
	header("Location: ../view/login.html");
	die();
}
$userid=$_SESSION['uid'];
$account = $_SESSION['account'];
$result = mysql_query(getTransacQuery($userid));
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="../view/fulltransactions.css">
</head>
   
<body>
<section id="loginBox">
	<h2 align="center">Transfer history</h2>
	
    
    <form method="post" class="minimal" action="../controllers/pdfgenerator.php">
        <input type="hidden" name="PDF" value="<?php echo $account; ?>"> 
        <button type="submit" class="btn-minimal" >Download PDF</button>
    </form>
	
    <table align="center">
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
			if($result) {
				while($row = mysql_fetch_array($result)) {
					echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td>$row[6]</td>";
					switch ($row[7]){
						case 0:
							echo "<td>Pending Approval</td>";
							break;
						case 1:
							echo "<td>Approved</td>";
							break;
						case 2:
							echo "<td>Rejected</td>";
							break;
					}
					echo "</tr>";
				}
			}
			mysql_close($con);
			echo "<a href=\"../controllers/user-landing.php\">Back</a>";
		?>
	</table>
</section>
</body>
</html>
