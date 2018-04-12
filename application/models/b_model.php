<?php 
class b_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	// OLD EMAIL CONFIGURATION ========================
	// function email_config(){
	// 	$config = array(
	// 		'protocol' => 'smtp',
	// 		'smtp_host' => '192.168.0.66',
	// 		'smtp_port' => 25,
	// 		'smtp_user' => 'wbis@mkt.aaci.ph',
	// 		'smtp_pass' => 'thisismadness0403',
	// 		'charset' => 'utf-8',
	// 		'mailtype' => 'html',
	// 		'newline' => "\r\n"
	// 	);
	// 	$this->load->library('email');
	// 	$this->email->initialize($config);
	// }
	// =================================================

	function send_noti(){
		require 'PHPMailer/PHPMailerAutoload.php';

		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'webinvty@aaci.ph';                 // SMTP username
		$mail->Password = 'l3tm31n.p0';                           // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;                                    // TCP port to connect to

		$mail->setFrom('webinvty@aaci.ph', 'AACI WEB Inventory System');
		$mail->addAddress('jayson.suyat@aaci.ph', 'Jayson Suyat');     // Add a recipient
		// $mail->addAddress('ellen@example.com');               // Name is optional
		// $mail->addReplyTo('info@example.com', 'Information');
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');

		// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$q1 = $this->db->query("SELECT CardName,
			(SELECT comm__name 
				FROM ocmt 
				WHERE status=1 
				AND comm__id='".$this->input->post('whitem')."')itemname
			FROM ocrd
			WHERE CardCode='".$this->input->post('bpname')."'
		");
		foreach($q1->result() as $bp){
			$q = $this->db->query("
				SELECT a.wh_code,a.emailadd,b.wh_name
				FROM oedr a
				INNER JOIN (
					SELECT wh_code,wh_name,wh_status 
					FROM mwhr WHERE wh_status=1)b 
				ON a.wh_code=b.wh_code
				WHERE b.wh_name='".$this->input->post('wh')."' 
					AND a.type='Creation' 
					AND a.status='1'
			");
			if($q->num_rows == true){
				$email_content="";
				foreach ($q->result() as $r){

						$dtype = $this->input->post('deltype');
						$src = $bp->CardName;
						$desti = $this->input->post('wh');
						$sdo = "";
						$ddo = "";

						if(isset($dtype)){
							if(isset($desti)){
								if($dtype=='Delivery In'){
									$dtype='Return to ';
								}else{
									$dtype=$dtype." at ";
									$sdo = $desti;
									$ddo = $src;
									$src = $sdo;
									$desti = $ddo;
								}
							}
						}

						// DATE AND TIME VALUE =========================
						date_default_timezone_set("Asia/Manila");
						$now = time('UP8');
						$datetime =  unix_to_human($now, TRUE);
						//===============================================

						$email_content = "<h1>".$dtype."<b>".$this->input->post('wh')."</b></h1>
							<hr>
							<table>
								<tr>
									<td>Source</td><td>".$src."</td>
									<td>Date and Time</td><td>".$datetime."</td>
								</tr>
								<tr>
									<td>Destination</td><td>".$desti."</td>
								</tr>
								<tr>
									<td>Ref No. 1</td><td>".$this->input->post('doctype1').$this->input->post('ref')."</td>
								</tr>
								<tr>
									<td>Ref No. 2</td><td>".$this->input->post('doctype2').$this->input->post('ref2')."</td>
								</tr>
								<tr>
									<td>Item Description</td><td>".$bp->itemname."</td>
								</tr>
								<tr>
									<td>Item UOM</td><td>".$this->input->post('uom')."</td>
								</tr>
								<tr>
									<td>Item Qty</td><td>".$this->input->post('whqty')."</td>
								</tr>
								<tr>
									<td>Remarks</td><td>".$this->input->post('remarks')."</td>
								</tr>
							</table>
							<hr>
							Login to <a href='".base_url()."'>http://ict.aaci.ph/inventory/</a> for more delivery information. 
						";

						$mail->Subject = 'AACI Web Inventory System: Delivery In';
						// $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
						$mail->Body    = $email_content;
						// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

						$mail->send();

						// if(!$mail->send()) {
						//     echo 'Message could not be sent.';
						//     echo 'Mailer Error: ' . $mail->ErrorInfo;
						// } else {
						//     echo 'Message has been sent';
						// }

				}
			}
		}
	}	

	function email_config(){

		$config = array(
			'protocol' => "smtp",
			'smtp_host' => "ssl://smtp.gmail.com",
			'smtp_port' => 465,
			'smtp_user' => "webinvty@aaci.ph",
			'smtp_pass' => "l3tm31n.p0",
			'charset' => "utf-8",
			'mailtype' => "html",
			'newline' => "\r\n"
		);

		$this->load->library('email');
		$this->email->initialize($config);
	}

	// function send_delivery_in(){

	// 	// TRANSACTION ID IN WHIR TABLE ==========
	// 	$tokens = explode ('_',current_url());
	// 	$id = $tokens[sizeof($tokens)-1];
	// 	 // ======================================

	// 	$q2 = $this->db->query("SELECT deldate, rr_category, wi_remarks, wi_deltype, wh_name, wi_reftype, wi_refnum, wi_reftype2, wi_refnum2, item_uom, wi_itemqty, item_id, wi_refname FROM whir WHERE wi_id = '$id' ");

	// 	foreach ($q2->result() as $wrec){

	// 		$q1 = $this->db->query("SELECT CardName,
	// 			(SELECT comm__name 
	// 				FROM ocmt 
	// 				WHERE status=1 
	// 				AND comm__id='".$wrec->item_id."')itemname
	// 			FROM ocrd
	// 			WHERE CardCode='".$wrec->wi_refname."'
	// 		");

	// 		foreach($q1->result() as $bp){
	// 			$q = $this->db->query("
	// 				SELECT a.wh_code,a.emailadd,b.wh_name
	// 				FROM oedr a
	// 				INNER JOIN (
	// 					SELECT wh_code,wh_name,wh_status 
	// 					FROM mwhr WHERE wh_status=1)b 
	// 				ON a.wh_code=b.wh_code
	// 				WHERE b.wh_name='".$wrec->wh_name."' 
	// 					AND a.type='Creation' 
	// 					AND a.status='1'
	// 			");
	// 			if($q->num_rows == true){
	// 				$email_content="";
	// 				foreach ($q->result() as $r){
	// 					$this->email_config();
	// 					// $this->email->from('wbis@mkt.aaci.ph', 'AACI WEB Inventory System');
	// 					$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
	// 					$this->email->to($r->emailadd);
	// 					if($wrec->wh_name <> 'Test Warehouse'){
	// 						$this->email->cc('adrian.pidot@aaci.ph');
	// 					}
						
	// 					// if($this->input->post('deltype') == 'Material Management'){
	// 						// $email_content = "<h1>".$this->input->post('deltype')." Warehouse: <b>".$this->input->post('wh')."</b></h1>";
	// 					// }
	// 					// else{
	// 						$dtype = $wrec->wi_deltype;
	// 						$src = $bp->CardName;
	// 						$desti = $wrec->wh_name;
	// 						$sdo = "";
	// 						$ddo = "";

	// 						if(isset($dtype)){
	// 							if(isset($desti)){
	// 								if($dtype=='Delivery In'){
	// 									// $dtype='Return to ';
	// 									$dtype='Return from '.$src.' '.$wrec->wi_itemqty.' '.$wrec->item_uom;
	// 								}else{
	// 									//$dtype=$dtype." at ";
	// 									$sdo = $desti;
	// 									$ddo = $src;
	// 									$src = $sdo;
	// 									$desti = $ddo;
	// 									$dtype='Delivery to '.$desti.' '.$wrec->wi_itemqty.' '.$wrec->item_uom;
	// 								}
	// 							}
	// 						}
							
	// 						$this->email->subject($dtype);

	// 						// $email_content = "<h1>".$dtype."<b>".$this->input->post('wh')."</b></h1>
	// 						$email_content = "<h1>".$dtype."</h1>
	// 							<hr>
	// 							<table>
	// 								<tr>
	// 									<td><b>Posting Date</b></td>
	// 									<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
	// 									<td>".$wrec->deldate."</td>
	// 								</tr>
	// 								<tr>
	// 									<td><b>Source</b></td>
	// 									<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
	// 									<td>".$src."</td>
	// 								</tr>
	// 								<tr>
	// 									<td><b>Destination</b></td>
	// 									<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
	// 									<td>".$desti."</td>
	// 								</tr>
	// 								<tr>
	// 									<td><b>Ref No. 1</b></td>
	// 									<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
	// 									<td>".$wrec->wi_reftype.$wrec->wi_refnum."</td>
	// 								</tr>
	// 								<tr>
	// 									<td><b>Ref No. 2</b></td>
	// 									<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
	// 									<td>".$wrec->wi_reftype2.$wrec->wi_refnum2."</td>
	// 								</tr>
	// 								<tr>
	// 									<td><b>Item Description</b></td>
	// 									<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
	// 									<td>".$bp->itemname."</td>
	// 								</tr>
	// 								<tr>
	// 									<td><b>Item UOM</b></td>
	// 									<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
	// 									<td>".$wrec->item_uom."</td>
	// 								</tr>
	// 								<tr>
	// 									<td><b>Item Qty</b></td>
	// 									<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
	// 									<td>".$wrec->wi_itemqty."</td>
	// 								</tr>
	// 								<tr>
	// 									<td><b>Remarks</b></td>
	// 									<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
	// 									<td>".$wrec->wi_remarks."</td>
	// 								</tr>
	// 							</table>
	// 							<hr>
	// 							Login to <a href='".base_url()."'>http://ict.aaci.ph/inventory/</a> for more delivery information. 
	// 						";

	// 					//}
	// 					$this->email->message($email_content);

	// 					if($wrec->wi_deltype=='Delivery In'){
	// 						if($wrec->wi_refname[0]=='C'){
	// 							if($wrec->rr_category <> NULL 
	// 								OR $wrec->wi_reftype=='RR' 
	// 								OR $wrec->wi_reftype=='NOR' 
	// 								OR $wrec->wi_reftype=='DR' 
	// 								OR $wrec->wi_reftype=='DO' 
	// 								OR $wrec->wi_reftype2=='RR' 
	// 								OR $wrec->wi_reftype2=='NOR' 
	// 								OR $wrec->wi_reftype2=='DR' 
	// 								OR $wrec->wi_reftype2=='DO'){

	// 								// $this->email->send();

	// 								// if(!$this->email->send()){
	// 								// return show_error($this->email->print_debugger());
	// 								// }
	// 								// return 'Registration has been successfull!';

	// 								if($this->email->send()){
	// 									$d = array('email_send'=>1);
	// 									$this->db->where('wi_id', $id);
	// 									$this->db->update('whir', $d);
	// 								}

	// 							}
	// 						}
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
	// }


	function send_delivery_in(){

			$sub_del_type_in = $this->input->post('sub_type_del_in');

			if($sub_del_type_in == 'DI_01'){
				$bp_name = $this->input->post('sub_in_warehouse');
			}elseif($sub_del_type_in == 'DI_02'){
				$bp_name = $this->input->post('sub_in_warehouse');
			}elseif($sub_del_type_in == 'DI_05'){
				$bp_name = $this->input->post('sub_in_warehouse');
			}elseif($sub_del_type_in == 'DI_03'){
				$bp_name = $this->input->post('sub_in_supplier');
			}elseif($sub_del_type_in == 'DI_04'){
				$bp_name = $this->input->post('sub_in_customer');
			}

			$q1 = $this->db->query("SELECT CardName,
				(SELECT comm__name 
					FROM ocmt 
					WHERE status=1 
					AND comm__id='".$this->input->post('whitem')."')itemname
				FROM ocrd
				WHERE CardCode='".$bp_name."'
			");

			foreach($q1->result() as $bp){
				$q = $this->db->query("
					SELECT a.wh_code,a.emailadd,b.wh_name
					FROM oedr a
					INNER JOIN (
						SELECT wh_code,wh_name,wh_status 
						FROM mwhr WHERE wh_status=1)b 
					ON a.wh_code=b.wh_code
					WHERE b.wh_name='".$this->input->post('wh')."' 
						AND a.type='Creation' 
						AND a.status='1'
				");
				if($q->num_rows == true){
					$email_content="";
					foreach ($q->result() as $r){
						$this->email_config();
						// $this->email->from('wbis@mkt.aaci.ph', 'AACI WEB Inventory System');
						$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
						$this->email->to($r->emailadd);
						if($this->input->post('wh') <> 'Test Warehouse'){
							$this->email->cc('adrian.pidot@aaci.ph');
						}
						
						// if($this->input->post('deltype') == 'Material Management'){
							// $email_content = "<h1>".$this->input->post('deltype')." Warehouse: <b>".$this->input->post('wh')."</b></h1>";
						// }
						// else{
							$dtype = $this->input->post('deltype');
							$src = $bp->CardName;
							$desti = $this->input->post('wh');
							$sdo = "";
							$ddo = "";

							if(isset($dtype)){
								if(isset($desti)){
									if($dtype=='Delivery In'){
										// $dtype='Return to ';
										$dtype='Return from '.$src.' '.$this->input->post('whqty').' '.$this->input->post('uom');
									}else{
										//$dtype=$dtype." at ";
										$sdo = $desti;
										$ddo = $src;
										$src = $sdo;
										$desti = $ddo;
										$dtype='Delivery to '.$desti.' '.$this->input->post('whqty').' '.$this->input->post('uom');
									}
								}
							}
							
							$this->email->subject($dtype);

							// $email_content = "<h1>".$dtype."<b>".$this->input->post('wh')."</b></h1>
							$email_content = "<h1>".$dtype."</h1>
								<hr>
								<table>
									<tr>
										<td><b>Posting Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$this->input->post('ddate')."</td>
									</tr>
									<tr>
										<td><b>Source</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$src."</td>
									</tr>
									<tr>
										<td><b>Destination</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$desti."</td>
									</tr>
									<tr>
										<td><b>Ref No. 1</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$this->input->post('doctype1').$this->input->post('ref')."</td>
									</tr>
									<tr>
										<td><b>Ref No. 2</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$this->input->post('doctype2').$this->input->post('ref2')."</td>
									</tr>
									<tr>
										<td><b>Item Description</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$bp->itemname."</td>
									</tr>
									<tr>
										<td><b>Item UOM</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$this->input->post('uom')."</td>
									</tr>
									<tr>
										<td><b>Item Qty</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$this->input->post('whqty')."</td>
									</tr>
									<tr>
										<td><b>Remarks</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$this->input->post('remarks')."</td>
									</tr>
								</table>
								<hr>
								Login to <a href='".base_url()."'>http://ict.aaci.ph/inventory/</a> for more delivery information. 
							";

						//}
						$this->email->message($email_content);

						$source_name = $this->input->post('bpname');

						if($this->input->post('deltype')=='Delivery In'){
							if($source_name[0]=='C'){
								if($this->input->post('rtn') <> NULL 
									OR $this->input->post('doctype1')=='RR' 
									OR $this->input->post('doctype1')=='NOR' 
									OR $this->input->post('doctype1')=='DR' 
									OR $this->input->post('doctype1')=='DO' 
									OR $this->input->post('doctype2')=='RR' 
									OR $this->input->post('doctype2')=='NOR' 
									OR $this->input->post('doctype2')=='DR' 
									OR $this->input->post('doctype2')=='DO'){

									// $this->email->send();

									// if(!$this->email->send()){
									// return show_error($this->email->print_debugger());
									// }
									// return 'Registration has been successfull!';

									if($this->email->send()){
										$d = array('email_send'=>1);
										$this->db->where('wi_reftype', $this->input->post('doctype1'));
										$this->db->where('wi_reftype2', $this->input->post('doctype2'));
										$this->db->where('wi_refnum', $this->input->post('ref'));
										$this->db->where('wi_refnum2', $this->input->post('ref2'));
										$this->db->update('whir', $d);
									}

								}
							}
						}
					}
				}
			}
		}


	function send_delivery_out(){

		// TRANSACTION ID IN WHIR TABLE ==========
		// $tokens = explode ('_',current_url());
		// $id = $tokens[sizeof($tokens)-1];
		//  // ======================================

		// $q2 = $this->db->query("SELECT ship_date, wi_expecteddeliverydate, wi_location, deldate, rr_category, wi_remarks, wi_deltype, wh_name, wi_reftype, wi_refnum, wi_reftype2, wi_refnum2, item_uom, wi_itemqty, item_id, wi_refname FROM whir WHERE wi_id = '$id' ");

		$q2 = $this->db->query("SELECT wi_id, ship_date, wi_expecteddeliverydate, wi_location, deldate, rr_category, wi_remarks, wi_deltype, wh_name, wi_reftype, wi_refnum, wi_reftype2, wi_refnum2, item_uom, wi_itemqty, item_id, wi_refname FROM whir 
			WHERE wi_reftype = '".$this->input->post('doctype1')."'
				AND wi_reftype2 = '".$this->input->post('doctype2')."'
				AND wi_refnum = '".$this->input->post('ref')."'
				AND wi_refnum2 = '".$this->input->post('ref2')."' ");

		foreach ($q2->result() as $wrec){

			$q1 = $this->db->query("SELECT CardName,
				(SELECT comm__name 
					FROM ocmt 
					WHERE status=1 
					AND comm__id='".$wrec->item_id."')itemname
				FROM ocrd
				WHERE CardCode='".$wrec->wi_refname."'
			");

			foreach($q1->result() as $bp){
				$q = $this->db->query("
					SELECT a.wh_code,a.emailadd,b.wh_name
					FROM oedr a
					INNER JOIN (
						SELECT wh_code,wh_name,wh_status 
						FROM mwhr WHERE wh_status=1)b 
					ON a.wh_code=b.wh_code
					WHERE b.wh_name='".$wrec->wh_name."' 
						AND a.type='Creation' 
						AND a.status='1'
				");
				if($q->num_rows == true){
					$email_content="";
					foreach ($q->result() as $r){
						
						$this->email_config();
						// $this->email->from('wbis@mkt.aaci.ph', 'AACI WEB Inventory System');
						$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
						$this->email->to($r->emailadd);
						
						// if($this->input->post('deltype') == 'Material Management'){
							// $email_content = "<h1>".$this->input->post('deltype')." Warehouse: <b>".$this->input->post('wh')."</b></h1>";
						// }
						// else{
							$dtype = $wrec->wi_deltype;
							$src = $bp->CardName;
							$desti = $wrec->wh_name;
							$sdo = "";
							$ddo = "";

							if(isset($dtype)){
								if(isset($desti)){
									if($dtype=='Delivery In'){
										// $dtype='Return to ';
										$dtype='Return from '.$src.' '.$wrec->wi_itemqty.' '.$wrec->item_uom;
									}else{
										//$dtype=$dtype." at ";
										$sdo = $desti;
										$ddo = $src;
										$src = $sdo;
										$desti = $ddo;
										$dtype='Delivery to '.$desti.' '.$wrec->wi_itemqty.' '.$wrec->item_uom;
									}
								}
							}
							
							$this->email->subject($dtype);

							// $email_content = "<h1>".$dtype."<b>".$this->input->post('wh')."</b></h1>
							$email_content = "<h1>".$dtype."</h1>
								<hr>
								<table>
									<tr>
										<td><b>Posting | Loading Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->deldate."</td>
									</tr>
									<tr>
										<td><b>Shipment Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->ship_date."</td>
									</tr>
									<tr>
										<td><b>Expected Delivery Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_expecteddeliverydate."</td>
									</tr>
									<tr>
										<td><b>Source</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$src."</td>
									</tr>
									<tr>
										<td><b>Destination</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$desti."</td>
									</tr>
									<tr>
										<td><b>Location</b></b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_location."</td>
									</tr>
									<tr>
										<td><b>Ref No. 1</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_reftype.$wrec->wi_refnum."</td>
									</tr>
									<tr>
										<td><b>Ref No. 2</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_reftype2.$wrec->wi_refnum2."</td>
									</tr>
									<tr>
										<td><b>Item Description</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$bp->itemname."</td>
									</tr>
									<tr>
										<td><b>Item UOM</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->item_uom."</td>
									</tr>
									<tr>
										<td><b>Item Qty</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_itemqty."</td>
									</tr>
									<tr>
										<td><b>Remarks</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_remarks."</td>
									</tr>
								</table>
								<hr>
								Login to <a href='".base_url()."'>http://ict.aaci.ph/inventory/</a> for more delivery information. 
							";

						//}
						$this->email->message($email_content);
						//$this->email->send();

						if($this->email->send()){
							$id = $wrec->wi_id;
							$d = array('email_send'=>1);
							$this->db->where('wi_id', $id);
							$this->db->update('whir', $d);
						}
						
					}
				}
			}
		}
	}

	function send_delivery_out_sap(){

		$q2 = $this->db->query("SELECT wi_id, ship_date, wi_expecteddeliverydate, wi_location, deldate, rr_category, wi_remarks, wi_deltype, wh_name, wi_reftype, wi_refnum, wi_reftype2, wi_refnum2, item_uom, wi_itemqty, item_id, wi_refname FROM whir 
			WHERE wi_reftype = '".$this->input->post('doctype1')."'
				AND wi_reftype2 = '".$this->input->post('doctype2')."'
				AND wi_refnum = '".$this->input->post('ref')."'
				AND wi_refnum2 = '".$this->input->post('ref2')."' ");

		foreach ($q2->result() as $wrec){

			$q1 = $this->db->query("SELECT CardName,
				(SELECT comm__name 
					FROM ocmt 
					WHERE status=1 
					AND comm__id='".$wrec->item_id."')itemname
				FROM ocrd
				WHERE CardCode='".$wrec->wi_refname."'
			");

			foreach($q1->result() as $bp){
				$q = $this->db->query("
					SELECT a.wh_code,a.emailadd,b.wh_name
					FROM oedr a
					INNER JOIN (
						SELECT wh_code,wh_name,wh_status 
						FROM mwhr WHERE wh_status=1)b 
					ON a.wh_code=b.wh_code
					WHERE b.wh_name='".$wrec->wh_name."' 
						AND a.type='Creation' 
						AND a.status='1'
				");
				if($q->num_rows == true){
					$email_content="";
					foreach ($q->result() as $r){
						
						$this->email_config();
						// $this->email->from('wbis@mkt.aaci.ph', 'AACI WEB Inventory System');
						$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
						$this->email->to($r->emailadd);
						
						// if($this->input->post('deltype') == 'Material Management'){
							// $email_content = "<h1>".$this->input->post('deltype')." Warehouse: <b>".$this->input->post('wh')."</b></h1>";
						// }
						// else{
							$dtype = $wrec->wi_deltype;
							$src = $bp->CardName;
							$desti = $wrec->wh_name;
							$sdo = "";
							$ddo = "";

							if(isset($dtype)){
								if(isset($desti)){
									if($dtype=='Delivery In'){
										// $dtype='Return to ';
										$dtype='Return from '.$src.' '.$wrec->wi_itemqty.' '.$wrec->item_uom;
									}else{
										//$dtype=$dtype." at ";
										$sdo = $desti;
										$ddo = $src;
										$src = $sdo;
										$desti = $ddo;
										$dtype='Delivery to '.$desti.' '.$wrec->wi_itemqty.' '.$wrec->item_uom;
									}
								}
							}
							
							$this->email->subject($dtype);

							// $email_content = "<h1>".$dtype."<b>".$this->input->post('wh')."</b></h1>
							$email_content = "<h1>".$dtype."</h1>
								<hr>
								<table>
									<tr>
										<td><b>Posting | Loading Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->deldate."</td>
									</tr>
									<tr>
										<td><b>Shipment Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->ship_date."</td>
									</tr>
									<tr>
										<td><b>Expected Delivery Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_expecteddeliverydate."</td>
									</tr>
									<tr>
										<td><b>Source</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$src."</td>
									</tr>
									<tr>
										<td><b>Destination</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$desti."</td>
									</tr>
									<tr>
										<td><b>Location</b></b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_location."</td>
									</tr>
									<tr>
										<td><b>Ref No. 1</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_reftype.$wrec->wi_refnum."</td>
									</tr>
									<tr>
										<td><b>Ref No. 2</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_reftype2.$wrec->wi_refnum2."</td>
									</tr>
									<tr>
										<td><b>Item Description</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$bp->itemname."</td>
									</tr>
									<tr>
										<td><b>Item UOM</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->item_uom."</td>
									</tr>
									<tr>
										<td><b>Item Qty</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_itemqty."</td>
									</tr>
									<tr>
										<td><b>Remarks</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_remarks."</td>
									</tr>
								</table>
								<hr>
								Login to <a href='".base_url()."'>http://ict.aaci.ph/inventory/</a> for more delivery information. 
							";

						//}
						$this->email->message($email_content);
						//$this->email->send();

						if($this->email->send()){
							$id = $wrec->wi_id;
							$d = array('email_send'=>1);
							$this->db->where('wi_id', $id);
							$this->db->update('whir', $d);
						}
						
					}
				}
			}
		}
	}

	function send_delivery_out_ae(){

		// // TRANSACTION ID IN WHIR TABLE ==========
		// $tokens = explode ('_',current_url());
		// $id = $tokens[sizeof($tokens)-1];
		//  // ======================================

		// $q2 = $this->db->query("SELECT deldate, rr_category, wi_remarks, wi_deltype, wh_name, wi_reftype, wi_refnum, wi_reftype2, wi_refnum2, item_uom, wi_itemqty, item_id, wi_refname FROM whir WHERE wi_id = '$id' ");

		$q2 = $this->db->query("SELECT ship_date, wi_expecteddeliverydate, wi_location, deldate, rr_category, wi_remarks, wi_deltype, wh_name, wi_reftype, wi_refnum, wi_reftype2, wi_refnum2, item_uom, wi_itemqty, item_id, wi_refname FROM whir 
			WHERE wi_reftype = 'DO'
				AND wi_reftype2 = '".$this->input->post('doctype2')."'
				AND wi_refnum = '".$this->input->post('ref')."'
				AND wi_refnum2 = '".$this->input->post('ref2')."' ");

		foreach ($q2->result() as $wrec){

			$q1 = $this->db->query("SELECT CardName,
				(SELECT comm__name 
					FROM ocmt 
					WHERE status=1 
					AND comm__id='".$wrec->item_id."')itemname
				FROM ocrd
				WHERE CardCode='".$wrec->wi_refname."'
			");

			foreach($q1->result() as $bp){
				$q = $this->db->query("
					SELECT AE_Email
					FROM ocrd
					WHERE CardCode='".$wrec->wi_refname."' 
				");
				if($q->num_rows == true){
					$email_content="";
					foreach ($q->result() as $r){
						$this->email_config();
						// $this->email->from('wbis@mkt.aaci.ph', 'AACI WEB Inventory System');
						$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
						$this->email->to($r->AE_Email);
						
						// if($this->input->post('deltype') == 'Material Management'){
							// $email_content = "<h1>".$this->input->post('deltype')." Warehouse: <b>".$this->input->post('wh')."</b></h1>";
						// }
						// else{
							$dtype = $wrec->wi_deltype;
							$src = $bp->CardName;
							$desti = $wrec->wh_name;
							$sdo = "";
							$ddo = "";

							if(isset($dtype)){
								if(isset($desti)){
									if($dtype=='Delivery In'){
										// $dtype='Return to ';
										$dtype='Return from '.$src.' '.$wrec->wi_itemqty.' '.$wrec->item_uom;
									}else{
										//$dtype=$dtype." at ";
										$sdo = $desti;
										$ddo = $src;
										$src = $sdo;
										$desti = $ddo;
										$dtype='Delivery to '.$desti.' '.$wrec->wi_itemqty.' '.$wrec->item_uom;
									}
								}
							}
							
							$this->email->subject($dtype);

							// $email_content = "<h1>".$dtype."<b>".$this->input->post('wh')."</b></h1>
							$email_content = "<h1>".$dtype."</h1>
								<hr>
								<table>
									<tr>
										<td><b>Posting Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->deldate."</td>
									</tr>
									<tr>
										<td><b>Shipment Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->ship_date."</td>
									</tr>
									<tr>
										<td><b>Expected Delivery Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_expecteddeliverydate."</td>
									</tr>
									<tr>
										<td><b>Source</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$src."</td>
									</tr>
									<tr>
										<td><b>Destination</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$desti."</td>
									</tr>
									<tr>
										<td><b>Location</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_location."</td>
									</tr>
									<tr>
										<td><b>Ref No. 1</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_reftype.$wrec->wi_refnum."</td>
									</tr>
									<tr>
										<td><b>Ref No. 2</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_reftype2.$wrec->wi_refnum2."</td>
									</tr>
									<tr>
										<td><b>Item Description</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$bp->itemname."</td>
									</tr>
									<tr>
										<td><b>Item UOM</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->item_uom."</td>
									</tr>
									<tr>
										<td><b>Item Qty</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_itemqty."</td>
									</tr>
									<tr>
										<td><b>Remarks</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_remarks."</td>
									</tr>

								</table>
								<hr>
								Login to <a href='".base_url()."'>http://ict.aaci.ph/inventory/</a> for more delivery information. 
							";

						//}
						$this->email->message($email_content);

						if($this->input->post('wh') <> 'Test Warehouse'){
							if($this->input->post('ddate') > '2016-09-07' ){
								if($this->input->post('sub_type_del_out') <> 'DO_04'){
									$this->email->send();
								}
							}
						}

					}
				}
			}
		}
	}

	function send_delivery_out_ae_sap(){

		$q2 = $this->db->query("SELECT ship_date, wi_expecteddeliverydate, wi_location, deldate, rr_category, wi_remarks, wi_deltype, wh_name, wi_reftype, wi_refnum, wi_reftype2, wi_refnum2, item_uom, wi_itemqty, item_id, wi_refname FROM whir 
			WHERE wi_reftype = '".$this->input->post('doctype1')."'
				AND wi_reftype2 = '".$this->input->post('doctype2')."'
				AND wi_refnum = '".$this->input->post('ref')."'
				AND wi_refnum2 = '".$this->input->post('ref2')."' ");

		foreach ($q2->result() as $wrec){

			$q1 = $this->db->query("SELECT CardName,
				(SELECT comm__name 
					FROM ocmt 
					WHERE status=1 
					AND comm__id='".$wrec->item_id."')itemname
				FROM ocrd
				WHERE CardCode='".$wrec->wi_refname."'
			");

			foreach($q1->result() as $bp){
				$q = $this->db->query("
					SELECT AE_Email
					FROM ocrd
					WHERE CardCode='".$wrec->wi_refname."' 
				");
				if($q->num_rows == true){
					$email_content="";
					foreach ($q->result() as $r){
						$this->email_config();
						// $this->email->from('wbis@mkt.aaci.ph', 'AACI WEB Inventory System');
						$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
						$this->email->to($r->AE_Email);
						
						// if($this->input->post('deltype') == 'Material Management'){
							// $email_content = "<h1>".$this->input->post('deltype')." Warehouse: <b>".$this->input->post('wh')."</b></h1>";
						// }
						// else{
							$dtype = $wrec->wi_deltype;
							$src = $bp->CardName;
							$desti = $wrec->wh_name;
							$sdo = "";
							$ddo = "";

							if(isset($dtype)){
								if(isset($desti)){
									if($dtype=='Delivery In'){
										// $dtype='Return to ';
										$dtype='Return from '.$src.' '.$wrec->wi_itemqty.' '.$wrec->item_uom;
									}else{
										//$dtype=$dtype." at ";
										$sdo = $desti;
										$ddo = $src;
										$src = $sdo;
										$desti = $ddo;
										$dtype='Delivery to '.$desti.' '.$wrec->wi_itemqty.' '.$wrec->item_uom;
									}
								}
							}
							
							$this->email->subject($dtype);

							// $email_content = "<h1>".$dtype."<b>".$this->input->post('wh')."</b></h1>
							$email_content = "<h1>".$dtype."</h1>
								<hr>
								<table>
									<tr>
										<td><b>Posting Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->deldate."</td>
									</tr>
									<tr>
										<td><b>Shipment Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->ship_date."</td>
									</tr>
									<tr>
										<td><b>Expected Delivery Date</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_expecteddeliverydate."</td>
									</tr>
									<tr>
										<td><b>Source</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$src."</td>
									</tr>
									<tr>
										<td><b>Destination</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$desti."</td>
									</tr>
									<tr>
										<td><b>Location</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_location."</td>
									</tr>
									<tr>
										<td><b>Ref No. 1</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_reftype.$wrec->wi_refnum."</td>
									</tr>
									<tr>
										<td><b>Ref No. 2</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_reftype2.$wrec->wi_refnum2."</td>
									</tr>
									<tr>
										<td><b>Item Description</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$bp->itemname."</td>
									</tr>
									<tr>
										<td><b>Item UOM</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->item_uom."</td>
									</tr>
									<tr>
										<td><b>Item Qty</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_itemqty."</td>
									</tr>
									<tr>
										<td><b>Remarks</b></td>
										<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
										<td>".$wrec->wi_remarks."</td>
									</tr>

								</table>
								<hr>
								Login to <a href='".base_url()."'>http://ict.aaci.ph/inventory/</a> for more delivery information. 
							";

						//}
						$this->email->message($email_content);

						if($this->input->post('wh') <> 'Test Warehouse'){
							if($this->input->post('ddate') > '2016-09-07' ){
								$this->email->send();
							}
						}

					}
				}
			}
		}
	}

	function create_send(){
		$q1 = $this->db->query("SELECT CardName,
			(SELECT comm__name 
				FROM ocmt 
				WHERE status=1 
				AND comm__id='".$this->input->post('whitem')."')itemname
			FROM ocrd
			WHERE CardCode='".$this->input->post('bpname')."'
		");
		foreach($q1->result() as $bp){
			$q = $this->db->query("
				SELECT a.wh_code,a.emailadd,b.wh_name
				FROM oedr a
				INNER JOIN (
					SELECT wh_code,wh_name,wh_status 
					FROM mwhr WHERE wh_status=1)b 
				ON a.wh_code=b.wh_code
				WHERE b.wh_name='".$this->input->post('wh')."' 
					AND a.type='CREATION' 
					AND status='1'
			");
			if($q->num_rows == true){
				$email_content="";
				foreach ($q->result() as $r){
					$this->email_config();
					// $this->email->from('wbis@mkt.aaci.ph', 'AACI WEB Inventory System');
					$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
					$this->email->to($r->emailadd);
					$this->email->subject('AACI Web Inventory System: Delivery In');
					// if($this->input->post('deltype') == 'Material Management'){
						// $email_content = "<h1>".$this->input->post('deltype')." Warehouse: <b>".$this->input->post('wh')."</b></h1>";
					// }
					// else{

						$dtype = $this->input->post('deltype');
						$src = $bp->CardName;
						$desti = $this->input->post('wh');
						$sdo = "";
						$ddo = "";

						if(isset($dtype)){
							if(isset($desti)){
								if($dtype=='Delivery In'){
									// $dtype='Return to ';
									$dtype='Return from '.$src.' '.$this->input->post('whqty').' '.$this->input->post('uom');
								}else{
									//$dtype=$dtype." at ";
									$sdo = $desti;
									$ddo = $src;
									$src = $sdo;
									$desti = $ddo;
									$dtype='Delivery to '.$desti.' '.$this->input->post('whqty').' '.$this->input->post('uom');
								}
							}
						}

						// DATE AND TIME VALUE =========================
						date_default_timezone_set("Asia/Manila");
						$now = time('UP8');
						$datetime =  unix_to_human($now, TRUE);
						//===============================================
						
						// $email_content = "<h1>".$dtype." <b>".$this->input->post('wh')."</b></h1>
						$email_content = "<h1>".$dtype."</h1>
							<hr>
							<table>
								<tr>
									<td>Source</td><td>".$src."</td>
									<td>Date and Time</td><td>".$datetime."</td>
								</tr>
								<tr>
									<td>Destination</td><td>".$desti."</td>
								</tr>
								<tr>
									<td>Ref No. 1</td><td>".$this->input->post('doctype1').$this->input->post('ref')."</td>
								</tr>
								<tr>
									<td>Ref No. 2</td><td>".$this->input->post('doctype2').$this->input->post('ref2')."</td>
								</tr>
								<tr>
									<td>Item Description</td><td>".$bp->itemname."</td>
								</tr>
								<tr>
									<td>Item UOM</td><td>".$this->input->post('uom')."</td>
								</tr>
								<tr>
									<td>Item Qty</td><td>".$this->input->post('whqty')."</td>
								</tr>
								<tr>
									<td>Remarks</td><td>".$this->input->post('remarks')."</td>
								</tr>
							</table>
							<hr>
							Login to <a href='".base_url()."'>http://ict.aaci.ph/inventory/</a> for more delivery information. 
						";
					//}
					$this->email->message($email_content);
					//$this->email->send();
					if(!$this->email->send()){
						return show_error($this->email->print_debugger());
					 }
					return 'Registration has been successfull!';
				}
			}
		}
	}

	function create_mm_send(){
		$q1 = $this->db->query("SELECT CardName,
			(SELECT comm__name 
				FROM ocmt 
				WHERE status=1 
				AND comm__id='".$this->input->post('whitem')."')itemname
			FROM ocrd
			WHERE CardCode='".$this->input->post('bpname')."'
		");
		foreach($q1->result() as $bp){
			$q = $this->db->query("
				SELECT a.wh_code,a.emailadd,b.wh_name
				FROM oedr a
				INNER JOIN (
					SELECT wh_code,wh_name,wh_status 
					FROM mwhr WHERE wh_status=1)b 
				ON a.wh_code=b.wh_code
				WHERE b.wh_name='".$this->input->post('wh')."' 
					AND a.type='CREATION' 
					AND status='1'
			");
			if($q->num_rows == true){
				$email_content="";
				foreach ($q->result() as $r){
					$this->email_config();
					$this->email->from('web@mkt.aaci.ph', 'AACI WEB Inventory System');
					$this->email->to($r->emailadd);
					$this->email->subject('AACI Web Inventory System: Delivery In');
					// if($this->input->post('deltype') == 'Material Management'){
						// $email_content = "<h1>".$this->input->post('deltype')." Warehouse: <b>".$this->input->post('wh')."</b></h1>";
					// }
					// else{
						$email_content = "<h1>".$this->input->post('deltype')." Warehouse: <b>".$this->input->post('wh')."</b></h1>
							<hr>
							<table>
								<tr>
									<td>Source</td><td>".$bp->CardName."</td>
								</tr>
								<tr>
									<td>Destination</td><td>".$this->input->post('wh')."</td>
								</tr>
								<tr>
									<td>Ref No. 1</td><td>".$this->input->post('doctype1').$this->input->post('ref')."</td>
								</tr>
								<tr>
									<td>Ref No. 2</td><td>".$this->input->post('doctype2').$this->input->post('ref2')."</td>
								</tr>
								<tr>
									<td>Item Description</td><td>".$bp->itemname."</td>
								</tr>
								<tr>
									<td>Item UOM</td><td>".$this->input->post('uom')."</td>
								</tr>
								<tr>
									<td>Item Qty</td><td>".$this->input->post('whqty')."</td>
								</tr>
								<tr>
									<td>Remarks</td><td>".$this->input->post('remarks')."</td>
								</tr>
							</table>
							<hr>
							Login at <a href='".base_url()."'>http://ict.aaci.ph/inventory/</a> for more delivery information. 
						";
					//}
					$this->email->message($email_content);
					$this->email->send();
					// if(!$this->email->send()){
						// return show_error($this->email->print_debugger());
					// }
					// return 'Registration has been successfull!';
				}
			}
		}
	}
}
?>