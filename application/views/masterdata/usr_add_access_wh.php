<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php echo form_open();?>

<style type="text/css">

	#btn_back a{
		color: white;
		text-decoration: none;
	}
	
	#note{
		font-size: 12px;
		font-style: italic;
		font-weight: bold;
	}

</style>

<?php $tokens = explode('/', current_url());?>

	<h5>
		<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;">Warehouse User Access</div>
							<div class="panel-body">
								<div class="col-md-3">
									<label>Name</label>
									<div class="input-group input-group-md">
										<?php $bpname=array('name'=>'uname','value'=>$tokens[sizeof($tokens)-1],'maxlength'=> '50','size'=> '50','readonly'=>true); echo form_input($bpname);?>
									</div>
									<div class="button">
										<input type="submit" class="btn btn-info" value="Update" name="add"/>
										<button id="btn_back" class="btn btn-danger" type="button"><?php echo anchor('main/user_access_list', 'Back'); ?></button>
									</div>
								</div>
								<div class="col-md-3">
									<label>Access</label>
									<div class="input-group input-group-md">
										<?php echo form_dropdown('whouse[]',$whlist, '', 'multiple="multiple"');?>
									</div>
									<label id="note"> Note: Press and Hold Ctrl key for multiple selection</label>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-body">
								<table>
									<thead>
										<tr>
											<th>Warehouse Access</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th colspan="2" class="ts-pager form-horizontal">
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
										<?php if (isset($uwhlist)): foreach($uwhlist as $r1):?>
										<tr>
											<td><?php echo $r1->accessname;?></td>
										</tr>
										<?php endforeach;?>
										<?php else:?>
										<tr>
											<td>No record</td>
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
	<h4><?php if(isset($error)): echo $error;?></h4>
	<?php endif;?>
	
<?php echo form_close();?>

<?php $this->load->view('footer');?>