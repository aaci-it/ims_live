<?php $this->load->view('header');?>
<?php echo form_open();?>
<?php foreach ($records as $r):?>

<table>
	<tr>
		<td>Are you sure you want to remove </td>
		<td>Description:<?php $name = array('name'=>'iname','value'=>$r->item_name,'maxlength'=>'50','size'=>'50'); echo form_input($name);?></td>
	</tr>
	<tr>
		<td><?php if($r->item_status ==1){
			echo form_checkbox('active[1]','1',true);}
		else{
			echo form_checkbox('active[0]','0',false);}?>Active</td>
	</tr>
	<tr>
		<td><?php echo form_submit('update','Update');?></td>
	</tr>
</table>
<?php endforeach;?>
<?php if(isset($duplicate)){
echo $duplicate;}?>
<?php echo validation_errors();?>
<?php echo form_close();?>
<?php $this->load->view('footer');?>