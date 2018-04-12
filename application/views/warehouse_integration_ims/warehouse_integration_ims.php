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
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Warehouse Integration SAP to IMS</div>
							<div class="panel-body">

							<div class="row">
								<div class="col-md-12 form-inline">
									<div class="alert alert-danger" id="del_msg">
										<label><strong><span class="glyphicon glyphicon-question-sign"></span>&nbsp;&nbsp;Are you sure you want to delete this warehouse ?</strong></label>
										&nbsp;&nbsp;<button class="btn btn-success del" type="submit"><?php echo anchor('main/warehouse_integration_delete_ims/'.$this->uri->segment(3), 'Yes'); ?></button>
										<button class="btn btn-danger del" type="button" id="del_no">No</button>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 form-inline">
									<div class="alert alert-info" id="del_info">
										<label><strong><span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;Customer cannot be deleted</strong></label>
										&nbsp;&nbsp;<button type="button" class="btn btn-success del" id="del_ok">Ok</button>
									</div>
								</div>
							</div>

							<button class="btn btn-info"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;<?php echo anchor('main/warehouse_integration_create_ims', 'Add Warehouse');?></button><br><br>
							<!-- <button class="btn btn-warning" id="export_xls"><a download="<?php echo $xls_name; ?>" href="#"><span class="glyphicon glyphicon-open"></span>&nbsp;&nbsp;Export to Excel</a></button><br><br> -->

							<div class="table-responsive">
								<table id="myTable">
									<thead>
										<tr>
											<th>SAP Whse Code</th>
											<th>SAP Whse Name</th>
											<th>Warehouse Code</th>
											<th>Warehouse Name</th>
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
											<td><?php echo $r->SAP_WhsCode; ?></td>
											<td><?php echo $r->SAP_WhsName; ?></td>
											<td><?php echo $r->WhsCode;?></td>
											<td><?php echo $r->WhsName;?></td>
											<td style="text-align: center;">

												<?php echo anchor('main/warehouse_integration_edit_ims/'.$r->Id,'<i class="glyphicon glyphicon-wrench"></i>
												<span class="hidden-tablet">Edit</span>', 'class="label label-warning"');?>

												<?php echo anchor('main/warehouse_integration_ims/'.$r->Id,'<i class="glyphicon glyphicon-trash"></i>
												<span class="hidden-tablet">Delete</span>', 'class="label label-danger btn-delete"');?>

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

<script type="text/javascript">
	
	$('#export_xls a').click(function(){
		$('#tft').remove();
		return ExcellentExport.excel(this, 'myTable', 'Warehouse_Integration_Lists');
	});

	$('#del_msg').hide();
	$('#del_info').hide();

	<?php if($can_del == 1): ?>
		$('#del_info').hide();
		$('#del_msg').fadeIn(1200);
		$('#del_msg').slideDown(1200);
		$('#del_msg').show();
	<?php endif; ?>

	$('#del_no').click(function(){
		$('#del_msg').fadeOut(1200);
	});

	$('#del_ok').click(function(){
		$('#del_info').fadeOut(1200);
	});

</script>