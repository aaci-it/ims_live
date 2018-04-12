<?php $this->load->view('bootstrap_files');?>

<?php echo form_open();?>

	<link rel="stylesheet" href="<?php echo base_url();?>timepicker/wickedpicker.css"/>
	<link rel="stylesheet" href="<?php echo base_url();?>timepicker/wickedpicker.min.css"/>

	<!-- DT Picker -->
 	<link rel="stylesheet" href="<?php echo base_url();?>DTPicker/css/bootstrap-datetimepicker.min.css" >
    <script src="<?php echo base_url();?>DTPicker/js/bootstrap-datetimepicker.min.js"></script>
    <!-- End of File -->

	<script type="text/javascript">

	//ALLOW NUMBER ON TEXTFIELD
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

		body{
			background: #ebebeb;
			margin: 0;
			padding: 0;
		}

		#pbody label{
			width: 175px;
			font-weight: normal;
		}

		.notes{
			background: #f0f0f0;
			padding: 10px;
		}

		select, input[type="text"]{
			width: 185px;
		}

		.panel-heading a{
			color: gray;
		}

		.panel-heading a:hover{
			color: white;
			text-decoration: none;
		}
		#truck_list{
			width: 170px;
		}

		#footer{
			position:fixed;
			left:0px;
			bottom:0px;
			height:40px;
			width:100%;
			background:#337ab7;
			padding: 10px;
		}

		#footer label{
			font-size: 11px;
			line-height: 0px;
			color: white;
			letter-spacing: 1.5px;
			font-weight: normal;
		}

		#print a{
			color: white;
			text-decoration: none;
		}

		.navbar-right{
			margin-right: 15px;
		}

		.js #sub_del_type_out_all, #sub_del_type_out_warehouse,
			#error_msg, #cust_code, #whse_code, #item_code, #truck_seal, #lbl_tseal,
			#truck_seal_no, #lbl_tseal_no, #dialog-message, #print_dr_pdf, #print_wis_pdf, #info_msg, #dialog-error,
			#dialog-error-date{
			display: none;
	
		}

		.ui-dialog-titlebar-close {
		    visibility: hidden;
		}

		input[type="submit"]{
			border-radius: 0px;
			width: 150px;
			font-family: segoe UI;
			font-size: 16px;
			font-weight: bold;
		}

		#error_msg, #info_msg{
			border-radius: 0px;
		}

		#error_msg a, #info_msg a{
			margin-right: 20px;
		}

	</style>

	<script type="text/javascript">
		document.documentElement.className = 'js';
	</script>

	<?php if(isset($records)): foreach($records as $r): ?>

	<?php

		date_default_timezone_set("Asia/Manila");

		if($r->DefDocType == "DR"){
			$deltype = array('name'=>'deltype', 'class'=>'form-control', 'value'=>'Customer Delivery', 'readonly'=>'readonly');
		}elseif($r->DefDocType == "WIS"){
			$deltype = array('name'=>'deltype', 'class'=>'form-control', 'value'=>'Pick-Up', 'readonly'=>'readonly');
		}else{
			$deltype = array('name'=>'deltype', 'class'=>'form-control', 'value'=>$this->input->post('deltype'), 'readonly'=>'readonly');
		}
		$datetime = date('Y-m-d');  

		$source = array('name'=>'source', 'class'=>'form-control', 'value'=>$r->Source, 'readonly'=>'readonly');
		// $cust_name = array('name'=>'cust_name', 'class'=>'form-control', 'value'=>$r->card_name, 'readonly'=>'readonly');
		
		$cust_name = $r->CardName;

		$ref = array('name'=>'ref', 'class'=>'form-control', 'value'=>$this->input->post('ref'), 'readonly'=>'readonly');
		$ref2 = array('name'=>'ref2', 'class'=>'form-control', 'value'=>$this->input->post('ref2'), 'required'=>'required');
		$ref3 = array('name'=>'ref3', 'class'=>'form-control', 'value'=>$r->PepsiSDRNo);
		$ref4 = array('name'=>'ref4', 'class'=>'form-control', 'value'=>$this->input->post('ref4'));

		if($r->TransPO <> ""){
			$po_num = $r->TransPO;
		}else{
			$po_num = $r->MotherPO;
		}

		$PONum = array('name'=>'PONum', 'class'=>'form-control', 'value'=>$po_num, 'readonly'=>'readonly');

		// $whitem = array('name'=>'whitem', 'class'=>'form-control', 'value'=>$r->item_desc, 'readonly'=>'readonly');
		$whitem = $r->Dscription;


		$uom = strtoupper($r->UnitMsr);
		$uom = array('name'=>'uom', 'class'=>'form-control', 'value'=>$uom, 'readonly'=>'readonly');

		$qty = array('name'=>'whqty', 'id'=>'itemqty', 'class'=>'form-control', 'value'=>$this->input->post('whqty'), 'onkeypress'=>'return isNumberKey(event)', 'required'=>'required');
		
		if(isset($do_qty_remain)):
			$doqty_no = $do_qty_remain['qty_remain'];
			$doqty_no = number_format($doqty_no, 2);
		else:
			$doqty_no = $r->DoQty;
			$doqty_no = number_format($doqty_no, 2);			
		endif;

		if($doqty_no == 0.00){
			$doqty_no = $r->DoQty;
			$doqty_no = number_format($doqty_no, 2);		
		}

		$doqty = array('name'=>'doqty', 'id'=>'doqty', 'class'=>'form-control', 'value'=>$doqty_no, 'readonly'=>'readonly');
		$intransit = array('name'=>'intransit', 'id'=>'intransit', 'class'=>'form-control', 'value'=>$this->input->post('intransit'), 'readonly'=>'readonly');
		$doctype2 = array('name'=>'doctype2', 'class'=>'form-control', 'value'=>$r->DefDocType);
		$exddate = array('name'=>'exddate', 'class'=>'form-control', 'value'=>$r->SAPExpDeldate, 'readonly'=>'readonly');
		$dodate = array('name'=>'dodate', 'class'=>'form-control', 'value'=>$r->Dodate, 'readonly'=>'readonly');
		$ddate = array('name'=>'ddate', 'value'=>$this->input->post('ddate'), 'id'=>'datepicker','data-format'=>'yyyy-MM-dd', 'required'=>'required');
		// $dr_remarks = array('name'=>'dr_remarks', 'class'=>'form-control', 'value'=>$this->input->post('dr_remarks'));
		// $do_remarks = array('name'=>'do_remarks', 'class'=>'form-control', 'value'=>$r->do_remarks, 'readonly'=>'readonly');
			
		$do_remarks = $r->DoRemarks;	

		$creation_date = array('name'=>'creation_date', 'class'=>'form-control', 'value'=>$datetime, 'readonly'=>'readonly');
		// $location = array('name'=>'location', 'class'=>'form-control', 'value'=>$r->location, 'readonly'=>'readonly');
		
		$location = $r->Location;

		$trk_temp = $r->TruckCode;

		if($trk_temp == "AGTI"){
			$trk_comp = "AGTI";
		}elseif($trk_temp == "WEI"){
			$trk_comp = "WEI";
		}elseif($trk_temp == "PICK"){
			$trk_comp = "PICK UP";
		}else{
			$trk_comp = "AACI TRUCK";
		}

		$truck_company = array('name'=>'truck_company', 'class'=>'form-control', 'value'=>$trk_comp, 'readonly'=>'readonly');
		
		$tdrvr = array('name'=>'tdrvr', 'class'=>'form-control', 'value'=>$this->input->post('tdrvr'), 'required'=>'required');
		$tpnum = array('name'=>'tpnum', 'class'=>'form-control', 'value'=>$this->input->post('tpnum'), 'required'=>'required');


		if($this->input->post('shipdate')){
			$sdate = $this->input->post('shipdate');
		}else{
			$sdate = $datetime;
		}

		$shipdate = array('name'=>'shipdate', 'value'=>$sdate, 'data-format'=>'yyyy-MM-dd', 'required'=>'required');

		$trucktime = array('name'=>'trucktime', 'class'=>'form-control', 'value'=>$this->input->post('trucktime'), 'required'=>'required', 'style'=>'width:160px;');
		$shiptime = array('name'=>'shiptime', 'class'=>'form-control', 'value'=>$this->input->post('shiptime'), 'required'=>'required', 'style'=>'width:160px;');

		$cust_code = array('name'=>'cust_code', 'class'=>'form-control', 'value'=>$r->CardCode, 'id'=>'cust_code');
		$item_code = array('name'=>'item_code', 'class'=>'form-control', 'value'=>$r->ItemCode, 'id'=>'item_code');
		$whse_code = array('name'=>'whse_code', 'class'=>'form-control', 'value'=>$r->WhsCode, 'id'=>'whse_code');

		// $tseal = $r->truck_seal;
	
		$catno = array('name'=>'catno', 'class'=>'form-control', 'value'=>$r->CatalogNo, 'id'=>'catno');

		// $doseriesno = array('name'=>'doseriesno', 'class'=>'form-control', 'value'=>$r->U_DONo, 'id'=>'doseriesno', 'readonly'=>'readonly');

		$doseriesno = array('name'=>'doseriesno', 'class'=>'form-control', 'value'=>$this->input->post('doseriesno'), 'id'=>'doseriesno', 'readonly'=>'readonly');
		
	?>

	<?php endforeach; ?>

	<?php else: ?>

	<?php



		$no_record_found = 1;
		$no_rec = "No Record Found";
		// $deltype_list = array('DO_01'=>'Customer Delivery', 'DO_05'=>'Pick-Up');

		$deltype = array('name'=>'deltype', 'class'=>'form-control', 'value'=>$this->input->post('deltype'), 'readonly'=>'readonly');

		$datetime = date('Y-m-d');   

		$source = array('name'=>'source', 'class'=>'form-control', 'value'=>$this->input->post('source'), 'readonly'=>'readonly');
		// $cust_name = array('name'=>'cust_name', 'class'=>'form-control', 'value'=>$this->input->post('cust_name'), 'readonly'=>'readonly');
		
		$cust_name = $this->input->post('cust_name');

		$ref = array('name'=>'ref', 'class'=>'form-control', 'value'=>$this->input->post('ref'));
		$ref2 = array('name'=>'ref2', 'class'=>'form-control', 'value'=>$this->input->post('ref2'));
		$ref3 = array('name'=>'ref3', 'class'=>'form-control', 'value'=>$this->input->post('ref3'));
		$ref4 = array('name'=>'ref4', 'class'=>'form-control', 'value'=>$this->input->post('ref4'));

		$PONum = array('name'=>'PONum', 'class'=>'form-control', 'value'=>$this->input->post('PONum'), 'readonly'=>'readonly');
		// $whitem = array('name'=>'whitem', 'class'=>'form-control', 'value'=>$this->input->post('whitem'), 'readonly'=>'readonly');
			
		$whitem = $this->input->post('whitem');

		$uom = array('name'=>'uom', 'class'=>'form-control', 'value'=>$this->input->post('uom'), 'readonly'=>'readonly');
		$qty = array('name'=>'whqty', 'id'=>'itemqty', 'class'=>'form-control', 'value'=>$this->input->post('whqty'), 'onkeypress'=>'return isNumberKey(event)');
		$doqty = array('name'=>'doqty', 'id'=>'doqty', 'class'=>'form-control', 'value'=>$this->input->post('doqty'), 'readonly'=>'readonly');
		$intransit = array('name'=>'intransit', 'id'=>'intransit', 'class'=>'form-control', 'value'=>$this->input->post('intransit'), 'readonly'=>'readonly');
		$doctype2 = array('name'=>'doctype2', 'class'=>'form-control', 'value'=>$this->input->post('doctype2'));
		$exddate = array('name'=>'exddate', 'class'=>'form-control', 'value'=>$this->input->post('exddate'), 'readonly'=>'readonly');
		$dodate = array('name'=>'dodate', 'class'=>'form-control', 'value'=>$this->input->post('dodate'), 'readonly'=>'readonly');
		$ddate = array('name'=>'ddate', 'value'=>$this->input->post('ddate'), 'data-format'=>'yyyy-MM-dd', 'id'=>'datepicker');
		// $dr_remarks = array('name'=>'dr_remarks', 'class'=>'form-control', 'value'=>$this->input->post('dr_remarks'));
		// $do_remarks = array('name'=>'do_remarks', 'class'=>'form-control', 'value'=>$this->input->post('do_remarks'), 'readonly'=>'readonly');
		
		$do_remarks = $this->input->post('do_remarks');

		$creation_date = array('name'=>'creation_date', 'class'=>'form-control', 'value'=>$datetime, 'readonly'=>'readonly');
		// $location = array('name'=>'location', 'class'=>'form-control', 'value'=>$this->input->post('location'), 'readonly'=>'readonly');
		
		$location = $this->input->post('location');

		$truck_company = array('name'=>'truck_company', 'class'=>'form-control', 'value'=>$this->input->post('truck_company'), 'readonly'=>'readonly');
		$tdrvr = array('name'=>'tdrvr', 'class'=>'form-control', 'value'=>$this->input->post('tdrvr'));
		$tpnum = array('name'=>'tpnum', 'class'=>'form-control', 'value'=>$this->input->post('tpnum'));
		$shipdate = array('name'=>'shipdate', 'value'=>$this->input->post('shipdate'), 'data-format'=>'yyyy-MM-dd');
		$trucktime = array('name'=>'trucktime', 'class'=>'form-control', 'value'=>$this->input->post('trucktime'), 'style'=>'width:160px;');
		$shiptime = array('name'=>'shiptime', 'class'=>'form-control', 'value'=>$this->input->post('shiptime'), 'style'=>'width:160px;');

		$cust_code = array('name'=>'cust_code', 'class'=>'form-control', 'value'=>$this->input->post('card_code'), 'id'=>'cust_code');
		$item_code = array('name'=>'item_code', 'class'=>'form-control', 'value'=>$this->input->post('item_code'), 'id'=>'item_code');
		$whse_code = array('name'=>'whse_code', 'class'=>'form-control', 'value'=>$this->input->post('whse_code'), 'id'=>'whse_code');

		// $already_encode = "DO Number is already encoded";

		$catno = array('name'=>'catno', 'class'=>'form-control', 'value'=>$this->input->post('catno'), 'id'=>'catno');
		
		$doseriesno = array('name'=>'doseriesno', 'class'=>'form-control', 'value'=>$this->input->post('doseriesno'), 'id'=>'doseriesno', 'readonly'=>'readonly');

	?>

	<?php endif; ?>

	<h5>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;">
						<strong>
							<?php echo anchor('main/wh_delivery_item_in/'.$this->uri->segment(3),'Delivery In');?> |
							<?php echo anchor('main/wh_delivery_item_reserve/'.$this->uri->segment(3),'Delivery Out Manual');?> |
							Delivery Out HO |
							<?php echo anchor('main/wh_delivery_item_mm/'.$this->uri->segment(3),'Material Management');?> |
							<?php echo anchor('main/tin_tout/'.$this->uri->segment(3),'Transfer In and Out');?>
						</strong>
					</div>
					<div class="panel-body form-inline" id="pbody">
					
						<div class="col-md-12">
							<div class="alert alert-danger" id="error_msg">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong><?php echo validation_errors(); ?></strong>
								<strong><?php if(isset($error)): ?><?php echo $error; ?><?php endif; ?></strong>
							</div>
						</div>

						<div class="col-md-12">
							<div class="alert alert-info" id="info_msg">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong><?php echo $info; ?></strong>
							</div>
						</div>

						<div class="col-md-7">
							
							<!-- WAREHOUSE CODE FOR SHOWING OF DELIVERY OUT PAGE -->
							<input type="hidden" name="wh_code_dout" value="<?php echo $this->uri->segment(3); ?>">

							<!-- HIDDEN FIELD FOR CUSTOMER CODE -->
							<?php echo form_input($cust_code); ?>

							<!-- HIDDEN FIELD FOR ITEM CODE -->
							<?php echo form_input($item_code); ?>

							<!-- HIDDEN FIELD FOR WAREHOUSE CODE -->
							<?php echo form_input($whse_code); ?>

							<label>Source *</label>
							<?php echo form_input($source); ?><br><br>
							<label>Type *</label>
							<input type="text" class="form-control" name="delout" value="Delivery Out" readonly>&nbsp;&nbsp;&nbsp;&nbsp;
							<!-- <input type="text" class="form-control" name="deltype" value="Customer Delivery" readonly> -->
							<!--<?php echo form_dropdown('deltype', $deltype_list, $this->input->post('deltype'), 'class="form-control"'); ?>-->
							<?php echo form_input($deltype); ?>
							<br/><br/>
							<label>Customer | Warehouse *</label>

							<!-- <?php echo form_input($cust_name); ?><br><br> -->
							<textarea name="cust_name" class="form-control" readonly cols="50"><?php echo $cust_name; ?></textarea>
							<br/><br/>
							<label>Reference No. 1 *</label>
							<input type="text" class="form-control" name="doctype1" value="DO" readonly>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_input($ref); ?>&nbsp;&nbsp;
							<button class="btn btn-warning btn-sm" name="get_do" type="submit" value="get" formnovalidate>Get DO</button>
							<br/><br/>
							<label>Reference No. 2</label>
							<?php echo form_input($doctype2); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_input($ref2);?>&nbsp;&nbsp;
							<!-- <button class="btn btn-success btn-xs" name="check_intransit" type="submit" value="check">Check Intransit</button> -->
							<br/><br/>
							<label>Reference No. 3</label>
							<?php echo form_input($ref3); ?><br/><br/>

							<label>Reference No. 4</label>
							<?php echo form_input($ref4); ?><br/><br/>

							<label>PO | SC Number</label>
							<?php echo form_input($PONum);?><br/><br/>
							<label>Item *</label>
							<!-- <?php echo form_input($whitem); ?> -->

							<textarea name="whitem" class="form-control" readonly cols="50"><?php echo $whitem; ?></textarea>

							<br/><br/>
							<label>Unit of Measurement *</label>
							<?php echo form_input($uom); ?><br/><br/>
							<label>Actual Quantity Loaded *</label>
							<?php echo form_input($qty);?> 
							<br/><br/>
							<label>DO / ITO Quantity</label>
							<?php echo form_input($doqty);?><br/><br/>
							<label>In-Transit</label>
							<?php echo form_input($intransit);?><br/><br/>
						
							<label>DO | SO Date</label>
							<?php echo form_input($dodate);?>
							<br/><br/>

							<label>Expected Delivery | <br/> Pick-up Date</label>
							<?php echo form_input($exddate);?>
							<br/><br/>

							<label>Posting | Loading Date</label>
	        				<div class='input-group date' id='datepicker'>
				                	<!-- <?php echo form_input($ddate);?> -->
				                	<?php if($this->input->post('ddate')){$ddate_01 = $this->input->post('ddate');}else{$ddate_01 = date('Y-m-d');} ?>
				                	<input type="text" name="ddate" value="<?php echo $ddate_01; ?>" class="form-control" id="datepicker" data-format="yyyy-MM-dd">
									<span class="add-on input-group-addon">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div>
							<br/><br/>
							<label>Warehouse Remarks</label>
							<!-- <?php echo form_textarea($dr_remarks);?> -->

							<textarea name="dr_remarks" class="form-control" cols="50" rows="6"><?php echo $this->input->post('dr_remarks'); ?></textarea>

							<br/><br/>
							
							<label>DO Remarks</label>
							<!-- <?php echo form_textarea($do_remarks);?> -->

							<textarea name="do_remarks" class="form-control" readonly cols="50" rows="6"><?php echo $do_remarks; ?></textarea>

							<br/><br/>
							
						</div>
						<div class="col-md-4">
							<br/>

							<label>Transaction No.</label>
							<?php if(isset($trans_no)): foreach($trans_no as $tn): ?>
							<input type="text" name="trans_no" value="<?php echo $tn->sn_nextnum ?>" class="form-control" readonly><br><br>
							<?php endforeach; ?>
							<?php endif; ?>

							<label>DO Series No</label>
							<?php echo form_input($doseriesno); ?><br><br>

							<label>Creation Date</label>
							<?php echo form_input($creation_date);?><br/><br/>

							<label>Production Code</label>
							<input type="text" name="pbatch_code" value="<?php echo $this->input->post('pbatch_code') ?>" class="form-control"><br><br>
							
							<label>Item Catalog No.</label>
							<?php echo form_input($catno);?><br/><br/>
						
							<label>Pick-up| Delivery Location *</label>
							<!-- <?php echo form_input($location);?> -->

							<textarea name="location" class="form-control" readonly cols="30"><?php echo $location; ?></textarea>

							<br/><br/>
							<label>Truckers Arrival Time *</label>
						
							<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
							    <?php echo form_input($trucktime); ?>
							    <span class="input-group-addon">
							        <span class="glyphicon glyphicon-time"></span>
							    </span>
							</div>

							<br/><br/>
							<label>Truck Company *</label>
							<?php echo form_input($truck_company);?><br><br>
							<label>Truck Plate Number *</label>
							<?php echo form_input($tpnum);?><br/><br/>
							<label>Truck Driver *</label>
							<?php echo form_input($tdrvr);?><br/><br/>
							
							<label>Shipment | Pick-up Date</label>
	        				<div class='input-group date' id='datepicker1'>
				                	<!-- <?php echo form_input($tdate);?> -->
				                	<?php if($this->input->post('shipdate')){$sdate_01 = $this->input->post('shipdate');}else{$sdate_01 = date('Y-m-d');} ?>
				                	<input type="text" name="shipdate" value="<?php echo $sdate_01; ?>" class="form-control" id="datepicker1" data-format="yyyy-MM-dd">
									<span class="add-on input-group-addon">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div>
							<br/><br/>

							<label>Shipment | Pick-up Time</label>

							<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
								<?php echo form_input($shiptime); ?>
							    <span class="input-group-addon">
							        <span class="glyphicon glyphicon-time"></span>
							    </span>
							</div><br><br>

							<label id="lbl_tseal">Require Truck Seal</label>

							<?php $tseal_list = array(1=>'Yes', 0=>'No'); ?>

							<?php echo form_dropdown('truck_seal', $tseal_list, $this->input->post('truck_seal'), 'id="truck_seal"'); ?>

							<br/><br/>

							<label id="lbl_tseal_no">Truck Seal No.</label>
							<input type="text" name="truck_seal_no" value="<?php echo $this->input->post('truck_seal_no') ?>" id="truck_seal_no">

							<br/><br/>

							<div class="well">

								<label><b><em>For Printing DR / WIS</em></b></label><br>

								<!-- <label>Note 1</label>
								<input type="text" name="note1" value="<?php echo $this->input->post('note1'); ?>" class="form-control" />
								<br><br>
								<label>Note 2</label>
								<input type="text" name="note2" value="<?php echo $this->input->post('note2'); ?>" class="form-control"/>
								<br><br> -->
								<label>Prepared By:</label>
								<input type="text" list="plist" name="prepared_by" value="<?php echo $this->input->post('prepared_by'); ?>" class="form-control" style="text-transform: uppercase;" autocomplete="off"/>
								<datalist id="plist">
									<?php if(isset($prepared_list)): foreach($prepared_list as $pl): ?>
										<option><?php echo $pl->prepared_by; ?></option>
									<?php endforeach; ?>
									<?php endif; ?>
								</datalist>	
								<br><br>
								<label>Checked By:</label>
								<input type="text" list="clist" name="checked_by" value="<?php echo $this->input->post('checked_by'); ?>" class="form-control" style="text-transform: uppercase;" autocomplete="off"/>
								<datalist id="clist">
									<?php if(isset($checked_list)): foreach($checked_list as $cl): ?>
										<option><?php echo $cl->checked_by; ?></option>
									<?php endforeach; ?>
									<?php endif; ?>
								</datalist>
								<br><br>
								<label>Guard Duty / Released By:</label>
								<input type="text" list="glist" name="guard_duty" value="<?php echo $this->input->post('guard_duty'); ?>" class="form-control" style="text-transform: uppercase;" autocomplete="off"/>
								<datalist id="glist">
									<?php if(isset($guard_list)): foreach($guard_list as $gl): ?>
										<option><?php echo $gl->guard_duty; ?></option>
									<?php endforeach; ?>
									<?php endif; ?>
								</datalist>	
								<br><br>
								<label>Seal No</label>
								<input type="text" name="seal" value="<?php echo $this->input->post('seal'); ?>" class="form-control" />
								<br><br>
								<label>Way Bill No.</label>
								<input type="text" name="ref_print" value="<?php echo $this->input->post('ref_print'); ?>" class="form-control" />
								
								<br><br>
								<label>Other Remarks</label>
								
								<textarea cols="50" rows="5" name="other_rmrks" class="form-control"><?php echo $this->input->post('other_rmrks'); ?></textarea>
								<br><br>

							</div>
							
							<input type="submit" name="submit_do" id="submit_do" class="btn btn-info" value="Submit" noformvalidate/><br><br>
							<!-- <button type="button" id="print" class="btn btn-warning"><?php echo anchor('main/print_dr/'.'126499', 'Print DR'); ?></button><br/><br/><br/> -->

							<div class="alert alert-info">
								<b>REQUIRED DOCUMENTS IN TRANSACTION:<br><br>
								Warehouse to Warehouse</b><br>
								*Reference No. 1: ITO<br><br>
								<b>Warehouse to Customer</b><br>
								*Reference No. 1: DO<br><br>
								<b>CY to Customer</b><br>
								*Reference No. 1: DO<br><br>
								<b>Other References</b><br>
								*Reference No. 3: Ex. SDR No.<br><br>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<div id="footer">
		<p style="text-align:center;">
			<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
		</p>
	</div>

