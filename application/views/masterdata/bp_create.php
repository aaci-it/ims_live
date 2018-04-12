<?php $this->load->view('header');?>
<div id="body">
<?php echo form_open();?>
<div class="footer">
<h3>Business Partner</h3>
<table>
	<tr>
		<td>Code:</td><td><?php $bpcode = array('name'=>'bpcode'); echo form_input($bpcode);?></td>
	</tr>
	<tr>
		<td>Name:</td><td><?php $bpname=array('name'=>'bpname','maxlength'=> '50','size'=> '50'); echo form_input($bpname);?></td>
	</tr>
	<tr>
		<td>Address:</td><td><?php $addr=array('name'=>'bpaddr','maxlength'=>'100'); echo form_textarea($addr);?></td>
	</tr>
	<tr>
		<td>Phone Number:</td><td><?php $bpphone = array('name'=>'bpphone'); echo form_input($bpphone);?></td>
	</tr>
	<tr>
		<td>Email Address:</td><td><?php $bpemail = array('name'=>'bpemail','maxlength'=>'100','size'=> '50'); echo form_input($bpemail);?></td>
	</tr>
		<tr>
		<td>Remarks:</td><td><?php $addr=array('name'=>'bpaddr','maxlength'=>'100'); echo form_textarea($addr);?></td>
	</tr>
	<tr>
		<td><?php echo form_submit('create','Create');?></td>
	</tr>
</table>
<?php if(isset($duplicate)){
echo $duplicate;}?>
<?php echo validation_errors();?>
</div>
<?php echo form_close();?>
</div>
<?php $this->load->view('footer');?>