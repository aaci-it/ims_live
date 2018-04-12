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

	.js #error_danger{
		display: none;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php  
	
	$gender_list = array('Male'=>'Male', 'Female'=>'Female');
	$comp_list = array('AACI'=>'AACI', 'AGTI'=>'AGTI', 'WEI'=>'WEI', 'CUSTOMER'=>'CUSTOMER');
	$stat_list = array('0'=>'Inactive', '1'=>'Active');
?>

<?php if(isset($records)): foreach($records as $r): ?>

<h5>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;">Edit User</div>
					<div class="panel-body form-inline">
						<div class="row">
							<div class="col-md-12">
								<div id="error_danger" class="alert alert-danger">
									<?php 
										if(isset($error)){echo $error;}
									?>
								</div>

								<?php echo validation_errors(); ?>
								<?php $pword = md5($r->memb__pword); ?>
							</div>
						</div><br>
						<div class="col-md-4">
							<label>Username</label>
							<input type="text" name="uname" class="form-control" value="<?php echo $r->memb__id; ?>" readonly><br><br>

							<label>New Password</label>
							<input type="password" name="pass" class="form-control" value="<?php echo $this->input->post('pass'); ?>"><br><br>

							<label>Mobile No.</label>
							<input type="number" name="mobile_no" class="form-control" value="<?php echo $r->memb__tel; ?>" maxlength="11"><br><br>

							<label>Status</label>
							<?php echo form_dropdown('status', $stat_list, $r->memb__status, 'style="width: 100px;"'); ?><br><br>

							<input type="submit" name="update_user" value="Update User" class="btn btn-info">
							<button id="btn_back" class="btn btn-danger" type="button"><?php echo anchor('main/user_access_list', 'Back'); ?></button>

						</div>
						<div class="col-md-4">
							<label>Fullname</label>
							<input type="text" name="fullname" class="form-control" value="<?php echo $r->memb__username; echo $this->input->post('fullname') ?>"><br><br>

							<label>Address</label>
							<textarea name="address"><?php echo $r->memb__addr; ?></textarea><br><br>

							<label>Company</label>
							<?php echo form_dropdown('comp_name', $comp_list, $r->memb_comp); ?>

						</div>
						<div class="col-md-4">
							<label>Email Address</label>
							<input type="text" name="eadd" class="form-control" value="<?php echo $r->memb__email; echo $this->input->post('eadd') ?>"><br><br>

							<label>Gender</label>
							<?php echo form_dropdown('gender', $gender_list, $r->memb__gender); ?><br><br><br>

							<label>Last Log On</label>
							<input type="text" name="log_time" class="form-control" value="<?php echo $r->log_time; ?>" readonly>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</h5>

<?php endforeach; ?>
<?php endif; ?>

<?php echo form_close();?>
<?php $this->load->view('footer');?>

<script type="text/javascript">

	$(document).ready(function(){

		<?php if(isset($error)): ?>
			$('#error_danger').show();
		<?php else: ?>
			$('#error_danger').hide();	
		<?php endif; ?>

	});

</script>