<?php
include 'db.php';	
include 'utils.php';
include 'sessionutils.php';
header("X-FRAME-OPTIONS: DENY");
if(!isSessionActive() || !enforceRBAC('employee')) {
	deleteSession();
 	header("Location: ../view/login.html");
 	die();
}
$userid=$_SESSION['uid'];
/*$result = mysql_query("SELECT * FROM users WHERE user_id =$userid AND role = 'employee'");
if(mysql_num_rows($result) != 1)
        header("Location: ../view/login.html"); */             
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="employeelanging.css">
    <title>TUMonline Bank</title>
</head>
<body>
<h2 align="center">Welcome Staff Member!</h2>
    
<section id="landingPage">
    <form method="post" class="minimal" action="../controllers/deletecookie.php">
        <button type="submit" class="btn-minimal" style="float: right;">Logout</button>
    </form>
Hello, today is <?php echo date('l, F jS, Y'); ?>.
<table align="center" style="width: 100%; border-spacing: 10px; padding: 20px; text-align: center">
<caption>Pending User Approvals</caption>
<tr>
<th>User ID</th><th>Name</th><th>Registration Date</th><th>Email</th><th>Link</th><th>InitialAmt</th>
</tr>
    
<?php

$result = mysql_query("SELECT username,fullname,registration_date,email,user_id FROM users WHERE is_active = 0 and role = 'user'");
$numrows = mysql_num_rows($result);
if($numrows==0)
{
    echo "</table>";
    echo "<h4> No pending requests </h4>";
}
else
{
    echo "<form method=\"post\" class=\"minimal\" action=\"empprocess.php\">";
            for($i=0; $i<$numrows; ++$i) {
                 $transactions = mysql_fetch_array($result);
                //echo $transactions[4];
                echo "<tr>
                <td>$transactions[0]</td>
                <td>$transactions[1]</td>
                <td>$transactions[2]</td>
                <td>$transactions[3]</td>
                <td>"; 
     echo "<input type=\"radio\" name=\"$transactions[4]\"  value=\"Accept\" id=\"accept\" >Accept</input>
                <input type=\"radio\" name=\"$transactions[4]\"  value=\"Decline\" id= \"decline\" >Decline</input></td>
           <td> <input type=\"text\" name=\"$i\" id=\"amt\" value=0></td>     
</tr> 
";
    }
    echo "<tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><input type=\"submit\" class=\"btn-minimal\"value=\"Submit\" > </td>
            </tr>"; 
}

?>  
   
 
</table>
    
        </form>
    
<br>    <br>    <br>    
</section>
    
<!-- Transaction Approval  -->

<section id="landingPage">    
    <table>
        <caption>Pending Transaction Approvals</caption>
        <tr>
            <th>Source Account</th><th>Destination Account</th><th>Amount</th><th>Date</th><th>Details</th>
        </tr>
        <?php
$result = mysql_query("SELECT source_account,destination_account,amount,creation_date,transaction_id FROM transactions WHERE is_approved = 0 and amount >= 10000");
$numrows = mysql_num_rows($result);
//echo $numrows;
            if($numrows==0)
            {
                echo "</table>";
                echo "<h4> No pending requests </h4>";
            }
            else
            {
                echo "<form method=\"post\" class=\"minimal\" action=\"tranprocess.php\">";
                        for($i=0; $i<$numrows; ++$i) {
                             $transactions = mysql_fetch_array($result);

                            echo "<tr>
                            <td>$transactions[0]</td>
                            <td>$transactions[1]</td>
                            <td>$transactions[2]</td>
                            <td>$transactions[3]</td>
                            <td>"; 
                 echo "<input type=\"radio\" name=\"$transactions[4]\"  value=\"Accept\" id=\"accept\" >Accept</input>
                            <input type=\"radio\" name=\"$transactions[4]\"  value=\"Decline\" id= \"decline\" >Decline</input></td>
            </tr> ";
                           // echo $transactions[4];
                }
                echo "<tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><input type=\"submit\" class=\"btn-minimal\" value=\"Submit\" > </td>
            </tr>"; 
            }

mysql_close($con);
?>    

                   
       
        </table> 
    </form>
    
    </section>

    <section id="landingPage">
		<h3>Search Customer</h3>
		<form method="post" class="minimal"
			action="../controllers/usersearch.php">
			<table cellpadding="0" cellspacing="0" border="0" width="90%">
				<tr>

					<td><label for="username"> Account Number:   <input type="text" name="accno" id="accno" class="landingText" required="required" style="width: 400px;" pattern="[0-9]*" placeholder="Enter only numbers"/>

					</label></td>
					<td class="talign"><button type="submit" class="btn-minimal">Search</button></td>
				</tr>
			</table>	
		</form>
	</section>
    
    </body>
</html>
