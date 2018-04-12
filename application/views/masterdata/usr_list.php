<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>
<?php echo form_open();?>

<?php 
	date_default_timezone_set("Asia/Manila");
	$datetime = date('Y-m-d');

	$xls_name = "User_Lists_".$datetime.".xls";

?>

<style type="text/css">

	#btn_add_user a{
		color: white;	
		text-decoration: none;
	}

	#export_xls a{
			color: white;
			text-decoration: none; 
	}

	.del, #error_del, #del_info{
		border-radius: 0px;
	}

	.del a{
		color: white;
		text-decoration: none;
	}

	.js #error_del, #del_info{
		display: none;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<script type="text/javascript">
	
	$(document).ready(function(){

			$('#export_xls a').click(function(){
				$('#tft').remove();
				return ExcellentExport.excel(this, 'myTable', 'User_Lists');
			});

	});

</script>

<h5>
	<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">User Access Lists</div>
							<div class="panel-body">

								<div class="row">
									<div class="col-md-12 form-inline">
										<div class="alert alert-danger" id="error_del">
											<label><strong><span class="glyphicon glyphicon-question-sign"></span>&nbsp;&nbsp;Are you sure you want to delete this user <em><?php echo $this->uri->segment(3); ?></em></strong></label>
											&nbsp;&nbsp;<button class="btn btn-success del" type="submit" name="del_yes"><?php echo anchor('main/user_delete/'.$this->uri->segment(3), 'Yes'); ?></button>
											<button class="btn btn-danger del" type="button" id="del_no">No</button>
										</div>

										<div class="alert alert-info" id="del_info">
											<label><strong><span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;User cannot be deleted</strong></label>
											&nbsp;&nbsp;<button class="btn btn-success del" type="button" id="del_ok">Ok</button>
										</div>

									</div>
								</div>

								<button id="btn_add_user" class="btn btn-info btn-sm" type="button"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;<?php echo anchor('main/add_user', 'Add User'); ?></button>
								<button class="btn btn-warning" id="export_xls"><a download="<?php echo $xls_name; ?>" href="#"><span class="glyphicon glyphicon-open"></span>&nbsp;&nbsp;Export to Excel</a></button>

								<br><br>
								<table id="myTable">
									<thead>
										<tr>
											<th>Name</th>
											<th>User Id</th>
											<th>Email Address</th>
											<th style="text-align: center;">Company</th>
											<th style="text-align: center;">Status</th>
											<th style="text-align: center">Action</th>
										</tr>
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
										<?php if (isset($records)): foreach ($records as $r):?>
										<tr>
											<td><?php echo $r->memb__username;?></td>
											<td><?php echo $r->memb__id; ?></td>
											<td><?php echo $r->memb__email; ?></td>
											<td style="text-align: center;"><?php echo $r->memb_comp; ?></td>
											<?php if($r->memb__status == 1){$stats = "Active"; $lbl_stats = "label label-success";}else{$stats="Inactive"; $lbl_stats = "label label-danger";} ?>
											<td style="text-align: center;"><label class="<?php echo $lbl_stats ?>"><?php echo $stats ?></label></td>
											
											<td style="text-align: center;">

												<?php echo anchor('main/user_access_update_wh/'.$r->memb__id,'<i class="glyphicon glyphicon-home"></i>
												<span class="hidden-tablet">Warehouse</span>', 'class="label label-info"');?>

												<?php echo anchor('main/user_access_update_mod/'.$r->memb__id,'<i class="glyphicon glyphicon-book"></i>
												<span class="hidden-tablet">Module</span>', 'class="label label-success"');?>

												<?php echo anchor('main/user_edit/'.$r->memb__id,'<i class="glyphicon glyphicon-pencil"></i>
												<span class="hidden-tablet">Edit</span>', 'class="label label-warning"');?>

												<?php echo anchor('main/user_access_list/'.$r->memb__id,'<i class="glyphicon glyphicon-trash"></i>
												<span class="hidden-tablet">Delete</span>', 'class="label label-danger"');?>

										</tr>
										<?php endforeach;?><?php else: ?>
										<tr>
											<td colspan="6">No record</td>
										</tr>
									<?php endif;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div><br><br>
</h5>

<?php echo form_close();?>
<?php $this->load->view('footer');?>

<script type="text/javascript">
	$('#error_del').hide();
	$('#del_info').hide();

	// IF $can_del == 0 user can be deleted
	<?php if($can_del == 1): ?>
		$('#del_info').hide();
		$('#error_del').fadeIn(1200);
		$('#error_del').slideDown(1200);
		$('#error_del').show();
	<?php else: ?>
		$('#error_del').hide();
		$('#del_info').fadeIn(1200);
		$('#del_info').slideDown(1200);
		$('#del_info').show();
	<?php endif; ?>

	$('#del_no').click(function(){
		$('#error_del').fadeOut(500);
		// $('#error_del').hide();
	});

	$('#del_ok').click(function(){
		$('#del_info').fadeOut(500);
		// $('#del_info').hide();
	});

</script>