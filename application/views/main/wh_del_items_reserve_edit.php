<?php $this->load->view('bootstrap_files');?>

<!--<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>-->

<?php 

date_default_timezone_set("Asia/Manila");

if(isset($sapdetails)): foreach($sapdetails as $r):
$exdate = $r->ExpectedDelDate;
$conexdate = $exdate->format('Y-m-d');
//$qty = array('name'=>'whqty','maxlength'=>20,'maxvalue'=>20,'value'=>$r->Qty,'readonly'=>true, 'required'=>'required');
$location = array('name'=>'location','maxlength'=>50,'maxvalue'=>50,'value'=>$r->Destination,'readonly'=>true);
$addr=array('name'=>'remarks','maxlength'=>'250','value'=>$r->Remarks,'readonly'=>true);
$tcmpny=array('name'=>'tcom','maxlength'=>'50','maxvalue'=>'50','value'=>$r->Trucker,'readonly'=>true);
$ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('ref2'));
$PONum=array('name'=>'PONum','maxlength'=>50,'maxvalue'=>50,'value'=>$r->PONo,'readonly'=>true);
// $uom=array('name'=>'uom','maxlength'=>50,'maxvalue'=>50,'value'=>$r->UoM,'readonly'=>true);
$tdate = array('name'=>'tdate','value'=>$cdate,'id'=>'datepicker1', 'data-format'=>'yyyy-MM-dd');
$tcmpny=array('name'=>'tcom','maxlength'=>'50','maxvalue'=>'50','value'=>$r->Trucker,'readonly'=>true);
$rdate = array('name'=>'rdate','value'=>$cdate,'readonly'=>'true','readonly'=>true);
$ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20,'onchange'=>"main/myFunction(this.value)",'value'=>$this->input->post('ref'),'readonly'=>true); 
$doqty = array('name'=>'doqty','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('doqty'), 'onkeypress'=>'return isNumberKey(event)');
$ddate = array('name'=>'ddate','value'=>$cdate,'id'=>'datepicker','data-format'=>'yyyy-MM-dd');
$tdrvr=array('name'=>'tdrvr','maxlength'=>'50','maxvalue'=>'50','value'=>$this->input->post('tdrvr'));
$tpnum=array('name'=>'tpnum','maxlength'=>'20','maxvalue'=>'20','value'=>$this->input->post('tpnum'));
$ref3=array('name'=>'ref3', 'value'=>$this->input->post('ref3'));

$trucktime = array('name'=>'trucktime', 'id'=>'trucktime', 'value'=>$this->input->post('trucktime'));
$trk_list = array('name'=>'trk_list');

$uom_list=array('name'=>'uom');

$expected_deldate = array('name'=>'expected_deldate','value'=>$expected_deldate,'id'=>'datepicker3', 'data-format'=>'yyyy-MM-dd');

$vdesti=$r->CardCode;
$vitem=$r->ItemCode;

endforeach;
else:
$rdate = array('name'=>'rdate','value'=>$cdate,'readonly'=>'true');
$tdate = array('name'=>'tdate','value'=>$cdate,'id'=>'datepicker1', 'data-format'=>'yyyy-MM-dd');
$deltype = array('name'=>'deltype','maxlength'=>20,'maxvalue'=>20,'readonly'=>'true','value'=>'Delivery Out'); 
$ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20,'onchange'=>"main/myFunction(this.value)",'value'=>$this->input->post('ref')); 
$ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('ref2'));
$qty = array('name'=>'whqty','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('whqty'), 'onkeypress'=>'return isNumberKey(event)');
$ddate = array('name'=>'ddate','value'=>$cdate,'id'=>'datepicker', 'data-format'=>'yyyy-MM-dd');
$addr=array('name'=>'remarks','maxlength'=>'250','value'=>$this->input->post('remarks'));
// $uom=array('name'=>'uom','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('uom'));
$doqty = array('name'=>'doqty','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('doqty'), 'onkeypress'=>'return isNumberKey(event)');
$tcmpny=array('name'=>'tcom','maxlength'=>'50','maxvalue'=>'50','value'=>$this->input->post('tcom'));
$tdrvr=array('name'=>'tdrvr','maxlength'=>'50','maxvalue'=>'50','value'=>$this->input->post('tdrvr'));
$tpnum=array('name'=>'tpnum','maxlength'=>'20','maxvalue'=>'20','value'=>$this->input->post('tpnum'));
$PONum=array('name'=>'PONum','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('PONum'));
$intransit = array('name'=>'intransit','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('intransit'), 'onkeypress'=>'return isNumberKey(event)');
$location = array('name'=>'location','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('location'));
$ref3=array('name'=>'ref3', 'value'=>$this->input->post('ref3'));

$trucktime = array('name'=>'trucktime', 'id'=>'trucktime', 'value'=>$this->input->post('trucktime'));
$shiptime = array('name'=>'shiptime', 'id'=>'shiptime', 'value'=>$this->input->post('shiptime'));
$trk_list = array('name'=>'trk_list');

$uom_list=array('name'=>'uom');

$transfer_ref = array('name'=>'tranfer_ref', 'value'=>$this->input->post('transfer_ref'));

$expected_deldate = array('name'=>'expected_deldate','value'=>$expected_deldate,'id'=>'datepicker3', 'data-format'=>'yyyy-MM-dd');

$vitem=null;
$vdesti=null;

endif;
?>

<?php echo form_open();
$tokens = explode('/', current_url());
$get = $tokens[sizeof($tokens)-1];?>

<?php if(isset($del_out_rec)): foreach($del_out_rec as $rec): ?>

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
			width: 170px;
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

		.navbar-right{
				margin-right: 15px;
		}

		.js #sub_del_type_out_all, #sub_del_type_out_warehouse{
			display: none;
		}

	</style>

	<script type="text/javascript">
		document.documentElement.className = 'js';
	</script>

	<?php 

		if($rec->wi_dtcode == "DT_04"){
			$do_dtype = "readonly";
			$cust_readonly = "readonly";
			$whse_readonly = "readonly";
			$po_readonly = "readonly";
			$item_readonly = "readonly";
			$uom_readonly = "readonly";
			$doqty_readonly = "readonly";
			$intrans_readonly = "readonly";
			$exdate_readonly = "readonly";
			$location_readonly = "readonly";
			$tcomp_readonly = "readonly";
		}elseif($rec->wi_dtcode == "DT_02"){
			$do_dtype = "";
			$cust_readonly = "";
			$whse_readonly = "";
			$po_readonly = "";
			$item_readonly = "";
			$uom_readonly = "";
			$doqty_readonly = "";
			$intrans_readonly = "";
			$exdate_readonly = "";
			$location_readonly = "";
			$tcomp_readonly = "";
		}else{
			$do_dtype = "";
			$cust_readonly = "";
			$whse_readonly = "";
			$po_readonly = "";
			$item_readonly = "";
			$uom_readonly = "";
			$doqty_readonly = "";
			$intrans_readonly = "";
			$exdate_readonly = "";
			$location_readonly = "";
			$tcomp_readonly = "";
		}

	 ?>

	<h5>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;">
						<strong>
							
							Delivery Out Edit
						
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
							<!--<?php if(isset($warehouse)):foreach($warehouse as $r):?>
								<?php echo form_dropdown('wh',$whlist,$r->wh_name);?><br/><br/>
							<?php endforeach;?><?php endif;?>-->

								<input type="text" name="wh" value="<?php echo $rec->wh_name ?>" readonly/><br/><br/>

							<label>Type *</label>
							<?php echo form_input($deltype);?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_dropdown('sub_type_del_out', $sub_type_del_out, $rec->wi_subtype, 'id="sub_del_type_out"'.$do_dtype); ?>
							<br/><br/>
							<label>Customer | Warehouse *</label>

							<?php echo form_dropdown('bpname',$bpname,$vdesti, 'id="sub_del_type_out_all"');?>
							<?php echo form_dropdown('sub_out_customer',$sub_del_type_out_customer, $rec->wi_refname, 'id="sub_del_type_out_customer"'.$cust_readonly);?>
							<?php echo form_dropdown('sub_out_warehouse',$sub_del_type_out_warehouse, $rec->wi_refname, 'id="sub_del_type_out_warehouse"'.$whse_readonly);?>

							<br/><br/>
							<label>Reference No. 1 *</label>
							<?php echo form_dropdown('doctype1',$doctype, $rec->wi_reftype , 'readonly="readonly"'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="ref" value="<?php echo $rec->wi_refnum ?>" readonly/>&nbsp;&nbsp;

							<!-- <button class="btn btn-warning" name="sapdo" type="button">Get</button> -->
							<br/><br/>

							<label>Reference No. 2</label>
							<?php echo form_dropdown('doctype2',$doctype,$rec->wi_reftype2, 'readonly="readonly"' ); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="ref2" value="<?php echo $rec->wi_refnum2 ?>"/>&nbsp;&nbsp;
							<br/><br/>

							<label>Reference No. 3</label>
							<input type="text" name="ref3" value="<?php echo $rec->wi_refnum3 ?>" />
							<br/><br/>

							<label>Transfer Ref.</label>
							<input type="text" name="transfer_ref" value="<?php echo $rec->transfer_ref ?>" />
							<br><br>

							<label>PO Number</label>
							<input type="text" name="PONum" value="<?php echo $rec->wi_PONum ?>" <?php echo $po_readonly ?>>
							<br/><br/>

							<label>Item *</label>
							<?php echo form_dropdown('whitem',$item,$rec->item_id, $item_readonly);?>
							<br/><br/>

							<label>Unit of Measurement *</label>
							<?php echo form_dropdown('uom', $uom, $rec->item_uom, 'id="uom"'.$uom_readonly); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" id="newuom" readonly style="text-transform: uppercase;"/>&nbsp;&nbsp;
							<button type="button" id="adduom" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus-sign"></span></button>
							<br/><br/>

							<label>Actual Quantity Loaded *</label>
							<input type="text" name="whqty" value="<?php echo $rec->wi_itemqty ?>" onkeypress="return isNumberKey(event)"/> 
							<br/><br/>

							<label>DO / ITO Quantity</label>
							<input type="text" name="doqty" value="<?php echo $rec->wi_doqty ?>" onkeypress="return isNumberKey(event)" <?php echo $doqty_readonly ?>> 
							<br/><br/>

							<label>In-Transit</label>
							<input type="text" name="intransit" value="<?php echo $rec->wi_intransit ?>" onkeypress="return isNumberKey(event)" <?php echo $intrans_readonly ?>> 
							<br/><br/>
						
							<label>Expected Delivery | <br/> Pick-up Date</label>
	        				<div class='input-group date' id='datepicker3'>
				                	<!-- <?php echo form_input($expected_deldate);?> -->
				                	<?php if($this->input->post('expected_deldate')){$edate_01 = $this->input->post('expected_deldate');}else{$edate_01 = date('Y-m-d');} ?>
				                	<input type="text" name="expected_deldate" value="<?php echo $edate_01; ?>" class="form-control" id="datepicker3" data-format="yyyy-MM-dd" <?php echo $exdate_readonly ?>>
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
							<textarea name="remarks" style="height: 150px;"><?php echo $rec->wi_remarks; ?></textarea>

							<br/><br/>

							<label>DO Remarks</label>
							<textarea name="remarks" style="height: 150px;" readonly><?php echo $rec->do_remarks; ?></textarea>

							<br/><br/>

						</div>
						<div class="col-md-4">
							<br/>

							<label>Transaction No.</label>
							<input type="text" name="trans_no" value="<?php echo $rec->wi_transno ?>" readonly><br><br>

							<label>Creation Date</label>
							<input type="text" name="rdate" value="<?php echo $rec->wi_createdatetime ?>" readonly/>
							<br/><br/>

							<label>Production Code</label>
							<input type="text" name="pbatch_code" value="<?php echo $this->input->post('pbatch_code') ?>"><br><br>

							<label>Pick-up Delivery Location *</label>
							<input type="text" name="location" value="<?php echo $rec->wi_location ?>"  <?php echo $location_readonly; ?>>
							<br/><br/>

							<label>Truckers Arrival Time *</label>

							<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
							    <input type="text" name="trucktime" class="form-control" value="<?php echo $rec->truck_arrival_time ?>" style="width: 160px;" value="<?php echo $this->input->post('trucktime'); ?>">
							    <span class="input-group-addon">
							        <span class="glyphicon glyphicon-time"></span>
							    </span>
							</div>

							<br/><br/>

							<label>Truck Company *</label>
							<?php echo form_dropdown('truck_list', $trucks, $rec->truck_company, 'id="truck_list"'.$tcomp_readonly); ?>
							<button type="button" id="addtruck" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus-sign"></span></button>
							<br/><br/><label>Trucker Name</label><input type="text" id="newtruck" readonly style="text-transform: uppercase;"/><br/><br/>

							<label>Truck Plate Number *</label>
							<input type="text" name="tpnum" value="<?php echo $rec->truck_platenum ?>" />
							<br/><br/>

							<label>Truck Driver *</label>
							<input type="text" name="tdrvr" value="<?php echo $rec->truck_driver ?>" />
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
							    <input type="text" name="shiptime" class="form-control" value="<?php echo $rec->ship_time ?>" style="width: 160px;" value="<?php echo $this->input->post('shiptime'); ?>">
							    <span class="input-group-addon">
							        <span class="glyphicon glyphicon-time"></span>
							    </span>
							</div><br/><br/>

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
								<label>Ref. wb</label>
								<input type="text" name="ref_print" value="<?php echo $this->input->post('ref_print'); ?>" class="form-control" />
							</div>

							<br/>
							<input type="submit" name="update" class="btn btn-info" value="Update" />
							<button type="button" onclick="history.back();" class="btn btn-danger">Back</button>
							<br/><br/><br/>

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

<?php endforeach; ?>
<?php endif; ?>

	<div id="footer">
		<p style="text-align:center;">
			<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
		</p>
	</div>

<?php echo form_close();?>


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
		
			$('#shiptime').click(function(){
				$('#shiptime').wickedpicker({twentyFour: true});
			});

			$('#trucktime').click(function(){
				$('#trucktime').wickedpicker({twentyFour: true});
			});
	
			$('.clockpicker').clockpicker();

		});
</script>

<script type="text/javascript">

	  	$('input').on('keypress', function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9-]+$");
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
	     	if($('#sub_del_type_out').val() == "DO_01"){
	     		$('#sub_del_type_out_customer').show();
	     		$('#sub_del_type_out_warehouse').hide();
	     		$('#sub_del_type_out_all').hide();
	     	}else if($('#sub_del_type_out').val() == "DO_02" || $('#sub_del_type_out').val() == "DO_03" ){
	     		$('#sub_del_type_out_warehouse').show();
	     		$('#sub_del_type_out_customer').hide();
	     		$('#sub_del_type_out_all').hide();
	     	}
	     });


	     if($('#sub_del_type_out').val() == "DO_01"){
	     		$('#sub_del_type_out_customer').show();
	     		$('#sub_del_type_out_warehouse').hide();
	     		$('#sub_del_type_out_all').hide();
	     	}else if($('#sub_del_type_out').val() == "DO_02" || $('#sub_del_type_out').val() == "DO_03" ){
	     		$('#sub_del_type_out_warehouse').show();
	     		$('#sub_del_type_out_customer').hide();
	     		$('#sub_del_type_out_all').hide();
	     	}
	     	
	     <?php if($rec->wi_subtype == "DO_01"): ?>
	     	$('#sub_del_type_out_customer').show();
	     	$('#sub_del_type_out_warehouse').hide();
	     	$('#sub_del_type_out_all').hide();
	     <?php elseif($rec->wi_subtype == "DO_02"): ?>
	     	$('#sub_del_type_out_warehouse').show();
	     	$('#sub_del_type_out_customer').hide();
	     	$('#sub_del_type_out_all').hide();
	     <?php endif; ?>
	  
	</script>