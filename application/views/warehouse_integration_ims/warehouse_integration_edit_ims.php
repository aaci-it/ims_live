<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php echo form_open();?>

<style type="text/css">

	#error_msg{
		border-radius: 0px;
	}

	.js #error_msg, #dialog-message{
		display: none;
	}

	.ui-dialog-titlebar-close {
    	visibility: hidden;
	}

</style>

<?php if(isset($wh_list_edit)): foreach($wh_list_edit as $r): ?>

<script type="text/javascript">
	document.documentElement.className = 'js';

	
	
</script>

<h5>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Edit Record</strong>
					</div>
					<div class="panel-body">

						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-danger" id="error_msg">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<label><strong><?php if(isset($error)){echo $error;} echo validation_errors();?></strong></label>
								</div>
							</div>
						</div>

						<div class="col-md-6">

							<label>SAP Warehouse Code</label>
							<?php echo form_dropdown('sap_wcode', $wh_list_sap, $r->SAP_WhsCode." - ".$r->SAP_WhsName, 'id="sap_wcode"'); ?><br/><br/>

							<label>SAP Warehouse Name</label>
							<input type="text" name="sap_wname" value="<?php echo $r->SAP_WhsName; ?>" id="sap_wname" readonly><br/><br/>
							
							<input type="hidden" name="swcode" value="<?php echo $r->SAP_WhsCode; ?>" id="swcode">

							<input type="submit" name="update" value="Update" class="btn btn-info" />
							<button class="btn btn-danger" type="button"><?php echo anchor('main/warehouse_integration_ims', 'Back', 'style="color: white;"'); ?></button><br><br>

						</div>


						<div class="col-md-4">

							<label>Warehouse Code</label>
							<?php echo form_dropdown('wh_code', $wh_list, $this->input->post('wh_code'), 'id="wh_code"'); ?><br/><br/>

							<label>Warehouse Name</label>
							<input type="text" name="wh_name" value="<?php echo $r->WhsName; ?>" id="wh_name" readonly/><br/><br/>

							<input type="hidden" name="wcode" value="<?php echo $r->WhsCode; ?>" id="wcode">

						</div>
						

					</div>
				</div>

				</div>
			</div>
		</div>
	</div>

</h5>

<?php echo form_close();?>
<?php $this->load->view('footer');?>

<br><br>

<!-- PLUG-INS FOR SUCCESS MESSAGEBOX -->
 <link rel="stylesheet" href="<?php echo base_url() ?>jquery-ui/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="<?php echo base_url() ?>jquery-ui/jquery-ui.js"></script>
<!-- END OF FILE -->

<script>

	// ERROR MESSAGE
	<?php if(isset($error) OR validation_errors()): ?>
			$('#error_msg').show();
		<?php else: ?>
			$('#error_msg').hide();	
		<?php endif; ?>

	$('#wh_code').change(function(){
		var wcode = $('#wh_code option:selected').text();
		var wname = $('#wh_code option:selected').text();
		wcode = wcode.substr(0, wcode.indexOf(' '));
		wname = wname.substr(wname.indexOf(' ') + 3);
		$('#wh_name').val(wname);
		$('#wcode').val(wcode);
	});

	$('#sap_wcode').change(function(){
		var swcode = $('#sap_wcode option:selected').text();
		var sap_wname = $('#sap_wcode option:selected').text();
		sap_wname = sap_wname.substr(sap_wname.indexOf(' ') + 3);
		swcode = swcode.substr(0, swcode.indexOf(' '));
		$('#sap_wname').val(sap_wname);
		$('#swcode').val(swcode);
	});

	// Load Warehouse Code and Name
	$('#wh_code').val('<?php echo $r->WhsCode; ?>');
	//Load SAP Warehouse Code and Name
	$('#sap_wcode').val('<?php echo $r->SAP_WhsCode; ?>');

 </script>

<div id="dialog-message" title="Customer Lists Information">
<br>
  <p>
    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
    New Customer Record was successfully added.
  </p>
</div>

<?php endforeach; ?>
<?php endif; ?>