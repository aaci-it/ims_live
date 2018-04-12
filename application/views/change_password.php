<?php
if(!$this->session->userdata('logged_in')){ redirect('main/logout'); }
?>
<!doctype html>
<html lang="en-US">
	<head>
		<title>IMS - Change Password</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" >
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min-select.css" >

		<style type="text/css">
			body{
				background: #ebebeb;
			}
			.container-fluid{
				width: 30%;
				margin-top: 8%;
			}
			.button input{
				font-weight: bold;
			}
			.fpass{
				align-items: center;
				display: flex;
				justify-content: center;
			}
			.footer{
				margin-top: 50px;
				position: absolute;
				left:50%;
				transform: translate(-50%,-50%)
			}
			.footer label{
				font-size: 12px;
				font-weight: 100;
				line-height: 10px;
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
		</style>
	</head>
<body>
			<?php echo form_open('main/change_password');?>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="logo"><img src="<?php echo base_url(); ?>images/aaci_logo_plain.png"></div>
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #317070; color:white;"><strong>Reset Password</strong></div>
							<div class="panel-body">
								<?php if($reset != NULL): ?>
									<label><?php echo $reset; ?></label>
								<?php endif ?>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-lock"></span>
									</span>
									<input type="password" class="form-control" placeholder="Current Password" name="cpword" required maxlength="25" size="20"/>
								</div><br/>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-lock"></span>
									</span>
									<input type="password" class="form-control" placeholder="New Password" name="npword" required maxlength="25" size="20"/>
								</div><br/>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-lock"></span>
									</span>
									<input type="password" class="form-control" placeholder="Re-Type Password" name="npword2" required maxlength="25" size="20"/>
								</div><br/>
								<div class="button">
									<input type="submit" class="btn btn-warning" value="RESET" name="go"/>
									<a href="<?php echo site_url('main/index'); ?>"><input class="btn btn-info" value="BACK" style="width: 80px;"></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer">
				<br/>
				<p style="text-align:center;">
				<label>Inventory Monitoring</label><br/>
				<label>All Asian Countertrade Inc. | ICT Department</label><br/>
				<label>© 2014 - Warehouse Management System</label><br/>
				</p>
			</div>

	<?php echo form_close();?>
</body>	
</html>
	