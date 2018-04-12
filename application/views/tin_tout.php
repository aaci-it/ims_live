<?php $this->load->view('bootstrap_files');?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>jquery-ui/jquery-ui.css">
<script src="<?php echo base_url(); ?>jquery-ui/external/jquery/jquery.js"></script>
<script src="<?php echo base_url(); ?>jquery-ui/jquery-ui.js"></script>

<script>
	$(function() {

		$( "#apdate_dout" ).datepicker({dateFormat: 'yy-mm-dd'});
		$( "#pdate_dout" ).datepicker({dateFormat: 'yy-mm-dd'});
		$( "#pdate_din" ).datepicker({dateFormat: 'yy-mm-dd'});

		for(x=0; x<=3; x++){

			// GET THE ITEM CODE AND NAME
			$('#item_din_'+x).change(function(){
				var selected = $(this).find("option:selected").text();

				// GET THE ID OF THE COUNTED
				var id_temp = $(this).attr('id');
				var parts = id_temp.indexOf('_', id_temp.indexOf('_')+1);
				var the_num = id_temp.substr( parts + 1 );

				$('#item_dout_'+the_num).val(selected);
			});

			// GET THE ITEM UOM
			$('#uom_din_'+x).change(function(){
				var selected = $(this).find("option:selected").text();

				// GET THE ID OF THE COUNTED
				var id_temp = $(this).attr('id');
				var parts = id_temp.indexOf('_', id_temp.indexOf('_')+1);
				var the_num = id_temp.substr( parts + 1 );

				$('#uom_dout_'+the_num).val(selected);
			});

			// GET THE ITEM QTY
			$('#qty_din_'+x).change(function(){
				var selected = $(this).val();

				// GET THE ID OF THE COUNTED
				var id_temp = $(this).attr('id');
				var parts = id_temp.indexOf('_', id_temp.indexOf('_')+1);
				var the_num = id_temp.substr( parts + 1 );

				$('#qty_dout_'+the_num).val(selected);
			});


			// GET THE TO WAREHOUSE D_IN TO FROM WAREHOUSE D_OUT
			$('#to_din_'+x).change(function(){
				var selected = $(this).val();

				// GET THE ID OF THE COUNTED
				var id_temp = $(this).attr('id');
				var parts = id_temp.indexOf('_', id_temp.indexOf('_')+1);
				var the_num = id_temp.substr( parts + 1 );

				$('#from_dout_'+the_num).val(selected);
			});

		}

		// GET THE ITEM NAME
		$('#icode_dout').keyup(function(){

			var newitem = $('#icode_dout').val();
		   	var exists = false;

			$('#item_dout option').each(function(){
				if ($(this).val() == newitem) {
				    exists = true;
				    return false;
				}
			});

			if(exists == true){
				$('#item_dout').val(newitem).change();
			}

		});

		// GET THE ITEM CODE
		$('#item_dout').change(function(){
			$('#icode_dout').val(this.value);
		});

	});

	//ALLOW NUMBER ON TEXTFIELD
	function isNumberKey(evt){
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

		.panel-body label{
			width: 175px;
			font-weight: normal;
			padding-left: 25px; 
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

		.navbar-right{
			margin-right: 15px;
		}

		.table{
			width: 80%;
		}

		.table th{
			background-color: lightgray;
			color: black;
			text-align: center;
		}
		.table td{
			text-align: center;
		}

		.qty{
			text-align: center;
		}

		.well{
			border-radius: 0px;
		}

		.label-success{
			font-size: 13px;
			width: 200px;
			border-radius: 0px;
			text-align: left;
		}

		.js #error_msg, #tin_message, #tout_message, #error_msg_din, #print_tin_pdf, #print_tout_pdf,
		#dialog-error-tin, #dialog-error-tout, #dialog-error-date-tin, #dialog-error-date-tout{
			display: none;
		}

		#error_msg, #error_msg_din{
			border-radius: 0px;
		}

		.location{
			text-transform: uppercase;
		}

		.icode{
			text-align: center;
		}

		.tpnum{
			text-align: center;
		}

		.tdrvr{
			text-align: center;
		}


		.ui-dialog-titlebar-close {
		    visibility: hidden;
		}

		th, td{width:100px !important;white-space:nowrap !important;}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>


