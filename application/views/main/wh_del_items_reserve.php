<?php $this->load->view('bootstrap_files');?>

<!--<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>-->

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-editable-select.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/jquery-editable-select.css">

<?php 

date_default_timezone_set("Asia/Manila");

if(isset($sapdetails)): foreach($sapdetails as $r):
$exdate = $r->ExpectedDelDate;
$conexdate = $exdate->format('Y-m-d');
//$qty = array('name'=>'whqty','maxlength'=>20,'maxvalue'=>20,'value'=>$r->Qty,'readonly'=>true, 'required'=>'required');
$location = array('name'=>'location','maxlength'=>50,'maxvalue'=>50,'value'=>$r->Destination,'readonly'=>true);
// $addr=array('name'=>'remarks','maxlength'=>'250','value'=>$r->Remarks,'readonly'=>true);

$addr = $r->Remarks;

// $do_remarks=array('name'=>'do_remarks','maxlength'=>'250','value'=>$r->do_remarks,'readonly'=>true);

$do_remarks = $r->do_remarks;

$tcmpny=array('name'=>'tcom','maxlength'=>'50','maxvalue'=>'50','value'=>$r->Trucker,'readonly'=>true);
$ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('ref2'));
$PONum=array('name'=>'PONum','maxlength'=>50,'maxvalue'=>50,'value'=>$r->PONo,'readonly'=>true);
// $uom=array('name'=>'uom','maxlength'=>50,'maxvalue'=>50,'value'=>$r->UoM,'readonly'=>true);
$tdate = array('name'=>'tdate','value'=>$cdate,'id'=>'datepicker1', 'data-format'=>'yyyy-MM-dd');
$tcmpny=array('name'=>'tcom','maxlength'=>'50','maxvalue'=>'50','value'=>$r->Trucker,'readonly'=>true);
$rdate = array('name'=>'rdate','value'=>$cdate,'readonly'=>'true','readonly'=>true);
$ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20,'onchange'=>"main/myFunction(this.value)",'value'=>$this->input->post('ref'),'readonly'=>true); 

$itemid = array('name'=>'itemid', 'value'=>$this->input->post('itemid'), 'id'=>'itemid', 'placeholder'=>'Enter Item Code');

// $doqty = array('name'=>'doqty','id'=>'doqty', 'maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('doqty'), 'onkeypress'=>'return isNumberKey(event)');

