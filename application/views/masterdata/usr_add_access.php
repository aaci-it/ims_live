<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>
<div id="body">
<?php echo form_open();?>
<div class="footer">
<?php $tokens = explode('/', current_url());?>
<h3>USER ACCESS</h3>
<?php //if (isset($uname)): foreach($uname as $r1):?>
	<h4>
		Name:<?php $bpname=array('name'=>'uname','value'=>$tokens[sizeof($tokens)-1],'maxlength'=> '50','size'=> '50','readonly'=>true); echo form_input($bpname);?>
	</h4>
<?php //endforeach;?>
	<h4>
		Warehouse:<?php echo form_dropdown('whouse',$whlist);?>
	</h4>

<table id="myTable" class="tablesorter">
	<thead>
		<tr>
			<th>Warehouse Access</th>
		</tr>
	</thead>
<tbody>
<?php if (isset($uwhlist)): foreach($uwhlist as $r1):?>
	<tr>
		<td><?php echo $r1->accessname;?></td>
		
		
	</tr>
<?php endforeach;?>

<?php else:?>
	<tr>
		<td>No record</td>
	</tr>
<?php endif;?>
</table>
<h4>Access:<?php echo form_dropdown('access',$aclist);?></h4>
	</tbody>
	<table id="myTable" class="tablesorter">
		<thead>
			<tr>
				<th>Item Code</th>
			</tr>
		</thead>
	<tbody>
<?php if (isset($ualist)): foreach($ualist as $r1):?>
	<tr>
		<td><?php echo $r1->accessname;?></td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr>
		<td>No record</td>
	</tr>
<?php endif;?>
</table>
	<h3><?php echo form_submit('add','Update');?></h3>

<br>
</div>
<?php echo form_close();?>
</div>
<?php $this->load->view('footer');?>