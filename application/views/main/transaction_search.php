<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>

<?php echo $this->load->view('header');?>


<?php 
$sdate = array('name'=>'sdate','value'=>$sdate,'id'=>'datepicker', 'data-format'=>'yyyy-MM-dd');
$edate = array('name'=>'edate','value'=>$edate,'id'=>'datepicker1', 'data-format'=>'yyyy-MM-dd');
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
	</script>

	<style type="text/css">

	/*	.btn-primary, .btn-info, .btn-warning{
			border-radius: 0px;
			opacity: .8;
		}*/


	</style>

			<h5>

			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Transaction Logs</div>
							<div class="panel-body">
								<div class="col-md-3">
									<label>Warehouse Name</label>
									<div class="input-group input-group-md">
										<?php echo form_dropdown('whlist',$whlist);?>
									</div>
									<label>Posting Date Start</label>
									<div class='input-group date' id='datepicker'>
						                <!-- <input data-format="yyyy-MM-dd" type="text" name="sdate"></input> -->
						                <?php echo form_input($sdate); ?>
											<span class="add-on">
												<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
										    </span>
									</div>&nbsp;&nbsp;&nbsp;
								</div>
								<div class="col-md-3">
									<label>Reference No</label>
									<div class="input-group input-group-md">
										<?php $ref = array('name'=>'ref','value'=>$refno,'maxlength'=>20,'maxvalue'=>20); echo form_input($ref);?>
									</div>
									<label>Posting Date End</label>
									<div class='input-group date' id='datepicker1'>
						                <!-- <input data-format="yyyy-MM-dd" type="text" name="edate"></input> -->
						                <?php echo form_input($edate); ?>
											<span class="add-on">
												<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
										    </span>
									</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</div>
								<div class="col-md-3">
									<label>Reference Name</label>
									<div class="input-group input-group-md">
										<?php $bpname = array('name'=>'bpname','value'=>$refname,'maxlength'=>50,'maxvalue'=>50); echo form_input($bpname);?>
									</div>
									<div class="button">
										<input type="submit" class="btn btn-success" value="Search" name="submit"/>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-body form-inline">

								<div class="totals">

									<?php if(isset($total_in_trans)): foreach($total_in_trans as $ti): ?>

										<button class="btn btn-primary" type="button">
										  <strong>Total In's</strong> <span class="badge"><?php echo number_format($ti->wi_itemqty, 2) ?></span>
										</button>&nbsp;&nbsp;

									<?php endforeach; ?>
									<?php endif; ?>

									<?php if(isset($total_out_trans)): foreach($total_out_trans as $to): ?>

										<button class="btn btn-info" type="button">
										  <strong>Total Out's</strong> <span class="badge"><?php echo number_format($to->wi_itemqty, 2) ?></span>
										</button>&nbsp;&nbsp;

									<?php endforeach; ?>
									<?php endif; ?>

									<?php if(isset($total_trans)): foreach($total_trans as $tt): ?>

										<button class="btn btn-warning" type="button">
										  <strong>Total</strong> <span class="badge"><?php echo number_format($tt->wi_itemqty, 2) ?></span>
										</button>

									<?php endforeach; ?>
									<?php endif; ?>

								</div>
							</div>
						</div>
					</div>


						<div class="col-md-12">
							
								<?php if(isset($error)):?><?php echo $error;?><?php endif;?>
									<table>
										<thead>
										<tr>
											<th>Source</th>
											<th>Destination</th>
											<th>Ref No. 1</th>
											<th>Ref No. 2</th>
											<th>Item Desc.</th>
											<th>Item Qty</th>
											<th>Posting Date</th>
											<th>Created By</th>
											<th>Created Date & Time</th>
											<th style="text-align: center;">Status</th>
											<th style="text-align: center;">Action</th>
										</tr>
										</thead>
										<tfoot>
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
										<?php $count = 0;?>
											<?php if (isset($deltran)): foreach($deltran as $r):?>	
											<?php $count = $count + 1; ?>
										<tr>
												<?php if ($r->wi_type == 0):?>
											<td>
												<?php 
													if($r->whse_name <> ""){
														echo $r->whse_name;
													}else{
														echo $r->CardName;
													}
							
												?>
											</td>
											<td><?php echo $r->wh_name;?></td>
												<?php else:?>
											<td><?php echo $r->wh_name;?></td>
											<td><?php echo $r->CardName;?></td>
												<?php endif;?>
											<td><?php echo $r->wi_reftype; echo $r->wi_refnum;?></td>
											<td><?php echo $r->wi_reftype2; echo $r->wi_refnum2;?></td>
											<td><?php echo $r->comm__name;?></td>
											<td><?php echo $r->wi_itemqty;?></td>
											<td><?php echo $r->deldate;?></td>
											<td><?php echo $r->wi_createby;?></td>
											<td><?php echo $r->wi_createdatetime;?></td>
											<?php  if ($r->wi_type == 0 AND $r->wi_status == 1){
												$delstat = 'Delivery In';
												$clr = "label label-info";
											}
											else if ($r->wi_type == 2 AND $r->wi_status == 1 AND $r->wi_dtcode == "DT_02"){
												$delstat = 'Delivery Out Manual';
												$clr = "label label-success";
											}
											else if ($r->wi_type == 2 AND $r->wi_status == 1 AND $r->wi_dtcode == "DT_04"){
												$delstat = 'Delivery Out HO';
												$clr = "label label-success";
											}
											else if ($r->wi_type == 1 AND $r->wi_status == 1 AND $r->wi_dtcode == "DT_02"){
												$delstat = 'Delivery Out Manual';
												$clr = "label label-success";
											}
											else if ($r->wi_type == 1 AND $r->wi_status == 1 AND $r->wi_dtcode == "DT_04"){
												$delstat = 'Delivery Out HO';
												$clr = "label label-success";
											}
											else{
												$delstat = 'Cancelled';
												$clr = "label label-warning";
											};?>
											<td style="text-align: center;"><label class="<?php echo $clr ?>"><?php echo $delstat;?></label></td>
											<td style="text-align: center;">

												<?php echo anchor('main/item_in_readonly/'.$r->wi_id,'<i class="glyphicon glyphicon-eye-open"></i>
												<span class="hidden-tablet">View</span>', 'class="label label-danger"');?>

											</td>
										</tr>

											<?php endforeach;?>
											<?php else: ?>
										<tr><td colspan=11>No record</td></tr>
											<?php endif;?>
										</tbody>
									</table>
							
						</div>
					
				</div>
			</div>

			</h5>

<?php echo form_close();?>

<?php echo $this->load->view('footer');?>

<br><br>