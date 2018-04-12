<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php echo $this->load->view('header');?>

<?php echo form_open();
$tokens = explode('/', current_url());
$get = $tokens[sizeof($tokens)-1];?>

<style type="text/css">
	#back_btn a{
		text-decoration: none;
		color: white;
		font-family: Segoe UI;
	}

	#back_btn{
		border-radius: 0px;
		padding: 5px;
		width: 80px;
	}

</style>

<h5>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading" style="background-color: #3e3e40; color:white;">Available and Reserve Items</div>
				<div class="panel-body">
					<table>
						<thead>
						<tr>
							<th>Code</th>
							<th>Code 2</th>
							<th>Item</th>
							<th>Available</th>
							<th>Reserve </th>
						</tr>
						</thead>
						<tfoot>
							<tr>
								<th colspan="5" class="ts-pager form-horizontal">
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
							<?php foreach($itemrecord as $r):?>
							<?php //qty null convert to 0
							if ($r->sqty == null){
								$sqty = 0;}
							else{
								$sqty = $r->sqty;}
							if ($r->tqty == null){
								$tqty = 0;}
							else{
								$tqty = $r->tqty;}
							if ($r->rqty == null){
								$rqty = 0;}
							else{
								$rqty = $r->rqty;}
							$qty = ($sqty - ($tqty + $rqty));
							if ($qty <> 0 OR $rqty <> 0):
								$count = $count + 1; ?>
						<tr>
							<td><?php echo $r->comm__id;?></td>
							<td><?php echo $r->comm__code2;?></td>
							<td><?php echo $r->comm__name;?></td>
							<td align = 'right'><?php echo number_format($qty,2);?></td>
							<?php if ($rqty == '0'):?>
							<td align = 'right'><?php echo number_format($rqty,2);?></td>
							<?php else:?>
							<td align = 'right'><?php echo number_format($rqty,2);?></td>
							<?php endif;?>
						</tr>
							<?php endif;?>
							<?php endforeach;?>
							<?php if ($count == 0):?>
						<tr>
							<td colspan=5>No record</td>
						</tr>
							<?php endif;?>
						</tbody>
					</table>

					<button type="button" class="btn btn-danger" id="back_btn"><?php echo anchor('main/home', 'Back'); ?></button>

				</div>
			</div>
		</div>
	</div>
</div>
</h5>

<br><br>

<?php echo form_close();?>
