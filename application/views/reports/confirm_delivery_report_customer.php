<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php echo $this->load->view('header');?>

<?php
	
	date_default_timezone_set("Asia/Manila");
	$cdate = date('Y-m-d');

	$sdate_from="";
	$sdate_to=""; 
	$sdate_from = array('name'=>'sdate_from', 'value'=>$cdate, 'id'=>'datepicker', 'data-format'=>'yyyy-MM-dd', 'onkeypress'=>'return isNumberKey(event)');
	$sdate_to = array('name'=>'sdate_to', 'value'=>$cdate,'id'=>'datepicker', 'data-format'=>'yyyy-MM-dd', 'onkeypress'=>'return isNumberKey(event)');
	$area = array('LUZON', 'VISAYAS', 'MINDANAO');
	$custchk =array('name'=>'all_cust','value'=>'accept', 'checked'=>false);

	date_default_timezone_set("Asia/Manila");
	$datetime = date('Y-m-d');

	$xls_name = "Confirmation_Delivery_Report_Customer_".$datetime.".xls";

?>

<?php echo form_open();?>

<!-- DT Picker -->
 	<link rel="stylesheet" href="<?php echo base_url();?>DTPicker/css/bootstrap-datetimepicker.min.css" >
    <script src="<?php echo base_url();?>DTPicker/js/bootstrap-datetimepicker.min.js"></script>
    <!-- End of File -->

    <script type="text/javascript">
	  $(function() {
	    $('#datepicker').datetimepicker({
	      pickTime: false
	    });
	    $('#datepicker1').datetimepicker({
	      pickTime: false
	    });
	  });

	  function fnExcelReport()
		{
		    var tab_text = '<table border="1px" style="font-size:12px" ">';
		    var textRange; 
		    var j = 0;
		    var tab = document.getElementById('myTable'); // id of table
		    var lines = tab.rows.length -1;

		    // the first headline of the table
		    if (lines > 0) {
		        tab_text = tab_text + '<tr bgcolor="#DFDFDF">' + tab.rows[0].innerHTML + '</tr>';
		    }

		    // table data lines, loop starting from 1
		    for (j = 2 ; j < lines; j++) {     
		        tab_text = tab_text + "<tr>" + tab.rows[j].innerHTML + "</tr>";
		    }

		    tab_text = tab_text + "</table>";
		    tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");             //remove if u want links in your table
		    tab_text = tab_text.replace(/<img[^>]*>/gi,"");                 // remove if u want images in your table
		    tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, "");    // reomves input params
		    tab_text = tab_text.replace(/<select[^>]*>|<\/select>/gi, "");    // reomves select params
		    tab_text = tab_text.replace(/<tfoot[^>]*>|<\/tfoot>/gi, "");    // reomves tfoot params
		    
		    console.log(tab_text); // aktivate so see the result (press F12 in browser)

		    var ua = window.navigator.userAgent;
		    var msie = ua.indexOf("MSIE "); 

		     // if Internet Explorer
		    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
		        txtArea1.document.open("txt/html","replace");
		        txtArea1.document.write(tab_text);
		        txtArea1.document.close();
		        txtArea1.focus(); 
		        sa = txtArea1.document.execCommand("SaveAs", true, "DataTableExport.xls");
		    }  
		    else // other browser not tested on IE 11
		        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

		    return (sa);
		}

		function isNumberKey(evt)
	       {
	          var charCode = (evt.which) ? evt.which : evt.keyCode;
	          if (charCode != 45 && charCode > 31 
	            && (charCode < 48 || charCode > 57))
	             return false;

	          return true;
	       }

	    $(document).ready(function(){

			$('#export_xls a').click(function(){
				$('#tft').remove();
				return ExcellentExport.excel(this, 'myTable', 'Confirmation_Delivery_Report');
			});

		});

	</script>

	<style type="text/css">
		#pbody label{
			width: 150px;
		}
		table{
			width: 150px;
			margin: auto;
		}
		.hidden_fields{
			opacity: 0;
			cursor: default;
		}

		#export_xls a{
			color: white;
			text-decoration: none; 
		}

		th, td{width:100px !important;white-space:nowrap !important;}
	</style>


