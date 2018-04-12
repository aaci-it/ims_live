

<?php 
foreach ($records as $r){
$js = 'id="wh" onChange="someFunction();"';
$rdate = array('name'=>'rdate','value'=>$r->wi_createdatetime,'readonly'=>'true');
$deltype = array('name'=>'deltype','maxlength'=>20,'maxvalue'=>20,'readonly'=>'true','value'=>'Material Management'); 
$ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20,'onchange'=>"main/myFunction(this.value)",'value'=>$r->wi_refnum,'readonly'=>'true'); 
$ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20,'value'=>$r->wi_refnum2,'readonly'=>'true');
$ddate = array('name'=>'ddate','value'=>$cdate,'id'=>'datepicker');
$addr=array('name'=>'remarks','maxlength'=>'250','value'=>$r->wi_remarks,'readonly'=>'true');
$cusname=array('name'=>'cusname','maxlength'=>'250','value'=>$r->CardName,'readonly'=>'true');
//$vcustomer = $r->wi_refname; 
$process=array('name'=>'process','maxlength'=>'250','value'=>$r->wi_mmprocess,'readonly'=>'true');
//$vprocess = $r->wi_mmprocess;
$doctype1=array('name'=>'doctype1','maxlength'=>'250','value'=>$r->wi_reftype,'readonly'=>'true');
//$vref1=$r->wi_reftype;
$doctype2=array('name'=>'doctype2','maxlength'=>'50','value'=>$r->wi_reftype2,'readonly'=>'true');
//$vref2=$r->wi_reftype2;
$tnum=array('name'=>'tnum','maxlength'=>20,'maxvalue'=>20,'value'=>$snmm,'readonly'=>true);
}
//$deltype1=array('name'=>'deltype1','maxlength'=>20,'maxvalue'=>20,'value'=>'Delivery Out','readonly'=>true);
//$deltype2=array('name'=>'deltype2','maxlength'=>20,'maxvalue'=>20,'value'=>'Delivery In','readonly'=>true);

$iunit1=array('name'=>'iunit1','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iunit1'));
$iunit2=array('name'=>'iunit2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iunit2'));
$iunit3=array('name'=>'iunit3','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iunit3'));
$iunit4=array('name'=>'iunit4','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iunit4'));
$iunit5=array('name'=>'iunit5','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iunit5'));
$iunit6=array('name'=>'iunit6','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iunit6'));
$iunit7=array('name'=>'iunit7','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iunit7'));
$iunit8=array('name'=>'iunit8','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iunit8'));
$iunit9=array('name'=>'iunit9','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iunit9'));
$iunit10=array('name'=>'iunit10','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iunit10'));
$iqty1=array('name'=>'iqty1','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty1'));
$iqty2=array('name'=>'iqty2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty2'));
$iqty3=array('name'=>'iqty3','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty3'));
$iqty4=array('name'=>'iqty4','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty4'));
$iqty5=array('name'=>'iqty5','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty5'));
$iqty6=array('name'=>'iqty6','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty6'));
$iqty7=array('name'=>'iqty7','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty7'));
$iqty8=array('name'=>'iqty8','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty8'));
$iqty9=array('name'=>'iqty9','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty9'));
$iqty10=array('name'=>'iqty10','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty10'));

$oiunit1=array('name'=>'oiunit1','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiunit1'));
$oiunit2=array('name'=>'oiunit2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiunit2'));
$oiunit3=array('name'=>'oiunit3','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiunit3'));
$oiunit4=array('name'=>'oiunit4','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiunit4'));
$oiunit5=array('name'=>'oiunit5','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiunit5'));
$oiunit6=array('name'=>'oiunit6','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiunit6'));
$oiunit7=array('name'=>'oiunit7','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiunit7'));
$oiunit8=array('name'=>'oiunit8','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiunit8'));
$oiunit9=array('name'=>'oiunit9','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiunit9'));
$oiunit10=array('name'=>'oiunit10','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiunit10'));
$oiqty1=array('name'=>'oiqty1','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty1'));
$oiqty2=array('name'=>'oiqty2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty2'));
$oiqty3=array('name'=>'oiqty3','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty3'));
$oiqty4=array('name'=>'oiqty4','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty4'));
$oiqty5=array('name'=>'oiqty5','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty5'));
$oiqty6=array('name'=>'oiqty6','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty6'));
$oiqty7=array('name'=>'oiqty7','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty7'));
$oiqty8=array('name'=>'oiqty8','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty8'));
$oiqty9=array('name'=>'oiqty9','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty9'));
$oiqty10=array('name'=>'oiqty10','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty10'));
$din = 'Delivery In';
$dout = 'Delivery Out';
$iocount = 0;
?>

