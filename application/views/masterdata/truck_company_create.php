<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php $terri = array('LUZON'=>'LUZON','VISAYAS'=>'VISAYAS','MINDANAO'=>'MINDANAO');?>

<style type="text/css">

	#error_msg{
		border-radius: 0px;
	}

	.js #error_msg, #dialog-message{
		display: none;
	}

	#back_btn a{
		color: white;
		text-decoration: none;
	}

	.ui-dialog-titlebar-close {
    	visibility: hidden;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php echo form_open();?>

<h5>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Add Truck Company</strong>
					</div>
					<div class="panel-body">

						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-danger" id="error_msg">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<label>
										<strong>
											<?php if(isset($error)){echo $error;} ?>
											<?php echo validation_errors(); ?>
										</strong>
									</label>
								</div>
							</div>
						</div>

						<div class="col-md-2">
							<label>Code</label>
							<?php if(isset($truck_series)): foreach($truck_series as $ts): ?>

							<?php 
								$temp = explode('_', $ts->Transporter_Code);
								$str = $temp[1]; 

								if($str[0] == '0'){
									$num = substr($str, 1, 2);
									$num = (int)$num + 1;
									$series = 'TRK_'.$num;
								}else{
									$num = (int)$str + 1;
									$series = 'TRK_'.$num;
								}

							?>

							<input type="text" class="form-control" name="truck_code" maxlength="50" size="50" value="<?php echo $series ?>" readonly/>
							<?php endforeach; ?>
							<?php endif; ?>

							<label>Status</label>
							<?php $stat_list = array('1'=>'Yes', '0'=>'No'); ?>
							<?php echo form_dropdown('truck_status', $stat_list, $this->input->post('status')); ?><br><br>
							
							<input type="submit" name="create" value="Create" class="btn btn-info" novalidate/>
							<button class="btn btn-danger" type="button" id="back_btn"><?php echo anchor('main/truck_company_list', 'Back'); ?></button>
						</div>
						<div class="col-md-5">
							<label>Short Name</label>
							<input type="text" class="form-control" value="<?php echo $this->input->post('truck_short_name') ?>" name="truck_short_name" maxlength="50" size="50" />
						
						</div>
						<div class="col-md-5">
							<label>Name</label>
							<input type="text" name="truck_name" value="<?php echo $this->input->post('truck_name') ?>" class="form-control">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</h5>

<?php echo form_close();?>

<?php $this->load->view('footer');?>

<!-- PLUG-INS FOR SUCCESS MESSAGEBOX -->
 <link rel="stylesheet" href="<?php echo base_url() ?>jquery-ui/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="<?php echo base_url() ?>jquery-ui/jquery-ui.js"></script>
<!-- END OF FILE -->

<div id="dialog-message" title="Truck Company List">
  <p>
    <div id="success_msg">
    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 10px 0;"></span>
    	Record(s) has been successfully saved.
    </div>
  </p>
</div>

<script>

	<?php if($this->uri->segment(3) == 'tc_01'): ?>

		$("#dialog-message").show();
		$("#dialog-message").dialog({
			modal: true,
			buttons: {
				Ok: function() {
			  		$(this).dialog( "close" );
			  		var x = window.location.href.slice(0,-6);
		          	window.location.href = x;
				}
			}
		});
	<?php endif; ?>


	// ERROR MESSAGE
	<?php if(isset($error) OR validation_errors()): ?>
			$('#error_msg').show();
		<?php else: ?>
			$('#error_msg').hide();	
		<?php endif; ?>
 </script>