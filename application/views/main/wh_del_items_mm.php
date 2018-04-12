<?php $this->load->view('bootstrap_files');?>
<!--<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>-->

<script type="text/javascript">
	//ALLOW NUMBER ON TEXTFIELD
	function isNumberKey(evt){
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
     }

</script>

<script type="text/javascript">
	$(document).ready(function(){

		var x;
		for(x=0;x<=10;x++){
			$('#idesc_'+x).change(function(){

				// GET THE ID
				var id_temp = $(this).attr('id');
				var parts = id_temp.indexOf('_', id_temp.indexOf('_')+1);
				var the_num = id_temp.substr( parts );

				$.ajax({
					type: "POST",
					url: "<?php echo base_url() ?>index.php/main/get_available",
					dataType: "json",
					data: {itemcode: $('#idesc_'+ the_num).val(), whcode: $('#doloc_'+ the_num).val()},
					success: function(data){
						
						$.each(data.avail_data, function(index, val){

							if(val.sqty == null){
								val.sqty = 0.00;
							}else{
								val.sqty = parseFloat(val.sqty);
							}

							if(val.tqty == null){
								val.tqty = 0.00;
							}else{
								val.tqty = parseFloat(val.tqty);
							}

							if(val.rqty == null){
								val.rqty = 0.00;
							}else{
								val.rqty = parseFloat(val.rqty);
							}

							var qty  = (parseFloat(val.sqty) - (parseFloat(val.tqty) + parseFloat(val.rqty)));
							var total = parseFloat(qty) + parseFloat(val.rqty);

							$('#ado_'+the_num).val(parseFloat(qty).toFixed(2));
							$('#rdo_'+the_num).val(parseFloat(val.rqty).toFixed(2));
							$('#tdo_'+the_num).val(parseFloat(total).toFixed(2));
						});
					}
				});
			});	
		}



		var y;
		for(y=0;y<=10;y++){
			$('#oidesc_'+y).change(function(){

				// GET THE ID
				var id_temp = $(this).attr('id');
				var parts = id_temp.indexOf('_', id_temp.indexOf('_')+1);
				var the_num = id_temp.substr( parts );

				$.ajax({
					type: "POST",
					url: "<?php echo base_url() ?>index.php/main/get_available",
					dataType: "json",
					data: {itemcode: $('#oidesc_'+ the_num).val(), whcode: $('#diloc_'+ the_num).val()},
					success: function(data){
						
						$.each(data.avail_data, function(index, val){

							if(val.sqty == null){
								val.sqty = 0.00;
							}else{
								val.sqty = parseFloat(val.sqty);
							}

							if(val.tqty == null){
								val.tqty = 0.00;
							}else{
								val.tqty = parseFloat(val.tqty);
							}

							if(val.rqty == null){
								val.rqty = 0.00;
							}else{
								val.rqty = parseFloat(val.rqty);
							}

							var qty  = (parseFloat(val.sqty) - (parseFloat(val.tqty) + parseFloat(val.rqty)));
							var total = parseFloat(qty) + parseFloat(val.rqty);

							$('#adi_'+the_num).val(parseFloat(qty).toFixed(2));
							$('#rdi_'+the_num).val(parseFloat(val.rqty).toFixed(2));
							$('#tdi_'+the_num).val(parseFloat(total).toFixed(2));

						});
					}
				});
			});	
		}


	});
</script>

