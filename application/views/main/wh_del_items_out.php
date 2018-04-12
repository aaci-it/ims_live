<?php echo $this->load->view('header');?>
<div id="body">
<?php echo form_open();
$tokens = explode('/', current_url());
$get = $tokens[sizeof($tokens)-1];?>

	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="/resources/demos/style.css" />
	<script>
		$(function() {
		$( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
		$( "#datepicker1" ).datepicker({dateFormat: 'yy-mm-dd'});
		});
	</script>

<div class="footer">
<h3><?php echo anchor('main/wh_delivery_item_in/'.$get,'Delivery In');?> | <?php echo anchor('main/wh_delivery_item_reserve/'.$get,'Delivery Out');?></h3>
<h4><?php if(isset($error)):?><?php echo $error;?><?php endif;?>
<p class="valid"><?php echo validation_errors();?></p></h4>
			<table>
				<tr>
					<?php if(isset($warehouse)):foreach($warehouse as $r):?>
					<td>Warehouse *</td><td><?php echo form_dropdown('wh',$whlist,$r->wh_name);?></td>
					<?php endforeach;?><?php endif;?>
					<td>Creation Date</td>
					<td><?php $rdate = array('name'=>'rdate','value'=>$cdate,'readonly'=>'true');?><?php echo form_input($rdate);?></td>
				</tr>
				<tr>
					<td>Type *</td><td><?php $deltype = array('name'=>'deltype','maxlength'=>20,'maxvalue'=>20,'readonly'=>'true','value'=>'Delivery Out'); echo form_input($deltype);?></td>
				</tr>
				<tr>
					<td>Reference Name *</td><td><?php echo form_dropdown('bpname',$bpname);?></td>
				</tr>
				<tr>
					<td>Reference No. 1 *</td>
					<td><?php echo form_dropdown('doctype1',$doctype,'-Select-');?>
					<?php $ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20); echo form_input($ref);?></td>
				</tr>
				<tr>
				<td>Reference No. 2</td>
					<td><?php echo form_dropdown('doctype2',$doctype,'-Select-');?>
					<?php $ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20); echo form_input($ref2);?></td>
				</tr>
				<tr>
					<td>Item *</td><td><?php echo form_dropdown('whitem',$item,$itemvalue);?></td>
				</tr>
				<tr>
					<td>Quantity *</td><td><?php $qty = array('name'=>'whqty','maxlength'=>20,'maxvalue'=>20); echo form_input($qty);?></td>
				</tr>
				<tr>
					<td>Delivery Date</td>
					<td><?php $ddate = array('name'=>'ddate','id'=>'datepicker','value'=>$cdate);?><?php echo form_input($ddate);?></td>
				</tr>
			</table>
<h3><?php echo form_submit('submit','Submit');?></h3>
<br>
</div>
<?php echo form_close();?>
</div>
<?php echo $this->load->view('footer');?>