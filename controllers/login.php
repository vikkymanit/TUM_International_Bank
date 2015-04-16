<?php
include 'DBconnections.php';//include 'cookieutils.php';
include 'sessionutils.php';
include 'validations.php'; 
$v=new validations();
$db = new DBconnections ();
$username = $_POST ["username"];
$pass = $_POST ["password"];
$select = $_POST ["typeselect"];
$hashpass = hash ( "md5", $pass );
//validate entered username
if($v->usernameMatch($username)!=1)
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
if($v->passwordMatch($pass)!=1)
{
	echo "<html>
    <script>
	    alert(\"Password must contain atleast 1 uppercase,1 lowercase and 1 number! Length between 8 and 20\");
		window.history.back();
	</script>
</html>";
die();
}
//validate role
if(strcmp("user",$select)!=0 && strcmp("employee",$select)!=0 && strcmp("admin",$select)!=0 )
{
	echo "<html>
    <script>
	    alert(\"You should select either a customer, an employee or an admin\");
		window.history.back();
	</script>
</html>";
die();
}
$result = $db->login( $username, $hashpass, $select );

if ($result == 'fail') {
	deleteSession(); 
	
	if(($db->isUserExist($username)) == 'exist'){ 
		if($db->getFailedAttempts($username) >= 5)
		{
			echo "<html>
						<script>
							alert(\"Your account has been bloacked, please contact the bank\");
							window.history.back();
						</script>
					</html>";
					die();
		}
		else
		{
			$db->setFailedAttempts($username);
				echo "<html>
				<script>
					alert(\"Login failed! Incorrect username or password.\");
					window.history.back();
				</script>
			</html>";
				die();
		}
		
	}
	echo "<html>
				<script>
					alert(\"Login failed! Incorrect username or password.\");
					window.history.back();
				</script>
			</html>";
				die();
} elseif ($result == 'roleerror') {
	deleteSession();
	echo "<html>
            <script>
	       alert(\"Login failed! Please check the role.\");
		      window.history.back();
	       </script>
        </html>";
	die();
} elseif ($result == 'userlogin') {
	if($db->getFailedAttempts($username) >= 5)
	{
		echo "<html>
				<script>
					alert(\"Your account has been bloacked, please contact the bank\");
					window.history.back();
				</script>
			</html>";
			die();
	}
	$_SESSION['lastActivity']=time();
	$_SESSION['role']='customer';
	$db->resetFailedAttempts($username);
	echo "  <html>
    <script>
    	window.top.location.href = '../controllers/user-landing.php';
	</script>
</html>	";
} elseif ($result == 'employeelogin') {
	if($db->getFailedAttempts($username) >= 5)
	{
		echo "<html>
				<script>
					alert(\"Your account has been bloacked, please contact the bank\");
					window.history.back();
				</script>
			</html>";
			die();
	}
	$_SESSION['role']='employee';
	$_SESSION['lastActivity']=time();
	$db->resetFailedAttempts($username);
	echo "  <html>  <script> window.top.location.href = '../controllers/employeelanding.php';
	               </script>
                   </html>	";
} else {
	if($db->getFailedAttempts($username) >= 5)
	{
		echo "<html>
				<script>
					alert(\"Your account has been bloacked, please contact the bank\");
					window.history.back();
				</script>
			</html>";
			die();
	}
	$_SESSION['role']='admin';
	$_SESSION['lastActivity']=time();
	echo "  <html>
    <script>
        window.top.location.href = '../controllers/adminlanding.php';
	    
	</script>
</html>	";
}
?>