<?php  

	date_default_timezone_set("Asia/Manila");
	$datetime = date('Y-m-d h:i:s');

?>

<?php echo form_open(); ?>
<h5>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading" style="background-color: #3e3e40; color:white;">
						<strong>
							<?php echo anchor('main/wh_delivery_item_in/'.$this->uri->segment(3),'Delivery In');?> |
							<?php echo anchor('main/wh_delivery_item_reserve/'.$this->uri->segment(3),'Delivery Out');?> |
							<?php echo anchor('main/wh_delivery_item_reserve_ho/'.$this->uri->segment(3),'Delivery Out HO');?> |
							<?php echo anchor('main/wh_delivery_item_mm/'.$this->uri->segment(3),'Material Management');?> |
							Transfer In and Out
						</strong>
					</div>
				<div class="panel-body form-inline">
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-danger" id="error_msg">
								<a href="#" class="close" data-dismiss="alert" aria-label="close" style="margin-right: 20px; ">&times;</a>
								<strong>
									<?php echo validation_errors(); ?>
									<?php if(isset($error)){ echo $error; } ?>
								</strong>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label><strong>Creation Date Time</strong></label>
							<input type="text" value="<?php echo $datetime ?>" readonly/>
						</div>
					</div>

					<br><br>

					<div class="row">
						<div class="col-md-12 well">

						<label class="label label-success"><strong>Transfer Out</strong></label>
						<div class="table-responsive">
							<table class="table table-bordered table-hovered">
								<thead>
									<th>Transaction No.</th>
									<th>From (Warehouse)</th>
									<th>To (Warehouse)</th>
									<th>Doc. Series No.</th>
									<th>RefType 1</th>
									<th>Ref Num 1</th>
									<th>RefType 2</th>
									<th>Ref Num 2</th>
									<th colspan="2">Item</th>
									<th>UOM</th>
									<th>Quantity</th>
									<th>Truck Comp.</th>
									<th>Plate No.</th>
									<th>Driver</th>
									<th>Posting Date</th>
									<th>Actual Pick-Up Date</th>
									<th>Remarks</th>
								</thead>
								<tbody>
									<?php if(isset($wh_list_name)): foreach($wh_list_name as $rr): ?>

										<?php 

											if($this->input->post('from_dout')){
												$wname_dout = $this->input->post('from_dout');
											}else{
												$wname_dout = $rr->wh_name;
											}

											// TRANSACTION SERIES FOR TOUT
											if(isset($tno_tout)){
												foreach($tno_tout as $tout){
													$tout_no = $tout->sn_nextnum;
												}
											}else{
												$tout_no = $this->input->post('tno_tout');
											}

										?>

									<tr>

										<td><input type="text" name="tno_tout" value="<?php echo $tout_no; ?>" style="width: 100px; text-align: center;" readonly></td>
										<td><?php echo form_dropdown('from_dout', $whlist, $wname_dout, 'id="from_dout"'); ?></td>
										<td><?php echo form_dropdown('to_dout', $whlist, $this->input->post('to_dout'), 'id="to_dout"'); ?></td>
										<td><input type="text" name="ds_no_dout" value="<?php echo $this->input->post('ds_no_dout'); ?>"></td>
										<td><?php echo form_dropdown('reftype1_dout', $doctype_out, $this->input->post('reftype1_dout')); ?></td>
										<td><input type="text" name="refnum1_dout" value="<?php echo $this->input->post('refnum1_dout'); ?>" style="width: 100px;"></td>
										<td><?php echo form_dropdown('reftype2_dout', $doctype_out, $this->input->post('reftype2_dout')); ?></td>
										<td><input type="text" name="refnum2_dout" value="<?php echo $this->input->post('refnum2_dout'); ?>" style="width: 100px;"></td>
										<td><input type="text" name="icode_dout" id="icode_dout" class="icode" value="<?php echo $this->input->post('icode_dout') ?>" placeholder="Enter Item Code" style="width: 130px;"></td>
										<td><?php echo form_dropdown('item_dout', $item, $this->input->post('item_dout'), 'id="item_dout"'); ?></td>
										<td><?php echo form_dropdown('uom_dout', $uom, $this->input->post('uom_dout'), 'style="width:70px;" id="uom_dout"'); ?></td>
										<td><input type="text" class="qty" name="qty_dout" id="qty_dout" value="<?php echo $this->input->post('qty_dout') ?>" onkeypress="return isNumberKey(event)" style="width: 80px;"></td>
										<td><?php echo form_dropdown('truck_comp_dout', $trucks, $this->input->post('truck_comp_dout')); ?></td>
										<td><input type="text" class="tpnum_dout" name="tpnum_dout" value="<?php echo $this->input->post('tpnum_dout') ?>"></td>
										<td><input type="text" class="tdrvr_dout" name="tdrvr_dout" value="<?php echo $this->input->post('tdrvr_dout') ?>" ></td>
										<td><input type="text" name="pdate_dout" id="pdate_dout" value="<?php echo $this->input->post('pdate_dout') ?>" style="text-align: center; width: 100px;"></td>
										<td><input type="text" name="apdate_dout" id="apdate_dout" value="<?php echo $this->input->post('apdate_dout') ?>" style="text-align: center; width: 100px;"></td>
										<td><input type="text" name="remarks_dout" value="<?php echo $this->input->post('remarks_dout') ?>"></td>

									</tr>
									<?php endforeach; ?>
									<?php endif; ?>
								</tbody>
							</table>
							</div>

							<div style="margin: auto; width: 200px; padding: 10px;">
								<input type="submit" name="submit_dout" value="Submit" class="btn btn-info"  style="width: 150px; border-radius: 0px; height: 40px; font-size: 15px; font-weight: bold;"/>
							</div>

						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-danger" id="error_msg_din">
								<a href="#" class="close" data-dismiss="alert" aria-label="close" style="margin-right: 20px; ">&times;</a>
								<strong>
									<?php if(isset($error_din)){ echo $error_din; } ?>
								</strong>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12 well">

						<label class="label label-success"><strong>Transfer In</strong></label>

						<div class="table-responsive">
							<table class="table table-bordered table-hovered">
								<thead>
									<th>Transaction No</th>
									<th>Doc. Series No.</th>
									<th>From (Warehouse)</th>
									<th>To (Warehouse)</th>
									<th>RefType 1</th>
									<th>Ref Num 1</th>
									<th>RefType 2</th>
									<th>Ref Num 2</th>
									<th>Truck Company</th>
									<th colspan="2">Item</th>
									<th>UOM</th>
									<th>Quantity</th>
									<th>Posting Date</th>
									<th>Remarks</th>
								</thead>
								<tbody>
									<?php if(isset($wh_list_name)): foreach($wh_list_name as $rr): ?>

										<?php 

											if($this->input->post('from_din')){
												$wname_din = $this->input->post('from_din');
											}else{
												$wname_din = $rr->wh_name;
											}
											
											// TRANSACTION SERIES FOR TIN
											if(isset($tno_tin)){
												foreach($tno_tin as $tin){
													$tin_no = $tin->sn_nextnum;
												}
											}else{
												$tin_no = $this->input->post('tno_tin');
											}

										?>

									<tr>
										<td><input type="text" name="tno_tin" value="<?php echo $tin_no; ?>" style="width: 100px; text-align: center;" readonly></td>
										<td><input type="text" id="ds_no_din" name="ds_no_din" value="<?php echo $this->input->post('ds_no_din') ?>" placeholder="Enter Series No." style="text-align: center;"></td>
										<td><?php echo form_dropdown('from_din', $whlist, $wname_din, 'id="from_din" readonly'); ?></td>
										<td><?php echo form_dropdown('to_din', $whlist, $this->input->post('to_din'), 'id="to_din" readonly'); ?></td>
										<td><input type="text" id="reftype1_din" name="reftype1_din" value="<?php echo $this->input->post('reftype1_din') ?>" style="width: 100px;" readonly></td>
										<td><input type="text" id="refnum1_din" name="refnum1_din" value="<?php echo $this->input->post('refnum1_din'); ?>" style="width: 100px;" readonly></td>
										<td><input type="text" id="reftype2_din" name="reftype2_din" value="<?php echo $this->input->post('reftype2_din') ?>" style="width: 100px;" readonly></td>
										<td><input type="text" id="refnum2_din" name="refnum2_din" value="<?php echo $this->input->post('refnum2_din'); ?>" style="width: 100px;" readonly></td>
										<td><input type="text" id="truck_comp" name="truck_comp" value="<?php echo $this->input->post('truck_comp') ?>" style="text-align: center; width: 100px;" readonly></td>
										<td><input type="text" id="icode_din" name="icode_din" class="icode" value="<?php echo $this->input->post('icode_din') ?>" style="width: 100px;" readonly></td>
										<td><?php echo form_dropdown('item_din', $item, $this->input->post('item_din'), 'id="item_din" readonly'); ?></td>
										<td><input type="text" name="uom_din" id="uom_din" value="<?php echo $this->input->post('uom_din') ?>" style="width: 70px;" readonly></td>
										<td><input type="text" class="qty" name="qty_din" id="qty_din" value="<?php echo $this->input->post('qty_din') ?>" onkeypress="return isNumberKey(event)" style="width: 80px;" readonly></td>
										<td><input type="text" name="pdate_din" id="pdate_din" value="<?php echo $this->input->post('pdate_din') ?>" style="text-align: center; width: 100px;"></td>
										<td><input type="text" name="remarks_din" value="<?php echo $this->input->post('remarks_din') ?>"></td>
									</tr>
									<?php endforeach; ?>
									<?php endif; ?>
								</tbody>
							</table>
							</div>

							<div style="margin: auto; width: 200px; padding: 10px;">
								<input type="submit" name="submit_din" value="Submit" class="btn btn-info"  style="width: 150px; border-radius: 0px; height: 40px; font-size: 15px; font-weight: bold;"/>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
