
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
    $to = $row->wi_location;
    $from = $row->wh_name;
    $trucker = $row->truck_company;
    $item_qty = $row->wi_itemqty;
    $item_uom = $row->item_uom;
    $item_desc = $row->comm__name;
    $remarks = $row->wi_remarks;
    $deldate = $row->deldate;
    $custcode = $row->CardCode;
    $custname = $row->CardName;
    $ponum = $row->wi_PONum;
    $dodate = $row->deldate;
    $note1 = $row->note1;
    $note2 = $row->note2;
    $seal = $row->seal;
    $wb = $row->ref_print;
    $tdriver = $row->truck_driver;
    $tpnum = $row->truck_platenum;
    $pby = $row->prepared_by;
    $cby = $row->checked_by;
    $gduty = $row->guard_duty;
    $other_rmks = $row->other_remarks;
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

  $pdf->SetFont('Cambria', '', 13); $pdf->SetXY(140, 32); $pdf->Cell(40, 13, $refnum1, 0, 1); //160, 35
  $pdf->SetFont('Cambria', '', 11); $pdf->SetXY(140, 38); $pdf->Cell(40, 11, $deldate, 0, 1); //160, 40

  $tcomp = "";
  if($trucker == "WEI"){
    $tcomp  = "WHITELANDS EXPRESS INC.";
  }elseif($trucker == "AGTI"){
    $tcomp = "ANDREW & GRAY TRANSPORT INC.";
  }else{
    $tcomp = $trucker;
  }

  $pdf->SetFont('Helvetica', '', 13); $pdf->SetXY(15, 45); $pdf->Cell(70, 13, $tcomp, 0, 1, 'C'); // 40, 55
  $pdf->SetFont('Helvetica', '', 13); $pdf->SetXY(110, 45); $pdf->Cell(70, 13, $custname, 0, 1, 'C'); // 145, 48
  $pdf->SetFont('Helvetica', '', 9); $pdf->SetXY(110, 52); $pdf->Cell(70, 9, $to, 0, 1, 'C'); // 145, 53

  $donum = 'DO '.$refnum1;

  $pdf->SetFont('Cambria', '', 10); $pdf->SetXY(3, 69); $pdf->Cell(25, 10, $donum, 0, 1); //20, 68
  $pdf->SetFont('Cambria', '', 10); $pdf->SetXY(35, 69); $pdf->Cell(25, 10, $ponum, 0, 1); //58, 68
  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(70, 69); $pdf->Cell(40, 10, $custcode, 0, 1); //90, 68
  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(130, 65); $pdf->Cell(40, 10, $dodate, 0, 1); // 140, 68

  $drnum = 'DR '.$refnum2;
  // $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(165, 69); $pdf->MultiCell(25, 25, $drnum, 'T', 'L'); // 186, 68
  $pdf->SetFont('Cambria', '', 10); $pdf->SetXY(160, 69); $pdf->Cell(40, 10, $drnum, 0, 1); // 140, 68

  $pdf->SetFont('Cambria','', 13); $pdf->SetXY(3, 88); $pdf->Cell(45, 11, $item_qty, 0, 1); // 20, 92
  $pdf->SetFont('Helvetica','', 13); $pdf->SetXY(55, 88); $pdf->Cell(15, 11, strtoupper($item_uom), 0, 1, 'C');
  $pdf->SetFont('Helvetica','', 13); $pdf->SetXY(85, 88); $pdf->Cell(55, 11,strtoupper($item_desc),0, 1);

  // $temp_qty = substr($item_qty, 0, -2);
  $words = convert_number_to_words(sprintf('%g',$item_qty)).' Bag(s) Only';

  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(85, 93); $pdf->Cell(55, 10, $words, 0, 1); // 105, 97

  $pdf->SetFont('Helvetica','', 10); $pdf->SetXY(135, 100); $pdf->MultiCell(55, 5, $other_rmks, 0, 1);  

  $note_1 = 'Note : '.$note1;

  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(3, 93); $pdf->Cell(50, 10, $note_1, 0, 1); // 105, 97

  $seal_1 = 'Seal No. : '.$seal;

  $pdf->SetFont('Cambria', '', 10); $pdf->SetXY(3, 98); $pdf->Cell(50, 10, $seal_1, 0, 1); // 25, 102

  $wbno_1 = 'Ref. wb : '.$wb;

  $pdf->SetFont('Cambria', '', 10); $pdf->SetXY(85, 98); $pdf->Cell(50, 10, $wbno_1, 0, 1);// 105, 102

  $dtime_str = "DATE/TIME IN (CUSTOMER)";

  $note_2 = 'Note : '.$note2;

  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(3, 103); $pdf->Cell(50, 10, $dtime_str, 0, 1); // 25, 107
  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(85, 103); $pdf->Cell(50, 10, $note_2, 0, 1); // 105, 53
  
  $str_01 = "Prepared By :";
  $str_02 = "Checked By :";
  $str_03 = "Released By :";


$pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(3, 108); $pdf->Cell(30, 10, $str_01, 0, 1); // 25, 115
  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(3, 113); $pdf->Cell(30, 10, $pby, 0, 1); //25, 118

  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(53, 108); $pdf->Cell(30, 10, $str_02, 0, 1); // 58, 115
  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(53, 113); $pdf->Cell(30, 10, $cby, 0, 1); //58, 118

  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(100, 108); $pdf->Cell(30, 10, $str_03, 0, 1); //92, 115
  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(100, 113); $pdf->Cell(30, 10, $gduty, 0, 1); // 92, 118

  $line = "_______________________________";
  $str_04 = "RECEIVED IN GOOD CONDITION";

  $pdf->SetFont('Helvetica', '', 11); $pdf->SetXY(112, 115); $pdf->Cell(80, 11, $line, 0, 1); // 130, 120
  $pdf->SetFont('Helvetica', 'B', 11); $pdf->SetXY(112, 120); $pdf->Cell(80, 11, $str_04, 0, 1, 'C'); // 130, 123

  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(40, 120); $pdf->Cell(50, 10, $tdriver, 0, 1); // 40, 125
  $pdf->SetFont('Cambria', '', 10); $pdf->SetXY(40, 130); $pdf->Cell(55, 10, $tpnum, 0, 1); // 55, 135

  $str_05 = "TIME RECEIVED :";
  $str_06 = "DATE RECEIVED :";
  $str_07 = "Note : All Documents is placed on envelope";

  $pdf->SetFont('Helvetica', '', 9); $pdf->SetXY(115, 130); $pdf->Cell(80, 9, $str_05, 0, 1); // 130, 130
  $pdf->SetFont('Helvetica', '', 9); $pdf->SetXY(115, 133); $pdf->Cell(80, 9, $str_06, 0, 1); //130, 135

  $pdf->SetFont('Helvetica', '', 10); $pdf->SetXY(105, 137); $pdf->Cell(80, 10, $str_07, 0, 1); //125, 70

  $pdf->SetFont('Cambria', '', 10); $pdf->SetXY(40, 137); $pdf->Cell(80, 10, $datetime, 0, 1); // 40 ,70

  // $_SERVER['DOCUMENT_ROOT'].'/inventory/application/PRINT_DOCS/WIS/
  $filename=$_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/PRINT_DOCS/DR/'.$wi_id.".pdf";
  $pdf->Output($filename,'F');

}

?>