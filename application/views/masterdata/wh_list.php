<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php 
	date_default_timezone_set("Asia/Manila");
	$datetime = date('Y-m-d');
	$xls_name = "Warehouse_Lists_".$datetime.".xls";
?>

<?php echo form_open();?>

<style type="text/css">

	button a, button a:hover{
		color: white;
	}

	#export_xls a{
			color: white;
			text-decoration: none; 
	}

	#del_yes a{
		color: white;
		text-decoration: none;
	}

	.del, #del_msg, #cannot_msg{
		border-radius: 0px;	
	}

	.js #del_msg, #cannot_msg{
		display: none;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<script type="text/javascript">
	$('#export_xls a').click(function(){
		$('#tft').remove();
		return ExcellentExport.excel(this, 'myTable', 'Warehouse_Lists');
	});
</script>

<h5>
	<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Warehouse Lists</div>
							<div class="panel-body">

								<div class="row">
									<div class="col-md-12 form-inline">
										<div class="alert alert-danger" id="del_msg">
											<?php if(isset($wname)): foreach($wname as $wr): ?>
											<label><strong><span class="glyphicon glyphicon-question-sign"></span>&nbsp;&nbsp;Are you sure you want to delete this warehouse <em><?php echo $wr->wh_name; ?></em> ?</strong></label>
											<?php endforeach; ?>
											<?php endif; ?>
											&nbsp;&nbsp;<button type="button" class="btn btn-success del" id="del_yes"><?php echo anchor('main/warehouse_delete/'.$this->uri->segment(3), 'Yes'); ?></button>
											<button type="button" class="btn btn-danger del" id="del_no">No</button>
										</div>

										<div class="alert alert-info" id="cannot_msg">
											<label><strong><span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;Warehouse cannot be deleted</strong></label>
											&nbsp;&nbsp;<button type="button" class="btn btn-success del" id="del_ok">Ok</button>
										</div>

									</div>
								</div>

								<button class="btn btn-info"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;<?php echo anchor('main/warehouse_create', 'Add Warehouse');?></button>
								<button class="btn btn-warning" id="export_xls"><a download="<?php echo $xls_name; ?>" href="#"><span class="glyphicon glyphicon-open"></span>&nbsp;&nbsp;Export to Excel</a></button><br><br>

								<table id="myTable">
									<thead>
										<th>Name</th>
										<th>Territory</th>
										<th>Address</th>
										<th style="text-align:center">Remarks</th>
										<th style="text-align:center">Status</th>
										<th style="text-align:center">Action</th>
									</thead>
									<tfoot id="tft">
										<tr>
											<th colspan="6" class="ts-pager form-horizontal">
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
										<?php if(isset($records)): foreach ($records as $r):?>
										<?php if ($r->wh_status == 1){
											$active='Active';
											$lbl = "label label-success";}
										else{
											$active='Inactive';
											$lbl = "label label-danger"; }?>

										<tr>
											<td><?php echo $r->wh_name;?></td>
											<td><?php echo $r->wh_territory; ?></td>
											<td><?php echo $r->wh_addr;?></td>
											<td><?php echo $r->wh_remarks; ?></td>
											<td style="text-align:center"><label class="<?php echo $lbl ?>"><?php echo $active ?></label></td>
											<td style="text-align:center">

												<?php echo anchor('main/warehouse_edit/'.$r->wh_code,'<i class="glyphicon glyphicon-wrench"></i>
												<span class="hidden-tablet">Edit</span>', 'class="label label-warning"');?>

												<?php echo anchor('main/warehouse_list/'.$r->wh_code,'<i class="glyphicon glyphicon-trash"></i>
												<span class="hidden-tablet">Delete</span>', 'class="label label-danger" id="del_confirm"');?>

											</td>
										</tr>
										<?php endforeach;?>
										<?php else:?>
										<tr><td>No record</td></tr>
										<?php endif;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
</h5>
	
<br>
<?php echo form_close();?>
<?php $this->load->view('footer');?>

<script type="text/javascript">

	$('#del_msg').hide();
	$('#cannot_msg').hide();

	<?php if($can_delete == 0): ?>
		$('#cannot_msg').hide();
		$('#del_msg').fadeIn(1200);
		$('#del_msg').slideDown(1200);
		$('#del_msg').show();
	<?php else: ?>
		$('#del_msg').hide();
		$('#cannot_msg').fadeIn(1200);
		$('#cannot_msg').slideDown(1200);
		$('#cannot_msg').show();
	<?php endif; ?>	

	$('#del_ok').click(function(){
		$('#cannot_msg').fadeOut(1200);	
	});

	$('#del_no').click(function(){
		$('#del_msg').fadeOut(1200);
	})

</script>