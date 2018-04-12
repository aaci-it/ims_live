
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>IMS-Forgot Password</title>
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" >
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min-select.css">
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

			#btn_back a{
				font-weight: bold;
				color: white;
				text-decoration: none;
			}

		</style>
		<script type="text/javascript">

			$(document).ready(function(){

				$('#email').focus();

				<?php if($reset != NULL): ?>
					$('#info_msg').show();
					$('#email').val("");
					$('#email').focus();

				<?php else: ?>
					$('#info_msg').hide();
				<?php endif; ?>

			});

		</script>
</head>
<body>
		<?php echo form_open('main/customer_forgot_password');?>
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
										<span class="glyphicon glyphicon-envelope"></span>
									</span>
									<input type="text" id="email" class="form-control" placeholder="Email" name="email" required maxlength="35" size="35"/>
								</div><br/>

								<input type="hidden" name="ccode" value="<?php echo $ccode; echo $this->input->post('ccode');?>">

								<div class="button">
									<input type="submit" class="btn btn-warning" value="RESET" name="go"/>
									<button type="button" class="btn btn-danger" id="btn_back"><a href='<?php echo base_url(); ?>index.php/main/customer_login/<?php echo $ccode; echo $this->input->post('ccode');?>'>BACK</a></button>
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