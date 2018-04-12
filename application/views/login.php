<!DOCTYPE HTML>

<html lang="en">
	<head>
		<title>IMS - Login</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" >
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min-select.css" >

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

		</style>
	</head>

	<body>
		<?php echo form_open(current_url());?>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="logo"><img src="<?php echo base_url(); ?>images/aaci_logo_plain.png"></div>
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #337ab7; color:white;"><strong>Inventory Management System</strong></div>
							<div class="panel-body">

								<p><strong><?php if(isset($error)){echo $error;} ?></strong></p>

								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-user"></span>
									</span>
									<input type="text" class="form-control" placeholder="Username" name="uname" required value="<?php echo $this->input->post('uname'); ?>"/>
								</div><br/>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-lock"></span>
									</span>
									<input type="password" class="form-control" placeholder="Password" name="pword" required value="<?php echo $this->input->post('pword'); ?>"/>
								</div><br/>
								<div class="button">
									<input type="submit" class="btn btn-success" value="LOGIN" name="submit"/>
									<input type="Reset" class="btn btn-danger" value="RESET"/>
								</div>
								<div class="fpass">
									<strong><?php echo anchor('main/forgot_password','Forgot Password?');?></strong>
								</div>
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