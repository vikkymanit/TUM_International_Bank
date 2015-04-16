<?php
require_once('../lib/PHPMailer/PHPMailerAutoload.php');
function tan_mail($email_address,$accountNo)
{
    $mail = new PHPMailer(); // defaults to using php "mail()"
    
    $body = "<p>Dear Customer,<br><br> Your account has now been activated. Your account number is '$accountNo'.<br><br>
		Please find your attached TAN list. The list is password protected. 
		The password is combination of first four characters of your full name in uppercase and first four characters of your
		username in lowercase. For example, if your full name is John Watson and username is jwatson then your password will be JOHNjwat.
		<br><br> Thank You for banking with us.</p>";
    
    $mail->SetFrom('securebankingcode@gmail.com', 'TUM International Bank');
    
    //$mail->AddReplyTo("name@yourdomain.com","First Last");
    
    $address = $email_address;
    $mail->AddAddress($address);
    $mail->Subject = "TAN List from TUM International Bank";
    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test		
    $mail->MsgHTML($body);
    $mail->AddAttachment("TANList.pdf"); // attachment
    $check = $mail->Send();

//    		 if(!$check) {
//    		   echo "Mailer Error: " . $mail->ErrorInfo;
//    		 } else {
//   		   echo "Message sent!";
//    		 }


    unlink("TANList.pdf");
}
//echo tan_mail("vikky_manit@yahoo.co.in");

function tan_mailpin($email_address,$accountNo,$clientPIN){
	$mail = new PHPMailer(); // defaults to using php "mail()"
    
    $body = "<p>Dear Customer,<br><br> Your account has now been activated. Your account number is '$accountNo'.<br><br>
		Your PIN is '$clientPIN'. Please use this PIN to generate codes for performing transactions. Please do not disclose this PIN to anyone. 
		<br><br> Thank You for banking with us.</p>";
    
    $mail->SetFrom('securebankingcode@gmail.com', 'TUM International Bank');   
    $address = $email_address;
    $mail->AddAddress($address);
    $mail->Subject = "PIN from TUM International Bank";
    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test		
    $mail->MsgHTML($body);
    $check = $mail->Send();

//    		 if(!$check) {
//    		   echo "Mailer Error: " . $mail->ErrorInfo;
//    		 } else {
//   		   echo "Message sent!";
//    		 }

}

?>