</h5>

<a id="print_tout_pdf" href="<?php echo base_url(). 'index.php/main/print_tout_pdf'; ?>" target="_blank">
	#
</a>
<a id="print_tin_pdf" href="<?php echo base_url(). 'index.php/main/print_tin_pdf'; ?>" target="_blank">
	#
</a>


<!-- <a href="<?php echo base_url(). 'index.php/main/create_pdf'; ?>" target="_blank">
   TEST View Pdf
</a> -->

<br><br>

<!-- DIALOG MESSAGE -->
<div id="tout_message" title="Transfer Out Complete">
  <p>
    <div id="success_msg">
    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 10px 0;"></span>
    	Record(s) has been successfully saved.
    </div>
  </p>
</div>

<div id="tin_message" title="Transfer In Complete">
  <p>
    <div id="success_msg">
    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 10px 0;"></span>
    	Record(s) has been successfully saved.
    </div>
  </p>
</div>

<div id="dialog-error-tin" title="Transaction No. Error">
	<p>
	  <div id="success_msg">
	  <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 10px 0;"></span>
	    	No Set Transaction Number for this Transaction Type.
	  </div>
	</p>
</div>

<div id="dialog-error-tout" title="Transaction No. Error">
	<p>
	  <div id="success_msg">
	  <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 10px 0;"></span>
	    	No Set Transaction Number for this Transaction Type.
	  </div>
	</p>
