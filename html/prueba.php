<?php

include_once("../fpdf/fpdf.php");



$pdf = new FPDF();
 $pdf->AddPage();
 $pdf->SetFont('Arial','B',16);
 $pdf->Cell(40,10,'Hello World!');

//$pdf->Image('imagenes/Logo apuestas online.png',10,10,-300);
error_log($pdf->GetX());
$pdf->Image('imagenes/Logo apuestas online.png',$pdf->GetX() / 2,10,-100);

header("Content-type:application/pdf");

 $pdf->Output();

?>
