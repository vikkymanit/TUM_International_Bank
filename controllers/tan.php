<?php
require_once 'tan_mail.php';
require_once('DbConnector.php');
require_once('../lib/fpdf/fpdf_protection.php');
//require ('../lib/fpdf/fpdf.php');
function genTAN($user_id, $accountNo, $email, $clientPIN)
{
    
    //Under the string $Characters write all the characters you want to be used to randomly generate the code.
    $Caracteres    = 'ABCDEFGHIJKLMOPQRSTUVXWYZabcdefghijklmnopqrstuvwxyz0123456789';
    $CaracteresLen = strlen($Caracteres);
    $CaracteresLen--;
    $TanNo = array();
    $Count = 0;
    
    //Establish the database connection
    $db = new DbConnector;
    
    //Generate 100 Tan numbers
    while ($Count < 100) {
        $Hash = NULL;
        for ($x = 1; $x <= 15; $x++) {
            $Pos = rand(0, $CaracteresLen);
            $Hash .= substr($Caracteres, $Pos, 1);
        }
        
        //Check if any duplicate Tan number exists in the database
        $result = $db->execQuery("SELECT tan FROM tan_numbers WHERE tan = '$Hash'");
        if (mysqli_num_rows($result) == 0) {
            $TanNo[$Count++] = $Hash;
        }
    }
    
    //Block insert the generated Tan numbers into the database
    $values = array();
    $seq    = 1;
    foreach ($TanNo as $value) {
        $values[] = '(' . $user_id . ',' . $seq++ . ',"' . $value . '",' . '"2015-12-12",' . 0 . ')';
    }
    
    $query = "INSERT INTO tan_numbers (user_id,seq_number,tan,expiry_date,expired) VALUES " . implode(',', $values);
    
    if (!$db->execQuery($query)) {
        echo "Database Error. Please try again later.";
        exit("Error Occured");
    }
    $db->closeConnection();
    if ($clientPIN == -1){
		genTANPDF($TanNo, $user_id);
		tan_mail($email,$accountNo);
	}
	else {
		tan_mailpin($email,$accountNo,$clientPIN);
	}
}

function genTANPDF($TanNo, $user_id)
{
    //Establish the database connection
    $db       = new DbConnector;
    $result   = $db->execQuery("SELECT fullname, username FROM users WHERE user_id = '$user_id'");
    $pdf      = new FPDF_Protection();
    $pass     = mysqli_fetch_assoc($result);
    $password = substr((strtoupper($pass['fullname'])), 0, 4) . substr((strtolower($pass['username'])), 0, 4);
    $pdf->SetProtection(array(
        'copy',
        'print'
    ), $password);
    $pdf->AddPage();
    $pdf->SetFont('Arial');
    for ($i = 0; $i < 100; $i++) {
        $temp = $i + 1 . "->" . $TanNo[$i];
        $pdf->Cell(5);
        $pdf->Cell(5, 5, $temp, 0, 1);
        
    }
    $filename = "TANList.pdf";
    $pdf->Output($filename, 'F');
}

//echo genTAN(2, 60018, "shivguru.rao.91@gmail.com");

?>
