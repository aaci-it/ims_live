<!DOCTYPE HTML>

<html lang="en">
	<head>
		<title>IMS - Customer Login</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" >
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min-select.css" >
		<script src="<?php echo base_url();?>tablesorter-master/js/jquery-3.0.0.min.js"></script>

		<style type="text/css">
			body{
				background: #ebebeb;
			}
			.container-fluid{
				position: absolute;
				margin: auto;
				top: 0;
				right: 0;
			 	bottom: 0;
			 	left: 0;
			 	width: 400px;
			 	height: 70%;
			}
			.button input{
				font-weight: bold;
			}
			.fpass{
				float: right;
			}
			.fpass a{
				font-weight: normal;
			}
			.logo img{
				width: 340px;
				height: 110px;
			}
			.btn{
				border-radius: 0 !important;
			}

			#footer{
			   position:fixed;
			   left:0px;
			   bottom:0px;
			   height:40px;
			   width:100%;
			   background:#337ab7;
			   padding: 10px;
			}

			#footer label{
				font-size: 11px;
				line-height: 0px;
				color: white;
				letter-spacing: 1.5px;
				font-weight: normal;
			}

			#sign-up{
				width: 100%;
				text-align: center;
				margin-top: -20px;
			}

		</style>

		<script type="text/javascript">

			$(document).ready(function(){

				$('#uname').focus();

			});

		</script>
	</head>

	<body>
		<?php echo form_open(current_url());?>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="logo"><img src="<?php echo base_url(); ?>images/aaci_logo_plain.png"></div>
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #337ab7; color:white;"><strong>Customer Login</strong></div>
							<div class="panel-body">

								<p><strong><?php if(isset($error)){echo $error;} ?></strong></p>

								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-user"></span>
									</span>
									<input type="text" id="uname" class="form-control" placeholder="Username" name="uname" required value="<?php echo $this->input->post('uname'); ?>"/>
								</div><br/>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-lock"></span>
									</span>
									<input type="password" id="pword" class="form-control" placeholder="Password" name="pword" required value="<?php echo $this->input->post('pword'); ?>"/>
								</div><br/>

								<input type="hidden" name="ccode" value="<?php echo $ccode; echo $this->input->post('ccode');?>">

								<div class="button">
									<input type="submit" class="btn btn-success" value="LOGIN" name="submit"/>
									<input type="Reset" class="btn btn-danger" value="RESET"/>
								</div>
								<div class="fpass">
									<strong><a href='<?php echo base_url(); ?>index.php/main/customer_forgot_password/<?php echo $ccode; echo $this->input->post('ccode');?>'>Forgot Password?</a></strong>
								</div><br>
								
							</div>
							<div class="col-md-12" id="sign-up">
									<hr>
									<label style="font-weight: normal;">Doesn't have an account</label>
									<strong><a href='<?php echo base_url(); ?>index.php/main/customer_registration/<?php echo $ccode; echo $this->input->post('ccode');?>'>Sign Up Here</a></strong>
								</div>
						</div>
					</div>
				</div>
			</div>

			<div id="footer">
				<p style="text-align:center;">
					<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
				</p>
			</div>
			
		<?php echo form_close();?>

	</body>


</html>