<?php echo form_close();?>

<?php 

	$trans_no = "";
	$trans_no = $this->uri->segment(5);
	
?>

<a id="print_dr_pdf" href="<?php echo base_url(). 'index.php/main/print_dr_pdf/'.$trans_no; ?>" target="_blank">
	#
</a>
<a id="print_wis_pdf" href="<?php echo base_url(). 'index.php/main/print_wis_pdf/'.$trans_no; ?>" target="_blank">
	#
</a>

<div id="dialog-message" title="Delivery Out Complete">
	<p>
		<div id="success_msg">
	   		<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 10px 0;"></span>
	    	Records has been successfully saved.
	   </div>
	</p>
</div>

<div id="dialog-error" title="Transaction No. Error">
	<p>
	  <div id="success_msg">
	  <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 10px 0;"></span>
	    	No Set Transaction Number for this Transaction Type.
	  </div>
	</p>
</div>

<div id="dialog-error-date" title="Transaction No. Error">
	<p>
	  <div id="success_msg">
	  <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 10px 0;"></span>
	    	Series for this transaction type reach its validity period
	  </div>
	</p>
</div>


<br><br>


<script type="text/javascript" src="<?php echo base_url();?>timepicker/wickedpicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>timepicker/wickedpicker.min.js"></script>