</div>


<div id="dialog-error-date-tin" title="Transaction No. Error">
	<p>
	  <div id="success_msg">
	  <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 10px 0;"></span>
	    	Series for this transaction type reach its validity period
	  </div>
	</p>
</div>

<div id="dialog-error-date-tout" title="Transaction No. Error">
	<p>
	  <div id="success_msg">
	  <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 10px 0;"></span>
	    	Series for this transaction type reach its validity period
	  </div>
	</p>
</div>

<?php echo form_close(); ?>

<?php echo $this->load->view('footer'); ?>

<!-- <script src="http://code.jquery.com/jquery-1.12.1.min.js"></script> -->
<script src="<?php echo base_url();?>js/timepicker.js"></script>

<!-- DT Picker -->
<link rel="stylesheet" href="<?php echo base_url();?>DTPicker/css/bootstrap-datetimepicker.min.css" >
<script src="<?php echo base_url();?>DTPicker/js/bootstrap-datetimepicker.min.js"></script>
<!-- End of File -->

<script type="text/javascript">

	<?php if($this->uri->segment(4) == 'tout_01'): ?>


		$("#tout_message").show();
		$("#tout_message").dialog({
			modal: true,
			buttons: {
				Ok: function() {
			  		$(this).dialog( "close" );
			  		var x = window.location.href.slice(0,-7);
		          	window.location.href = x;

		          	$("#print_tout_pdf")[0].click();
				}
			}
		});
	<?php elseif($this->uri->segment(4) == 'tin_01'): ?>

		$("#tin_message").show();
		$("#tin_message").dialog({
			modal: true,
			buttons: {
				Ok: function() {
			  		$(this).dialog( "close" );
			  		var x = window.location.href.slice(0,-7);
		          	window.location.href = x;

		          	$("#print_tin_pdf")[0].click();
				}
			}
		});
	<?php endif; ?>

	<?php 
				$temp = $this->uri->segment(3);
				$slen = 0;

				if(strlen($temp) == 2){
					$slen = -11;
				}else{
					$slen = -10;
				}

			 ?>

			<?php if(!isset($tno_tin)): ?>
				$("#dialog-error-tin").show();
				$("#dialog-error-tin").dialog({
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

			<?php if(!isset($tno_tout)): ?>
				$("#dialog-error-tout").show();
				$("#dialog-error-tout").dialog({
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

			<?php if(isset($cvdate_tin)): ?>
				$("#dialog-error-date-tin").show();
				$("#dialog-error-date-tin").dialog({
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

			<?php if(isset($cvdate_tout)): ?>
				$("#dialog-error-date-tout").show();
				$("#dialog-error-date-tout").dialog({
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

	$('#error_msg').hide();
	<?php if(validation_errors() OR isset($error)): ?>
		$('#error_msg').show();
	<?php else: ?>
		$('#error_msg').hide();
	<?php endif; ?>

	$('#error_msg_din').hide();
	<?php if(isset($error_din)): ?>
		$('#error_msg_din').show();
		$("html, body").animate({ scrollTop: $(document).height() }, 1000);
	<?php else: ?>
		$('#error_msg_din').hide();
	<?php endif; ?>

	// DISPLAY INFO FROM TRANSFER OUT DATA
		$('#ds_no_din').keyup(function(){

			if(this.value == ""){
				$('#reftype1_din').val("");
				$('#refnum1_din').val("");
				$('#reftype2_din').val("");
				$('#refnum2_din').val("");
				$('#icode_din').val("");
				$('#item_din').get(0).selectedIndex = 0;
				$('#uom_din').val("");
				$('#qty_din').val("");
				$('#truck_comp').val("");
			}else{
				$.ajax({
					url: "<?php echo base_url()?>index.php/main/get_tout_data",
					type: "POST",
					dataType: "json",
					data: {ds_no_din: $('#ds_no_din').val()},
					success: function(data){
						$.each(data.tout_data, function(index, val){
							$('#reftype1_din').val(val.wi_reftype);
							$('#refnum1_din').val(val.wi_refnum);
							$('#reftype2_din').val(val.wi_reftype2);
							$('#refnum2_din').val(val.wi_refnum2);
							$('#icode_din').val(val.item_id);
							$('#item_din').val(val.item_id);
							$('#uom_din').val(val.item_uom);
							$('#qty_din').val(val.wi_itemqty);
							$('#from_din').val(val.wh_name);
							$('#to_din').val(val.wi_location);
							$('#truck_comp').val(val.truck_company);
						});
					}
				});
			}
		});


</script>