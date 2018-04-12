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

<?php if(isset($records)): foreach($records as $r): ?>

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
						<div class="col-md-4">

							<label>Warehouse Code</label>
							<input type="text" name="wh_code" value="<?php echo $r->wh_code; ?>" id="wh_name" readonly/><br/><br/>

							<input type="submit" name="update" value="Update" class="btn btn-info" />
							<button class="btn btn-danger" type="button"><?php echo anchor('main/inventory_insight_whse', 'Back', 'style="color: white;"'); ?></button><br><br>
						</div>

						<div class="col-md-4">
							<label>Warehouse Name</label>
							<input type="text" name="wh_name" value="<?php echo $r->wh_name; ?>" id="wh_name" readonly/><br/><br/>
						</div>

						<div class="col-md-4">
							<label>Warehouse Location</label>
							<?php $list = array('0'=>'NCR', '1'=>'South Luzon', '2'=>'North Luzon'); ?>
							<?php echo form_dropdown('wh_loc', $list, $r->wh_location); ?>
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


<?php endforeach; ?>
<?php endif; ?>