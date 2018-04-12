<?php
	
	if($trn_data){
		foreach($trn_data as $row){
			
			//a.trk_arriveddate, a.trk_arrivedstatus, a.trk_accepteddate, a.trk_acceptedqty, a.trk_acceptedremarks, a.trk_canceledstatus, a.trk_canceledremarks, a.trk_canceledqty
			$bp = $row->CardName;
			$po = $row->wi_PONum;
			$do = '';
			if($row->wi_reftype == 'DO'){
				$do = $row->wi_refnum;
			}
			if($row->wi_reftype2 == 'DO'){
				$do = $row->wi_refnum2;
			}
			$dr = $row->wi_drnum;
			if($row->wi_reftype == 'DR'){
				$dr = $row->wi_refnum;
			}
			if($row->wi_reftype2 == 'DR'){
				$dr = $row->wi_refnum2;
			}
			$itemcode = $row->item_id;
			$dscription = $row->comm__name;
			$qty = $row->wi_itemqty;//$row->wi_doqty;
			$accepted_qty = $row->trk_acceptedqty;
			$variance = $qty - $accepted_qty;
			$location = $row->wi_location;
			$shipment = $row->wi_exactdeliverydate;
			$arrival = $row->trk_arriveddate.' '.$row->trk_arrivedtime;
			$unloading = $row->trk_accepteddate.' '.$row->trk_acceptedtime; 
			$del_status = '';
			if($row->trk_acceptedstatus == '1'){
				$del_status = 'Accepted';
			}
			$remarks = $row->trk_acceptedremarks;
			$uom = $row->item_uom;
		}
	}

	// dates are test purposes only
	
	$email_content = "
		Dear Ms. / Mr. Contact Person Name  <br /> <br />
		Please take this time to review your order details and confirm your company's acknowledgement of the delivery of <br />
		the goods and any supplemental documents  as follows: <br /> <br />
		Customer : $bp <br />
		Purchase Order # :  $po <br />
		Delivery Order # : $do <br />
		Delivery Receipt # : $dr <br />
		Item Description :  $itemcode  $dscription <br />
		Delivered Qty / Unit:  $qty $uom <br />
		Accepted Qty / Unit: $accepted_qty $uom <br />
		Variance Qty / Unit: $variance <br />
		Delivery Destination: $location <br />
		Shipment Date: $shipment <br />
		Arrival Date and Time: $arrival <br />
		Unloading Finish Time: 4:40 PM <br />
		Delivery Status: $del_status <br />
		Remarks: $remarks <br />
		Thank you for choosing All Asian Countertrade, Inc. We will be delighted to receive your confirmation or hear from you within 24 hours through this link. <br /> <br />
		Sincerely, <br />
		<Name of Account Executive or Sales Manager or Special Point of Contact> <br />
		Mobile No.: <Contact of AE, SM or SPOC) <br />
		Contact No.:  (02) 651-5700 loc <454>
	";
	
	echo $email_content;
?>