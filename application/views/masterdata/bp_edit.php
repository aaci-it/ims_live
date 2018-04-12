<?php $this->load->view('header');?>
<?php echo form_open();?>
<h3>Business Partner</h3>
<table>
<?php foreach ($records as $r):?>
	<tr>
		<td>Code:<?php $bpcode = array('name'=>'bpcode','value'=>$r->CardCode); echo form_input($bpcode);?></td>
		<td>Name:<?php $bpname=array('name'=>'bpname','value'=>$r->CardName,'maxlength'=> '50','size'=> '50'); echo form_input($bpname);?></td>
	</tr>
	<tr>
		<?php if ($r->Status==1):?>
		<td><?php echo form_checkbox('active[1]','1',true);?>Active</td>
		<?php else:?>
		<td><?php echo form_checkbox('active[0]','0',false);?>Active</td>
		<?php endif;?>
	</tr>
	<tr>
		<td><?php echo form_submit('update','Update');?></td>
	</tr>
<? endforeach;?>
</table>
<?php if(isset($duplicate)){
echo $duplicate;}?>
<?php echo validation_errors();?>
<?php echo form_close();?>
<?php $this->load->view('footer');?>