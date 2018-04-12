<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php echo $this->load->view('header');?>

<?php 
$sdate = array('name'=>'sdate','value'=>$sdate,'id'=>'datepicker', 'data-format'=>'yyyy-MM-dd');
$edate = array('name'=>'edate','value'=>$edate,'id'=>'datepicker1', 'data-format'=>'yyyy-MM-dd');

date_default_timezone_set("Asia/Manila");
$datetime = date('Y-m-d');

if(isset($wn)){
	$xls_name = "Unserve_Lists_".$wn."_".$datetime.".xls";
}

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

		$(document).ready(function(){

			$('#export_xls a').click(function(){
				$('#tft').remove();
				return ExcellentExport.excel(this, 'myTable', 'Unserve_Lists');
			});

		});

	</script>

	<style type="text/css">
		#pbody label{
			width: 150px;
		}
		#footer{
			position:fixed;
			left:0px;
			bottom:0px;
			height:40px;
			width:100%;
			background:#337ab7;
			padding: 15px;
		}

		#footer label{
			font-size: 11px;
			line-height: 0px;
			color: white;
			letter-spacing: 1.5px;
			font-weight: normal;
		}

		#export_xls a{
			color: white;
			text-decoration: none; 
		}

		th, td{width:100px !important;white-space:nowrap !important;}

	</style>

<h5>
    <div class="container-fluid">
	    <div class="row">
	        <div class='col-sm-12'>
	        	<div class="panel panel-default">
	        		<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>IMS to SAP Logs</strong></div>
	        		<div class="panel-body form-inline" id="pbody">
	        				<label>Warehouse</label>
	        				<?php echo form_dropdown('whouse',$whlist,$this->input->post('whouse'));?>&nbsp;&nbsp;&nbsp;

	        				<label>DO Number</label>
	        				<input type="text" name="do_number" value="<?php echo $this->input->post('do_number') ?>" class="form-control">&nbsp;&nbsp;&nbsp;

	        				<label>DR / WIS Number</label>
	        				<input type="text" name="dr_number" value="<?php echo $this->input->post('dr_number') ?>" class="form-control">

	        				<br/><br/>
	        				
							
							<input type="submit" name="submit" value="Search" class="btn btn-info"/><br><br>
							<!-- <button class="btn btn-warning" id="export_xls"><a download="<?php echo $xls_name; ?>" href="#">Export to Excel</a></button><br/><br/><br/> -->

							<div class="table-responsive">
							<table id="myTable">
								<thead>
									<tr>
										<th>DO No.</th>
										<th>DR/WIS</th>
										<th> Whse Name</th>
										<th>Item Name</th>
										<th>Qty</th>
										<th style="text-align: center;">Approve</th>
										<th style="text-align: center;">Out</th>
										<th>SAP Del. No.</th>
										<th>ActDel. By</th>
										<th>Del. Date</th>
									</tr>
								</thead>
								<tfoot id="tft">
										<tr>
											<th colspan="12" class="ts-pager form-horizontal">
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
									<?php if (isset($rec)): foreach ($rec as $r): ?>

									<tr>
										<td><?php echo $r->DO; ?></td>
										<td><?php echo $r->DRWIS; ?></td>
										<td><?php echo $r->WhsName; ?></td>
										<td><?php echo $r->ItemName; ?></td>
										<td><?php echo $r->Qty; ?></td>

										<td style="text-align: center; width: 140px; max-width: 140px; overflow: hidden;">

											<?php 
												$approve = "";
												$atext = "";

												if (isset($r->ApproveStats)) {
													if ($r->ApproveStats == "Approve" AND $r->wi_type = 1) {
														$approve = "label label-success";
														$date1 = strtotime($r->ApproveDate);
														$time1 = strtotime($r->ApproveTime); 
														$approve_date = date('Y-m-d', $date1);
														$approve_time = date('H:i:s', $time1);
														$atext = $approve_date.' '.$approve_time;
													} else {
														$approve = "label label-warning";
														$atext = "Pending";
													}
												} else {
													$approve = "label label-warning";
													$atext = "Pending";
												}
												
											?>

											<label class="<?php echo $approve; ?>"><?php echo $atext; ?></label>
										</td>

										<td style="text-align: center; width: 140px; max-width: 140px; overflow: hidden;">
											<?php 
												$out = "";
												$otext = "";

												if (isset($r->OutStats)) {
													if ($r->OutStats == "Out") {
														$out = "label label-success";
														$date2 = strtotime($r->OutDate);
														$time2 = strtotime($r->OutTime); 
														$out_date = date('Y-m-d', $date2);
														$out_time = date('H:i:s', $time2);
														$otext = $out_date.' '.$out_time;
													} else {
														$out = "label label-warning";
														$otext = "Pending";
													}
												} else {
													$out = "label label-warning";
													$otext = "Pending";
												}

												
											?>

											<label class="<?php echo $out; ?>"><?php echo $otext; ?></label>
										</td>


										<td><?php echo $r->SAPDoc; ?></td>
										<td>
											
										<?php  

											$adby = "";

											if ($r->SAPDoc <> "") {
												if ($r->wi_transno == $r->U_RefNo) {
													$adby = "IMS";
												} else {
													$adby = "SAP";
												}

											} else {
												if ($r->DocStatus == 'C') {
													$adby = "SAP";
												}
											}

											echo $adby;

										?>

										</td>
										<td><?php echo date('Y-m-d', strtotime($r->DelDate)); ?></td>



										<!-- <td>

											<?php 
												$stats = "";

												if ($r->Status == "Open") {
													$stats = "label label-success";
												} else {
													$stats = "label label-warning";
												}
											?>

											<label class="<?php echo $stats; ?>"><?php echo $r->Status; ?></label>
										</td> -->

									</tr>
									<?php endforeach; ?>
									<?php else: ?>
									<tr>
										<td colspan="10"><?php echo "No Record Found"; ?></td>
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
</h5><br/>

<div id="footer">
	<p style="text-align:center;">
		<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
	</p>
</div>


<?php echo form_close();?>