if(isset($check_intransit)){
	foreach($check_intransit as $r){
		$doqty = array('name'=>'doqty','id'=>'doqty', 'maxlength'=>20,'maxvalue'=>20,'value'=>$r->wi_intransit, 'onkeypress'=>'return isNumberKey(event)', 'readonly'=>'readonly');
	}
}else{
	$doqty = array('name'=>'doqty','id'=>'doqty', 'maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('doqty'), 'onkeypress'=>'return isNumberKey(event)');
}

$ddate = array('name'=>'ddate','value'=>$cdate,'id'=>'datepicker','data-format'=>'yyyy-MM-dd');
$dodate = array('name'=>'dodate','value'=>$cdate,'id'=>'datepicker4', 'data-format'=>'yyyy-MM-dd');
$tdrvr=array('name'=>'tdrvr','maxlength'=>'50','maxvalue'=>'50','value'=>$this->input->post('tdrvr'));
$tpnum=array('name'=>'tpnum','maxlength'=>'20','maxvalue'=>'20','value'=>$this->input->post('tpnum'));
$ref3=array('name'=>'ref3', 'value'=>$this->input->post('ref3'));
$ref4=array('name'=>'ref4', 'value'=>$this->input->post('ref4'));

$trucktime = array('name'=>'trucktime', 'id'=>'trucktime', 'value'=>$this->input->post('trucktime'));
$trk_list = array('name'=>'trk_list');

$uom_list=array('name'=>'uom');

$expected_deldate = array('name'=>'expected_deldate','value'=>$expected_deldate,'id'=>'datepicker3', 'data-format'=>'yyyy-MM-dd');

$vdesti=$r->CardCode;
$vitem=$r->ItemCode;

$catno=array('name'=>'catno', 'value'=>$this->input->post('catno'));

$doseriesno=array('name'=>'doseriesno', 'value'=>$this->input->post('doseriesno'));

endforeach;
else:
	
$rdate = array('name'=>'rdate','value'=>$cdate,'readonly'=>'true');
$tdate = array('name'=>'tdate','value'=>$cdate,'id'=>'datepicker1', 'data-format'=>'yyyy-MM-dd');
$deltype = array('name'=>'deltype','maxlength'=>20,'maxvalue'=>20,'readonly'=>'true','value'=>'Delivery Out'); 
$ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20,'onchange'=>"main/myFunction(this.value)",'value'=>$this->input->post('ref')); 
$ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('ref2'));
$qty = array('name'=>'whqty','id'=>'itemqty', 'maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('whqty'), 'onkeypress'=>'return isNumberKey(event)');
$ddate = array('name'=>'ddate','value'=>$cdate,'id'=>'datepicker', 'data-format'=>'yyyy-MM-dd');
$dodate = array('name'=>'dodate','value'=>$cdate,'id'=>'datepicker4', 'data-format'=>'yyyy-MM-dd');
// $addr=array('name'=>'remarks','maxlength'=>'250','value'=>$this->input->post('remarks'));

$addr = $this->input->post('remarks');	

// $do_remarks=array('name'=>'do_remarks','maxlength'=>'250','value'=>$this->input->post('do_remarks'));

$do_remarks = $this->input->post('do_remarks');

$itemid = array('name'=>'itemid', 'value'=>$this->input->post('itemid'), 'id'=>'itemid', 'placeholder'=>'Enter Item Code');

// $uom=array('name'=>'uom','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('uom'));

if(isset($check_intransit)){
	foreach($check_intransit as $r){
		$doqty = array('name'=>'doqty','id'=>'doqty', 'maxlength'=>20,'maxvalue'=>20,'value'=>$r->wi_intransit, 'onkeypress'=>'return isNumberKey(event)', 'readonly'=>'readonly');
	}
}else{
	$doqty = array('name'=>'doqty','id'=>'doqty', 'maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('doqty'), 'onkeypress'=>'return isNumberKey(event)');

}

$tcmpny=array('name'=>'tcom','maxlength'=>'50','maxvalue'=>'50','value'=>$this->input->post('tcom'));
$tdrvr=array('name'=>'tdrvr','maxlength'=>'50','maxvalue'=>'50','value'=>$this->input->post('tdrvr'));
$tpnum=array('name'=>'tpnum','maxlength'=>'20','maxvalue'=>'20','value'=>$this->input->post('tpnum'));
$PONum=array('name'=>'PONum','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('PONum'));
$intransit = array('name'=>'intransit','id'=>'intransit', 'maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('intransit'), 'onkeypress'=>'return isNumberKey(event)', 'readonly'=>'readonly');
$location = array('name'=>'location','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('location'));
$ref3=array('name'=>'ref3', 'value'=>$this->input->post('ref3'));
$ref4=array('name'=>'ref4', 'value'=>$this->input->post('ref4'));

$trucktime = array('name'=>'trucktime', 'id'=>'trucktime', 'value'=>$this->input->post('trucktime'));
$shiptime = array('name'=>'shiptime', 'id'=>'shiptime', 'value'=>$this->input->post('shiptime'));
$trk_list = array('name'=>'trk_list');

$uom_list=array('name'=>'uom');

$transfer_ref = array('name'=>'transfer_ref', 'value'=>$this->input->post('transfer_ref'));

$expected_deldate = array('name'=>'expected_deldate','value'=>$expected_deldate,'id'=>'datepicker3', 'data-format'=>'yyyy-MM-dd');

$vitem=null;
$vdesti=null;

$catno=array('name'=>'catno', 'value'=>$this->input->post('catno'));

$doseriesno=array('name'=>'doseriesno', 'value'=>$this->input->post('doseriesno'));

endif;
?>

<?php echo form_open();
$tokens = explode('/', current_url());
$get = $tokens[sizeof($tokens)-1];?>

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

		select, input[type="text"], textarea{
			width: 200px;
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

		.js #dialog-message, #sub_del_type_out_all,
			#sub_del_type_out_customer, #sub_del_type_out_warehouse, #print_dr_pdf, #print_wis_pdf, #dialog-error, #dialog-error-date{
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

	</style>

	<script type="text/javascript">
		document.documentElement.className = 'js';
	</script>

	<h5>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;">
						<strong>
							<?php echo anchor('main/wh_delivery_item_in/'.$this->uri->segment(3),'Delivery In');?> |
							Delivery Out Manual |
							<?php echo anchor('main/wh_delivery_item_reserve_ho/'.$this->uri->segment(3),'Delivery Out HO');?> |
							<?php echo anchor('main/wh_delivery_item_mm/'.$this->uri->segment(3),'Material Management');?> |
							<?php echo anchor('main/tin_tout/'.$this->uri->segment(3),'Transfer In and Out');?> |
						</strong>
					</div>
					<div class="panel-body form-inline" id="pbody">
						<div class="col-md-7">

							<!--<?php if(isset($error)):?><div class="glb_error"><?php echo $error;?></div><?php endif;?>
							<br/>-->
							
							<!--<p><?php echo validation_errors();?></p>-->

							<!-- WAREHOUSE CODE FOR SHOWING OF DELIVERY OUT PAGE -->
							<input type="hidden" name="wh_code_dout" value="<?php echo $this->uri->segment(3); ?>">

							<label>Source *</label>
							<?php if(isset($warehouse)):foreach($warehouse as $r):?>
								<?php echo form_dropdown('wh',$whlist,$r->wh_name);?><br/><br/>
							<?php endforeach;?><?php endif;?>	
							<label>Type *</label>
							<?php echo form_input($deltype);?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_dropdown('sub_type_del_out', $sub_type_del_out, '', 'id="sub_del_type_out"'); ?>
							<br/><br/>
							<label>Customer | Warehouse *</label>

							<?php echo form_dropdown('bpname',$bpname,$vdesti, 'id="sub_del_type_out_all"');?>
							<?php echo form_dropdown('sub_out_customer',$sub_del_type_out_customer, '', 'id="sub_del_type_out_customer"');?>
							<?php echo form_dropdown('sub_out_warehouse',$sub_del_type_out_warehouse, '', 'id="sub_del_type_out_warehouse"');?>

							<br/><br/>
							<label>Reference No. 1 *</label>
							<?php echo form_dropdown('doctype1',$doctype,$this->input->post('doctype1'),'id="rf1"'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_input($ref); ?>&nbsp;&nbsp;
							<button class="btn btn-success btn-xs" name="check_intransit" type="submit" value="cins">Check Intransit</button>
							<!--<?php echo form_error('ref'); ?>-->
							<br/><br/>
							<label>Reference No. 2</label>
							<?php echo form_dropdown('doctype2',$doctype,$this->input->post('doctype2'), 'id="rf2"'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_input($ref2);?>
							<br/><br/>
							
							<label>Reference No. 3</label>
							<?php echo form_input($ref3); ?><br/><br/>
							
							<label>Reference No. 4</label>
							<?php echo form_input($ref4); ?><br/><br/>
							
							<label>Transfer Ref.</label>
							<?php echo form_input($transfer_ref); ?><br><br>
							<label>PO | SC Number</label>
							<?php echo form_input($PONum);?><br/><br/>
							<label>Item *</label>
							<?php echo form_input($itemid); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_dropdown('whitem',$item, $this->input->post('whitem'),'id="item_list"');?><br/><br/>
							<label>Unit of Measurement *</label>
							<?php echo form_dropdown('uom', $uom, $this->input->post('uom'), 'id="uom"'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" id="newuom" readonly style="text-transform: uppercase;"/>&nbsp;&nbsp;
							<button type="button" id="adduom" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus-sign"></span></button>
							<br/><br/>
							<label>Actual Quantity Loaded *</label>
							<?php echo form_input($qty);?> 
							<!--<?php echo form_error('whqty');?>-->
							<br/><br/>
							<label>DO / ITO Quantity</label>
							<?php echo form_input($doqty);?><br/><br/>
							<label>In-Transit</label>
							<?php echo form_input($intransit);?><br/><br/>
						
							<label>Expected Delivery | <br/> Pick-up Date</label>
	        				<div class='input-group date' id='datepicker3'>
				                	<!-- <?php echo form_input($expected_deldate);?> -->
				                	<?php if($this->input->post('expected_deldate')){$edate_01 = $this->input->post('expected_deldate');}else{$edate_01 = date('Y-m-d');} ?>
				                	<input type="text" name="expected_deldate" value="<?php echo $edate_01; ?>" class="form-control" id="datepicker3" data-format="yyyy-MM-dd">
									<span class="add-on input-group-addon">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div>
							<br/><br/>

							<label>DO | SO Date</label>
	        				<div class='input-group date' id='datepicker4'>
				                	<!-- <?php echo form_input($dodate);?> -->
				                	<?php if($this->input->post('dodate')){$dodate_01 = $this->input->post('dodate');}else{$dodate_01 = date('Y-m-d');} ?>
				                	<input type="text" name="dodate" value="<?php echo $dodate_01; ?>" class="form-control" id="datepicker4" data-format="yyyy-MM-dd">
									<span class="add-on input-group-addon">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div>
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
							<!-- <?php echo form_textarea($addr);?> -->

							<textarea name="remarks" class="form-control" cols="50" rows="5"><?php echo $addr; ?></textarea>

							<br/><br/>

							<label>DO Remarks</label>
							<!-- <?php echo form_textarea($do_remarks);?> -->

							<textarea name="do_remarks" class="form-control" cols="50" rows="5"><?php echo $do_remarks; ?></textarea>

							<br/><br/>
							
						</div>
						<div class="col-md-4">
							<br/>

							<label>Transaction No.</label>
							<?php if(isset($trans_no)): foreach($trans_no as $tn): ?>
							<input type="text" name="trans_no" value="<?php echo $tn->sn_nextnum; ?>" readonly><br><br>
							<?php endforeach; ?>
							<?php endif; ?>

							<label>DO Series No</label>
							<?php echo form_input($doseriesno); ?><br><br>

							<label>Creation Date</label>
							<?php echo form_input($rdate);?><br/><br/>

							<label>Production Code</label>
							<input type="text" name="pbatch_code" value="<?php echo $this->input->post('pbatch_code') ?>"><br><br>

							<label>Item Catalog No.</label>
							<?php echo form_input($catno);?><br/><br/>
							
							<label>Pick-up| Delivery Location *</label>
							<?php echo form_input($location);?><br/><br/>
							<label>Truckers Arrival Time *</label>
						
							<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
							    <input type="text" name="trucktime" class="form-control" style="width: 160px;" value="<?php echo $this->input->post('trucktime'); ?>">
							    <span class="input-group-addon">
							        <span class="glyphicon glyphicon-time"></span>
							    </span>
							</div>

							<br/><br/>
			
							<label>Truck Company *</label>
							<?php echo form_dropdown('truck_list', $trucks, $this->input->post('truck_list'), 'id="truck_list"'); ?>
							<button type="button" id="addtruck" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus-sign"></span></button>
							<br/><br/><label>Trucker Name</label><input type="text" id="newtruck" readonly style="text-transform: uppercase;"/><br/><br/>

							<label>Truck Plate Number *</label>
							<!-- <?php echo form_input($tpnum);?> -->

							<input type="text" name="tpnum" list="plateno" id="tpnum" value="<?php echo $this->input->post('tpnum') ?>" autocomplete="off">
							<datalist id="plateno" class="tpnum">
							</datalist>

							<br/><br/>
							<label>Truck Driver *</label>
							<!-- <?php echo form_input($tdrvr);?> -->

							<input type="text" name="tdrvr" id="tdrvr" list="driver" value="<?php echo $this->input->post('tdrvr') ?>" autocomplete="off">
							<datalist id="driver" class="tdrvr">
							</datalist>

							<br/><br/>
						
							<label>Shipment | Pick-up Date</label>
	        				<div class='input-group date' id='datepicker1'>
				                	<!-- <?php echo form_input($tdate);?> -->
				                	<?php if($this->input->post('tdate')){$tdate_01 = $this->input->post('tdate');}else{$tdate_01 = date('Y-m-d');} ?>
				                	<input type="text" name="tdate" value="<?php echo $tdate_01; ?>" class="form-control" id="datepicker1" data-format="yyyy-MM-dd">
									<span class="add-on input-group-addon">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div>
							<br/><br/>

							<label>Shipment | Pick-up Time</label>

							<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
							    <input type="text" name="shiptime" class="form-control" style="width: 160px;" value="<?php echo $this->input->post('shiptime'); ?>">
							    <span class="input-group-addon">
							        <span class="glyphicon glyphicon-time"></span>
							    </span>
							</div>

							<br><br>

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

							<input type="submit" name="submit" class="btn btn-info" value="Submit"/><br><br>
							<!-- <button type="button" id="print" class="btn btn-warning"><?php echo anchor('main/print_dr/'.'126499', 'Print DR'); ?></button><br/><br/><br/> -->

							<div class="alert alert-info">
								<b>REQUIRED DOCUMENTS IN TRANSACTION:<br><br>
								Warehouse to Warehouse</b><br>
								*Reference No. 1: ITO<br><br>
								<b>Warehouse to Customer</b><br>
								*Reference No. 1: DO<br><br>
								<b>CY to Customer</b><br>
								*Reference No. 1: DO<br><br>
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

<?php

validation_errors() !="" ? $this->error_modal->alert("IMS - Warning",validation_errors()) : ""; // throw validation errors
$msg !="" ? $this->error_modal->alert("IMS - Success",$msg) : ""; // throw success message

if(isset($error)){
	$error !="" ? $this->error_modal->alert("IMS - Warning",$error) : "";	 // throw another validation errors
}

?>

<script type="text/javascript" src="<?php echo base_url();?>timepicker/wickedpicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>timepicker/wickedpicker.min.js"></script>

<script>
		$(function() {
		
			$('#truck_list').change(function(){

				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>index.php/main/get_truck_plateno",
					dataType: "json",
					data: {truck_name: this.value},
					success: function(data){

						if(data.truck_data == "WALA"){
							$('.tpnum').empty();
							$('#tpnum').val("");
						}else{
							var len = data.truck_data.length;

			                $(".tpnum").empty();
			                for( var i = 0; i<len; i++){
			                    var name = data.truck_data[i]['Truck_PlateNo'];
			                    $(".tpnum").append("<option value='"+name+"'>"+name+"</option>");

			                }
						}

					}

				});
			});

			$('#tpnum').change(function(){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>index.php/main/get_truck_driver",
					dataType: "json",
					data: {truck_plateno: this.value},
					success: function(data){

						if(data.truck_data2 == "WALA"){
							$('.tdrvr').empty();
							$('#tdrvr').val("");
						}else{
							var len = data.truck_data2.length;

			                $(".tdrvr").empty();
			                for( var i = 0; i<len; i++){
			                    var name = data.truck_data2[i]['Truck_Driver'];
			                    $(".tdrvr").append("<option value='"+name+"'>"+name+"</option>");
			                }

						}

					}

				});

			});

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
			<?php elseif($this->uri->segment(4) == "dout_01"): ?>

				$("#dialog-message").show();
				$("#dialog-message").dialog({
					modal: true,
					buttons: {
						Ok: function() {
					  		$(this).dialog( "close" );
					  		var x = window.location.href.slice(0,-8);
				          	window.location.href = x;

						}
					}
				});

			<?php endif; ?>

			<?php 

				$temp = $this->uri->segment(3);
				$slen = 0;

				if(strlen($temp) == 2){
					$slen = -27;
				}else{
					$slen = -26;
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

			<?php if(isset($cvdate_dout)): ?>
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

		});
</script>


<!-- PLUG-INS FOR SUCCESS MESSAGEBOX -->
 <link rel="stylesheet" href="<?php echo base_url() ?>jquery-ui/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="<?php echo base_url() ?>jquery-ui/jquery-ui.js"></script>
<!-- END OF FILE -->

<script type="text/javascript">

	  	$('input').on('keypress', function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9-:. ]+$");
		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		    if (!regex.test(key)) {
		       event.preventDefault();
		       return false;
		    }
		});

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


	     // SET THE VALUE OF REF1 TO DR AND REF2 TO DO
		$('#rf1').val("DO");
		$('#rf2').val("DR");


	     // $('#sub_del_type_out_customer').hide();
	     $('#sub_del_type_out_all').hide();
	     $('#sub_del_type_out_customer').show();
	     $('#sub_del_type_out_warehouse').hide();

	     $('#sub_del_type_out').change(function(){
	     	if($('#sub_del_type_out').val() == "DO_01"){
	     		$('#sub_del_type_out_customer').show();
	     		$('#sub_del_type_out_warehouse').hide();
	     		$('#sub_del_type_out_all').hide();

	     		// SET THE VALUE OF REF1 TO DR AND REF2 TO DO
				$('#rf1').val("DO");
				$('#rf2').val("DR");

	     	}else if($('#sub_del_type_out').val() == "DO_04"){
	     		$('#sub_del_type_out_customer').show();
	     		$('#sub_del_type_out_warehouse').hide();
	     		$('#sub_del_type_out_all').hide();

	     		// SET THE VALUE OF REF1 TO ADJ AND REF2 TO ADJ
				$('#rf1').val("ADJ");
				$('#rf2').val("ADJ");

	     	}else if($('#sub_del_type_out').val() == "DO_05"){
	     		$('#sub_del_type_out_customer').show();
	     		$('#sub_del_type_out_warehouse').hide();
	     		$('#sub_del_type_out_all').hide();

	     		// SET THE VALUE OF REF1 TO WIS AND REF2 TO DO
				$('#rf1').val("WIS");
				$('#rf2').val("DO");

	     	}else if($('#sub_del_type_out').val() == "DO_02"){
	     		$('#sub_del_type_out_warehouse').show();
	     		$('#sub_del_type_out_customer').hide();
	     		$('#sub_del_type_out_all').hide();

	     		// SET THE VALUE OF REF1 TO ADJ AND REF2 TO ADJ
				$('#rf1').val("ADJ");
				$('#rf2').val("ADJ");

	     	}else if($('#sub_del_type_out').val() == "DO_03"){
	     		$('#sub_del_type_out_warehouse').show();
	     		$('#sub_del_type_out_customer').hide();
	     		$('#sub_del_type_out_all').hide();

	     		// SET THE VALUE OF REF1 TO ADJ AND REF2 TO ADJ
				$('#rf1').val("ADJ");
				$('#rf2').val("ADJ");

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


		// REMOVE THE DELIVERY TO CUSTOMER (DR) AND PICK-UP (WIS)
		// $("#sub_del_type_out option[value=DO_01]").remove();
		// $("#sub_del_type_out option[value=DO_05]").remove();

		$('#itemid').keyup(function(){
			var newitem = $("#itemid").val();

	    	var exists = false;
			$('#item_list option').each(function(){
			    if ($(this).val() == newitem) {
			        exists = true;
			        return false;
			    }
			});

			if(exists == true){
				$('#item_list').val(newitem).change();
			}
		});


	  
	</script>