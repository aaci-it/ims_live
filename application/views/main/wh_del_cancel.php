<?php $this->load->view('header');?>

<style type="text/css">
	#back_btn a{
		color: white;
		text-decoration: none;
	}

	.js #error_msg{
		display: none;
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
					<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Confirmation of Delivery Cancellation</strong></div>
					<div class="panel-body">

						<div class="alert alert-danger" id="error_msg">
						    <a href="#" class="close" data-dismiss="alert" aria-label="close" id="close">&times;</a>
						    <strong><?php echo validation_errors();?></strong>
						</div>

						<label>Remarks</label>
						<?php $addr=array('name'=>'remarks','maxlength'=>'100'); echo form_textarea($addr);?><br/><br/>
						<input type="submit" name="update" value="Submit" class="btn btn-info"/>
						<!-- <button class="btn btn-danger" onclick="history.back();">Back</button> -->
						<button class="btn btn-danger" id="back_btn"><?php echo anchor('main/wh_delivery_cancel_list', 'Back'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

</h5>


<?php if(isset($duplicate)){
echo $duplicate;}?>


<?php echo form_close();?>

<?php $this->load->view('footer');?>

<script type="text/javascript">

		<?php if(validation_errors()): ?>
			$('#error_msg').show();
		<?php else: ?>
			$('#error_msg').hide();
		<?php endif; ?>

		$('#close').click(function(){
			$('#error_msg').hide();
		});

</script>