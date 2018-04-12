<?php $this->load->view('header');?>
<div id="body">
<?php echo form_open();?>
<div class="footer">
<h3>Business Partner Commodity</h3>
<?php foreach ($records as $r):?>
	<h4>Code:<?php $bpcode = array('name'=>'bpcode','value'=>$r->CardCode,'readonly'=>true,'readonly'=>true); echo form_input($bpcode);?>
		Name:<?php $bpname=array('name'=>'bpname','value'=>$r->CardName,'maxlength'=> '50','size'=> '50','readonly'=>true); echo form_input($bpname);?>
	</h4>
	<h4>
		Item:<?php echo form_dropdown('bpitem',$item);?><?php echo form_submit('add','Add');?>
	</h4>
		<?$bpid = $r->CardCode;
 endforeach;?>
<table id="myTable" class="tablesorter">
	<thead>
	<tr>
		<th>Item Code</th>
		<th>Item Name</th>
		<th>Action</th>
	</tr>
	</thead>
	<tbody>
<?php if (isset($bpitem)): foreach($bpitem as $r1):?>
	<tr>
		<td><?php echo $r1->comm__id;?></td>
		<td><?php echo $r1->comm__name;?></td>
		<td><?php echo anchor('main/businesspartner_remove_item/'.$bpid.'/'.$r1->bi_code,'Remove');?></td>
		
	</tr>
<?php endforeach;?>
	</tbody>
<?php else:?>
	<tr>
		<td>No record</td>
	</tr>
<?php endif;?>
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
<br>
</div>
<?php echo form_close();?>
</div>
<?php $this->load->view('footer');?>