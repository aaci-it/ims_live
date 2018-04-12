<?php
class Acl_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	function cleanInput($input) {
	  $search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	  );
	 
		$output = preg_replace($search, '', $input);
		return $output;
	}
	
	function sanitize($input) {
		if (is_array($input)) {
			foreach($input as $var=>$val) {
				$output[$var] = sanitize($val);
			}
		}
		else {
			if (get_magic_quotes_gpc()) {
				$input = stripslashes($input);
			}
			$input  = $this->cleanInput($input);
			$output = mysql_real_escape_string($input);
			$output = trim($output);
		}
		return $output;
	}
	
	
	function if_digit($a){
		// Old code ereg("^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$", $a)
		if(preg_match("/^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$/", $a)){
			return true;
		}
		else{
			return false;
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
	
	function change_password(){
		$old = $this->sanitize($this->input->post('cpword'));
		$new = $this->sanitize($this->input->post('npword'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('cpword', 'Current Password', 'trim|required');
		$this->form_validation->set_rules('npword', 'New Password', 'trim|required|matches[npword2]');
		$this->form_validation->set_rules('npword2', 'Re-Type New Password', 'trim|required');
		if($this->form_validation->run()){
			$d = array('memb__pword' => md5($new));
			$this->db->where('memb__id',$this->session->userdata('usr_uname')); 
			$update = $this->db->update('ousr',$d);
			if($update){
				return 'Password has been replaced!';
			}
		}
	}
	
	function forgot_password(){
		$email = $this->sanitize($this->input->post('email'));
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		if($this->form_validation->run()){
			if($username = $this->email_check($email)){ 	
				$pw = $this->sanitize((rand(100000,900000)));
				$d = array('memb__pword' => md5($pw));
				$this->db->where('memb__email',$email); 
				$update = $this->db->update('ousr',$d);
				if($update > 0){
					
					$this->email_config();
					$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
					$this->email->to($email);
					$this->email->subject('AACI Web Inventory System Password Reset');

					$email_content = "
					<p><b>AACI WEB BASED INVENTORY PASSWORD RESET</b></p>
					<table>
						<tr>
							<td><b>Username</b></td>
							<td><b> : </b></td>
							<td><em>".$username."</em></td>
						</tr>
						<tr>
							<td><b>Password</b></td>
							<td><b> : </b></td>
							<td><em>".$pw."</em></td>
						</tr>
					</table>

					<p>Kindly change your password after you login to the system. <a href='".base_url()."'><b><em>Click here</em></b></a></p>

					";
					$this->email->message($email_content);
					
					if(!$this->email->send()){
						return show_error($this->email->print_debugger());
					}
					return 'Your password has been sent to your email.';
				}
			}
			else{
				return 'Email does not exist!';
			}
		}
	}
	
	function register(){
		//get the VRs clean 
		$username = $this->sanitize($this->input->post('username'));
		$name = $this->sanitize($this->input->post('name'));
		$email = $this->sanitize($this->input->post('email'));
		
		//check for duplicate or if some parts already exist and other sh*t.
		if($this->email_check($email)){ return 'Email Address already exist!'; }
		if($this->username_check($username)){ return 'Username already exist'; }
		if($this->name_check($name)){ return 'Name already exist!'; }
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		
		if($this->form_validation->run()){
			$pw = $this->sanitize((rand(100000,900000)));
			$d = array(
				'memb__id' => $username,
				'memb__username' => $name,
				'memb__pword' => md5($pw),
				'memb__email' => $email,
				'memb__status' => 1
			);
			$insert = $this->db->insert('ousr',$d);
			if($insert > 0){
				
				$this->email_config();
				$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
				$this->email->to($email);
				$this->email->subject('AACI Web Inventory System Registration');
				$email_content = "<p>AACI Web Inventory System Registration</p>
					Username: ".$username." <br />
					Password: ".$pw." <br />
					Kindly change your password after you login to the system. <a href='".base_url()."'>Click here</a>
				";
				$this->email->message($email_content);
				
				if(!$this->email->send()){
					return show_error($this->email->print_debugger());
				}
				return 'Registration has been successfull!';
			}
		}
		else{
			return 'Registration failed!';
		}
	}
	
	
	/* function login(){
		$this->load->database();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username','Username','required|trim ');
		$this->form_validation->set_rules('password','Password','required|trim ');
		
		if($this->form_validation->run()){
			$username = $this->sanitize($this->input->post('username'));
			$password = $this->sanitize($this->input->post('password'));
			$this->db->where('memb__id',$username);
			$this->db->where('memb__pword',md5($password));
			$q = $this->db->get('ousr');
			if($q->num_rows == 1){
				$row = $q->row();
				$logindata= array(
					'userid' => $row->memb__id,
					'username' => $row->memb__username,
					'validated'=>true
				);
				$this->session->set_userdata($logindata);
				return true;
			}
			else{
				return 'Wrong Username or Password';
			}
		}
		return false;
    } */
	
	function email_check($email){
		$this->db->where('memb__email',$email); 
		$q = $this->db->get('ousr');
		if($q->num_rows == 1){ $data = $q->row(); $un = $data->memb__id; return $un; }{ return false; }
	}
	
	function username_check($username){
		$this->db->where('memb__id',$username); 
		$q = $this->db->get('ousr');
		if($q->num_rows == 1){ return true; }{ return false; }
	}
	
	function name_check($name){
		$this->db->like('memb__username',$name); 
		$q = $this->db->get('ousr');
		if($q->num_rows == 1){ return true; }{ return false; }
	}
	
	function my_update(){
		$d = array(
			'memb__pword' => md5($this->input->post('pword')),
			'memb__email' => $this->input->post('email'),
			'memb__lastuser' => $this->session->userdata('username')
		);
		$this->db->where('memb__id',$this->input->post('usrcode'));
	}
	function dr_layout(){
		//$DocNum = $this->uri->segment(3);
		//$DocNum = $this->input->post('do');
		$DocNum = $this->uri->segment(3);
		/*if($this->input->post('add_dr_no')){
			$adt = $this->load->database('adt', TRUE);
			if($this->check_dr_number()){
				$update = array(
					'dr_number' => $this->input->post('dr_no')
				);
				$adt->where('do_number', $DocNum);
				$adt->update('phase2.dbo.FinalTransmital', $update);
			}
		}*/
		
		$sap = $this->load->database('db2', TRUE);
		$query = "
			SELECT (SELECT Name FROM [@TRUCKER]WHERE Code = A.U_Trucker) 'Truck', A.Address2, CONVERT(VARCHAR(10), A.DocDueDate, 101) as 'DocDueDate', A.CardName, A.DocNum,A.U_PONo, B.Quantity, B.unitMsr,B.Dscription, A.Comments FROM ODLN A 
			INNER JOIN DLN1 B ON A.DocEntry = B.DocEntry
			WHERE A.DocNum = '$DocNum'
		";
		$q = $sap->query($query);
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		else{
			return false;
		}
	}
	
	function whse_summary(){ 
		$q = $this->db->get('mwhr');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		else{
			return false;
		}
	}
	

	// SENDING OF TRANSACTION SUMMARY ---------------------------------------

	function whse_trn_summary(){ 
		
		$today = date("Y-m-d");
		$from = $today.' 00:00:00';
		$to = $today.' 23:59:59';
		$in = 0;
		$out = 0;
		$tr_count = 0;
		$email_content = '';
					
		foreach($this->whse_summary() as $row){
			$whse = 	$row->wh_name;	
			$q = $this->db->query("
				SELECT * FROM whir WHERE wh_name = '$whse' AND wi_createdatetime BETWEEN '$from' AND '$to'			
			");
			if($q->num_rows() > 0){
				foreach($q->result() as $rows){
					if($rows->wi_deltype == 'Delivery In'){
        				$in = $in + $rows->wi_itemqty;
        			}
        			if($rows->wi_deltype == 'Delivery Out'){
        				$out = $out + $rows->wi_itemqty;
        			}
        			$tr_count++;
				}

				
				$email_content .= $whse.' | <b> Transaction count: '.$tr_count.'</b><br />IN : '.$in.'<br />OUT : '.$out.'<br />';
				$email_content .= "\n";
			}
       	$in = 0;
       	$out = 0;
       	$tr_count = 0;
		}
		
		$email = array('jayson.suyat@aaci.ph','madona.nabejet@aaci.ph', 'audit@aaci.ph');
		$this->email_config();

		$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
		$this->email->to($email);
		$this->email->subject('Web Based Inventory Updates');
		if(!$email_content == ''){
			$this->email->message($email_content);
			if(!$this->email->send()){
				return show_error($this->email->print_debugger());
			}else{ return 'Email Sent'; }
		}
		else{
			return 'Email Content is Null';
		}
	}

	function sum_del_ret_query(){ 
		$q = $this->db->get('mwhr');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		else{
			return false;
		}
	}
	
	function sum_del_ret_count(){ 
		
		$today = date("Y-m-d");
		$from = $today.' 00:00:00';
		$to = $today.' 23:59:59';

		$in = 0;

		$dom_out = 0;
		$doa_out = 0;

		$email_content = '';
					
		foreach($this->sum_del_ret_query() as $row){
			$whse = 	$row->wh_name;	
			$q = $this->db->query("
				SELECT * FROM whir WHERE wh_name = '$whse' AND wi_createdatetime BETWEEN '$from' AND '$to'			
			");
			if($q->num_rows() > 0){
				foreach($q->result() as $rows){

					// TOTAL DELIVERIES FROM DO MANUNAL
					if($rows->wi_type == 1 AND wi_dtcode == "DT_02" AND wi_subtype == "DO_01" AND wi_approvestatus == 1){
        				$dom_out = $dom_out + 1;
        			}

					// TOTAL DELIVERIES FROM DO HO
					if($rows->wi_type == 1 AND wi_dtcode == "DT_04" AND wi_subtype == "DO_01" AND wi_approvestatus == 1){
        				$doa_out = $doa_out + 1;
        			}

        			// TOTAL RETURNS
					if($rows->wi_type == 0 AND wi_dtcode == "DT_01" AND wi_subtype == "DI_04" AND wi_approvestatus == 1){
        				$in = $in + 1;
        			}

				}

				
				$email_content .= $whse.' <br />
											DO Manual Deliveries : '.$dom_out.'<br />
											DO HO Deliveries : '.$doa_out.'<br />
											Returns : '.$in.'<br />';
				$email_content .= "\n";
			}

       	$in = 0;
       	$dom_out = 0;
       	$doa_out = 0;
		
		}
		
		// $email = array('jayson.suyat@aaci.ph','madona.nabejet@aaci.ph', 'audit@aaci.ph');
		$email = array('jayson.suyat@aaci.ph');
		$this->email_config();

		$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
		$this->email->to($email);
		$this->email->subject('Web Based Inventory Updates');
		if(!$email_content == ''){
			$this->email->message($email_content);
			if(!$this->email->send()){
				return show_error($this->email->print_debugger());
			}else{ return 'Email Sent'; }
		}
	
	}

	function whse_trn_summary_test(){ 
		
		$today = date("Y-m-d");
		$from = $today.' 00:00:00';
		$to = $today.' 23:59:59';
		$in = 0;
		$out = 0;
		$tr_count = 0;
		$email_content = '';
					
		foreach($this->whse_summary() as $row){
			$whse = 	$row->wh_name;	
			$q = $this->db->query("
				SELECT * FROM whir WHERE wh_name = '$whse' AND wi_createdatetime BETWEEN '$from' AND '$to'			
			");
			if($q->num_rows() > 0){
				foreach($q->result() as $rows){
					if($rows->wi_deltype == 'Delivery In'){
        				$in = $in + $rows->wi_itemqty;
        			}
        			if($rows->wi_deltype == 'Delivery Out'){
        				$out = $out + $rows->wi_itemqty;
        			}
        			$tr_count++;
				}

				
				$email_content .= $whse.' | <b> Transaction count: '.$tr_count.'</b><br />IN : '.$in.'<br />OUT : '.$out.'<br />';
				$email_content .= "\n";
			}
       	$in = 0;
       	$out = 0;
       	$tr_count = 0;
		}
		
		$email = array('jayson.suyat@aaci.ph');
		$this->email_config();

		$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System Test');
		$this->email->to($email);
		$this->email->subject('Web Based Inventory Updates');
		if(!$email_content == ''){
			$this->email->message($email_content);
			if(!$this->email->send()){
				return show_error($this->email->print_debugger());
			}else{ return 'Email Sent'; }
		}
		else{
			return 'Email Content is Null';
		}
	}
	
	function print_dr_pdf(){
		
		$print = array();
		$print = $this->dr_layout();
		
		$show_layout = 0;
		
		if($print){
			foreach($print as $row){
				$truck = $row->Truck;
				$address = $row->Address2;
				$del_date = $row->DocDueDate;
				$bp = $row->CardName;
				$docnum = $row->DocNum;
				$po_no = $row->U_PONo;
				$qty = number_format($row->Quantity,3,'.','');
				$uom = $row->unitMsr;
				$description = $row->Dscription;
				$rem = $row->Comments;
			}
			$show_layout = 1;
		}

		if($show_layout == 1){
			$pdf = new FPDF();
			$pdf->AddPage();
			$pdf->SetMargins(1, 1, 1);
			
			$pdf->SetFont('Arial','B',9); $pdf->SetXY(135, 25); $pdf->Cell(40,10,$docnum,0, 1);
			$pdf->SetFont('Arial','B',9); $pdf->SetXY(135, 29); $pdf->Cell(40,10,$del_date,0, 1);
			$pdf->SetFont('Arial','B',12); $pdf->SetXY(40, 34); $pdf->Cell(40,10,$truck,0, 1);
			$pdf->SetFont('Arial','B',12); $pdf->SetXY(127, 34); $pdf->Cell(40,10,$bp,0, 1);
			$pdf->SetFont('Arial','B',12); $pdf->SetXY(127, 38); $pdf->Cell(40,10,$address,0, 1);
			$pdf->SetFont('Arial','B',12); $pdf->SetXY(50, 45); $pdf->Cell(40,10,$po_no,0, 1);
			$pdf->SetFont('Arial','B',12); $pdf->SetXY(180, 45); $pdf->Cell(40,10,$docnum,0, 1);
			$pdf->SetFont('Arial','B',12); $pdf->SetXY(40, 50); $pdf->Cell(40,10,$qty,0, 1);
			$pdf->SetFont('Arial','B',12); $pdf->SetXY(70, 50); $pdf->Cell(40,10,$uom,0, 1);
			$pdf->SetFont('Arial','B',9); $pdf->SetXY(120, 50); $pdf->Cell(40,10,$description,0, 1);
			$pdf->SetFont('Arial','B',7); $pdf->SetXY(100, 55); $pdf->MultiCell(100,5,$rem);
			$name = $this->uri->segment(3);
			//$pdf->Output($name.'.pdf', 'D');
			//$pdf->Output();
			
			if( $pdf->Output($name.'.pdf', 'D') ){ redirect('main'); }
			
		}
	}
	
	function email_clients(){
		
		if($this->trn_data()){
			foreach($this->trn_data() as $row){
				
				$wh_name = $row->wh_name;
				$cust_code = $row->CardCode;

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
				if($row->wi_reftype == 'DR' OR $row->wi_reftype == 'ATW' OR $row->wi_reftype == 'WIS'){
					$dr = $row->wi_refnum;
				}
				if($row->wi_reftype2 == 'DR' OR $row->wi_reftype2 == 'ATW' OR $row->wi_reftype2 == 'WIS'){
					$dr = $row->wi_refnum2;
				}
				$itemcode = $row->item_id;
				$dscription = $row->comm__name;
				$qty = $row->wi_itemqty;
				$accepted_qty = $row->trk_acceptedqty;
				$variance = $qty - $accepted_qty;
				$location = $row->wi_location;
				$shipdate = $row->wi_expecteddeliverydate;
				$shiptime = $row->ship_time;
				$utime = $row->trk_acceptedutime;
				$arrival = $row->trk_arriveddate;
				$arrtime = $row->trk_arrivedtime;
				$unloading = $row->trk_accepteddate.' '.$row->trk_acceptedtime; 
				$del_status = '';
				if($row->trk_acceptedstatus == '1'){
					$del_status = 'Accepted';
				}
				$dr_remarks = $row->wi_remarks;
				$arr_remarks = $row->trk_acceptedremarks;
				$can_remarks = $row->trk_canceledremarks;
				$uom = $row->item_uom;

				$deldate = $row->deldate;
				$transporter = $row->truck_company;
				$truck_pnum = $row->truck_platenum;
				$truck_arrival_source = $row->truck_arrival_time;
				$truck_adate = $row->trk_arriveddate;
				$truck_atime = $row->trk_arrivedtime;
				$reftype = $row->wi_reftype;
				$reftype2 = $row->wi_reftype2;
				$refnum = $row->wi_refnum;
				$refnum2 = $row->wi_refnum2;
				$ref3 = $row->wi_refnum3;

			}
		}
	
		// YEAR
		$year = substr($deldate, 0, 4);

		// MONTH
		$month = substr($deldate, 5, 2);

		$variance = number_format($variance,3);

		$accepted_qty = number_format($accepted_qty,3);

		// DISPLAY MESSAGE IF HAS VARIANCE
		if($variance <> 0){
			$del_stats = 'Accepted '.$accepted_qty.' | '.'Returned '.$variance;
		}else{
			$del_stats = 'Accepted '.$accepted_qty;
		}

		if($can_remarks <> NULL){
			$remarks = $arr_remarks.' | '.$can_remarks;
		}else{
			$remarks = $arr_remarks;
		}


		// DISPLAY TIME IN AM OR PM FOR SHIPMENT TIME=============================================
		$shiptime = substr($shiptime, 0, -3);

		if($shiptime[0]=='0'){
			$time1 = substr($shiptime, -4, 1);
		}else{
			$time1 = substr($shiptime, 0, 2);
		}

		$time2 = (int)$time1;
		if($shiptime=="00:00"){
			$shiptime="";
		}else{
			if($time2 >=12 and $time2 <=23){
				$shiptime = $shiptime;
			}else{
				$shiptime = $shiptime;
			}
		}

		// DISPLAY TIME IN AM OR PM FOR ARRIVAL TIME=============================================
		$arrtime = substr($arrtime, 0, -3);

		if($arrtime[0]=='0'){
			$time2 = substr($arrtime, -4, 1);
		}else{
			$time2 = substr($arrtime, 0, 2);
		}

		$time3 = (int)$time2;
		if($arrtime=="00:00"){
			$arrtime="";
		}else{
			if($time3 >=12 and $time3 <=23){
				$arrtime = $arrtime;
			}else{
				$arrtime = $arrtime;
			}
		}

		// DISPLAY TIME IN AM OR PM FOR UNLOADING TIME=============================================
		$utime = substr($utime, 0, -3);

		if($utime[0]=='0'){
			$time3 = substr($utime, -4, 1);
		}else{
			$time3 = substr($utime, 0, 2);
		}

		$time4 = (int)$time3;
		if($utime=="00:00"){
			$utime="";
		}else{
			if($time4 >=12 and $time4 <=23){
				$utime = $utime;
			}else{
				$utime = $utime;
			}
		}
		// ========================================================================

		$contact_person = $row->Customer_Addressee;

		if($contact_person == ""){
			$cpsn = "Contact Person Name";
		}else{
			$cpsn = $contact_person;
		}

		$email_content = "

			Dear <b><em>Ms. / Mr. $cpsn</em></b>  <br /> <br />
			Please take this time to review your order details and confirm your company's acknowledgement of the delivery of <br />
			the goods and any supplemental documents  as follows: <br /> <br />

			<table>
				<tr>
					<td><b>Customer</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <b><em> $bp </em></b></td>
				</tr>
				<tr>
					<td><b>Purchase Order</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <b><em> $po </em></b></td>
				</tr>
				<tr>
					<td><b>Delivery Order #</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <b><em> $do </em></b></td>
				</tr>
				<tr>
					<td><b>DR / WIS / ATW #</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <b><em> $dr </em></b></td>
				</tr>
				<tr>
					<td><b>Reference No. 3</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <b><em> $ref3 </em></b></td>
				</tr>
				<tr>
					<td><b>Item Description</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $itemcode $dscription </em></td>
				</tr>
				<tr>
					<td><b>Delivery Qty / Unit</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $qty $uom </em></td>
				</tr>
				<tr>
					<td><b>Accepted Qty / Unit</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $accepted_qty $uom </em></td>
				</tr>
				<tr>
					<td><b>Returned Qty / Unit</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $variance $uom </em></td>
				</tr>
				<tr>
					<td><b>Delivery Destination</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $location </em></td>
				</tr>
				<tr>
					<td><b>Shipment Date and Time</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $shipdate $shiptime </em></td>
				</tr> 
				<tr>
					<td><b>Arrival Date and Time</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $arrival $arrtime</em></td>
				</tr>
				<tr>
					<td><b>Unloading Finish Time</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $utime </em></td>
				</tr>
				<tr>
					<td><b>Delivery Status</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $del_stats </em></td>
				</tr>
				<tr>
					<td><b>DR Remarks</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $dr_remarks </em></td>
				</tr>
				<tr>
					<td><b>Transporter Remarks</b></td>
					<td> : </td>
					<td>&nbsp;&nbsp; <em> $remarks </em></td>
				</tr>

			</table><br/>

			Thank you for choosing <b>All Asian Countertrade, Inc.</b> We will be delighted to receive your <b><em><a href='".base_url()."index.php/main/customer_login/".$cust_code."_".$po."_".$do."'> confirmation </a></em></b> or hear from you within <b><em>24 hours</em></b> through this link. <br /> <br />
			Sincerely, <br><br>
			
			<em><b>$row->Account_Executive</b></em><br>
			<i>Account Executive</i><br>
			Email Address : $row->AE_Email<br>
			Contact No : $row->AE_Mobile<br><br>


			<em><b>$row->Logistics</b></em><br>
			<i>Outbound Logistics</i><br>
			Email Address : $row->Logistics_Email<br>
			Contact No : $row->Logistics_Mobile<br><br>

		";

		$cust_email1 = $row->Customer_Email;
		$cust_email2 = $row->Customer_Email2;

		if($cust_email1 == "" AND $cust_email2 == ""){
			$email_receiver = array('jayson.suyat@aaci.ph');
		}elseif($cust_email1 <> "" AND $cust_email2 == ""){
			$email_receiver = array($cust_email1);
		}elseif($cust_email1 == "" AND $cust_email2 <> "" ){
			$email_receiver = array($cust_email2);
		}elseif($cust_email1 <> "" AND $cust_email2 <> ""){
			$email_receiver = array($cust_email1, $cust_email2);
		}

		$subject = 'Delivery Confirmation PO# '.$po;
		$this->email_config();
		$this->email->from('webinvty@aaci.ph', 'All Asian Countertrade, Inc.');
		// $this->email->to('jayson.suyat@aaci.ph');
		$this->email->to($email_receiver);
		// $this->email->cc('madona.nabejet@aaci.ph');
		$this->email->subject($subject);
		
		//Check if the Confirmation Delivery Record was already inserted
		$qry = "SELECT cust_code, po_num, do_num, dr_num, item_code FROM cdel WHERE cust_code = '$cust_code'
				AND po_num = '$po'
				AND do_num = '$do'
				AND dr_num = '$dr' 
				AND item_code = '$itemcode' 
				AND confirm <> 1 ";

		$qry = $this->db->query($qry);

		if($qry->num_rows() > 0){
		}else{
			$cdel_data = array(
				'src_whse'=>$wh_name,
				'cust_cpsn'=>'Contact Person',
				'cust_code'=>$cust_code,
				'cust_name'=>$bp,
				'po_num'=>$po,
				'do_num'=>$do,
				'dr_num'=>$dr,
				'item_code'=>$itemcode,
				'item_desc'=>$dscription,
				'del_qty'=>$qty,
				'del_uom'=>$uom,
				'acc_qty'=>$accepted_qty,
				'acc_uom'=>$uom,
				'var_qty'=>$variance,
				'del_desti'=>$location,
				'ship_date'=>$shipdate,
				'ship_time'=>$shiptime,
				'arr_date'=>$arrival,
				'arr_time'=>$arrtime,
				'uld_ftime'=>$utime,
				'del_stat'=>$del_status,
				'arr_remarks'=>$arr_remarks,
				'can_remarks'=>$can_remarks,
				'dr_remarks'=>$dr_remarks,
				'year'=>$year,
				'month'=>$month,
				'transporter'=>$transporter,
				'truck_platenum'=>$truck_pnum,
				'arrival_time_source'=>$truck_arrival_source,
				'truck_arrival_date'=>$truck_adate,
				'truck_arrival_time'=>$truck_atime,
				'reftype'=>$reftype,
				'reftype2'=>$reftype2,
				'refnum'=>$refnum,
				'refnum2'=>$refnum2,
				'ref3'=>$ref3,
				'deldate'=>$deldate
			);
			
			// INSERT CONFIRMATION DELIVERY RECORD TO TABLE cdel
			$this->db->insert('cdel', $cdel_data);
			// ===================================================

			// UPDATE whir TABLE =================================
			$str = array('email_cdel'=>1);
			$this->db->where('wi_PONum',$po);
			$this->db->update('whir', $str);
			// ===================================================

			// SEND CONFIRMATION DELIVERY TO CUSTOMER ============
			$this->email->message($email_content);
			// ===================================================

			// DONT SEND TO CUSTOMER IF THE WAREHOUSE IF TEST WAREHOUSE
			// if($wh_name <> "Test Warehouse"){
				if(!$this->email->send()){
					return show_error($this->email->print_debugger());
				}
			// }
		}				
	}
	
	function trn_data(){
		$id = $this->input->post('emails');		
		$q = $this->db->query("
			SELECT a.deldate, a.truck_company, a.truck_platenum, a.truck_arrival_time, a.wi_remarks, a.wi_expecteddeliverydate, a.ship_date, a.ship_time, a.trk_acceptedutime, a.wh_name, a.wi_refnum, c.comm__name, a.wi_refnum2, b.CardName, a.wi_PONum, a.wi_reftype, a.wi_reftype2, a.wi_drnum, a.item_id, a.wi_doqty, a.wi_itemqty, a.wi_location, a.wi_exactdeliverydate, a.item_uom,
			a.wi_refnum3, a.wi_reftype, a.wi_reftype2, a.trk_arrivedtime, a.trk_arriveddate, a.trk_arrivedstatus, a.trk_accepteddate, a.trk_acceptedqty, a.trk_acceptedremarks, a.trk_canceledstatus, a.trk_canceledremarks, a.trk_canceledqty, a.trk_acceptedtime, a.trk_arrivedtime, a.trk_acceptedstatus, b.CardCode,
			b.Customer_Email, b.Customer_Email2, b.Customer_Addressee, b.Account_Executive, b.AE_Email, b.AE_Mobile, b.Logistics, b.Logistics_Email, b.Logistics_Mobile
			FROM whir a
			INNER JOIN ocrd b ON a.wi_refname = b.CardCode
			INNER JOIN ocmt c ON a.item_id = c.comm__id
			WHERE a.wi_id = '$id'
		");
		
		if($q->num_rows > 0){
			return $q->result();
		}
	}

	function condel_data(){
		$string = $this->uri->segment(3);  
		$var = explode('_', $string);
 
 		$cust_code = $var[0];
		$po_num = $var[1];
		$do_num = $var[2];

		$qry = "SELECT * FROM cdel WHERE cust_code = '$cust_code' AND  po_num = '$po_num' AND do_num = '$do_num' AND confirm = 0 ";
		$q = $this->db->query($qry);

		if($q->num_rows > 0){
			foreach ($q->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}else{
			return false;
		}
	}

	function update_condel(){
		date_default_timezone_set("Asia/Manila");
		$date = date('Y-m-d');
		$time = date('H:i');
		$ip = $this->input->ip_address();
		$pnum = $this->input->post('pn');
		$dnum = $this->input->post('dn');

		$con = array(
			'qty_acpt'=>$this->input->post('qa'),
			'qty_ret'=>$this->input->post('qr'),
			'cust_rmks'=>$this->input->post('cust_rmks'),
			'cust_ip'=>$ip,
			'cdel_date'=>$date,
			'cdel_time'=>$time,
			'confirm'=>1,
			'cust_username'=>$this->session->userdata('usr_uname')
		);

		$this->db->where('po_num', $pnum);
		$this->db->where('do_num', $dnum);
		$this->db->update('cdel',$con);
	}

	function condel_email_success(){

		$pnum = $this->input->post('pn');
		$qry = "SELECT * FROM cdel a
					LEFT JOIN ocrd b ON b.CardCode = a.cust_code
					WHERE po_num = '$pnum' AND confirm = 1 ";
		$q = $this->db->query($qry);

		if($q->num_rows > 0){
			foreach($q->result() as $rec){
				
				$variance = $rec->var_qty;
				$variance = number_format($variance,3);

				$accepted_qty = $rec->acc_qty;
				$accepted_qty = number_format($accepted_qty,3);

				// DISPLAY MESSAGE IF HAS VARIANCE
				if($variance <> 0){
					$del_stats = 'Accepted '.$accepted_qty.' | '.'Returned '.$variance;
				}else{
					$del_stats = 'Accepted '.$accepted_qty;
				}

				$arr_remarks = $rec->arr_remarks;
				$can_remarks = $rec->can_remarks;

				if($can_remarks <> NULL){
					$remarks = $arr_remarks.' | '.$can_remarks;
				}else{
					$remarks = $arr_remarks;
				}


				// DISPLAY TIME IN AM OR PM FOR SHIPMENT TIME=============================================
				$shiptime = $rec->ship_time;
				$shiptime = substr($shiptime, 0, -3);

				if($shiptime[0]=='0'){
					$time1 = substr($shiptime, -4, 1);
				}else{
					$time1 = substr($shiptime, 0, 2);
				}

				$time2 = (int)$time1;
				if($shiptime=="00:00"){
					$shiptime="";
				}else{
					if($time2 >=12 and $time2 <=23){
						$shiptime = $shiptime;
					}else{
						$shiptime = $shiptime;
					}
				}

				// DISPLAY TIME IN AM OR PM FOR ARRIVAL TIME=============================================
				$arrtime = $rec->arr_time;
				$arrtime = substr($arrtime, 0, -3);

				if($arrtime[0]=='0'){
					$time2 = substr($arrtime, -4, 1);
				}else{
					$time2 = substr($arrtime, 0, 2);
				}

				$time3 = (int)$time2;
				if($arrtime=="00:00"){
					$arrtime="";
				}else{
					if($time3 >=12 and $time3 <=23){
						$arrtime = $arrtime;
					}else{
						$arrtime = $arrtime;
					}
				}

				$utime_temp = $rec->uld_ftime;
				$utime = substr($utime_temp, 0, 4);

				$email_content = "

					<b>DELIVERY CONFIRMATION COMPLETE</b><br /> <br />

					<table>
						<tr>
							<td><b>Customer</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <b><em> $rec->cust_name </em></b></td>
						</tr>
						<tr>
							<td><b>Purchase Order</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <b><em> $rec->po_num </em></b></td>
						</tr>
						<tr>
							<td><b>Delivery Order #</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <b><em> $rec->do_num </em></b></td>
						</tr>
						<tr>
							<td><b>DR / WIS / ATW #</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <b><em> $rec->dr_num </em></b></td>
						</tr>
						<tr>
							<td><b>Reference No. 3</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <b><em> $rec->ref3 </em></b></td>
						</tr>
						<tr>
							<td><b>Item Description</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->item_code $rec->item_desc </em></td>
						</tr>
						<tr>
							<td><b>Delivery Qty / Unit</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->del_qty $rec->del_uom </em></td>
						</tr>
						<tr>
							<td><b>Accepted Qty / Unit</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->acc_qty $rec->del_uom </em></td>
						</tr>
						<tr>
							<td><b>Variance Qty / Unit</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->var_qty $rec->del_uom </em></td>
						</tr>
						<tr>
							<td><b>Delivery Destination</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->del_desti </em></td>
						</tr>
						<tr>
							<td><b>Shipment Date and Time</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->ship_date $shiptime </em></td>
						</tr> 
						<tr>
							<td><b>Arrival Date and Time</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->arr_date $arrtime </em></td>
						</tr>
						<tr>
							<td><b>Unloading Finish Time</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $utime </em></td>
						</tr>
						<tr>
							<td><b>Delivery Status</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $del_stats </em></td>
						</tr>
						<tr>
							<td><b>DR Remarks</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->dr_remarks </em></td>
						</tr>
						<tr>
							<td><b>Transporter Remarks</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $remarks </em></td>
						</tr>

					</table><br/>

					<b>CUSTOMER FEEDBACK</b><br/><br/>

					<table>
						<tr>
							<td><b>Quantity Accepted</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->qty_acpt </em></td>
						</tr>
						<tr>
							<td><b>Quantity Returned</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->qty_ret </em></td>
						</tr>
						<tr>
							<td><b>Customer Remarks</b></td>
							<td> : </td>
							<td>&nbsp;&nbsp; <em> $rec->cust_rmks </em></td>
						</tr>
					</table>

				";

				$cust_email1 = $rec->Customer_Email;
				$cust_email2 = $rec->Customer_Email2;

				if($cust_email1 == "" AND $cust_email2 == ""){
					$email_receiver = array('jayson.suyat@aaci.ph');
				}elseif($cust_email1 <> "" AND $cust_email2 == ""){
					$email_receiver = array($cust_email1);
				}elseif($cust_email1 == "" AND $cust_email2 <> "" ){
					$email_receiver = array($cust_email2);
				}elseif($cust_email1 <> "" AND $cust_email2 <> ""){
					$email_receiver = array($cust_email1, $cust_email2);
				}

				$subject = 'Delivery Confirmed PO# '.$pnum;
				$this->email_config();
				$this->email->from('webinvty@aaci.ph', 'All Asian Countertrade, Inc.');
				// $this->email->to('jayson.suyat@aaci.ph');
				$this->email->to($email_receiver);
				// $this->email->cc('webinvty@aaci.ph, madona.nabejet@aaci.ph');
				// $this->email->cc('madona.nabejet@aaci.ph');
				$this->email->subject($subject);

				$this->email->message($email_content);

				if(!$this->email->send()){
				return show_error($this->email->print_debugger());
				}

			}
		}

	}


}

?>
