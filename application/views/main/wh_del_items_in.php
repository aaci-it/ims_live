<?php $this->load->view('bootstrap_files');?>

<?php 

date_default_timezone_set("Asia/Manila");

$js = 'id="wh" onChange="someFunction();"';
$rdate = array('name'=>'rdate','value'=>$cdate,'readonly'=>'true');
$deltype = array('name'=>'deltype','maxlength'=>20,'maxvalue'=>20,'readonly'=>'true','value'=>'Delivery In'); 
// $ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20,'onchange'=>"main/myFunction(this.value)",'value'=>$this->input->post('ref'), 'id'=>'ref'); 
$ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('ref2'), 'id'=>'refnum2');
// $qty = array('name'=>'whqty','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('whqty'));
//$ddate = array('name'=>'ddate','value'=>$cdate,'id'=>'datepicker');
$addr=array('name'=>'remarks','maxlength'=>'250','value'=>$this->input->post('remarks'));
// $uom=array('name'=>'uom','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('uom'));
$loi=array('name'=>'loi','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('loi'));
$tcmpny=array('name'=>'tcom','maxlength'=>'50','maxvalue'=>'50','value'=>$this->input->post('tcom'));
$tdrvr=array('name'=>'tdrvr','maxlength'=>'50','maxvalue'=>'50','value'=>$this->input->post('tdrvr'), 'id'=>'truck_driver');
$tpnum=array('name'=>'tpnum','maxlength'=>'20','maxvalue'=>'20','value'=>$this->input->post('tpnum'), 'id'=>'truck_pnum');
$itoqty=array('name'=>'itoqty','maxlength'=>'20','maxvalue'=>'20','value'=>$this->input->post('itoqty'),'onkeypress'=>'return isNumberKey(event)');
$intransit=array('name'=>'intransit','maxlength'=>'20','maxvalue'=>'20','value'=>$this->input->post('intransit'), 'id'=>'intransit', 'onkeypress'=>'return isNumberKey(event)');
$location = array('name'=>'location','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('location'), 'id'=>'location');
$tdate = array('name'=>'tdate','value'=>$cdate,'id'=>'datepicker1', 'data-format'=>'yyyy-MM-dd');

$transfer_ref = array('name'=>'transfer_ref', 'value'=>$this->input->post('transfer_ref'));

$itemid = array('name'=>'itemid', 'value'=>$this->input->post('itemid'), 'id'=>'itemid', 'placeholder'=>'Enter Item Code');

$ref3 = array('name'=>'ref3', 'value'=>$this->input->post('ref3'));
$ref4 = array('name'=>'ref4', 'value'=>$this->input->post('ref4'));

?>

<?php echo form_open();?>
	
	<link rel="stylesheet" href="<?php echo base_url();?>timepicker/wickedpicker.css"/>
	<link rel="stylesheet" href="<?php echo base_url();?>timepicker/wickedpicker.min.css"/>

	<!-- DT Picker -->
 	<link rel="stylesheet" href="<?php echo base_url();?>DTPicker/css/bootstrap-datetimepicker.min.css" >
    <script src="<?php echo base_url();?>DTPicker/js/bootstrap-datetimepicker.min.js"></script>
    <!-- End of File -->

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

		body{
			background: #ebebeb;
			margin: 0;
			padding: 0;
		}

		#pbody label{
			width: 150px;
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


		.ui-dialog-titlebar-close {
		    visibility: hidden;
		}


		.js #dialog-message, #sub_del_type_in_warehouse,
			#sub_del_type_in_supplier, #sub_del_type_in_all, #print_rr_pdf, #dialog-error, #dialog-error-date{
			display: none;
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
							Delivery In |
							<?php echo anchor('main/wh_delivery_item_reserve/'.$this->uri->segment(3),'Delivery Out Manual');?> |
							<?php echo anchor('main/wh_delivery_item_reserve_ho/'.$this->uri->segment(3),'Delivery Out HO');?> |
							<?php echo anchor('main/wh_delivery_item_mm/'.$this->uri->segment(3),'Material Management');?> |
							<?php echo anchor('main/tin_tout/'.$this->uri->segment(3),'Transfer In and Out');?>
						</strong>
					</div>
					<div class="panel-body form-inline" id="pbody">
						<div class="col-md-7">

							<!-- WAREHOUSE CODE FOR SHOWING OF DELIVERY IN PAGE -->
							<input type="hidden" name="wh_code_din" value="<?php echo $this->uri->segment(3); ?>">

							<!--<?php if(isset($error)):?><div class="glb_error"><?php echo $error;?></div><?php endif;?>-->
							<br/>
							<!--<p><?php echo validation_errors();?></p>-->
							
							
							<label>Type *</label>
							<?php echo form_input($deltype);?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_dropdown('sub_type_del_in', $sub_type_del_in, $this->input->post('sub_type_del_in'), 'id="sub_del_type_in"'); ?>
							<br/><br/>

							<label>Source *</label>
							
							<?php echo form_dropdown('bpname',$bpname, $this->input->post('bpname'), 'id="sub_del_type_in_all"');?>
							<?php echo form_dropdown('sub_in_customer',$sub_del_type_in_customer, $this->input->post('sub_in_customer'), 'id="sub_del_type_in_customer"');?>
							<?php echo form_dropdown('sub_in_supplier',$sub_del_type_in_supplier, $this->input->post('sub_in_supplier'), 'id="sub_del_type_in_supplier"');?>
							<?php echo form_dropdown('sub_in_warehouse',$sub_del_type_in_warehouse, $this->input->post('sub_in_warehouse'), 'id="sub_del_type_in_warehouse"');?>

							<br/><br/>

							<label>Destination *</label>
							<?php if(isset($warehouse)):foreach($warehouse as $r):?>
								<?php echo form_dropdown('wh',$whlist,$r->wh_name);?><br/><br/>
							<?php endforeach;?><?php endif;?>
							<label>Return Category</label>
							<?php echo form_dropdown('rtn',$rtn);?><br/><br/>
							<label>Reference No. 1 *</label>
							<?php echo form_dropdown('doctype1',$doctype,$this->input->post('doctype1'), 'id="reftype1"');?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="ref" maxlength="20" maxvalue="20" onchange="main/myFunction(this.value)" value="<?php echo $this->input->post('ref'); ?>" id="refnum1" />	
							&nbsp;

							<button type="button" class="btn btn-success btn-xs" id="get_rr_details"><span class="glyphicon glyphicon-ok"></span></button>

							<br/><br/>
							<label>Reference No. 2</label>
							<?php echo form_dropdown('doctype2',$doctype,$this->input->post('doctype2'), 'id="reftype2"');?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_input($ref2);?><br/><br/>
							
							<label>Reference No. 3</label>
							<?php echo form_dropdown('doctype3',$doctype,$this->input->post('doctype3'), 'id="reftype3"');?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_input($ref3); ?><br/><br/>

							<label>Reference No. 4</label>
							<?php echo form_dropdown('doctype4',$doctype,$this->input->post('doctype4'), 'id="reftype4"');?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_input($ref4); ?><br/><br/>
							
							<label>Transfer Ref.</label>
							<?php echo form_input($transfer_ref); ?><br><br>
							<label>LOI No.</label>
							<?php echo form_input($loi);?><br/><br/>
							<label>Item *</label>
							<?php echo form_input($itemid); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_dropdown('whitem',$item, $this->input->post('whitem'), 'id="item_list"');?><br/><br/>
							<label>Unit of Measurement *</label>
							<?php echo form_dropdown('uom', $uom, $this->input->post('uom'), 'id="uom"'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" id="newuom" readonly style="text-transform: uppercase;"/>&nbsp;&nbsp;
							<button type="button" id="adduom" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus-sign"></span></button>
							<br/><br/>
							<label>Actual Quantity *</label>
							<input type="text" name="whqty" id="item_qty" maxlength="20" maxvalue="20" value="<?php echo $this->input->post('whqty'); ?>" onkeypress="return isNumberKey(event)"/>
							<!--<?php echo form_error('whqty'); ?>-->
							<br/><br/>
							<label>ITO Quantity</label>
							<?php echo form_input($itoqty);?><br/><br/>
							<label>In-Transit</label>
							<?php echo form_input($intransit); ?><br/><br/>
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
							<label>Remarks</label>
							<?php echo form_textarea($addr);?>
						</div>
						<div class="col-md-4">
							<br/>
							<label>Transaction No.</label>
							<?php if(isset($trans_no)): foreach($trans_no as $tno): ?>
							<input type="text" name="trans_no" value="<?php echo $tno->sn_nextnum; ?>" readonly><br><br>
							<?php endforeach; ?>
							<?php endif; ?>

							<label>Creation Date</label>
							<?php echo form_input($rdate);?><br/><br/>

							<label>Production Code</label>
							<input type="text" name="pbatch_code" value="<?php echo $this->input->post('pbatch_code') ?>"><br><br>

							<label>Location</label>
							<?php echo form_input($location);?><br/><br/>
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
							<div class="well">

								<label><b><em>For Printing Receipts</em></b></label><br>

								<label>Prepared By:</label>
								<input type="text" list="plist" name="prepared_by" value="<?php echo $this->input->post('prepared_by'); ?>" class="form-control" style="text-transform: uppercase;" autocomplete="off"/>
								<datalist id="plist">
									<?php if(isset($prepared_list)): foreach($prepared_list as $pl): ?>
										<option><?php echo $pl->prepared_by; ?></option>
									<?php endforeach; ?>
									<?php endif; ?>
								</datalist>	
								<br><br>
	
								<label>Guard on Duty:</label>
								<input type="text" list="glist" name="guard_duty" value="<?php echo $this->input->post('guard_duty'); ?>" class="form-control" style="text-transform: uppercase;" autocomplete="off"/>
								<datalist id="glist">
									<?php if(isset($guard_list)): foreach($guard_list as $gl): ?>
										<option><?php echo $gl->guard_duty; ?></option>
									<?php endforeach; ?>
									<?php endif; ?>
								</datalist>	
								<br><br>
	
							</div>

							<input type="submit" value="Submit" name="submit" class="btn btn-info" /><br/><br/><br/><br/>

							<div class="alert alert-info">
								<b>REQUIRED DOCUMENTS IN TRANSACTION:<br><br>
								Warehouse to Warehouse</b><br>
								*Reference No. 1: ITO<br><br>
								<b>Customer to Warehouse</b><br>
								*Reference No. 1: RR<br><br>
								<b>CY to Warehouse</b><br>
								*Reference No. 1: ITO<br><br>
								<b>Shipping to CY</b><br>
								*Reference No. 1: ITO<br>
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

<a id="print_rr_pdf" href="<?php echo base_url(). 'index.php/main/print_rr_pdf'; ?>" target="_blank">
	#
</a>

<div id="dialog-message" title="Delivery In Complete">
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
	$error !="" ? $this->error_modal->alert("IMS - Warning",$error) : ""; // throw another validation errors
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

		
			<?php if($this->uri->segment(4) == 'din_01_RR'): ?>
				$("#dialog-message").show();
				$("#dialog-message").dialog({
					modal: true,
					buttons: {
						Ok: function() {
					  		$(this).dialog( "close" );
					  		var x = window.location.href.slice(0,-10);
				          	window.location.href = x;

				          	$("#print_rr_pdf")[0].click();
						}
					}
				});
			<?php elseif($this->uri->segment(4) == "din_01"): ?>
				$("#dialog-message").show();
				$("#dialog-message").dialog({
					modal: true,
					buttons: {
						Ok: function() {
					  		$(this).dialog( "close" );
					  		var x = window.location.href.slice(0,-7);
				          	window.location.href = x;
						}
					}
				});
			<?php endif; ?>

			<?php 

				$temp = $this->uri->segment(3);
				$slen = 0;

				if(strlen($temp) == 2){
					$slen = -23;
				}else{
					$slen = -22;
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

			<?php if(isset($cvdate_din)): ?>
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
	

		});
</script>

<!-- PLUG-INS FOR SUCCESS MESSAGEBOX -->
 <link rel="stylesheet" href="<?php echo base_url() ?>jquery-ui/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="<?php echo base_url() ?>jquery-ui/jquery-ui.js"></script>
<!-- END OF FILE -->

 <script type="text/javascript">
	  $(function() {
	  	
	  	$('#reftype1').val("DO");
	    $('#reftype2').val("DR");

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

	     $('#sub_del_type_in_warehouse').hide();
	     $('#sub_del_type_in_supplier').hide();
	     $('#sub_del_type_in_all').hide();

	     if($('#sub_del_type_in').val() == "DI_03"){
	     		$('#sub_del_type_in_supplier').show();
	     		$('#sub_del_type_in_customer').hide();
	     		$('#sub_del_type_in_warehouse').hide();
	     	}else if($('#sub_del_type_in').val() == "DI_04"){
	     		$('#sub_del_type_in_customer').show();
	     		$('#sub_del_type_in_supplier').hide();
	     		$('#sub_del_type_in_warehouse').hide();
	     	}else if($('#sub_del_type_in').val() == "DI_01"){
	     		$('#sub_del_type_in_warehouse').show();
	     		$('#sub_del_type_in_customer').hide();
	     		$('#sub_del_type_in_supplier').hide();
	     	}else if($('#sub_del_type_in').val() == "DI_02"){
	     		$('#sub_del_type_in_warehouse').show();
	     		$('#sub_del_type_in_customer').hide();
	     		$('#sub_del_type_in_supplier').hide();
	     	}else if($('#sub_del_type_in').val() == "DI_05"){
	     		$('#sub_del_type_in_warehouse').show();
	     		$('#sub_del_type_in_customer').hide();
	     		$('#sub_del_type_in_supplier').hide();
	     	}else if($('#sub_del_type_in').val() == "DI_06"){
	     		$('#sub_del_type_in_warehouse').show();
	     		$('#sub_del_type_in_customer').hide();
	     		$('#sub_del_type_in_supplier').hide();
	     }

	     $('#sub_del_type_in').change(function(){
	     	if($('#sub_del_type_in').val() == "DI_03"){
	     		$('#sub_del_type_in_supplier').show();
	     		$('#sub_del_type_in_customer').hide();
	     		$('#sub_del_type_in_warehouse').hide();

	     		$('#reftype1').val("");
	     		$('#reftype2').val("");

	     	}else if($('#sub_del_type_in').val() == "DI_04"){
	     		$('#sub_del_type_in_customer').show();
	     		$('#sub_del_type_in_supplier').hide();
	     		$('#sub_del_type_in_warehouse').hide();

	     		$('#reftype1').val("DO");
	     		$('#reftype2').val("DR");

	     	}else if($('#sub_del_type_in').val() == "DI_01"){
	     		$('#sub_del_type_in_warehouse').show();
	     		$('#sub_del_type_in_customer').hide();
	     		$('#sub_del_type_in_supplier').hide();

	     		$('#reftype1').val("");
	     		$('#reftype2').val("");

	     	}else if($('#sub_del_type_in').val() == "DI_02"){
	     		$('#sub_del_type_in_warehouse').show();
	     		$('#sub_del_type_in_customer').hide();
	     		$('#sub_del_type_in_supplier').hide();

	     		$('#reftype1').val("");
	     		$('#reftype2').val("");

	     	}else if($('#sub_del_type_in').val() == "DI_05"){
	     		$('#sub_del_type_in_warehouse').show();
	     		$('#sub_del_type_in_customer').hide();
	     		$('#sub_del_type_in_supplier').hide();

	     		$('#reftype1').val("");
	     		$('#reftype2').val("");

	     	}else if($('#sub_del_type_in').val() == "DI_06"){
	     		$('#sub_del_type_in_warehouse').show();
	     		$('#sub_del_type_in_customer').hide();
	     		$('#sub_del_type_in_supplier').hide();

	     		$('#reftype1').val("");
	     		$('#reftype2').val("");

	     	}

	     });
	 	

	 	$("#sub_del_type_in option[value=DI_02]").remove();

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
				// alert("kita");
			}
		});

	 	$(document).on('click', '#get_rr_details', function(){

	 		if($('#sub_del_type_in').val() == "DI_03"){

	     		$cust_code = $('#sub_del_type_in_supplier').val();

	     	}else if($('#sub_del_type_in').val() == "DI_04"){

	     		$cust_code = $('#sub_del_type_in_customer').val();

	     		$.ajax({
		 			type: "POST",
		 			url: "<?php echo base_url(); ?>index.php/main/get_return_data",
		 			dataType: "json",
		 			data: {do_num: $('#refnum1').val()},
		 			success: function(data){
		 				$.each(data.return_data, function(index, val){
		 					$('#item_qty').val(val.wi_itemqty);
		 					$('#item_list').val(val.item_id);
		 					$('#sub_del_type_in_customer').val(val.wi_refname);
							$('#refnum2').val(val.wi_refnum2);
							$('#itemid').val(val.item_id);
		 				});
		 			}

		 		});

	     	}else if($('#sub_del_type_in').val() == "DI_01"){

	     		$cust_code = $('sub_del_type_in_warehouse').val();

	     	}else if($('#sub_del_type_in').val() == "DI_02"){

	     		$cust_code = $('sub_del_type_in_warehouse').val();

	     	}else if($('#sub_del_type_in').val() == "DI_05"){

	     		$cust_code = $('sub_del_type_in_warehouse').val();

	     	}else if($('#sub_del_type_in').val() == "DI_06"){

	     		$cust_code = $('sub_del_type_in_warehouse').val();
	     	}

	 	});

	 	
	  });

	</script>