<?php $this->load->view('header');?>
<?php echo form_open(current_url());?>
<div id="body">
<div class="footer">

<h3>Business Partner List</h3>
<?php echo anchor('main/businesspartner_create','Add');?>
<table id="myTable" class="tablesorter">
	<thead>
		<tr>
			<th>Code</th>
			<th>Name</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php if (isset($records)): foreach ($records as $r):?>
	<?php //if($r->Status ==1){
		//$status='Active';}
	//else{
		//$status='Active';}?>
		<tr>
			<td><?php echo $r->CardCode;?></td>
			<td><?php echo $r->CardName;?></td>
			<td><?php echo 'Active';?></td>
			<td><?php //echo anchor('main/businesspartner_edit/'.$r->CardCode,'Edit');?> <?php echo anchor('main/businesspartner_add_item/'.$r->CardCode,'Add Item');?></td>
		</tr>
<?php endforeach;?>
<?php else: ?>
		<tr>
			<td>No record</td>
		</tr>
<?php endif;?>
	</tbody>
</table>
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
</div>
<br>
</div>
<?php echo form_close();?>
<?php $this->load->view('footer');?>