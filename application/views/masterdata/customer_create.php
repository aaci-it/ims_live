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

<h5>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Add Customer</strong>
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

						<div class="col-md-3">
							<label>Code</label>
							<input type="text" name="ccode" value="<?php echo $this->input->post('ccode'); ?>" /><br/><br/><br>
							<label>Customer Email Address 2</label>
							<input type="text" name="cust_email2" value="<?php echo $this->input->post('cust_email2'); ?>"><br><br>
							<label>Logistics</label>
							<input type="text" name="log" value="<?php echo $this->input->post('log'); ?>"><br><br><br>
							<label>Location 2</label>
							<textarea name="location2"><?php echo $this->input->post('location3'); ?></textarea><br><br>
							<label>Location 6</label>
							<textarea name="location6"><?php echo $this->input->post('location6'); ?></textarea>
			
							<br><br>

							<input type="submit" name="update" value="Add" class="btn btn-info" />
							<button class="btn btn-danger" type="button"><?php echo anchor('main/customer_list', 'Back', 'style="color: white;"'); ?></button><br><br>
						</div>
						<div class="col-md-3">
							<label>Name</label>
							<textarea name="cname" ><?php echo $this->input->post('cname'); ?></textarea><br/><br>
							<label>Account Executive</label>
							<input type="text" name="ae" value="<?php echo $this->input->post('ae'); ?>"><br/><br/>
							<label>Logistics Email Address</label>
							<input type="text" name="log_email" value="<?php echo $this->input->post('log_email'); ?>"><br><br><br>
							<label>Location 3</label>
							<textarea name="location3"><?php echo $this->input->post('location3'); ?></textarea>		
						</div>
						<div class="col-md-3">
							<label>Customer Addressee</label>
							<input type="text" name="cadd" value="<?php echo $this->input->post('cadd'); ?>"><br/><br/><br>
							<label>AE Email Address</label>
							<input type="text" name="ae_email" value="<?php echo $this->input->post('ae_email'); ?>"><br><br>
							<label>Logistics Mobile</label>
							<input type="text" name="logistics_mobile" value="<?php echo $this->input->post('logistics_mobile'); ?>"><br><br><br>
							<label>Location 4</label>
							<textarea name="location4"><?php echo $this->input->post('location4'); ?></textarea><br><br> 

						</div>
						<div class="col-md-3">
							<label>Customer Email Address</label>
							<input type="text" name="cust_email" value="<?php echo $this->input->post('cust_email'); ?>"><br/><br/><br>
							<label>AE Mobile</label>
							<input type="text" name="ae_mobile" value="<?php echo $this->input->post('ae_mobile'); ?>"><br><br>
							<label>Location</label>
							<textarea name="location"><?php echo $this->input->post('location'); ?></textarea><br/><br/>
							<label>Location 5</label>
							<textarea name="location5"><?php echo $this->input->post('location5'); ?></textarea><br><br> 
							
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

  	// SUCCESS MESSAGE
  	<?php if($this->uri->segment(3) == "1"): ?>

  	$(function(){
  		$( "#dialog-message" ).dialog({
	      modal: true,
	      buttons: {
	        Ok: function() {
	          $( this ).dialog( "close" );

	          var x = window.location.href.slice(0,-2);

		      window.location.href = x;

	        }
	      }
	    });
  	});
	   
	<?php else: ?>
		$('#dialog-message').hide();
	<?php endif; ?>

 </script>

<div id="dialog-message" title="Customer Lists Information">
<br>
  <p>
    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
    New Customer Record was successfully added.
  </p>
</div>