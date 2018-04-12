<?php $this->load->view('bootstrap_files');?>

<?php 
$js = 'id="wh" onChange="someFunction();"';

$deltype = array('name'=>'deltype','maxlength'=>20,'maxvalue'=>20,'readonly'=>'true','value'=>'Delivery In'); 
$tdate = array('name'=>'tdate','value'=>$cdate,'id'=>'datepicker1', 'data-format'=>'yyyy-MM-dd');

?>

<?php echo form_open();?>

<?php if(isset($del_in_rec)): foreach($del_in_rec as $rec): ?>
	
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

		.js #sub_del_type_in_warehouse, #sub_del_type_in_supplier,
			#sub_del_type_in_all{
				display: none;
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
							Delivery In Edit
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
							<?php echo form_dropdown('sub_type_del_in', $sub_type_del_in, $rec->wi_subtype, 'id="sub_del_type_in"'); ?>
							<br/><br/>

							<label>Source *</label>
							
							<?php echo form_dropdown('bpname',$bpname, $rec->wh_name, 'id="sub_del_type_in_all"');?>
							<?php echo form_dropdown('sub_in_customer',$sub_del_type_in_customer, $rec->wi_refname, 'id="sub_del_type_in_customer"');?>
							<!--<?php echo form_dropdown('sub_in_supplier',$sub_del_type_in_supplier, $rec->wi_refname, 'id="sub_del_type_in_supplier"');?>-->
							<?php echo form_dropdown('sub_in_warehouse',$sub_del_type_in_warehouse, $rec->wi_refname, 'id="sub_del_type_in_warehouse"');?>

							<br/><br/>

							<label>Destination *</label>
							<!--<?php if(isset($warehouse)):foreach($warehouse as $r):?>
								<?php echo form_dropdown('wh',$whlist,$r->wh_name, 'readonly="readonly"');?><br/><br/>
							<?php endforeach;?><?php endif;?>-->

							<input type="text" name="wh" value="<?php echo $rec->wh_name; ?>" readonly/><br/><br/>

							<label>Return Category</label>
							<?php echo form_dropdown('rtn',$rtn, $rec->rr_category);?><br/><br/>

							<label>Reference No. 1 *</label>
							<?php $js = 'id = "doctype1"'; echo form_dropdown('doctype1', $doctype, $rec->wi_reftype, $js);?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="ref" maxlength="20" maxvalue="20" onchange="main/myFunction(this.value)" value="<?php echo $rec->wi_refnum; ?>" id="ref" />
							<!--<?php echo form_error('ref'); ?>-->
							<br/><br/>

							<label>Reference No. 2</label>
							<?php echo form_dropdown('doctype2', $doctype, $rec->wi_reftype2);?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="ref2" maxlength="20" maxvalue="20" onchange="main/myFunction(this.value)" value="<?php echo $rec->wi_refnum2; ?>" id="ref2" />
							<br/><br/>

							<label>Transfer Ref.</label>
							<input type="text" name="transfer_ref" value="<?php echo $rec->transfer_ref; ?>" />
							<br><br>

							<label>LOI No.</label>
							<input type="text" name="loi" value="<?php echo $rec->wi_LOINum; ?>" />
							<br/><br/>

							<label>Item *</label>
							<?php echo form_dropdown('whitem',$item, $rec->item_id);?><br/><br/>

							<label>Unit of Measurement *</label>
							<?php echo form_dropdown('uom', $uom, $rec->item_uom, 'id="uom"'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" id="newuom" readonly style="text-transform: uppercase;"/>&nbsp;&nbsp;
							<button type="button" id="adduom" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus-sign"></span></button>
							<br/><br/>

							<label>Actual Quantity *</label>
							<input type="text" name="whqty" maxlength="20" maxvalue="20" value="<?php echo $rec->wi_itemqty; ?>" onkeypress="return isNumberKey(event)"/>
							<br/><br/>

							<label>ITO Quantity</label>
							<input type="text" name="itoqty" maxlength="20" maxvalue="20" value="<?php echo $rec->wi_doqty; ?>" onkeypress="return isNumberKey(event)"/>
							<br/><br/>

							<label>In-Transit</label>
							<input type="text" id="intransit" name="intransit" maxlength="20" maxvalue="20" value="<?php echo $rec->wi_intransit; ?>" onkeypress="return isNumberKey(event)"/>
							<br/><br/>

							<label>Posting Date</label>
	        				<div class='input-group date' id='datepicker'>
				                <input data-format="yyyy-MM-dd" type="text" name="ddate" value="<?php echo $rec->deldate; ?>"></input>
									<span class="add-on">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div>
							<br/><br/>

							<label>Remarks</label>
							<textarea name="remarks" style="height: 150px;"><?php echo $rec->wi_remarks; ?></textarea>
						</div>

						<div class="col-md-4">
							<br/>

							<label>Transaction No.</label>
							<input type="text" name="trans_no" value="<?php echo $rec->wi_transno; ?>" readonly><br><br>

							<label>Creation Date</label>
							<input type="text" name="rdate" value="<?php echo $rec->wi_createdatetime; ?>" readonly/>
							<br/><br/>

							<label>Production Code</label>
							<input type="text" name="pbatch_code" value="<?php echo $this->input->post('pbatch_code') ?>"><br><br>

							<label>Location</label>
							<input type="text" name="location" value="<?php echo $rec->wi_location; ?>"/>
							<br/><br/>

							<label>Truck Company *</label>
							<?php echo form_dropdown('truck_list', $trucks, $rec->truck_company, 'id="truck_list"'); ?>
							<button type="button" id="addtruck" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus-sign"></span></button>
							<br/><br/><label>Trucker Name</label><input type="text" id="newtruck" readonly style="text-transform: uppercase;"/><br/><br/>

							<label>Truck Plate Number</label>
							<input type="text" name="tpnum" value="<?php echo $rec->truck_platenum; ?>"/>
							<br/><br/>

							<label>Truck Driver</label>
							<input type="text" name="tdrvr" value="<?php echo $rec->truck_driver; ?>"/>
							<br/><br/>

							<label>Shipment | Pick-up Date</label>
	        				<div class='input-group date' id='datepicker1'>
				                <?php echo form_input($tdate);?>
									<span class="add-on">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div>
							<br/><br/>


							<input type="submit" value="Update" name="update" class="btn btn-info" />
							<button type="button" onclick="history.back();" class="btn btn-danger">Back</button><br/><br/><br/><br/>

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
	$error !="" ? $this->error_modal->alert("IMS - Warning",$error) : ""; // throw another validation errors
}

