<?php echo $this->load->view('header');?>

<?php 
$sdate = array('name'=>'sdate','value'=>$sdate,'id'=>'datepicker');
$edate = array('name'=>'edate','value'=>$edate,'id'=>'datepicker1');
?>
<div id="body">
<?php echo form_open();?>
<link rel="stylesheet" href="<?php echo base_url();?>des/calendar/jquery-ui.css"/>
	<script type="text/javascript" src="<?php echo base_url();?>des/calendar/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>des/calendar/jquery-ui.js"></script>
	<script>
		$(function() {
		$( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
		$( "#datepicker1" ).datepicker({dateFormat: 'yy-mm-dd'});
		});
	</script>
<div class="footer">
	<h3>
		Unserve Lists | 
		<?php echo anchor('main/wh_delivery_dr_summary','DR Summary Lists');?> | 
		<?php echo anchor('main/wh_delivery_rr_summary','RR Summary Lists');?> |
		<?php echo anchor('main/wh_delivery_wis_summary','WIS Summary Lists');?> | 
	</h3>
	<h3>
		<?php echo anchor('main/wh_delivery_war_summary','WAR Summary Lists');?> |
		<?php echo anchor('main/wh_delivery_cancelDO_list','Cancelled DO Lists');?> |
		<?php echo anchor('main/ito_summary','ITO Summary Report');?> 
	</h3>
	<h4>
		<table>
			<tr>
				<td>Warehouse</td>
				<td><?php echo form_dropdown('whouse',$whlist,$this->input->post('whouse'));?></td>
			</tr>
			<tr>
				<td>Creation Date Start </td><td><?php echo form_input($sdate);?></td>
				<td>Creation Date End </td><td><?php echo form_input($edate);?></td>
				<td colspan='2' align='right'><?php echo form_submit('submit','Search');?></td>
			</tr>
		</table>
	</h4>
		<div style="height: 600px; overflow-x: scroll;">
			<table id="myTable" class="tablesorter">
				<thead>
					<tr>
						<!--- Customer , JO No., Item No, Description, Quantity on Job Order --->
						<th>Customer</th>
						<th>JO Number</th>
						<th>Item Code</th>
						<th>Item Description</th>
						<th>Qty</th>
					</tr>
				</thead>
				<tbody>
					<tr><?php if(isset($reserverecord)): foreach ($reserverecord as $r):?>
						<td><?php echo $r->CardName;?></td>
						<td><?php echo $r->JONumber; ?></td>
						<td><?php echo $r->item_id;?></td>
						<td><?php echo $r->comm__name;?></td>
						<td><?php echo number_format($r->wi_itemqty,2);?></td>
					</tr>
					<?php endforeach;?>
				<tr>
					<td colspan=11>No Record</td>
				</tr>
					<?php endif;?>
				</tbody>
			</table>
		</div>
</div>
<div id="pager" class="pager">
		<img src="<?php echo base_url();?>des/sortable/addons/pager/icons/first.png" class="first"/>
		<img src="<?php echo base_url();?>des/sortable/addons/pager/icons/prev.png" class="prev"/>
		<input type="text" class="pagedisplay"/>
		<img src="<?php echo base_url();?>des/sortable/addons/pager/icons/next.png" class="next"/>
		<img src="<?php echo base_url();?>des/sortable/addons/pager/icons/last.png" class="last"/>
		<select class="pagesize">
			<option selected="selected" value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option  value="40">40</option>
		</select>
</div>

<br>
<?php echo form_close();?>
</div>
<?php echo $this->load->view('footer');?>