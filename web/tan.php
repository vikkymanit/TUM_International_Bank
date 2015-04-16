<?php
//include 'tan_mail.php';
function genTAN($user_id){
//Under the string $Characters write all the characters you want to be used to randomly generate the code.
$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZabcdefghijklmnopqrstuvwxyz0123456789';
$CaracteresLen = strlen($Caracteres);
$CaracteresLen--;
$TanNo = array();
$Count = 0;
//Establish the database connection
$user = 'root';
$pass = '';
$db = 'foobank';
$Conn = new mysqli('localhost', $user, $pass, $db) or die("Failed to connect to database");
//Generate 100 Tan numbers
while($Count<100){
	$Hash=NULL;
		for($x=1;$x<=15;$x++){
			$Pos = rand(0,$CaracteresLen);
			$Hash .= substr($Caracteres,$Pos,1);
		}
	$result = mysqli_query($Conn,"SELECT tan FROM tan_numbers WHERE tan = '$Hash'"); //Check if any duplicate Tan number exists in the database
	if(mysqli_num_rows($result) == 0) {
		$TanNo[$Count++] = $Hash;
		//mysqli_query($Conn,"INSERT INTO tan (tanno) VALUES ('$Hash')");
	}
}
//Block insert the generated Tan numbers into the database
$values = array();
$seq = 1;
foreach($TanNo as $value){
    $values[] = '('.$user_id . ',' . $seq++ . ',"' . $value. '",' . '"2014-12-12",' . 1 . ')';
}
//$values = implode( ',', $values );
$query = "INSERT INTO tan_numbers (user_id,seq_number,tan,expiry_date,expired) VALUES ".implode( ',', $values );
echo $query;
if(!mysqli_query($Conn, $query)){
	echo "Database Error. Please try again later.";
}
mysqli_close($Conn);
}
echo genTAN(1);
?>
