<html>
<head>
<link rel="stylesheet" type="text/css" href="employeelanging.css">
</head>
<body>
<h4 align="center">Welcome Staff Member!</h4>
<section id="landingPage">
Hello, today is <?php echo date('l, F jS, Y'); ?>.
<table>
<caption>Pending User Approvals</caption>
<tr>
<th>User ID</th><th>Name</th><th>Registration Date</th><th>Email</th><th>Link</th>
</tr>
    
<?php
include 'db.php';
$result = mysql_query("SELECT username,fullname,registration_date,email,user_id FROM users WHERE is_active = 0 and role = 'user'");
$numrows = mysql_num_rows($result);
//echo $numrows;
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
</tr> ";
    }
}

?>  
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><input type="submit" value="Done" > </td>
    </tr>
 
</table>
    
        </form>
    
<br>    <br>    <br>    <br>    
<a href="">More</a>
</section>
    
<!-- Transaction Approval  -->

<section>    
    <table>
        <caption>Pending Transaction Approvals</caption>
        <tr>
            <th>Source Account</th><th>Destination Account</th><th>Amount</th><th>Date</th><th>Details</th>
        </tr>
        <?php
$result = mysql_query("SELECT source_account,destination_account,amount,creation_date FROM transactions WHERE is_approved = 0 and amount >= 10000");
$numrows = mysql_num_rows($result);
echo $numrows;
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
                echo $transactions[4];
                echo "<tr>
                <td>$transactions[0]</td>
                <td>$transactions[1]</td>
                <td>$transactions[2]</td>
                <td>$transactions[3]</td>
                <td>"; 
     echo "<input type=\"radio\" name=\"$transactions[4]\"  value=\"Accept\" id=\"accept\" >Accept</input>
                <input type=\"radio\" name=\"$transactions[4]\"  value=\"Decline\" id= \"decline\" >Decline</input></td>
</tr> ";
    }
}

mysql_close($con);
?>    

       
    </table> 
    
    </section>
    
    </body>
</html>
