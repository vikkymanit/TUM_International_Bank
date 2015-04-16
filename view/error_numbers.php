<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Transaction status</title>
<link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
	<section id="landingPage">
	<h3 align="center">The transactions to the following accounts could not be carried out.</h3>
	<table align="center" style="width: 100%; border-spacing: 10px; padding: 20px; text-align: center">
		<?php
			session_start();
			foreach ($_SESSION['invalid_acc'] as $invalidacc) {
				echo "<tr><td><h4>$invalidacc</h4></td></tr>";	
			}
		?>
	</table>
	<a href="../controllers/user-landing.php">Back</a>
</section>
</body>
</html>