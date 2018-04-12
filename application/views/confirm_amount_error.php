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
		</style>

		<script type="text/javascript">

			function GoBackWithRefresh(event) {
			    if ('referrer' in document) {
			        window.location = document.referrer;
			        /* OR */
			        //location.replace(document.referrer);
			    } else {
			        window.history.back();
			    }
			}

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
							<label>Invalid Quantity Accepted or Returned.</label>
							<button class="btn btn-danger" onclick="GoBackWithRefresh(event);" style="float: right;">Back</button>
						</div>
					</div>
				</div>
			</div>
		</div>
				
	</body>


</html>