?>

<script type="text/javascript" src="<?php echo base_url();?>timepicker/wickedpicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>timepicker/wickedpicker.min.js"></script>

<script>
		$(function() {
		
			$('#shiptime').click(function(){
				$('#shiptime').wickedpicker({twentyFour: true});
			});
	

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

	     $('#sub_del_type_in').change(function(){
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
	     	}

	     });
	 	
	 	// SHOW AND HIDE THE SOURCE IN DELIVERY IN EDIT

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
	     	}


	     <?php if($rec->wi_subtype == "DI_01"): ?>
	     	$('#sub_del_type_in_warehouse').show();
	     	$('#sub_del_type_in_customer').hide();
	     	$('#sub_del_type_in_supplier').hide();
	     <?php elseif($rec->wi_subtype == "DI_02"): ?>
	     	$('#sub_del_type_in_warehouse').show();
	     	$('#sub_del_type_in_customer').hide();
	     	$('#sub_del_type_in_supplier').hide();
	     <?php elseif($rec->wi_subtype == "DI_03"): ?>
	     	$('#sub_del_type_in_supplier').show();
	     	$('#sub_del_type_in_customer').hide();
	     	$('#sub_del_type_in_warehouse').hide();
	     <?php elseif($rec->wi_subtype == "DI_04"): ?>
	     	$('#sub_del_type_in_customer').show();
	     	$('#sub_del_type_in_supplier').hide();
	     	$('#sub_del_type_in_warehouse').hide();
	     <?php elseif($rec->wi_subtype == "DI_05"): ?>
	     	$('#sub_del_type_in_warehouse').show();
	     	$('#sub_del_type_in_customer').hide();
	     	$('#sub_del_type_in_supplier').hide();
	     <?php endif;  ?>

	</script>