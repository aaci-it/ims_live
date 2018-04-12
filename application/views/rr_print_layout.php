
<?php

	function convert_number_to_words($number) {
    
		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
			0                   => 'Zero',
			1                   => 'One',
			2                   => 'Two',
			3                   => 'Three',
			4                   => 'Four',
			5                   => 'Five',
			6                   => 'Six',
			7                   => 'Seven',
			8                   => 'Eight',
			9                   => 'Nine',
			10                  => 'Ten',
			11                  => 'Eleven',
			12                  => 'Twelve',
			13                  => 'Thirteen',
			14                  => 'Fourteen',
			15                  => 'Fifteen',
			16                  => 'Sixteen',
			17                  => 'Seventeen',
			18                  => 'Eighteen',
			19                  => 'Nineteen',
			20                  => 'Twenty',
			30                  => 'Thirty',
			40                  => 'Fourty',
			50                  => 'Fifty',
			60                  => 'Sixty',
			70                  => 'Seventy',
			80                  => 'Eighty',
			90                  => 'Ninety',
			100                 => 'Hundred',
			1000                => 'Thousand',
			1000000             => 'Million',
			1000000000          => 'Billion',
			1000000000000       => 'Trillion',
			1000000000000000    => 'Quadrillion',
			1000000000000000000 => 'Quintillion'
		);
		    
		if (!is_numeric($number)) {
		    return false;
		}
		    
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
		    // overflow
		    trigger_error(
		        'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
		        E_USER_WARNING
		    );
		    return false;
		}

		if ($number < 0) {
		    return $negative . convert_number_to_words(abs($number));
		}
		    
		$string = $fraction = null;
		    
		if (strpos($number, '.') !== false) {
		    list($number, $fraction) = explode('.', $number);
		}
		    
		switch (true) {
		    case $number < 21:
		        $string = $dictionary[$number];
		        break;
		    case $number < 100:
		        $tens   = ((int) ($number / 10)) * 10;
		        $units  = $number % 10;
		        $string = $dictionary[$tens];
		        if ($units) {
		            $string .= $hyphen . $dictionary[$units];
		        }
		        break;
		    case $number < 1000:
		        $hundreds  = $number / 100;
		        $remainder = $number % 100;
		        $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
		        if ($remainder) {
		            $string .= $conjunction . convert_number_to_words($remainder);
		        }
		        break;
		    default:
		        $baseUnit = pow(1000, floor(log($number, 1000)));
		        $numBaseUnits = (int) ($number / $baseUnit);
		        $remainder = $number % $baseUnit;
		        $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
		        if ($remainder) {
		            $string .= $remainder < 100 ? $conjunction : $separator;
		            $string .= convert_number_to_words($remainder);
		        }
		        break;
		}
		    
		if (null !== $fraction && is_numeric($fraction)) {
		    $string .= $decimal;
		    $words = array();
		    foreach (str_split((string) $fraction) as $number) {
		        $words[] = $dictionary[$number];
		    }
		    $string .= implode(' ', $words);
		}
		    
		return $string;
	}

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
		$from = $row->wh_name;
		$trucker = $row->truck_company;
		$tdriver = $row->truck_driver;
		$tpnum = $row->truck_platenum;
		$item_qty = $row->wi_itemqty;
		$item_uom = $row->item_uom;
		$item_desc = $row->comm__name;
		$remarks = $row->wi_remarks;
		$custname = $row->CardName;
		$custaddr = $row->wi_location;
		$uname = $row->memb__username;
		$pby = $row->prepared_by;
		$gduty = $row->guard_duty;
	}	
	$show_layout = 1;
}

if($show_layout == 1){

	date_default_timezone_set("Asia/Manila");
	$date_today = date('Y-m-d');
	
	$pdf = new FPDF();
	$pdf->AddPage('O');
	new FPDF('P','mm','Letter');

	$pdf->SetFont('Arial','',9); $pdf->SetXY(155, 20); $pdf->Cell(40,10,$date_today,0, 1); // 185, 40
	$pdf->SetFont('Arial','',9); $pdf->SetXY(155, 25); $pdf->Cell(40,10,$from,0, 1); // 185, 50

	$rfrom = $custname.' / '.$trucker;

	$pdf->SetFont('Arial','B',12); $pdf->SetXY(25, 21); $pdf->Cell(90,10,$rfrom,0, 1); // 55, 43

	$itmqty_temp = explode(".", $item_qty);

	// CHECK IF THE ITEM QTY HAS DECIMAL GREATER THAN 0
	if($itmqty_temp[1] == "000"){
		$item_qty = $itmqty_temp[0];
	}else{
		$item_qty = $item_qty;
	}

	$iqty = '***'.$item_qty.'***';

	$pdf->SetFont('Arial','B',12); $pdf->SetXY(1, 40); $pdf->Cell(30,10,$iqty,0, 1); // 20, 70
	$pdf->SetFont('Arial','B',12); $pdf->SetXY(40, 40); $pdf->Cell(95,10,$item_desc,0, 1); // 60, 70

	$cwords = convert_number_to_words($item_qty).' BAG(S) ONLY';
	$cwords = strtoupper($cwords);

	$pdf->SetFont('Arial','',10); $pdf->SetXY(40, 44); $pdf->Cell(95,10,$cwords,0, 1); // 60, 75

	$str_01 = "# ";
	$str_02 = "# ";
	$str_03 = "PLATE# ".$tpnum;

	$pdf->SetFont('Arial','',8); $pdf->SetXY(1, 45); $pdf->Cell(95,10,$str_01,0, 1); // 60, 85
	$pdf->SetFont('Arial','',8); $pdf->SetXY(1, 48); $pdf->Cell(95,10,$str_02,0, 1); // 60, 90
	$pdf->SetFont('Arial','',8); $pdf->SetXY(1, 51); $pdf->Cell(95,10,$str_03,0, 1); // 60, 95

	$pdf->SetFont('Arial','B',12); $pdf->SetXY(10, 70); $pdf->Cell(70,10,$remarks,0, 1); // 40, 125

	$pdf->SetFont('Arial','',10); $pdf->SetXY(2, 82); $pdf->Cell(50,10,strtoupper($pby),0, 1, 'C'); // 20, 145
	$pdf->SetFont('Arial','',10); $pdf->SetXY(65, 82); $pdf->Cell(50,10,strtoupper($gduty),0, 1, 'C'); // 95, 145

	$pdf->SetFont('Arial','',10); $pdf->SetXY(145, 82); $pdf->Cell(50,10,strtoupper($tdriver),0, 1); // 165, 145

	// $_SERVER['DOCUMENT_ROOT'].'/inventory/application/PRINT_DOCS/RR/
	$filename=$_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/PRINT_DOCS/RR/'.$wi_id.".pdf";
	$pdf->Output($filename,'F');
}

?>
