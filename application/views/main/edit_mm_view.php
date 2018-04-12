<?php $this->load->view('bootstrap_files');?>
<!--<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>-->

<script type="text/javascript">

	$(document).ready(function(){
		<?php if(isset($outval) OR validation_errors() OR isset($idescerror) OR isset($idescerror2)): ?>
			$('#error_mm').show();
		<?php else: ?>
			$('#error_mm').hide();
		<?php endif; ?>
	});


	//ALLOW NUMBER ON TEXTFIELD
	function isNumberKey(evt){
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
      }

</script>

<?php 
$js = 'id="wh" onChange="someFunction();"';
$rdate = array('name'=>'rdate','value'=>$cdate,'readonly'=>'true');
$deltype = array('name'=>'deltype','maxlength'=>20,'maxvalue'=>20,'readonly'=>'true','value'=>'Material Management'); 
//$ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20,'onchange'=>"main/myFunction(this.value)",'value'=>$this->input->post('ref')); 
// $ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('ref2'));
$ddate = array('name'=>'ddate','value'=>$cdate,'id'=>'datepicker');
$addr=array('name'=>'remarks','maxlength'=>'250','value'=>$this->input->post('remarks'));
foreach($snmm as $mm){
$tnum=array('name'=>'tnum','maxlength'=>20,'maxvalue'=>20,'value'=>$mm->sn_nextnum,'readonly'=>true);
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

$iqty1=array('name'=>'iqty1','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty1'), 'onkeypress'=>'return isNumberKey(event)');
$iqty2=array('name'=>'iqty2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty2'), 'onkeypress'=>'return isNumberKey(event)');
$iqty3=array('name'=>'iqty3','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty3'), 'onkeypress'=>'return isNumberKey(event)');
$iqty4=array('name'=>'iqty4','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty4'), 'onkeypress'=>'return isNumberKey(event)');
$iqty5=array('name'=>'iqty5','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty5'), 'onkeypress'=>'return isNumberKey(event)');
$iqty6=array('name'=>'iqty6','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty6'), 'onkeypress'=>'return isNumberKey(event)');
$iqty7=array('name'=>'iqty7','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty7'), 'onkeypress'=>'return isNumberKey(event)');
$iqty8=array('name'=>'iqty8','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty8'), 'onkeypress'=>'return isNumberKey(event)');
$iqty9=array('name'=>'iqty9','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty9'), 'onkeypress'=>'return isNumberKey(event)');
$iqty10=array('name'=>'iqty10','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('iqty10'), 'onkeypress'=>'return isNumberKey(event)');

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

$oiqty1=array('name'=>'oiqty1','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty1'), 'onkeypress'=>'return isNumberKey(event)');
$oiqty2=array('name'=>'oiqty2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty2'), 'onkeypress'=>'return isNumberKey(event)');
$oiqty3=array('name'=>'oiqty3','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty3'), 'onkeypress'=>'return isNumberKey(event)');
$oiqty4=array('name'=>'oiqty4','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty4'), 'onkeypress'=>'return isNumberKey(event)');
$oiqty5=array('name'=>'oiqty5','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty5'), 'onkeypress'=>'return isNumberKey(event)');
$oiqty6=array('name'=>'oiqty6','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty6'), 'onkeypress'=>'return isNumberKey(event)');
$oiqty7=array('name'=>'oiqty7','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty7'), 'onkeypress'=>'return isNumberKey(event)');
$oiqty8=array('name'=>'oiqty8','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty8'), 'onkeypress'=>'return isNumberKey(event)');
$oiqty9=array('name'=>'oiqty9','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty9'), 'onkeypress'=>'return isNumberKey(event)');
$oiqty10=array('name'=>'oiqty10','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('oiqty10'), 'onkeypress'=>'return isNumberKey(event)');
$din = 'Delivery In';
$dout = 'Delivery Out';
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

		body{
			background: #ebebeb;
			margin: 0;
			padding: 0;
		}

		#pbody label{
			width: 180px;
		}

		.notes{
			background: #f0f0f0;
			padding: 10px;
		}

		select, input[type="text"], textarea{
			width: 200px;
		}

		.panel-heading a{
			color: gray;
		}

		.panel-heading a:hover{
			color: white;
			text-decoration: none;
		}
		table input[type="text"]{
			width: 100px;
		}

		.table{
			width: 80%;
			margin: auto;
		}

		.table th{
			background-color: #f0f0f0;
			color: black;
			text-align: center;
		}
		.table td{
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

			#btn_back a{
				color: white;
				text-decoration: none;
			}

			.navbar-right{
				margin-right: 15px;
			}

	</style>

	<h5>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;">
						<strong>
						Material Management Edit
						</strong>
					</div>
					<div class="panel-body form-inline" id="pbody">

						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-danger" id="error_mm">
									<strong>
									<?php if(isset($outval)): foreach($outval as $tist):?>
										<p><?php echo $tist;?></p>
									<?php endforeach;?><?php endif;?>
									<p><?php if(isset($idescerror)): ?><?php echo $idescerror;?><?php endif;?></p>
									<p><?php if(isset($idescerror2)): ?><?php echo $idescerror2;?><?php endif;?></p>
									<p class="valid"><?php echo validation_errors();?></p>
									</strong>
								</div>
							</div>
						</div>

						<?php if(isset($edit_record)): foreach($edit_record as $rec): ?>

						<div class="col-md-7">

							<!-- WAREHOUSE CODE FOR SHOWING OF MATERIAL MANAGEMENT PAGE -->
							<input type="hidden" name="wh_code_mm" value="<?php echo $this->uri->segment(3); ?>">

							<label>Type *</label>
							<?php echo form_input($deltype);?><br/><br/>
							<label>Process *</label>
							<?php echo form_dropdown('process',$process,$rec->wi_mmprocess);?><br/><br/>
							<label>Customer Name *</label>
							<?php echo form_dropdown('cusname',$bpname,$rec->wi_refname);?><br/><br/>
							<label>Reference No. 1 *</label>
							<?php echo form_dropdown('doctype1',$reftype1,$rec->wi_reftype);?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="ref" maxlength="20" maxvalue="20" onchange="main/myFunction(this.value)" value="<?php echo $rec->wi_refnum?>" required />
							<br/><br/>
							<label>Reference No. 2</label>
							<?php echo form_dropdown('doctype2',$reftype2,$rec->wi_reftype2);?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="ref2" maxlength="20" maxvalue="20" onchange="main/myFunction(this.value)" value="<?php echo $rec->wi_refnum2?>" required />
							<br/><br/>
							<label>Posting Date</label>
							<input type="text" name="ddate" maxlength="20" maxvalue="20" value="<?php echo $rec->deldate?>" readonly />
							<br/><br/>
						</div>
						<div class="col-md-4">
							<label>Creation Date</label>
							<input type="text" name="rdate" maxlength="20" maxvalue="20" value="<?php echo $rec->deldate?>" readonly/><br/><br/>
							<label>Transaction No.</label>
							<input type="text" name="tnum" maxlength="20" maxvalue="20" value="<?php echo $rec->wi_transno?>" readonly/>
							<br/><br/>
							<label>Remarks</label>
							<textarea name="remarks" cols="4" rows="4"><?php echo $rec->wi_remarks ?></textarea>
							<br/><br/>
						</div>

					<?php endforeach; ?>
					<?php endif; ?>

						<div class="row">
							<table class="table table-bordered">
								<tr>
									<th>No.</th>
									<th>Delivery Type</th>
									<th>Location</th>
									<th>Description</th>
									<th>Unit of Measurement</th>
									<th>Quantity</th>
								</tr>

								<?php if(isset($mm_ctr)): foreach($mm_ctr as $r3): ?>

									<input type="hidden" name="ctr" value="<?php echo $r3->ctr ?>" id="counter">

								<?php endforeach; ?>
								<?php endif; ?>

								<?php $ctr = 1; ?>

								<?php if(isset($rec_list)): foreach($rec_list as $r2): ?>

								<tr>

								<?php if($r2->wi_type == 0){ $wt = "Delivery In";}else{$wt =  "Delivery Out";};?>

									<td><?php echo $ctr; ?></td>
									<td><input type="text" name="" value="<?php echo $wt ?>" readonly></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>
									<?php $l = "loc".$ctr; ?>
									<td><input type="text" name="<?php echo $l ?>" value="<?php echo $r2->wh_name ?>" style="width:200px;" readonly></td>
										<?php endforeach;?><?php endif;?>
									<td>
										<?php  $ic = "itemcode_".$ctr; ?>
										<?php  $ic2 = "icode_".$ctr; ?>
										<?php  $ic3 = "icodeo_".$ctr; ?>
										<?php echo form_dropdown('desc'.$ctr, $item,$r2->item_id, 'id='.$ic);?>
										<input type="hidden" name="<?php echo $ic3 ?>" id="<?php echo $ic3 ?>" value="<?php echo $r2->item_id; ?>">
										<input type="hidden" name="<?php echo $ic2 ?>" id="<?php echo $ic2 ?>" value="<?php echo $r2->item_id; ?>">
									</td>
									<td>
										<?php  $iu = "itemuom_".$ctr; ?>
										<?php  $iu2 = "iuom_".$ctr; ?>
										<?php  $iu3 = "iuomo_".$ctr; ?>
										<?php echo form_dropdown('unit'.$ctr, $uom, $r2->item_uom, 'id='.$iu); ?>
										<input type="hidden" name="<?php echo $iu3 ?>" id="<?php echo $iu3 ?>" value="<?php echo $r2->item_uom; ?>">
										<input type="hidden" name="<?php echo $iu2 ?>" id="<?php echo $iu2 ?>" value="<?php echo $r2->item_uom; ?>">
									</td>
									<?php $q = "qty".$ctr; ?>
									<td><input type="text" name="<?php echo $q ?>" value="<?php echo $r2->wi_itemqty ?>" onkeypress="return isNumberKey(event)"></td>

								<?php $ctr+=1; ?>

								<?php endforeach; ?>
								<?php else: ?>
									<td colspan="7">No Record(s)</td>
								</tr>
								<?php endif; ?>

								
								
							</table>
						</div><br/><br/>

						<div style="margin: auto; width: 400px; padding: 10px;">
							<input type="submit" name="submit" value="Submit" class="btn btn-info"  style="width: 150px;"/>
							<button type="button" class="btn btn-danger" style="width: 150px;" id="btn_back"><?php echo anchor('main/wh_delivery_approve_mm_list', 'Back'); ?></button>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	</h5><br/>

	<div id="footer">
		<p style="text-align:center;">
			<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
		</p>
	</div>
		
	
<?php echo form_close();?>

<script type="text/javascript">
	$(document).ready(function(){

		var ctr = $('#counter').val();

		for(x=1; x<=ctr; x++){

			// ITEMCODE
			$("#itemcode_"+x).change(function(){

				var val = this.id;
				var temp = val.substr(val.indexOf("_") + 1)
				var temp2 = $("#itemcode_"+temp+" option:selected").val();

				$("#icode_"+temp).val(temp2);

			});

			// ITEMUOM
			$("#itemuom_"+x).change(function(){

				var val = this.id;
				var temp = val.substr(val.indexOf("_") + 1)
				var temp2 = $("#itemuom_"+temp+" option:selected").val();

				$("#iuom_"+temp).val(temp2);

			});

		}
	})
</script>