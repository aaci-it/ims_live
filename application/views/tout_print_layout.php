
<?php

$show_layout = 0;

if($print){
	foreach($print as $row){
		$wi_id = $row->wi_id;
		$whname = $row->wh_name;
		$itemcode = $row->item_id;
		$reftype1 = $row->wi_reftype;
		$refnum1 = $row->wi_refnum;
		$reftype2 = $row->wi_reftype2;
		$refnum2 = $row->wi_refnum2;
		$to = $row->wi_location;
		$from = $row->wh_name;
		$trucker = $row->truck_company;
		$item_qty = $row->wi_itemqty;
		$item_uom = $row->item_uom;
		$item_desc = $row->comm__name;
		$remarks = $row->wi_remarks;
	}	
	$show_layout = 1;
}


if($show_layout == 1){

	date_default_timezone_set("Asia/Manila");
	$date_today = date('Y-m-d');

	$pdf = new FPDF();
	$pdf->AddPage('O');
	new FPDF('P','mm','Letter');
	$pdf->SetMargins(1, 1, 1);

	$bl_no = $reftype1.'# '.$refnum1;

	$pdf->SetFont('Arial','B',9); $pdf->SetXY(100, 24); $pdf->Cell(40,10,$date_today,0, 1); // 135, 48
	$pdf->SetFont('Arial','B',9); $pdf->SetXY(100, 21); $pdf->Cell(40,10,$bl_no,0, 1); // 135, 53

	$pdf->SetFont('Arial','B',9); $pdf->SetXY(2, 24); $pdf->Cell(80,10,$from,0, 1); // 30, 54s
	$pdf->SetFont('Arial','B',9); $pdf->SetXY(2, 26.5); $pdf->Cell(80,10,$to,0, 1); // 30, 59

	$address = "ALL ASIAN COUNTERTRADE INC.";

	$pdf->SetFont('Arial','B',9); $pdf->SetXY(2, 29); $pdf->Cell(80,10,$address,0, 1); // 30, 64
	$pdf->SetFont('Arial','B',9); $pdf->SetXY(2, 31.5); $pdf->Cell(80,10,$to,0, 1); // 30, 69

	$pdf->SetFont('Arial','B',11); $pdf->SetXY(1, 40); $pdf->Cell(30,15,$item_qty,0, 1); // 10, 80
	$pdf->SetFont('Arial','B',11); $pdf->SetXY(15, 40); $pdf->Cell(13,15,$item_uom,0, 1); // 40, 80
	$pdf->SetFont('Arial','B',11); $pdf->SetXY(30, 45); $pdf->MultiCell(55,3,$item_desc,0, 1); // 55, 80
	$pdf->SetFont('Arial','B',11); $pdf->SetXY(92, 40); $pdf->Cell(55,15,$date_today,0, 1); // 112, 80

	$wb_no = $reftype2.'# '.$refnum2;

	$pdf->SetFont('Arial','B',11); $pdf->SetXY(92, 50); $pdf->MultiCell(40,3,$wb_no,0, 1); // 112, 85
	$pdf->SetFont('Arial','B',9); $pdf->SetXY(2, 50); $pdf->Cell(85,15,$remarks,0, 1); // 30, 97

	$whse_man = "";
	$guard = "";
	$driver = "";

	$pdf->SetFont('Arial','B',9); $pdf->SetXY(2, 56); $pdf->Cell(85,15,$whse_man,0, 1); // 30, 97
	$pdf->SetFont('Arial','B',9); $pdf->SetXY(40, 56); $pdf->Cell(85,15,$guard,0, 1); // 30, 97
	$pdf->SetFont('Arial','B',9); $pdf->SetXY(90, 56); $pdf->Cell(85,15,$whse_man,0, 1); // 30, 97

	// $pdf->SetFont('Arial','B',12); $pdf->SetXY(40, 34); $pdf->Cell(40,10,$truck,0, 1);
	// $pdf->SetFont('Arial','B',12); $pdf->SetXY(127, 34); $pdf->Cell(40,10,$bp,0, 1);
	// $pdf->SetFont('Arial','B',12); $pdf->SetXY(127, 38); $pdf->Cell(40,10,$address,0, 1);
	// $pdf->SetFont('Arial','B',12); $pdf->SetXY(50, 45); $pdf->Cell(40,10,$po_no,0, 1);
	// $pdf->SetFont('Arial','B',12); $pdf->SetXY(180, 45); $pdf->Cell(40,10,$docnum,0, 1);
	// $pdf->SetFont('Arial','B',12); $pdf->SetXY(40, 50); $pdf->Cell(40,10,$qty,0, 1);
	// $pdf->SetFont('Arial','B',12); $pdf->SetXY(70, 50); $pdf->Cell(40,10,$uom,0, 1);
	// $pdf->SetFont('Arial','B',9); $pdf->SetXY(120, 50); $pdf->Cell(40,10,$description,0, 1);
	// $pdf->SetFont('Arial','B',7); $pdf->SetXY(100, 55); $pdf->MultiCell(100,5,$rem);

	// $_SERVER['DOCUMENT_ROOT'].'/inventory/application/PRINT_DOCS/TOUT/
	$filename=$_SERVER['DOCUMENT_ROOT'].'/inventory/application/PRINT_DOCS/TOUT/'.$wi_id.".pdf";
	$pdf->Output($filename,'F');
}

?>
