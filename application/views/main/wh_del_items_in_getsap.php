

<?php 
$js = 'id="wh" onChange="someFunction();"';
$rdate = array('name'=>'rdate','value'=>$cdate,'readonly'=>'true');
$deltype = array('name'=>'deltype','maxlength'=>20,'maxvalue'=>20,'readonly'=>'true','value'=>'Delivery In'); 
$ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20,'onchange'=>"main/myFunction(this.value)",'value'=>$this->input->post('ref')); 
$ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('ref2'));
$qty = array('name'=>'whqty','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('whqty'));
$ddate = array('name'=>'ddate','value'=>$cdate,'id'=>'datepicker');
$addr=array('name'=>'remarks','maxlength'=>'250','value'=>$this->input->post('remarks'));
$uom=array('name'=>'uom','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('uom'));
$loi=array('name'=>'loi','maxlength'=>50,'maxvalue'=>50,'value'=>$this->input->post('loi'));
$tcmpny=array('name'=>'tcom','maxlength'=>'50','maxvalue'=>'50','value'=>$this->input->post('tcom'));
$tdrvr=array('name'=>'tdrvr','maxlength'=>'50','maxvalue'=>'50','value'=>$this->input->post('tdrvr'));
$tpnum=array('name'=>'tpnum','maxlength'=>'20','maxvalue'=>'20','value'=>$this->input->post('tpnum'));
?>

<?php echo form_open($this->input->post('doctype1'));?>
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
<div id="body">
	<div class="footer">
		<h3>
			Delivery In | 
			<?php echo anchor('main/wh_delivery_item_reserve/'.$this->uri->segment(3),'Delivery Out');?> |
			<?php echo anchor('main/wh_delivery_item_mm/'.$this->uri->segment(3),'Material Management');?> <?php //echo anchor('main/wh_delivery_item_out/'.$get,'Delivery Out');?>
		</h3>
		<h4><?php if(isset($error)):?><?php echo $error;?><?php endif;?>
		<p class="valid"><?php echo validation_errors();?></p></h4>
		<table>
			<tr>
				<td>Source *</td>
				<td><?php echo form_dropdown('bpname',$bpname);?></td>
			
				
				<td>Creation Date</td>
				<td><?php echo form_input($rdate);?></td>
			</tr>
			<tr>
				<td>Type *</td>
				<td><?php echo form_input($deltype);?></td>
			</tr>
			<tr>
				<?php if(isset($warehouse)):foreach($warehouse as $r):?>
					<td>Destination *</td><td><?php echo form_dropdown('wh',$whlist,$r->wh_name);?></td>
					<?php endforeach;?><?php endif;?>
			</tr>
			<tr>
				<td>Reference No. 1 *</td>
				
				<td><?php echo form_dropdown('doctype1',$doctype,$ponum); 
					echo form_input($ref);
					echo anchor('main/wh_delivery_item_in_getsap/'.$this->input->post('doctype1').$this->input->post('ref'),'Get');?></td>
				<td rowspan = 6 colspan =2>
					<b>REQUIRED DOCUMENTS IN TRANSACTION:<br><br>
					Warehouse to Warehouse</b><br>
					*Reference No. 1: ITO<br><br>
					<b>Customer to Warehouse</b><br>
					*Reference No. 1: RR<br><br>
					<b>CY to Warehouse</b><br>
					*Reference No. 1: ITO<br><br>
					<b>Shipping to CY</b><br>
					*Reference No. 1: ITO<br>
				</td>
			</tr>
			<tr>
				<td>Reference No. 2</td>
				<td><?php echo form_dropdown('doctype2',$doctype,'-Select-');  echo form_input($ref2);?></td>
			</tr>
			<tr>
				<td>LOI No.</td>
				<td><?php echo form_input($loi);?></td>
			</tr>
			<tr>
				<td>Item *</td>
				<td><?php echo form_dropdown('whitem',$item);?></td>
			</tr>
			<tr>
				<td>Unit of Measurement *</td>
				<td><?php echo form_input($uom);?></td>
			</tr>
			<tr>
				<td>Actual Quantity Loaded *</td>
				<td><?php echo form_input($qty);?></td>
			</tr>
			<tr>
				<td>Posting Date</td>
				<td><?php echo form_input($ddate);?></td>
			</tr>
			<tr>
				<td>Truck Company</td>
				<td><?php echo form_input($tcmpny);?></td>
			</tr>
			<tr>
				<td>Truck Plate Number</td>
				<td><?php echo form_input($tpnum);?></td>
			</tr>
			<tr>
				<td>Truck Driver</td>
				<td><?php echo form_input($tdrvr);?></td>
			</tr>
			<tr>
				<td>Remarks</td>
				<td><?php echo form_textarea($addr);?></td>
			</tr>
		</table>
		<h3><?php echo form_submit('submit','Submit');?></h3>
		
	</div>
</div>
<?php echo form_close();?>