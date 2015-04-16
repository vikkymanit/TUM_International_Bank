<html>
<head>
    <link rel="stylesheet" type="text/css" href="login.css">
    <title>TUMonline Bank</title>
</head>
   
<body>
<h4 align="center">Welcome user!</h4>
<section id="landingPage">
	<?php
		session_start();
		switch ($_SESSION['error']) {
			case 1:
				echo "<h3 align='center'>Invalid account number. Please try again.</h3>";
				break;
			case 2:
				echo "<h3 align='center'>You can't give your own account number.</h3>";
				break;
			case 3:
				echo "<h3 align='center'>Invalid TAN number.</h3>";
				break;
			case 4:
				echo "<h3 align='center'>Negative transaction amounts are not supported.</h3>";
				break;
			case 5:
				echo "<h3 align='center'>You dont have sufficient balance for this transaction.</h3>";
				break;
			case 6:
				echo "<h3 align='center'>You have uploaded an invalid file. Please try again.</h3>";
				break;
			case 7:
				echo "<h3 align='center'>That accout number doesn't exist.</h3>";
				break;
			case 8:
				echo "<h3 align='center'>Internal Error occurred. Please try again.</h3>";
				break;
			case 9:
				echo "<h3 align='center'>Max file size exceeded. Please upload a file less than 10MB</h3>";
				break;
		}
		if ($_SESSION['error']==7) {
			echo "<a href=\"../controllers/employeelanding.php\">Back</a>";
		} else {
			echo "<a href=\"../controllers/user-landing.php\">Back</a>";
		}
	?>
</section>
</body>	

</html>
