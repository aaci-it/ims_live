<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php echo $this->load->view('header');?>

<div id="body">
<?php echo form_open();
$tokens = explode('/', current_url());
$get = $tokens[sizeof($tokens)-1];?>

<style type="text/css">
	th, td{width:100px !important;white-space:nowrap !important;}
</style>

	<h5>
		<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Monitored Deliveries</div>
							<div class="panel-body">
								<div class="col-md-3">
									<label>Warehouse Name</label>
									<div class="input-group input-group-md">
										<?php echo form_dropdown('whouse',$whlist,$vwhouse);?>
									</div>
									<div class="button">
										<input type="submit" class="btn btn-success" value="Search" name="submit"/>
									</div>
								</div>
								<div class="col-md-3">
									<label>Reference No.</label>
									<div class="input-group input-group-md">
										<?php $refno = array('name'=>'refno','value'=>$refno,'maxlength'=>50,'maxvalue'=>50); echo form_input($refno);?>
									</div>
								</div>
							</div>
						</div>
					</div>
						<div class="col-md-12">
							<div class="table-responsive">
								<table>
									<thead>
										<tr>
											<th>Customer Code</th>
											<th>Customer Name</th>
											<th>P.O No.</th>
											<th>D.O No.</th>
											<th>D.R No.</th>
											<th>Item Code</th>
											<th>Item Desc</th>
											<th>Qty</th>
											<th>Uom</th>
											<th>Destination</th>
											<th>Ship Date Time</th>
											<th>Arrived Date Time</th>
											<th>Unloading Time</th>
											<th>Customer Accpt Qty</th>
											<th>Customer Return Qty</th>
											<th>Customer Remarks</th>
											<th>Confirmed</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th colspan="17" class="ts-pager form-horizontal">
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
										<tr>
											<?php if(isset($cdel_record)): foreach ($cdel_record as $r):?>
											<td><?php echo $r->cust_code; ?></td>
											<td><?php echo $r->cust_name; ?></td>
											<td><?php echo $r->po_num; ?></td>
											<td><?php echo $r->do_num; ?></td>
											<td><?php echo $r->dr_num; ?></td>
											<td><?php echo $r->item_code; ?></td>
											<td><?php echo $r->item_desc; ?></td>
											<td><?php echo $r->del_qty; ?></td>
											<td><?php echo $r->del_uom; ?></td>
											<td><?php echo $r->del_desti; ?></td>
											<td><?php echo $r->ship_date; echo ' '; echo $r->ship_time; ?></td>
											<td><?php echo $r->arr_date; echo ' '; echo $r->arr_time; ?></td>
											<td><?php echo $r->uld_ftime; ?></td>
											<td><?php echo $r->qty_acpt; ?></td>
											<td><?php echo $r->qty_ret; ?></td>
											<td><?php echo $r->cust_rmks; ?></td>
											<td><?php $c=""; if($r->confirm==1): $c='Yes'; else: $c='No';?><?php endif; ?><?php echo $c; ?></td>

										</tr>
											<?php endforeach;?>
											<?php else:?>
										<tr>
											<td colspan=17>No Record</td>
										</tr>
											<?php endif;?>
									</tbody>
								</table>
							</div>
						</div>
					
				</div>
			</div>
	</h5>

		

<br>
<?php echo form_close();?>
</div>
<?php echo $this->load->view('footer');?>