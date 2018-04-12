<?php echo $this->load->view('header');?>
<?php 
$name = array('name'=>'usrname','id'=>'usrname','size'=>'35','placeholder'=>'Enter full name','value'=>set_value('usrname'));
$usrcode = array('name'=>'usrcode','id'=>'usrcode','size'=>'35','placeholder'=>'Enter user code','value'=>set_value('usrcode'));
$pword = array('name'=>'pword','id'=>'pword','size'=>'35','placeholder'=>'Enter password','value'=>set_value('pword'));
$email = array('name'=>'email','id'=>'email','size'=>'35','placeholder'=>'Enter valid email','value'=>set_value('email'));
$addr = array('name'=>'addr','id'=>'addr','placeholder'=>'Enter address','cols'=>'100','rows'=>'5','value'=>set_value('addr'));
$gender = array('Male' => 'Male','Female'=>'Female');
$status = array('1' => 'Super User','0'=>'Normal User');

if($this->uri->segment(4) == 'readonly'){$status = 1;}
else {$status=0;}

//echo form_open(current_url());
?>
<style>
	body { min-width: 520px; }
	.column { width: auto; float: left; padding-bottom: 100px; }
	.portlet { margin: 0 1em 1em 0; }
	.portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em; }
	.portlet-header .ui-icon { float: right; }
	.portlet-content { padding: 0.4em; }
	.ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
	.ui-sortable-placeholder * { visibility: hidden; }
</style>
<script>
	$(function() {
		$( ".column" ).sortable({
			connectWith: ".column"
		});

		$( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
			.find( ".portlet-header" )
				.addClass( "ui-widget-header ui-corner-all" )
				.prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
				.end()
			.find( ".portlet-content" );

		$( ".portlet-header .ui-icon" ).click(function() {
			$( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
			$( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
		});

		$( ".column" ).disableSelection();
	});
</script>

<div class="column">

	<div class="portlet">
		<div class="portlet-header">General Information</div>
		<div class="portlet-content">
			<table>
				<tr>
					<td>Name</td>
					<td><?php echo form_input($name);?></td>
					<td>Usercode</td>
					<td><?php echo form_input($usrcode);?></td>
					<td>Password</td>
					<td><?php echo form_password($pword);?></td>
				</tr>
			</table>
		</div>
	</div>

	<div class="portlet">
		<div class="portlet-header">Misc</div>
		<div class="portlet-content">
			<table width="950px">
				<tr>
					<td>Email</td>
					<td><?php echo form_input($email);?></td>
					<td>Status</td>
					<td><?php echo form_dropdown('status',$status,'0');?></td>
					<td>Gender</td>
					<td><?php echo form_dropdown('gender',$gender,'male');?></td>
					
				</tr>
			</table>
			<table>
				<tr>
					<td>Address</td>
					<td><?php echo form_textarea($addr);?></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="portlet">
		<div class="portlet-header">Action</div>
		<div class="portlet-content">
			<table width="950px">
				<tr>
					<td>Save info?</td>
					<td><?php echo form_submit('save','Save');?></td>
				</tr>
			</table>
			<table>
				<tr>
					<td class="loginError" width="950px"><?php echo validation_errors();?></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php echo form_close();?>
<?php echo $this->load->view('footer');?>