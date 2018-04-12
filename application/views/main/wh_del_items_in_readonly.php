<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header'); ?>

<?php 
$show = 0;
if($records){ 
	foreach($records as $r){

		if ($r->wi_type == 0){
			$type="Delivery In";
			$src=$r->CardName;
			$dest=$r->wh_name;
		}else{
			$type="Delivery Out";
			$src=$r->wh_name;
			$dest=$r->CardName;
		}

		$rdate = array('name'=>'rdate','value'=>$r->wi_createdatetime,'readonly'=>'true');
		$deltype = array('name'=>'deltype','maxlength'=>20,'maxvalue'=>20,'readonly'=>'true','value'=>$type,'readonly'=>'true'); 
		$ref = array('name'=>'ref','maxlength'=>20,'maxvalue'=>20,'onchange'=>"main/myFunction(this.value)",'value'=>$r->wi_reftype.$r->wi_refnum,'readonly'=>'true'); 
		$ref2 = array('name'=>'ref2','maxlength'=>20,'maxvalue'=>20,'value'=>$r->wi_reftype2.$r->wi_refnum2,'readonly'=>'true');
		$ref3 = array('name'=>'ref3','maxlength'=>20,'maxvalue'=>20,'value'=>$r->wi_reftype3.$r->wi_refnum3,'readonly'=>'true');
		$ref4 = array('name'=>'ref4','maxlength'=>20,'maxvalue'=>20,'value'=>$r->wi_reftype4.$r->wi_refnum4,'readonly'=>'true');
		$qty = array('name'=>'whqty','maxlength'=>20,'maxvalue'=>20,'value'=>$r->wi_itemqty,'readonly'=>'true');
		$exdate = array('name'=>'exdate','value'=>$r->wi_expecteddeliverydate,'id'=>'datepicker','readonly'=>'true');
		$dodate = array('name'=>'dodate','value'=>$r->do_date,'id'=>'datepicker','readonly'=>'true');
		$ddate = array('name'=>'ddate','value'=>$r->deldate,'id'=>'datepicker','readonly'=>'true');
		$addr=array('name'=>'remarks','value'=>$r->wi_remarks,'readonly'=>'true', 'cols'=>'50', 'rows'=>'6');
		$uom=array('name'=>'uom','maxlength'=>50,'maxvalue'=>50,'value'=>$r->item_uom,'readonly'=>'true');
		$loi=array('name'=>'loi','maxlength'=>50,'maxvalue'=>50,'value'=>$r->wi_LOINum,'readonly'=>'true');
		$tcmpny=array('name'=>'tcom','maxlength'=>'50','maxvalue'=>'50','value'=>$r->truck_company,'readonly'=>'true','size'=>50);
		$tdrvr=array('name'=>'tdrvr','maxlength'=>'50','maxvalue'=>'50','value'=>$r->truck_driver,'readonly'=>'true','size'=>50);
		$tpnum=array('name'=>'tpnum','maxlength'=>'20','maxvalue'=>'20','value'=>$r->truck_platenum,'readonly'=>'true');
		$item=array('name'=>'itemname','value'=>$r->comm__name,'readonly'=>true,'size'=>50);
		$source=array('name'=>'source','value'=>$src,'readonly'=>true,'size'=>50);
		$destination=array('name'=>'desti','value'=>$dest,'readonly'=>true,'size'=>50);
		$PONum=array('name'=>'PONum','maxlength'=>50,'maxvalue'=>50,'value'=>$r->wi_PONum,'readonly'=>true);
		//$DONum=array('name'=>'DONum','maxlength'=>50,'maxvalue'=>50,'value'=>$r->wi_doqty,'readonly'=>true);
		$rtn=array('name'=>'trn','maxlength'=>50,'maxvalue'=>50,'value'=>$r->rr_category,'readonly'=>true);
		$itoqty=array('name'=>'itoqty','maxlength'=>10,'maxvalue'=>10,'value'=>$r->wi_doqty,'readonly'=>true);
		$intransit=array('name'=>'intransit','maxlength'=>10,'maxvalue'=>10,'value'=>$r->wi_intransit,'readonly'=>true);
		$location=array('name'=>'location','maxlength'=>50,'maxvalue'=>50,'value'=>$r->wi_location,'readonly'=>true);

		$trucktime = array('name'=>'trucktime', 'value'=>$r->truck_arrival_time, 'readonly'=>true);
		$shipdate = array('name'=>'shipdate', 'value'=>$r->wi_expecteddeliverydate, 'readonly'=>true);
		$shiptime = array('name'=>'shiptime', 'value'=>$r->ship_time, 'readonly'=>true);

		$deltype_header = $type;

		$note1 = $r->note1;
		$note2 = $r->note2;
		$seal = $r->seal;
		$ref_print = $r->ref_print;

		$trans_no = array('name'=>'trans_no', 'value'=>$r->wi_transno, 'readonly'=>true);

		$doremarks=array('name'=>'doremarks', 'value'=>$r->do_remarks,'readonly'=>'true', 'cols'=>'50', 'rows'=>'6');

		$doseriesno = array('name'=>'doseriesno', 'value'=>$r->do_series_no, 'readonly'=>true);

		$pcode = array('name'=>'pcode', 'value'=>$r->pbatch_code, 'readonly'=>true);

		$catno = array('name'=>'catno', 'value'=>$r->catalog_no, 'readonly'=>true);

		$pby = array('name'=>'pby', 'value'=>$r->prepared_by, 'readonly'=>true, 'class'=>'form-control');

		$cby = array('name'=>'cby', 'value'=>$r->checked_by, 'readonly'=>true, 'class'=>'form-control');

		$gby = array('name'=>'gby', 'value'=>$r->guard_duty, 'readonly'=>true, 'class'=>'form-control');

	}

	$show = 1;
}
?>

