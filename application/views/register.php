<?php
if(!$this->session->userdata('logged_in')){ redirect('main/index'); }
?>
<!doctype html>
<html lang="en-US">
<head>
	<title>IMS - Register</title>
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
				align-items: center;
				display: flex;
				justify-content: center;
			}
			.logo{
				position: relative;
				margin: auto;
				margin-left: 10px;
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

		<?php echo form_open('main/register');?>
					
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="logo"><img src="<?php echo base_url(); ?>images/aaci_logo_plain.png"></div>
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #337ab7; color:white;"><strong>User Registration</strong></div>
							<div class="panel-body">
								<?php if($reg_result != NULL): ?>
									<label><?php echo $reg_result; ?></label>
								<?php endif ?>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-user"></span>
									</span>
									<input type="text" class="form-control" placeholder="Username" name="username" required maxlength="25" size="20" value="<?php echo $this->input->post('username'); ?>"/>
								</div><br/>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-envelope"></span>
									</span>
									<input type="text" class="form-control" placeholder="Email Address" name="email" required maxlength="50" size="50" value="<?php echo $this->input->post('email'); ?>"/>
								</div><br/>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-user"></span>
									</span>
									<input type="text" class="form-control" placeholder="Name" name="name" required maxlength="25" size="20" value="<?php echo $this->input->post('name'); ?>"/>
								</div><br/>
								<div class="button">
									<input type="submit" class="btn btn-warning" value="REGISTER" name="go"/>
									<a href="<?php echo site_url('main/index'); ?>"><input class="btn btn-info" value="BACK" style="width: 80px;"></a>
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