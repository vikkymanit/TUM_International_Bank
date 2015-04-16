<?php
include 'genuserid.php';
include 'DBconnections.php';
include 'validations.php'; 
$v=new validations();
// Grab User submitted information
$uid = $_POST ["username"];
$pass = $_POST ["password"];
$email = $_POST ["email"];
$fullname = $_POST ["fullname"];
$repasswd = $_POST ["repassword"];
$select = $_POST ["typeselect"];
$tranauth = $_POST["tranauth"];

$t = time ();
$timestamp = date ( 'Y-m-d H:i:s', $t );

$hashpass = hash ( 'md5', $pass );
//validate entered username
if($v->usernameMatch($uid)!=1)
{
	echo "<html>
    <script>
	    alert(\"Username must have only alphanumeric characters with length between 8 and 20!\");
		window.history.back();
	</script>
</html>";
die();
}
//validate entered password.
if($v->passwordMatch($pass)!=1 || $pass!=$repasswd)
{
	echo "<html>
    <script>
	    alert(\"Password must contain atleast 1 uppercase,1 lowercase and 1 number! Length between 8 and 20\");
		window.history.back();
	</script>
</html>";
die();
}
//validate fullname
if($v->fullnameMatch($fullname)!=1)
{
	echo "<html>
    <script>
	    alert(\"Illegal Characters in FullName Field!\");
		window.history.back();
	</script>
</html>";
die();
}
//valide email
if($v->emailMatch($email)!=1)
{
	echo "<html>
    <script>
	    alert(\"Please check your E-Mail!\");
		window.history.back();
	</script>
</html>";
die();
}
//validate role
//echo strcmp("user",$select);
if(strcmp("user",$select)!=0 && strcmp("employee",$select)!=0)
{
	echo "<html>
    <script>
	    alert(\"You should select either a customer or an employee\");
		window.history.back();
	</script>
</html>";
die();
}
//validate Transaction authentication
if(strcmp($tranauth,"scs")!=0 && strcmp($tranauth,"email")!=0)
{
	echo "<html>
    <script>
	    alert(\"You should select either via SCS or via TAN Numbers\");
		window.history.back();
	</script>
</html>";
die();
}

$db=new DBconnections();
$result=$db->isUserExist($uid);
if($result=='exist')
{
	echo "  <html>
    <script>
	    alert(\"Username Already exist! Please try another user name\");
		window.history.back();
	</script>
</html>	";
}
elseif($result=='notexist')
{
	$user_id=genUserid();
	if($db->insertUser( $uid, $fullname,$hashpass,$email,$select,$timestamp,$user_id,$tranauth)==1)
	{
		echo "  <html>
        <script>
            alert(\"Signup Successful! Please wait for activation mail.\");
            window.location.href = '../view/login.html';
        </script>
    </html>	";
	}
}

?>
