<!doctype html>
<html lang="en-US">
<head>
	<title>IMS - Customer Registration</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" >
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min-select.css" >
		<script src="<?php echo base_url();?>tablesorter-master/js/jquery-3.0.0.min.js"></script>
		<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  		<link rel="stylesheet" href="/resources/demos/style.css">
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

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
			#back_button a{
				text-transform: uppercase;
				color: white;
				font-weight: bold;
				text-decoration: none;
			}

			.ui-helper-hidden-accessible {
			    border: 0;
			    clip: rect(0 0 0 0);
			    height: 1px;
			    margin: -1px;
			    overflow: hidden;
			    padding: 0;
			    position: absolute;
			    width: 1px;
			}

			#cust_code{
				text-transform: uppercase;
			}

			::-webkit-input-placeholder { /* WebKit browsers */
			    text-transform: none;
			}
			:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
			    text-transform: none;
			}
			::-moz-placeholder { /* Mozilla Firefox 19+ */
			    text-transform: none;
			}
			:-ms-input-placeholder { /* Internet Explorer 10+ */
			    text-transform: none;
			}

		</style>

		<script type="text/javascript">

			$(document).ready(function(){
				$('#cust_code').focus();

				<?php if($reg_result != NULL): ?>
					$('#reg_result').show();
					$('#username').val("");
					$('#email').val("");
					$('#name').val("");

					$('#username').focus();

				<?php else: ?>
					$('#reg_result').hide();
				<?php endif; ?>

				<?php if(isset($error) OR (validation_errors())): ?>
					$('#error_msg').show();
				<?php else: ?>
					$('#error_msg').hide();
				<?php endif; ?>

				$('#cust_list').hide();

				// CODE FOR AUTOCOMPLETE TEXTBOX

				// var domelts = $('#cust_list option');

				// // Next, translate that into an array of just the values
				// var values = $.map(domelts, function(elt, i) { return $(elt).val();});

			 //    $( "#comp_name" ).autocomplete({
			 //      source: values
			 //    });

			});

		</script>

</head>
<body>

		<?php echo form_open('main/customer_registration');?>

		<?php
			$url_3 = $this->uri->segment(3);

			if($url_3 == ""){
				$rdy = "";
				$ccode2 = "";
			}else{
				$rdy = "readonly";
				$tokens = explode('_', $url_3);
				$ccode2 = $tokens[sizeof($tokens)-3];
			} 
		 ?>
					
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="logo"><img src="<?php echo base_url(); ?>images/aaci_logo_plain.png"></div>
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #337ab7; color:white;"><strong>Customer Registration</strong></div>
							<div class="panel-body">

								<div class="alert alert-info" id="reg_result">
									<?php if($reg_result != NULL): ?>
										<label><?php echo $reg_result; ?></label>
									<?php endif ?>
								</div>

								<div class="alert alert-danger" id="error_msg">
									<?php if(isset($error)): ?>
										<label><?php echo $error; ?></label>
									<?php endif; ?>
									<label><?php echo validation_errors(); ?></label>
								</div>

								<?php echo form_dropdown('cust_list', $cust_list, '', 'id="cust_list"'); ?>

								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-home"></span>
									</span>
									<input name="customer_code" id="cust_code" value="<?php echo $ccode2; echo $this->input->post('customer_code'); ?>" class="form-control" placeholder="Customer Code" <?php echo $rdy; ?> required>

								</div><br/>

								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-user"></span>
									</span>
									<input type="text" id="username" class="form-control" placeholder="Username" name="username" required maxlength="25" size="20" value="<?php echo $this->input->post('username'); ?>"/>
								</div><br/>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-envelope"></span>
									</span>
									<input type="text" id="email" class="form-control" placeholder="Email Address" name="email" required maxlength="50" size="50" value="<?php echo $this->input->post('email'); ?>"/>
								</div><br/>
								<div class="input-group input-group-md">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-user"></span>
									</span>
									<input type="text" id="name" class="form-control" placeholder="Name" name="name" required maxlength="25" size="20" value="<?php echo $this->input->post('name'); ?>"/>
								</div><br/>

								<input type="hidden" name="ccode" value="<?php echo $ccode; echo $this->input->post('ccode');?>">

								<div class="button">
									<input type="submit" class="btn btn-warning" value="REGISTER" name="customer_register"/>
									<button type="button" class="btn btn-danger" id="back_button"><a href='<?php echo base_url(); ?>index.php/main/customer_login/<?php echo $ccode; echo $this->input->post('ccode'); ?>'>Back</a></button>
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