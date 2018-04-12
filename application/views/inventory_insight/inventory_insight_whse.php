<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php 
	date_default_timezone_set("Asia/Manila");
	$datetime = date('Y-m-d');

	$xls_name = "Warehouse_Integration_Lists_".$datetime.".xls";

?>

<?php echo form_open(current_url());?>

<style type="text/css">
	
	button a, button a:hover{
		color: white;
	}

	th, td{width:100px !important;white-space:nowrap !important;}

	#export_xls a{
			color: white;
			text-decoration: none; 
	}

	.del, #del_msg, #del_info{
		border-radius: 0px;
	}

	.js #del_msg, #del_info{
		display: none;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<h5>
	<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Inventory Shares Configuration</div>
							<div class="panel-body">

							<div class="table-responsive">
								<table id="myTable">
									<thead>
										<tr>
											<th>Warehouse Code</th>
											<th>Warehouse Name</th>
											<th>Warehouse Location</th>
											<th style="text-align: center;">Action</th>
										</tr>
									</thead>
									<tfoot id="tft">
										<tr>
											<th colspan="20" class="ts-pager form-horizontal">
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
										<?php if (isset($records)): foreach ($records as $r):?>
										<tr>
											<td><?php echo $r->wh_code;?></td>
											<td><?php echo $r->wh_name;?></td>
											<td>
												<?php
													if($r->wh_location <> ""){
														if($r->wh_location == "0"){
															echo "NCR";
														}elseif($r->wh_location == "1"){
															echo "South Luzon";
														}else{
															echo "North";
														}
													} 
												?>
											</td>
											<td style="text-align: center;">

												<?php echo anchor('main/inventory_insight_whse_edit/'.$r->wh_code,'<i class="glyphicon glyphicon-wrench"></i>
												<span class="hidden-tablet">Edit</span>', 'class="label label-warning"');?>

											</td>
										</tr>
										<?php endforeach;?><?php else: ?>
										<tr>
											<td colspan="5">No record</td>
										</tr>
									<?php endif;?>
									</tbody>
								</table>
						</div>
					</div>
				</div>
			</div>
</h5>

<?php echo form_close();?>
<?php $this->load->view('footer');?>

<br><br>
