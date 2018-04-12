<?php

$logged = $this->session->userdata('validated'); 
echo validation_errors();
if($logged == FALSE AND $this->uri->segment(3) != 'login'){ echo anchor('main/index/login','Login'); }


if($logged == TRUE){
	echo anchor('main/index/register','Register');
	echo anchor('main/logout', 'Logout');
}


$username = array('name'=>'username','id'=>'username','maxlength'=>'25','size'=> '20','placeholder'=> 'Username');
$password = array('name'=>'password','id'=> 'password','maxlength'=>'25','size'=>'20','placeholder'=>'Password');
$email = array('name'=>'email','id'=>'email','maxlength'=>'25','size'=> '20','placeholder'=> 'Email');
$name = array('name'=>'name','id'=>'name','maxlength'=>'25','size'=> '20','placeholder'=> 'Name');
$department = array('---'=>'---','Trading'=>'Trading','Treasury'=>'Treasury','Logistics'=>'Logistics','HR'=>'HR','Admin'=>'Admin','IT'=>'IT');
$location = array('---'=>'---','Makati'=>'Makati','Bacolod'=>'Bacolod');
$userlevel = array(0=>'Normal',1=>'Super User');

$site = '';

//echo $this->uri->segment(2);
if($this->uri->segment(3) == 'register'){ $site = 1; } //Register
if($this->uri->segment(3) == 'login'){ $site = 2; } //Login
if($this->uri->segment(3) == 'update'){ $site = 3; } //Account Maintenance
if($this->uri->segment(3) == 'reset'){ $site = 4; } //Account Maintenance
?>
<?php if($site == 1){ ?>
	<div id="register">
		<h4>User Registration</h4>
		<?php echo form_open('main/register'); ?>
		<ul>
			<li><?php echo form_input($username); ?></li>
			<li><?php echo form_password($password); ?></li>
			<li><?php echo form_input($email); ?></li>
			<li><?php echo form_input($name); ?></li>
			<li><?php echo 'Department '.form_dropdown('department',$department); ?></li>
			<li><?php echo 'Location '.form_dropdown('location',$location); ?></li>
			<li><?php echo 'User Level '.form_dropdown('userlevel',$userlevel); ?></li>
			<li><?php echo form_submit('go', 'Go'); ?></li>
		</ul>
		<?php echo form_close(); ?>
	</div>
<?php } ?>

<?php if($site == 2 AND  $logged == FALSE){ ?>
	<div id="login">
		<h4>Log in</h4>
		<?php echo form_open('main/login'); ?>
		<ul>
			<li><?php echo form_input($username); ?></li>
			<li><?php echo form_password($password); ?></li>
			<li><?php echo form_submit('go', 'Go'); ?></li>
		</ul>
		<?php echo form_close(); ?>
	</div>
<?php } ?>

<?php if($site == 3){ ?>
	<div id="update">
		<h4>Account Maintenance</h4>
		<?php echo form_open('main/update'); ?>
		<ul>
			<li><?php echo form_input($username); ?></li>
			<li><?php echo form_input($email); ?></li>
			<li><?php echo form_input($name); ?></li>
			<li><?php echo 'Department '.form_dropdown('department',$department); ?></li>
			<li><?php echo 'Location '.form_dropdown('location',$location); ?></li>
			<li><?php echo 'User Level '.form_dropdown('userlevel',$userlevel); ?></li>
			<li><?php echo form_submit('go', 'Go'); ?></li>
		</ul>
		<?php echo form_close(); ?>
	</div>
<?php } ?>

<?php if($site == 4){ ?>
	<div id="reset">
		<h4>Reset Password</h4>
		<?php 
			echo form_open('main/reset'); 
			echo form_input($username);
			echo form_submit('reset', 'Reset');
			echo form_close();
		?>
	</div>
<?php } ?>