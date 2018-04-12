<?php echo $this->load->view('header');?>
<div id="body">
<?php echo form_open();
$tokens = explode('/', current_url());
$get = $tokens[sizeof($tokens)-1];?>

<div class="footer">
<h3>Delivery Transaction</h3>
			<h4><?php if(isset($error)):?><?php echo $error;?><?php endif;?>
			<p class="valid"><?php echo validation_errors();?></p></h4>
			<table>
				<tr>
					<td>Warehouse</td><td><?php echo form_dropdown('wh',$whlist,$this->input->post('whouse'));?></td>
					<td>Type</td><td><?php echo form_dropdown('deltype',$deltype,$deftype);?></td>
				</tr>
				<tr>
					<td>Reference Name</td><td><?php echo form_dropdown('bpname',$bpname);?></td>
				</tr>
				<tr>
					
					<td>Reference No. 1</td>
					<td><?php echo form_dropdown('doctype1',$doctype,'-Select-');?>
					<?php $ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20); echo form_input($ref);?></td>
					<td>Reference No. 2</td>
					<td><?php echo form_dropdown('doctype2',$doctype,'-Select-');?>
					<?php $ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20); echo form_input($ref2);?></td>
				</tr>
				<tr>
					<td>Item</td><td><?php echo form_dropdown('whitem',$item,$itemvalue);?></td>
					<td>Quantity</td><td><?php $qty = array('name'=>'whqty','maxlength'=>20,'maxvalue'=>20); echo form_input($qty);?></td>
				</tr>
				<tr>
					<td colspan='2' align='right'><?php echo form_submit('submit','Submit');?></td>
				</tr>
			</table>

<br>
</div>
<?php echo form_close();?>
</div>
<?php echo $this->load->view('footer');?>