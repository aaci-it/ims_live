<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php 
	date_default_timezone_set("Asia/Manila");
	$datetime = date('Y-m-d');

	$xls_name = "Delivery_Receipts_Lists_".$datetime.".xls";

?>

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

<?php echo form_open();?>
	<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Transfer Out Print Lists</div>
							<div class="panel-body">

							<div class="row">
								<div class="col-md-12 form-inline">
									<div class="alert alert-danger" id="del_msg">
										<label><strong><span class="glyphicon glyphicon-question-sign"></span>&nbsp;&nbsp;Are you sure you want to delete this print document <em><?php echo $this->uri->segment(3) ?></em> ?</strong></label>
										&nbsp;&nbsp;<button class="btn btn-success del" type="submit"><?php echo anchor('main/delete_tout_pdf/'.$this->uri->segment(3), 'Yes'); ?></button>
										<button class="btn btn-danger del" type="button" id="del_no">No</button>
									</div>
								</div>
							</div>

							<label>Warehouse</label>
	        				<?php echo form_dropdown('whouse',$whlist,$this->input->post('whouse'));?><br>

	        				<button type="submit" name="submit" class="btn btn-success" value="submit"><span class="glyphicon glyphicon-search"></span>&nbsp;Search</button>
							<button class="btn btn-warning" id="export_xls"><a download="<?php echo $xls_name; ?>" href="#"><span class="glyphicon glyphicon-open"></span>&nbsp;&nbsp;Export to Excel</a></button><br><br>

							<div class="table-responsive">
								<table id="myTable">
									<thead>
										<tr>
											<th>Id</th>
											<th>Ref No. 1</th>
											<th>Ref No. 2</th>
											<th>Item</th>
											<th style="text-align: center;">Quantity</th>
											<th>Location</th>
											<th style="text-align: center;">No. of Print</th>
											<th style="text-align: center;">Action</th>
										</tr>
									</thead>
									<tfoot id="tft">
										<tr>
											<th colspan="8" class="ts-pager form-horizontal">
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
											<td><?php echo $r->wi_id;?></td>
											<td><?php echo $r->wi_refnum2?></td>
											<td><?php echo $r->wi_refnum;?></td>
											<td><?php echo $r->comm__name; ?></td>
											<td style="text-align: center;"><?php echo $r->wi_itemqty; ?></td>
											<td><?php echo $r->wi_location; ?></td>
											<td style="text-align: center;"><?php echo $r->no_of_print; ?></td>

											<td style="text-align: center;">

												<?php echo anchor('main/open_tout_pdf/'.$r->wi_id,'<i class="glyphicon glyphicon-print"></i>
												<span class="hidden-tablet">Print</span>', 'class="label label-success"');?>

											</td>
										</tr>
										<?php endforeach;?><?php else: ?>
										<tr>
											<td colspan="8">No record</td>
										</tr>
									<?php endif;?>
									</tbody>
								</table>
						</div>
					</div>
				</div>
			</div>
	<?php echo form_close();?>
</h5>

<?php $this->load->view('footer');?>

<br><br>

<script type="text/javascript">
	
	$('#export_xls a').click(function(){
		$('#tft').remove();
		return ExcellentExport.excel(this, 'myTable', 'Truck_Company_Lists');
	});

	$('#del_msg').hide();
	$('#del_info').hide();

	<?php if($this->uri->segment(3) <> ""): ?>
		$('#del_msg').fadeIn(1200);
		$('#del_msg').slideDown(1200);
		$('#del_msg').show();
	<?php endif; ?>

	$('#del_no').click(function(){
		$('#del_msg').fadeOut(1200);
	});

</script>