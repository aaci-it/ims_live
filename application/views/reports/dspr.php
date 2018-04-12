<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php echo $this->load->view('header');?>

<?php 
// $sdate = array('name'=>'sdate','value'=>$sdate,'id'=>'datepicker');
// $edate = array('name'=>'edate','value'=>$edate,'id'=>'datepicker1');
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
	</style>


<h5>
    <div class="container-fluid">
	    <div class="row">
	        <div class='col-sm-12'>
	        	<div class="panel panel-default">
	        		<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Daily Stock Position Report</strong></div>
	        		<div class="panel-body form-inline" id="pbody">
	        				<label>Warehouse</label>
	        				<?php echo form_dropdown('whouse',$whlist,$this->input->post('whouse'));?>&nbsp;&nbsp;&nbsp;
							<input type="submit" name="Search" value="Search" class="btn btn-info"/>
							<button class="btn btn-warning" onclick="fnExcelReport();">Export to Excel</button><br/><br/>

							<table id="myTable">
								<thead>
									<tr>
										<!--- Customer , JO No., Item No, Description, Quantity on Job Order -->
										<th>Item No.</th>
										<th>Description</th>
										<th>Beginning Balance</th>
										<th>RR</th>
										<th>DR</th>
										<th>WIS</th>
										<th>ATW</th>
										<th>Total</th>
										<th>WAR</th>
										<th>Bal End</th>
										<th>UDO</th>
										<th>Intransit</th>
										<th>Bal Avl</th>
									</tr>
								</thead>
								<tfoot>
										<tr>
											<th colspan="13" class="ts-pager form-horizontal">
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
									<tr><?php if($reserverecord){ foreach ($reserverecord as $r){ ?>

										<?php 
											$begbal=$r->BegBal;
											$total_out=$r->DR + $r->WIS;
											$balend = $begbal-$total_out;
											$UDO = $r->DOQTY - $r->ITMQTY; 
											$ITO = $r->I_DOQTY - $r->I_ITMQTY; 
										?>

										<td><?php echo $r->ID;?></td>
										<td><?php echo $r->Dscription; ?></td>
										<td><?php echo $r->BegBal;?></td>
										<td><?php echo $r->RR;?></td>
										<td><?php echo $r->DR;?></td>
										<td><?php echo $r->WIS;?></td>
										<td><?php echo $r->ATW;?></td>
										<td><?php echo number_format($r->DR + $r->WIS,2);?></td>
										<td><?php echo $r->WAR;?></td>
										<td><?php echo number_format($balend,2); ?></td>
										<td><?php echo number_format($UDO,2);?></td>
										<td><?php echo number_format($ITO,2);?></td>
										<td><?php echo number_format($UDO,2);?></td>
									</tr>
									<?php } ?>
								<tr>
									<td colspan=13>No Record</td>
								</tr>
									<?php } ?>
								</tbody>
							</table>
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