<h5>
	<?php if(isset($user)): foreach($user as $rcd): ?>
    <div class="container-fluid">
	    <div class="row">
	        <div class='col-sm-12'>
	        	<div class="panel panel-default">
	        		<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Delivery Confirmation Report - Customer</strong></div>
	        		<div class="panel-body form-inline" id="pbody">
	        			<div class="col-md-4">
	        				<label>Customer Name</label>
	        				<?php echo form_dropdown('cname',$cuslist,$this->input->post('cname'));?><br/><br/>
	        				<label>Shipment Date From</label>
							<div class='input-group date' id='datepicker'>
				                 <?php echo form_input($sdate_from); ?>
									<span class="add-on">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div><br/><br/>
	   
	        			</div>
	        			<div class="col-md-4">
	        				<?php if($rcd->memb_comp == 'AACI'): ?>

	        					<label>All Customers</label>
	        					<?php echo form_checkbox($custchk); ?><br/><br/>

	        				<?php else: ?>

	        					<div class="hidden_fields">
	        						<label>All Customers</label>
	        						<input type="text" readonly><br/></br>
	        					</div>

	        				<?php endif; ?>
	        				<label>Shipment Date To</label>
							<div class='input-group date' id='datepicker1'>
				                 <?php echo form_input($sdate_to); ?>
									<span class="add-on">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div><br/><br/>
	        				
	        			</div>
	        			<div class="col-md-4">
	        				<label>Area</label>
	        				<?php echo form_dropdown('area',$area,$this->input->post('area'));?><br/><br/>
	        				<label>Destination</label>
	        				<input type="text" name="desti" /><br/><br/>

							<input type="hidden" name="tname" value="<?php echo $rcd->memb_comp; ?>"/>

							<input type="submit" name="Search" value="Search" class="btn btn-info"/>
							<button class="btn btn-warning" id="export_xls"><a download="<?php echo $xls_name; ?>" href="#">Export to Excel</a></button><br/><br/><br/>

	        			</div>

	        			<div class="col-md-12">
	        			<div class="table-responsive">
							<table id="myTable">
								<thead>
									<tr>
										<th>Year</th>
										<th>Month</th>
										<th>Status</th>
										<th>Area</th>
										<th>Delivery Doc Date</th>
										<th>Name</th>
										<th>Destination</th>
										<th>PO Number</th>
										<th>Reference No. 3</th>
										<th>DO No.</th>
										<th>DR</th>
										<th>ATW</th>
										<th>WIS</th>
										<th>Item Code</th>
										<th>Item Description</th>
										<th>UoM</th>
										<th>Delivery Qty</th>
										<th>Accepted Qty</th>
										<th>Returned Qty</th>
										<th>Transporter</th>
										<th>Truck Plate No</th>
										<th>Shipment Date</th>
										<th>Arrival Date at Destination</th>
										<th>Arrival Time</th>
										<th>Unloading Finish Time</th>
										<th>User Name</th>
										<th>Confirmation DateTime</th>
										<th>DO Remarks</th>
										<th>Transporter's Remarks</th>
										<th>Customer Remarks</th>
									</tr>
								</thead>
								<tfoot id="tft">
										<tr>
											<th colspan="33" class="ts-pager form-horizontal">
												<button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
												<button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
												<span class="pagedisplay"></span> <!-- this can be any element, including an input -->
												<button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
												<button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
												<select class="pagesize input-mini" title="Select page size">
													<option selected="selected" value="10">10</option>
													<option value="20">20</option>
													<option value="30">30</option>
													<option value="40">40</option>
												</select>
												<select class="pagenum input-mini" title="Select page number"></select>
											</th>
										</tr>
								</tfoot>
								<tbody>
									<tr><?php if($records): foreach ($records as $r): ?>

										<?php
											$deldate = $r->deldate; 
											$year = substr($deldate, 0, 4);
											$month = substr($deldate, 5, 2); 
										?>

										<td><?php echo $year; ?></td>
										<td>
											<?php

												$mon_temp = $month;

												if($mon_temp == '01'){
													$month = 'JAN';
												}elseif($mon_temp == '02'){
													$month = 'FEB';
												}elseif($mon_temp == '03'){
													$month = 'MAR';
												}elseif($mon_temp == '04'){
													$month = 'APR';
												}elseif($mon_temp == '05'){
													$month = 'MAY';
												}elseif($mon_temp == '06'){
													$month = 'JUN';
												}elseif($mon_temp == '07'){
													$month = 'JUL';
												}elseif($mon_temp == '08'){
													$month = 'AUG';
												}elseif($mon_temp == '09'){
													$month = 'SEP';
												}elseif($mon_temp == '10'){
													$month = 'OCT';
												}elseif($mon_temp == '11'){
													$month = 'NOV';
												}elseif($mon_temp == '12'){
													$month = 'DEC';
												}

												echo $month; 
											?>
										</td>
										<td>
											<?php 

												$stats_temp = $r->confirm;

												if($stats_temp == 0){
													$status = 'In-Transit';
												}else{
													$status = 'Confirmed';
												}

												echo $status; 
											?>
										</td>
										<td><?php echo "AREA"; ?></td>
										<td><?php echo $r->deldate; ?></td>
										<td><?php echo $r->CardName; ?></td>
										<td><?php echo $r->wi_location; ?></td>
										<td><?php echo $r->wi_PONum; ?></td>
										<td><?php echo $r->wi_refnum3; ?></td>										
										<td>
											<?php 
												
												if($r->wi_reftype == 'DO'){
													echo $do = 'DO '.$r->wi_refnum;
												}elseif($r->wi_reftype2 == 'DO'){
													echo $do = 'DO '.$r->wi_refnum2;
												}else{
													echo $do = "-";
												}
							
											?>
										</td>
										<td>
											<?php 
												
												if($r->wi_reftype == 'DR'){
													echo $dr = 'DR '.$r->wi_refnum;
												}elseif($r->wi_reftype2 == 'DR'){
													echo $dr = 'DR '.$r->wi_refnum2;
												}else{
													echo $dr = "-";
												}
							
											?>
										</td>
										<td>
											<?php 
												
												if($r->wi_reftype == 'ATW'){
													echo $atw = 'ATW '.$r->wi_refnum;
												}elseif($r->wi_reftype2 == 'ATW'){
													echo $atw = 'ATW '.$r->wi_refnum2;
												}else{
													echo $atw = "-";
												}
							
											?>
										</td>
										<td>
											<?php 
												
												if($r->wi_reftype == 'WIS'){
													echo $wis = 'WIS '.$r->wi_refnum;
												}elseif($r->wi_reftype2 == 'WIS'){
													echo $wis = 'WIS '.$r->wi_refnum2;
												}else{
													echo $wis = "-";
												}
							
											?>
										</td>
										<td><?php echo $r->item_id; ?></td>
										<td><?php echo $r->comm__name; ?></td>
										<td><?php echo $r->item_uom; ?></td>
										<td><?php echo $r->wi_doqty; ?></td>
										<td><?php echo $r->trk_acceptedqty; ?></td>
										<td><?php echo $r->trk_canceledqty; ?></td>
										<td>
											<?php 
												echo $r->truck_company; 
											?>
										</td>
										<td><?php echo $r->truck_platenum; ?></td>
										<td><?php echo $r->wi_expecteddeliverydate; ?></td>
										<td><?php echo $r->trk_arriveddate; ?></td>
										<td>
											<?php 

												$truck_atime = $r->trk_arrivedtime;
												$truck_atime = substr($truck_atime, 0, -3);

												if($truck_atime[0]=='0'){
													$time1 = substr($truck_atime, -4, 1);
												}else{
													$time1 = substr($truck_atime, 0, 2);
												}

												$time2 = (int)$time1;
												if($truck_atime=="00:00"){
													$truck_atime="";
												}else{
													if($time2 >=12 and $time2 <=23){
														$truck_atime = $truck_atime." PM";
													}else{
														$truck_atime = $truck_atime." AM";
													}
												}

												echo $truck_atime; 
											?>
										</td>
										<td><?php echo $r->trk_acceptedutime; ?></td>
										<td><?php echo $r->cust_username; ?></td>
										<td><?php echo $r->cdel_date; echo " "; echo $r->cdel_time; ?></td>
										<td><?php echo $r->dr_remarks; ?></td>
										<td><?php echo $r->trk_acceptedremarks; echo " "; echo $r->trk_canceledremarks; ?></td>
										<td><?php echo $r->cust_rmks; ?></td>

									</tr>
									<?php endforeach;?>
									<?php else:?>
								<tr>
									<td colspan=33>No Record</td>
								</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
						</div>
	        		</div>
	        	</div>
	        </div>
	    </div>
	</div>
<?php endforeach; ?>
<?php endif; ?>
</h5><br>

<div id="footer">
	<p style="text-align:center;">
		<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
	</p>
</div>


<?php echo form_close();?>

