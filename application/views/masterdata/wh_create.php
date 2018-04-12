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
					<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Add Warehouse</strong>
					</div>
					<div class="panel-body">

						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-danger" id="error_msg">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<label><strong><?php if(isset($error)){echo $error;} ?><?php echo validation_errors();?></strong></label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<label>Warehouse Name</label>
							<input type="text" class="form-control" name="whname" maxlength="50" size="50" value="<?php echo $this->input->post('whname') ?>" />
							<label>Territory</label>
							<?php echo form_dropdown('terri', $terri, $this->input->post('terri')); ?><br/><br/>
							
							<input type="submit" name="create" value="Create" class="btn btn-info"/>
							<button class="btn btn-danger" type="button" id="back_btn"><?php echo anchor('main/warehouse_list', 'Back'); ?></button>
						</div>
						<div class="col-md-4">
							<label>SAP Code</label>
							<input type="text" class="form-control" value="<?php echo $this->input->post('whsapcode') ?>" name="whsapcode" maxlength="50" size="50" />
							<label>Remarks</label>
							<textarea class="form-control" name="remarks"><?php echo $this->input->post('remarks') ?></textarea>
						</div>
						<div class="col-md-4">
							<label>Address</label>
							<textarea class="form-control" name="whaddr" ><?php echo $this->input->post('whaddr') ?></textarea>
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

<div id="dialog-message" title="Warehouse Lists">
<br>
  <p>
    <span class="ui-icon ui-icon-circle-check"></span>
    Record was successfully added.
  </p>
</div>