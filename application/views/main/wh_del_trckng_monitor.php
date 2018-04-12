<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php echo $this->load->view('header');?>

<?php 


// DISPLAY DATE TODAY

date_default_timezone_set("Asia/Manila");
$date_today = date('Y-m-d');

if(isset($records)): foreach($records as $r):

// DISPLAY TIME IN AM OR PM FOR ARRIVED TIME=============================================
$t = $r->trk_arrivedtime;
$t = substr($t, 0, -3);

if($t[0]=='0'){
	$time1 = substr($t, -4, 1);
}else{
	$time1 = substr($t, 0, 2);
}

$time2 = (int)$time1;
if($t=="00:00"){
	$t="";
}else{
	if($time2 >=12 and $time2 <=23){
		$t = $t." PM";
	}else{
		$t = $t." AM";
	}
}

// $arrtime = array('name'=>'arr_time','class'=>'arr_time','value'=>$t);

// DISPLAY TIME IN AM OR PM FOR ARRIVED TIME=============================================
$t2 = $r->trk_acceptedutime;
$t2 = substr($t2, 0, -3);

if($t2[0]=='0'){
	$time2 = substr($t2, -4, 1);
}else{
	$time2 = substr($t2, 0, 2);
}

$time3 = (int)$time2;
if($t2=="00:00"){
	$t2="";
}else{
	if($time3 >=12 and $time3 <=23){
		$t2 = $t2." PM";
	}else{
		$t2 = $t2." AM";
	}
}

// $uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2);
// ========================================================================

