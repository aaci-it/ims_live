<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php
foreach ($records as $r):
$name=array('name'=>'whname','value'=>$r->wh_name,'maxlength'=> '50','size'=> '50','readonly'=>true); 
$code=array('name'=>'whcode','value'=>$r->wh_code,'maxlength'=> '20','size'=> '20','readonly'=>true); 
$addr=array('name'=>'whaddr','value'=>$r->wh_addr,'maxlength'=>'100');
$emailadd = array('name'=>'emailadd','maxlength'=>'50','maxvalue'=>'50');
$sapcode=array('name'=>'whsapcode','maxlength'=> '50','size'=> '20','value'=>$r->wh_sapcode);
if ($r->wh_status == 1){
	$astatus = true;
}
else{
	$astatus = false;
}
endforeach;
?>

<?php $terri = array('LUZON'=>'LUZON','VISAYAS'=>'VISAYAS','MINDANAO'=>'MINDANAO');?>

<?php echo form_open();?>

<h5>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Update Warehouse</strong></div>
					<div class="panel-body">
						<div class="col-md-3">
							<label>Warehouse Name</label>
							<?php echo form_input($name);?>
							<label>Email Address</label>
							<?php echo form_dropdown('etype',$etypelist);?>
							<label>SAP Code</label>
							<?php echo form_input($sapcode);?><br/>
							<button class="btn btn-info" name="update">Update</button>
							<button class="btn btn-danger" onclick="history.back();">Back</button>
						</div>
						<div class="col-md-2">
							<label>Code</label>
							<?php echo form_input($code);?>
							<label>&nbsp;</label>
							<?php echo form_input($emailadd);?>
							<label>Territory</label>
							<?php echo form_dropdown('terri', $terri, $this->input->post('terri')); ?><br/><br/>
						</div>
						<div class="col-md-1">
							<label>Active</label>
							<?php echo form_checkbox('active','accept',$astatus);?><br/>
						</div>
						<div class="col-md-3">
							<label>Address</label>
							<?php echo form_textarea($addr);?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<table>
					<thead>
						<tr>
							<th>Type</th>
							<th>Email Address</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($emailrecords)):foreach($emailrecords as $r):?>
						<tr>
							<td><?php echo $r->type;?></td>
							<td><?php echo $r->emailadd;?></td>
						<tr>
						<?php endforeach;?>
						<?php else:?>
						<tr>
							<td colspan=2><?php echo 'No Record';?></td>
							
						<tr>
						<?php endif;?>
					<tbody>
				</table>
			</div>
		</div>
	</div>

</h5>


<?php if(isset($duplicate)){
echo $duplicate;}?>
<?php echo validation_errors();?>


<?php echo form_close();?>

<?php $this->load->view('footer');?>