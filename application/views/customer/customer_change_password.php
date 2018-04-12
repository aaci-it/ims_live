
<!doctype html>
<html lang="en-US">
	<head>
		<title>IMS - Change Password</title>
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
				align-items: center;
				display: flex;
				justify-content: center;
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
		<script type="text/javascript">

			$(document).ready(function(){

				$('#cpword').focus();

				<?php if($reset != NULL): ?>
					$('#info_msg').show();
					$('#cpword').val("");
					$('#npword').val("");
					$('#npword2').val("");

					$('#cpword').focus();

				<?php else: ?>
					$('#info_msg').hide();
				<?php endif; ?>

			});

		</script>
	</head>
<body>
			<?php echo form_open('main/customer_change_password');?>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="logo"><img src="<?php echo base_url(); ?>images/aaci_logo_plain.png"></div>
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #337ab7; color:white;"><strong>Reset Password</strong></div>
							<div class="panel-body">

								<div class="alert alert-info" id="info_msg">
									<?php if($reset != NULL): ?>
										<label><?php echo $reset; ?></label>
									<?php endif ?>
								</div>
								
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-lock"></span>
									</span>
									<input type="password" id="cpword" class="form-control" placeholder="Current Password" name="cpword" required maxlength="25" size="20"/>
								</div><br/>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-lock"></span>
									</span>
									<input type="password" id="npword" class="form-control" placeholder="New Password" name="npword" required maxlength="25" size="20"/>
								</div><br/>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-lock"></span>
									</span>
									<input type="password" id="npword2" class="form-control" placeholder="Re-Type Password" name="npword2" required maxlength="25" size="20"/>
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
			<div id="footer">
				<p style="text-align:center;">
					<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
				</p>
			</div>

	<?php echo form_close();?>
</body>	
</html>
	