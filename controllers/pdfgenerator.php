<?php
require ('../lib/fpdf/fpdf.php');
require_once('utils.php');
require_once ('DbConnector.php');
session_start();
$account=$_SESSION['account'];
$db = new DbConnector ();
$userid = getUserId($account);
$result = $db->execQuery(getTransacQuery($userid));

$numrows = mysqli_num_rows ( $result ); 


$pdf = new FPDF ('P','mm',array(300,250));
$pdf->AddPage ();
$pdf->SetFont ( 'Helvetica', 'B', 16 );
$pdf->Cell ( 0, 0, 'Transaction History', 0, 1, 'C' );
$pdf->Cell ( 0, 10, '', 0, 1 );

	
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();
$cell_width = 25;
$pdf->SetFont ( 'Arial', 'B', 14 );
$pdf->SetXY(2 , $current_y);
$pdf->MultiCell ( 20, 5, 'Source Name');
$pdf->SetXY($current_x + $cell_width, $current_y);
$current_x = $pdf->GetX();
$pdf->MultiCell ( 25, 5, 'Source Account');
$pdf->SetXY($current_x + $cell_width +5, $current_y);
$current_x = $pdf->GetX();
$pdf->MultiCell ( 30, 5, 'Destination Name');
$pdf->SetXY($current_x + $cell_width + 10, $current_y);
$current_x = $pdf->GetX();
$pdf->MultiCell ( 30, 5, 'Destination Account');
$pdf->SetXY($current_x + $cell_width + 10, $current_y);
$current_x = $pdf->GetX();
$pdf->MultiCell ( 25, 5, 'Amount');
$pdf->SetXY($current_x + $cell_width + 5, $current_y);
$current_x = $pdf->GetX();
$pdf->MultiCell ( 15, 5, 'Date');
$pdf->SetXY($current_x + $cell_width + 5, $current_y);
$current_x = $pdf->GetX();
$pdf->MultiCell ( 30, 5, 'Description');
$pdf->SetXY($current_x + $cell_width + 10, $current_y);
$current_x = $pdf->GetX();
$pdf->MultiCell ( 30, 5, 'Status');

$pdf->MultiCell(10, 10, '');
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();
$cell_width = 25;

if ($numrows == 0) {
	$pdf->SetFont ( 'Arial', 'I', 12 );
	$pdf->Cell ( 0, 0, 'No transactions done', 0, 1, 'C' );
} else {
	$pdf->SetFont ( 'Arial', '', 12 );
	for($i = 0; $i < $numrows; ++ $i) {
		$transactions = mysqli_fetch_assoc ( $result );
		$pdf->SetXY(2 , $current_y);
		$pdf->MultiCell ( 30, 5, $transactions ['src_userid']);
		$pdf->SetXY($current_x + $cell_width , $current_y);
		$current_x = $pdf->GetX();
		$pdf->MultiCell ( 25, 5, $transactions ['src_account']);
		$pdf->SetXY($current_x + $cell_width +5, $current_y);
		$current_x = $pdf->GetX();
		$pdf->MultiCell ( 30, 5, $transactions ['dst_userid']);
		$pdf->SetXY($current_x + $cell_width + 10, $current_y);
		$current_x = $pdf->GetX();
		$pdf->MultiCell ( 30, 5, $transactions ['dst_account']);
		$pdf->SetXY($current_x + $cell_width + 10, $current_y);
		$current_x = $pdf->GetX();
		$pdf->MultiCell ( 25, 5, $transactions ['amount']);
		$pdf->SetXY($current_x + $cell_width + 5, $current_y);
		$current_x = $pdf->GetX();
		$pdf->MultiCell ( 30, 5, $transactions ['creation_date']);

		$pdf->SetXY($current_x + $cell_width + 5, $current_y);
		$current_x = $pdf->GetX();
		$pdf->MultiCell ( 30, 5, $transactions ['description']);
		$pdf->SetXY($current_x + $cell_width + 10, $current_y);
		$current_x = $pdf->GetX();
		switch ($transactions ['is_approved']){
			case 0:
				$pdf->MultiCell ( 30, 5, "Pending Approval");
				break;
			case 1:
				$pdf->MultiCell ( 30, 5, "Approved");
				break;
			case 2:
				$pdf->MultiCell ( 30, 5, "Rejected");
				break;
		}
		
		$current_y = $pdf->GetY() + 7;
		$current_x = $pdf->GetX();
		$cell_width = 25;
	}
}

$pdf->Output ();
// echo genPdf(1234);
?>