if(isset($modaccess)): foreach($modaccess as $rcd): 

	if($rcd->accessname == "Monitor"){

		//UPDATE BUTTON
		if($r->trk_arrivedstatus == 1 AND $r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 1){
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info', 'disabled'=>'disabled');
		}elseif($r->trk_arrivedstatus == 1 AND $r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 0){
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info', 'disabled'=>'disabled');
		}elseif($r->trk_arrivedstatus == 1 AND $r->trk_acceptedstatus == 0 AND $r->trk_canceledstatus == 1){
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info', 'disabled'=>'disabled');
		}else{
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info');
		}
		// EMAIL BUTTON
		if($r->email_cdel == 1){
			$email_button = array('name'=>'email', 'value'=>'Email', 'class'=>'btn btn-warning', 'disabled'=>'disabled');
		}else{
			$email_button = array('name'=>'email', 'value'=>'Email', 'class'=>'btn btn-warning');
		}

		//ARRIVED CHECKBOX
		if($r->trk_arrivedstatus == 1){
			$arrchk=array('name'=>'arrived','value'=>'arrived','checked'=>true, 'disabled'=>'disabled');
			$arrdate = array('name'=>'arrdate','id'=>'datepicker','value'=>$r->trk_arriveddate, 'disabled'=>'disabled');
			$arrtime = array('name'=>'arr_time','class'=>'arr_time','value'=>$t, 'readonly'=>'readonly');
			$arrremarks = array('name'=>'arrRemarks','size'=>'250','value'=>$r->trk_arrivedremarks, 'disabled'=>'disabled');
		}else{

			$arrchk=array('name'=>'arrived','value'=>'arrived', 'checked'=>false, 'id'=>'archk');
			$arrdate = array('name'=>'arrdate','id'=>'datepicker','value'=>$this->input->post('arrdate'));
			$arrtime = array('name'=>'arr_time','class'=>'arr_time','value'=>$this->input->post('arr_time'));
			$arrremarks = array('name'=>'arrRemarks','size'=>'250','value'=>$this->input->post('arrRemarks'));
		}
		

		//ACCEPTED CHECKBOX

		if($r->trk_acceptedqty <> NULL){
			$aqty = $r->trk_acceptedqty;
		}else{
			$aqty = $r->wi_itemqty;
		}

		if($r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 1){
			//ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accept', 'checked'=>true, 'disabled'=>'disabled');
			$accqty=$r->trk_acceptedqty;
			$accdate = array('name'=>'accdate','id'=>'datepicker1','value'=>$r->trk_accepteddate, 'disabled'=>'disabled');
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2, 'disabled'=>'disabled');
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$r->trk_acceptedremarks, 'disabled'=>'disabled');
			$aqa = array('name'=>'aqa','size'=>'10','value'=>$aqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			//CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'accept', 'checked'=>true, 'disabled'=>'disabled');
			$canqty=$r->trk_canceledqty;
			$candate = array('name'=>'candate','id'=>'datepicker2','value'=>$r->trk_canceleddate, 'disabled'=>'disabled');
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$canqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$r->trk_canceledremarks, 'disabled'=>'disabled');

		}elseif($r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 0){
			//ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accept', 'checked'=>true, 'disabled'=>'disabled');
			$accqty=$r->trk_acceptedqty;
			$accdate = array('name'=>'accdate','id'=>'datepicker1','value'=>$r->trk_accepteddate, 'disabled'=>'disabled');
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2, 'disabled'=>'disabled');
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$r->trk_acceptedremarks, 'disabled'=>'disabled');
			$aqa = array('name'=>'aqa','size'=>'10','value'=>$aqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			//CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'canceled', 'checked'=>false, 'disabled'=>'disabled', 'id'=>'cachk');
			$canqty=$r->trk_canceledqty;
			$candate = array('name'=>'candate','disabled'=>'disabled');
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$this->input->post('aqc'), 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$this->input->post('canRemarks'), 'disabled'=>'disabled');

		}elseif($r->trk_acceptedstatus == 0 AND $r->trk_canceledstatus == 1){
			//ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accepted', 'checked'=>false, 'disabled'=>'disabled', 'id'=>'acchk');
			$accqty=$r->trk_acceptedqty;
			$accdate = array('name'=>'accdate','disabled'=>'disabled');
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2, 'disabled'=>'disabled');
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$this->input->post('accRemarks'), 'disabled'=>'disabled');
			$aqa = array('name'=>'aqa','size'=>'10', 'id'=>'aqa_qty', 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled', 'value'=>$this->input->post('aqa'));
			//CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'accept', 'checked'=>true, 'disabled'=>'disabled');
			$canqty=$r->trk_canceledqty;
			$candate = array('name'=>'candate','id'=>'datepicker2','value'=>$r->trk_canceleddate, 'disabled'=>'disabled');
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$canqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$r->trk_canceledremarks, 'disabled'=>'disabled');
		}else{
			// ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accepted', 'checked'=>false, 'id'=>'acchk');
			$accqty=$r->wi_itemqty;
			$accdate = array('name'=>'accdate','id'=>'datepicker1','value'=>$this->input->post('accdate'));
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$this->input->post('uld_time'));
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$this->input->post('accRemarks'));
			$aqa = array('name'=>'aqa','size'=>'10','value'=>$this->input->post('aqa'), 'onkeypress'=>'return isNumberKey(event)', 'id'=>'aqa_qty');
			// CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'canceled', 'checked'=>false, 'id'=>'cachk');
			$canqty=null;
			$candate = array('name'=>'candate','id'=>'datepicker2','value'=>$this->input->post('candate'));
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$this->input->post('aqc'), 'onkeypress'=>'return isNumberKey(event)');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$this->input->post('canRemarks'));

		}
		
	}	


	if($rcd->accessname == "Monitor_ITD_ARR"){

		//UPDATE BUTTON
		if($r->trk_arrivedstatus == 1 AND $r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 1){
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info', 'disabled'=>'disabled');
		}elseif($r->trk_arrivedstatus == 1 AND $r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 0){
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info', 'disabled'=>'disabled');
		}elseif($r->trk_arrivedstatus == 1 AND $r->trk_acceptedstatus == 0 AND $r->trk_canceledstatus == 1){
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info', 'disabled'=>'disabled');
		}else{
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info');
		}
		// EMAIL BUTTON
		if($r->email_cdel == 1){
			$email_button = array('name'=>'email', 'value'=>'Email', 'class'=>'btn btn-warning', 'disabled'=>'disabled');
		}else{
			$email_button = array('name'=>'email', 'value'=>'Email', 'class'=>'btn btn-warning');
		}

		//ARRIVED CHECKBOX
		if($r->trk_arrivedstatus == 1){
			$arrchk=array('name'=>'arrived','value'=>'arrived','checked'=>true, 'disabled'=>'disabled');
			$arrdate = array('name'=>'arrdate','id'=>'datepicker','value'=>$r->trk_arriveddate, 'disabled'=>'disabled');
			$arrtime = array('name'=>'arr_time','class'=>'arr_time','value'=>$t, 'readonly'=>'readonly');
			$arrremarks = array('name'=>'arrRemarks','size'=>'250','value'=>$r->trk_arrivedremarks, 'disabled'=>'disabled');
		}else{
			$arrchk=array('name'=>'arrived','value'=>'arrived', 'checked'=>false);
			$arrdate = array('name'=>'arrdate','id'=>'datepicker','value'=>$this->input->post('arrdate'));
			$arrtime = array('name'=>'arr_time','class'=>'arr_time','value'=>$this->ihput->post('arr_time'));
			$arrremarks = array('name'=>'arrRemarks','size'=>'250','value'=>$this->input->post('arrRemarks'));
		}
		

		//ACCEPTED CHECKBOX

		if($r->trk_acceptedqty <> NULL){
			$aqty = $r->trk_acceptedqty;
		}else{
			$aqty = $r->wi_itemqty;
		}

		if($r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 1){
			//ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accept', 'checked'=>true, 'disabled'=>'disabled');
			$accqty=$r->trk_acceptedqty;
			$accdate = array('name'=>'accdate','id'=>'datepicker1','value'=>$r->trk_accepteddate, 'disabled'=>'disabled');
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2, 'disabled'=>'disabled');
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$r->trk_acceptedremarks, 'disabled'=>'disabled');
			$aqa = array('name'=>'aqa','size'=>'10','value'=>$aqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			//CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'accept', 'checked'=>true, 'disabled'=>'disabled');
			$canqty=$r->trk_canceledqty;
			$candate = array('name'=>'candate','id'=>'datepicker2','value'=>$r->trk_canceleddate, 'disabled'=>'disabled');
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$canqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$r->trk_canceledremarks, 'disabled'=>'disabled');

		}elseif($r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 0){
			//ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accepted', 'checked'=>true, 'disabled'=>'disabled');
			$accqty=$r->trk_acceptedqty;
			$accdate = array('name'=>'accdate','id'=>'datepicker1','value'=>$r->trk_accepteddate, 'disabled'=>'disabled');
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2, 'disabled'=>'disabled');
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$r->trk_acceptedremarks, 'disabled'=>'disabled');
			$aqa = array('name'=>'aqa','size'=>'10','value'=>$aqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			//CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'canceled', 'checked'=>false, 'disabled'=>'disabled', 'id'=>'cachk');
			$canqty=$r->trk_canceledqty;
			$candate = array('name'=>'candate','disabled'=>'disabled');
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$this->input->post('aqc'), 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$this->input->post('canRemarks'), 'disabled'=>'disabled');

		}elseif($r->trk_acceptedstatus == 0 AND $r->trk_canceledstatus == 1){
			//ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accepted', 'checked'=>false, 'disabled'=>'disabled', 'id'=>'acchk');
			$accqty=$r->trk_acceptedqty;
			$accdate = array('name'=>'accdate','disabled'=>'disabled');
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2, 'disabled'=>'disabled');
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$this->input->post('accRemarks'), 'disabled'=>'disabled');
			$aqa = array('name'=>'aqa','size'=>'10', 'id'=>'aqa_qty', 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled', 'value'=>$this->input->post('aqa'));
			//CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'canceled', 'checked'=>true, 'disabled'=>'disabled');
			$canqty=$r->trk_canceledqty;
			$candate = array('name'=>'candate','id'=>'datepicker2','value'=>$r->trk_canceleddate, 'disabled'=>'disabled');
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$canqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$r->trk_canceledremarks, 'disabled'=>'disabled');
		}else{
			// ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accepted', 'checked'=>false, 'disabled'=>'disabled', 'id'=>'acchk');
			$accqty=$r->wi_itemqty;
			$accdate = array('name'=>'accdate','id'=>'datepicker1','value'=>$r->trk_accepteddate, 'disabled'=>'disabled');
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2, 'disabled'=>'disabled');
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$this->input->post('accRemarks'), 'disabled'=>'disabled');
			$aqa = array('name'=>'aqa','size'=>'10','value'=>$this->input->post('aqa'), 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled', 'id'=>'aqa_qty');
			// CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'canceled', 'checked'=>false, 'disabled'=>'disabled', 'id'=>'cachk');
			$canqty=null;
			$candate = array('name'=>'candate','id'=>'datepicker2','value'=>$r->trk_canceleddate, 'disabled'=>'disabled');
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$this->input->post('aqc'), 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$this->input->post('canRemarks'), 'disabled'=>'disabled');

		}
		
	}	

	if($rcd->accessname == "Monitor_ITD_AC_AGTI" OR $rcd->accessname == "Monitor_ITD_AC_WEI"){

		//UPDATE BUTTON
		if($r->trk_arrivedstatus == 1 AND $r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 1){
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info', 'disabled'=>'disabled');
		}elseif($r->trk_arrivedstatus == 1 AND $r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 0){
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info', 'disabled'=>'disabled');
		}elseif($r->trk_arrivedstatus == 1 AND $r->trk_acceptedstatus == 0 AND $r->trk_canceledstatus == 1){
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info', 'disabled'=>'disabled');
		}else{
			$update_button = array('name'=>'add', 'value'=>'Update', 'class'=>'btn btn-info');
		}
		// EMAIL BUTTON
		if($r->email_cdel == 1){
			$email_button = array('name'=>'email', 'value'=>'Email', 'class'=>'btn btn-warning', 'disabled'=>'disabled');
		}else{
			$email_button = array('name'=>'email', 'value'=>'Email', 'class'=>'btn btn-warning');
		}

		//ARRIVED CHECKBOX

		if($r->trk_arrivedstatus == 1){
			$arrchk=array('name'=>'arrived','value'=>'arrived','checked'=>true, 'disabled'=>'disabled');
			$arrdate = array('name'=>'arrdate','id'=>'datepicker','value'=>$r->trk_arriveddate, 'disabled'=>'disabled');
			$arrtime = array('name'=>'arr_time','class'=>'arr_time','value'=>$t, 'readonly'=>'readonly');
			$arrremarks = array('name'=>'arrRemarks','size'=>'250','value'=>$r->trk_arrivedremarks, 'disabled'=>'disabled');
		}else{
			$arrchk=array('name'=>'arrived','value'=>'arrived', 'checked'=>false, 'disabled'=>'disabled');
			$arrdate = array('name'=>'arrdate','id'=>'datepicker','value'=>$r->trk_arriveddate, 'disabled'=>'disabled');
			$arrtime = array('name'=>'arr_time','class'=>'arr_time','value'=>$this->input->post('arr_time'), 'disabled'=>'disabled');
			$arrremarks = array('name'=>'arrRemarks','size'=>'250','value'=>$this->input->post('arrRemarks'), 'disabled'=>'disabled');
		}

		//ACCEPTED CHECKBOX

		if($r->trk_acceptedqty <> NULL){
			$aqty = $r->trk_acceptedqty;
		}else{
			$aqty = $r->wi_itemqty;
		}

		if($r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 1){
			//ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accept', 'checked'=>true, 'disabled'=>'disabled');
			$accqty=$r->trk_acceptedqty;
			$accdate = array('name'=>'accdate','id'=>'datepicker1','value'=>$r->trk_accepteddate, 'disabled'=>'disabled');
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2, 'disabled'=>'disabled');
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$r->trk_acceptedremarks, 'disabled'=>'disabled');
			$aqa = array('name'=>'aqa','size'=>'10','value'=>$aqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			//CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'accept', 'checked'=>true, 'disabled'=>'disabled');
			$canqty=$r->trk_canceledqty;
			$candate = array('name'=>'candate','id'=>'datepicker2','value'=>$r->trk_canceleddate, 'disabled'=>'disabled');
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$canqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$r->trk_canceledremarks, 'disabled'=>'disabled');

		}elseif($r->trk_acceptedstatus == 1 AND $r->trk_canceledstatus == 0){
			//ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accepted', 'checked'=>true, 'disabled'=>'disabled');
			$accqty=$r->trk_acceptedqty;
			$accdate = array('name'=>'accdate','id'=>'datepicker1','value'=>$r->trk_accepteddate, 'disabled'=>'disabled');
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2, 'disabled'=>'disabled');
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$r->trk_acceptedremarks, 'disabled'=>'disabled');
			$aqa = array('name'=>'aqa','size'=>'10','value'=>$aqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			//CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'canceled', 'checked'=>false, 'disabled'=>'disabled', 'id'=>'cachk');
			$canqty=$r->trk_canceledqty;
			$candate = array('name'=>'candate','disabled'=>'disabled');
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$this->input->post('aqc'), 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$this->input->post('canRemarks'), 'disabled'=>'disabled');

		}elseif($r->trk_acceptedstatus == 0 AND $r->trk_canceledstatus == 1){
			//ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accepted', 'checked'=>false, 'disabled'=>'disabled', 'id'=>'acchk');
			$accqty=$r->trk_acceptedqty;
			$accdate = array('name'=>'accdate','disabled'=>'disabled');
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$t2, 'disabled'=>'disabled');
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$this->input->post('accRemarks'), 'disabled'=>'disabled');
			$aqa = array('name'=>'aqa','size'=>'10', 'id'=>'aqa_qty', 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled', 'value'=>$this->input->post('aqa'));
			//CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'canceled', 'checked'=>true, 'disabled'=>'disabled');
			$canqty=$r->trk_canceledqty;
			$candate = array('name'=>'candate','id'=>'datepicker2','value'=>$r->trk_canceleddate, 'disabled'=>'disabled');
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$canqty, 'onkeypress'=>'return isNumberKey(event)', 'disabled'=>'disabled');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$r->trk_canceledremarks, 'disabled'=>'disabled');
		}else{
			// ACCEPTED FIELDS
			$accchk=array('name'=>'accepted','value'=>'accepted', 'checked'=>false, 'id'=>'acchk');
			$accqty=$r->wi_itemqty;
			$accdate = array('name'=>'accdate','id'=>'datepicker1','value'=>$this->input->post('accdate'));
			$uldtime = array('name'=>'uld_time','class'=>'uld_time','value'=>$this->input->post('uld_time'));
			$accremarks = array('name'=>'accRemarks','size'=>'250','value'=>$this->input->post('accRemarks'));
			$aqa = array('name'=>'aqa','size'=>'10','value'=>$this->input->post('aqa'), 'onkeypress'=>'return isNumberKey(event)', 'id'=>'aqa_qty');
			// CANCELLED FIELDS
			$canchk=array('name'=>'canceled','value'=>'canceled', 'checked'=>false, 'id'=>'cachk');
			$canqty=null;
			$candate = array('name'=>'candate','id'=>'datepicker2','value'=>$this->input->post('candate'));
			$aqc =  array('name'=>'aqc','size'=>'10','value'=>$this->input->post('aqc'), 'onkeypress'=>'return isNumberKey(event)');
			$canremarks = array('name'=>'canRemarks','size'=>'250','value'=>$this->input->post('canRemarks'));

		}
		
	}	
	
