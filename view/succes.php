<html>
<head>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
   
<body>
<h4 align="center">Welcome user!</h4>
<section id="landingPage">
	<?php 
		session_start();
		switch ($_SESSION['message']) {
			case 1:
				echo "<h3 align='center'>Your transaction has been successful</h3>";
				break;
			case 2:
				echo "<h3 align='center'>Your transaction request has been received. It will be carried out subject to approval.</h3>";
				break;
		}
	?>
	<a href="../controllers/user-landing.php">Back</a>
</section>
</body>	

</html>
