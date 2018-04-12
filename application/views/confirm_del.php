<!DOCTYPE HTML>

<html lang="en">
	<head>
		<title>IMS - Delivery Confirmation</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" >
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min-select.css" >
		<script src="<?php echo base_url();?>bootstrap/js/jquery.min.js"></script>

		<script type="text/javascript">

			//This will let you enter numbers only and the decimal point ones.
			$(window).load(function(){
				$('#qa').keypress(function(event) {
				  if(event.which < 46 || event.which >= 58 || event.which == 47) {
				    event.preventDefault();
				  }

				  if(event.which == 46 && $(this).val().indexOf('.') != -1) {
				    this.value = '' ;
				  }  
				});

				$('#qr').keypress(function(event) {
				  if(event.which < 46 || event.which >= 58 || event.which == 47) {
				    event.preventDefault();
				  }

				  if(event.which == 46 && $(this).val().indexOf('.') != -1) {
				    this.value = '' ;
				  }  
				});
			});

			function get_Total(){
				var total=0;

				var qty_acc = document.getElementById('qa');
				var qty_ret = document.getElementById('qr');
				var tot = document.getElementById('total');

				total = parseFloat(qty_acc.value) - parseFloat(qty_ret.value);

				tot.value = total;
			}

			function auto_subtract_qa(){

				var diff1=0;

				var qty_acc = document.getElementById('qa');
				var qty_ret = document.getElementById('qr');
				var diff2 = document.getElementById('total2');
		
				diff1 = parseFloat(diff2.value) - parseFloat(qty_ret.value);

				qty_acc.value = diff1;

			}	

			function auto_subtract_qr(){

				var diff1=0;

				var qty_acc = document.getElementById('qa');
				var qty_ret = document.getElementById('qr');
				var diff2 = document.getElementById('total2');
		
				diff1 = parseFloat(diff2.value) - parseFloat(qty_acc.value);

				qty_ret.value = diff1;

			}

		</script>

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
	</head>

	<body onload="get_Total();">
		<?php echo form_open('main/confirm_success');?>
				
			<?php if(isset($cdel_rec)): foreach($cdel_rec as $rec): if($rec->po_num == $po_num AND $rec->do_num == $do_num): ?>

				<?php

					$variance = $rec->var_qty;

					// $variance = number_format($variance,2);

					// DISPLAY MESSAGE IF HAS VARIANCE
					if($variance <> 0){
						$del_stats = 'Accepted '.$rec->acc_qty.' | '.'Returned '.$variance;
					}else{
						$del_stats = 'Accepted '.$rec->acc_qty;
					}

					$arr_remarks = $rec->arr_remarks;
					$can_remarks = $rec->can_remarks;

					if($can_remarks <> NULL){
						$remarks = $arr_remarks.' | '.$can_remarks;
					}else{
						$remarks = $arr_remarks;
					}


					// DISPLAY TIME IN AM OR PM FOR SHIPMENT TIME============================================= 
					$shiptime = $rec->ship_time;
					$shiptime = substr($shiptime, 0, -3);

					if($shiptime[0]=='0'){
						$time = substr($shiptime, -4, 1);
					}else{
						$time = substr($shiptime, 0, 2);
					}

					$time2 = (int)$time;
					if($shiptime=="00:00"){
						$shiptime="";
					}else{
						if($time2 >=12 and $time2 <=23){
							$shiptime = $shiptime." PM";
						}else{
							$shiptime = $shiptime." AM";
						}
					}

					// DISPLAY TIME IN AM OR PM FOR ARRIVAL TIME============================================= 
					$arrtime = $rec->arr_time;
					$arrtime = substr($arrtime, 0, -3);

					if($arrtime[0]=='0'){
						$time1 = substr($arrtime, -4, 1);
					}else{
						$time1 = substr($arrtime, 0, 2);
					}

					$time3 = (int)$time1;
					if($arrtime=="00:00"){
						$arrtime="";
					}else{
						if($time3 >=12 and $time3 <=23){
							$arrtime = $arrtime." PM";
						}else{
							$arrtime = $arrtime." AM";
						}
					}

				?>

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
								<!-- <div class="panel-heading" style="background-color: #317070; color:white;"><strong>Delivery Confirmation</strong></div> -->
								<div class="panel-body form-inline">
										<label style="font-size: 20px;">Email Delivery Confirmation</label><br/>
									<div class="cbox">
										<table class="table table-bordered table-sm">
											<tr>
												<td><label>Customer Name</label></td>
												<td><label><?php echo $rec->cust_name; ?></label></td>
											</tr>
											<tr>
												<td><label>Purchase Order</label></td>
												<td><label><?php echo $rec->po_num; ?></label></td>
											</tr>
											<tr>
												<td><label>Delivery Order #</label></td>
												<td><label><?php echo $rec->do_num; ?></label></td>
											</tr>
											<tr>
												<td><label>DR / WIS / ATW #</label></td>
												<td><label><?php echo $rec->dr_num; ?></label></td>
											</tr>
											<tr>
												<td><label>Reference No. 3</label></td>
												<td><label><?php echo $rec->ref3; ?></label></td>
											</tr>
											<tr>
												<td><label>Item Description</label></td>
												<td><label><?php echo $rec->item_code; echo"&nbsp;"; echo $rec->item_desc; ?></label></td>
											</tr>
											<tr>
												<td><label>Delivery Qty / Unit</label></td>
												<td><label><?php echo $rec->del_qty; echo"&nbsp;"; echo $rec->del_uom; ?></label></td>
											</tr>
											<tr>
												<td><label>Accepted Qty / Unit</label></td>
												<td><label><?php echo $rec->acc_qty; echo"&nbsp;"; echo $rec->acc_uom; ?></label></td>
											</tr>
											<tr>
												<td><label>Variance Qty / Unit</label></td>
												<td><label><?php echo $rec->var_qty; echo"&nbsp;"; echo $rec->acc_uom; ?></label></td>
											</tr>
											<tr>
												<td><label>Delivery Destination</label></td>
												<td><label><?php echo $rec->del_desti; ?></label></td>
											</tr>
											<tr>
												<td><label>Shipment Date and Time</label></td>
												<td><label><?php echo $rec->ship_date; echo ' '; echo $shiptime; ?></label></td>
											</tr>
											<tr>
												<td><label>Arrival Date and Time</label></td>
												<td><label><?php echo $rec->arr_date; echo ' '; echo $arrtime; ?></label></td>
											</tr>
											<tr>
												<td><label>Unloading Finish Time</label></td>
												<td><label><?php echo $rec->uld_ftime; ?></label></td>
											</tr>
											<tr>
												<td><label>Delivery Status</label></td>
												<td><label><?php echo $del_stats; ?></label></td>
											</tr>
											<tr>
												<td><label>DR Remarks</label></td>
												<td><label><?php echo $rec->dr_remarks; ?></label></td>
											</tr>
											<tr>
												<td><label>Transporter Remarks</label></td>
												<td><label><?php echo $remarks; ?></label></td>
											</tr>
										</table>

										<label style="font-size: 20px;">For Customer Entry</label><br/>
										<table class="table">
											<tr>
												<td><label>Quantity Accepted</label></td>
												<td><input type="text" name="qa" id="qa" class="form-control" value="<?php echo $rec->del_qty; ?>" onblur="if(this.value=='')this.value='0';" onChange="auto_subtract_qr()" onfocus="if(this.value=='')this.value='0'" /></td>
											</tr>
											<tr>
												<td><label>Quantity Returned</label></td>
												<td><input type="text" name="qr" id="qr" class="form-control" value="0" onblur="if(this.value=='')this.value='0';" onChange="auto_subtract_qa()" onfocus="if(this.value=='')this.value='0'" /></td>
											</tr>
											<tr>
												<td><label>Total</label></td>
												<td><input type="text" name="total" id="total" class="form-control" readonly /></td>
											</tr>
											<tr>
												<td><label>Remarks</label></td>
												<td><textarea class="form-control" name="cust_rmks" style="width: 250px;"></textarea></td>
											</tr>
										</table>
										
										<input type="hidden" value="<?php echo $code; ?>" name="code"/>
										<input type="hidden" value="<?php echo $po_num; ?>" name="pn"/>
										<input type="hidden" value="<?php echo $do_num; ?>" name="dn"/>
										<input type="hidden" value="<?php echo $rec->del_qty; ?>" name="total2" id="total2"/>

										<?php if(isset($error)):?><?php echo $error;?><?php endif;?>

										<input type="submit" class="btn btn-info" value="Confirm" />
										<!-- <button class="btn btn-danger">Cancel</button> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			<?php endif; endforeach; endif; ?>

		<?php echo form_close();?>

	</body>


</html>