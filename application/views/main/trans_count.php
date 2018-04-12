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

			<h5>

			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Transaction Count</div>
							<div class="panel-body">
								<div class="col-md-3">
									<label>Warehouse Name</label>
									<div class="input-group input-group-md">
										<?php echo form_dropdown('whlist',$whlist);?>
									</div>
									<label>Created Date Start</label>
									<div class='input-group date' id='datepicker'>
						                <!-- <input data-format="yyyy-MM-dd" type="text" name="sdate"></input> -->
						                <?php echo form_input($sdate); ?>
											<span class="add-on">
												<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
										    </span>
									</div>&nbsp;&nbsp;&nbsp;
								</div>
								<div class="col-md-3">
									<label>Reference Type</label>
									<div class="input-group input-group-md">
										<?php $dtype = array('Delivery In', 'Delivery Out'); echo form_dropdown('deltype',$dtype); ?>
									</div>
									<label>Created Date End</label>
									<div class='input-group date' id='datepicker1'>
						                <!-- <input data-format="yyyy-MM-dd" type="text" name="edate"></input> -->
						                <?php echo form_input($edate); ?>
											<span class="add-on">
												<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
										    </span>
									</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</div>
								<div class="col-md-3">
									<br/><br/>
									<div class="button">
										<input type="submit" class="btn btn-success" value="Search" name="submit"/>
									</div>
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
											<th>Delivery Date</th>
											<th>Created By</th>
											<th>Created Date & Time</th>
											<th>Status</th>
											<th>Action</th>
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
											<td><?php echo $r->CardName;?></td>
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
											}
											else if ($r->wi_type == 2 AND $r->wi_status == 1){
												$delstat = 'Delivery Out';
											}
											else if ($r->wi_type == 1 AND $r->wi_status == 1){
												$delstat = 'Delivery Out';
											}
											else{
												$delstat = 'Cancelled';
											};?>
											<td><?php echo $delstat;?></td>
											<td><?php echo anchor('main/item_in_readonly/'.$r->wi_id,'View');?></td>
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