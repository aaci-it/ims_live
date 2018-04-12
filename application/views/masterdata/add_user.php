<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php echo form_open();?>

<style type="text/css">

	#btn_back a{
		color: white;
		text-decoration: none;
	}

	.col-md-4 label{
		width: 100px;
	}

	.js #error_msg, #dialog-message{
		display: none;
	}

	#error_msg{
		border-radius: 0px;
	}

	.ui-dialog-titlebar-close {
    	visibility: hidden;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php  
	
	$gender_list = array('Male'=>'Male', 'Female'=>'Female');
	$comp_list = array('AACI'=>'AACI', 'AGTI'=>'AGTI', 'WEI'=>'WEI', 'CUSTOMER'=>'CUSTOMER');
?>

<h5>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;">Add User</div>
					<div class="panel-body form-inline">
						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-danger" id="error_msg">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<label><strong><?php if(isset($error)){echo $error;} ?><?php echo validation_errors(); ?></strong></label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<label>Username</label>
							<input type="text" name="uname" class="form-control" value="<?php echo $this->input->post('uname'); ?>" ><br><br>

							<label>Password</label>
							<input type="password" name="pass" class="form-control" value="<?php echo $this->input->post('pass'); ?>" ><br><br><br>

							<label>Mobile No.</label>
							<input type="number" name="mobile_no" class="form-control" value="<?php echo $this->input->post('mobile_no'); ?>" maxlength="11"><br><br>

							<input type="submit" name="add_user" value="Add User" class="btn btn-info">
							<button id="btn_back" class="btn btn-danger" type="button"><?php echo anchor('main/user_access_list', 'Back'); ?></button>

						</div>
						<div class="col-md-4">
							<label>Fullname</label>
							<input type="text" name="fullname" class="form-control" value="<?php echo $this->input->post('fullname'); ?>" ><br><br>

							<label>Address</label>
							<textarea name="address"><?php echo $this->input->post('address'); ?></textarea><br><br>

							<label>Company</label>
							<?php echo form_dropdown('comp_name', $comp_list, $this->input->post('comp_name')); ?>

						</div>
						<div class="col-md-4">
							<label>Email Address</label>
							<input type="text" name="eadd" class="form-control" value="<?php echo $this->input->post('eadd'); ?>"><br><br>

							<label>Gender</label>
							<?php echo form_dropdown('gender', $gender_list); ?>

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

<div id="dialog-message" title="User Access Lists Information">
<br>
  <p>
    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
    New User Record was successfully added.
  </p>
</div>