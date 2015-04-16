<?php
require_once('DbConnector.php');
require_once('../lib/PHPMailer/PHPMailerAutoload.php');
include 'validations.php';
$v=new validations();
if(isset($_POST['submit'])) 
{       //echo "1";   
		$username = $_POST['username']; 
		$email = $_POST['email'];
		if($v->usernameMatch($username)!=1)
		{
			echo "<html>
					<script>
						alert(\"Please check your username.\");
						window.history.back();
					</script>
			    </html>";
				die();
		}
		if($v->emailMatch($email)!=1)
		{
			echo "<html>
					<script>
						alert(\"Please check your email.\");
						window.history.back();
					</script>
			    </html>";
				die();
		}
		$db = new DbConnector;
		$result = $db->getUser($username);
		if($result['user_id'] != 0)
		{	
				
				//print_r($row);
				$db_email = $result['email']; 
				$userid = $result['user_id']; 
				if($email == $db_email)
				{	
					//$code = md5(90*13+$Results['userid']);
					$code = md5(rand(99, 999999)+$Results['userid']);
					$mail = new PHPMailer();
					$mail->SetFrom('securebankingcode@gmail.com', 'TUM International Bank');
					$address = $email;
					$mail->AddAddress($address);
					$mail->Subject = "Forget Password";
					$body = "<p><br>Click here to reset your password https://localhost/foobank/controllers/reset.php?c=$code </p>";
					$mail->MsgHTML($body);
					$mail->Send();
/*					if(!$mail->Send()) {
						   echo "Mailer Error: " . $mail->ErrorInfo;
						 } else {
					   echo "Message sent!";
						 }
*/						 
				//	$db->execQuery("Update users SET passreset = '$code' WHERE username = '$username'");	
					$db->updateResetCode($code,$username);
					$db->closeConnection();
					 echo " 
						<html>
							<script>
								alert(\"Your password reset link is sent to your e-mail address\");
								window.location.href = '../view/login.html';
							</script>
						</html>	
				";	
				}
				else
				{
	
						
						
					echo "
					<html> 
						<script>
						 		alert(\"Incorrect email\");
								window.location.href = '../view/forgotpassword.html';  
							</script>
							 
						 </html> 
					";
				}
				
			
		}
		else
		{
			  
		echo "	
		<html>
					<script>
					alert(\"Username does not exists\");
					window.location.href = '../view/forgotpassword.html';
				</script>
			</html>	
		";
		}

}
   
?>
