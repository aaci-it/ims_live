<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php echo $this->load->view('header');?>

<?php 
$cutoff_date = array('name'=>'cutoff_date','value'=>$this->input->post('cutoff_date'), 'id'=>'datepicker', 'data-format'=>'yyyy-MM-dd', 'required'=>'required');
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

	var num_rows = $('#total_rows').val();
	var total_count = 0;

	for(x=0; x<=num_rows; x++){
		$('#counted_'+x).change(function(){

			// GET THE ID OF THE COUNTED
			var id_temp = $(this).attr('id');
			var parts = id_temp.split('_', 2);
			var the_num  = parts[1];

			var cbal = $('#cbal_'+the_num).val().replace(/\,/g,'');;
			var count = $(this).val();
			var total = 0;
			
			if(cbal == "0.00"){
				$('#variance_'+the_num).val("0.00");
			}else{	

				total = parseFloat(cbal, 2) - parseFloat(count, 2); 
				total = parseFloat(total).toFixed(2);
				$('#variance_'+the_num).val(total);

			}
		});

		$('#counted_'+x).blur(function(){

			// GET THE ID OF THE COUNTED
			var id_temp = $(this).attr('id');
			var parts = id_temp.split('_', 2);
			var the_num  = parts[1];

			if($(this).val() == ""){
				$('#variance_'+the_num).val("");
			}

		});
	}

	
    $('.counted').change(function(){
    	var sum = 0;
	    $('.counted').each(function() {
	        sum += Number($(this).val());

	        $('#total_count').val(sum);
	    });
    });


	});
	
	function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }

    
</script>

<style type="text/css">

	button a, button a:hover{
		color: white;
	}

	.js #error_msg{
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
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Opening Balance</div>
							<div class="panel-body">

								<div class="row">
									<div class="col-md-12">
										<div class="alert alert-danger" id="error_msg">
											<strong><?php echo validation_errors(); ?></strong>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<label>Warehouse</label>
									<?php echo form_dropdown('wh', $whlist, $this->input->post('wh'));?><br/>

									<button type="submit" name="search" class="btn btn-info" value="Search"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Search</button>
									<button type="submit" class="btn btn-success" name="post" value="Post"><span class="glyphicon glyphicon-save-file"></span>&nbsp;&nbsp;Post</button>
									<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;Save as Draft</button><br><br>
								</div>
								
								<div class="col-md-4">
									<label>Cut-Off Date</label>
									<!-- <div class='input-group date' id='datepicker'>
						                <?php echo form_input($cutoff_date); ?>
											<span class="add-on">
												<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
										    </span>
									</div> -->

									<div class='input-group date' id='datepicker' style="width: 200px;">
					                	<input type="text" name="cutoff_date" value="<?php echo $this->input->post('cutoff_date'); ?>" class="form-control" id="datepicker" data-format="yyyy-MM-dd" required>
										<span class="add-on input-group-addon">
											<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
									    </span>
									</div>

								</div>

								<div class="col-md-4">
									<label>Total Count</label>
									<input type="text" name="total_count" id="total_count" value="0.00" readonly style="text-align: center;">
								</div>

								<?php if(isset($total_items)): foreach($total_items as $ti): ?>
									<input type="hidden" value="<?php echo $ti->ctr; ?>" id="total_rows">
									<?php $ctr = $ti->ctr; ?>
								<?php endforeach; ?>
								<?php endif; ?>
							
								<table>
									<thead>
									<tr>
										<th style="text-align: center;">Code</th>
										<th style="text-align: center;">Code 2</th>
										<th>Name</th>
										<th style="text-align: center;">UOM</th>
										<th style="text-align: center;">Current Balance</th>
										<th style="text-align: center;">Counted</th>
										<th style="text-align: center;">Variance</th>
										<th style="text-align: center;">Warehouse</th>
									</tr>
									</thead>
									<tfoot>
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
									<?php $ctr=0; ?>
									<?php if(isset($records)): foreach ($records as $r):?>
									<?php $ctr++; ?>
									<tr>
										<td style="text-align: center;"><input type="text" name="icode_<?php echo $ctr; ?>" id="icode_<?php echo $ctr; ?>" value="<?php echo $r->comm__id; ?>" style="width: 85px; width: 85px; text-align: center;" readonly></td>
										<td style="text-align: center;"><input type="text" name="icode2_<?php echo $ctr; ?>" id="icode2_<?php echo $ctr; ?>" value="<?php echo $r->comm__code2; ?>" style="width: 85px; width: 85px; text-align: center;" readonly></td>
										<td><?php echo $r->comm__name;?></td>
										<td style="text-transform: uppercase; text-align: center;"><input type="text" name="unit_<?php echo $ctr; ?>" value="<?php echo $r->item_uom; ?>" style="text-align: center; width: 50px; text-transform: uppercase" readonly></td>

										<?php $qty = ($r->sqty - ($r->tqty + $r->rqty)); ?>
										<td style="text-align: center;"><input type="text" name="cbal_<?php echo $ctr; ?>" class="current" id="cbal_<?php echo $ctr; ?>" value="<?php echo number_format($qty, 2); ?>" style="text-align: center; width: 100px;" readonly></td>
										<td style="text-align: center;"><input type="text" name="counted_<?php echo $ctr; ?>" class="counted" id="counted_<?php echo $ctr; ?>" value="<?php echo $this->input->post('counted_'.$ctr) ?>" style="width: 100px; text-align: center;" onkeypress="return isNumberKey(event)"></td>
										<td style="text-align: center;"><input type="text" name="variance_<?php echo $ctr; ?>" class="variance" id="variance_<?php echo $ctr; ?>" value="<?php echo $this->input->post('variance_'.$ctr) ?>" style="width: 100px; text-align: center;" readonly></td>
										<?php $wh_id = "wh_".$ctr; ?>

										<?php if(isset($whse)){$wname = $whse;}else{$wname = $this->input->post('wh_'.$ctr);} ?>

										<td style="text-align: center;"><?php echo form_dropdown('wh_'.$ctr, $whlist, $wname);?><br/></td>
									</tr>
									<?php endforeach;?>
									<?php else:?>
									<tr><td colspan="8">No record</td></tr>
									<?php endif;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>


</h5>
	
<br><br>

<?php echo form_close();?>

<?php $this->load->view('footer');?>


<script type="text/javascript">
		
	$('#error_msg').hide();

	<?php if(validation_errors()): ?>
		$('#error_msg').show();
	<?php else: ?>
		$('#error_msg').hide();
	<?php endif; ?>

</script>