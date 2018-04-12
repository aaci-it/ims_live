<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php echo form_open();?>
<?php foreach ($records as $r):?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Edit Customer</strong></div>
				<div class="panel-body">
					<div class="col-md-3">
						<label>Code</label>
						<input type="text" name="ccode" value="<?php echo $r->CardCode;?>" readonly/><br/><br/><br>
						<label>Customer Email Address 2</label>
						<input type="text" name="cust_email2" value="<?php echo $r->Customer_Email2; ?>"><br/><br/>
						<label>Logistics</label>
						<input type="text" name="log" value="<?php echo $r->Logistics; ?>"><br><br><br>
						<label>Location 2</label>
						<textarea name="location2"><?php echo $r->Address2;?></textarea><br><br>
						<label>Location 6</label>
						<textarea name="location6"><?php echo $r->Address6;?></textarea><br><br>

						<input type="submit" name="update" value="Update" class="btn btn-info" />
						<button class="btn btn-danger" type="button"><?php echo anchor('main/customer_list', 'Back', 'style="color: white;"'); ?></button><br><br>
					</div>
					<div class="col-md-3">
						<label>Name</label>
						<textarea name="cname" readonly><?php echo $r->CardName;?></textarea><br/><br>
						<label>Account Executive</label>
						<input type="text" name="ae" value="<?php echo $r->Account_Executive; ?>"><br/><br/>
						<label>Logistics Email Address</label>
						<input type="text" name="log_email" value="<?php echo $r->Logistics_Email; ?>"><br><br><br>
						<label>Location 3</label>
						<textarea name="location3"><?php echo $r->Address3;?></textarea><br><br>

						<label>Require Truck Seal</label>
						<?php $tlist = array(1=>'Yes', 0=>'No'); ?>	
						<?php echo form_dropdown('truck_seal', $tlist, $r->truck_seal); ?>
						
					</div>
					<div class="col-md-3">
						<label>Customer Addressee</label>
						<input type="text" name="cadd" value="<?php echo $r->Customer_Addressee; ?>"><br/><br/><br>
						<label>AE Email Address</label>
						<input type="text" name="ae_email" value="<?php echo $r->AE_Email; ?>"><br><br>
						<label>Logistics Mobile</label>
						<input type="text" name="logistics_mobile" value="<?php echo $r->Logistics_Mobile; ?>"><br><br><br>
						<label>Location 4</label>
						<textarea name="location4"><?php echo $r->Address4;?></textarea><br>
						
					</div>
					<div class="col-md-3">
						<label>Customer Email Address</label>
						<input type="text" name="cust_email" value="<?php echo $r->Customer_Email; ?>"><br/><br/><br>
						<label>AE Mobile</label>
						<input type="text" name="ae_mobile" value="<?php echo $r->AE_Mobile; ?>"><br><br>
						<label>Location</label>
						<textarea name="location"><?php echo $r->Address;?></textarea><br/><br/>	
						<label>Location 5</label>
						<textarea name="location5"><?php echo $r->Address5;?></textarea><br><br>
						
					</div>
					<div class="col-md-12">
						<label><strong><?php echo validation_errors(); ?></strong></label>
					</div>

				</div>
			</div>
		</div>
		
		
	</div>
</div><br><br>

<?php endforeach;?>

<?php echo form_close();?>

<?php $this->load->view('footer');?>