<?php
require_once 'DbConnector.php';
include 'sessionutils.php';
header("X-FRAME-OPTIONS: DENY");
if(!isSessionActive() || !enforceRBAC('admin')) {
	header("Location: ../view/login.html");
	die();
}
$userid=$_SESSION['uid'];
$db = new DbConnector;

$query="SELECT * FROM users WHERE user_id =$userid AND role = 'admin';";
$result = $db->execQuery($query);
if(mysqli_num_rows($result) != 1)
        header("Location: ../view/login.html");    


$valuesaccept = array();
$valuesreject = array();
$valuesunblock = array();
$t = time();
$timestamp = date('Y-m-d H:i:s', $t);

$sqlaccept = "Update users set is_active = 1 where user_id in (";
$sql1 = "Update users set activation_date = '$timestamp' where user_id in (";
$sqlunblock = "Update users set failed_attempt_count = 0 where user_id in (";

$sqlreject = "Delete from users where user_id in (";
//print_r($_POST);
foreach ($_POST as $key => $value){
    if ($_POST[$key] == "Accept")
        $valuesaccept[] = $key;
    elseif ($_POST[$key] == "Decline")
        $valuesreject[] = $key;
    elseif ($_POST[$key] == "Unblock")
        $valuesunblock[] = $key;
}

$valuesaccept = implode(',',$valuesaccept);
$valuesaccept .= ')';

$valuesreject = implode(',',$valuesreject);
$valuesreject .= ')';

$valuesunblock = implode(',',$valuesunblock);
$valuesunblock .= ')';

$sqlaccept .= $valuesaccept;
$sqlreject .= $valuesreject;
$sqlunblock .= $valuesunblock; 
$sql1 .= $valuesaccept;
//print_r($sql);
$db->execQuery($sqlaccept);
$db->execQuery($sqlreject);
$db->execQuery($sql1);
$db->execQuery($sqlunblock);


// mysqli_close($Conn);
//header('Location: /ScTeam11/employeelanding.php');

?>
	<html>
		<head>
			<link rel="stylesheet" type="text/css" href="employeelanging.css">
			<title>TUMonline Bank</title>
		</head>
		<body>
			<h4 align="center">Welcome Admin!</h4>
				<section id="landingPage">
					<form method="post" class="minimal" action="../controllers/deletecookie.php">
						<button type="submit" class="btn-minimal" style="float: right;">Logout</button>
					</form>
                Hello, today is <?php echo date('l, F jS, Y'); ?>.
				<table>
					<caption>Pending Employee Approvals</caption>
					<tr>
						<th>Employee ID</th><th>Name</th><th>Registration Date</th><th>Email</th><th>Accept or Decline</th>
					</tr>

<?php
//Establish the database connection

$result = $db -> execQuery("SELECT user_id,username,fullname,registration_date,email FROM users WHERE is_active = 0 and role = \"employee\"");
$numrows = mysqli_num_rows($result);
//echo $numrows;
if($numrows==0)
{
    echo "</table>";
    echo "<h4> No pending requests </h4>";
}
else
{
    ?>
        <form method="post" class="minimal" action="adminlanding.php">
            <?php
                for($i=0; $i<$numrows; ++$i) {
                     $transactions = mysqli_fetch_assoc($result);
                    ?>
                        <tr>
                            <td><?php echo $transactions['username']; ?></td>
                            <td><?php echo $transactions['fullname']; ?></td>
                            <td><?php echo $transactions['registration_date']; ?></td>
                            <td><?php echo $transactions['email']; ?></td>
                            <td><input type="radio" name=<?php echo $transactions['user_id']; ?>  value="Accept" id="accept" >Accept</input>
                            <input type="radio" name=<?php echo $transactions['user_id']; ?>  value="Decline" id= "decline" >Decline</input></td>
                        </tr>
                    <?php

                }
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="submit" class="btn-minimal" value="Done">
                </td>
            </tr>
               
    <?php
}
?>
 </table>
        </form>
        <br>    <br>    <br> 
        </section>
        

<!-- Blocked Account Approval  -->


<section id="landingPage">

            <table>
                <caption>Blocked Accounts Activation</caption>
            <tr>
                <th>User ID</th><th>Name</th><th>Registration Date</th><th>Email</th><th>Unblock</th>
            </tr>

<?php
//Establish the database connection

$result = $db -> execQuery("SELECT user_id,username,fullname,registration_date,email FROM users WHERE failed_attempt_count >= 5");
$numrows = mysqli_num_rows($result);
//echo $numrows;
if($numrows==0)
{
    echo "</table>";
    echo "<h4> No blocked accounts </h4>";
}
else
{
    ?>
        <form method="post" class="minimal" action="adminlanding.php">
            <?php
                for($i=0; $i<$numrows; ++$i) {
                     $transactions = mysqli_fetch_assoc($result);
                    ?>
                        <tr>
                            <td><?php echo $transactions['username']; ?></td>
                            <td><?php echo $transactions['fullname']; ?></td>
                            <td><?php echo $transactions['registration_date']; ?></td>
                            <td><?php echo $transactions['email']; ?></td>
                            
                            <td><input type="radio" name=<?php echo $transactions['user_id']; ?>  value="Unblock" id="unblock" >Unblock</input></td>
                        </tr>
                    <?php

                }
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="submit" class="btn-minimal" value="Done">
                </td>
            </tr>
           
    <?php
}
?>
     </table>
        </form>
        </section>
<?php
$db -> closeConnection();
?>

</body>
</html>
