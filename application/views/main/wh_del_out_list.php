<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>

<script type="text/javascript">

	$(document).ready(function(){

		<?php 
			$wh_name = $this->uri->segment(3);
			$wh_name = str_replace('%20', ' ', $wh_name) 
		?>
		<?php if($wh_name <> "" && $vwhouse == ""): ?>
			$("#whouse option").each(function() {
			  if($(this).text() == '<?php echo $wh_name; ?>'){
			    $(this).attr('selected', 'selected');
			    $('#submit').click();            
			  }                        
			});
		<?php endif; ?>

	});

</script>


<?php echo $this->load->view('header');?>
<div id="body">
<?php echo form_open();
$tokens = explode('/', current_url());
$get = $tokens[sizeof($tokens)-1];?>

	<h5>
		<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Out Reserve Delivery</div>
							<div class="panel-body">
								<div class="col-md-3">
									<label>Warehouse Name</label>
									<div class="input-group input-group-md">
										<?php echo form_dropdown('whouse',$whlist, $vwhouse, 'id="whouse"');?>
									</div>
									<div class="button">
										<input type="submit" class="btn btn-success" id="submit" value="Search" name="submit"/>
									</div>
								</div>
								<div class="col-md-3">
									<label>Reference No.</label>
									<div class="input-group input-group-md">
										<?php echo form_dropdown('doctype',$doctype,$this->input->post('doctype'));?>
									</div>
								</div>
								<div class="col-md-2">
									<label>&nbsp;</label>
									<div class="input-group input-group-md">
										<?php $refno = array('name'=>'refno','value'=>$refno,'maxlength'=>50,'maxvalue'=>50); echo form_input($refno);?>
									</div>
								</div>
							</div>
						</div>
					</div>
						<div class="col-md-12">
								<table>
									<thead>
									<tr>
										<th>Source</th>
										<th>Destination</th>
										<th>Ref No. 1</th>
										<th>Ref No. 2</th>
										<th>Item</th>
										<th>Quantity</th>
										<th>Approved Date & Time</th>
										<th>Posting Date</th>
										<th>Creation DateTime</th>
										<th style="text-align: center;">Action</th>
									</tr>
									</thead>
									<tfoot>
										<tr>
											<th colspan="10" class="ts-pager form-horizontal">
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
									
											<?php //echo form_checkbox('active[0]','0',true,'readonly');?>
											<?php if ($r->wi_type == 0):?>
											<td><?php echo $r->CardName;?></td>
											<td><?php echo $r->wh_name;?></td>
											<?php else:?>
											<td><?php echo $r->wh_name;?></td>

											<?php

												$whse_name = "";

												if ($r->CardName == ""){
													$whse_name = $r->wi_location;
												}else{
													$whse_name = $r->CardName;
												}

											?>

											<td><?php echo $whse_name;?></td>
											<?php endif;?>
											<td><?php echo $r->wi_reftype; echo $r->wi_refnum;?></td>
											<td><?php echo $r->wi_reftype2; echo  $r->wi_refnum2;?></td>
											<td><?php echo $r->comm__name;?></td>
											<td><?php echo number_format($r->wi_itemqty,2);?></td>
											<td><?php echo $r->wi_approvedatetime;?></td>
											<td><?php echo $r->deldate;?></td>
											<td><?php echo $r->wi_createdatetime; ?></td>
											<td style="text-align: center;">

												<?php echo anchor('main/wh_delivery_out/'.$r->item_id.'/'.$get.'_'.$r->wi_id,'<i class="glyphicon glyphicon-ok"></i>
												<span class="hidden-tablet">Out</span>', 'class="label label-success"');?>

												<?php echo anchor('main/item_in_readonly/'.$r->wi_id,'<i class="glyphicon glyphicon-eye-open"></i>
												<span class="hidden-tablet">View</span>', 'class="label label-danger"');?>

											</td>
										
									</tr>
										<?php endforeach;?>
										<?php else:?>
									<tr>
										<td colspan=10>No Record</td>
									</tr>
										<?php endif;?>
									</tbody>
								</table>
							
						</div>
					
				</div>
			</div>

	</h5>
	
<br>
<?php echo form_close();?>
</div>
<?php echo $this->load->view('footer');?>