<script>
		$(function() {

			
			<?php if($this->uri->segment(4) == 'dout_01_DR'): ?>
				$("#dialog-message").show();
				$("#dialog-message").dialog({
					modal: true,
					buttons: {
						Ok: function() {
					  		$(this).dialog( "close" );
					  		var x = window.location.href.slice(0,-10);
				          	window.location.href = x;

				          	$("#print_dr_pdf")[0].click();
						}
					}
				});
			<?php elseif($this->uri->segment(4) == 'dout_01_WIS'): ?>

				$("#dialog-message").show();
				$("#dialog-message").dialog({
					modal: true,
					buttons: {
						Ok: function() {
					  		$(this).dialog( "close" );
					  		var x = window.location.href.slice(0,-11);
				          	window.location.href = x;

				          	$("#print_wis_pdf")[0].click();
						}
					}
				});
			<?php endif; ?>

			<?php 

				$temp = $this->uri->segment(3);
				$slen = 0;

				if(strlen($temp) == 2){
					$slen = -30;
				}else{
					$slen = -29;
				}

			 ?>

			<?php if(!isset($trans_no)): ?>
				$("#dialog-error").show();
				$("#dialog-error").dialog({
					modal: true,
					buttons: {
						Ok: function() {
					  		$(this).dialog( "close" );
					  		var x = window.location.href.slice(0, <?php echo $slen ?>);
				          	window.location.href = x;
						}
					}
				});
			<?php endif; ?>

			<?php if(isset($cvdate_dout_ho)): ?>
				$("#dialog-error-date").show();
				$("#dialog-error-date").dialog({
					modal: true,
					buttons: {
						Ok: function() {
					  		$(this).dialog( "close" );
					  		var x = window.location.href.slice(0, <?php echo $slen ?>);
				          	window.location.href = x;
						}
					}
				});
			<?php endif; ?>


			$('#shiptime').click(function(){
				$('#shiptime').wickedpicker({twentyFour: true});
			});

			$('#trucktime').click(function(){
				$('#trucktime').wickedpicker({twentyFour: true});
			});

			$('.clockpicker').clockpicker();


			<?php if(validation_errors() OR isset($error) OR isset($already_encode)): ?>
				$('#error_msg').show();
			<?php else: ?>
				$('#error_msg').hide();
			<?php endif; ?>

			<?php if(isset($info)): ?>
				$('#info_msg').show();
			<?php else: ?>
				$('#info_msg').hide();
			<?php endif; ?>

			$('#datepicker').datetimepicker({
		      pickTime: false
		    });
		    $('#datepicker1').datetimepicker({
		      pickTime: false
		    });
		    $('#datepicker2').datetimepicker({
		      pickTime: false
		    });

		     $('#datepicker3').datetimepicker({
		      pickTime: false
		    });

		     $('#datepicker4').datetimepicker({
		      pickTime: false
		    });



		});
