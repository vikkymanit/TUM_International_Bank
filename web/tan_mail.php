<?php
	function tan_mail($TANList,$email){
	$email_subject = "Your TAN List";
	$email_address = "vikkymanit@yahoo.co.in";
	$email_message = "Dear Customer,". "\n\n" ."Please find your list below.\n";
	for($i=1;$i<=100;$i++)
	{
		$email_message .= $i . "->" . $TANList[$i]. "\n";        //The TAN list
	}
	$email_message .= "\n\n Thank You for banking with us.";
	mail($email_address,$email_subject,$email_message,'From: securebankingcode@gmail.com');
	}
?>