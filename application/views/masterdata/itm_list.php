<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php  
	
	date_default_timezone_set("Asia/Manila");
	$datetime = date('Y-m-d');

	$xls_name = "Item_List_".$datetime.".xls";
	
?>

<?php echo form_open();?>

<style type="text/css">

	button a, button a:hover{
		color: white;
	}

	#del_info, #del_msg, .del{
		border-radius: 0px;
	}

	#del_no a{
		color: white;
		text-decoration: none;
	}

	.js #del_msg, #del_info{
		display: none;
	}

	#export_xls a{
			color: white;
			text-decoration: none; 
	}

	#del_yes a{
		color: white;
		text-decoration: none;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<script type="text/javascript">
	$(document).ready(function(){

			$('#export_xls a').click(function(){
				$('#tft').remove();
				var x = window.location.href;
		        window.location.href = x;
				return ExcellentExport.excel(this, 'myTable', 'Item_List');
				
			});

		});
</script>

<h5>

	<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Item Lists</div>
							<div class="panel-body">

								<div class="alert alert-danger" id="del_msg">
									<strong><span class="glyphicon glyphicon-question-sign"></span>&nbsp;&nbsp;Are you sure you want to delete this item <u><em><?php echo $this->uri->segment(3); ?></em></u> ? </strong>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="submit" class="btn btn-info btn-xs del" name="del_yes" value="delete" id="del_yes"><?php echo anchor('main/item_delete/'.$this->uri->segment(3), 'Yes'); ?></button>
									<button type="button" class="btn btn-danger btn-xs del" id="del_no">No</button>
								</div>

								<div class="alert alert-info" id="del_info">
									<strong><span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;Item cannot be deleted</strong>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="button" class="btn btn-success btn-xs del" id="del_ok">Ok</button>
								</div>

								<button class="btn btn-info"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;<?php echo anchor('main/item_create','Add Item');?></button>

								<button class="btn btn-warning" id="export_xls"><a download="<?php echo $xls_name; ?>" href="#"><span class="glyphicon glyphicon-open"></span>&nbsp;&nbsp;Export to Excel</a></button><br/><br/>

								<table id="myTable">
									<thead>
									<tr>
										<th>Code</th>
										<th>Code 2</th>
										<th>Name</th>
										<th style="text-align: center;">Whse Item Group</th>
										<th style="text-align: center;">Type</th>
										<th style="text-align: center;">Sub Type</th>
										<th style="text-align: center;">Status</th>
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
									<?php if(isset($records)): foreach ($records as $r):?>
									<tr>
										<td><?php echo $r->comm__id; ?></td>
										<td><?php echo $r->comm__code2; ?></td>
										<td><?php echo $r->comm__name;?></td>
										<td style="text-align: center;"><?php echo $r->Description; ?></td>
										<td style="text-align: center;"><?php echo $r->item_type; ?></td>
										<td style="text-align: center;"><?php echo $r->item_subtype; ?></td>
										<?php if($r->status == 1){$stats = "Active"; $clr = "label label-success";}else{$stats = "Inactive"; $clr = "label label-danger";} ?>
										<td style="text-align:center;"><label class="<?php echo $clr ?>"><?php echo $stats; ?></label></td>
										<td style="text-align:center;">

											<?php echo anchor('main/item_edit/'.$r->comm__id,'<i class="glyphicon glyphicon-wrench"></i>
											<span class="hidden-tablet">Edit</span>', 'class="label label-warning"');?>

											<?php echo anchor('main/item_list/'.$r->comm__id,'<i class="glyphicon glyphicon-trash"></i>
											<span class="hidden-tablet">Delete</span>', 'class="label label-danger"');?>

										</td>
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
	
		// <?php if($this->uri->segment(3) == ""): ?>
		// 		$('#del_confirm').hide();
		// 		$('#del_confirm_2').hide();
		// 		$('#del_confirm_bal').hide();
		// <?php else: ?>
		// 	<?php if(isset($check_bal)): foreach($check_bal as $cb): ?>
		// 		<?php $qty = ($cb->sqty - ($cb->tqty + $cb->rqty)); ?>

		// 		<?php if($qty == 0 ): ?>
		// 			$('#del_confirm_bal').hide();
		// 			$('#del_confirm_2').hide();
		// 			$('#del_confirm').show();
		// 		<?php else: ?>
		// 			$('#del_confirm').hide();
		// 			$('#del_confirm_2').hide();
		// 			$('#del_confirm_bal').show();

		// 			$('#del_yes_bal').click(function(){
		// 				$('#del_confirm_bal').hide();
		// 				$('#del_confirm').hide();
		// 				$('#del_confirm_2').show();
		// 			});

		// 		<?php endif; ?>

		// 	<?php endforeach; ?>
		// 	<?php endif; ?>
		// <?php endif; ?>


	$('#del_msg').hide();
	$('#del_info').hide();

	<?php if($can_del == 1): ?>
		$('#del_msg').hide();
		$('#del_info').fadeIn(1200);
		$('#del_info').slideDown(1200);
		$('#del_info').show();
	<?php else: ?>
		$('#del_info').hide();
		$('#del_msg').fadeIn(1200);
		$('#del_msg').slideDown(1200);
		$('#del_msg').show();
	<?php endif; ?>

	$('#del_ok').click(function(){
		$('#del_info').fadeOut(1200);
	});

	$('#del_no').click(function(){
		$('#del_msg').fadeOut(1200);
	});


</script>