</script>

<!-- PLUG-INS FOR SUCCESS MESSAGEBOX -->
 <link rel="stylesheet" href="<?php echo base_url() ?>jquery-ui/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="<?php echo base_url() ?>jquery-ui/jquery-ui.js"></script>
<!-- END OF FILE -->

<script type="text/javascript">

	  	$('input').on('keypress', function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9-]+$");
		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		    if (!regex.test(key)) {
		       event.preventDefault();
		       return false;
		    }
		});
	  	
	  
	    // ADDING TRUCKER LIST -----------------
	    $("#addtruck").click(function(){
	    
	    	$("#newtruck").attr("readonly", false);
	    	$("#newtruck").focus();

	    	if($("#newtruck").val() == ""){

	    	}else{
	    		var newtruck = $("#newtruck").val();
	    		newtruck = newtruck.toUpperCase();

	    		var exists = false;
				$('#truck_list option').each(function(){
				    if (this.value == newtruck) {
				        exists = true;
				        return false;
				    }
				});

				if(exists == false){
					$("#truck_list").append('<option value="' + newtruck + '" selected>' + newtruck + '</option>');
	    			$("#newtruck").attr("readonly", true);
	    			$("#newtruck").val("");
				}
	    		
	    	}

	    });

	    // DISABLE ADD TRUCK FIELD
	     $("#truck_list").click(function(){

	     	$("#newtruck").attr("readonly", true);

	     });

	    // ADDING UOM LIST -----------------
	    $("#adduom").click(function(){
	    
	    	$("#newuom").attr("readonly", false);
	    	$("#newuom").focus();

	    	if($("#newuom").val() == ""){

	    	}else{
	    		var newuom = $("#newuom").val();
	    		newuom = newuom.toUpperCase();

	    		var exists = false;
				$('#uom option').each(function(){
				    if (this.value == newuom) {
				        exists = true;
				        return false;
				    }
				});

				if(exists == false){
					$("#uom").append('<option value="' + newuom + '" selected>' + newuom + '</option>');
	    			$("#newuom").attr("readonly", true);
	    			$("#newuom").val("");
				}
	    		
	    	}

	    });

	     // DISABLE ADD UOM FIELD
	     $("#uom").click(function(){

	     	$("#newuom").attr("readonly", true);

	     });


	     // $('#sub_del_type_out_customer').hide();
	     $('#sub_del_type_out_all').hide();
	     $('#sub_del_type_out_warehouse').hide();

	     $('#sub_del_type_out').change(function(){
	     	if($('#sub_del_type_out').val() == "DO_01" || $('#sub_del_type_out').val() == "DO_04"){
	     		$('#sub_del_type_out_customer').show();
	     		$('#sub_del_type_out_warehouse').hide();
	     		$('#sub_del_type_out_all').hide();
	     	}else if($('#sub_del_type_out').val() == "DO_02" || $('#sub_del_type_out').val() == "DO_03" ){
	     		$('#sub_del_type_out_warehouse').show();
	     		$('#sub_del_type_out_customer').hide();
	     		$('#sub_del_type_out_all').hide();
	     	}
	     });

		 $('#itemqty').blur(function(){

			var doqty = $('#doqty').val();
			var itemqty = $('#itemqty').val();
			var intransit = doqty - itemqty;

			$('#intransit').val(intransit);

		});

		$('#doqty').blur(function(){

			var doqty = $('#doqty').val();
			var itemqty = $('#itemqty').val();
			var intransit = doqty - itemqty;

			$('#intransit').val(intransit);

		});

		$('#cust_code').hide();
		$('#item_code').hide();
		$('#whse_code').hide();

		$('#truck_seal').hide();
		$('#lbl_tseal').hide();
		$('#truck_seal_no').hide();

		<?php if($tseal == 1): ?>
			$('#truck_seal').show();
			$('#truck_seal').val(1);
			$('#lbl_tseal').show();
			$('#lbl_tseal_no').show();
			$('#truck_seal_no').show();
			$("#truck_seal_no").attr("required", true);
		<?php else: ?>
			$('#truck_seal').hide();
			$('#lbl_tseal').hide();
			$('#lbl_tseal_no').hide();
			$('#truck_seal_no').hide();
		<?php endif; ?>

		$('#truck_seal').change(function(){
			if($('#truck_seal').val() == 0){
				$('#truck_seal_no').hide();
				$('#lbl_tseal_no').hide();
				$("#truck_seal_no").attr("required", false);
			}else{
				$('#truck_seal_no').show();
				$('#lbl_tseal_no').show();
				$("#truck_seal_no").attr("required", true);
			}
		});
	  
</script>
