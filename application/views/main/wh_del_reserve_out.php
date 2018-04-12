<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php echo $this->load->view('header');?>

<?php echo form_open();
$tokens = explode('/', current_url());
$get = $tokens[sizeof($tokens)-1];?>

<?php foreach($warehouse as $r):?>

	<h5>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;">Reserved Delivery</div>
					<div class="panel-body form-inline">
						<label>Warehouse Name : </label>
						<?php $wh = array('name'=>'wh','value'=>$r->wh_name,'readonly'=>true);echo form_input($wh);?>
						<br/><br/>
						<table>
							<thead>
								<tr>
									<th>Reference Name</th>
									<th>Ref No. 1</th>
									<th>Ref No. 2</th>
									<th>Item</th>
									<th>Quantity</th>
									<th>Approved Date & Time</th>
									<th>Status</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th colspan="7" class="ts-pager form-horizontal">
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
								<?php if(isset($reserverecord)): foreach ($reserverecord as $r):?>
								<?php if ($r->wi_approvestatus == '1'):?>
									<?php //echo form_checkbox('active[0]','0',true,'readonly');?>
									<td><?php echo $r->wi_refname;?></td>
									<td><?php echo $r->wi_reftype; echo $r->wi_refnum;?></td>
									<td><?php echo $r->wi_reftype2; echo  $r->wi_refnum2;?></td>
									<td><?php echo $r->comm__name;?></td>
									<td><?php echo number_format($r->wi_itemqty,2);?></td>
									<td><?php echo $r->wi_approvedatetime;?></td>
									<td><?php echo 'Pending Out';?> </td>
								<?php else:?>
									<?php //echo form_checkbox('active[0]','0',false);?>
									<td><?php echo $r->wi_refname;?></td>
									<td><?php echo $r->wi_reftype; echo $r->wi_refnum;?></td>
									<td><?php echo $r->wi_reftype2; echo  $r->wi_refnum2;?></td>
									<td><?php echo $r->comm__name;?></td>
									<td><?php echo $r->wi_itemqty;?></td>
									<td><?php echo $r->wi_approvedatetime;?></td>
									<td><?php echo 'Pending Approval';?> </td>
								<?php endif;?>
							</tr>
								<?php endforeach;?>
								<?php else:?>
							<tr>
								<td colspan=7>No Record</td>
							</tr>
								<?php endif;?>
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>
	</h5>

<?php endforeach;?>

<?php echo form_close();?>

<?php echo $this->load->view('footer');?>