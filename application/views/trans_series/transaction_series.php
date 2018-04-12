
<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header'); ?>

<style type="text/css">
	
	#add_series a{
		text-decoration: none;
		color: white;
	}

	a{
		text-decoration: none;
	}

	.js #error_msg{
		display: none;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php echo form_open(); ?>

<div class="container-fluid">
	<div class="col-md-12">
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Transaction Series</strong></div>	
				<div class="panel-body">

					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-danger" id="error_msg">
								<a href="#" class="close" data-dismiss="alert" aria-label="close" style="margin-right: 20px; ">&times;</a>
								<strong><?php echo validation_errors(); ?></strong>
							</div>
						</div>
					</div>
	
					<label>Warehouse</label>
					<?php echo form_dropdown('wh', $whlist, $this->input->post('wh'), 'id="whse_code"');?><br>

					<button type="submit" name="btn_search" class="btn btn-success" id="btn_search"><span class="glyphicon glyphicon-search"></span>&nbsp;Search</button>
					<br/><br/>

					<table>
						<thead>
							<th>TransTypCode</th>
							<th>Description</th>
							<th>FirstNo</th>
							<th>LastNo</th>
							<th>NextNo</th>
							<th>Validity Date</th>
							<th style="text-align: center;">Status</th>
							<th style="text-align: center;">Action</th>
						</thead>
						<tbody>
							<tr>
								<?php if(isset($rcd)): foreach($rcd as $r): ?>
									<td><?php echo $r->sn_code; ?></td>
									<td><?php echo $r->sn_name; ?></td>
									<td><?php echo $r->sn_firstnum; ?></td>
									<td><?php echo $r->sn_lastnum; ?></td>
									<td><?php echo $r->sn_nextnum; ?></td>
									<td><?php echo $r->validity_date; ?></td>
									<?php if($r->sn_status == 1){$stats = 'Active'; $clr = "label label-success";}else{$stats = "Inactive"; $clr = "label label-danger";} ?>
									<td style="text-align: center;"><label class="<?php echo $clr ?>"><?php echo $stats; ?></label></td>
									<td style="text-align: center;">

										<?php echo anchor('main/edit_trans_series/'.$r->whse_code.'-'.$r->sn_code_1,'<i class="glyphicon glyphicon-wrench"></i>
										<span class="hidden-tablet">Edit</span>', 'class="label label-warning"');?>

<!-- 										<?php echo anchor('main/transaction_series','<i class="glyphicon glyphicon-trash"></i>
										<span class="hidden-tablet">Delete</span>', 'class="label label-danger"');?> -->

									</td>
							</tr>
								<?php endforeach; ?>
								<?php else: ?>
							<tr>
								<td colspan="8">No Transaction Series Found</td>
							</tr>
								<?php endif; ?>
						</tbody>
					</table>

					<button type="button" class="btn btn-info" id="add_series"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Add Series</button>

				</div>
			</div>
		</div>
	</div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">

	// $('#add_series').hide();

	$("#add_series").click(function(){

		var x = window.location.href.slice(0,-19);
		x = window.location.href = x + '/add_trans_series/' + $('#whse_code').val();
		
	});

	<?php if(validation_errors()): ?>
		$('#error_msg').show();
	<?php else: ?>
		$('#error_msg').hide();
	<?php endif; ?>
</script>