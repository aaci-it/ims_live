<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php echo $this->load->view('header');?>

<?php 
$sdate = array('name'=>'posting_date_start','value'=>$this->input->post('posting_date_start'),'id'=>'posting_date_start', 'data-format'=>'yyyy-MM-dd');
$edate = array('name'=>'posting_date_end','value'=>$this->input->post('posting_date_end'),'id'=>'posting_date_end', 'data-format'=>'yyyy-MM-dd');
$aofdate = array('name'=>'aofdate','value'=>$this->input->post('aofdate'),'id'=>'aofdate', 'data-format'=>'yyyy-MM-dd');

date_default_timezone_set("Asia/Manila");
$datetime = date('Y-m-d');

if(isset($wn)){
	$xls_name = "DSPR_".$wn."_".$datetime.".xls";
}else{
	$xls_name = "";
}

?>

<?php echo form_open();?>

<!-- DT Picker -->
 	<link rel="stylesheet" href="<?php echo base_url();?>DTPicker/css/bootstrap-datetimepicker.min.css" >
    <script src="<?php echo base_url();?>DTPicker/js/bootstrap-datetimepicker.min.js"></script>
    <!-- End of File -->

    <script type="text/javascript">
	  $(function() {
	    $('#posting_date_start').datetimepicker({
	      pickTime: false
	    });
	    $('#posting_date_end').datetimepicker({
	      pickTime: false
	    });
	    $('#aofdate').datetimepicker({
	      pickTime: false
	    });
	  });

	  function fnExcelReport()
		{
		    var tab_text = '<table border="1px" style="font-size:12px" ">';
		    var textRange; 
		    var j = 0;
		    var tab = document.getElementById('myTable'); // id of table
		    var lines = tab.rows.length - 2;

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
				return ExcellentExport.excel(this, 'myTable', 'DSPR');
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
	        		<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Daily Stock Position Report</strong></div>
	        		<div class="panel-body form-inline" id="pbody">
	        			<div class="col-md-4">
	        				<label>Warehouse</label>
	        				<?php echo form_dropdown('whouse',$whlist,$this->input->post('whouse'));?><br><br>

	        				<input type="submit" name="Search" value="Search" class="btn btn-info"/>
							<button class="btn btn-warning" id="export_xls"><a download="<?php echo $xls_name; ?>" href="#">Export to Excel</a></button><br/><br/><br/>
	        			</div>
	        			<div class="col-md-4">
	        				<!-- <label>Posting Date Start</label>
							<div class='input-group date' id='posting_date_start'>
				                <?php echo form_input($sdate); ?>
									<span class="add-on">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div> -->

							<label>As of Date</label>
	        				<div class='input-group date' id='aofdate'>
				                <?php echo form_input($aofdate); ?>
									<span class="add-on">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div>

	        			</div>
	        			<div class="col-md-4">
	        				<!-- <label>Posting Date End</label>
							<div class='input-group date' id='posting_date_end'>
				                <?php echo form_input($edate); ?>
									<span class="add-on">
										<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								    </span>
							</div> -->
	        			</div>
						
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="myTable">
										<thead>
											<tr>
												<!--- Customer , JO No., Item No, Description, Quantity on Job Order -->
												<th>Item No.</th>
												<th>Description</th>
												<th>Beginning Balance</th>
												<th>RR</th>
												<th>Other Receipts</th>
												<th>Total</th>
												<th>DR</th>
												<th>WIS</th>
												<th>ATW</th>
												<th>Other Issuances</th>
												<th>Total</th>
												<th>WAR</th>
												<th>Bal End</th>
												<th>UDO</th>
												<th>Intransit</th>
												<th>Bal Avl</th>
											</tr>
										</thead>
										<tfoot id="tft">
												<tr>
													<th colspan="16" class="ts-pager form-horizontal">
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
											<tr><?php if($dspr_record): foreach ($dspr_record as $r): ?>

												<?php if($r->BegBal <> 0): ?>
											
												<td><?php echo $r->ID; ?></td>
												<td><?php echo $r->Dscription; ?></td>

												<!-- BEGINNING BALANCE -->
												<?php 
													if(isset($bb)){
														if($bb == 'Y'){
															$beg_bal = 0.00;
														}else{
															$beg_bal = $r->BegBal;
														}
													} 
												?>

												<td><b><?php echo number_format($beg_bal,2); ?></b></td>
												<td><?php echo $r->RR; ?></td>
												<td><?php echo $r->OR; ?></td>
												<td><b><?php echo number_format($r->RR + $r->OR, 2); ?></b></td>
												<td><?php echo $r->DR;?></td>
												<td><?php echo $r->WIS;?></td>
												<td><?php echo $r->ATW;?></td>
												<td><?php echo $r->OI; ?></td>

												<?php $total_dout = $r->DR + $r->WIS + $r->ATW + $r->OI; ?>

												<td><b><?php echo number_format($total_dout,2);?></b></td>

												<td><?php echo $r->WAR;?></td>

												<?php $bal_end = $r->BegBal - $total_dout; ?>

												<td><b><?php echo number_format($bal_end,2); ?></b></td>
												<td><?php echo $r->UDO; ?></td>
												<td><?php echo $r->ITO; ?></td>

												<?php $bal_avl = $bal_end - $r->UDO; ?>

												<td><b><?php echo number_format($bal_avl,2); ?></b></td>
											</tr>
											<?php endif; ?>
											<?php endforeach; ?>
											<?php else: ?>
											<tr>
												<td colspan=16>No Record</td>
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
	</div>
</h5><br>

<div id="footer">
	<p style="text-align:center;">
		<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
	</p>
</div>

<?php echo form_close();?>

