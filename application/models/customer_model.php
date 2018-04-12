<?php
class Customer_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	function customer_login_validation(){
		$this->db->where('cust__id',$this->input->post('uname'));
		$this->db->where('cust__pword',md5($this->input->post('pword')));
		$q=$this->db->get('ocrd');
		if ($q->num_rows()==1){
			return true;
		}
		else{
			return false;
		}
	}

	function customer_login_validation2(){
		$this->db->where('cust__id2',$this->input->post('uname'));
		$this->db->where('cust__pword2',md5($this->input->post('pword')));
		$q=$this->db->get('ocrd');
		if ($q->num_rows()==1){
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

	function customer_registration(){
		//get the VRs clean 
		$username = $this->sanitize($this->input->post('username'));
		$name = $this->sanitize($this->input->post('name'));
		$email = $this->sanitize($this->input->post('email'));
		
		$input = $this->input->post('ccode');
		$cust_code = substr($input, 0, strpos($input, "_"));
		$cust_code2 = $this->input->post('customer_code');

		if($cust_code == ""){
			$cust_code = $cust_code2;
		}else{
			$cust_code = $cust_code;
		}

			$qry=$this->db->get_where('ocrd', array('cust__id'=>"", 'CardCode'=>$cust_code));
			if($qry->num_rows() > 0){
				$pw = $this->sanitize((rand(100000,900000)));

				$d2 = array(
					'memb__id' => $username,
					'memb__username' => $name,
					'memb__pword' => md5($pw),
					'memb__email' => $email,
					'memb__status' => 1,
					'memb_comp' => 'CUSTOMER'
				);
				
				$this->db->insert('ousr',$d2);

				$d = array(
					'cust__id' => $username,
					'cust__username' => $name,
					'cust__pword' => md5($pw),
					'cust__email' => $email,
					'cust__status' => 1
				);

				$this->db->where('CardCode', $cust_code);
				$this->db->update('ocrd',$d);

				$this->email_config();
				$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
				$this->email->to($email);
				$this->email->subject('AACI Web Inventory System Registration');
				$email_content="
					<b>AACI Customer Portal Registration</b>
					
					<table>
						<tr>
							<td><b>Username</b></td>
							<td>:</td>
							<td><em>".$username."</em></td>
						</tr>
						<tr>
							<td><b>Password</b></td>
							<td>:</td>
							<td><em>".$pw."</em></td>
						</tr>
					</table>
					Kindly change your password after you login to the system. <a href='".base_url()."index.php/main/customer_login/".$this->input->post('ccode')."'> Click Here. </a>

				";
				$this->email->message($email_content);
				
				if(!$this->email->send()){
					return show_error($this->email->print_debugger());
				}
				return 'Registration has been successful!';
				
			}else{

				$pw = $this->sanitize((rand(100000,900000)));

				$d2 = array(
					'memb__id' => $username,
					'memb__username' => $name,
					'memb__pword' => md5($pw),
					'memb__email' => $email,
					'memb__status' => 1,
					'memb_comp' => 'CUSTOMER'
				);

				$this->db->insert('ousr',$d2);

				$d = array(
					'cust__id2' => $username,
					'cust__username2' => $name,
					'cust__pword2' => md5($pw),
					'cust__email2' => $email,
					'cust__status2' => 1
				);

				$this->db->where('CardCode', $cust_code);
				$this->db->update('ocrd',$d);

			
				$this->email_config();
				$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
				$this->email->to($email);
				$this->email->subject('AACI Web Inventory System Registration');
				// $email_content = "<p>AACI Web Inventory System Registration</p>
				// 	Username: ".$username." <br />
				// 	Password: ".$pw." <br />
				// 	Kindly change your password after you login to the system. <a href='".base_url()."'>Click here</a>
				// ";

				$email_content="
					<b>AACI Customer Portal Registration</b>
					
					<table>
						<tr>
							<td><b>Username</b></td>
							<td>:</td>
							<td><em>".$username."</em></td>
						</tr>
						<tr>
							<td><b>Password</b></td>
							<td>:</td>
							<td><em>".$pw."</em></td>
						</tr>
					</table>
					Kindly change your password after you login to the system. <a href='".base_url()."index.php/main/customer_login/".$this->input->post('ccode')."'> Click Here. </a>

				";

				$this->email->message($email_content);
				
				if(!$this->email->send()){
					return show_error($this->email->print_debugger());
				}
				return 'Registration has been successfull!';


			}
		
	}

	function customer_limit_reach(){

		$input = $this->input->post('ccode');
		$cust_code = substr($input, 0, strpos($input, "_"));
		$cust_code2 = $this->input->post('customer_code');

		if($cust_code == ""){
			$cust_code = $cust_code2;
		}else{
			$cust_code = $cust_code;
		}

		$qry = $this->db->query("SELECT * FROM ocrd WHERE cust__id <> '' AND cust__id2 <> '' AND CardCode = '$cust_code' ");
		
		if($qry->num_rows() == 1){
			return true;
		}

	}

	function customer_change_password(){

		$old = $this->sanitize($this->input->post('cpword'));
		$new = $this->sanitize($this->input->post('npword'));

		$this->load->library('form_validation');
		$this->form_validation->set_rules('cpword', 'Current Password', 'trim|required');
		$this->form_validation->set_rules('npword', 'New Password', 'trim|required|matches[npword2]');
		$this->form_validation->set_rules('npword2', 'Re-Type New Password', 'trim|required');

		if($this->form_validation->run()){

			$user_id = $this->session->userdata('usr_uname');

			$qry = $this->db->query("SELECT * FROM ocrd WHERE cust__id = '$user_id' ");
			$qry2 = $this->db->query("SELECT * FROM ocrd WHERE cust__id2 = '$user_id' ");

			if($qry->num_rows() > 0){

				$d = array('memb__pword' => md5($new));
				$this->db->where('memb__id',$this->session->userdata('usr_uname'));
				$this->db->update('ousr',$d);

				$d2 = array('cust__pword' => md5($new));
				$this->db->where('cust__id',$this->session->userdata('usr_uname'));
				$this->db->update('ocrd',$d2);

				return "Password has been replaced";
	
			}elseif($qry2->num_rows() > 0){

				$d = array('memb__pword' => md5($new));
				$this->db->where('memb__id',$this->session->userdata('usr_uname'));
				$this->db->update('ousr',$d);

				$d2 = array('cust__pword2' => md5($new));
				$this->db->where('cust__id2',$this->session->userdata('usr_uname'));
				$this->db->update('ocrd',$d2);

				return "Password has been replaced";

			}

		}
	}

	function customer_forgot_password(){

		$input = $this->input->post('ccode');
		$cust_code = substr($input, 0, strpos($input, "_"));

		$email = $this->sanitize($this->input->post('email'));
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		if($this->form_validation->run()){

			$qry = $this->db->query("SELECT * FROM ocrd WHERE CardCode = '$cust_code' AND cust__email = '$email' ");
			$qry2 = $this->db->query("SELECT * FROM ocrd WHERE CardCode = '$cust_code' AND cust__email2 = '$email' ");

			if($qry->num_rows()==1){

				$pw = $this->sanitize((rand(100000,900000)));
				$d = array('cust__pword' => md5($pw));

				$this->db->where('cust__email', $email);
				$update = $this->db->update('ocrd', $d);

				$d2 = array('memb__pword' => md5($pw));

				$this->db->where('memb__email', $email);
				$this->db->update('ousr', $d2);

				$this->email_config();
				$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
				$this->email->to($email);
				$this->email->subject('AACI Web Inventory System Password Reset');

				$data = $qry->row(); 
				$un = $data->cust__id;

				$email_content = "
				<p><b>AACI WEB BASED INVENTORY PASSWORD RESET</b></p>
				<table>
					<tr>
						<td><b>Username</b></td>
						<td><b> : </b></td>
						<td><em>".$un."</em></td>
					</tr>
					<tr>
						<td><b>Password</b></td>
						<td><b> : </b></td>
						<td><em>".$pw."</em></td>
					</tr>
				</table>

				<p>Kindly change your password after you login to the system. <a href='".base_url()."index.php/main/customer_login/".$this->uri->segment(3)."'> Click Here</a></p>

				";

				$this->email->message($email_content);
					
				if(!$this->email->send()){
					return show_error($this->email->print_debugger());
				}
				return 'Your password has been sent to your email.';

			}elseif($qry2->num_rows()==1){
				$pw = $this->sanitize((rand(100000,900000)));
				$d = array('cust__pword2' => md5($pw));

				$this->db->where('cust__email2', $email);
				$this->db->update('ocrd', $d);

				$d2 = array('memb__pword' => md5($pw));

				$this->db->where('memb__email', $email);
				$this->db->update('ousr', $d2);

				$this->email_config();
				$this->email->from('webinvty@aaci.ph', 'AACI WEB Inventory System');
				$this->email->to($email);
				$this->email->subject('AACI Web Inventory System Password Reset');

				$data = $qry->row(); 
				$un = $data->cust__id2;

				$email_content = "
				<p><b>AACI WEB BASED INVENTORY PASSWORD RESET</b></p>
				<table>
					<tr>
						<td><b>Username</b></td>
						<td><b> : </b></td>
						<td><em>".$un."</em></td>
					</tr>
					<tr>
						<td><b>Password</b></td>
						<td><b> : </b></td>
						<td><em>".$pw."</em></td>
					</tr>
				</table>

				<p>Kindly change your password after you login to the system. <a href='".base_url()."index.php/main/customer_login/".$this->uri->segment(3)."'> Click Here</a></p>

				";

				$this->email->message($email_content);
					
				if(!$this->email->send()){
					return show_error($this->email->print_debugger());
				}
				return 'Your password has been sent to your email.';

			}

		}
	}

	function customer_email_count(){
		$input = $this->input->post('ccode');
		$cust_code = substr($input, 0, strpos($input, "_"));

		$qry2 = $this->db->query("SELECT * FROM ocrd WHERE CardCode = '$cust_code' AND cust__id <> '' AND cust__id2 <> '' ");
			if($qry2->num_rows()==1){
				return true;
			}
	}

	function customer_email_check(){

		$email = $this->input->post('email');

		$this->db->where('cust__email', $email);
		$qry = $this->db->get('ocrd');

		if($qry->num_rows() > 0){ 
			return true;
		}

	}
	
	function customer_email_check2(){

		$email = $this->input->post('email');

		$this->db->where('cust__email2', $email);
		$qry = $this->db->get('ocrd');

		if($qry->num_rows() > 0){  
			return true;
		}
	}

	function customer_email_check3(){

		$email = $this->input->post('email');

		$this->db->where('memb__email', $email);
		$this->db->where('memb_comp', 'CUSTOMER');
		$qry = $this->db->get('ousr');

		if($qry->num_rows() > 0){  
			return true;
		}
	}

	function customer_username_check(){

		$uname = $this->input->post('username');

		$this->db->where('cust__id', $uname);
		$qry = $this->db->get('ocrd');

		if($qry->num_rows() > 0){  
			return true;
		}
	}

	function customer_username_check2(){

		$uname = $this->input->post('username');

		$this->db->where('cust__id2', $uname);
		$qry = $this->db->get('ocrd');

		if($qry->num_rows() > 0){  
			return true;
		}
	}
	
	function customer_username_check3(){

		$uname = $this->input->post('username');

		$this->db->where('memb__id', $uname);
		$this->db->where('memb_comp', 'CUSTOMER');
		$qry = $this->db->get('ousr');

		if($qry->num_rows() > 0){  
			return true;
		}
	}

	function customer_name_check(){

		$name = $this->input->post('name');

		$this->db->where('cust__username', $name);
		$qry = $this->db->get('ocrd');

		if($qry->num_rows() > 0){  
			return true;
		}
	}

	function customer_name_check2(){

		$name = $this->input->post('name');

		$this->db->where('cust__username2', $name);
		$qry = $this->db->get('ocrd');

		if($qry->num_rows() > 0){ 
			return true;
		}
	}

	function customer_name_check3(){

		$name = $this->input->post('name');

		$this->db->where('memb__username', $name);
		$this->db->where('memb_comp', 'CUSTOMER');
		$qry = $this->db->get('ousr');

		if($qry->num_rows() > 0){ 
			return true;
		}
	}

	function get_customer_list(){

		$this->db->where('Type', 'C');
		$q = $this->db->get('ocrd');

		if($q->num_rows()==true){
			foreach($q->result_array() as $r){
				$data[$r['CardCode']]=$r['CardName'];
			}
			return $data;
		}
	}

	function customer_code_check(){

		$cust_code = $this->input->post('customer_code');

		$this->db->where('CardCode', $cust_code);
		$qry = $this->db->get('ocrd');

		if($qry->num_rows() <= 0){
			return true;
		}

	}

}

?>