<?php echo form_open();?>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="/resources/demos/style.css" />
	<script>
		$(function() {
		$( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
		$( "#datepicker1" ).datepicker({dateFormat: 'yy-mm-dd'});
		});
	</script>

	<style type="text/css">

		#pbody label{
			width: 150px;
		}

		th{
			background-color: #3e3e40;
			color: white;
			text-align: center;
		}

		#footer{
			   position:fixed;
			   left:0px;
			   bottom:0px;
			   height:40px;
			   width:100%;
			   background:#337ab7;
			   padding: 15px;
			}

			#footer label{
				font-size: 11px;
				line-height: 0px;
				color: white;
				letter-spacing: 1.5px;
				font-weight: normal;
			}

	</style>

		<h4>
			<?php
					if(isset($outval)): foreach($outval as $tist):?>
				<p><?php echo $tist;?></p>
					<?php endforeach;?><?php endif;?>
				<p><?php if(isset($idescerror)): ?><?php echo $idescerror;?><?php endif;?></p>
				<p><?php if(isset($idescerror2)): ?><?php echo $idescerror2;?><?php endif;?></p>
			
				<p class="valid"><?php echo validation_errors();?></p>
		</h4>

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Material Management</strong></div>
						<div class="panel-body form-inline" id="pbody">
							<div class="row">
								<div class="col-md-8">
									<label>Type *</label>
									<?php echo form_input($deltype);?><br/><br/>
									<label>Process *</label>
									<?php echo form_input($process);?><br/><br/>
									<label>Customer Name *</label>
									<?php echo form_input($cusname);?><br/><br/>
									<label>Reference No. 1 *</label>
									<?php echo form_input($doctype1);?>
									<?php echo form_input($ref);?> <br/><br/>
									<label>Reference No. 2 *</label>
									<?php echo form_input($doctype2); ?>
									<?php echo form_input($ref2);?> <br/><br/>
									<label>Posting Date *</label>
									<?php echo form_input($ddate);?><br/><br/>

								</div>
								<div class="col-md-4">
									<label>Creation Date & Time</label>
									<?php echo form_input($rdate);?><br/><br/>
									<label>Transaction No.</label>
									<?php echo form_input($tnum);?><br/><br/>
									<label>Remarks</label>
									<?php echo form_textarea($addr);?><br/><br/>
								</div>
							</div>
							<div class="row">
								<table class="table table-bordered" style="width: 95%; margin: auto;">
									<tr>
										<th>Item</th>
										<th>Status</th>
										<th>Delivery Type</th>
										<th>Location</th>
										<th>Description</th>
										<th>Unit of Measurement</th>
										<th>Quantity</th>
									</tr>
									<?php if(isset($itemout)):foreach($itemout as $io):?>
									<?php $iocount ++;?>
									<tr>
										<td></td>
										<td><?php echo form_checkbox('chkio','accept1',true);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo $io->wh_name;?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo $io->comm__name;?></td>
										<td><?php echo $io->item_uom;?></td>
										<td><?php echo $io->wi_itemqty;?></td>
									</tr>
									<?php  endforeach;?>
									<?php endif;?>
									<tr>
										<td>1</td>
										<td><?php echo form_checkbox('chk1','accept1',false);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo form_dropdown('iloc1',$whlist,$this->input->post('iloc1'));?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo form_dropdown('idesc1',$item,$this->input->post('idesc1'));?></td>
										<td><?php echo form_input($iunit1);?></td>
										<td><?php echo form_input($iqty1);?></td>
									</tr>
									<tr>
										<td>2</td>
										<td><?php echo form_checkbox('chk2','accept2',false);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo form_dropdown('iloc2',$whlist,$this->input->post('iloc2'));?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo form_dropdown('idesc2',$item,$this->input->post('idesc2'));?></td>
										<td><?php echo form_input($iunit2);?></td>
										<td><?php echo form_input($iqty2);?></td>
									</tr>
									<tr>
										<td>3</td>
										<td><?php echo form_checkbox('chk3','accept3',false);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo form_dropdown('iloc3',$whlist,$this->input->post('iloc3'));?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo form_dropdown('idesc3',$item,$this->input->post('idesc3'));?></td>
										<td><?php echo form_input($iunit3);?></td>
										<td><?php echo form_input($iqty3);?></td>
									</tr>
									<tr>
										<td>4</td>
										<td><?php echo form_checkbox('chk4','accept4',false);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo form_dropdown('iloc4',$whlist,$this->input->post('iloc4'));?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo form_dropdown('idesc4',$item,$this->input->post('idesc4'));?></td>
										<td><?php echo form_input($iunit4);?></td>
										<td><?php echo form_input($iqty4);?></td>
									</tr>
									<tr>
										<td>5</td>
										<td><?php echo form_checkbox('chk5','accept5',false);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo form_dropdown('iloc5',$whlist,$this->input->post('iloc5'));?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo form_dropdown('idesc5',$item,$this->input->post('idesc5'));?></td>
										<td><?php echo form_input($iunit5);?></td>
										<td><?php echo form_input($iqty5);?></td>
									</tr>
									<tr>
										<td>6</td>
										<td><?php echo form_checkbox('chk6','accept6',false);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo form_dropdown('iloc6',$whlist,$this->input->post('iloc6'));?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo form_dropdown('idesc6',$item,$this->input->post('idesc6'));?></td>
										<td><?php echo form_input($iunit6);?></td>
										<td><?php echo form_input($iqty6);?></td>
									</tr>
									<tr>
										<td>7</td>
										<td><?php echo form_checkbox('chk7','accept7',false);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo form_dropdown('iloc7',$whlist,$this->input->post('iloc7'));?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo form_dropdown('idesc7',$item,$this->input->post('idesc7'));?></td>
										<td><?php echo form_input($iunit7);?></td>
										<td><?php echo form_input($iqty7);?></td>
									</tr>
									<tr>
										<td>8</td>
										<td><?php echo form_checkbox('chk8','accept8',false);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo form_dropdown('iloc8',$whlist,$this->input->post('iloc8'));?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo form_dropdown('idesc8',$item,$this->input->post('idesc8'));?></td>
										<td><?php echo form_input($iunit8);?></td>
										<td><?php echo form_input($iqty8);?></td>
									</tr>
									<tr>
										<td>9</td>
										<td><?php echo form_checkbox('chk9','accept9',false);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo form_dropdown('iloc9',$whlist,$this->input->post('iloc9'));?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo form_dropdown('idesc9',$item,$this->input->post('idesc9'));?></td>
										<td><?php echo form_input($iunit9);?></td>
										<td><?php echo form_input($iqty9);?></td>
									</tr>
									<tr>
										<td>10</td>
										<td><?php echo form_checkbox('chk10','accept10',false);?></td>
										<td><?php echo $dout;?></td>
											<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
										<td><?php echo form_dropdown('iloc10',$whlist,$this->input->post('iloc10'));?></td>
											<?php //endforeach;?><?php //endif;?>
										<td><?php echo form_dropdown('idesc10',$item,$this->input->post('idesc10'));?></td>
										<td><?php echo form_input($iunit10);?></td>
										<td><?php echo form_input($iqty10);?></td>
									</tr>
								</table><br/>
								<div class="row">
									<table class="table table-bordered" style="width: 95%; margin: auto;">
										<tr>
											<th>Item</th>
											<th>Status</th>
											<th>Delivery Type</th>
											<th>Location</th>
											<th>Description</th>
											<th>Unit of Measurement</th>
											<th>Quantity</th>
										</tr>
										<?php if(isset($itemin)):foreach($itemin as $ii):?>
										<?php $iocount ++;?>
										<tr>
											<td></td>
											<td><?php echo form_checkbox('chkii','accept1',true);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo $ii->wh_name;?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo $ii->comm__name;?></td>
											<td><?php echo $ii->item_uom;?></td>
											<td><?php echo $ii->wi_itemqty;?></td>
										</tr>
										<?php  endforeach;?>
										<?php endif;?>
										<tr>
											<td>1</td>
											<td><?php echo form_checkbox('ochk1','oaccept1',false);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo form_dropdown('oiloc1',$whlist,$this->input->post('oiloc1'));?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo form_dropdown('oidesc1',$item,$this->input->post('oidesc1'));?></td>
											<td><?php echo form_input($oiunit1);?></td>
											<td><?php echo form_input($oiqty1);?></td>
										</tr>
										<tr>
											<td>2</td>
											<td><?php echo form_checkbox('ochk2','oaccept2',false);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo form_dropdown('oiloc2',$whlist,$this->input->post('oiloc2'));?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo form_dropdown('oidesc2',$item,$this->input->post('oidesc2'));?></td>
											<td><?php echo form_input($oiunit2);?></td>
											<td><?php echo form_input($oiqty2);?></td>
										</tr>
										<tr>
											<td>3</td>
											<td><?php echo form_checkbox('ochk3','oaccept3',false);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo form_dropdown('oiloc3',$whlist,$this->input->post('oiloc3'));?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo form_dropdown('oidesc3',$item,$this->input->post('oidesc3'));?></td>
											<td><?php echo form_input($oiunit3);?></td>
											<td><?php echo form_input($oiqty3);?></td>
										</tr>
										<tr>
											<td>4</td>
											<td><?php echo form_checkbox('ochk4','oaccept4',false);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo form_dropdown('oiloc4',$whlist,$this->input->post('oiloc4'));?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo form_dropdown('oidesc4',$item,$this->input->post('oidesc4'));?></td>
											<td><?php echo form_input($oiunit4);?></td>
											<td><?php echo form_input($oiqty4);?></td>
										</tr>
										<tr>
											<td>5</td>
											<td><?php echo form_checkbox('ochk5','oaccept5',false);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo form_dropdown('oiloc5',$whlist,$this->input->post('oiloc5'));?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo form_dropdown('oidesc5',$item,$this->input->post('oidesc5'));?></td>
											<td><?php echo form_input($oiunit5);?></td>
											<td><?php echo form_input($oiqty5);?></td>
										</tr>
										<tr>
											<td>6</td>
											<td><?php echo form_checkbox('ochk6','oaccept6',false);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo form_dropdown('oiloc6',$whlist,$this->input->post('oiloc6'));?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo form_dropdown('oidesc6',$item,$this->input->post('oidesc6'));?></td>
											<td><?php echo form_input($oiunit6);?></td>
											<td><?php echo form_input($oiqty6);?></td>
										</tr>
										<tr>
											<td>7</td>
											<td><?php echo form_checkbox('ochk7','oaccept7',false);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo form_dropdown('oiloc7',$whlist,$this->input->post('oiloc7'));?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo form_dropdown('oidesc7',$item,$this->input->post('oidesc7'));?></td>
											<td><?php echo form_input($oiunit7);?></td>
											<td><?php echo form_input($oiqty7);?></td>
										</tr>
										<tr>
											<td>8</td>
											<td><?php echo form_checkbox('ochk8','oaccept8',false);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo form_dropdown('oiloc8',$whlist,$this->input->post('oiloc8'));?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo form_dropdown('oidesc8',$item,$this->input->post('oidesc8'));?></td>
											<td><?php echo form_input($oiunit8);?></td>
											<td><?php echo form_input($oiqty8);?></td>
										</tr>
										<tr>
											<td>9</td>
											<td><?php echo form_checkbox('ochk9','oaccept9',false);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo form_dropdown('oiloc9',$whlist,$this->input->post('oiloc9'));?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo form_dropdown('oidesc9',$item,$this->input->post('oidesc9'));?></td>
											<td><?php echo form_input($oiunit9);?></td>
											<td><?php echo form_input($oiqty9);?></td>
										</tr>
										<tr>
											<td>10</td>
											<td><?php echo form_checkbox('chk100','oaccept10',false);?></td>
											<td><?php echo $din;?></td>
												<?php //if(isset($warehouse)):foreach($warehouse as $r):?>
											<td><?php echo form_dropdown('oiloc10',$whlist,$this->input->post('oiloc10'));?></td>
												<?php //endforeach;?><?php //endif;?>
											<td><?php echo form_dropdown('oidesc10',$item,$this->input->post('oidesc10'));?></td>
											<td><?php echo form_input($oiunit10);?></td>
											<td><?php echo form_input($oiqty10);?></td>
										</tr>
									</table><br/>

									<div class="col-md-4" style="margin-left: 30px;">
										<input type="submit" name="submit" value="Submit" class="btn btn-info"/>
										<button class="btn btn-danger" onclick="history.back();">Back</button>
									</div>
								
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><br/>

		<div id="footer">
			<p style="text-align:center;">
				<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
			</p>
		</div>

<?php echo form_close();?>