endforeach;
endif;

$source = array(
	'name'=>'source',
	'value'=>$r->wh_name,
	'readonly'=>true
);
$desti= array('name'=>'desti','value'=>$r->CardName,'readonly'=>true);
$ref1 = array('name'=>'ref1','value'=>$r->wi_reftype." ".$r->wi_refnum,'readonly'=>true);
$ref2 = array('name'=>'ref2','value'=>$r->wi_reftype2." ".$r->wi_refnum2,'readonly'=>true);
$ref3 = array('name'=>'ref3','value'=>$r->wi_refnum3,'readonly'=>true);
$qty=array('name'=>'qty','value'=>$r->wi_itemqty,'readonly'=>true, 'id'=>'total_qty');
$desc=array('name'=>'desc','value'=>$r->comm__name,'readonly'=>true);


$rape_type1 = $r->wi_reftype;
$rape_type2 = $r->wi_reftype2;
$dumating = $r->trk_arrivedstatus;
$tinanggap = $r->trk_acceptedstatus;
$inayawan = $r->trk_canceledstatus;


?>

	<link rel="stylesheet" href="<?php echo base_url();?>timepicker/wickedpicker.css"/>
	<link rel="stylesheet" href="<?php echo base_url();?>timepicker/wickedpicker.min.css"/>
	<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/jquery.min.js"></script>

	<link rel="stylesheet" href="<?php echo base_url();?>des/calendar/jquery-ui.css"/>
	<script type="text/javascript" src="<?php echo base_url();?>des/calendar/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>des/calendar/jquery-ui.js"></script>
	<script>

		$(document).ready(function() {

			//ARRIVED DATE
			<?php if($r->trk_arrivedstatus == 0): ?>
				$( "#datepicker" ).val('<?php echo $date_today; ?>');
				<?php if($this->input->post('arrdate')): ?>
					$( "#datepicker" ).val('<?php echo $this->input->post("arrdate"); ?>');
				<?php endif; ?>
			<?php else: ?>
				$( "#datepicker" ).val('<?php echo $r->trk_arriveddate; ?>');
			<?php endif; ?>

			//ACCEPTED DATE
			<?php if($r->trk_acceptedstatus == 0): ?>
				$( "#datepicker1" ).val('<?php echo $date_today; ?>');
				<?php if($this->input->post('accdate')): ?>
					$( "#datepicker1" ).val('<?php echo $this->input->post("accdate"); ?>');
				<?php endif; ?>
			<?php else: ?>
				$( "#datepicker1" ).val('<?php echo $r->trk_accepteddate; ?>');
			<?php endif; ?>

			//CANCELED DATE
			<?php if($r->trk_canceledstatus == 0): ?>
				$( "#datepicker2" ).val('<?php echo $date_today; ?>');
				<?php if($this->input->post('candate')): ?>
					$( "#datepicker2" ).val('<?php echo $this->input->post("candate"); ?>');
				<?php endif; ?>
			<?php else: ?>
				$( "#datepicker2" ).val('<?php echo $r->trk_canceleddate; ?>');
			<?php endif; ?>

			// ARRIVED STATUS
			<?php if(isset($arr_stat)): ?>
				<?php if($arr_stat == 'arrived'): ?>
					$('#archk').attr('checked', true);
				<?php endif; ?>
			<?php else: ?>
				$('#archk').attr('checked', false);
			<?php endif; ?>

			// ACCEPTED STATUS
			<?php if(isset($acc_stat)): ?>
				<?php if($acc_stat == 'accepted'): ?>
					$('#acchk').attr('checked', true);
				<?php endif; ?>
			<?php else: ?>
				$('#acchk').attr('checked', false);
			<?php endif; ?>

			// CANCELED STATUS
			<?php if(isset($can_stat)): ?>
				<?php if($can_stat == 'canceled'): ?>
					$('#cachk').attr('checked', true);
				<?php endif; ?>
			<?php else: ?>
				$('#cachk').attr('checked', false);
			<?php endif; ?>

			$("#acchk").click(function(){
	            var checked = $(this).is(':checked');
	            var total = $('#total_qty').val();
	            if(checked){
	               $('#aqa_qty').val(total); 
	            }else{
	               $('#aqa_qty').val(""); 
	            }
	        });

		});

		$(function() {

			$( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
			$( "#datepicker1" ).datepicker({dateFormat: 'yy-mm-dd'});
			$( "#datepicker2" ).datepicker({dateFormat: 'yy-mm-dd'});

			$('#back_btn').click(function(){
				window.location.href = "<?php echo base_url('main/wh_delivery_trckng_list'); ?>";
			});

		});
	</script>

<?php endforeach;?><?php endif;?>

	<script type="text/javascript">

		function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }

	</script>

	<style type="text/css">

		#pbody label{
			width: 120px;
		}

		#back_btn a{
			color: white;
		}

		#footer{
			   position:fixed;
			   left:0px;
			   bottom:0px;
			   height:40px;
			   width:100%;
			   background:#337ab7;
			   padding: 15px;
			}

			#footer label{
				font-size: 11px;
				line-height: 0px;
				color: white;
				letter-spacing: 1.5px;
				font-weight: normal;
			}

		p{
			font-weight: normal;
		}

		#back_btn{
			text-decoration: none;
			color: white;
		}

		.js #error_msg{
			display: none;
		}

	</style>

	<script type="text/javascript">
		document.documentElement.className = 'js';
	</script>

 
