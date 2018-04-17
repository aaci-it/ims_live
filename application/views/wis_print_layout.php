

<?php

	// require($_SERVER['DOCUMENT_ROOT'].'/inventory_test/assets/fonts/Cambria.php');

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
		$pby = $row->prepared_by;
		$cby = $row->checked_by;
		$gduty = $row->guard_duty;
		$seal = $row->seal;
		$wb = $row->ref_print;
		$po_num = $row->wi_PONum;
	}	
	$show_layout = 1;
}


if($show_layout == 1){

	date_default_timezone_set("Asia/Manila");
	$date_today = date('Y-m-d');
	$datetime = date('Y-m-d h:i:s');
	
	$pdf = new FPDF('P','mm','Letter');

	$pdf->AddFont('Cambria', '', 'Cambria.php');
	$pdf->AddPage();
	

	$pdf->SetFont('Arial','', 10); $pdf->SetXY(100, 1); $pdf->Cell(50,10,$datetime,0, 1); // 120, 5

	$pdf->SetFont('Cambria', '', 13); $pdf->SetXY(145, 32); $pdf->Cell(50, 13, $refnum1, 0, 1); // 160, 40 

	$pdf->SetFont('Cambria', '', 10); $pdf->SetXY(150, 38); $pdf->Cell(50, 10, $date_today, 0, 1); // 160, 40 
	$pdf->SetFont('Arial', '', 13); $pdf->SetXY(30, 35); $pdf->Cell(85, 13, $custname, 0, 1); // 45, 40
	$pdf->SetFont('Arial', '', 10); $pdf->SetXY(30, 42); $pdf->Cell(85, 10, $custaddr, 0, 1); // 45, 45

	$iqty = '***'.$item_qty.'***'.'   '.$item_uom;

	$pdf->SetFont('Cambria', '', 13); $pdf->SetXY(3, 55); $pdf->Cell(55, 13, $iqty, 0, 1); //20, 60
	$pdf->SetFont('Arial', '', 13); $pdf->SetXY(80, 55); $pdf->Cell(130, 13, strtoupper($item_desc), 0, 1); //85, 60
		
	$words = '( '.strtoupper(convert_number_to_words(sprintf('%g',$item_qty))). ' BAG(S) ONLY )';

	$pdf->SetFont('Arial', '', 10); $pdf->SetXY(80, 61); $pdf->Cell(130, 10, $words, 0, 1); //85, 60

	$str_01 = "Prepared By:";
	$str_02 = "Checked By:";
	$str_03 = "Guard Duty:";

	$pdf->SetFont('Arial','', 10); $pdf->SetXY(3, 68); $pdf->Cell(55, 10, $str_01, 0, 1); //20, 70
	$pdf->SetFont('Arial', '', 10); $pdf->SetXY(3, 73); $pdf->Cell(55, 10, $pby, 0, 1); // 20, 73

	$pdf->SetFont('Arial', '', 10); $pdf->SetXY(60, 68); $pdf->Cell(55, 10, $str_02, 0, 1); // 60, 70
	$pdf->SetFont('Arial', '', 10); $pdf->SetXY(60, 73); $pdf->Cell(55, 10, $cby, 0, 1); // 60, 73

	$pdf->SetFont('Arial', '', 10); $pdf->SetXY(100, 68); $pdf->Cell(55, 10, $str_03, 0, 1); // 100, 70
	$pdf->SetFont('Arial', '', 10); $pdf->SetXY(100, 73); $pdf->Cell(55, 10, $gduty, 0, 1); // 100, 73

	$ponum_1 = 'PO#: '.$po_num;
	$pdf->SetFont('Cambria', '', 10); $pdf->SetXY(110, 78); $pdf->Cell(55, 10, $ponum_1, 0, 1); // 100, 73

	$wbno_1 = 'WB#: '.$wb;
	$pdf->SetFont('Cambria', '', 10); $pdf->SetXY(110, 83); $pdf->Cell(55, 10, $wbno_1, 0, 1); // 100, 73

	$seal_1 = 'SEAL INTACT #: '.$seal;
	$pdf->SetFont('Cambria', '', 10); $pdf->SetXY(110, 88); $pdf->Cell(55, 10, $seal_1, 0, 1); // 100, 73

	$str_04 = "DATE/TIME IN (CUSTOMER):";
	$str_05 = "TIME RECEIVED:__________ DATE RECEIVED:__________";

	$pdf->SetFont('Arial', 'B', 10); $pdf->SetXY(3, 80); $pdf->Cell(55, 10, $str_04, 0, 1); // 20, 78
	$pdf->SetFont('Arial', '', 10); $pdf->SetXY(3, 85); $pdf->Cell(55, 10, $str_05, 0, 1); // 20, 83

	$pdf->SetFont('Arial', '', 12); $pdf->SetXY(20, 95); $pdf->Cell(60, 12, $trucker, 0, 1, 'C'); // 50, 95
	$pdf->SetFont('Arial', '', 12); $pdf->SetXY(20, 100); $pdf->Cell(60, 12, $tdriver, 0, 1, 'C'); //50, 105
	$pdf->SetFont('Cambria','',12); $pdf->SetXY(120, 95); $pdf->Cell(75, 12, $tpnum, 0, 1, 'C'); // 145, 95
	$pdf->SetFont('Arial','',12); $pdf->SetXY(120, 100); $pdf->Cell(75, 12, $from, 0, 1, 'C'); // 145, 105

	// $_SERVER['DOCUMENT_ROOT'].'/inventory/application/PRINT_DOCS/WIS/
	$filename=$_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/PRINT_DOCS/WIS/'.$wi_id.".pdf";
	$pdf->Output($filename,'F');


}

?>
