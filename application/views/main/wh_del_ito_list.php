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
		<?php echo anchor('main/wh_delivery_unserve_list','Unserve Lists');?> | 
		<?php echo anchor('main/wh_delivery_dr_summary','DR Summary Lists');?> |  
		<?php echo anchor('main/wh_delivery_rr_summary','RR Summary Lists');?> |
		<?php echo anchor('main/wh_delivery_wis_summary','WIS Summary Lists');?>
	</h3>
	<h3>
		<?php echo anchor('main/wh_delivery_war_summary','WAR Summary Lists');?> |
		<?php echo anchor('main/wh_delivery_cancelDO_list','Cancelled DO Lists');?> |
		ITO Summary Lists
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
	<table id="myTable" class="tablesorter">
	<thead>
		<tr>
			<th>Source</th>
			<th>Destination</th>
			<th>Ref No. 1</th>
			<th>Ref No. 2</th>
			<th>Item Code</th>
			<th>Item Desc</th>
			<th>Item Qty</th>
			<th>DO/ITO Qty</th>
			<th>Posting Date</th>
			<th>Expected Delivery Date</th>
			<th>Exact Delivery Date</th>
			<th>Delivery Type</th>
		</tr>
	</thead>
	<tbody>
		<tr><?php if(isset($reserverecord)): foreach ($reserverecord as $r):?>
				<?php if($r->wi_type==0){
					$delstat="Delivery In";
					$char =null;
				}
				else{
					$delstat="Delivery Out";
					$char = '-';
				}?>
				<?php if ($r->wi_type == 0):?>
			<td><?php echo $r->CardName;?></td>
			<td><?php echo $r->wh_name;?></td>
				<?php else:?>
			<td><?php echo $r->wh_name;?></td>
			<td><?php echo $r->CardName;?></td>
				<?php endif;?>
			<td><?php echo $r->wi_reftype; echo $r->wi_refnum;?></td>
			<td><?php echo $r->wi_reftype2; echo  $r->wi_refnum2;?></td>
			<td><?php echo $r->item_id;?></td>
			<td><?php echo $r->comm__name;?></td>
			<td><?php echo $char.number_format($r->wi_itemqty,2);?></td>
			<td><?php echo $r->wi_doqty;?></td>
			<td><?php echo $r->deldate;?></td>
			<td><?php echo $r->wi_expecteddeliverydate;?></td>
			<td><?php echo $r->wi_exactdeliverydate;?></td>
		
			<td><?php echo $delstat;?></td>
			
		</tr>
		<?php endforeach;?>
		<?php else:?>
	<tr>
		<td colspan=12>No Record</td>
	</tr>
		<?php endif;?>
	</tbody>
</table>
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