<?php echo form_open(current_url());?>

<h5>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Delivery Monitoring Updates</strong></div>
					<div class="panel-body form-inline" id="pbody">

						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-danger" id="error_msg">
									<p><?php echo validation_errors();?></p>
									<h4><?php if(isset($error)):?><?php echo $error;?><?php endif;?>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<label>Source</label>
							<?php echo form_input($source);?><br/><br/>
							<label>Reference No. 1</label>
							<?php echo form_input($ref1);?><br/><br/>
							<label>Reference No. 3</label>
							<?php echo form_input($ref3);?><br/><br/>
							<label>Arrived</label>
							<?php echo form_checkbox($arrchk);?><br/><br/>
							<label>Date</label>
							<?php echo form_input($arrdate);?><br/><br/>
							<label>Time</label>
							
							<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
							    <?php echo form_input($arrtime); ?>
							</div>

							<br/><br/>
							<label>Remarks</label>
							<?php echo form_textarea($arrremarks);?>
						</div>
						<div class="col-md-4">
							<label>Destination</label>
							<?php echo form_input($desti);?><br/><br/>
							<label>Reference No. 2</label>
							<?php echo form_input($ref2);?><br/><br/>
							<label>Accepted</label>
							<?php echo form_checkbox($accchk);?><br/><br/>
							<label>Date</label>
							<?php echo form_input($accdate);?><br/><br/>
							<label>Unloading / Loading Time</label>
							
							<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
							    <?php echo form_input($uldtime); ?>
							</div>

							<br/><br/>
							<label>Actual Quantity Accepted</label>
							<?php echo form_input($aqa);?><br/><br/>
							<label>Transporter Remarks</label>
							<?php echo form_textarea($accremarks);?><br>
						</div>
						<div class="col-md-4">
							<label>Total Item Quantity</label>
							<?php echo form_input($qty);?><br/><br/>
							<label>Item Description</label>
							<?php echo form_input($desc);?><br/><br/>
							<label>Canceled</label>
							<?php echo form_checkbox($canchk);?><br/><br/>
							<label>Date</label>
							<?php echo form_input($candate);?><br/><br/>
							<label>Actual Quantity Returned</label>
							<?php echo form_input($aqc);?><br/><br/>
							<label>Transporter Remarks</label>
							<?php echo form_textarea($canremarks);?><br/><br/>
							

							<?php $emails = array('name'=>'emails','value'=>$this->uri->segment(3),'readonly'=>true, 'type'=>'hidden'); echo form_input($emails); ?>

							<!-- <input type="hidden" value="<?php $this->uri->segment(3); ?>" name="emails" readonly/> -->

							<!-- <input type="submit" value="Update" class="btn btn-info" name="add" /> -->

							<?php echo form_submit($update_button); ?>

							<?php if($rape_type1 == 'DO' OR $rape_type2 == 'DO'){ 
									if($dumating == 1 AND $tinanggap == 1 AND $inayawan == 1){ 
										// echo form_submit('email', 'Email'); 
										// echo "<button class='btn btn-warning' name='email'>Email</button>";
										// echo "<input type='submit' class='btn btn-warning' name='email' value='Email' />";
										echo form_submit($email_button);
									}elseif($dumating == 1 AND $tinanggap == 0 AND $inayawan == 1){ 
										echo form_submit($email_button);
									}elseif($dumating == 1 AND $tinanggap == 1 AND $inayawan == 0){ 
										echo form_submit($email_button);
									}
								}   
							?>

							<!-- <button class="btn btn-danger" id="back_btn"><?php echo anchor('main/wh_delivery_trckng_list','Back');?></button> -->

								<button class="btn btn-danger" type="button" id="back_btn"><?php echo anchor('main/wh_delivery_trckng_list', 'Back');  ?></button><br><br>

							<!-- <button class="btn btn-danger" id="back_btn">Back</button> -->

						<br>

						</div>
						<div class="col-md-12">
							<div class="alert alert-info">
								<p><strong><em>Note :</em></strong> <strong>LOADING TIME</strong> - is for PICK UP <strong>|</strong> <strong>UNLOADING TIME</strong> - is for TRUCK DELIVERY</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</h5><br/>

<div id="footer">
	<p style="text-align:center;">
		<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
	</p>
</div>

<?php echo form_close();?>


<script type="text/javascript" src="<?php echo base_url();?>timepicker/wickedpicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>timepicker/wickedpicker.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap-clockpicker.min.css">
<script src="<?php echo base_url();?>bootstrap/js/bootstrap-clockpicker.min.js"></script>

<script>
		$(function() {
		
			// $('.arr_time').click(function(){
			// 	$('.arr_time').wickedpicker({twentyFour: true});
			// });

			// $('.uld_time').click(function(){
			// 	$('.uld_time').wickedpicker({twentyFour: true});
			// });
			
			$('.clockpicker').clockpicker({
			    placement: 'top',
			    align: 'left',
			    donetext: 'Done'
			});

		});
</script>


<script type="text/javascript">
		
	<?php if(isset($validation_errors) OR isset($error)): ?>
				$('#error_msg').show();
			<?php else: ?>
				$('#error_msg').hide();
			<?php endif; ?>	

</script>