<style type="text/css">

	#pbody label{
		width: 200px;
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

<script type="text/javascript">
		
	$(document).ready(function(){



	})

</script>

<?php if($show == 1){ ?>
	<?php echo form_open();?>
		
		<h5>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong><?php echo $deltype_header; ?></strong></div>
							<div class="panel-body form-inline" id="pbody">
								<div class="col-md-6">
									<label>Source</label>
									<?php echo form_input($source);?><br/><br/>
									<label>Type</label>
									<?php echo form_input($deltype);?><br/><br/>
									<label>Destination</label>
									<?php echo form_input($destination);?><br/><br/>

									<?php if($records): foreach($records as $r): ?>

										<?php if($r->wi_type == 0): ?>
											<label>Return Category</label>
											<?php echo form_input($rtn);?><br/><br/>
										<?php endif; ?>

									<?php endforeach; ?>
									<?php endif; ?>

									<label>Reference No. 1</label>
									<?php echo form_input($ref);?><br/><br/>
									<label>Reference No. 2</label>
									<?php echo form_input($ref2);?><br/><br/>
									<label>Reference No. 3</label>
									<?php echo form_input($ref3);?><br/><br/>
									<label>Reference No. 4</label>
									<?php echo form_input($ref4);?><br/><br/>

									<?php if($records): foreach($records as $r): ?>

										<?php if($r->wi_type == 0): ?>
											<label>LOI Number</label>
											<?php echo form_input($rtn);?><br/><br/>
										<?php endif; ?>

									<?php endforeach; ?>
									<?php endif; ?>

									<label>PO Number</label>
									<?php echo form_input($PONum);?><br/><br/>

									<label>Item Description</label>
									<?php echo form_input($item);?><br/><br/>
									<label>Item Unit of Measurement</label>
									<?php echo form_input($uom);?><br/><br/>
									<label>Actual Quantity Loaded</label>
									<?php echo form_input($qty);?><br/><br/>
									<label>DO / ITO Quantity</label>
									<?php echo form_input($itoqty);?><br/><br/>
									<label>In-Transit</label>
									<?php echo form_input($intransit);?><br/><br/>

									<?php if($records): foreach($records as $r): ?>

										<?php if($r->wi_type == 2): ?>
											<label>Expected Delivery Date</label>
											<?php echo form_input($exdate);?><br/><br/>

											<label>DO Date</label>
											<?php echo form_input($dodate);?><br/><br/>
										<?php endif; ?>
									
									<?php endforeach; ?>
									<?php endif; ?>

									<label>Posting | Loading Date</label>
									<?php echo form_input($ddate);?><br/><br/>
									<label>Warehouse Remarks</label>
									<?php echo form_textarea($addr);?><br/><br/>

									<?php if($records): foreach($records as $r): ?>

										<?php if($r->wi_type == 2): ?>
											<label>DO Remarks</label>
											<?php echo form_textarea($doremarks);?><br/><br/>
										<?php endif; ?>
									
									<?php endforeach; ?>
									<?php endif; ?>

								</div>
								<div class="col-md-6">
									<label>Transaction No</label>
									<?php echo form_input($trans_no); ?><br><br>

									<?php if($records): foreach($records as $r): ?>

										<?php if($r->wi_type == 2): ?>
											<label>DO Series No</label>
											<?php echo form_input($doseriesno);?><br/><br/>
										<?php endif; ?>
									
									<?php endforeach; ?>
									<?php endif; ?>

									<label>Creation Date</label>
									<?php echo form_input($rdate);?><br/><br/>

									<label>Production Code</label>
									<?php echo form_input($pcode);?><br/><br/>

									<label>Item Catalog No</label>
									<?php echo form_input($catno);?><br/><br/>

									<label>Pick-up | Delivery Location</label>
									<?php echo form_input($location);?><br/><br/>
									<label>Trucker's Arrival Time</label>
									<?php echo form_input($trucktime);?><br/><br/>
									<label>Truck Company</label>
									<?php echo form_input($tcmpny);?><br/><br/>
									<label>Truck Plate Number</label>
									<?php echo form_input($tpnum);?><br/><br/>
									<label>Truck Driver</label>
									<?php echo form_input($tdrvr);?><br/><br/>
									<label>Shipment Pick-up Date</label>
									<?php echo form_input($shipdate);?><br/><br/>
									<label>Shipment Pick-up Time</label>
									<?php echo form_input($shiptime);?><br/><br/>

									<div class="well">
										<label><b><em>For Printing DR / WIS</em></b></label><br>

										<label>Prepared By:</label>
										<?php echo form_input($pby); ?>
										<br><br>
										<label>Checked By:</label>
										<?php echo form_input($cby); ?>
										<br><br>
										<label>Guard Duty/Released By:</label>
										<?php echo form_input($gby); ?>
										<br><br>
										<label>Seal No</label>
										<input type="text" name="seal" value="<?php echo $seal ?>" class="form-control" readonly/>
										<br><br>
										<label>Way Bill No.</label>
										<input type="text" name="ref_print" value="<?php echo $ref_print ?>" class="form-control" readonly/>
									</div>
									
									<button class="btn btn-info" onclick="history.back();">Back</button>
								</div>
							</div>
						</div>
					<div>
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
<?php } else { echo 'failed to retrieve data.'.$this->uri->segment(3); }?>
