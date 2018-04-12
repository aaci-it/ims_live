<!DOCTYPE HTML>

<html lang="en">
	<head>
		<title>IMS - Thank You</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" >
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min-select.css" >
		<script src="<?php echo base_url();?>bootstrap/js/jquery.min.js"></script>

		<style type="text/css">
			body{
				background: #ebebeb;
			}
			.container-fluid{
				width: 50%;
				margin-top: 2%;


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
				margin:	auto;
				position: absolute;
				left:50%;
				transform: translate(-50%,-50%)
			}
			.logo img{
				width: 100%;
				height: 20%;
			}
			
			.btn{
				border-radius: 0 !important;
			}
			.navbar{
				border-radius: 0px;
			}
			.nav-item{
				padding-top: 10px;
				padding-left: 10px;
			}
			.cbox label{
				/*width: 100%;*/
				padding-left: 10px;
			}
			.table{
				padding: -10px;
			}

			#login_page{
				width: 100%;
				text-align: center;
				margin-top: -20px;
			}

		</style>

		<script type="text/javascript">

			(function (global) { 

			    if(typeof (global) === "undefined") {
			        throw new Error("window is undefined");
			    }

			    var _hash = "!";
			    var noBackPlease = function () {
			        global.location.href += "#";

			        // making sure we have the fruit available for juice (^__^)
			        global.setTimeout(function () {
			            global.location.href += "!";
			        }, 50);
			    };

			    global.onhashchange = function () {
			        if (global.location.hash !== _hash) {
			            global.location.hash = _hash;
			        }
			    };

			    global.onload = function () {            
			        noBackPlease();

			        // disables backspace on page except on input fields and textarea..
			        document.body.onkeydown = function (e) {
			            var elm = e.target.nodeName.toLowerCase();
			            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
			                e.preventDefault();
			            }
			            // stopping event bubbling up the DOM tree..
			            e.stopPropagation();
			        };          
			    }

			})(window);

		</script>

	</head>

	<body>
		
		<div class="container-fluid">
			<nav class="navbar navbar-full navbar-dark bg-primary">
					<ul class="nav navbar-nav" id="navi">
		  				<li class="navbar-brand" style="background: white;">
		  					<img style="height: 30px; width: 30px; margin-top: -5px;"src="<?php echo base_url(); ?>images/aaci_icon.ico">
		  				</li>
		 					<li class="nav-item" style="font-size: 20px;"><label>All Asian Countertrade, Inc.</label></li>
		  			</ul>	
				</nav>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body form-inline">
							<label>Thank you for your business.</label>
							<input type="hidden" name="code" value="<?php echo $code; $this->input->post('code');?>">
						</div>
					</div>
				</div>
				<div class="col-md-12" id="login_page">
					<br>
					<strong><a href='<?php echo base_url(); ?>index.php/main/confirmation_delivery_report_customer_onsite/<?php echo $this->input->post('code');?>'>Click this link to view report</a></strong>
				</div>
			</div>
		</div>
				
	</body>

</html>