<?php 
$js = 'id="wh" onChange="someFunction();"';
$rdate = array('name'=>'rdate','value'=>$cdate,'readonly'=>'true');
$deltype = array('name'=>'deltype','maxlength'=>20,'maxvalue'=>20,'readonly'=>'true','value'=>'Material Management'); 
//$ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20,'onchange'=>"main/myFunction(this.value)",'value'=>$this->input->post('ref')); 
$ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20,'value'=>$this->input->post('ref2'));
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

			var x = 0;
			for(x=0;x<=10;x++){

				$('#ado_'+x).attr('readonly', 'true');
				$('#rdo_'+x).attr('readonly', 'true');
				$('#tdo_'+x).attr('readonly', 'true');

				$('#adi_'+x).attr('readonly', 'true');
				$('#rdi_'+x).attr('readonly', 'true');
				$('#tdi_'+x).attr('readonly', 'true');

			}

			var x;
			for(x=0; x<=10; x++){
				$('#iloc_'+x).change(function(){

					// GET THE ID
					var id_temp = $(this).attr('id');
					var parts = id_temp.indexOf('_', id_temp.indexOf('_')+1);
					var the_num = id_temp.substr( parts );

					$("#doloc_" + the_num + " option").filter(function() {
					    return $(this).text() == $('#iloc_' + the_num).val(); 
					}).attr('selected', true).selectmenu('refresh', true);

				});
			}


			var y;
			for(y=0; y<=10; y++){
				$('#oiloc_'+y).change(function(){

					// GET THE ID
					var id_temp = $(this).attr('id');
					var parts = id_temp.indexOf('_', id_temp.indexOf('_')+1);
					var the_num = id_temp.substr( parts );

					$("#diloc_" + the_num + " option").filter(function() {
					    return $(this).text() == $('#oiloc_' + the_num).val(); 
					}).attr('selected', true).selectmenu('refresh', true);

				});
			}

			<?php 

				$temp = $this->uri->segment(3);
				$slen = 0;

				if(strlen($temp) == 2){
					$slen = -23;
				}else{
					$slen = -22;
				}

			 ?>

			<?php if(!isset($trans_no)): ?>
				$("#dialog-error").show();
				$("#dialog-error").dialog({
					modal: true,
					buttons: {
						Ok: function() {
					  		$(this).dialog( "close" );
					  		var x = window.location.href.slice(0, <?php echo $slen ?>);
				          	window.location.href = x;
						}
					}
				});
			<?php endif; ?>

			<?php if(isset($cvdate_mm)): ?>
				$("#dialog-error-date").show();
				$("#dialog-error-date").dialog({
					modal: true,
					buttons: {
						Ok: function() {
					  		$(this).dialog( "close" );
					  		var x = window.location.href.slice(0, <?php echo $slen ?>);
				          	window.location.href = x;
						}
					}
				});
			<?php endif; ?>


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

			.navbar-right{
				margin-right: 15px;
			}

			.js #error_mm, #dialog-error, #dialog-error-date{
				display: none;
			}

			td input, select[option]{
				text-align: center;
			}

		.ui-dialog-titlebar-close {
		    visibility: hidden;
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
					<div class="panel-heading" style="background-color: #3e3e40; color:white;">
						<strong>
						<?php echo anchor('main/wh_delivery_item_in/'.$this->uri->segment(3),'Delivery In');?> |
						<?php echo anchor('main/wh_delivery_item_reserve/'.$this->uri->segment(3),'Delivery Out Manual');?> |
						<?php echo anchor('main/wh_delivery_item_reserve_ho/'.$this->uri->segment(3),'Delivery Out HO');?> |
						Material Management |
						<?php echo anchor('main/tin_tout/'.$this->uri->segment(3),'Transfer In and Out');?>
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

						<div class="col-md-7">

							<!-- WAREHOUSE CODE FOR SHOWING OF MATERIAL MANAGEMENT PAGE -->
							<input type="hidden" name="wh_code_mm" value="<?php echo $this->uri->segment(3); ?>">

							<label>Type *</label>
							<?php echo form_input($deltype);?><br/><br/>
							<label>Process *</label>
							<?php echo form_dropdown('process',$process,$this->input->post('process'));?><br/><br/>
							<label>Customer Name *</label>
							<?php echo form_dropdown('cusname',$bpname,$this->input->post('cusname'));?><br/><br/>
							<label>Reference No. 1 *</label>
							<?php echo form_dropdown('doctype1',$reftype1,$this->input->post('doctype1'));?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="ref" maxlength="20" maxvalue="20" onchange="main/myFunction(this.value)" value="<?php echo $this->input->post('ref')?>" required />
							<br/><br/>
							<label>Reference No. 2</label>
							<?php echo form_dropdown('doctype2',$reftype2,$this->input->post('doctype2'));?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php echo form_input($ref2);?><br/><br/>
							<label>Posting Date</label>
							<?php echo form_input($ddate);?><br/><br/>
						</div>
						<div class="col-md-4">
							<label>Creation Date</label>
							<?php echo form_input($rdate);?><br/><br/>
							<label>Transaction No.</label>
							<?php if(isset($trans_no)): foreach($trans_no as $tno): ?>
							<input type="text" name="trans_no" value="<?php echo $tno->sn_nextnum; ?>" readonly><br><br>
							<?php endforeach; ?>
							<?php endif; ?>
							<label>Remarks</label>
							<?php echo form_textarea($addr);?><br/><br/>
						</div>
						<div class="row">
							<table class="table table-bordered">
								<tr>
									<th>Item</th>
									<th>Status</th>
									<th>Delivery Type</th>
									<th>Location</th>
									<th style="display: none;">Location 2</th>
									<th>Description</th>
									<th>Available</th>
									<th>Reserved</th>
									<th>Total</th>
									<th>UOM</th>
									<th>Quantity</th>
								</tr>
								<tr>
									<td>1</td>
									<td><?php echo form_checkbox('chk1','accept1',false);?></td>
									<td><?php echo $dout;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('iloc1') <> ""): ?>
												<?php $do1 = $this->input->post('iloc1');?>
											<?php else: ?>
												<?php $do1 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('iloc1',$whlist, $do1, 'id="iloc_1"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('doloc1') <> ""): ?>
											<?php $dol1 = $this->input->post('doloc1');?>
										<?php else: ?>
											<?php $dol1 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('doloc1', $whlist_code, $dol1, 'id="doloc_1"'); ?></td>
									<td><?php echo form_dropdown('idesc1',$item,$this->input->post('idesc1'), 'id="idesc_1"');?></td>
									<td><input type="text" id="ado_1"></td>
									<td><input type="text" id="rdo_1"></td>
									<td><input type="text" id="tdo_1"></td>
									<td><?php echo form_dropdown('iunit1', $uom, $this->input->post('iunit1'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($iqty1);?></td>
								</tr>
								<tr>
									<td>2</td>
									<td><?php echo form_checkbox('chk2','accept2',false);?></td>
									<td><?php echo $dout;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('iloc2') <> ""): ?>
												<?php $do2 = $this->input->post('iloc2');?>
											<?php else: ?>
												<?php $do2 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('iloc2',$whlist, $do2, 'id="iloc_2"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('doloc2') <> ""): ?>
											<?php $dol2 = $this->input->post('doloc2');?>
										<?php else: ?>
											<?php $dol2 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('doloc2', $whlist_code, $dol2, 'id="doloc_2"'); ?></td>
									<td><?php echo form_dropdown('idesc2',$item,$this->input->post('idesc_2'), 'id="idesc_2"');?></td>
									<td><input type="text" id="ado_2"></td>
									<td><input type="text" id="rdo_2"></td>
									<td><input type="text" id="tdo_2"></td>
									<td><?php echo form_dropdown('iunit2', $uom, $this->input->post('iunit2'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($iqty2);?></td>
								</tr>
								<tr>
									<td>3</td>
									<td><?php echo form_checkbox('chk3','accept3',false);?></td>
									<td><?php echo $dout;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('iloc3') <> ""): ?>
												<?php $do3 = $this->input->post('iloc3');?>
											<?php else: ?>
												<?php $do3 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('iloc3',$whlist, $do3, 'id="iloc_3"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('doloc3') <> ""): ?>
											<?php $dol3 = $this->input->post('doloc3');?>
										<?php else: ?>
											<?php $dol3 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('doloc3', $whlist_code, $dol3, 'id="doloc_3"'); ?></td>
									<td><?php echo form_dropdown('idesc3',$item,$this->input->post('idesc3'), 'id="idesc_3"');?></td>
									<td><input type="text" id="ado_3"></td>
									<td><input type="text" id="rdo_3"></td>
									<td><input type="text" id="tdo_3"></td>
									<td><?php echo form_dropdown('iunit3', $uom, $this->input->post('iunit3'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($iqty3);?></td>
								</tr>
								<tr>
									<td>4</td>
									<td><?php echo form_checkbox('chk4','accept4',false);?></td>
									<td><?php echo $dout;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('iloc4') <> ""): ?>
												<?php $do4 = $this->input->post('iloc4');?>
											<?php else: ?>
												<?php $do4 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('iloc4',$whlist, $do4, 'id="iloc_4"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('doloc4') <> ""): ?>
											<?php $dol4 = $this->input->post('doloc4');?>
										<?php else: ?>
											<?php $dol4 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('doloc4', $whlist_code, $dol4, 'id="doloc_4"'); ?></td>
									<td><?php echo form_dropdown('idesc4',$item,$this->input->post('idesc4'), 'id="idesc_4"');?></td>
									<td><input type="text" id="ado_4"></td>
									<td><input type="text" id="rdo_4"></td>
									<td><input type="text" id="tdo_4"></td>
									<td><?php echo form_dropdown('iunit4', $uom, $this->input->post('iunit4'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($iqty4);?></td>
								</tr>
								<tr>
									<td>5</td>
									<td><?php echo form_checkbox('chk5','accept5',false);?></td>
									<td><?php echo $dout;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('iloc5') <> ""): ?>
												<?php $do5 = $this->input->post('iloc5');?>
											<?php else: ?>
												<?php $do5 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('iloc5',$whlist, $do5, 'id="iloc_5"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('doloc5') <> ""): ?>
											<?php $dol5 = $this->input->post('doloc5');?>
										<?php else: ?>
											<?php $dol5 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('doloc5', $whlist_code, $dol5, 'id="doloc_5"'); ?></td>
									<td><?php echo form_dropdown('idesc5',$item,$this->input->post('idesc5'), 'id="idesc_5"');?></td>
									<td><input type="text" id="ado_5"></td>
									<td><input type="text" id="rdo_5"></td>
									<td><input type="text" id="tdo_5"></td>
									<td><?php echo form_dropdown('iunit5', $uom, $this->input->post('iunit5'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($iqty5);?></td>
								</tr>
								<tr>
									<td>6</td>
									<td><?php echo form_checkbox('chk6','accept6',false);?></td>
									<td><?php echo $dout;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('iloc6') <> ""): ?>
												<?php $do6 = $this->input->post('iloc6');?>
											<?php else: ?>
												<?php $do6 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('iloc6',$whlist, $do6, 'id="iloc_6"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('doloc6') <> ""): ?>
											<?php $dol6 = $this->input->post('doloc6');?>
										<?php else: ?>
											<?php $dol6 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('doloc6', $whlist_code, $dol6, 'id="doloc_6"'); ?></td>
									<td><?php echo form_dropdown('idesc6',$item,$this->input->post('idesc6'), 'id="idesc_6"');?></td>
									<td><input type="text" id="ado_6"></td>
									<td><input type="text" id="rdo_6"></td>
									<td><input type="text" id="tdo_6"></td>
									<td><?php echo form_dropdown('iunit6', $uom, $this->input->post('iunit6'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($iqty6);?></td>
								</tr>
								<tr>
									<td>7</td>
									<td><?php echo form_checkbox('chk7','accept7',false);?></td>
									<td><?php echo $dout;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('iloc7') <> ""): ?>
												<?php $do7 = $this->input->post('iloc7');?>
											<?php else: ?>
												<?php $do7 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('iloc7',$whlist, $do7, 'id="iloc_7"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('doloc7') <> ""): ?>
											<?php $dol7 = $this->input->post('doloc7');?>
										<?php else: ?>
											<?php $dol7 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('doloc7', $whlist_code, $dol7, 'id="doloc_7"'); ?></td>
									<td><?php echo form_dropdown('idesc7',$item,$this->input->post('idesc7'), 'id="idesc_7"');?></td>
									<td><input type="text" id="ado_7"></td>
									<td><input type="text" id="rdo_7"></td>
									<td><input type="text" id="tdo_7"></td>
									<td><?php echo form_dropdown('iunit7', $uom, $this->input->post('iunit7'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($iqty7);?></td>
								</tr>
								<tr>
									<td>8</td>
									<td><?php echo form_checkbox('chk8','accept8',false);?></td>
									<td><?php echo $dout;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('iloc8') <> ""): ?>
												<?php $do8 = $this->input->post('iloc8');?>
											<?php else: ?>
												<?php $do8 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('iloc8',$whlist, $do8, 'id="iloc_8"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('doloc8') <> ""): ?>
											<?php $dol8 = $this->input->post('doloc8');?>
										<?php else: ?>
											<?php $dol8 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('doloc8', $whlist_code, $dol8, 'id="doloc_8"'); ?></td>
									<td><?php echo form_dropdown('idesc8',$item,$this->input->post('idesc8'), 'id="idesc_8"');?></td>
									<td><input type="text" id="ado_8"></td>
									<td><input type="text" id="rdo_8"></td>
									<td><input type="text" id="tdo_8"></td>
									<td><?php echo form_dropdown('iunit8', $uom, $this->input->post('iunit8'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($iqty8);?></td>
								</tr>
								<tr>
									<td>9</td>
									<td><?php echo form_checkbox('chk9','accept9',false);?></td>
									<td><?php echo $dout;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('iloc9') <> ""): ?>
												<?php $do9 = $this->input->post('iloc9');?>
											<?php else: ?>
												<?php $do9 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('iloc9',$whlist, $do9, 'id="iloc_9"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('doloc9') <> ""): ?>
											<?php $dol9 = $this->input->post('doloc9');?>
										<?php else: ?>
											<?php $dol9 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('doloc9', $whlist_code, $dol9, 'id="doloc_9"'); ?></td>
									<td><?php echo form_dropdown('idesc9',$item,$this->input->post('idesc9'), 'id="idesc_9"');?></td>
									<td><input type="text" id="ado_9"></td>
									<td><input type="text" id="rdo_9"></td>
									<td><input type="text" id="tdo_9"></td>
									<td><?php echo form_dropdown('iunit9', $uom, $this->input->post('iunit9'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($iqty9);?></td>
								</tr>
								<tr>
									<td>10</td>
									<td><?php echo form_checkbox('chk10','accept10',false);?></td>
									<td><?php echo $dout;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('iloc10') <> ""): ?>
												<?php $do10 = $this->input->post('iloc10');?>
											<?php else: ?>
												<?php $do10 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('iloc10',$whlist, $do10, 'id="iloc_10"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('doloc10') <> ""): ?>
											<?php $dol10 = $this->input->post('doloc10');?>
										<?php else: ?>
											<?php $dol10 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('doloc10', $whlist_code, $dol10, 'id="doloc_10"'); ?></td>
									<td><?php echo form_dropdown('idesc10',$item,$this->input->post('idesc10'), 'id="idesc_10"');?></td>
									<td><input type="text" id="ado_10"></td>
									<td><input type="text" id="rdo_10"></td>
									<td><input type="text" id="tdo_10"></td>
									<td><?php echo form_dropdown('iunit10', $uom, $this->input->post('iunit10'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($iqty10);?></td>
								</tr>
							</table>
						</div><br/><br/>
						<div class="row">
							<table class="table table-bordered">
								<tr>
									<th>Item</th>
									<th>Status</th>
									<th>Delivery Type</th>
									<th>Location</th>
									<th style="display: none;">Location 2</th>
									<th>Description</th>
									<th>Available</th>
									<th>Reserved</th>
									<th>Total</th>
									<th>Unit of Measurement</th>
									<th>Quantity</th>
								</tr>
								<tr>
									<td>1</td>
									<td><?php echo form_checkbox('ochk1','oaccept1',false);?></td>
									<td><?php echo $din;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('oiloc1') <> ""): ?>
												<?php $di1 = $this->input->post('oiloc1');?>
											<?php else: ?>
												<?php $di1 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('oiloc1',$whlist, $di1, 'id="oiloc_1"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('diloc1') <> ""): ?>
											<?php $dil1 = $this->input->post('diloc1');?>
										<?php else: ?>
											<?php $dil1 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('diloc1', $whlist_code, $dil1, 'id="diloc_1"'); ?></td>

									<td><?php echo form_dropdown('oidesc1',$item,$this->input->post('oidesc1'), 'id="oidesc_1"');?></td>
									<td><input type="text" id="adi_1"></td>
									<td><input type="text" id="rdi_1"></td>
									<td><input type="text" id="tdi_1"></td>
									<td><?php echo form_dropdown('oiunit1', $uom, $this->input->post('oiunit1'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($oiqty1);?></td>
								</tr>
								<tr>
									<td>2</td>
									<td><?php echo form_checkbox('ochk2','oaccept2',false);?></td>
									<td><?php echo $din;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('oiloc2') <> ""): ?>
												<?php $di2 = $this->input->post('oiloc2');?>
											<?php else: ?>
												<?php $di2 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('oiloc2',$whlist, $di2, 'id="oiloc_2"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('diloc2') <> ""): ?>
											<?php $dil2 = $this->input->post('diloc2');?>
										<?php else: ?>
											<?php $dil2 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('diloc2', $whlist_code, $dil2, 'id="diloc_2"'); ?></td>

									<td><?php echo form_dropdown('oidesc2',$item,$this->input->post('oidesc2'), 'id="oidesc_2"');?></td>
									<td><input type="text" id="adi_2"></td>
									<td><input type="text" id="rdi_2"></td>
									<td><input type="text" id="tdi_2"></td>
									<td><?php echo form_dropdown('oiunit2', $uom, $this->input->post('oiunit2'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($oiqty2);?></td>
								</tr>
								<tr>
									<td>3</td>
									<td><?php echo form_checkbox('ochk3','oaccept3',false);?></td>
									<td><?php echo $din;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('oiloc3') <> ""): ?>
												<?php $di3 = $this->input->post('oiloc3');?>
											<?php else: ?>
												<?php $di3 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('oiloc3',$whlist, $di3, 'id="oiloc_3"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('diloc3') <> ""): ?>
											<?php $dil3 = $this->input->post('diloc3');?>
										<?php else: ?>
											<?php $dil3 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('diloc3', $whlist_code, $dil3, 'id="diloc_3"'); ?></td>

									<td><?php echo form_dropdown('oidesc3',$item,$this->input->post('oidesc3'), 'id="oidesc_3"');?></td>
									<td><input type="text" id="adi_3"></td>
									<td><input type="text" id="rdi_3"></td>
									<td><input type="text" id="tdi_3"></td>
									<td><?php echo form_dropdown('oiunit3', $uom, $this->input->post('oiunit3'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($oiqty3);?></td>
								</tr>
								<tr>
									<td>4</td>
									<td><?php echo form_checkbox('ochk4','oaccept4',false);?></td>
									<td><?php echo $din;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('oiloc4') <> ""): ?>
												<?php $di4 = $this->input->post('oiloc4');?>
											<?php else: ?>
												<?php $di4 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('oiloc4',$whlist, $di4, 'id="oiloc_4"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('diloc4') <> ""): ?>
											<?php $dil4 = $this->input->post('diloc4');?>
										<?php else: ?>
											<?php $dil4 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('diloc4', $whlist_code, $dil4, 'id="diloc_4"'); ?></td>

									<td><?php echo form_dropdown('oidesc4',$item,$this->input->post('oidesc4'), 'id="oidesc_4"');?></td>
									<td><input type="text" id="adi_4"></td>
									<td><input type="text" id="rdi_4"></td>
									<td><input type="text" id="tdi_4"></td>
									<td><?php echo form_dropdown('oiunit4', $uom, $this->input->post('oiunit4'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($oiqty4);?></td>
								</tr>
								<tr>
									<td>5</td>
									<td><?php echo form_checkbox('ochk5','oaccept5',false);?></td>
									<td><?php echo $din;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('oiloc5') <> ""): ?>
												<?php $di5 = $this->input->post('oiloc5');?>
											<?php else: ?>
												<?php $di5 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('oiloc5',$whlist, $di5, 'id="oiloc_5"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('diloc5') <> ""): ?>
											<?php $dil5 = $this->input->post('diloc5');?>
										<?php else: ?>
											<?php $dil5 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('diloc5', $whlist_code, $dil5, 'id="diloc_5"'); ?></td>

									<td><?php echo form_dropdown('oidesc5',$item,$this->input->post('oidesc5'), 'id="oidesc_5"');?></td>
									<td><input type="text" id="adi_5"></td>
									<td><input type="text" id="rdi_5"></td>
									<td><input type="text" id="tdi_5"></td>
									<td><?php echo form_dropdown('oiunit5', $uom, $this->input->post('oiunit5'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($oiqty5);?></td>
								</tr>
								<tr>
									<td>6</td>
									<td><?php echo form_checkbox('ochk6','oaccept6',false);?></td>
									<td><?php echo $din;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('oiloc6') <> ""): ?>
												<?php $di6 = $this->input->post('oiloc6');?>
											<?php else: ?>
												<?php $di6 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('oiloc6',$whlist, $di6, 'id="oiloc_6"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('diloc6') <> ""): ?>
											<?php $dil6 = $this->input->post('diloc6');?>
										<?php else: ?>
											<?php $dil6 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('diloc6', $whlist_code, $dil6, 'id="diloc_6"'); ?></td>

									<td><?php echo form_dropdown('oidesc6',$item,$this->input->post('oidesc6'), 'id="oidesc_6"');?></td>
									<td><input type="text" id="adi_6"></td>
									<td><input type="text" id="rdi_6"></td>
									<td><input type="text" id="tdi_6"></td>
									<td><?php echo form_dropdown('oiunit6', $uom, $this->input->post('oiunit6'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($oiqty6);?></td>
								</tr>
								<tr>
									<td>7</td>
									<td><?php echo form_checkbox('ochk7','oaccept7',false);?></td>
									<td><?php echo $din;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('oiloc7') <> ""): ?>
												<?php $di7 = $this->input->post('oiloc7');?>
											<?php else: ?>
												<?php $di7 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('oiloc7',$whlist, $di7, 'id="oiloc_7"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('diloc7') <> ""): ?>
											<?php $dil7 = $this->input->post('diloc7');?>
										<?php else: ?>
											<?php $dil7 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('diloc7', $whlist_code, $dil7, 'id="diloc_7"'); ?></td>

									<td><?php echo form_dropdown('oidesc7',$item,$this->input->post('oidesc7'), 'id="oidesc_7"');?></td>
									<td><input type="text" id="adi_7"></td>
									<td><input type="text" id="rdi_7"></td>
									<td><input type="text" id="tdi_7"></td>
									<td><?php echo form_dropdown('oiunit7', $uom, $this->input->post('oiunit7'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($oiqty7);?></td>
								</tr>
								<tr>
									<td>8</td>
									<td><?php echo form_checkbox('ochk8','oaccept8',false);?></td>
									<td><?php echo $din;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('oiloc8') <> ""): ?>
												<?php $di8 = $this->input->post('oiloc8');?>
											<?php else: ?>
												<?php $di8 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('oiloc8',$whlist, $di8, 'id="oiloc_8"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('diloc8') <> ""): ?>
											<?php $dil8 = $this->input->post('diloc8');?>
										<?php else: ?>
											<?php $dil8 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('diloc8', $whlist_code, $dil8, 'id="diloc_8"'); ?></td>

									<td><?php echo form_dropdown('oidesc8',$item,$this->input->post('oidesc8'), 'id="oidesc_8"');?></td>
									<td><input type="text" id="adi_8"></td>
									<td><input type="text" id="rdi_8"></td>
									<td><input type="text" id="tdi_8"></td>
									<td><?php echo form_dropdown('oiunit8', $uom, $this->input->post('oiunit8'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($oiqty8);?></td>
								</tr>
								<tr>
									<td>9</td>
									<td><?php echo form_checkbox('ochk9','oaccept9',false);?></td>
									<td><?php echo $din;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('oiloc9') <> ""): ?>
												<?php $di9 = $this->input->post('oiloc9');?>
											<?php else: ?>
												<?php $di9 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('oiloc9',$whlist, $di9, 'id="oiloc_9"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('diloc9') <> ""): ?>
											<?php $dil9 = $this->input->post('diloc9');?>
										<?php else: ?>
											<?php $dil9 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('diloc9', $whlist_code, $dil9, 'id="diloc_9"'); ?></td>

									<td><?php echo form_dropdown('oidesc9',$item,$this->input->post('oidesc9'), 'id="oidesc_9"');?></td>
									<td><input type="text" id="adi_9"></td>
									<td><input type="text" id="rdi_9"></td>
									<td><input type="text" id="tdi_9"></td>
									<td><?php echo form_dropdown('oiunit9', $uom, $this->input->post('oiunit9'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($oiqty9);?></td>
								</tr>
								<tr>
									<td>10</td>
									<td><?php echo form_checkbox('chk100','oaccept10',false);?></td>
									<td><?php echo $din;?></td>
										<?php if(isset($warehouse)):foreach($warehouse as $r):?>

											<?php if($this->input->post('oiloc10') <> ""): ?>
												<?php $di10 = $this->input->post('oiloc10');?>
											<?php else: ?>
												<?php $di10 = $r->wh_name;?>
											<?php endif; ?>

									<td><?php echo form_dropdown('oiloc10',$whlist, $di10, 'id="oiloc_10"');?></td>
										<?php endforeach;?><?php endif;?>

										<?php if($this->input->post('diloc10') <> ""): ?>
											<?php $dil10 = $this->input->post('diloc10');?>
										<?php else: ?>
											<?php $dil10 = $this->uri->segment(3);?>
										<?php endif; ?>

									<td style="display: none;"><?php echo form_dropdown('diloc10', $whlist_code, $dil10, 'id="diloc_10"'); ?></td>

									<td><?php echo form_dropdown('oidesc10',$item,$this->input->post('oidesc10'), 'id="oidesc_10"');?></td>
									<td><input type="text" id="adi_10"></td>
									<td><input type="text" id="rdi_10"></td>
									<td><input type="text" id="tdi_10"></td>
									<td><?php echo form_dropdown('oiunit10', $uom, $this->input->post('oiunit10'), 'style="width: 80px;"'); ?></td>
									<td><?php echo form_input($oiqty10);?></td>
								</tr>
							</table>
						</div><br/><br/>
						<div style="margin: auto; width: 200px; padding: 10px;">
							<input type="submit" name="submit" value="Submit" class="btn btn-info"  style="width: 150px; border-radius: 0px; padding: 10px; font-weight: bold;"/>
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

<div id="dialog-error" title="Transaction No. Error">
	<p>
	  <div id="success_msg">
	  <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 10px 0;"></span>
	    	No Set Transaction Number for this Transaction Type.
	  </div>
	</p>
</div>

<div id="dialog-error-date" title="Transaction No. Error">
	<p>
	  <div id="success_msg">
	  <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 10px 0;"></span>
	    	Series for this transaction type reach its validity period
	  </div>
	</p>
</div>

<script type="text/javascript">

		<?php if(isset($outval) OR validation_errors() OR isset($idescerror) OR isset($idescerror2)): ?>
			$('#error_mm').show();
		<?php else: ?>
			$('#error_mm').hide();
		<?php endif; ?>

</script>