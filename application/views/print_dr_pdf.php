
<?php

$show_layout = 0;

if($print){
	foreach($print as $row){
		$truck = $row->Truck;
		$address = $row->Address2;
		$del_date = $row->DocDueDate;
		$bp = $row->CardName;
		$docnum = $row->DocNum;
		$po_no = $row->U_PONo;
		$qty = number_format($row->Quantity,2,'.','');
		$uom = $row->unitMsr;
		$description = $row->Dscription;
		$rem = $row->Comments;
	}
	$show_layout = 1;
}
else{
	echo 'no record/s found';
}

if($show_layout == 1){
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetMargins(1, 1, 1);
	
	$pdf->SetFont('Arial','B',9); $pdf->SetXY(135, 25); $pdf->Cell(40,10,$docnum,0, 1);
	$pdf->SetFont('Arial','B',9); $pdf->SetXY(135, 29); $pdf->Cell(40,10,$del_date,0, 1);
	$pdf->SetFont('Arial','B',12); $pdf->SetXY(40, 34); $pdf->Cell(40,10,$truck,0, 1);
	$pdf->SetFont('Arial','B',12); $pdf->SetXY(127, 34); $pdf->Cell(40,10,$bp,0, 1);
	$pdf->SetFont('Arial','B',12); $pdf->SetXY(127, 38); $pdf->Cell(40,10,$address,0, 1);
	$pdf->SetFont('Arial','B',12); $pdf->SetXY(50, 45); $pdf->Cell(40,10,$po_no,0, 1);
	$pdf->SetFont('Arial','B',12); $pdf->SetXY(180, 45); $pdf->Cell(40,10,$docnum,0, 1);
	$pdf->SetFont('Arial','B',12); $pdf->SetXY(40, 50); $pdf->Cell(40,10,$qty,0, 1);
	$pdf->SetFont('Arial','B',12); $pdf->SetXY(70, 50); $pdf->Cell(40,10,$uom,0, 1);
	$pdf->SetFont('Arial','B',9); $pdf->SetXY(120, 50); $pdf->Cell(40,10,$description,0, 1);
	$pdf->SetFont('Arial','B',7); $pdf->SetXY(100, 55); $pdf->MultiCell(100,5,$rem);
	$name = $this->uri->segment(3);
	//$pdf->Output($name.'.pdf', 'D');
	//$pdf->Output();
	
	if( $pdf->Output($name.'.pdf', 'D') ){ redirect('main'); }
	
}

?>