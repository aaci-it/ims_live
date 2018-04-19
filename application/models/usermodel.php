<?php class Usermodel extends CI_model{

	function system_logs() {

		date_default_timezone_set("Asia/Manila");
		$now = time('UP8');
		$datetime =  unix_to_human($now, TRUE);
		$ip = $this->input->ip_address();

		$data = array(
			'log_time'=>$datetime,
			'log_info'=>$this->input->post('uname'),
			'log_IP'=>$ip
		);

		$this->db->insert('sys_logs',$data);
	}

//user
	function login_validation(){
		$this->db->where('memb__id',$this->input->post('uname'));
		$this->db->where('memb__pword',md5($this->input->post('pword')));
		$q=$this->db->get('ousr');
		if ($q->num_rows() > 0){
			$data = $q->row();
			$this->db->where('usercode',$data->memb__id);
			$this->db->where('accessname','Administration');
			$query = $this->db->get('ouar');
			if ($query->num_rows() > 0){ return 'Admin'; }else{ return 'User'; }
		}
		else{
			return false;
		}
	}
	function signin_user(){
		$q=$this->db->get_where('ousr',array('memb__id'=>$this->session->userdata('usr_uname')));
		if($q->num_rows()==1){
			return $q->result();
		}
	}

	function signin_customer(){

		// $ccode_decode = $this->uri->segment(3);
		// $ccode_decode = str_replace(array('-', '_', '~'), array('+', '/', '='), $ccode_decode);
		// $ccode_decode = $this->encrypt->decode($ccode_decode);

		$input = $this->uri->segment(3);
		$cust_code = substr($input, 0, strpos($input, "_"));

		$q=$this->db->get_where('ocrd',array('cust__id'=>$this->session->userdata('usr_uname'), 'CardCode'=>$cust_code));
		$q2=$this->db->get_where('ocrd',array('cust__id2'=>$this->session->userdata('usr_uname'), 'CardCode'=>$cust_code));
		if($q->num_rows()==1){
			return $q->result();
		}elseif($q2->num_rows()==1){
			return $q2->result();
		}
	}

	function user_delete(){
		$this->db->where('memb__id', $this->uri->segment(3));
		$this->db->delete('ousr');
	}

//masterdata
	function records_get(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		if ($tokens[sizeof($tokens)-2] == 'businesspartner_edit' or $tokens[sizeof($tokens)-2] == 'businesspartner_add_item'){
			$q=$this->db->get_where('ocrd',array('CardCode'=>$get));
		}
		elseif ($tokens[sizeof($tokens)-2] == 'item_edit'){
			$q=$this->db->get_where('ocmt',array('comm__id 	'=>$get));
		}
		elseif ($tokens[sizeof($tokens)-3] == 'businesspartner_remove_item'){
			$get = $tokens[sizeof($tokens)-2];
			$q=$this->db->get_where('ocrd',array('CardCode'=>$get));
		}
		else{
		$q=$this->db->get_where('mwhr',array('wh_code'=>$get));
		}
		if ($q->num_rows()==1){
			return $q->result();
		}
	}
	
	function get_doctype_Out(){
		$this->db->order_by('name');
		$q=$this->db->get_where('aodt',array('status'=>1,'type'=>'Delivery Out'));
		foreach ($q->result_array() as $r){
			$data[$r['name']]=$r['name'];
		}
		return $data;
	}

	function get_doctype_In(){
		$this->db->order_by('name');
		$q=$this->db->get_where('aodt',array('status'=>1,'type'=>'Delivery In'));
		foreach ($q->result_array() as $r){
			$data[$r['name']]=$r['name'];
		}
		return $data;
	}

	function get_doctype(){
		$this->db->order_by('name');
		$q=$this->db->get_where('aodt',array('status'=>1));
		foreach ($q->result_array() as $r){
			$data[$r['name']]=$r['name'];
		}
		return $data;
	}
	function get_deltype(){
		$q=$this->db->get_where('dtdr',array('delstatus'=>1));
		foreach($q->result_array() as $r){
			$data[$r['delcode']]=$r['deltype'];
		}
		return $data;
	}
	//Old query
	 function get_bplistDdown(){
		$q = $this->db->get_where('ocrd',array('Status'=>1));
		foreach($q->result_array() as $r){
		$data[$r['CardCode']]=$r['CardName'];
		}
		return $data;
	 }

	function get_bplistDdown_SAP(){
		$db2=$this->load->database('db3',TRUE);
		$q=$db2->query("
					SELECT a.CardType,a.CardName,a.CardCode,a.Type 
					FROM (SELECT
                      CardCode, CardName,CardType,
                              CASE  
                              WHEN QryGroup14 = 'Y' THEN 'OPEN MARKET' 
                              WHEN QryGroup15 = 'Y' THEN 'INDUSTRIAL'
                              ELSE '' END AS 'TYPE'
                      FROM OCRD WHERE CardType = 'C'
					  AND CardName <> 'null' 
                      UNION ALL
                      SELECT CardCode,CardName,CardType, '' 'TYPE'
                      FROM OCRD WHERE CardType = 'S'  AND GroupCode = '127'
                      UNION ALL
                      SELECT WhsCode as CardCode, WhsName AS CardName, 'W' 'CardType', '' 'TYPE' 
                      from OWHS a
					  left join UFD1 c on c.FldValue=a.U_Whse_Type
					  where a.U_Whse_Type in ('M','W', 'V','P','T')
					) a ORDER BY a.CardType,a.CardName,a.CardCode,a.Type
		");


		// $q=$db2->query("
		// 			SELECT a.CardType,a.CardName,a.CardCode,a.Type 
		// 			FROM (SELECT
  //                     CardCode, CardName,CardType,
  //                             CASE 
  //                             WHEN QryGroup14 = 'Y' THEN 'OPEN MARKET' 
  //                             WHEN QryGroup15 = 'Y' THEN 'INDUSTRIAL'
  //                             ELSE '' END AS 'TYPE'
  //                     FROM OCRD WHERE CardType = 'C'
		// 			  AND CardName <> 'null' 
  //                     UNION ALL
  //                     SELECT WhsCode as CardCode, WhsName AS CardName, 'W' 'CardType', '' 'TYPE' 
  //                     from OWHS a
		// 			  left join UFD1 c on c.FldValue=a.U_Whse_Type
		// 			  where a.U_Whse_Type in ('M','W', 'V','P','T')
		// 			) a ORDER BY a.CardType,a.CardName,a.CardCode,a.Type
		// ");

		if($q->num_rows()==true){
			foreach($q->result_array() as $r){
				$data[$r['CardCode']]=$r['CardType']."-".$r['CardName'];
			}
			return $data;
		}
	}

	function get_bplistDdown_SAP_Customer(){
		$db2=$this->load->database('db3',TRUE);

		// $q=$db2->query("
		// 			SELECT a.CardType,a.CardName,a.CardCode,a.Type 
		// 			FROM (SELECT
  //                     CardCode, CardName,CardType,
  //                             CASE 
  //                             WHEN QryGroup14 = 'Y' THEN 'OPEN MARKET' 
  //                             WHEN QryGroup15 = 'Y' THEN 'INDUSTRIAL'
  //                             ELSE '' END AS 'TYPE'
  //                     FROM OCRD WHERE CardType = 'C'
		// 			  AND CardName <> 'null' 
		// 			) a ORDER BY a.CardType,a.CardName,a.CardCode,a.Type
		// ");

		$q=$db2->query("
					SELECT
                      CardCode, CardName,CardType,
                              CASE 
                              WHEN QryGroup14 = 'Y' THEN 'OPEN MARKET' 
                              WHEN QryGroup15 = 'Y' THEN 'INDUSTRIAL'
                              ELSE '' END AS 'TYPE'
                      FROM OCRD WHERE CardType = 'C'
					  AND CardName <> 'null'
					  ORDER BY CardName 

		");

		if($q->num_rows()==true){
			foreach($q->result_array() as $r){
				$data[$r['CardCode']]=$r['CardName'];
			}
			return $data;
		}
	}

	// OLD SCRIPT
	// function get_bplistDdown_SAP_Warehouse(){
	// 	$db2=$this->load->database('db2',TRUE);

	// 	// $q=$db2->query("
	// 	// 			SELECT a.CardType,a.CardName,a.CardCode,a.Type 
	// 	// 			FROM (
 //  //                     SELECT WhsCode as CardCode, WhsName AS CardName, 'W' 'CardType', '' 'TYPE' 
 //  //                     from OWHS a
	// 	// 			  left join UFD1 c on c.FldValue=a.U_Whse_Type
	// 	// 			  where a.U_Whse_Type in ('M','W', 'V','P','T')
	// 	// 			) a ORDER BY a.CardType,a.CardName,a.CardCode,a.Type
	// 	// ");

	// 	$q=$db2->query("
 //                      SELECT WhsCode as CardCode, WhsName AS CardName, 'W' 'CardType', '' 'Type' 
 //                      from OWHS a
	// 				  left join UFD1 c on c.FldValue=a.U_Whse_Type
	// 				  where a.U_Whse_Type in ('M','W', 'V','P','T')
	// 				  ORDER BY CardType, CardName, CardType, Type
	// 	");

	// 	if($q->num_rows()==true){
	// 		foreach($q->result_array() as $r){
	// 			$data[$r['CardCode']]=$r['CardName'];
	// 		}
	// 		return $data;
	// 	}
	// }

	function get_bplistDdown_SAP_Warehouse(){
		$db3 = $this->load->database('db3',TRUE);

		// $q=$db2->query("
		// 			SELECT a.CardType,a.CardName,a.CardCode,a.Type 
		// 			FROM (
  //                     SELECT WhsCode as CardCode, WhsName AS CardName, 'W' 'CardType', '' 'TYPE' 
  //                     from OWHS a
		// 			  left join UFD1 c on c.FldValue=a.U_Whse_Type
		// 			  where a.U_Whse_Type in ('M','W', 'V','P','T')
		// 			) a ORDER BY a.CardType,a.CardName,a.CardCode,a.Type
		// ");

		$q=$db3->query("
                      SELECT WhsCode as CardCode, WhsName AS CardName, 'W' 'CardType', '' 'Type' 
                      from OWHS a
					  ORDER BY CardType, CardName, CardType, Type
		");

		if($q->num_rows()==true){
			foreach($q->result_array() as $r){
				$data[$r['CardCode']]=$r['CardName'];
			}
			return $data;
		}
	}

	function get_bplistDdown_SAP_Supplier(){
		$db2=$this->load->database('db3',TRUE);

		$q=$db2->query("
                      SELECT CardCode,CardName,CardType, '' 'TYPE'
                      FROM OCRD WHERE CardType = 'S'  AND GroupCode = '127'
                      ORDER BY CardName
		");

		if($q->num_rows()==true){
			foreach($q->result_array() as $r){
				$data[$r['CardCode']]=$r['CardName'];
			}
			return $data;
		}
	}

	function item_get_SAP(){
		$db2=$this->load->database('db2',TRUE);
		$q=$db2->query("SELECT
			ItemName,ItemCode
			FROM OITM 
			WHERE ItmsGrpCod='105'
			AND U_Commodity = '02'
			ORDER BY ItemName");
		if($q->num_rows()==true){
			foreach($q->result_array() as $r){
				$data[$r['ItemCode']]=$r['ItemName'];
			}
			return $data;
		}
	}
	function item_get(){
		$this->db->order_by('comm__name');
		$q=$this->db->get_where('ocmt', array('status'=>1));
		foreach($q->result_array() as $r){
			$data[$r['comm__id']]=$r['comm__name'];
		}
		return $data;
	}
	function bp_list(){
		// $this->db->order_by('CardName');
		// $q=$this->db->get('ocrd');
		// if($q->num_rows()==true){
			// return $q->result();
		// }
		$db2=$this->load->database('db2',TRUE);
		$q=$db2->query("SELECT
			CardCode, CardName, 
				CASE 
				WHEN QryGroup14 = 'Y' THEN 'OPEN MARKET' 
				WHEN QryGroup15 = 'Y' THEN 'INDUSTRIAL'
				ELSE '' END AS 'TYPE'
			FROM OCRD WHERE CardType = 'C'");
		if($q->num_rows()==true){
			return $q->result();
		}
	}

	function bp_upload(){
		$bp = $this->input->post('bpcode');
		$db2=$this->load->database('db2',TRUE);
		$q1=$this->db->query("SELECT * FROM ocrd WHERE CardCode='$bp'");
		if ($q1->num_rows == false){

			$q=$db2->query("
				SELECT DISTINCT a.CardCode,a.CardType,a.CardName,a.Type
					FROM (SELECT
                      CardCode, CardName,CardType,
                              CASE 
                              WHEN QryGroup14 = 'Y' THEN 'OPEN MARKET' 
                              WHEN QryGroup15 = 'Y' THEN 'INDUSTRIAL'
                              ELSE '' END AS 'TYPE'
                      FROM OCRD WHERE CardType = 'C'
                      UNION ALL
                      SELECT CardCode,CardName,CardType, '' 'TYPE'
                      FROM OCRD WHERE CardType = 'S'  AND GroupCode = '127'
                      UNION ALL
                      SELECT WhsCode as CardCode, WhsName AS CardName, 'W' 'CardType', '' 'TYPE' 
                      from OWHS a
					  left join UFD1 c on c.FldValue=a.U_Whse_Type
					  where a.U_Whse_Type in ('M','W', 'V','P','T')
					) a 
				WHERE a.CardCode='$bp' ORDER BY a.CardCode,a.CardType,a.CardName,a.Type
			");
			if($q->num_rows()==true){
				foreach($q->result() as $r){
					$data = array(
						'CardCode'=>$r->CardCode,
						'CardName'=>$r->CardName,
						'status'=>'1',
						'type'=>$r->CardType,
						'CusType'=>$r->Type
					);
					$this->db->insert('ocrd',$data);
				}
			}
		}
	}

	function bp_upload_sap(){
		$bp = $this->input->post('cust_code');
		$db2=$this->load->database('db2',TRUE);
		$q1=$this->db->query("SELECT * FROM ocrd WHERE CardCode='$bp'");
		if ($q1->num_rows == false){

			$q=$db2->query("
				SELECT DISTINCT a.CardCode,a.CardType,a.CardName,a.Type
					FROM (SELECT
                      CardCode, CardName,CardType,
                              CASE 
                              WHEN QryGroup14 = 'Y' THEN 'OPEN MARKET' 
                              WHEN QryGroup15 = 'Y' THEN 'INDUSTRIAL'
                              ELSE '' END AS 'TYPE'
                      FROM OCRD WHERE CardType = 'C'
                      UNION ALL
                      SELECT CardCode,CardName,CardType, '' 'TYPE'
                      FROM OCRD WHERE CardType = 'S'  AND GroupCode = '127'
                      UNION ALL
                      SELECT WhsCode as CardCode, WhsName AS CardName, 'W' 'CardType', '' 'TYPE' 
                      from OWHS a
					  left join UFD1 c on c.FldValue=a.U_Whse_Type
					  where a.U_Whse_Type in ('M','W', 'V','P','T')
					) a 
				WHERE a.CardCode='$bp' ORDER BY a.CardCode,a.CardType,a.CardName,a.Type
			");
			if($q->num_rows()==true){
				foreach($q->result() as $r){
					$data = array(
						'CardCode'=>$r->CardCode,
						'CardName'=>$r->CardName,
						'status'=>'1',
						'type'=>$r->CardType,
						'CusType'=>$r->Type
					);
					$this->db->insert('ocrd',$data);
				}
			}
		}
	}

	function bp_code_validate(){
		$q=$this->db->get_where('ocrd',array('CardCode'=>$this->input->post('bpcode')));
		if ($q->num_rows()==1){
			return $q->result();
		}
	}
	function bp_name_validate(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$this->db->where('CardCode !=',$get);
		$q=$this->db->get_where('ocrd',array('CardName'=>$this->input->post('bpname')));
		if($q->num_rows()==1){
			return $q->result();
		}
	}
	function bp_create(){
		$data=array(
			'CardCode'=>$this->input->post('bpcode'),
			'CardName'=>$this->input->post('bpname'),
			'Status'=>1,
			'last_user'=>$this->session->userdata('usr_uname')
		);
		$this->db->insert('ocrd',$data);
	}
		function bp_edit_code_validate(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$this->db->where('CardCode !=',$get);
		$q=$this->db->get_where('ocrd',array('CardCode'=>$this->input->post('bpcode')));
		if ($q->num_rows()==1){
			return $q->result();
		}
	}
	function bp_edit(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$now = time('UP8');
		$datetime =  unix_to_human($now, TRUE);
		if ($this->input->post('active') == true){
			$active = 1;
		}
		else{
			$active=0;
		}
		$data = array(
			'CardCode'=>$this->input->post('bpcode'),
			'CardName'=>$this->input->post('bpname'),
			'Status'=>$active,
			'last_user'=>$this->session->userdata('usr_uname'),
			'last_updated'=>$datetime
		);
		$this->db->where('CardCode',$get);
		$this->db->update('ocrd',$data);
	}
	//*
	function bp_list_item(){
		$tokens = explode('/', current_url());
		if ($tokens[sizeof($tokens)-2] == 'businesspartner_remove_item'){
			$get = $tokens[sizeof($tokens)-1];
		}
		else{
			$get = $tokens[sizeof($tokens)-1];
		}
		$q = $this->db->query("SELECT * 
			FROM mbir a 
			INNER JOIN ocmt b ON a.comm__id = b. comm__id
			WHERE a.bi_status = 1 AND a.CardCode='".$get."'" );
		if ($q->num_rows==true){
			return $q->result();
		}
	}
	//*
	function bp_item_validation(){
		 $tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$this->db->where('CardCode',$get);
		$this->db->where('comm__id',$this->input->post('bpitem'));
		$this->db->where('bi_status',1);
		$q=$this->db->get('mbir');
		if ($q->num_rows ==TRUE){
			return $q->result();
		}
	}
	//*
	function bp_add_item(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$data = array(
			'CardCode'=>$get,
			'comm__id'=>$this->input->post('bpitem'),
			'bi_status'=>1,
			'bi_createby'=>$this->session->userdata('usr_uname')
		);
		$this->db->insert('mbir',$data);
	}
	//*
	function bp_remove_item(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$data=array('bi_status'=>0);
		$this->db->where('bi_code',$get);
		$this->db->update('mbir',$data);
	}
	function item_list(){
		$this->db->order_by('comm__name');
		// $q=$this->db->get('ocmt');

		$q = $this->db->query("SELECT *, b.Description, c.Description as item_type, d.Description as item_subtype FROM ocmt a
								LEFT JOIN item_group b ON b.Code = a.item_group
								LEFT JOIN item_type c ON c.Code = a.item_type
								LEFT JOIN item_subtype d ON d.Code = a.item_subtype
								ORDER BY a.comm__name");

		if ($q->num_rows() == true){
			return $q->result();
		}
	}

	function item_code_validation(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$q=$this->db->get_where('ocmt',array('comm__id'=>$this->input->post('icode')));
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	function item_code2_validation(){
		$q=$this->db->get_where('ocmt',array('comm__code2'=>$this->input->post('code2')));
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	function item_name_validation(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$q=$this->db->get_where('ocmt',array('comm__name'=>$this->input->post('iname')));
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	function item_edit_name_validation(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$this->db->where('comm__id !=',$get);
		$q=$this->db->get_where('ocmt',array('comm__name'=>$this->input->post('iname')));
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	function item_edit_code_validation(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$this->db->where('comm__id !=',$get);
		$q=$this->db->get_where('ocmt',array('comm__id'=>$this->input->post('icode')));
		if ($q->num_rows() == true){
			return $q->result();
		}
	}

	function item_create(){

		$grp_name = $this->input->post('new_grpname');

		$this->db->where('Description', $grp_name);
		$qry = $this->db->get('item_group');

		if($qry->num_rows() > 0){

			// INSERT TO OCMT TABLE
			$data=array(
				'comm__id'=>$this->input->post('icode'),
				'comm__name'=>$this->input->post('iname'),
				'comm__code2'=>$this->input->post('code2'),
				'item_group'=>$this->input->post('item_group'),
				'item_type'=>$this->input->post('item_type'),
				'item_subtype'=>$this->input->post('item_subtype'),
				'comm__grp'=>105,
				'status'=>$this->input->post('item_status')
			);

			$this->db->insert('ocmt',$data);

		}else{
			$q = $this->db->query("SELECT MAX(Code) AS Code FROM item_group");
			if($q->num_rows == TRUE){
				foreach($q->result_array() as $r){

					$num = mb_substr($r['Code'], -2);

					if($num[0] == '0'){
						$num = mb_substr($num, -1);
						(int)$num;
						$num+=1;
						$series = "IG_0".(string)$num;
					}else{
						(int)$num;
						$num+=1;
						$series = "IG_".(string)$num;
					}

					$d2 = array(
						'Code'=>$series,
						'Description'=>$grp_name
					);
				}

				$this->db->insert('item_group', $d2);


				// INSERT TO OCMT TABLE
				$data=array(
					'comm__id'=>$this->input->post('icode'),
					'comm__name'=>$this->input->post('iname'),
					'comm__code2'=>$this->input->post('code2'),
					'item_group'=>$series,
					'item_type'=>$this->input->post('item_type'),
					'item_subtype'=>$this->input->post('item_subtype'),
					'comm__grp'=>105,
					'status'=>1
				);

				$this->db->insert('ocmt',$data);

			}
		}


	}

	function item_edit(){

		$grp_name = $this->input->post('new_grpname');

		$this->db->where('Description', $grp_name);
		$qry = $this->db->get('item_group');

		if($qry->num_rows() > 0){

			// UPDATE OCMT TABLE
			$data = array(
				'comm__id'=>$this->input->post('icode'),
				'comm__name'=>$this->input->post('iname'),
				'comm__code2'=>$this->input->post('code2'),
				'item_group'=>$this->input->post('item_group'),
				'item_type'=>$this->input->post('item_type'),
				'item_subtype'=>$this->input->post('item_subtype'),
				'status'=>$this->input->post('item_status')
			);
			$this->db->where('comm__id',$this->input->post('icode'));
			$this->db->update('ocmt',$data);

		}else{
			$q = $this->db->query("SELECT MAX(Code) AS Code FROM item_group");
			if($q->num_rows == TRUE){
				foreach($q->result_array() as $r){

					$num = mb_substr($r['Code'], -2);

					if($num[0] == '0'){
						$num = mb_substr($num, -1);
						(int)$num;
						$num+=1;
						$series = "IG_0".(string)$num;
					}else{
						(int)$num;
						$num+=1;
						$series = "IG_".(string)$num;
					}

					$d2 = array(
						'Code'=>$series,
						'Description'=>$grp_name
					);
				}

				$this->db->insert('item_group', $d2);

				// UPDATE OCMT TABLE
				$data = array(
					'comm__id'=>$this->input->post('icode'),
					'comm__name'=>$this->input->post('iname'),
					'comm__code2'=>$this->input->post('code2'),
					'item_group'=>$series,
					'item_type'=>$this->input->post('item_type'),
					'item_subtype'=>$this->input->post('item_subtype')
				);

				$this->db->where('comm__id',$this->input->post('icode'));
				$this->db->update('ocmt',$data);

			}
		}
		
	}

	function check_if_item_has_trans(){

		$item_id = $this->uri->segment(3);

		// $this->db->where('item_id', $item_id);
		// $qry = $this->db->get_where('whir', array('item_id'=>$item_id));
		$qry = $this->db->query("SELECT * FROM whir WHERE item_id = '$item_id' ");

		if($qry->num_rows() > 0){
			return true;
		}

	}

	function get_item_type(){

		$qry = $this->db->get('item_type');

		foreach($qry->result_array() as $r){
			$data[$r['Code']] = $r['Description']; 
		}
		return $data;
	}

	function get_item_subtype(){

		$qry = $this->db->get('item_subtype');

		foreach($qry->result_array() as $r){
			$data[$r['Code']] = $r['Description'];
		}
		return $data;
	}

	function check_item_balance(){

		$url = $this->uri->segment(3);

		$q = $this->db->query("SELECT a.comm__id, a.comm__code2, a.comm__name,b.sqty, c.tqty ,d.rqty
			FROM ocmt a
			LEFT OUTER JOIN (SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as sqty 
				FROM whir INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=0 
					AND whir.wi_status=1 
					AND whir.wi_approvestatus=1
				GROUP BY item_id)b 
			ON a.comm__id = b.item_id
			LEFT OUTER JOIN(SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as tqty 
				FROM whir INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=1 
					AND whir.wi_status=1 
				GROUP BY item_id)c 
			ON a.comm__id = c.item_id
			LEFT OUTER JOIN(
				SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as rqty 
				FROM whir 
				INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=2 
					AND whir.wi_status=1 
					AND wi_status=1 
				GROUP BY item_id
			)d 
			ON a.comm__id = d.item_id
			WHERE a.comm__id = '$url' ");
		if ($q->num_rows() == true){
			return $q->result();
		}
	}

	function item_delete(){

		$this->db->where('comm__id', $this->uri->segment(3));
		$this->db->delete('ocmt');

	}

	function item_group(){
		$qry = $this->db->get('item_group');

		if($qry->num_rows()==true){
			foreach($qry->result_array() as $r){
				$data[$r['Code']]=$r['Description'];
			}
			return $data;
		}
	}
	
	function wh_list(){
		$q=$this->db->get('mwhr');
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	function warehouse_user_access(){
		$q=$this->db->query("SELECT a.wh_name FROM mwhr a 
			LEFT JOIN ouaw b ON a.wh_name = b.accessname
			where a.wh_status=1 and b.usercode='".$this->session->userdata('usr_uname')."' and b.status=1
		");
		if($q->num_rows == true){
			return $q->result();
		}
	}
	function whname_select($get){
		$q=$this->db->get_where('mwhr',array('wh_code'=>$get,'wh_status'=>1));
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	function wh_name_validation(){
		$this->db->where('wh_name',$this->input->post('whname'));
		$q=$this->db->get('mwhr');
		if($q->num_rows() > 0){
			return true;
		}
	}
	function wh_sapcode_validation_update(){

		if($this->input->post('whsapcode') <> ""){
			$wh_name = $this->input->post('whsapcode');

			$this->db->where('wh_sapcode', $wh_name);
			$this->db->where('wh_status',1);
			$q=$this->db->get('mwhr');
			if($q->num_rows() > 0){
				return true;
			}

		}

		
	}
	function wh_sapcode_validation(){

		$this->db->where('wh_sapcode', $this->input->post('whsapcode'));
		$this->db->where('wh_status',1);
		$q=$this->db->get('mwhr');
		if($q->num_rows() > 0){
			return true;
		}
	}
	function wh_create(){
		$data=array(
			'wh_name'=>$this->input->post('whname'),
			'wh_addr'=>$this->input->post('whaddr'),
			'wh_status'=>1,
			'wh_sapcode'=>$this->input->post('whsapcode'),
			'wh_createby'=>$this->session->userdata('usr_uname'),
			'wh_territory'=>$this->input->post('terri'),
			'wh_remarks'=>$this->input->post('remarks')
		);
		$q=$this->db->insert('mwhr',$data);
	}
	function wh_edit(){
		date_default_timezone_set("Asia/Manila");
		$cdate = date('Y-m-d');
		if ($this->input->post('active') == true){
			$active = 1;
		}
		else{
			$active=0;
		}
		$data = array(
			'wh_addr'=>$this->input->post('whaddr'),
			'wh_status'=>$active,
			'wh_sapcode'=>$this->input->post('whsapcode'),
			'wh_updateby'=>$this->session->userdata('usr_uname'),
			'wh_updatedatetime'=>$cdate,
			'wh_territory'=>$this->input->post('terri')

		);
		$this->db->where('wh_name',$this->input->post('whname'));
		$this->db->update('mwhr',$data);
	}

	function warehouse_delete(){

		$this->db->where('wh_code', $this->uri->segment(3));
		$this->db->delete('mwhr');

	}

	function get_wname(){

		$this->db->where('wh_code', $this->uri->segment(3));
		$qry = $this->db->get('mwhr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function check_whouse_trans(){

		$wcode = $this->uri->segment(3);

		$this->db->where('wh_code', $wcode);
		$qry = $this->db->get('mwhr');

		foreach($qry->result_array() as $r){

			$wname = $r['wh_name'];
			$qry2 = $this->db->query("SELECT * FROM whir WHERE wh_name = '$wname' ");

			if($qry2->num_rows() > 0){
				return $qry2->result();
				return true;
			}

		}

	}

	function wh_add_emailadd(){
		$this->load->helper('email');
		if (valid_email($this->input->post('emailadd'))){
			$q1=$this->db->get_where('oedr',array(
				'wh_code'=>$this->input->post('whcode'),
				'type'=>$this->input->post('etype'),
				'emailadd'=>$this->input->post('emailadd'),
				'status'=>1
			));
			if ($q1->num_rows == false){
				$data = array(
					'wh_code'=>$this->input->post('whcode'),
					'type'=>$this->input->post('etype'),
					'emailadd'=>$this->input->post('emailadd'),
					'status'=>1,
					'cby'=>$this->session->userdata('usr_uname')
				);
				$q=$this->db->insert('oedr',$data);
			}
			else{
				$data = array('status'=>0);
				$this->db->where(
					array(
						'wh_code'=>$this->input->post('whcode'),
						'type'=>$this->input->post('etype'),
						'emailadd'=>$this->input->post('emailadd'),
						'status'=>1
					)
				);
				$this->db->update('oedr',$data);
			}
		}
	}
	function email_list_active(){
		$this->db->order_by('type');
		$q=$this->db->get_where('oedr',array(
			'wh_code'=>$this->uri->segment(3),
			'status'=>1)
		);
		if($q->num_rows() == true){
			return $q->result();
		}
	}
	//main
	function get_warehouse($get){
		$q=$this->db->get_where('mwhr',array('wh_code'=>$get,'wh_status'=>1));
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	// function get_warehouse_active(){
		// $this->db->order_by('wh_name');
		// $q=$this->db->get_where('mwhr',array('wh_status'=>1));
		// if ($q->num_rows() == true){
			// return $q->result();
		// }
	// }
	function bp_list_active(){
		$q=$this->db->get_where('ocrd',array('Status'=>1));
		// foreach($q->result_array() as $r){
			// $data[$r['CardCode']]=$r['CardName'];
		// }
		if($q->num_rows == true){
			return $q->result();
		}
		return $data;
	}
	function wh_active(){
		//$q=$this->db->get_where('mwhr',array('wh_status'=>1));
		$q=$this->db->query("SELECT * FROM mwhr a 
			LEFT JOIN ouaw b ON a.wh_name = b.accessname
			where a.wh_status=1 and b.usercode='".$this->session->userdata('usr_uname')."' and b.status=1
		");
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	function wh_list_active(){
		$q = $this->db->query("SELECT a.wh_code,a.wh_name,
CASE WHEN b.sqty IS NULL THEN 0 ELSE b.sqty END AS delin,
CASE WHEN c.tqty IS NULL THEN 0 ELSE c.tqty END AS delout,
CASE WHEN d.rqty IS NULL THEN 0 ELSE d.rqty END AS delres

			FROM mwhr a
			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as sqty 
				FROM whir 
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				where wi_type=0 AND wi_status=1 AND wi_approvestatus=1  group by wh_name
			)b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as tqty 
				FROM whir 
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				WHERE whir.wi_type=1 
					AND whir.wi_status=1 
					AND whir.wi_approvestatus=1  
				group by wh_name
			)c ON a.wh_name=c.wh_name
			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as rqty 
				FROM whir
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				where wi_type=2 
					AND wi_status=1    
				group by wh_name
			)d ON a.wh_name=d.wh_name
			WHERE wh_status=1 AND hm_status=0"
		);
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	function wh_item($get){
		$q=$this->db->get_where('whir',array('wh_name'=>$get));
		if($q->num_rows() == true){
			return $q->result();
		}
	}
	function get_item_record($get){
		$q = $this->db->query("SELECT a.comm__id, a.comm__code2, a.comm__name,b.sqty, c.tqty ,d.rqty
			FROM ocmt a
			LEFT OUTER JOIN (SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as sqty 
				FROM whir INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=0 
					AND whir.wi_status=1 
					AND whir.wi_approvestatus=1
					AND mwhr.wh_code='".$get."'
				GROUP BY item_id)b 
			ON a.comm__id = b.item_id
			LEFT OUTER JOIN(SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as tqty 
				FROM whir INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=1 
					AND whir.wi_status=1 
					AND mwhr.wh_code='".$get."'
				GROUP BY item_id)c 
			ON a.comm__id = c.item_id
			LEFT OUTER JOIN(
				SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as rqty 
				FROM whir 
				INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=2 
					AND whir.wi_status=1 
					AND wi_status=1 
					AND mwhr.wh_code='".$get."'
				GROUP BY item_id
			)d 
			ON a.comm__id = d.item_id");
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	
	
	
	function dspr_begbal(){
		$date = 'deldate';
		$today = date('Y-m-d'); 
		$date1 = str_replace('-', '/', $today);
		$yesterday = date('Y-m-d',strtotime($date1 . "-1 days"));
		$yesterday = $yesterday.' 23:59:59';
		//$yesterday = '2016-05-30 23:59:59';
		$whse = $this->input->post('whouse');

		if($this->input->post('Search')){
			$q = $this->db->query("
				SELECT 
					a.comm__id 'ID',
					a.comm__name 'Dscription',
					((SELECT IFNULL(SUM(`wi_itemqty`),0) FROM `whir` WHERE `wh_name` = '$whse' AND `wi_type` = 0 AND `wi_status` = 1 AND `wi_approvestatus` = 1 AND `item_id` = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday') - 
						((SELECT IFNULL(SUM(`wi_itemqty`),0) FROM `whir` WHERE `wh_name` = '$whse' AND `wi_type` = 1 AND `wi_status` = 1 AND `wi_approvestatus` = 1 AND `item_id` = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday') + 
							(SELECT IFNULL(SUM(`wi_itemqty`),0) FROM `whir` WHERE `wh_name` = '$whse' AND `wi_type` = 2 AND `wi_status` = 1 AND `wi_approvestatus` = 1 AND `item_id` = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday') ) ) AS  'BegBal'
				FROM ict_ims.ocmt a
				
			");				
			if ($q->num_rows() == true){
				return $q->result();
			}
		}
		else{
			return false;
		}
	}

	function dspr_rr(){
		$date = 'deldate';
		$today = date('Y-m-d'); 
		$date1 = str_replace('-', '/', $today);
		$yesterday = date('Y-m-d',strtotime($date1 . "-1 days"));
		$yesterday = $yesterday.' 23:59:59';
		//$yesterday = '2016-05-30 23:59:59';
		$whse = $this->input->post('whouse');

		if($this->input->post('Search')){
			$q = $this->db->query("
				SELECT 
					(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'RR' ) + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'RR' )  AS 'RR'
				FROM ict_ims.ocmt a
			");				
			if ($q->num_rows() == true){
				return $q->result();
			}
		}
		else{
			return false;
		}
	}

	// function dspr(){
	// 	$date = 'deldate';
	// 	$today = date('Y-m-d'); 
	// 	$date1 = str_replace('-', '/', $today);
	// 	$yesterday = date('Y-m-d',strtotime($date1 . "-1 days"));
	// 	//$yesterday = '2016-05-30 23:59:59';
	// 	$whse = $this->input->post('whouse');

	// 	if($this->input->post('Search')){
	// 		$q = $this->db->query("
	// 			SELECT 
	// 			a.comm__id 'ID',
	// 			a.comm__name 'Dscription',
	// 			(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND deldate <= '$yesterday' ) - ( (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wh_name = '$whse'  AND item_id = a.comm__id AND deldate <= '$yesterday' ) + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir  WHERE wi_type=2 AND wi_status=1 AND wi_status=1 AND wh_name = '$whse' AND item_id = a.comm__id AND deldate <= '$yesterday' ) ) AS  'BegBal', 
	// 			(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND deldate = '$today' AND wi_reftype = 'RR' ) + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND deldate = '$today' AND wi_reftype2 = 'RR' )  AS 'RR',
	// 			( (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wh_name = '$whse'  AND item_id = a.comm__id AND deldate = '$today' AND wi_reftype = 'DR') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir  WHERE wi_type=2 AND wi_status=1 AND wh_name = '$whse' AND item_id = a.comm__id AND deldate = '$today' AND wi_reftype = 'DR') ) + ( (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wh_name = '$whse'  AND item_id = a.comm__id AND deldate = '$today' AND wi_reftype2 = 'DR') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir  WHERE wi_type=2 AND wi_status=1 AND wi_status=1 AND wh_name = '$whse' AND item_id = a.comm__id AND deldate = '$today' AND wi_reftype2 = 'DR') ) AS 'DR',
	// 			( (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wh_name = '$whse'  AND item_id = a.comm__id AND deldate = '$today' AND wi_reftype = 'WIS') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir  WHERE wi_type=2 AND wi_status=1 AND wh_name = '$whse' AND item_id = a.comm__id AND deldate = '$today' AND wi_reftype = 'WIS') ) + ( (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wh_name = '$whse'  AND item_id = a.comm__id AND deldate = '$today' AND wi_reftype2 = 'WIS') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir  WHERE wi_type=2 AND wi_status=1 AND wi_status=1 AND wh_name = '$whse' AND item_id = a.comm__id AND deldate = '$today' AND wi_reftype2 = 'WIS') )  AS 'WIS',
	// 			(SELECT IFNULL(sum(wi_itemqty),0)
	// 				FROM whir
	// 				WHERE wi_type=1
	// 					AND wi_reftype = 'DO'
	// 					AND wi_status=1
	// 					AND wi_approvestatus=1
	// 					AND wh_name = '$whse' 
	// 					AND item_id = a.comm__id 
	// 					AND deldate = '$today'
	// 				OR wi_type=1
	// 					AND wi_reftype2 = 'DO'
	// 					AND wi_status=1
	// 					AND wi_approvestatus=1
	// 					AND wh_name = '$whse' 
	// 					AND item_id = a.comm__id 
	// 					AND deldate = '$today') AS 'UDO'
	// 			FROM ict_ims.ocmt a
				
	// 		");				
	// 		if ($q->num_rows() == true){
	// 			return $q->result();
	// 		}
	// 	}
	// 	else{
	// 		return false;
	// 	}
	// }
	
	function wh_itemqty_validation(){
		$item = $_POST['whitem'];
		$qty = $_POST['whqty'];
		$get = $_POST['wh'];
		$q=$this->db->query("
			SELECT a.comm__id,a.comm__name,b.sqty,c.tqty,d.rqty
			FROM ocmt a
			LEFT OUTER JOIN (
				SELECT item_id,sum(wi_itemqty) as sqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=0 AND wi_approvestatus=1 AND wi_status=1 
				GROUP BY item_id
			)b ON a.comm__id = b.item_id
			LEFT OUTER JOIN (
				SELECT item_id,sum(wi_itemqty) as tqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=1 AND wi_status=1 
				GROUP BY item_id
			)c ON a.comm__id = c.item_id
			LEFT OUTER JOIN (
				SELECT item_id,sum(wi_itemqty) as rqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=2 AND wi_status=1 
				GROUP BY item_id
			)d ON a.comm__id = d.item_id
			WHERE comm__id='".$item."'
			");
		if ($q->num_rows() == true){
			//return $q->result();
			foreach($q->result() as $r){
				if ($r->sqty == null){
					$sqty = 0;
				}
				else{
					$sqty = $r->sqty;
				}
				if ($r->tqty == null){
					$tqty = 0;
				}
				else{
					$tqty = $r->tqty;
				}
				if ($r->rqty == null){
					$rqty = 0;
				}
				else{
					$rqty = $r->rqty;
				}
				(float)$total = ((float)$sqty - ((float)$tqty + (float)$rqty));
			}
			if ($qty > $total){
				return true;
				
				
			}
			//echo($total);
		}
	}

	function wh_itemqty_validation_sap(){

		$ims_whsecode = $this->uri->segment(3);
		$ims_whsename = "";
		$whsename = "";

		// $qry_whse = $this->db->where('WhsCode', $ims_whsecode)
		// 				     ->get('whse_integration_ims');

		// if($qry_whse->num_rows() > 0){
		// 	foreach($qry_whse->result_array() as $qrec){
		// 		$ims_whsename = $qrec['SAP_WhsName'];
		// 		$whsename = $qrec['WhsName'];
		// 	}
		// }else{
		// 	$qry_whse2 = $this->db->where('wh_code', $ims_whsecode)
		// 				     ->get('mwhr');

		// 	if($qry_whse2->num_rows() > 0){
		// 		foreach($qry_whse2->result_array() as $qrec2){
		// 			$ims_whsename = $qrec2['wh_name'];
		// 			$whsename = $qrec2['wh_name'];
		// 		}
		// 	}
		// }

		$qry_whse2 = $this->db->where('wh_code', $ims_whsecode)
						     ->get('mwhr');

			if($qry_whse2->num_rows() > 0){
				foreach($qry_whse2->result_array() as $qrec2){
					$ims_whsename = $qrec2['wh_name'];
					$whsename = $qrec2['wh_name'];
				}
			}

		$item = $_POST['item_code'];
		$qty = $_POST['whqty'];

		$get = $ims_whsename;
		
		$q=$this->db->query("
			SELECT a.comm__id,a.comm__name,b.sqty,c.tqty,d.rqty
			FROM ocmt a
			LEFT OUTER JOIN (
				SELECT item_id,sum(wi_itemqty) as sqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=0 AND wi_approvestatus=1 AND wi_status=1 
				GROUP BY item_id
			)b ON a.comm__id = b.item_id
			LEFT OUTER JOIN (
				SELECT item_id,sum(wi_itemqty) as tqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=1 AND wi_status=1 
				GROUP BY item_id
			)c ON a.comm__id = c.item_id
			LEFT OUTER JOIN (
				SELECT item_id,sum(wi_itemqty) as rqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=2 AND wi_status=1 
				GROUP BY item_id
			)d ON a.comm__id = d.item_id
			WHERE comm__id='".$item."'
			");

		if ($q->num_rows() == true){
			//return $q->result();
			foreach($q->result() as $r){
				if ($r->sqty == null){
					$sqty = 0;
				}
				else{
					$sqty = $r->sqty;
				}
				if ($r->tqty == null){
					$tqty = 0;
				}
				else{
					$tqty = $r->tqty;
				}
				if ($r->rqty == null){
					$rqty = 0;
				}
				else{
					$rqty = $r->rqty;
				}
				$total = ($sqty - ($tqty + $rqty));
			}
			if ($qty > $total){
				return true;
			}
			//echo($total);
		}
	}

	function home_wh_add(){

		date_default_timezone_set("Asia/Manila");
		$datetime = date('Y-m-d h:i:s');

		if ($this->input->post('ref2')==""){
			$type2 = "";
			$tname2="";
		}
		else{
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}

		$str = $this->input->post('shiptime');
		$shiptime = preg_replace('/\s+/', '', $str);

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
		}elseif($sub_del_type_in == 'DI_06'){
			$bp_name = $this->input->post('sub_in_warehouse');
		}

		$data = array(
			'wi_type'=>0,
			'wh_name'=>$this->input->post('wh'),
			'wi_reftype'=>$this->input->post('doctype1'),
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'wi_refnum3'=>$this->input->post('ref3'),
			'wi_refnum4'=>$this->input->post('ref4'),
			'transfer_ref'=>$this->input->post('transfer_ref'),
			'wi_LOINum'=>$this->input->post('loi'),
			'wi_refname'=>$bp_name,
			'item_id'=>$this->input->post('whitem'),
			'wi_itemqty'=>$this->input->post('whqty'),
			'wi_createby'=>$this->session->userdata('usr_uname'),
			'wi_status'=>1,
			'deldate'=>$this->input->post('ddate'),
			'wi_remarks'=>$this->input->post('remarks'),
			'item_uom'=>$this->input->post('uom'),
			'truck_company'=>$this->input->post('truck_list'),
			'truck_driver'=>$this->input->post('tdrvr'),
			'truck_platenum'=>$this->input->post('tpnum'),
			'wi_deltype'=>$this->input->post('deltype'),
			'rr_category'=>$this->input->post('rtn'),
			'wi_doqty'=>$this->input->post('itoqty'),
			'wi_itoqty'=>$this->input->post('itoqty'),
			'wi_intransit'=>$this->input->post('intransit'),
			'wi_location'=>$this->input->post('location'),
			'ship_date'=>$this->input->post('shipdate'),
			'ship_time'=>$shiptime,
			'wi_subtype'=>$sub_del_type_in,
			'wi_dtcode'=>'DT_01',
			'wi_transno'=>$this->input->post('trans_no'),

			'prepared_by'=>$this->input->post('prepared_by'),
			'guard_duty'=>$this->input->post('guard_duty'),

			'no_of_print'=>1,
			'print_status'=>1,

			'pbatch_code'=>$this->input->post('pbatch_code'),

			'wi_reftype3'=>$this->input->post('doctype3'),
			'wi_reftype4'=>$this->input->post('doctype4'),
			'wi_createdatetime' => $datetime

		);
		$q=$this->db->insert('whir',$data);

		// DI_05 IS FOR ADJUSTMENT
		if($sub_del_type_in <> "DI_05"){

			$this->db->select('sn_nextnum');
			$this->db->where('sn_code', 'DI');
			$this->db->where('whse_code', $this->uri->segment(3));

			$qry2 = $this->db->get('sndr');

			foreach($qry2->result_array() as $tr){

				(int)$next_no = 0;
				$next_no = $tr['sn_nextnum'];
				$next_no+=1;

				$data2 = array(
					'sn_nextnum'=>$next_no
				);

				$this->db->where('sn_code', 'DI');
				$this->db->where('whse_code', $this->uri->segment(3));
				$this->db->update('sndr', $data2);

			}

		}

	}

	function home_wh_add_edit(){

		if ($this->input->post('ref2')==""){
			$type2 = "";
			$tname2="";
		}
		else{
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}

		$str = $this->input->post('shiptime');
		$shiptime = preg_replace('/\s+/', '', $str);

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

		$tokens = explode('/', current_url());
		$wi_id = $tokens[sizeof($tokens)-1];

		$data = array(
			'wi_type'=>0,
			'wh_name'=>$this->input->post('wh'),
			'wi_reftype'=>$this->input->post('doctype1'),
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'transfer_ref'=>$this->input->post('transfer_ref'),
			'wi_LOINum'=>$this->input->post('loi'),
			'wi_refname'=>$bp_name,
			'item_id'=>$this->input->post('whitem'),
			'wi_itemqty'=>$this->input->post('whqty'),
			'wi_createby'=>$this->session->userdata('usr_uname'),
			'wi_status'=>1,
			'deldate'=>$this->input->post('ddate'),
			'wi_remarks'=>$this->input->post('remarks'),
			'item_uom'=>$this->input->post('uom'),
			'truck_company'=>$this->input->post('truck_list'),
			'truck_driver'=>$this->input->post('tdrvr'),
			'truck_platenum'=>$this->input->post('tpnum'),
			'wi_deltype'=>$this->input->post('deltype'),
			'rr_category'=>$this->input->post('rtn'),
			'wi_doqty'=>$this->input->post('itoqty'),
			'wi_itoqty'=>$this->input->post('itoqty'),
			'wi_intransit'=>$this->input->post('intransit'),
			'wi_location'=>$this->input->post('location'),
			'ship_date'=>$this->input->post('shipdate'),
			'ship_time'=>$shiptime,
			'wi_subtype'=>$sub_del_type_in,

			'pbatch_code'=>$this->input->post('pbatch_code')
		);
		
		$this->db->where('wi_id', $wi_id);
		$q=$this->db->update('whir',$data);
	}

	function di_nappr(){

		$qry2 = $this->db->query("SELECT wi_id FROM whir ORDER BY wi_id DESC LIMIT 1");

		if($qry2->num_rows() > 0){
			foreach($qry2->result() as $r){

				if ($this->input->post('ref2')==""){
					$type2 = "";
					$tname2="";
				}
				else{
					$type2=$this->input->post('doctype2');
					$tname2=$this->input->post('ref2');
				}

				$str = $this->input->post('shiptime');
				$shiptime = preg_replace('/\s+/', '', $str);

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
				}else{
					$bp_name = "";
				}

				$data = array(
					'wi_id'=>$r->wi_id,
					'di_id'=>"di_".$r->wi_id,
					'wi_type'=>0,
					'wh_name'=>$this->input->post('wh'),
					'wi_reftype'=>$this->input->post('doctype1'),
					'wi_refnum'=>$this->input->post('ref'),
					'wi_reftype2'=>$type2,
					'wi_refnum2'=>$tname2,
					'transfer_ref'=>$this->input->post('transfer_ref'),
					'wi_LOINum'=>$this->input->post('loi'),
					'wi_refname'=>$bp_name,
					'item_id'=>$this->input->post('whitem'),
					'wi_itemqty'=>$this->input->post('whqty'),
					'wi_createby'=>$this->session->userdata('usr_uname'),
					'wi_status'=>1,
					'deldate'=>$this->input->post('ddate'),
					'wi_remarks'=>$this->input->post('remarks'),
					'item_uom'=>$this->input->post('uom'),
					'truck_company'=>$this->input->post('truck_list'),
					'truck_driver'=>$this->input->post('tdrvr'),
					'truck_platenum'=>$this->input->post('tpnum'),
					'wi_deltype'=>$this->input->post('deltype'),
					'rr_category'=>$this->input->post('rtn'),
					'wi_doqty'=>$this->input->post('itoqty'),
					'wi_itoqty'=>$this->input->post('itoqty'),
					'wi_intransit'=>$this->input->post('intransit'),
					'wi_location'=>$this->input->post('location'),
					'ship_date'=>$this->input->post('shipdate'),
					'ship_time'=>$shiptime,
					'wi_subtype'=>$sub_del_type_in
				);
				$q=$this->db->insert('di_nappr',$data);

			}
		}

	}

	function home_wh_out(){

		date_default_timezone_set("Asia/Manila");
		$datetime = date('Y-m-d h:i:s');

		if ($this->input->post('ref2')==""){
			$type2 = "";
			$tname2="";
		}
		else{
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}

		$shiptime = $this->input->post('shiptime');
		// $shiptime = preg_replace('/\s+/', '', $str);

		$trucktime = $this->input->post('trucktime');
		// $trucktime = preg_replace('/\s+/', '', $str2);

		$po = $this->input->post('PONum');
		$pnum = str_replace(' ', '', $po);

		$sub_del_type_out = $this->input->post('sub_type_del_out');

		if($sub_del_type_out == 'DO_01'){
			$bp_name = $this->input->post('sub_out_customer');
			$dtype = "DO_01";
		}elseif($sub_del_type_out == 'DO_02'){
			$bp_name = $this->input->post('sub_out_warehouse');
			$dtype = "DO_02";
		}elseif($sub_del_type_out == 'DO_03'){
			$bp_name = $this->input->post('sub_out_warehouse');
			$dtype = "DO_03";
		}elseif($sub_del_type_out == 'DO_04'){
			$bp_name = $this->input->post('sub_out_customer');
			$dtype = "DO_04";
		}elseif($sub_del_type_out == 'DO_05'){
			$bp_name = $this->input->post('sub_out_customer');
			$dtype = "DO_05";
		}else{
			$bp_name = $this->input->post('cust_code');
			$dtype = "";
		}

		// if($this->input->post('sub_type_del_out') == "Customer Delivery"){
		// 	$dtype = "DO_01";
		// }elseif($this->input->post('sub_type_del_out') == "Pick-Up"){
		// 	$dtype = "DO_05";
		// }

		$data = array(
			'wi_type'=>2,
			'wh_name'=>$this->input->post('wh'),
			'wi_reftype'=>$this->input->post('doctype1'),
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'wi_refname'=>$bp_name,
			'item_id'=>$this->input->post('whitem'),
			'wi_itemqty'=>$this->input->post('whqty'), //actual loaded
			'wi_createby'=>$this->session->userdata('usr_uname'),
			'wi_status'=>1,
			'deldate'=>$this->input->post('ddate'),
			'wi_remarks'=>$this->input->post('remarks'),
			'wi_doqty'=>$this->input->post('doqty'), // Real
			'item_uom'=>$this->input->post('uom'),
			'truck_company'=>$this->input->post('truck_list'),
			'truck_driver'=>$this->input->post('tdrvr'),
			'truck_platenum'=>$this->input->post('tpnum'),
			'wi_PONum'=>$pnum,
			'wi_deltype'=>$this->input->post('deltype'),
			'wi_expecteddeliverydate'=>$this->input->post('expected_deldate'),
			'wi_intransit'=>$this->input->post('intransit'),
			'wi_location'=>$this->input->post('location'),
			'ship_date'=>$this->input->post('tdate'),
			'ship_time'=>$shiptime,
			'wi_refnum3'=>$this->input->post('ref3'),
			'wi_refnum4'=>$this->input->post('ref4'),
			'transfer_ref'=>$this->input->post('transfer_ref'),
			'truck_arrival_time'=>$trucktime,
			'wi_subtype'=>$dtype,
			'do_date'=>$this->input->post('dodate'),
			'do_remarks'=>$this->input->post('do_remarks'),
			'wi_dtcode'=>'DT_02',
			'wi_transno'=>$this->input->post('trans_no'),

			// 'note1'=>$this->input->post('note1'),
			// 'note2'=>$this->input->post('note2'),
			'prepared_by'=>$this->input->post('prepared_by'),
			'checked_by'=>$this->input->post('checked_by'),
			'guard_duty'=>$this->input->post('guard_duty'),
			'seal'=>$this->input->post('seal'),
			'ref_print'=>$this->input->post('ref_print'),

			'no_of_print'=>1,
			'print_status'=>1,

			'pbatch_code'=>$this->input->post('pbatch_code'),
			
			'catalog_no'=>$this->input->post('catno'),

			'do_series_no'=>$this->input->post('doseriesno'),

			'other_remarks' => $this->input->post('other_rmrks'),

			'wi_createdatetime' => $datetime

		);
		$q=$this->db->insert('whir',$data);

		// DO_03 IS FOR ADJUSTMENT TRANSACTIONS
		if($dtype <> "DO_03"){

			//UPDATE SNDR TABLE
			$this->db->select('sn_nextnum');
			$this->db->where('sn_code','DO');
			$this->db->where('whse_code', $this->uri->segment(3));

			$qry2 = $this->db->get('sndr');

			foreach($qry2->result_array() as $tn){

				(int)$next_no = $tn['sn_nextnum'];
				$next_no+=1;

				$data2 = array(
					'sn_nextnum'=>$next_no
				);

				$this->db->where('sn_code', 'DO');
				$this->db->where('whse_code', $this->uri->segment(3));
				$this->db->update('sndr', $data2);

			}

		}

	}

	function dr_print_layout(){

		$trans_no = "";
		$trans_no = $this->uri->segment(3);

		// $this->db->select_max('wi_id');
		// $this->db->where('wi_transno', $trans_no);
		// $this->db->select('wh_name');
		// $qry1 = $this->db->get('whir');

		$qry1 = $this->db->query("SELECT * FROM whir WHERE wi_transno = '$trans_no' ");

		foreach($qry1->result_array() as $row){

			$whse_id = $row['wi_id'];

			$qry2 = $this->db->query("SELECT * FROM whir a
										LEFT OUTER JOIN ocmt b ON b.comm__id = a.item_id
										LEFT OUTER JOIN ocrd c ON c.CardCode = a.wi_refname
									WHERE a.wi_transno = '$trans_no' ");

			if($qry2->num_rows() > 0){
				foreach($qry2->result_array() as $ra){

					// INSERT INTO PDOCS TABLE SUMMARY OF PRINT DOCUMENTS
					date_default_timezone_set("Asia/Manila");
					$datetime = date('Y-m-d h:i:s');

					$data = array(
							'whse_id'=>$row['wi_id'],
							'doc_type'=>'DR',
							'whse_name'=>$ra['wh_name'],
							'print_datetime'=>$datetime,
							'print_by'=>$this->session->userdata('usr_uname')
					);

					$this->db->insert('pdocs', $data);

				}

				return $qry2->result();
			}

		}

	}

	function wis_print_layout(){

		$trans_no = "";
		$trans_no = $this->uri->segment(3);

		// $this->db->select_max('wi_id');
		// $qry1 = $this->db->get('whir');

		$qry1 = $this->db->query("SELECT * FROM whir WHERE wi_transno = '$trans_no' ");

		foreach($qry1->result_array() as $row){

			$whse_id = $row['wi_id'];

			$qry2 = $this->db->query("SELECT * FROM whir a
										LEFT OUTER JOIN ocmt b ON b.comm__id = a.item_id
										LEFT OUTER JOIN ocrd c ON c.CardCode = a.wi_refname
									WHERE a.wi_transno = '$trans_no' ");

			if($qry2->num_rows() > 0){

				foreach($qry2->result_array() as $ra){

					// INSERT INTO PDOCS TABLE SUMMARY OF PRINT DOCUMENTS
					date_default_timezone_set("Asia/Manila");
					$datetime = date('Y-m-d h:i:s');

					$data = array(
							'whse_id'=>$row['wi_id'],
							'doc_type'=>'WIS',
							'whse_name'=>$ra['wh_name'],
							'print_datetime'=>$datetime,
							'print_by'=>$this->session->userdata('usr_uname')
					);

					$this->db->insert('pdocs', $data);

				}

				return $qry2->result();
			}
		}

	}

	function rr_print_layout(){

		$this->db->select_max('wi_id');
		$qry1 = $this->db->get('whir');

		foreach($qry1->result_array() as $row){

			$whse_id = $row['wi_id'];

			$qry2 = $this->db->query("SELECT * FROM whir a
										LEFT OUTER JOIN ocmt b ON b.comm__id = a.item_id
										LEFT OUTER JOIN ocrd c ON c.CardCode = a.wi_refname
										LEFT OUTER JOIN ousr d ON d.memb__id = a.wi_createby
									WHERE a.wi_id = '$whse_id' ");

			if($qry2->num_rows() > 0){

				foreach($qry2->result_array() as $ra){

					// INSERT INTO PDOCS TABLE SUMMARY OF PRINT DOCUMENTS
					date_default_timezone_set("Asia/Manila");
					$datetime = date('Y-m-d h:i:s');

					$data = array(
							'whse_id'=>$row['wi_id'],
							'doc_type'=>'RR',
							'whse_name'=>$ra['wh_name'],
							'print_datetime'=>$datetime,
							'print_by'=>$this->session->userdata('usr_uname')
					);

					$this->db->insert('pdocs', $data);

				}

				return $qry2->result();
			}
		}

	}

	function home_wh_out_sap(){

		date_default_timezone_set("Asia/Manila");
		$datetime = date('Y-m-d h:i:s');

		if ($this->input->post('ref2')==""){
			$type2 = "";
			$tname2="";
		}
		else{
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}

		$shiptime = $this->input->post('shiptime');
		// $shiptime = preg_replace('/\s+/', '', $str);

		$trucktime = $this->input->post('trucktime');
		// $trucktime = preg_replace('/\s+/', '', $str2);

		$po = $this->input->post('PONum');
		$pnum = str_replace(' ', '', $po);

		if($this->input->post('deltype') == "Customer Delivery"){
			$dtype = "DO_01";
		}elseif($this->input->post('deltype') == "Pick-Up"){
			$dtype = "DO_05";
		}else{
			$dtype = "";
		}

		$data = array(
			'wi_type'=>2,
			'wh_name'=>$this->input->post('source'),
			'wi_reftype'=>$this->input->post('doctype1'),
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'wi_refname'=>$this->input->post('cust_code'),
			'item_id'=>$this->input->post('item_code'),
			'wi_itemqty'=>$this->input->post('whqty'), //actual loaded
			'wi_createby'=>$this->session->userdata('usr_uname'),
			'wi_status'=>1,
			'deldate'=>$this->input->post('ddate'),
			'wi_remarks'=>$this->input->post('dr_remarks'),
			'wi_doqty'=>$this->input->post('doqty'), // Real
			'item_uom'=>$this->input->post('uom'),
			'truck_company'=>$this->input->post('truck_company'),
			'truck_driver'=>$this->input->post('tdrvr'),
			'truck_platenum'=>$this->input->post('tpnum'),
			'wi_PONum'=>$pnum,
			'wi_deltype'=>$this->input->post('delout'),
			'wi_expecteddeliverydate'=>$this->input->post('exddate'),
			'wi_intransit'=>$this->input->post('intransit'),
			'wi_location'=>$this->input->post('location'),
			'ship_date'=>$this->input->post('shipdate'),
			'ship_time'=>$shiptime,
			'wi_refnum3'=>$this->input->post('ref3'),
			'transfer_ref'=>$this->input->post('transfer_ref'),
			'truck_arrival_time'=>$trucktime,
			'wi_subtype'=>$dtype,
			'do_date'=>$this->input->post('dodate'),
			'do_remarks'=>$this->input->post('do_remarks'),
			'wi_dtcode'=>'DT_04',
			'wi_transno'=>$this->input->post('trans_no'),

			'prepared_by'=>$this->input->post('prepared_by'),
			'checked_by'=>$this->input->post('checked_by'),
			'guard_duty'=>$this->input->post('guard_duty'),
			'seal'=>$this->input->post('seal'),
			'ref_print'=>$this->input->post('ref_print'),

			'no_of_print'=>1,
			'print_status'=>1,

			'pbatch_code'=>$this->input->post('pbatch_code'),
			'wi_refnum4'=>$this->input->post('ref4'),

			'catalog_no'=>$this->input->post('catno'),

			'do_series_no'=>$this->input->post('doseriesno'),

			'other_remarks' => $this->input->post('other_rmrks'),
			'wi_createdatetime' => $datetime
			
		);
		$q=$this->db->insert('whir',$data);

		$data2 = array('truck_seal'=>$this->input->post('truck_seal'));

		$this->db->where('CardCode', $this->input->post('cust_code'));
		$this->db->update('ocrd', $data2);

		// UPDATE SNDR TABLE
		$this->db->select('sn_nextnum');
		$this->db->where('sn_code', 'DO HO');
		$this->db->where('whse_code', $this->uri->segment(3));

		$qry3 = $this->db->get('sndr');

		foreach($qry3->result_array() as $tn){

			(int)$next_no = $tn['sn_nextnum'];
			$next_no+=1;

			$data3 = array(
				'sn_nextnum'=>$next_no
			);

			$this->db->where('sn_code', 'DO HO');
			$this->db->where('whse_code', $this->uri->segment(3));
			$this->db->update('sndr', $data3);

		}	

	}

	function home_wh_out_sap_link(){

		date_default_timezone_set("Asia/Manila");
		$datetime = date('Y-m-d');

		if ($this->input->post('ref2')=="") {
			$type2 = "";
			$tname2="";
		} else {
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}

		$shiptime = $this->input->post('shiptime');
		// $shiptime = preg_replace('/\s+/', '', $str);

		$trucktime = $this->input->post('trucktime');
		// $trucktime = preg_replace('/\s+/', '', $str2);

		$po = $this->input->post('PONum');
		$pnum = str_replace(' ', '', $po);

		if($this->input->post('deltype') == "Customer Delivery"){
			$dtype = "DO_01";
		}elseif($this->input->post('deltype') == "Pick-Up"){
			$dtype = "DO_05";
		}else{
			$dtype = "";
		}

		$data = array(
			'wi_type'=>2,
			'wi_dtcode'=>'DT_04',
			'wi_deltype'=>$this->input->post('delout'),
			'wi_subtype'=>$dtype,
			'wh_name'=>$this->input->post('source'),
			'wi_reftype'=>$this->input->post('doctype1'),
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'wi_refname'=>$this->input->post('cust_code'),
			'wi_location'=>$this->input->post('location'),
			'item_id'=>$this->input->post('item_code'),
			'item_uom'=>$this->input->post('uom'),
			'wi_itemqty'=>$this->input->post('whqty'),//actual loaded
			'deldate'=>$this->input->post('ddate'),
			'wi_expecteddeliverydate'=>$this->input->post('exddate'),
			'truck_company'=>$this->input->post('truck_company'),
			'truck_driver'=>$this->input->post('tdrvr'),
			'truck_platenum'=>$this->input->post('tpnum'),
			'wi_remarks'=>$this->input->post('dr_remarks'),
			'wi_doqty'=>$this->input->post('doqty'), // Real
			'wi_status'=> 1,
			'wi_createdatetime' => $datetime,
			'wi_transno' => $this->input->post('trans_no'),
			'wi_approve' => 0
	
		);

		$db3 = $this->load->database('db3', TRUE);
		$q = $db3->insert('IMS_Data', $data);
	
	}

	function home_wh_out_edit(){
		if ($this->input->post('ref2')==""){
			$type2 = "";
			$tname2="";
		}
		else{
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}

		$str = $this->input->post('shiptime');
		$shiptime = preg_replace('/\s+/', '', $str);

		$str2 = $this->input->post('trucktime');
		$trucktime = preg_replace('/\s+/', '', $str2);

		$po = $this->input->post('PONum');
		$pnum = str_replace(' ', '', $po);

		$sub_del_type_out = $this->input->post('sub_type_del_out');

		if($sub_del_type_out == 'DO_01'){
			$bp_name = $this->input->post('sub_out_customer');
		}elseif($sub_del_type_out == 'DO_02'){
			$bp_name = $this->input->post('sub_out_warehouse');
		}elseif($sub_del_type_out == 'DO_03'){
			$bp_name = $this->input->post('sub_out_warehouse');
		}elseif($sub_del_type_out == 'DO_04'){
			$bp_name = $this->input->post('sub_out_customer');
		}elseif($sub_del_type_out == 'DO_05'){
			$bp_name = $this->input->post('sub_out_customer');
		}

		$tokens = explode('/', current_url());
		$wi_id = $tokens[sizeof($tokens)-1];

		$data = array(
			'wi_type'=>2,
			'wh_name'=>$this->input->post('wh'),
			'wi_reftype'=>$this->input->post('doctype1'),
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'wi_refname'=>$bp_name,
			'item_id'=>$this->input->post('whitem'),
			'wi_itemqty'=>$this->input->post('whqty'), //actual loaded
			'wi_createby'=>$this->session->userdata('usr_uname'),
			'wi_status'=>1,
			'deldate'=>$this->input->post('ddate'),
			'wi_remarks'=>$this->input->post('remarks'),
			'wi_doqty'=>$this->input->post('doqty'), // Real
			'item_uom'=>$this->input->post('uom'),
			'truck_company'=>$this->input->post('truck_list'),
			'truck_driver'=>$this->input->post('tdrvr'),
			'truck_platenum'=>$this->input->post('tpnum'),
			'wi_PONum'=>$pnum,
			'wi_deltype'=>$this->input->post('deltype'),
			'wi_expecteddeliverydate'=>$this->input->post('expected_deldate'),
			'wi_intransit'=>$this->input->post('intransit'),
			'wi_location'=>$this->input->post('location'),
			'ship_date'=>$this->input->post('tdate'),
			'ship_time'=>$shiptime,
			'wi_refnum3'=>$this->input->post('ref3'),
			'transfer_ref'=>$this->input->post('transfer_ref'),
			'truck_arrival_time'=>$trucktime,
			'wi_subtype'=>$sub_del_type_out,

			'pbatch_code'=>$this->input->post('pbatch_code')
		);
		
		$this->db->where('wi_id', $wi_id);
		$q=$this->db->update('whir',$data);
	}

	function do_nappr(){

		$qry2 = $this->db->query("SELECT wi_id FROM whir ORDER BY wi_id DESC LIMIT 1");

		if($qry2->num_rows() > 0){
			foreach($qry2->result() as $r){
				if ($this->input->post('ref2')==""){
					$type2 = "";
					$tname2="";
				}
				else{
					$type2=$this->input->post('doctype2');
					$tname2=$this->input->post('ref2');
				}

				$shiptime = $this->input->post('shiptime');

				$trucktime = $this->input->post('trucktime');

				$po = $this->input->post('PONum');
				$pnum = str_replace(' ', '', $po);

				$sub_del_type_out = $this->input->post('sub_type_del_out');

				if($sub_del_type_out == 'DO_01'){
					$bp_name = $this->input->post('sub_out_customer');
					$dtype = "DO_01";
				}elseif($sub_del_type_out == 'DO_02'){
					$bp_name = $this->input->post('sub_out_warehouse');
					$dtype = "DO_02";
				}elseif($sub_del_type_out == 'DO_03'){
					$bp_name = $this->input->post('sub_out_warehouse');
					$dtype = "DO_03";
				}elseif($sub_del_type_out == 'DO_04'){
					$bp_name = $this->input->post('sub_out_customer');
					$dtype = "DO_04";
				}elseif($sub_del_type_out == 'DO_05'){
					$bp_name = $this->input->post('sub_out_customer');
					$dtype = "DO_05";
				}else{
					$bp_name = $this->input->post('cust_code');
					$dtype = "";
				}

				$data = array(
					'wi_id'=>$r->wi_id,
					'do_id'=>"do_".$r->wi_id,
					'wi_type'=>2,
					'wh_name'=>$this->input->post('wh'),
					'wi_reftype'=>$this->input->post('doctype1'),
					'wi_refnum'=>$this->input->post('ref'),
					'wi_reftype2'=>$type2,
					'wi_refnum2'=>$tname2,
					'wi_refname'=>$bp_name,
					'item_id'=>$this->input->post('whitem'),
					'wi_itemqty'=>$this->input->post('whqty'), //actual loaded
					'wi_createby'=>$this->session->userdata('usr_uname'),
					'wi_status'=>1,
					'deldate'=>$this->input->post('ddate'),
					'wi_remarks'=>$this->input->post('remarks'),
					'wi_doqty'=>$this->input->post('doqty'), // Real
					'item_uom'=>$this->input->post('uom'),
					'truck_company'=>$this->input->post('truck_list'),
					'truck_driver'=>$this->input->post('tdrvr'),
					'truck_platenum'=>$this->input->post('tpnum'),
					'wi_PONum'=>$pnum,
					'wi_deltype'=>$this->input->post('deltype'),
					'wi_expecteddeliverydate'=>$this->input->post('expected_deldate'),
					'wi_intransit'=>$this->input->post('intransit'),
					'wi_location'=>$this->input->post('location'),
					'ship_date'=>$this->input->post('tdate'),
					'ship_time'=>$shiptime,
					'wi_refnum3'=>$this->input->post('ref3'),
					'transfer_ref'=>$this->input->post('transfer_ref'),
					'truck_arrival_time'=>$trucktime,
					'wi_subtype'=>$dtype,
					'do_date'=>$this->input->post('dodate'),
					'do_remarks'=>$this->input->post('do_remarks')
				);
				$q=$this->db->insert('do_nappr',$data);
			}
		}

	}

	function home_wh_mm(){
		if($this->input->post('deltype1') == 'Delivery In'){
			$deltype=0;
			//$source=$this->input->post('cus2');
			//$desti=$this->input->post('wh');
		}
		else{
			$deltype=2;
			//$source=$this->input->post('wh');
			//$desti=$this->input->post('cus2');
		}
		if ($this->input->post('ref2')==""){
			$type2 = "";
			$tname2="";
		}
		else{
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}
		$data = array(
			'wi_type'=>$deltype,
			'wh_name'=>$this->input->post('wh'),
			'wi_reftype'=>$this->input->post('doctype1'),
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'wi_refname'=>$this->input->post('cus2'),
			'item_id'=>$this->input->post('whitem'),
			'wi_itemqty'=>$this->input->post('whqty'),
			'wi_createby'=>$this->session->userdata('usr_uname'),
			'wi_status'=>1,
			'deldate'=>$this->input->post('ddate'),
			'wi_remarks'=>$this->input->post('remarks'),
			'item_uom'=>$this->input->post('iunit1'),
			'wi_mmprocess'=>$this->input->post('process'),
			'wi_deltype'=>$this->input->post('deltype1')
		);
		$q=$this->db->insert('whir',$data);
	}

	function wh_delivery_cancel_list_active_search(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT a.wi_type,
			a.wi_reftype2,
			a.wi_reftype,
			a.wi_refnum2,
			a.wi_approvedatetime,
			a.wi_refnum,
			a.wi_refname,
			a.wi_id,
			a.wh_name,
			a.wi_refnum,
			a.wi_refname,
			a.item_id,
			a.wi_approvestatus,
			a.deldate,
			wi_itemqty,wh_code,
			c.comm__name,
			d.CardName AS CName,
			a.wi_type,
			a.wi_createdatetime,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
		FROM whir a
		LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
		LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
		LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
		WHERE wi_type=2
				AND wi_status=1 
				AND a.wh_name='".$this->input->post('whouse')."' 
				AND a.wi_reftype LIKE '%".$dtype."%' 
				AND a.wi_refnum LIKE '%".$this->input->post('refno')."%'
			OR wi_type=2
				AND wi_status=1 
				AND a.wh_name='".$this->input->post('whouse')."' 
				AND a.wi_reftype2 LIKE '%".$dtype."%' 
				AND a.wi_refnum2 LIKE '%".$this->input->post('refno')."%'
			OR wi_type=0
				AND wi_approvestatus=0 
				AND wi_status=1
				AND a.wh_name='".$this->input->post('whouse')."' 
				AND a.wi_reftype LIKE '%".$dtype."%' 
				AND a.wi_refnum LIKE '%".$this->input->post('refno')."%'
			OR wi_type=0
				AND wi_approvestatus=0  
				AND wi_status=1 
				AND a.wh_name='".$this->input->post('whouse')."' 
				AND a.wi_reftype2 LIKE '%".$dtype."%' 
				AND a.wi_refnum2 LIKE '%".$this->input->post('refno')."%'
		ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function wh_delivery_reserve_list_active($get){
		$q = $this->db->query("SELECT 
				a.wi_reftype2,
				a.wi_reftype,
				a.wi_refnum2,
				a.wi_approvedatetime,
				a.wi_refnum,
				a.wi_refname,
				a.wi_id,
				a.wh_name,
				a.wi_refnum,
				a.wi_refname,
				a.item_id,
				a.wi_approvestatus,
				wi_itemqty,wh_code,
				c.comm__name,
				d.CardName
			FROM whir a
			LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
			WHERE wi_type=2 AND wi_status=1 AND wh_code='".$get."'
			ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}
	//This reserve
	function wh_delivery_reserve_list(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT a.wi_type,
			a.wi_reftype2,
			a.wi_reftype,
			a.wi_refnum2,
			a.wi_approvedatetime,
			a.wi_refnum,
			a.wi_refname,
			a.wi_id,
			b.wh_name,
			a.wi_refnum,
			a.wi_refname,
			a.item_id,
			a.wi_approvestatus,
			a.deldate,
			a.wi_itemqty,
			b.wh_code,
			c.comm__name,
			d.CardName AS CName,
			a.wi_type AS type,
			a.wi_createby,
			a.wi_createdatetime,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName,
			a.wi_dtcode,
			a.wi_location,
			a.wi_dtcode
			FROM whir a
			LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
			WHERE wi_type IN (2,0) 
				AND wi_status=1 
				AND wi_approvestatus = 0 
				AND a.wh_name='".$this->input->post('whouse')."'
				AND wi_deltype in ('Delivery In', 'Delivery Out')
			ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}
	
	function if_del_out($a, $b, $c, $d){ // check reserved 
		$this->db->where('ref_num', $a);
		$this->db->where('ref_type', $b);
		$q = $this->db->get('mand');
		if($q->num_rows() > 0){
			return $q->result();
		}
		else{
			if($this->add_doito_to_mand($b, $a, $c, $d)){
				$this->db->where('ref_num', $a);
				$this->db->where('ref_type', $b);
				$q = $this->db->get('mand');
				if($q->num_rows() > 0){
					return $q->result();
				}
			}
			else{
				return false;
			}
		}
	}
	
	function add_doito_to_mand($a, $b, $c, $d){
		//doctype, ref, item code, qty
		
		$this->db->where('ref_type', $a);
		$this->db->where('ref_num', $b);
		$q = $this->db->get('mand');
		if(!$q->num_rows() > 0){
			$insert = array(
				'create_date' => date('Y-m-d'),
				'ref_type' => $a,
				'ref_num' => $b,
				'item_code' => $c,
				'act_qty' => $d,
				'rem_qty' => $d
			);
			if($this->db->insert('mand', $insert)){
				return true;
			}
		}
	}
	
	function exceeds_del_draft($a, $b, $c, $d){
		if($c == 'DO' OR $c == 'ITO'){
			$reftype = $c;
			$refnum = $a;
			$reference = 'wi_reftype';
		}
		
		if($d == 'DO' OR $d == 'ITO'){
			$reftype = $d;
			$refnum = $b;
			$reference = 'wi_reftype2';
		}
		
		$q = $this->db->query("
			SELECT SUM(wi_itemqty) 'draft' FROM whir WHERE '$reference' = '$reftype' AND wi_refnum = '$refnum' AND wi_status = '1' AND wi_type = '2' AND wi_approvestatus = '1'
		");
		
		/*$this->db->where($reference, $reftype);
		$this->db->where('wi_refnum', $refnum);
		$this->db->where('wi_type', '2');
		$this->db->where('wi_status', '1');
		$this->db->where('wi_approvestatus', '1');
		$q = $this->db->get('whir');*/
		foreach($q->result() as $m){
			return $m->draft;
		}
	}
	
	function wh_delivery_reserve_list_mm(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT a.wi_type,
			a.wi_reftype2,
			a.wi_reftype,
			a.wi_refnum2,
			a.wi_approvedatetime,
			a.wi_refnum,
			a.wi_refname,
			a.wi_id,
			a.wh_name,
			a.wi_refnum,
			a.wi_refname,
			a.item_id,
			a.wi_approvestatus,
			a.deldate,
			a.wi_mmtnum,
			wi_itemqty,
			wh_code,c.comm__name ,
			d.CardName AS CName,
			a.wi_type AS type,
			a.wi_transno,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
			FROM whir a
			LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
			WHERE wi_type IN (2,0) 
				AND wi_status=1 
				AND wi_approvestatus = 0 
				AND a.wh_name='".$this->input->post('whouse')."'
				AND wi_deltype = 'Material Management'
			ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}
	function wh_delivery_out_list(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("
			SELECT a.wi_type,
				a.wi_reftype2,
				a.wi_reftype,
				a.wi_refnum2,
				a.wi_approvedatetime,
				a.wi_refnum,
				a.wi_refname,
				a.wi_id,
				a.wh_name,
				a.wi_refnum,
				a.wi_refname,
				a.item_id,
				a.wi_approvestatus,
				a.deldate,
				wi_itemqty,
				wh_code,
				c.comm__name,
				d.CardName AS CName,
				a.wi_createdatetime,
				CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName,
				a.wi_location
			FROM whir a
			LEFT OUTER JOIN mwhr b 
				ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c 
				ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d 
				ON a.wi_refname=d.CardCode 
			WHERE wi_type=2 
				AND wi_status=1 
				AND wi_approvestatus =1 
				AND a.wh_name='".$this->input->post('whouse')."' 
				AND a.wi_reftype LIKE '%".$dtype."%' 
				AND a.wi_refnum LIKE '%".$this->input->post('refno')."%'
			OR wi_type=2 
				AND wi_status=1 
				AND wi_approvestatus =1 
				AND a.wh_name='".$this->input->post('whouse')."' 
				AND a.wi_reftype2 LIKE '%".$dtype."%' 
				AND a.wi_refnum2 LIKE '%".$this->input->post('refno')."%'
			ORDER BY wi_id
		");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function wh_delivery_reservelist($get){
		$q = $this->db->query("
			SELECT a.wi_reftype2,
				a.wi_reftype,
				a.wi_refnum2,
				a.wi_approvedatetime,
				a.wi_refnum,
				a.wi_refname,
				a.wi_id,
				a.wh_name,
				a.wi_refnum,
				a.wi_refname,
				a.item_id,
				a.wi_approvestatus,
				wi_itemqty,
				wh_code,
				c.comm__name,
				d.CardName 
			FROM whir a
			LEFT OUTER JOIN mwhr b 
				ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c 
				ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d 
				ON a.wi_refname=d.CardCode 
			WHERE wi_type=2 
				AND wi_status=1 
				AND wh_name='".$get."'
			ORDER BY wi_id
		");
		if($q->num_rows() == true){
			return $q->result();
		}
	}
	
	// CODE BY SIR ARMAN

	// function wh_delivery_reserve_out(){ // DELIVERY OUT WRITE ON DATABASE
	// 	date_default_timezone_set("Asia/Manila");
	// 	$datetime = date('Y-m-d H:m:s');
	// 	$tokens = explode('_', current_url());
	// 	$id = $tokens[sizeof($tokens)-1];
	// 	$remarks = $this->input->post('remarks');
		
	// 	if($this->check_dr($this->input->post('dr_num')) == FALSE){
	// 		$data=array(
	// 			'wi_drnum'=>$this->input->post('dr_num'),
	// 			'wi_type'=>1,
	// 			'wi_outby'=>$this->session->userdata('usr_uname'),
	// 			'wi_outdatetime'=>$datetime,
	// 			'wi_updateby'=>$this->session->userdata('usr_uname'),
	// 			'wi_updatedatetime'=>$datetime,
	// 			'wi_remarks'=>$remarks
	// 		);
	// 		$this->db->where('wi_id',$id);
	// 		$this->db->update('whir',$data);
	// 		//$this->update_ito_intransit();	
	// 	}
	// 	else{
	// 		return 'DR_E';
	// 	}
	// }
	
	function wh_delivery_reserve_out(){
		date_default_timezone_set("Asia/Manila");
		$datetime = date('Y-m-d H:m:s');
		$tokens = explode('_', current_url());
		$id = $tokens[sizeof($tokens)-1];
		$data=array(
			//'wi_drnum'=>$this->input->post('drnum'),
			'wi_type'=>1,
			'wi_outby'=>$this->session->userdata('usr_uname'),
			'wi_outdatetime'=>$datetime,
			'wi_updateby'=>$this->session->userdata('usr_uname'),
			'wi_updatedatetime'=>$datetime
		);
		$this->db->where('wi_id',$id);
		$this->db->update('whir',$data);

		$qry = $this->db->query("SELECT * FROM whir WHERE wi_id = '$id' ");

		foreach($qry->result() as $r){

			$intransit = $r->wi_doqty - $r->wi_itemqty;

			if($r->wi_reftype2 == 'DO' AND $intransit == 0){

				$this->db->where('wi_reftype2', $r->wi_reftype2);
				$this->db->where('wi_refnum2', $r->wi_refnum2);

				$d = array(
					'udo_active'=>1
				);

				$this->db->update('whir', $d);
			}

		}

	}

	function wh_delivery_reserve_out_link() {
		
		date_default_timezone_set("Asia/Manila");
		$date_today = date('Y-m-d');
		$time_today = date('H:m:s');

		$tokens = explode('_', current_url());
		$id = $tokens[sizeof($tokens)-1];

		$qry = $this->db->query("SELECT wi_transno FROM whir WHERE wi_id = $id ");

		$trans_no = "";

		if ($qry->num_rows() == TRUE) {
			foreach ($qry->result_array() as $r) {
				$trans_no = $r['wi_transno'];
			}
		}

		$data = array(
				'wi_type' => 1,
				'wi_outdate' => $date_today,
				'wi_outtime' => $time_today
			);

		$db3 = $this->load->database('db3', TRUE);

		$db3->where('wi_transno', $trans_no);
		$db3->update('IMS_Data', $data);

	}

	function do_out(){
		date_default_timezone_set("Asia/Manila");
		$datetime = date('Y-m-d H:m:s');
		$tokens = explode('_', current_url());
		$id = $tokens[sizeof($tokens)-1];

		$this->db->where('wi_id', $id);
		$qry2 = $this->db->get('do_appr');

		if($qry2->num_rows() > 0){
			foreach($qry2->result() as $r){
				$data = array(
					'wi_id'=>$r->wi_id,
					'do_id'=>$r->do_id,
					'wh_name'=>$r->wh_name,
					'wi_reftype'=>$r->wi_reftype,
					'wi_refnum'=>$r->wi_refnum,
					'wi_reftype2'=>$r->wi_reftype2,
					'wi_refnum2'=>$r->wi_refnum2,
					'wi_refname'=>$r->wi_refname,
					'item_id'=>$r->item_id,
					'wi_itemqty'=>$r->wi_itemqty,
					'wi_createby'=>$r->wi_createby,
					'wi_status'=>1,
					'deldate'=>$r->deldate,
					'wi_remarks'=>$r->wi_remarks,
					'wi_doqty'=>$r->wi_doqty,
					'item_uom'=>$r->item_uom,
					'truck_company'=>$r->truck_company,
					'truck_driver'=>$r->truck_driver,
					'truck_platenum'=>$r->truck_platenum,
					'wi_PONum'=>$r->wi_PONum,
					'wi_deltype'=>$r->wi_deltype,
					'wi_expecteddeliverydate'=>$r->wi_expecteddeliverydate,
					'wi_intransit'=>$r->wi_intransit,
					'wi_location'=>$r->wi_location,
					'ship_date'=>$r->ship_date,
					'ship_time'=>$r->ship_time,
					'wi_refnum3'=>$r->wi_refnum3,
					'transfer_ref'=>$r->transfer_ref,
					'truck_arrival_time'=>$r->truck_arrival_time,
					'wi_subtype'=>$r->wi_subtype,
					'do_date'=>$r->do_date,
					'do_remarks'=>$r->do_remarks,
					'wi_approvestatus'=>'1',
					'wi_approveby'=>$r->wi_approveby,
					'wi_approvedatetime'=>$r->wi_approvestatus,
					'wi_updateby'=>$r->wi_updateby,
					'wi_updatedatetime'=>$r->wi_updatedatetime,
					'wi_type'=>1,
					'wi_outby'=>$this->session->userdata('usr_uname'),
					'wi_outdatetime'=>$datetime,
					'wi_updateby'=>$this->session->userdata('usr_uname'),
					'wi_updatedatetime'=>$datetime
				);
				$q=$this->db->insert('do_out',$data);
			}
		}

		$this->db->query("DELETE FROM do_appr WHERE wi_id = '".$id."' ");
	}
	

	function check_dr($a){
		$this->db->where('wi_drnum', $a);
		$q = $this->db->get('whir');
		if($q->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function wh_delivery_reserve_cancel($get){
		date_default_timezone_set("Asia/Manila");
		$tokens = explode('_', current_url());
		$id = $tokens[sizeof($tokens)-1];
		$datetime = date('Y-m-d H:m:s');
		$data=array(
			'wi_cancelremarks'=>$this->input->post('remarks'),
			'wi_status'=>'0',
			'wi_cancelby'=>$this->session->userdata('usr_uname'),
			'wi_canceldatetime'=>$datetime,
			'wi_updateby'=>$this->session->userdata('usr_uname'),
			'wi_updatedatetime'=>$datetime
		);
		$this->db->where('wi_id',$id);
		$this->db->update('whir',$data);
	}

	function wh_delivery_reserve_cancel_link(){

		$tokens = explode('_', current_url());
		$id = $tokens[sizeof($tokens)-1];

		$trans_no = "";

		$qry = $this->db->query("SELECT wi_transno FROM whir WHERE wi_id = $id ");

		if ($qry->num_rows() == TRUE) {
			foreach ($qry->result_array() as $r) {
				$trans_no = $r['wi_transno'];
			}
		}

		$data = array(
			'wi_status' => 0
		);

		$db3 = $this->load->database('db3', TRUE);

		$db3->where('wi_transno', $trans_no);
		$db3->update('IMS_Data', $data);
	}


	//'wi_approvestatus'=>'1',
	function wh_delivery_reserve_approve($get){
		date_default_timezone_set("Asia/Manila");
		$this->load->helper('date');
		$tokens = explode ('_',current_url());
		$id = $tokens[sizeof($tokens)-1];
		$now = time('UP8');
		$datetime = date('Y-m-d H:m:s');
		$data=array(
			'wi_approvestatus'=>'1',
			'wi_approveby'=>$this->session->userdata('usr_uname'),
			'wi_approvedatetime'=>$datetime,
			'wi_updateby'=>$this->session->userdata('usr_uname'),
			'wi_updatedatetime'=>$datetime
		);
		$this->db->where('wi_id',$id);
		$this->db->update('whir',$data);
	}

	function wh_delivery_reserve_approve_link() {

		date_default_timezone_set("Asia/Manila");
		$date_today = date('Y-m-d');
		$time_today = date('H:m:s');

		$tokens = explode ('_',current_url());
		$id = $tokens[sizeof($tokens)-1];
		
		$qry = $this->db->query("SELECT wi_transno FROM whir WHERE wi_id = $id ");

		$trans_no = "";

		if ($qry->num_rows() == TRUE) {
			foreach ($qry->result_array() as $r) {
				$trans_no = $r['wi_transno'];
			}
		}

		$data = array(
			'wi_approve' => 1,
			'wi_approvedate' => $date_today,
			'wi_approvetime' => $time_today
		);

		$db3 = $this->load->database('db3', TRUE);

		$db3->where('wi_transno', $trans_no);
		$db3->update('IMS_Data', $data);
	}

	function di_appr(){
		date_default_timezone_set("Asia/Manila");
		$this->load->helper('date');
		$tokens = explode ('_',current_url());
		$id = $tokens[sizeof($tokens)-1];
		$now = time('UP8');
		$datetime = date('Y-m-d H:m:s');

		$this->db->where('wi_id', $id);
		$qry2 = $this->db->get('di_nappr');

		if($qry2->num_rows() > 0){
			foreach($qry2->result() as $r){
				$data = array(
					'wi_id'=>$r->wi_id,
					'di_id'=>$r->di_id,
					'wi_type'=>$r->wi_type,
					'wh_name'=>$r->wh_name,
					'wi_reftype'=>$r->wi_reftype,
					'wi_refnum'=>$r->wi_refnum,
					'wi_reftype2'=>$r->wi_reftype2,
					'wi_refnum2'=>$r->wi_refnum2,
					'transfer_ref'=>$r->transfer_ref,
					'wi_LOINum'=>$r->wi_LOINum,
					'wi_refname'=>$r->wi_refname,
					'item_id'=>$r->item_id,
					'wi_itemqty'=>$r->wi_itemqty,
					'wi_createby'=>$r->wi_createby,
					'wi_status'=>1,
					'deldate'=>$r->deldate,
					'wi_remarks'=>$r->wi_remarks,
					'item_uom'=>$r->item_uom,
					'truck_company'=>$r->truck_company,
					'truck_driver'=>$r->truck_driver,
					'truck_platenum'=>$r->truck_platenum,
					'wi_deltype'=>$r->wi_deltype,
					'rr_category'=>$r->rr_category,
					'wi_doqty'=>$r->wi_doqty,
					'wi_itoqty'=>$r->wi_itoqty,
					'wi_intransit'=>$r->wi_intransit,
					'wi_location'=>$r->wi_location,
					'ship_date'=>$r->ship_date,
					'ship_time'=>$r->ship_time,
					'wi_subtype'=>$r->wi_subtype,
					'wi_approvestatus'=>'1',
					'wi_approveby'=>$this->session->userdata('usr_uname'),
					'wi_approvedatetime'=>$datetime,
					'wi_updateby'=>$this->session->userdata('usr_uname'),
					'wi_updatedatetime'=>$datetime
				);
				$q=$this->db->insert('di_appr',$data);
			}
		}

		$this->db->query("DELETE FROM di_nappr WHERE wi_id = '".$id."' ");

	}
	
	function do_appr(){
		date_default_timezone_set("Asia/Manila");
		$this->load->helper('date');
		$tokens = explode ('_',current_url());
		$id = $tokens[sizeof($tokens)-1];
		$now = time('UP8');
		$datetime = date('Y-m-d H:m:s');

		$this->db->where('wi_id', $id);
		$qry2 = $this->db->get('do_nappr');

		if($qry2->num_rows() > 0){
			foreach($qry2->result() as $r){
				$data = array(
					'wi_id'=>$r->wi_id,
					'do_id'=>$r->do_id,
					'wi_type'=>2,
					'wh_name'=>$r->wh_name,
					'wi_reftype'=>$r->wi_reftype,
					'wi_refnum'=>$r->wi_refnum,
					'wi_reftype2'=>$r->wi_reftype2,
					'wi_refnum2'=>$r->wi_refnum2,
					'wi_refname'=>$r->wi_refname,
					'item_id'=>$r->item_id,
					'wi_itemqty'=>$r->wi_itemqty,
					'wi_createby'=>$r->wi_createby,
					'wi_status'=>1,
					'deldate'=>$r->deldate,
					'wi_remarks'=>$r->wi_remarks,
					'wi_doqty'=>$r->wi_doqty,
					'item_uom'=>$r->item_uom,
					'truck_company'=>$r->truck_company,
					'truck_driver'=>$r->truck_driver,
					'truck_platenum'=>$r->truck_platenum,
					'wi_PONum'=>$r->wi_PONum,
					'wi_deltype'=>$r->wi_deltype,
					'wi_expecteddeliverydate'=>$r->wi_expecteddeliverydate,
					'wi_intransit'=>$r->wi_intransit,
					'wi_location'=>$r->wi_location,
					'ship_date'=>$r->ship_date,
					'ship_time'=>$r->ship_time,
					'wi_refnum3'=>$r->wi_refnum3,
					'transfer_ref'=>$r->transfer_ref,
					'truck_arrival_time'=>$r->truck_arrival_time,
					'wi_subtype'=>$r->wi_subtype,
					'do_date'=>$r->do_date,
					'do_remarks'=>$r->do_remarks,
					'wi_approvestatus'=>'1',
					'wi_approveby'=>$this->session->userdata('usr_uname'),
					'wi_approvedatetime'=>$datetime,
					'wi_updateby'=>$this->session->userdata('usr_uname'),
					'wi_updatedatetime'=>$datetime
				);
				$q=$this->db->insert('do_appr',$data);
			}
		}

		$this->db->query("DELETE FROM do_nappr WHERE wi_id = '".$id."' ");

	}

	function get_wh_name(){

		$tokens2 = explode ('_',current_url());
		$id = $tokens2[sizeof($tokens2)-1];

		$this->db->where('wi_id', $id);
		$qry = $this->db->get('whir');

		if($qry->num_rows() == 1){
			return $qry->result();
		}

	}

	function bpselect_itemlist($get){
		$q=$this->db->query("SELECT b.comm__name,a.comm__id
			FROM mbir a
			LEFT OUTER JOIN ocmt b ON a.comm__id=b.comm__id
			WHERE a.CardCode='".$get."' AND a.bi_status=1
		");
		//$q=$this->db->get_where('mbir',array('CardCode'=>$get));
		if ($q->num_rows == true){
			return $q->result();
			echo 'me';
		}
	}
	function bpselect_whlist($select){
		$tokens = explode('_', $select);
		$get = $tokens[sizeof($tokens)-1];
		$q=$this->db->query("SELECT a.wh_code,a.wh_name,b.sqty,c.tqty,d.rqty 
			FROM mwhr a
			LEFT OUTER JOIN (
				SELECT wi_id,wi_type,wh_name,item_id,SUM(wi_itemqty) as sqty 	
				FROM whir 
				WHERE item_id='".$get."' AND wi_type=0 AND wi_status=1
				GROUP BY wh_name
			)b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN (
				SELECT wi_id,wi_type,wh_name,item_id,SUM(wi_itemqty) as tqty 	
				FROM whir 
				WHERE item_id='".$get."' AND wi_type=1 AND wi_status=1
				GROUP BY wh_name
			)c ON a.wh_name=c.wh_name
			LEFT OUTER JOIN (
				SELECT wi_id,wi_type,wh_name,item_id,SUM(wi_itemqty) as rqty 	
				FROM whir 
				WHERE item_id='".$get."' AND wi_type=2 AND wi_status=1
				GROUP BY wh_name
			)d ON a.wh_name=d.wh_name
			WHERE a.wh_status=1"
		);
		if ($q->num_rows==true){
			return $q->result();
		}
	}
	
	//TRANSACTION
	function translist(){
		$q=$this->db->query("SELECT a.wi_type,
		a.wh_name,
		a.wi_refnum,
		a.wi_refname,
		a.item_id,
		a.wi_itemqty,
		a.wi_status,
		a.wi_createby,
		a.wi_createdatetime,
		a.wi_updateby,
		a.wi_updatedatetime,
		a.deldate,
		b.memb__username AS cname,
		c.comm__name,
		d.memb__username AS uname
			FROM whir a 
			LEFT OUTER JOIN ousr b ON a.wi_createby=b.memb__id
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN ousr d ON a.wi_updateby=d.memb__id
			ORDER BY a.wh_name,a.wi_status"
			);
		if($q->num_rows==true){
			return $q->result();
		}
	}
	function whlist(){
		$q=$this->db->query("SELECT a.wh_code,
				a.wh_name,
				a.wh_status  FROM mwhr a 
			LEFT JOIN ouaw b ON a.wh_name = b.accessname
			where a.wh_status=1 and b.usercode='".$this->session->userdata('usr_uname')."' and b.status=1 
			ORDER BY a.wh_name 
		");
		if ($q->num_rows==true){
			foreach($q->result_array() as $r){
				$data[$r['wh_name']]=$r['wh_name'];
			}
			return $data;
		}
	}
	function all_whlist(){
		$this->db->order_by('wh_name');
		$q=$this->db->get_where('mwhr',array('wh_status'=>1));
		foreach($q->result_array() as $r){
			$data[$r['wh_name']]=$r['wh_name'];
		}
		return $data;
	}
	function whlist_sort_nodate(){
		$q=$this->db->query("SELECT a.wi_id,
		a.wi_type,
		a.wh_name,
		a.wi_reftype,
		a.wi_refnum,
		a.wi_reftype2,
		a.wi_refnum2,
		a.wi_refname,
		a.item_id,
		a.wi_itemqty,
		a.wi_status,
		a.wi_createby,
		a.wi_createdatetime,
		a.deldate,
		b.memb__username,
		c.comm__name,
		d.CardName AS CName,
		e.wh_name AS whse_name,
		a.wi_dtcode,
		CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
			FROM whir a 
			LEFT OUTER JOIN ousr b ON a.wi_createby=b.memb__id
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN (SELECT CardName,CardCode,Status FROM ocrd WHERE Status=1)d ON a.wi_refname=d.CardCode 
			LEFT OUTER JOIN (SELECT wh_code, wh_name, wh_status FROM mwhr WHERE wh_status=1)e ON a.wi_refname=e.wh_code
			WHERE a.wh_name='".$this->input->post('whlist')."' 
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_type=0
				AND a.wi_approvestatus=1
			OR a.wh_name='".$this->input->post('whlist')."'
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_type=1
				AND a.wi_approvestatus=1
			OR a.wh_name='".$this->input->post('whlist')."'
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_status=0
			ORDER BY DATE(wi_createdatetime) DESC"
			);
		if($q->num_rows==true){
			return $q->result();
		}
	}
	function whlist_sort(){
		$q=$this->db->query("SELECT a.wi_id,
		a.wi_type,
		a.wh_name,
		a.wi_reftype,
		a.wi_refnum,
		a.wi_reftype2,
		a.wi_refnum2,
		a.item_id,
		a.wi_itemqty,
		a.wi_status,
		a.wi_createby,
		a.wi_createdatetime,
		a.deldate,
		b.memb__username,
		c.comm__name,
		d.CardName AS CName,
		e.wh_name AS whse_name,
		a.wi_dtcode,
		CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
			FROM whir a 
			LEFT OUTER JOIN ousr b ON a.wi_createby=b.memb__id
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN (SELECT CardName,CardCode,Status FROM ocrd WHERE Status=1)d ON a.wi_refname=d.CardCode
			LEFT OUTER JOIN (SELECT wh_code, wh_name, wh_status FROM mwhr WHERE wh_status=1)e ON a.wi_refname=e.wh_code 
			WHERE a.wh_name='".$this->input->post('whlist')."' 
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_type=0
				AND a.wi_approvestatus=1
 				AND a.deldate 
					BETWEEN '".$this->input->post('sdate')."'
					AND '".$this->input->post('edate')."'
			OR a.wh_name='".$this->input->post('whlist')."'
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_type=1
				AND a.wi_approvestatus=1
				AND a.deldate
					BETWEEN '".$this->input->post('sdate')."'
					AND '".$this->input->post('edate')."'
			OR a.wh_name='".$this->input->post('whlist')."'
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_status=0
				AND a.deldate
					BETWEEN '".$this->input->post('sdate')."'
					AND '".$this->input->post('edate')."'
			ORDER BY DATE(wi_createdatetime) DESC"
			);
		if($q->num_rows==true){
			return $q->result();
		}
	}
	
	function userlist(){
		$this->db->order_by('memb__username');
		// $q=$this->db->get_where('ousr',array('memb__status'=>1));

		$q=$this->db->get('ousr');

		// $q = $this->db->query("SELECT *, MAX(b.log_time) AS log_time FROM ousr a
		// 					 JOIN sys_logs b ON b.log_info = a.memb__id
		// 					WHERE memb__status = 1");

		if($q->num_rows==true){
			return $q->result();
		}
	}
	function useraccesslist(){
		$this->db->order_by('name');
		$q=$this->db->get_where('oual',array('status'=>1));
		if($q->num_rows==true){
			foreach($q->result_array() as $r){
			$data[$r['name']]=$r['name'];
			}
			return $data;
		}
	}
	function username(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$q=$this->db->get_where('ousr',array('memb__id'=>$get));
		if($q->num_rows=true){
			return $q->result();
		}
	}
	function user_whauselist(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$q=$this->db->get_where('ouaw',array('usercode'=>$get,'status'=>'1'));
		if($q->num_rows==true){
			return $q->result();
		}
	}
	function user_accesslist(){
		$tokens = explode('/', current_url());
		$get = $tokens[sizeof($tokens)-1];
		$q=$this->db->get_where('ouar',array('usercode'=>$get,'status'=>'1'));
		if($q->num_rows==true){
			return $q->result();
		}
	}

	function check_if_user_id_exists(){

		$this->db->where('memb__id', $this->input->post('uname'));
		$qry = $this->db->get('ousr');

		if($qry->num_rows() > 0){
			return true;
		}
	}

	function check_if_fullname_exists(){

		$this->db->where('memb__username', $this->input->post('fullname'));
		$qry = $this->db->get('ousr');

		if($qry->num_rows() > 0){
			return true;
		}
	}

	function check_if_user_email_exists(){

		$this->db->where('memb__email', $this->input->post('eadd'));
		$qry = $this->db->get('ousr');

		if($qry->num_rows() > 0){
			return true;
		}
	}

	function check_edit_if_fullname_exists(){

		$fullname = $this->input->post('fullname');
		$id = $this->input->post('uname');
		$qry = $this->db->query("SELECT * FROM ousr WHERE memb__username = '$fullname' AND memb__id <> '$id' ");

		if($qry->num_rows() > 0){
			return true;
		}

	}

	function check_edit_if_email_exists(){

		$email = $this->input->post('eadd');

		if(!empty($email)){
			$id = $this->input->post('uname');

			$qry = $this->db->query("SELECT * FROM ousr WHERE memb__email = '$email' AND memb__id <> '$id' ");

			if($qry->num_rows() > 0){
				return true;
			}
		}

	}

	function add_user(){
		$data = array(
			'memb__id'=>$this->input->post('uname'),
			'memb__pword'=>md5($this->input->post('pass')),
			'memb__username'=>$this->input->post('fullname'),
			'memb__status'=>1,
			'memb__email'=>$this->input->post('eadd'),
			'memb__addr'=>$this->input->post('address'),
			'memb__gender'=>$this->input->post('gender'),
			'memb__tel'=>$this->input->post('mobile_no'),
			'memb_comp'=>$this->input->post('comp_name')
		);

		$this->db->insert('ousr', $data);
	}

	function user_edit(){

		if($this->input->post('pass') <> ""){
			$data = array(
				'memb__pword'=>md5($this->input->post('pass')),
				'memb__username'=>$this->input->post('fullname'),
				'memb__email'=>$this->input->post('eadd'),
				'memb__addr'=>$this->input->post('address'),
				'memb__gender'=>$this->input->post('gender'),
				'memb__tel'=>$this->input->post('mobile_no'),
				'memb_comp'=>$this->input->post('comp_name'),
				'memb__status'=>$this->input->post('status')
			);

			$this->db->where('memb__id', $this->input->post('uname'));
			$this->db->update('ousr', $data);
		}else{
			$data = array(
				'memb__username'=>$this->input->post('fullname'),
				'memb__email'=>$this->input->post('eadd'),
				'memb__addr'=>$this->input->post('address'),
				'memb__gender'=>$this->input->post('gender'),
				'memb__tel'=>$this->input->post('mobile_no'),
				'memb_comp'=>$this->input->post('comp_name'),
				'memb__status'=>$this->input->post('status')
			);

			$this->db->where('memb__id', $this->input->post('uname'));
			$this->db->update('ousr', $data);
		}

	}

	function user_edit_records(){

		$id = $this->uri->segment(3);
		// $qry = $this->db->get_where('ousr', array('memb__id'=>$id));

		// $qry = $this->db->query("SELECT *, (SELECT MAX(log_time) FROM sys_logs c WHERE c.log_info = a.memb__id ) AS log_time FROM ousr a 
								
		// 						WHERE memb__status = 1 AND memb__id = '$id' ");

		$qry = $this->db->query("SELECT *, (SELECT MAX(log_time) FROM sys_logs c WHERE c.log_info = a.memb__id ) AS log_time FROM ousr a 
								
								WHERE memb__id = '$id' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function check_if_user_can_delete(){

		$uid = $this->uri->segment(3);

		$this->db->where('wi_createby', $uid);
		$qry = $this->db->get('whir');

		if($qry->num_rows() > 0){
			return true;
		}

	}

	function access_add_module1(){
		//if ($this->input->post('whouse')<>'-Select-'){
		if(isset($_POST['whouse'])){
			foreach($_POST['whouse'] as $r){
				$q=$this->db->get_where('ouaw',
					array(
						'usercode'=>$this->input->post('uname'),
						'accessname'=>$r,
						'status'=>'1'
					)
				);
				if($q->num_rows == true){
					return true;
				}
			}
		}
			
		//}
	}
	function access_add_wh(){

		// $wh_access['whouse'] = $this->input->post('whouse');

		if(isset($_POST['whouse'])){
			foreach($_POST['whouse'] as $r){
				$data = array(
					'usercode'=>$this->input->post('uname'),
					'accessname'=>$r,
					'status'=>'1',
					'cby'=>$this->session->userdata('usr_uname')
				);
				$this->db->insert('ouaw',$data);
			}
		}

	}

	function access_update_wh(){

		if($_POST['whouse']){
			foreach($_POST['whouse'] as $r){
				date_default_timezone_set("Asia/Manila");
				$cdate = date('Y-m-d');
				$data = array(
					'status'=>'0',
					'uby'=>$this->session->userdata('usr_uname'),
					'udatetime'=>$cdate
				);
				$this->db->where(
					array(
						'usercode'=>$this->input->post('uname'),
						'accessname'=>$r,
						'status'=>'1'
					)
				);
				$this->db->update('ouaw',$data);
			}
		}

		
	}

	function access_add_module2(){
		
		if(isset($_POST['access'])){
			foreach($_POST['access'] as $r){
				$q=$this->db->get_where('ouar',
					array(
						'usercode'=>$this->input->post('uname'),
						'accessname'=>$r,
						'status'=>'1'
					)
				);
				if ($q->num_rows == true){
					return true;
				}
			}
		}
	
	}

	function access_update_mod(){

		if(isset($_POST['access'])){
			foreach($_POST['access'] as $r){
				date_default_timezone_set("Asia/Manila");
				$cdate = date('Y-m-d');
				$data = array(
					'status'=>'0',
					'uby'=>$this->session->userdata('usr_uname'),
					'udatetime'=>$cdate
				);
				$this->db->where(
					array(
						'usercode'=>$this->session->userdata('uname'),
						'accessname'=>$r,
						'status'=>'1'
					)
				);
				$this->db->update('ouar',$data);
			}
		}

	}

	function access_add_mod(){

		if(isset($_POST['access'])){
			foreach($_POST['access'] as $r){
				$data = array(
						'usercode'=>$this->input->post('uname'),
						'accessname'=>$r,
						'status'=>'1'
				);

				$this->db->insert('ouar',$data);
			}
		}
	}

	function access_valid(){
			$q=$this->db->query("SELECT *
				FROM ousr a
				INNER JOIN ouaw b 
					ON a.memb__id=b.usercode
				WHERE b.status='1' 
					AND a.memb__id='".$this->session->userdata('usr_uname')."'"
				);
			if($q->num_rows==true){
				return $q->result();
			}
	}
	function access_module_valid(){
			$q=$this->db->query("SELECT *
				FROM ousr a
				INNER JOIN ouar b ON a.memb__id=b.usercode
				WHERE a.memb__id='".$this->session->userdata('usr_uname')."'
				and b.accessname='Creation'"
				);
			if($q->num_rows==true){
				return true;
			}
		
	}
	function access_module_approve(){
		$q=$this->db->query("SELECT *
				FROM ousr a
				INNER JOIN ouar b 
					ON a.memb__id=b.usercode
				INNER JOIN oual c 
					ON b.accessname=c.name
				WHERE b.status='1' 
					AND a.memb__id='".$this->session->userdata('usr_uname')."'
					AND b.accessname <> 'Creation'
				ORDER BY c.list_sort"
				);
			if($q->num_rows==true){
				return $q->result();
			}
	}
	function SAP_item_add(){
		$q1=$this->db->get_where('ocmt',
			array(
				'comm__id'=>$this->input->post('whitem'),
				'status'=>1
			)
		);
		if($q1->num_rows() == false){
			$db2=$this->load->database('db2',TRUE);
			$q=$db2->query("SELECT
				ItemName,ItemCode,ItmsGrpCod
				FROM OITM 
				WHERE ItemCode='".$this->input->post('whitem')."'");
			if($q->num_rows()==true){
				foreach($q->result() as $r){
					$data = array(
					'comm__name'=>$r->ItemName,
					'comm__id'=>$r->ItemCode,
					'status'=>'1',
					'comm__grp'=>$r->ItmsGrpCod
				);
				$this->db->insert('ocmt',$data);
				}
			}
		}
		
	}

	function update_item_table(){

		$q1=$this->db->get('ocmt');

		foreach($q1->result() as $r1){
			$db2=$this->load->database('db2',TRUE);
			// $q=$db2->query("SELECT
			// 	ItemName,ItemCode,ItmsGrpCod
			// 	FROM OITM WHERE ItmsGrpCod='105'
			// AND U_Commodity = '02'
			// AND ItemCode <> ". $r1->comm__id ."
			// ORDER BY ItemName ");

			$q = $db2->where('U_Commodity', '02')
				     ->where('ItmsGrpCod', '105')
					 ->where('ItemCode <>', $r1->comm__id)
					 ->get('OITM');

			if($q->num_rows()==true){
				foreach($q->result() as $r){
					$data = array(
						'comm__name'=>$r->ItemName,
						'comm__id'=>$r->ItemCode,
						'status'=>'1',
						'comm__grp'=>$r->ItmsGrpCod
					);
					$this->db->insert('ocmt',$data);
				}
			}
		}

	}

	//User
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
	
	function register(){
		$this->load->library('form_validation');
		
		//$this->form_validation->set_rules('voucher', 'Voucher Number', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('department', 'Department', 'trim|required');
		$this->form_validation->set_rules('location', 'Location', 'trim|required');
		$this->form_validation->set_rules('userlevel', 'User Level', 'trim|required');
		
		if($this->form_validation->run()){
			//$this->db->where(
			
			$d = array(
				'memb__id' => $this->sanitize($this->input->post('username')),
				'memb__username' => $this->sanitize($this->input->post('name')),
				'memb__pword' => $this->sanitize(md5($this->input->post('password'))),
				'memb__email' => $this->sanitize($this->input->post('email')),
				'department' => $this->sanitize($this->input->post('department')),
				'location' => $this->sanitize($this->input->post('location')),
				'memb__status' => $this->sanitize($this->input->post('userlevel'))
			);
			if($this->db->insert('ousr',$d)){ return true; }
		}
		return false;
	}
	
	
	function login(){
		
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
		}
		return false;
    }
	
	function my_update(){
		$d = array(
			'memb__pword' => md5($this->input->post('pword')),
			'memb__email' => $this->input->post('email'),
			'memb__lastuser' => $this->session->userdata('username')
		);
		$this->db->where('memb__id',$this->input->post('usrcode'));
	}
	function out_del_info(){
		$tokens = explode('_', $this->uri->segment(4));
			$id = $tokens[sizeof($tokens)-1];
		// $q=$this->db->get_where('whir',
				// array(
					// 'wi_id'=>$tokens[sizeof($tokens)-1],
					// 'wi_status'=>'1'
				// )
			// );
			$q=$this->db->query("SELECT *
				FROM whir a 
				WHERE wi_id='".$tokens[sizeof($tokens)-1]."' 
				AND wi_status='1'");
			if ($q->num_rows == true){
				return $q->result();
			}
	}
	function wh_delivery_unserve_list_active_search(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT a.wi_type,
			a.wi_reftype2,
			a.wi_reftype,
			a.wi_refnum2,
			a.wi_approvedatetime,
			a.wi_refnum,
			a.wi_refname,
			a.wi_id,
			a.wh_name,
			a.wi_refnum,
			a.wi_refname,
			a.item_id,
			a.wi_remarks,
			a.wi_approvestatus,
			a.deldate,
			a.wi_doqty,
			a.wi_itemqty,wh_code,
			c.comm__name,
			d.CardName AS CName,
			a.wi_expecteddeliverydate,
			a.wi_doqty - a.wi_itemqty AS RMN_QTY,
			(SELECT IFNULL(SUM(wi_itemqty),0) FROM whir WHERE wi_doqty = a.wi_doqty) AS ITEMQTY_TEMP,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
		FROM whir a
		LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
		LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
		LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
		WHERE wi_type=1
			AND wi_doqty <> 0
			AND udo_active <> 1 
			AND wi_status=1 
			AND a.wh_name='".$this->input->post('whouse')."' 
		ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function wh_delivery_unserve_list_active_search_date_search(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT a.wi_type,
			a.wi_reftype2,
			a.wi_reftype,
			a.wi_refnum2,
			a.wi_approvedatetime,
			a.wi_refnum,
			a.wi_refname,
			a.wi_id,
			a.wh_name,
			a.wi_refnum,
			a.wi_refname,
			a.item_id,
			a.wi_remarks,
			a.wi_approvestatus,
			a.deldate,
			a.wi_doqty,
			a.wi_itemqty,wh_code,
			c.comm__name,
			d.CardName AS CName,
			a.wi_expecteddeliverydate,
			a.wi_doqty - a.wi_itemqty AS RMN_QTY,
			(SELECT IFNULL(SUM(wi_itemqty),0) FROM whir WHERE wi_reftype2 = a.wi_reftype2 AND wi_refnum2 = a.wi_refnum2) AS ITEMQTY_TEMP,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
		FROM whir a
		LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
		LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
		LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
		WHERE wi_type=1
			AND wi_doqty <> 0 
			AND wi_status=1
			AND udo_active <> 1
			AND a.wh_name='".$this->input->post('whouse')."' 
			AND a.deldate
				BETWEEN '".$this->input->post('sdate')." 00:00:01'
				AND '".$this->input->post('edate')." 23:59:59'
		ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function get_customers_with_do(){
		$q = $this->db->get_where('whir',array('wi_reftype' => 'DO'));
		if($q->num_rows()){
			return $q->result();
		}
	}
	function get_do_summary($a){
		//$q = $this->db->get_where('whir',array('wi_refname' => $a));
		$q = $this->db->query("
			SELECT wi_refname,wi_refnum,wi_doqty,wi_itemqty,wi_doqty-wi_itemqty as 'TRQ' FROM whir WHERE wi_refname = '$a'
		"); 
		if($q->num_rows()){
			return $q->result();
		}
	}
	function validation_del_out_ref(){
		$q = $this->db->query("
			SELECT *
			FROM whir
			WHERE wi_type='1'
				AND wi_status='1'
				AND wi_reftype='".$this->input->post('doctype1')."'
				AND wi_refnum='".$this->input->post('ref')."'
				AND wh_name='".$this->input->post('wh')."'
			OR wi_type='1'
				AND wi_status='1'
				AND wi_reftype='".$this->input->post('doctype2')."'
				AND wi_refnum='".$this->input->post('ref2')."'
				AND wh_name='".$this->input->post('wh')."'
			OR wi_type='2'
				AND wi_status='1'
				AND wi_reftype='".$this->input->post('doctype1')."'
				AND wi_refnum='".$this->input->post('ref')."'
				AND wh_name='".$this->input->post('wh')."'
			OR wi_type='2'
				AND wi_status='1'
				AND wi_reftype='".$this->input->post('doctype2')."'
				AND wi_refnum='".$this->input->post('ref2')."'
				AND wh_name='".$this->input->post('wh')."'
		");
		if($q->num_rows() == true){
			return true;
		}
	}

	function validation_del_in_ref(){
		if ($this->input->post('doctype1') <> 'DO'){
			if($this->input->post('doctype2') <> 'DO'){
				if ($this->input->post('doctype1') <> 'ITO'){
					if($this->input->post('doctype2') <> 'ITO'){
						$q = $this->db->query("
							SELECT *
							FROM whir
							WHERE wi_type='0'
								AND wi_status='1'
								AND wi_reftype='".$this->input->post('doctype1')."'
								AND wi_refnum='".$this->input->post('ref')."'
								AND wh_name='".$this->input->post('wh')."'
							OR wi_type='0'
								AND wi_status='1'
								AND wi_reftype='".$this->input->post('doctype2')."'
								AND wi_refnum='".$this->input->post('ref2')."'
								AND wh_name='".$this->input->post('wh')."'
							OR wi_type='2'
								AND wi_status='1'
								AND wi_reftype2='".$this->input->post('doctype1')."'
								AND wi_refnum2='".$this->input->post('ref')."'
								AND wh_name='".$this->input->post('wh')."'
							OR wi_type='2'
								AND wi_status='1'
								AND wi_reftype2='".$this->input->post('doctype2')."'
								AND wi_refnum2='".$this->input->post('ref2')."'
								AND wh_name='".$this->input->post('wh')."'
							
						");
						if($q->num_rows() == true){
							return true;
							echo 'Yes';
						}
					}
				}
			}
		}
	}

	
	function check_dr_if_exists_1(){
		if($this->input->post('doctype1') == 'DR'){
			
			$dr = $this->input->post('ref');
			
			$qry = $this->db->query("SELECT * FROM whir
									WHERE wi_deltype = 'Delivery Out' AND wi_reftype = 'DR' 
									AND wi_refnum = '$dr' AND wi_status = 1 ");

			if($qry->num_rows() == TRUE){
				return true;
			}

		}
	}

	function check_dr_if_exists_1_sap(){
		if($this->input->post('doctype1') == 'DR'){
			
			$dr = $this->input->post('ref');
			
			$qry = $this->db->query("SELECT * FROM whir
									WHERE wi_deltype = 'Delivery Out' AND wi_reftype = 'DR' 
									AND wi_refnum = '$dr' AND wi_status = 1 ");

			if($qry->num_rows() == TRUE){
				return true;
			}

		}
	}

	function check_dr_if_exists_2(){
		if($this->input->post('doctype2') == 'DR'){
			
			$do = $this->input->post('ref');
			$dr = $this->input->post('ref2');
			
			$qry = $this->db->query("SELECT * FROM whir
									WHERE wi_deltype = 'Delivery Out' AND wi_reftype2 = 'DR' 
									AND wi_refnum2 = '$dr' AND wi_refnum = '$do' AND wi_status = 1 ");

			if($qry->num_rows() == TRUE){
				return true;
			}

		}
	}

	function check_dr_if_exists_2_sap(){
		if($this->input->post('doctype2') == 'DR'){
			
			$dr = $this->input->post('ref2');
			
			$qry = $this->db->query("SELECT * FROM whir
									WHERE wi_deltype = 'Delivery Out' AND wi_reftype2 = 'DR' 
									AND wi_refnum2 = '$dr' AND wi_status = 1 ");

			if($qry->num_rows() == TRUE){
				return true;
			}

		}
	}

	function check_wis_if_exists_1(){
		if($this->input->post('doctype1') == 'WIS'){

			$wis = $this->input->post('ref');

			$qry = $this->db->query("SELECT * FROM whir
									WHERE wi_deltype = 'Delivery Out' AND wi_reftype = 'WIS'
									AND wi_refnum = '$wis' AND wi_status = 1");

			if($qry->num_rows() == TRUE){
				return true;
			}

		}
	}

	function check_wis_if_exists_1_sap(){
		if($this->input->post('doctype1') == 'WIS'){

			$wis = $this->input->post('ref');

			$qry = $this->db->query("SELECT * FROM whir
									WHERE wi_deltype = 'Delivery Out' AND wi_reftype = 'WIS'
									AND wi_refnum = '$wis' AND wi_status = 1");

			if($qry->num_rows() == TRUE){
				return true;
			}

		}
	}

	function check_wis_if_exists_2(){
		if($this->input->post('doctype2') == 'WIS'){

			$wis = $this->input->post('ref2');

			$qry = $this->db->query("SELECT * FROM whir
									WHERE wi_deltype = 'Delivery Out' AND wi_reftype2 = 'WIS'
									AND wi_refnum2 = '$wis' AND wi_status = 1");

			if($qry->num_rows() == TRUE){
				return true;
			}

		}
	}

	function check_wis_if_exists_2_sap(){
		if($this->input->post('doctype2') == 'WIS'){

			$wis = $this->input->post('ref2');

			$qry = $this->db->query("SELECT * FROM whir
									WHERE wi_deltype = 'Delivery Out' AND wi_reftype2 = 'WIS'
									AND wi_refnum2 = '$wis' AND wi_status = 1");

			if($qry->num_rows() == TRUE){
				return true;
			}

		}
	}

	function wh_delivery_rr_summary_search(){
		
		$qry = $this->db->query("SELECT * FROM dspr_rr a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}
	
	function wh_delivery_rr_summary_date_search(){
		
		$qry = $this->db->query("SELECT * FROM dspr_rr a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."'
									AND a.posting_date
										BETWEEN '".$this->input->post('sdate')."'
	 									AND '".$this->input->post('edate')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function ito_summary(){
		
		// if submitted update mand first
		
		if($this->input->post()){
			$this->update_running_balance();
		}
		
		// condition
		
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		
		if ($this->input->post('sdate')  == '' AND $this->input->post('edate') == ''){
			//$data['reserverecord']=$this->usermodel->wh_delivery_rr_summary_search();
			$q = $this->db->query("SELECT 
				a.wi_refnum,
				a.wi_refnum2,
				a.wh_name,
				a.wi_reftype,
				a.wi_reftype2,
				a.deldate,
				a.item_id,
				a.wi_doqty,
				a.wi_itemqty,
				c.comm__name,
				a.wi_doqty,
				a.wi_deltype,
				(SELECT com_qty FROM mand WHERE ref_type = a.wi_reftype AND ref_num = a.wi_refnum OR ref_type = a.wi_reftype2 AND ref_num = a.wi_refnum2 ) AS 'Received',
				(SELECT rem_qty FROM mand WHERE ref_type = a.wi_reftype AND ref_num = a.wi_refnum OR ref_type = a.wi_reftype2 AND ref_num = a.wi_refnum2 ) AS 'InTransit',
				a.wi_remarks,
				CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
			FROM whir a
			LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
			WHERE wi_type=0
				AND wi_status=1 
				AND wi_reftype = 'ITO'
				AND a.wh_name='".$this->input->post('whouse')."' 
			OR wi_type=0
				AND wi_status=1 
				AND wi_reftype2 ='ITO'
				AND a.wh_name='".$this->input->post('whouse')."' 
			ORDER BY wi_id");
		}
		else{
			//$data['reserverecord']=$this->usermodel->wh_delivery_rr_summary_date_search();
			$q = $this->db->query("SELECT
				a.wi_refnum,
				a.wi_refnum2,
				a.wh_name,
				a.wi_reftype,
				a.wi_reftype2,
				a.deldate,
				a.item_id,
				a.wi_doqty,
				a.wi_itemqty,
				c.comm__name,
				a.wi_doqty,
				a.wi_deltype,
				(SELECT com_qty FROM mand WHERE ref_type = a.wi_reftype AND ref_num = a.wi_refnum OR ref_type = a.wi_reftype2 AND ref_num = a.wi_refnum2 ) AS 'Received',
				(SELECT rem_qty FROM mand WHERE ref_type = a.wi_reftype AND ref_num = a.wi_refnum OR ref_type = a.wi_reftype2 AND ref_num = a.wi_refnum2 ) AS 'InTransit',
				a.wi_remarks,
				CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
			FROM whir a
			LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
			WHERE wi_type=0
				AND wi_status=1 
				AND wi_reftype ='ITO'
				AND a.wh_name='".$this->input->post('whouse')."'
				AND a.wi_createdatetime 
					BETWEEN '".$this->input->post('sdate')." 00:00:01'
						AND '".$this->input->post('edate')." 23:59:59'
			OR wi_type=0
				AND wi_status=1 
				AND wi_reftype2 ='ITO'
				AND a.wh_name='".$this->input->post('whouse')."' 
				AND a.wi_createdatetime 
					BETWEEN '".$this->input->post('sdate')." 00:00:01'
						AND '".$this->input->post('edate')." 23:59:59'
			ORDER BY wi_id");
		}
		
		if($q->num_rows() == true){
			return $q->result();
		}
	}
	
	function jo_summary(){
		
		//Customer , JO No., Item No, Description, Quantity on Job Order
		/*$q = $this->db->query("
			SELECT 
			c.CardName,
			case 
				when a.wi_reftype = 'JO' THEN a.wi_refnum 
				when a.wi_reftype2 = 'JO' THEN a.wi_refnum2 
				ELSE ''
			END AS 'JONumber',
			a.item_id,
			b.comm__name,
			a.wi_itemqty
			FROM whir a
			LEFT OUTER JOIN ocmt b ON a.item_id=b.comm__id
			LEFT OUTER JOIN ocrd c ON a.wi_refname=c.CardCode
			WHERE wi_deltype = 'Material Management' AND wi_reftype OR wi_reftype2 = 'JO' 
		");*/
		
		if ($this->input->post('sdate')  == '' AND $this->input->post('edate') == ''){
			//$data['reserverecord']=$this->usermodel->wh_delivery_rr_summary_search();
			$q = $this->db->query("SELECT 
				d.CardName,
				case 
					when a.wi_reftype = 'JO' THEN a.wi_refnum 
					when a.wi_reftype2 = 'JO' THEN a.wi_refnum2 
					ELSE ''
				END AS 'JONumber',
				a.item_id,
				c.comm__name,
				a.wi_itemqty
			FROM whir a
			LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
			WHERE wi_type=0
				AND wi_status=1 
				AND wi_reftype = 'JO'
				AND a.wh_name='".$this->input->post('whouse')."' 
			OR wi_type=0
				AND wi_status=1 
				AND wi_reftype2 ='JO'
				AND a.wh_name='".$this->input->post('whouse')."' 
			ORDER BY wi_id");
		}
		else{
			//$data['reserverecord']=$this->usermodel->wh_delivery_rr_summary_date_search();
			$q = $this->db->query("SELECT
				d.CardName,
				case 
					when a.wi_reftype = 'JO' THEN a.wi_refnum 
					when a.wi_reftype2 = 'JO' THEN a.wi_refnum2 
					ELSE ''
				END AS 'JONumber',
				a.item_id,
				c.comm__name,
				a.wi_itemqty
			FROM whir a
			LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
			WHERE wi_type=0
				AND wi_status=1 
				AND wi_reftype ='JO'
				AND a.wh_name='".$this->input->post('whouse')."'
				AND a.wi_createdatetime 
					BETWEEN '".$this->input->post('sdate')." 00:00:01'
						AND '".$this->input->post('edate')." 23:59:59'
			OR wi_type=0
				AND wi_status=1 
				AND wi_reftype2 ='JO'
				AND a.wh_name='".$this->input->post('whouse')."' 
				AND a.wi_createdatetime 
					BETWEEN '".$this->input->post('sdate')." 00:00:01'
						AND '".$this->input->post('edate')." 23:59:59'
			ORDER BY wi_id");
		}
		
		if($q->num_rows() == true){
			return $q->result();
		}
	}
	
	
	// function wh_delivery_dr_summary_search(){
	// 	if ($this->input->post('doctype') == '-Select-'){
	// 		$dtype="";
	// 	}
	// 	else{
	// 		$dtype=$this->input->post('doctype');
	// 	}
	// 	$q = $this->db->query("SELECT a.wi_reftype2,
	// 		a.wi_reftype,
	// 		a.wi_refnum2,
	// 		a.wi_approvedatetime,
	// 		a.wi_refnum,
	// 		a.wi_refname,
	// 		a.wi_id,
	// 		a.wh_name,
	// 		a.wi_refnum,
	// 		a.wi_refname,
	// 		a.item_id,
	// 		a.wi_approvestatus,
	// 		a.deldate,
	// 		a.wi_doqty,
	// 		wi_itemqty,wh_code,
	// 		c.comm__name,
	// 		d.CardName AS CName,
	// 		a.wi_type,
	// 		a.item_id,
	// 		c.comm__id,
	// 		a.wi_exactdeliverydate,
	// 		a.wi_expecteddeliverydate,
	// 		a.trk_accepteddate,
	// 		a.trk_acceptedremarks,
	// 		a.truck_driver,
	// 		a.truck_platenum,
	// 		a.wi_createdatetime,
	// 		a.ship_date,
	// 		CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
	// 	FROM whir a
	// 	LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
	// 	LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
	// 	LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
	// 	WHERE wi_type in (1,2)
	// 		AND wi_approvestatus = 1 
	// 		AND wi_status=1 
	// 		AND wi_reftype ='DR'
	// 		AND a.wh_name='".$this->input->post('whouse')."' 
	// 		AND d.CardName LIKE '%".$this->input->post('refname')."%'
	// 	OR wi_type in (1,2)
	// 		AND wi_approvestatus = 1 
	// 		AND wi_status=1 
	// 		AND wi_reftype2 ='DR'
	// 		AND a.wh_name='".$this->input->post('whouse')."'
	// 		AND d.CardName LIKE '%".$this->input->post('refname')."%'
	// 	ORDER BY wi_id");
	// 	if($q->num_rows() == true){
	// 		return $q->result();
	// 	}
	// }
	
	// function wh_delivery_dr_summary_date_search(){
	// 	if ($this->input->post('doctype') == '-Select-'){
	// 		$dtype="";
	// 	}
	// 	else{
	// 		$dtype=$this->input->post('doctype');
	// 	}
	// 	$q = $this->db->query("SELECT a.wi_reftype2,
	// 		a.wi_reftype,
	// 		a.wi_refnum2,
	// 		a.wi_approvedatetime,
	// 		a.wi_refnum,
	// 		a.wi_refname,
	// 		a.wi_id,
	// 		a.wh_name,
	// 		a.wi_refnum,
	// 		a.wi_refname,
	// 		a.item_id,
	// 		a.wi_approvestatus,
	// 		a.deldate,
	// 		a.wi_doqty,
	// 		wi_itemqty,wh_code,
	// 		c.comm__name,
	// 		d.CardName AS CName,
	// 		a.wi_type,
	// 		a.item_id,
	// 		a.wi_exactdeliverydate,
	// 		a.wi_expecteddeliverydate,
	// 		a.trk_accepteddate,
	// 		a.trk_acceptedremarks,
	// 		a.truck_platenum,
	// 		a.truck_driver,
	// 		a.wi_createdatetime,
	// 		a.ship_date,
	// 		CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
	// 	FROM whir a
	// 	LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
	// 	LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
	// 	LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
	// 	WHERE wi_type in (1,2)
	// 		AND wi_approvestatus = 1  
	// 		AND wi_status=1 
	// 		AND wi_reftype ='DR'
	// 		AND a.wh_name='".$this->input->post('whouse')."'
	// 		AND a.deldate
	// 			BETWEEN '".$this->input->post('sdate')." 00:00:00'
	// 				AND '".$this->input->post('edate')." 00:00:00'
	// 	OR wi_type in (1,2)
	// 		AND wi_approvestatus = 1 
	// 		AND wi_status=1 
	// 		AND wi_reftype2 ='DR'
	// 		AND a.wh_name='".$this->input->post('whouse')."'
	// 		AND a.deldate
	// 			BETWEEN '".$this->input->post('sdate')." 00:00:00'
	// 				AND '".$this->input->post('edate')." 00:00:00'
	// 	ORDER BY wi_id");
	// 	if($q->num_rows() == true){
	// 		return $q->result();
	// 	}
	// }

	function wh_delivery_dr_summary_search(){
		
		$qry = $this->db->query("SELECT * FROM dspr_dr a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}
	function wh_delivery_dr_summary_date_search(){
		
		$qry = $this->db->query("SELECT * FROM dspr_dr a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."'
									AND a.posting_date
										BETWEEN '".$this->input->post('sdate')."'
	 									AND '".$this->input->post('edate')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function wh_delivery_wis_summary_search(){
		
		$qry = $this->db->query("SELECT * FROM dspr_wis a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}
	function wh_delivery_wis_summary_date_search(){
		
		$qry = $this->db->query("SELECT * FROM dspr_wis a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."'
									AND a.posting_date
										BETWEEN '".$this->input->post('sdate')."'
	 									AND '".$this->input->post('edate')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	// function wh_delivery_wis_summary_search(){
	// 	if ($this->input->post('doctype') == '-Select-'){
	// 		// $dtype="";
	// 	}
	// 	else{
	// 		$dtype=$this->input->post('doctype');
	// 	}
	// 	$q = $this->db->query("SELECT a.wi_reftype2,
	// 		a.wi_reftype,
	// 		a.wi_refnum2,
	// 		a.wi_approvedatetime,
	// 		a.wi_refnum,
	// 		a.wi_refname,
	// 		a.wi_id,
	// 		a.wh_name,
	// 		a.wi_refnum,
	// 		a.wi_refname,
	// 		a.item_id,
	// 		a.wi_PONum,
	// 		a.wi_remarks,
	// 		a.wi_approvestatus,
	// 		a.deldate,
	// 		a.wi_doqty,
	// 		wi_itemqty,wh_code,
	// 		c.comm__name,
	// 		d.CardName AS CName,
	// 		a.wi_type,
	// 		a.truck_platenum,
	// 		a.wi_createdatetime,
	// 		CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
	// 	FROM whir a
	// 	LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
	// 	LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
	// 	LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
	// 	WHERE wi_type in (1,2)
	// 		AND wi_approvestatus = 1  
	// 		AND wi_status=1 
	// 		AND wi_reftype ='WIS'
	// 		AND a.wh_name='".$this->input->post('whouse')."' 
	// 	OR wi_type in (1,2)
	// 		AND wi_approvestatus = 1 
	// 		AND wi_status=1 
	// 		AND wi_reftype2 ='WIS'
	// 		AND a.wh_name='".$this->input->post('whouse')."'
	// 	ORDER BY wi_id");
	// 	if($q->num_rows() == true){
	// 		return $q->result();
	// 	}
	// }
	// function wh_delivery_wis_summary_date_search(){
	// 	if ($this->input->post('doctype') == '-Select-'){
	// 		$dtype="";
	// 	}
	// 	else{
	// 		$dtype=$this->input->post('doctype');
	// 	}
	// 	$q = $this->db->query("SELECT a.wi_reftype2,
	// 		a.wi_reftype,
	// 		a.wi_refnum2,
	// 		a.wi_approvedatetime,
	// 		a.wi_refnum,
	// 		a.wi_refname,
	// 		a.wi_id,
	// 		a.wh_name,
	// 		a.wi_refnum,
	// 		a.wi_refname,
	// 		a.item_id,
	// 		a.wi_PONum,
	// 		a.wi_remarks,
	// 		a.wi_approvestatus,
	// 		a.deldate,
	// 		a.wi_doqty,
	// 		wi_itemqty,wh_code,
	// 		c.comm__name,
	// 		d.CardName AS CName,
	// 		a.wi_type,
	// 		a.truck_platenum,
	// 		a.wi_createdatetime,
	// 		CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
	// 	FROM whir a
	// 	LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
	// 	LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
	// 	LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
	// 	WHERE wi_type in (1,2)
	// 		AND wi_approvestatus = 1  
	// 		AND wi_status=1 
	// 		AND wi_reftype ='WIS'
	// 		AND a.wh_name='".$this->input->post('whouse')."' 
	// 		AND a.deldate
	// 			BETWEEN '".$this->input->post('sdate')." 00:00:01'
	// 				AND '".$this->input->post('edate')." 23:59:59'
	// 	OR wi_type in (1,2)
	// 		AND wi_approvestatus = 1 
	// 		AND wi_status=1 
	// 		AND wi_reftype2 ='WIS'
	// 		AND a.wh_name='".$this->input->post('whouse')."'
	// 		AND a.deldate
	// 			BETWEEN '".$this->input->post('sdate')." 00:00:01'
	// 				AND '".$this->input->post('edate')." 23:59:59'
	// 	ORDER BY wi_id");
	// 	if($q->num_rows() == true){
	// 		return $q->result();
	// 	}
	// }

	function etype_active_list(){
		$q=$this->db->get_where('aoer',array('status'=>1));
		foreach ($q->result_array() as $r){
			$data[$r['type']]=$r['type'];
		}
		return $data;
	}
	function del_in_emailnotif_list(){
		$q=$this->db->get_where('oedr',array('wh_code'=>$this->input->post('wh'),'status'=>1
		));
		foreach ($q->result_array() as $r){
			$data[$r['emailadd']]=$r['emailadd'];
		}
		return $data;
	}
	function get_item_active(){
		
		$id = $this->uri->segment(3);
		$q=$this->db->query("SELECT a.wi_type,
			a.wh_name,
			(SELECT CardName FROM ocrd WHERE CardCode = a.wi_refname) AS CName,
			a.wi_reftype,
			a.wi_refnum,
			a.wi_reftype2,
			a.wi_reftype3,
			a.wi_reftype4,
			a.wi_refnum2,
			a.wi_refnum3,
			a.wi_refnum4,
			a.wi_LOINum,
			a.wi_PONum,
			a.deldate,
			a.wi_createdatetime,
			(SELECT comm__name FROM ocmt WHERE comm__id = a.item_id) 'comm__name',
			a.item_uom,
			a.wi_itemqty,
			a.truck_company,
			a.truck_driver,
			a.truck_platenum,
			a.wi_remarks,
			a.wi_doqty,
			a.rr_category,
			a.wi_itoqty,
			a.wi_intransit,
			a.wi_location,
			a.trk_arrivedstatus,
			a.trk_arrivedremarks,
			a.trk_arriveddate,
			a.trk_arrivedtime,
			a.trk_acceptedstatus,
			a.trk_acceptedremarks,
			a.trk_accepteddate,
			a.trk_acceptedutime,
			a.trk_acceptedqty,
			a.trk_canceledstatus,
			a.trk_canceledremarks,
			a.trk_canceleddate,
			a.trk_canceledqty,
			a.email_cdel,
			a.wi_expecteddeliverydate,
			a.do_date,
			a.do_remarks,
			a.pbatch_code,
			a.catalog_no,
			a.truck_arrival_time,
			a.ship_time,
			a.note1,
			a.note2,
			a.prepared_by,
			a.checked_by,
			a.guard_duty,
			a.seal,
			a.ref_print,
			a.do_series_no,
			a.wi_transno,
			CASE WHEN (SELECT CardName FROM ocrd WHERE CardCode = a.wi_refname) IS NULL THEN a.wi_refname ELSE (SELECT CardName FROM ocrd WHERE CardCode = a.wi_refname) END AS CardName
			FROM whir a
			LEFT OUTER JOIN ocmt b ON a.item_id=b.comm__id
			LEFT OUTER JOIN ocrd c ON a.wi_refname=c.CardCode
			WHERE a.wi_id='$id'
		");
		if ($q->num_rows() == true){
			return $q->result();
		}
	}
	
	function wh_delivery_condel_list(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}

		$q = $this->db->query("SELECT * FROM cdel WHERE src_whse = '". $this->input->post('whouse')."' AND confirm = 1");

		if($q->num_rows() > 0){
			return $q->result();
		}

	}

	function wh_delivery_trckng_list(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		if($this->input->post('modname') == 'AGTI'){
			$truck_comp = 'AGTI';
		}elseif($this->input->post('modname') == 'WEI'){
			$truck_comp = 'WEI';
		}else{
			$truck_comp = '%';
		}

		$q = $this->db->query("SELECT a.wi_type,
			a.wi_reftype2,
			a.wi_reftype,
			a.wi_refnum2,
			a.wi_approvedatetime,
			a.wi_refnum,
			a.wi_refname,
			a.wi_id,
			a.wh_name,
			a.wi_refnum,
			a.wi_refname,
			a.item_id,
			a.wi_approvestatus,
			a.deldate,
			a.wi_exactdeliverydate,
			wi_itemqty,
			wh_code,c.comm__name,
			d.CardName AS CName,
			a.wi_type AS type,
			a.wi_deliveredstatus,
			a.truck_company,
			a.truck_platenum,
			a.truck_driver,
			a.trk_arrivedstatus,
			a.trk_acceptedstatus,
			a.trk_canceledstatus,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
			FROM whir a
			LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
			WHERE wi_type = 1
				AND a.wh_name='".$this->input->post('whouse')."'
				AND a.wi_refnum LIKE '%".$this->input->post('refno')."%'
				AND wi_deliveredstatus IN (0,1)
				AND wi_status=1
				AND wi_deltype IN ('Delivery In','Delivery Out')
				AND email_cdel=0
				AND truck_company LIKE '$truck_comp'
				AND wi_reftype <> 'Count Sheet'
				AND wi_createdatetime
					BETWEEN '2016-08-01 00:00:01'
					AND '2099-01-01 23:59:59'
			OR wi_type = 1
				AND a.wh_name='".$this->input->post('whouse')."'
				AND a.wi_refnum2 LIKE '%".$this->input->post('refno')."%'
				AND wi_deliveredstatus IN (0,1)
				AND wi_status=1
				AND wi_deltype IN ('Delivery In','Delivery Out')
				AND email_cdel=0
				AND truck_company LIKE '$truck_comp'
				AND wi_reftype <> 'Count Sheet'
				AND wi_createdatetime
					BETWEEN '2016-08-01 00:00:01'
					AND '2099-01-01 23:59:59'
			ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function wh_delivery_trckng_list_internal(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		if($this->input->post('modname') == 'AGTI'){
			$truck_comp = 'AGTI';
		}elseif($this->input->post('modname') == 'WEI'){
			$truck_comp = 'WEI';
		}else{
			$truck_comp = '%';
		}

		$q = $this->db->query("SELECT a.wi_type,
			a.wi_reftype2,
			a.wi_reftype,
			a.wi_refnum2,
			a.wi_approvedatetime,
			a.wi_refnum,
			a.wi_refname,
			a.wi_id,
			a.wh_name,
			a.wi_refnum,
			a.wi_refname,
			a.item_id,
			a.wi_approvestatus,
			a.deldate,
			a.wi_exactdeliverydate,
			wi_itemqty,
			wh_code,c.comm__name,
			d.CardName AS CName,
			a.wi_type AS type,
			a.wi_deliveredstatus,
			a.truck_company,
			a.truck_platenum,
			a.truck_driver,
			a.trk_arrivedstatus,
			a.trk_acceptedstatus,
			a.trk_canceledstatus,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
			FROM whir a
			LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
			WHERE wi_type = 1
				AND a.wh_name='".$this->input->post('whouse')."'
				AND a.wi_refnum LIKE '%".$this->input->post('refno')."%'
				AND wi_deliveredstatus IN (0,1)
				AND wi_status=1
				AND wi_deltype IN ('Delivery In','Delivery Out')
				AND email_cdel=0
				AND truck_company LIKE '$truck_comp'
				AND wi_reftype <> 'Count Sheet'
				AND wi_createdatetime
					BETWEEN '2013-01-01 00:00:01'
					AND '2016-07-31 23:59:59'
			OR wi_type = 1
				AND a.wh_name='".$this->input->post('whouse')."'
				AND a.wi_refnum2 LIKE '%".$this->input->post('refno')."%'
				AND wi_deliveredstatus IN (0,1)
				AND wi_status=1
				AND wi_deltype IN ('Delivery In','Delivery Out')
				AND email_cdel=0
				AND truck_company LIKE '$truck_comp'
				AND wi_reftype <> 'Count Sheet'
				AND wi_createdatetime
					BETWEEN '2013-01-01 00:00:01'
					AND '2016-07-31 23:59:59'
			ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	// function trckng_update(){
		// if($q = $this->trckng_arrived_validation() == true and $this->input->post('arrived') == true){
			// $this->trckng_arrived();
		// }
		// if($this->input->post('qty') == $this->input->post('aqa')+ $this->input->post('aqc')){
			// if($q = $this->trckng_accepted_validation() == true and $this->input->post('accepted') == true){
				// $this->trckng_accepted();
			// }
			// if($q = $this->trckng_accepted_validation() == true and $this->input->post('accepted') == true){
				// $this->trckng_accepted();
			// }
		// }
		// else{
			// $data['error']="Invalid Quantity Accepted + Quantity Canceled";
				// $this->load->view('header',$data);
				// $this->load->view('main/wh_del_trckng_monitor',$data);
				// $this->load->view('footer',$data);
		// }
	// }

	function trckng_arrived_validation(){
		$q = $this->db->get_where('whir',array(
			'trk_arrivedstatus'=>0,
			'wi_id'=>$this->uri->segment(3)));
		if($q->num_rows() == true){
			return true;
		}
	}
	function trckng_accepted_validation(){
		$q = $this->db->get_where('whir',array(
			'trk_acceptedstatus'=>0,
			'wi_id'=>$this->uri->segment(3)));
		if($q->num_rows() == true){
			return true;
		}
	}
	function trckng_canceled_validation(){
		$q = $this->db->get_where('whir',array(
			'trk_canceledstatus'=>0,
			'wi_id'=>$this->uri->segment(3)));
		if($q->num_rows() == true){
			return true;
		}
	}
	function trckng_arrived(){
		date_default_timezone_set("Asia/Manila");
		$cdate = date('Y-m-d');
		$atime = $this->input->post('arr_time');
		// $atime = preg_replace('/\s+/', '', $str);
		$data=array(
			'trk_arrivedstatus'=>1,
			'trk_arrivedremarks'=>$this->input->post('arrRemarks'),
			'trk_arriveddate'=>$this->input->post('arrdate'),
			'trk_arrivedtime'=>$atime,
			'trk_arrivedcby'=>$this->session->userdata('usr_uname'),
			'trk_arrivedcdatetime'=>$cdate
		);
		$this->db->where('wi_id',$this->uri->segment(3));
		$this->db->update('whir',$data);
	}
	function trckng_accepted(){
		date_default_timezone_set("Asia/Manila");
		$cdate = date('Y-m-d');
		$utime = $this->input->post('uld_time');
		// $utime = preg_replace('/\s+/', '', $str2);
		$data=array(
			'trk_acceptedstatus'=>1,
			'trk_acceptedremarks'=>$this->input->post('accRemarks'),
			'trk_accepteddate'=>$this->input->post('accdate'),
			'trk_acceptedutime'=>$utime,
			'trk_acceptedcby'=>$this->session->userdata('usr_uname'),
			'trk_acceptedcdatetime'=>$cdate,
			'trk_acceptedqty'=>$this->input->post('aqa')
		);
		$this->db->where('wi_id',$this->uri->segment(3));
		$this->db->update('whir',$data);
	}
	function trckng_canceled(){
		date_default_timezone_set("Asia/Manila");
		$cdate = date('Y-m-d');
		$data=array(
			'trk_canceledstatus'=>1,
			'trk_canceledremarks'=>$this->input->post('canRemarks'),
			'trk_canceleddate'=>$this->input->post('candate'),
			'trk_canceledcby'=>$this->session->userdata('usr_uname'),
			'trk_canceledcdatetime'=>$cdate,
			'trk_canceledqty'=>$this->input->post('aqc')
		);
		$this->db->where('wi_id',$this->uri->segment(3));
		$this->db->update('whir',$data);
	}
	function def_mm_process_active(){
		$this->db->order_by('name');
		$q=$this->db->get_where('ampt',array('status'=>1));
		foreach($q->result_array() as $r){
			$data[$r['name']]=$r['name'];
		}
		return $data;
	}
	function def_defaultdeltype_active(){
	
		$q=$this->db->get_where('dtdr',array('delstatus'=>1,'defaulttype'=>1));
		foreach($q->result_array() as $r){
			$data[$r['deltype']]=$r['deltype'];
		}
		return $data;
	}

	function wh_mm_itemqty_validation($get,$item,$qty){
		$q=$this->db->query("
			SELECT a.comm__id,a.comm__name,b.sqty,c.tqty,d.rqty
			FROM ocmt a
			LEFT OUTER JOIN (
				SELECT item_id,ROUND(SUM(wi_itemqty), 2) as sqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=0 AND wi_approvestatus=1 AND wi_status=1 
				GROUP BY item_id
			)b ON a.comm__id = b.item_id
			LEFT OUTER JOIN (
				SELECT item_id,ROUND(SUM(wi_itemqty), 2) as tqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=1 AND wi_status=1 
				GROUP BY item_id
			)c ON a.comm__id = c.item_id
			LEFT OUTER JOIN (
				SELECT item_id,ROUND(SUM(wi_itemqty), 2) as rqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=2 AND wi_status=1 
				GROUP BY item_id
			)d ON a.comm__id = d.item_id
			WHERE comm__id='".$item."'
			");
		if ($q->num_rows() == true){
			//return $q->result();
			foreach($q->result() as $r){
				if ($r->sqty == null){
					$sqty = 0;
				}
				else{
					$sqty = $r->sqty;
				}
				if ($r->tqty == null){
					$tqty = 0;
				}
				else{
					$tqty = $r->tqty;
				}
				if ($r->rqty == null){
					$rqty = 0;
				}
				else{
					$rqty = $r->rqty;
				}
				$total = ($sqty - ($tqty + $rqty));
			}
			if ((int)$total < (int)$qty){
				return true;
			}
		}
	}

	function del_mm_item2(){
		if($this->input->post('deltype2') == 'Delivery In'){$deltype=0;}
		else{$deltype=2;}
		if ($this->input->post('ref2')==""){
			$type2 = "";
			$tname2="";
		}
		else{
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}
		$data1 = array(
			'wi_type'=>2,
			'wh_name'=>$this->input->post('iloc2'),
			'wi_reftype'=>$this->input->post('doctype1'),
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'wi_refname'=>$desti=$this->input->post('cus2'),
			'item_id'=>$this->input->post('idesc2'),
			'wi_itemqty'=>$this->input->post('iqty2'),
			'wi_createby'=>$this->session->userdata('usr_uname'),
			'wi_status'=>1,
			'deldate'=>$this->input->post('ddate'),
			'wi_remarks'=>$this->input->post('remarks'),
			'item_uom'=>$this->input->post('iunit2'),
			'wi_mmprocess'=>$this->input->post('process'),
			'wi_deltype'=>$this->input->post('deltype2')
		);
		$q=$this->db->insert('whir',$data1);
	}

	function del_mm_item3($owhname,$oidesc,$oiuom,$oiqty){
		if($this->input->post('deltype3') == 'Delivery In'){$deltype=0;}
		else{$deltype=2;}
		if ($this->input->post('ref2')==""){
			$type2 = "";
			$tname2="";
		}
		else{
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}
		$data1 = array(
			'wi_type'=>2,
			'wi_reftype'=>$this->input->post('doctype1'), 
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'wi_mmprocess'=>$this->input->post('process'),
			'wi_createby'=>$this->session->userdata('usr_uname'),
			'wi_status'=>1,
			'deldate'=>$this->input->post('ddate'),
			'wi_remarks'=>$this->input->post('remarks'),
			'wi_refname'=>$desti=$this->input->post('cusname'),
			'wi_deltype'=>$this->input->post('deltype'),
			'wi_mmtnum'=>$this->input->post('tnum'),
			'mm_status'=>1,
			'wi_doqty'=>$oiqty,
			'wh_name'=>$owhname,
			'item_id'=>$oidesc,
			'item_uom'=>$oiuom,
			'wi_itemqty'=>$oiqty,
			'wi_transno'=>$this->input->post('trans_no'),
			'wi_dtcode'=>"DT_03"
		);
		$q=$this->db->insert('whir',$data1);

		//UPDATE SNDR TABLE
		$qry2  = $this->db->select('sn_nextnum')
					  ->where('sn_code', 'MM')
					  ->where('whse_code', $this->uri->segment(3))
		              ->get('sndr');

		foreach($qry2->result_array() as $tn){

			(int)$next_no = $tn['sn_nextnum'];
			$next_no+=1;

			$data2 = array(
				'sn_nextnum'=>$next_no
			);

			$this->db->where('sn_code', 'MM')
					 ->where('whse_code', $this->uri->segment(3))
			         ->update('sndr', $data2);

		}

	}
	
	function del_mm_item_in($whname,$idesc,$iuom,$iqty){
		if($this->input->post('deltype3') == 'Delivery In'){$deltype=0;}
		else{$deltype=2;}
		if ($this->input->post('ref2')==""){
			$type2 = "";
			$tname2="";
		}
		else{
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}
		$data1 = array(
			'wi_type'=>0,
			'wi_reftype'=>$this->input->post('doctype1'),
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'wi_mmprocess'=>$this->input->post('process'),
			'wi_createby'=>$this->session->userdata('usr_uname'),
			'wi_status'=>1,
			'deldate'=>$this->input->post('ddate'),
			'wi_remarks'=>$this->input->post('remarks'),
			'wi_refname'=>$desti=$this->input->post('cusname'),
			'wi_deltype'=>$this->input->post('deltype'),
			'wi_mmtnum'=>$this->input->post('tnum'),
			'mm_status'=>1,
			'wi_doqty'=>$iqty,
			'wh_name'=>$whname,
			'item_id'=>$idesc,
			'item_uom'=>$iuom,
			'wi_itemqty'=>$iqty,
			'wi_transno'=>$this->input->post('trans_no'),
			'wi_dtcode'=>"DT_03"
		);
		$q=$this->db->insert('whir',$data1);

		//UPDATE SNDR TABLE
		$qry2  = $this->db->select('sn_nextnum')
					  ->where('sn_code', 'MM')
					  ->where('whse_code', $this->uri->segment(3))
		              ->get('sndr');

		foreach($qry2->result_array() as $tn){

			(int)$next_no = $tn['sn_nextnum'];
			$next_no+=1;

			$data2 = array(
				'sn_nextnum'=>$next_no
			);

			$this->db->where('sn_code', 'MM')
					 ->where('whse_code', $this->uri->segment(3))
			         ->update('sndr', $data2);

		}

	}
	function del_mm_item4(){
		if($this->input->post('deltype4') == 'Delivery In'){$deltype=0;}
		else{$deltype=2;}
		if ($this->input->post('ref2')==""){
			$type2 = "";
			$tname2="";
		}
		else{
			$type2=$this->input->post('doctype2');
			$tname2=$this->input->post('ref2');
		}
		$data1 = array(
			'wi_type'=>$deltype,
			'wi_reftype'=>$this->input->post('doctype1'),
			'wi_refnum'=>$this->input->post('ref'),
			'wi_reftype2'=>$type2,
			'wi_refnum2'=>$tname2,
			'wi_mmprocess'=>$this->input->post('process'),
			'wi_createby'=>$this->session->userdata('usr_uname'),
			'wi_status'=>1,
			'deldate'=>$this->input->post('ddate'),
			'wi_remarks'=>$this->input->post('remarks'),
			
			'wi_deltype'=>$this->input->post('deltype4'),
			'wh_name'=>$this->input->post('iloc4'),
			'wi_refname'=>$desti=$this->input->post('cus4'),
			'item_id'=>$this->input->post('idesc4'),
			'item_uom'=>$this->input->post('iunit4'),
			'wi_itemqty'=>$this->input->post('iqty4')
		);
		$q=$this->db->insert('whir',$data1);
	}
	function sn_mm(){
		$q=$this->db->get_where('sndr',array('sn_code'=>'MM','sn_status'=>'1'));
		if($q->num_rows() == true){
			return $q->result();
		}
	}
	function add_transnum(){
		$q=$this->db->query("UPDATE sndr 
			SET sn_nextnum=(sn_nextnum + 1) 
			WHERE sn_code='MM' AND sn_status=1"
		);
	}
	function ref_mm_primary(){
		$q=$this->db->get_where(
			'aodt',array(
				'type'=>'Material Management',
				'status'=>1,
				'class'=>0
			)
		);
			if($q->num_rows() == true){
				foreach ($q->result_array() as $r){
				$data[$r['name']]=$r['name'];
			}
			return $data;
		}
	}
	function ref_mm_secondary(){
		$q=$this->db->get_where(
			'aodt',array(
				'type'=>'Material Management',
				'status'=>1,
				'class'=>1
			)
		);
			if($q->num_rows() == true){
				foreach ($q->result_array() as $r){
				$data[$r['name']]=$r['name'];
			}
			return $data;
		}
	}
	function customer_mm(){
		$db2=$this->load->database('db2',TRUE);
		$q=$db2->query("
					SELECT
                      CardCode, CardName,CardType,
                              CASE 
                              WHEN QryGroup14 = 'Y' THEN 'OPEN MARKET' 
                              WHEN QryGroup15 = 'Y' THEN 'INDUSTRIAL'
                              ELSE '' END AS 'TYPE'
                      FROM OCRD WHERE CardType = 'C'
					  AND CardName <> 'null'
					  ORDER BY CardName
		");
		if($q->num_rows()==true){
			foreach($q->result_array() as $r){
				$data[$r['CardCode']]=$r['CardName'];
			}
			return $data;
		}
	}
	function wh_delivery_mm_list(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT a.wi_type,
			a.wi_reftype2,
			a.wi_reftype,
			a.wi_refnum2,
			a.wi_approvedatetime,
			a.wi_refnum,
			a.wi_refname,
			a.wi_id,
			a.wh_name,
			a.wi_refnum,
			a.wi_refname,
			a.item_id,
			a.wi_approvestatus,
			a.deldate,
			SUM(wi_itemqty) AS qty,
			wh_code,
			d.CardName AS CName,
			a.wi_type AS type,
			a.wi_mmtnum,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
			FROM whir a
			LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
			LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
			WHERE wi_type IN (2,0) 
				AND wi_status=1 
				AND a.wh_name='".$this->input->post('whouse')."'
				AND wi_deltype = 'Material Management'
				AND mm_status='1'
			GROUP BY a.wi_mmtnum
			ORDER BY wi_id
		");
		if($q->num_rows() == true){
			return $q->result();
		}
	}
	function mm_update_get(){
		$q = $this->db->query("
			SELECT wi_type,
				wi_reftype2,
				wi_reftype,
				wi_refnum2,
				wi_approvedatetime,
				wi_refnum,
				wi_refname,
				wi_id,
				wh_name,
				wi_refnum,
				wi_refname,
				item_id,
				wi_approvestatus,
				deldate,
				SUM(wi_itemqty) AS qty,
				wi_mmtnum,
				wi_remarks,
				wi_createdatetime,
				wi_mmprocess,
				b.CardName
			FROM whir a
			LEFT OUTER JOIN (SELECT CardName,CardCOde,Status FROM ocrd WHERE Status=1)b ON a.wi_refname=b.CardCode
			WHERE wi_mmtnum='".$this->uri->segment(3)."'
			ORDER BY wi_mmtnum
		");
		if($q->num_rows == true){
			return $q->result();
		}
	}
	function mm_update_iteoout_get(){
		$q = $this->db->query("
			SELECT * 
			FROM whir a
			INNER JOIN (SELECT comm__id,comm__name,status FROM ocmt WHERE status=1)b ON a.item_id=b.comm__id
			WHERE wi_status=1 AND mm_status=1 AND wi_type IN (1,2)
			AND wi_mmtnum='".$this->uri->segment(3)."'
			ORDER BY wi_id
		");
		if($q->num_rows == true){
			return $q->result();
		}
	}
	function mm_update_itemin_get(){
		$q = $this->db->query("
			SELECT * 
			FROM whir a
			INNER JOIN (SELECT comm__id,comm__name,status FROM ocmt WHERE status=1)b ON a.item_id=b.comm__id
			WHERE wi_status=1 AND mm_status=1 AND wi_type =0
			AND wi_mmtnum='".$this->uri->segment(3)."'
			ORDER BY wi_id
		");
		if($q->num_rows == true){
			return $q->result();
		}
	}
	function mm_done(){
		$now = time('UP8');
		$datetime =  unix_to_human($now, TRUE);
		$data = array(
			'mm_status'=>0,
			'mm_doneby'=>$this->session->userdata('usr_uname'),
			'mm_donedatetime'=>$datetime
		);
		$this->db->where('wi_mmtnum',$this->uri->segment(3));
		$this->db->update('whir',$data);	
	}
	function return_cat(){
		$this->db->order_by('name');
		$q=$this->db->get_where('arct',array('status'=>1));
		foreach ($q->result_array() as $r){
			$data[$r['name']]=$r['name'];
		}
		return $data;
	}
	function getDO_SAP(){ 
		$db2=$this->load->database('db2',TRUE);
		$type = '';
		if($this->input->post('doctype1') == 'DO'){
			$DocNum = $this->input->post('ref');
			$type = $this->input->post('doctype1');
		}
		if($this->input->post('doctype1') == 'ITO'){
			$DocNum = $this->input->post('ref');
			$type = $this->input->post('doctype1');
		}
		if($this->input->post('doctype2') == 'DO'){
			$DocNum = $this->input->post('ref2');
			$type = $this->input->post('doctype2');
		}
		if($this->input->post('doctype2') == 'ITO'){
			$DocNum = $this->input->post('ref2');
			$type = $this->input->post('doctype2');
		}
		$q = NULL;
		if($type == 'DO'){
			$q=$db2->query("
				SELECT A.CardCode, (SELECT Name FROM [@TRUCKER]WHERE Code = A.U_Trucker) 'Truck', A.Address2, CONVERT(VARCHAR(10), A.DocDueDate, 101) as 'DocDueDate', A.CardName, A.DocNum,A.U_PONo, B.Quantity, B.unitMsr,B.Dscription, A.Comments, B.ItemCode FROM ODLN A 
				INNER JOIN DLN1 B ON A.DocEntry = B.DocEntry
				WHERE A.DocNum = '$DocNum' AND A.CANCELED = 'N' AND A.DocStatus = 'O'
			");	
		}
		if($type == 'ITO'){
			$q=$db2->query("
				SELECT A.CardCode, (SELECT Name FROM [@TRUCKER]WHERE Code = A.U_Trucker) 'Truck', A.Address2, CONVERT(VARCHAR(10), A.DocDueDate, 101) as 'DocDueDate', A.CardName, A.DocNum,A.U_PONo, B.Quantity, B.unitMsr,B.Dscription, A.Comments, B.ItemCode, (SELECT WhsName FROM OWHS WHERE WhsCode = A.Filler) 'Desti', A.Filler FROM OWTR A 
				INNER JOIN WTR1 B ON A.DocEntry = B.DocEntry
				WHERE A.DocNum = '$DocNum' AND A.CANCELED = 'N' AND A.DocStatus = 'O'
			");	
		}
		if(!$q == NULL){
			if($q->num_rows()){
				return $q->result();
			}
			else{
				return false;
			}
		}		
	}
	
	function get_ito_rem_qty($a){
		$this->db->where('ref_type', 'ITO');
		$this->db->where('ref_num', $a);
		$q = $this->db->get('mand');
		if($q->num_rows() > 0){
			return $q->row('rem_qty');
		}
	}
	
	function check_balance_on_mand($type, $a){
		$q2 = $this->db->query("SELECT * FROM mand WHERE ref_num = '$a' AND ref_type = '$type'");
		if($q2->num_rows() > 0){
			return true;
		}
	}
	
	function update_stock_bal_tbl(){
		$now = time('UP8');
		$datetime =  unix_to_human($now, TRUE);
		$db2=$this->load->database('db2',TRUE);
		
		$data1 = array();
		$bps = $this->db->query("
			SELECT A.refnum, A.reftype FROM
			(SELECT wi_refnum 'refnum', wi_reftype 'reftype' FROM whir WHERE wi_refnum <> wi_PONum AND wi_reftype IN('DO','ITO') AND wi_status <> '0' AND wi_type = 1
			UNION
			SELECT wi_refnum2 'refnum', wi_reftype2 'reftype' FROM whir WHERE wi_refnum2 <> wi_PONum AND wi_reftype2 IN('DO','ITO') AND wi_status <> '0' AND wi_type = 1) A
			ORDER BY A.refnum, A.reftype
		");
		if ($bps->num_rows() > 0){
			foreach($bps->result() as $row){
				$data1[] = $row;
			}
		}
		
		foreach($data1 as $raw){
			$a = $raw->refnum;
			$type = $raw->reftype;
			$data = array();
				
			if(preg_match("/^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$/", $a)){
				
				if( !$this->check_balance_on_mand($type, $a) ){
					if($type == 'DO'){
						$q=$db2->query("
							SELECT A.DocNum, B.Quantity, B.ItemCode, B.Dscription, 'DO' as 'Type' FROM ODLN A
							INNER JOIN DLN1 B ON A.DocEntry = B.DocEntry WHERE A.DocNum = '$a'
						");	
					}
					if($type == 'ITO'){
						$q=$db2->query("
							SELECT A.DocNum, B.Quantity, B.ItemCode, B.Dscription, 'ITO' as 'Type' FROM OWTR A
							INNER JOIN WTR1 B ON A.DocEntry = B.DocEntry WHERE A.DocNum = '$a'
						");
					}	
					if($q->num_rows() > 0){
						foreach($q->result() as $row){
							$data[] = $row;
						}
					}	
					foreach($data as $dt){
						if( !$this->check_balance_on_mand($type, $a) ){
							//add new record		
							$new_rec = array(
								'ref_type' => $dt->Type,
								'ref_num' => $dt->DocNum,
								'item_code' => $dt->ItemCode,
								'act_qty' => $dt->Quantity,
								'create_date' => $datetime
							);
							$this->db->insert('mand', $new_rec);			
						}
					}				
				}
			}
		}
		
		//update the record balance
		// wi_itemqty (actual loaded/received),  wi_doqty (from SAP) 		
		
		foreach($data1 as $raw){
			$a = $raw->refnum;
			if(preg_match("/^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$/", $a)){
				$q3 = $this->db->query("
					SELECT wi_refnum, wi_reftype, wi_refnum2, wi_reftype2, SUM(IFNULL(wi_itemqty, 0)) 'Com', ( SELECT act_qty FROM mand WHERE ref_num = '$a' AND ref_type IN('DO', 'ITO') ) - SUM(IFNULL(wi_itemqty, 0)) 'rem_qty' FROM whir 
					WHERE wi_refnum = '$a' AND wi_status <> '0' AND wi_type = 1 AND wi_reftype IN('DO', 'ITO') OR wi_refnum2 = '$a' AND wi_status <> '0' AND wi_type = 1 AND wi_reftype2 IN('DO', 'ITO')
				");
					
				if($q3->num_rows()){
					foreach($q3->result() as $ut){
						$update_rec = array(
							'com_qty' => $ut->Com,
							'rem_qty' => $ut->rem_qty
						);
						if($ut->wi_reftype == 'DO' OR $ut->wi_reftype == 'ITO'){
							$this->db->where('ref_type', $ut->wi_reftype);
						}
						else{
							$this->db->where('ref_type', $ut->wi_reftype2);
						}
						$this->db->where('ref_num', $a);
						$this->db->update('mand', $update_rec);
					}
				}
			}
		}
	}
	
	function update_running_balance(){
		$data1 = array();
		$bps = $this->db->query("
			SELECT A.refnum, A.reftype FROM
			(SELECT wi_refnum 'refnum', wi_reftype 'reftype' FROM whir WHERE wi_refnum <> wi_PONum AND wi_reftype IN('DO','ITO') AND wi_status <> '0' AND wi_type = 1 
			UNION
			SELECT wi_refnum2 'refnum', wi_reftype2 'reftype' FROM whir WHERE wi_refnum2 <> wi_PONum AND wi_reftype2 IN('DO','ITO') AND wi_status <> '0' AND wi_type = 1) A
			ORDER BY A.refnum, A.reftype
		");
		if ($bps->num_rows() > 0){
			foreach($bps->result() as $row){
				$data1[] = $row;
			}
		}
		
		foreach($data1 as $raw){
			$a = $raw->refnum;
			if(preg_match("/^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$/", $a)){
				$q3 = $this->db->query("
					SELECT wi_refnum, wi_reftype, wi_refnum2, wi_reftype2, SUM(IFNULL(wi_itemqty, 0)) 'Com', ( SELECT act_qty FROM mand WHERE ref_num = '$a' AND ref_type IN('DO', 'ITO') ) - SUM(IFNULL(wi_itemqty, 0)) 'rem_qty' FROM whir 
					WHERE wi_refnum = '$a' AND wi_status <> '0' AND wi_type = 1 AND wi_reftype IN('DO', 'ITO') OR wi_refnum2 = '$a' AND wi_status <> '0' AND wi_type = 1 AND wi_reftype2 IN('DO', 'ITO')
				");
					
				if($q3->num_rows()){
					foreach($q3->result() as $ut){
						$update_rec = array(
							'com_qty' => $ut->Com,
							'rem_qty' => $ut->rem_qty
						);
						if($ut->wi_reftype == 'DO' OR $ut->wi_reftype == 'ITO'){
							$this->db->where('ref_type', $ut->wi_reftype);
						}
						else{
							$this->db->where('ref_type', $ut->wi_reftype2);
						}
						$this->db->where('ref_num', $a);
						$this->db->update('mand', $update_rec);
					}
				}
			}
		}
	}
	
	function check_if_to_be_posted_qty_is_greater_than_actual($doctype1, $doctype2, $ref1, $ref2, $whqty){
		/*$doctype1 = $this->input->post('doctype1'); 
		$doctype2 = $this->input->post('doctype2'); 
		$ref1 = $this->input->post('ref1'); 
		$ref2 = $this->input->post('ref2'); 
		$whqty = $this->input->post('whqty'); //actual loaded*/
		
		if($doctype1 == 'DO' OR $doctype1 == 'ITO' OR $doctype2 == 'DO' OR $doctype2 == 'ITO'){
			if($doctype1 == 'DO' OR $doctype1 == 'ITO'){
				$this->db->where('ref_type', $doctype1);
				$this->db->where('ref_num', $ref1);
			}
			if($doctype2 == 'DO' OR $doctype2 == 'ITO'){
				$this->db->where('ref_type', $doctype2);
				$this->db->where('ref_num', $ref2);
			}
			$this->db->where('rem_qty >=', $whqty);
			$q = $this->db->get('mand');
			if($q->num_rows() > 0){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	function check_if_refnum_existed($doctype1, $doctype2, $ref1, $ref2){
		if($doctype1 == 'DO' OR $doctype1 == 'ITO'){
			$this->db->where('ref_type', $doctype1);
			$this->db->where('ref_num', $ref1);
		}
		if($doctype2 == 'DO' OR $doctype2 == 'ITO'){
			$this->db->where('ref_type', $doctype2);
			$this->db->where('ref_num', $ref2);
		}
		//$this->db->where('rem_qty >=', $whqty);
		$q = $this->db->get('mand');
		if($q->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function download_pdf(){
		$print = $this->uri->segment(7);
		if($print){
			$refresh = base_url().'index.php/main/print_dr_pdf/'.$print;
			return $refresh;
		}
		else{
			return false;
		}
	}
	
	function check_if_actual_is_correct(){
		// check if the quantity from SAP is greater than or equal to actual loaded/received quantity. Check if total DR will not exceeds total DO/ITO in SAP.
		$actual = $this->input->post('recqty');
		if( $this->input->post('itoqty') >= $actual ){
			$this->update_stock_bal_tbl();
			$q = $this->db->query("
				SELECT * FROM mand WHERE rem_qty <= '$actual'  
			");
			
			if($q->num_rows() > 0){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}

	function get_customer_SAP(){
		$db2=$this->load->database('db2',TRUE);
		$q=$db2->query("
					SELECT a.CardType,a.CardName,a.CardCode,a.Type 
					FROM (SELECT
                      CardCode, CardName,CardType,
                              CASE 
                              WHEN QryGroup14 = 'Y' THEN 'OPEN MARKET' 
                              WHEN QryGroup15 = 'Y' THEN 'INDUSTRIAL'
                              ELSE '' END AS 'TYPE'
                      FROM OCRD WHERE CardType = 'C'
					  AND CardName <> 'null'
					) a ORDER BY a.CardType,a.CardName,a.CardCode,a.Type
		");
		if($q->num_rows()==true){
			foreach($q->result_array() as $r){
				$data[$r['CardCode']]=$r['CardName'];
			}
			return $data;
		}
	}

	function confirm_del_report_aaci(){

		$cname = $this->input->post('cname');
		$desti = $this->input->post('desti');

		if($this->input->post('all_cust') == TRUE){
			$customer = '%';
		}else{
			$customer = $cname;
		}

		if($this->input->post('Search')){

			if($cname){
				$q = $this->db->query("SELECT a.wi_type,
						a.wi_reftype2,
						a.wi_reftype,
						a.wi_refnum2,
						a.wi_refnum,
						a.wi_refname,
						a.wi_id,
						a.wh_name,
						a.wi_refnum,
						a.wi_refname,
						a.item_id,
						a.deldate,
						a.wi_PONum,
						a.truck_arrival_time,
						a.wi_location,
						a.wi_doqty,
						a.wi_exactdeliverydate,
						a.wi_expecteddeliverydate,
						a.trk_acceptedutime,
						a.wi_itemqty,
						a.item_uom,
						a.trk_acceptedqty,
						a.trk_canceledqty,
						a.wi_deliveredremarks,
						wh_code,
						c.comm__name,
						d.CardName AS CName,
						d.CardCode,
						a.wi_type AS type,
						a.wi_deliveredstatus,
						a.truck_company,
						a.truck_platenum,
						a.truck_driver,
						a.trk_arrivedstatus,
						a.trk_acceptedstatus,
						a.trk_arrivedtime,
						a.trk_arriveddate,
						a.trk_acceptedremarks,
						a.trk_canceledremarks,
						a.wi_refnum3,
						e.cust_rmks,
						e.arr_remarks,
						e.can_remarks,
						e.cdel_date,
						e.cdel_time,
						e.confirm,
						e.dr_remarks,
						e.cust_username,
						a.trk_canceledstatus,
						CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
						FROM whir a
						LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
						LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
						LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
						INNER JOIN cdel e ON a.wi_PONum=e.po_num AND a.wi_refnum = e.refnum AND a.wi_refnum2 = e.refnum2
						WHERE wi_type = 1
							AND wi_deliveredstatus IN (0,1)
							AND wi_status=1
							AND wi_deltype IN ('Delivery In','Delivery Out')
							AND email_cdel in (0,1)
							AND wi_reftype <> 'Count Sheet'
							AND wi_refname LIKE '$customer'
							AND wi_approvestatus = 1
							AND wi_status = 1
							AND e.confirm = 1
						ORDER BY wi_id");

				if($q->num_rows() == true){
					return $q->result();
				}
			}
			
		}

	}

	function confirm_del_report_aaci_sort(){

		$cname = $this->input->post('cname');
		$desti = $this->input->post('desti');

		if($this->input->post('all_cust') == TRUE){
			$customer = '%';
		}else{
			$customer = $cname;
		}

		if($this->input->post('Search')){

			if($cname){
				$q = $this->db->query("SELECT a.wi_type,
						a.wi_reftype2,
						a.wi_reftype,
						a.wi_refnum2,
						a.wi_refnum,
						a.wi_refname,
						a.wi_id,
						a.wh_name,
						a.wi_refnum,
						a.wi_refname,
						a.item_id,
						a.deldate,
						a.wi_PONum,
						a.truck_arrival_time,
						a.wi_location,
						a.wi_doqty,
						a.wi_exactdeliverydate,
						a.wi_expecteddeliverydate,
						a.trk_acceptedutime,
						a.wi_itemqty,
						a.item_uom,
						a.trk_acceptedqty,
						a.trk_canceledqty,
						a.wi_deliveredremarks,
						wh_code,
						c.comm__name,
						d.CardName AS CName,
						d.CardCode,
						a.wi_type AS type,
						a.wi_deliveredstatus,
						a.truck_company,
						a.truck_platenum,
						a.truck_driver,
						a.trk_arrivedstatus,
						a.trk_acceptedstatus,
						a.trk_arrivedtime,
						a.trk_arriveddate,
						a.trk_acceptedremarks,
						a.trk_canceledremarks,
						a.wi_refnum3,
						e.cust_rmks,
						e.arr_remarks,
						e.can_remarks,
						e.cdel_date,
						e.cdel_time,
						e.confirm,
						e.dr_remarks,
						e.cust_username,
						a.trk_canceledstatus,
						CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
						FROM whir a
						LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
						LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
						LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
						INNER JOIN cdel e ON a.wi_PONum=e.po_num AND a.wi_refnum = e.refnum AND a.wi_refnum2 = e.refnum2
						WHERE wi_type = 1
							AND wi_deliveredstatus IN (0,1)
							AND wi_status=1
							AND wi_deltype IN ('Delivery In','Delivery Out')
							AND email_cdel in (0,1)
							AND wi_reftype <> 'Count Sheet'
							AND wi_refname LIKE '$customer'
							AND wi_approvestatus = 1
							AND wi_status = 1
							AND e.confirm = 1
							AND wi_createdatetime
								BETWEEN '".$this->input->post('sdate_from')." 00:00:01'
								AND '".$this->input->post('sdate_to')." 23:59:59'
						ORDER BY wi_id");

				if($q->num_rows() == true){
					return $q->result();
				}
			}
			
		}

	}

	function confirm_del_report_trucker(){

		$cname = $this->input->post('cname');
		$desti = $this->input->post('desti');
		$trucker = $this->input->post('trucker');
		$comp_name = $this->input->post('tname');

		// TRUCKER
		if($trucker == ""){
			// COMPANY NAME
			if($comp_name == 'AGTI'){
				$trucker = 'AGTI';
			}elseif($comp_name == 'WEI'){
				$trucker = 'WEI';
			}else{
				$trucker = '%';
			}
		}else{
			$trucker;
		}

		// CUSTOMER
		if($this->input->post('all_cust') == TRUE){
			$customer = '%';
		}else{
			$customer = $cname;
		}

		if($this->input->post('Search')){
			if($cname){
		
				$q = $this->db->query("SELECT a.wi_type,
						a.wi_reftype2,
						a.wi_reftype,
						a.wi_refnum2,
						a.wi_refnum,
						a.wi_refname,
						a.wi_id,
						a.wh_name,
						a.wi_refnum,
						a.wi_refname,
						a.item_id,
						a.deldate,
						a.wi_PONum,
						a.truck_arrival_time,
						a.wi_location,
						a.wi_doqty,
						a.wi_exactdeliverydate,
						a.wi_expecteddeliverydate,
						a.trk_acceptedutime,
						a.wi_itemqty,
						a.item_uom,
						a.trk_acceptedqty,
						a.trk_canceledqty,
						a.wi_deliveredremarks,
						wh_code,
						c.comm__name,
						d.CardName AS CName,
						a.wi_type AS type,
						a.wi_deliveredstatus,
						a.truck_company,
						a.truck_platenum,
						a.truck_driver,
						a.trk_arrivedstatus,
						a.trk_acceptedstatus,
						a.trk_arrivedtime,
						a.trk_arriveddate,
						a.trk_acceptedremarks,
						a.trk_canceledremarks,
						e.cust_rmks,
						e.arr_remarks,
						e.can_remarks,
						e.cdel_date,
						e.cdel_time,
						e.confirm,
						e.dr_remarks,
						e.cust_username,
						a.trk_canceledstatus,
						CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
						FROM whir a
						LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
						LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
						LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
						INNER JOIN cdel e ON a.wi_PONum=e.po_num AND a.wi_refnum = e.refnum AND a.wi_refnum2 = e.refnum2
						WHERE wi_type = 1
							AND wi_deliveredstatus IN (0,1)
							AND wi_status=1
							AND wi_deltype IN ('Delivery In','Delivery Out')
							AND email_cdel in (0,1)
							AND wi_reftype <> 'Count Sheet'
							AND wi_refname LIKE '$customer'
							AND wi_approvestatus = 1
							AND wi_status = 1
							AND truck_company LIKE '$trucker'
							AND e.confirm = 1
						ORDER BY wi_id");

				if($q->num_rows() == true){
					return $q->result();
				}
			}
		}

	}

	function confirm_del_report_trucker_sort(){

		$cname = $this->input->post('cname');
		$desti = $this->input->post('desti');
		$trucker = $this->input->post('trucker');
		$comp_name = $this->input->post('tname');

		// CUSTOMER
		if($this->input->post('all_cust') == TRUE){
			$customer = '%';
		}else{

			if($comp_name == 'AGTI' OR $comp_name == "WEI"){
				$customer = '%';
			}else{
				$customer = $cname;
			}
		}

		// TRUCKER
		if($trucker == ""){
			// COMPANY NAME
			if($comp_name == 'AGTI'){
				$trucker = 'AGTI';
			}elseif($comp_name == 'WEI'){
				$trucker = 'WEI';
			}else{
				$trucker = '%';
			}
		}else{
			$customer='%';
			$trucker;
		}

		if($this->input->post('Search')){
			if($cname){

				$q = $this->db->query("SELECT a.wi_type,
						a.wi_reftype2,
						a.wi_reftype,
						a.wi_refnum2,
						a.wi_refnum,
						a.wi_refname,
						a.wi_id,
						a.wh_name,
						a.wi_refnum,
						a.wi_refname,
						a.item_id,
						a.deldate,
						a.wi_PONum,
						a.truck_arrival_time,
						a.wi_location,
						a.wi_doqty,
						a.wi_exactdeliverydate,
						a.wi_expecteddeliverydate,
						a.trk_acceptedutime,
						a.wi_itemqty,
						a.item_uom,
						a.trk_acceptedqty,
						a.trk_canceledqty,
						a.wi_deliveredremarks,
						wh_code,
						c.comm__name,
						d.CardName AS CName,
						a.wi_type AS type,
						a.wi_deliveredstatus,
						a.truck_company,
						a.truck_platenum,
						a.truck_driver,
						a.trk_arrivedstatus,
						a.trk_acceptedstatus,
						a.trk_arrivedtime,
						a.trk_arriveddate,
						a.trk_acceptedremarks,
						a.trk_canceledremarks,
						e.cust_rmks,
						e.arr_remarks,
						e.can_remarks,
						e.cdel_date,
						e.cdel_time,
						e.confirm,
						e.dr_remarks,
						e.cust_username,
						a.trk_canceledstatus,
						CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
						FROM whir a
						LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
						LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
						LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
						INNER JOIN cdel e ON a.wi_PONum=e.po_num AND a.wi_refnum = e.refnum AND a.wi_refnum2 = e.refnum2
						WHERE wi_type = 1
							AND wi_deliveredstatus IN (0,1)
							AND wi_status=1
							AND wi_deltype IN ('Delivery In','Delivery Out')
							AND email_cdel in (0,1)
							AND wi_reftype <> 'Count Sheet'
							AND wi_refname LIKE '$customer'
							AND wi_approvestatus = 1
							AND wi_status = 1
							AND truck_company LIKE '$trucker'
							AND e.confirm = 1
							AND wi_createdatetime
								BETWEEN '".$this->input->post('sdate_from')." 00:00:01'
								AND '".$this->input->post('sdate_to')." 23:59:59'
						ORDER BY wi_id");

				if($q->num_rows() == true){
					return $q->result();
				}
			}
		}

	}

	function confirm_del_report_customer(){

		$cname = $this->input->post('cname');
		$desti = $this->input->post('desti');

		if($this->input->post('all_cust') == TRUE){
			$customer = '%';
		}else{
			$customer = $cname;
		}

		if($this->input->post('Search')){

			if($cname){
				$q = $this->db->query("SELECT a.wi_type,
						a.wi_reftype2,
						a.wi_reftype,
						a.wi_refnum2,
						a.wi_refnum,
						a.wi_refname,
						a.wi_id,
						a.wh_name,
						a.wi_refnum,
						a.wi_refname,
						a.item_id,
						a.deldate,
						a.wi_PONum,
						a.truck_arrival_time,
						a.wi_location,
						a.wi_doqty,
						a.wi_exactdeliverydate,
						a.wi_expecteddeliverydate,
						a.trk_acceptedutime,
						a.wi_itemqty,
						a.item_uom,
						a.trk_acceptedqty,
						a.trk_canceledqty,
						a.wi_deliveredremarks,
						a.wi_refnum3,
						wh_code,
						c.comm__name,
						d.CardName AS CName,
						a.wi_type AS type,
						a.wi_deliveredstatus,
						a.truck_company,
						a.truck_platenum,
						a.truck_driver,
						a.trk_arrivedstatus,
						a.trk_acceptedstatus,
						a.trk_arrivedtime,
						a.trk_arriveddate,
						a.trk_acceptedremarks,
						a.trk_canceledremarks,
						e.cust_rmks,
						e.arr_remarks,
						e.can_remarks,
						e.cdel_date,
						e.cdel_time,
						e.confirm,
						e.cust_username,
						e.dr_remarks,
						a.trk_canceledstatus,
						CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
						FROM whir a
						LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
						LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
						LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
						INNER JOIN cdel e ON a.wi_PONum=e.po_num AND a.wi_refnum = e.refnum AND a.wi_refnum2 = e.refnum2
						WHERE wi_type = 1
							AND wi_deliveredstatus IN (0,1)
							AND wi_status=1
							AND wi_deltype IN ('Delivery In','Delivery Out')
							AND email_cdel in (0,1)
							AND wi_reftype <> 'Count Sheet'
							AND wi_refname LIKE '$customer'
							AND wi_approvestatus = 1
							AND wi_status = 1
							AND e.confirm = 1
						ORDER BY wi_id");

				if($q->num_rows() == true){
					return $q->result();
				}

			}
			
		}

	}

	function confirm_del_report_customer_sort(){

		$cname = $this->input->post('cname');
		$desti = $this->input->post('desti');

		if($this->input->post('all_cust') == TRUE){
			$customer = '%';
		}else{
			$customer = $cname;
		}

		if($this->input->post('Search')){

			if($cname){
				$q = $this->db->query("SELECT a.wi_type,
						a.wi_reftype2,
						a.wi_reftype,
						a.wi_refnum2,
						a.wi_refnum,
						a.wi_refname,
						a.wi_id,
						a.wh_name,
						a.wi_refnum,
						a.wi_refname,
						a.item_id,
						a.deldate,
						a.wi_PONum,
						a.truck_arrival_time,
						a.wi_location,
						a.wi_doqty,
						a.wi_exactdeliverydate,
						a.wi_expecteddeliverydate,
						a.trk_acceptedutime,
						a.wi_itemqty,
						a.item_uom,
						a.trk_acceptedqty,
						a.trk_canceledqty,
						a.wi_deliveredremarks,
						a.wi_refnum3,
						wh_code,
						c.comm__name,
						d.CardName AS CName,
						a.wi_type AS type,
						a.wi_deliveredstatus,
						a.truck_company,
						a.truck_platenum,
						a.truck_driver,
						a.trk_arrivedstatus,
						a.trk_acceptedstatus,
						a.trk_arrivedtime,
						a.trk_arriveddate,
						a.trk_acceptedremarks,
						a.trk_canceledremarks,
						e.cust_rmks,
						e.arr_remarks,
						e.can_remarks,
						e.cdel_date,
						e.cdel_time,
						e.confirm,
						e.cust_username,
						e.dr_remarks,
						a.trk_canceledstatus,
						CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
						FROM whir a
						LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
						LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
						LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
						INNER JOIN cdel e ON a.wi_PONum=e.po_num AND a.wi_refnum = e.refnum AND a.wi_refnum2 = e.refnum2
						WHERE wi_type = 1
							AND wi_deliveredstatus IN (0,1)
							AND wi_status=1
							AND wi_deltype IN ('Delivery In','Delivery Out')
							AND email_cdel in (0,1)
							AND wi_reftype <> 'Count Sheet'
							AND wi_refname LIKE '$customer'
							AND wi_approvestatus = 1
							AND wi_status = 1
							AND e.confirm = 1
							AND wi_createdatetime
								BETWEEN '".$this->input->post('sdate_from')." 00:00:01'
								AND '".$this->input->post('sdate_to')." 23:59:59'
						OR wi_type = 1
							AND wi_deliveredstatus IN (0,1)
							AND wi_status=1
							AND wi_deltype IN ('Delivery In','Delivery Out')
							AND email_cdel in (0,1)
							AND wi_reftype <> 'Count Sheet'
							AND wi_refname LIKE '$customer'
							AND wi_approvestatus = 1
							AND wi_status = 1
							AND e.confirm = 1
							AND wi_createdatetime
								BETWEEN '".$this->input->post('sdate_from')." 00:00:01'
								AND '".$this->input->post('sdate_to')." 23:59:59'
						ORDER BY wi_id");

				if($q->num_rows() == true){
					return $q->result();
				}

			}
			
		}

	}


	function confirm_del_report_customer_onsite(){

		$cust_code = $this->input->post('cust_code');
		$desti = $this->input->post('desti');

		if($this->input->post('Search')){

				$q = $this->db->query("SELECT a.wi_type,
						a.wi_reftype2,
						a.wi_reftype,
						a.wi_refnum2,
						a.wi_refnum,
						a.wi_refname,
						a.wi_id,
						a.wh_name,
						a.wi_refnum,
						a.wi_refname,
						a.item_id,
						a.deldate,
						a.wi_PONum,
						a.truck_arrival_time,
						a.wi_location,
						a.wi_doqty,
						a.wi_exactdeliverydate,
						a.wi_expecteddeliverydate,
						a.trk_acceptedutime,
						a.wi_itemqty,
						a.item_uom,
						a.trk_acceptedqty,
						a.trk_canceledqty,
						a.wi_deliveredremarks,
						a.wi_refnum3,
						a.wh_code,
						c.comm__name,
						d.CardName AS CName,
						a.wi_type AS type,
						a.wi_deliveredstatus,
						a.truck_company,
						a.truck_platenum,
						a.truck_driver,
						a.trk_arrivedstatus,
						a.trk_acceptedstatus,
						a.trk_arrivedtime,
						a.trk_arriveddate,
						a.trk_acceptedremarks,
						a.trk_canceledremarks,
						e.cust_rmks,
						e.arr_remarks,
						e.can_remarks,
						e.cdel_date,
						e.cdel_time,
						e.confirm,
						e.cust_username,
						e.dr_remarks,
						a.trk_canceledstatus,
						CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
						FROM whir a
						LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
						LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
						LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
						INNER JOIN cdel e ON a.wi_PONum=e.po_num AND a.wi_refnum = e.refnum AND a.wi_refnum2 = e.refnum2
						WHERE wi_type = 1
							AND wi_deliveredstatus IN (0,1)
							AND wi_status=1
							AND wi_deltype IN ('Delivery In','Delivery Out')
							AND email_cdel in (0,1)
							AND wi_reftype <> 'Count Sheet'
							AND wi_refname LIKE '$cust_code'
							AND wi_approvestatus = 1
							AND wi_status = 1
							AND e.confirm = 1
						ORDER BY wi_id");

				if($q->num_rows() == true){
					return $q->result();
				}
			
		}

	}

	function confirm_del_report_customer_onsite_sort(){

		$cust_code = $this->input->post('cust_code');
		$desti = $this->input->post('desti');

		if($this->input->post('Search')){

				$q = $this->db->query("SELECT a.wi_type,
						a.wi_reftype2,
						a.wi_reftype,
						a.wi_refnum2,
						a.wi_refnum,
						a.wi_refname,
						a.wi_id,
						a.wh_name,
						a.wi_refnum,
						a.wi_refname,
						a.item_id,
						a.deldate,
						a.wi_PONum,
						a.truck_arrival_time,
						a.wi_location,
						a.wi_doqty,
						a.wi_exactdeliverydate,
						a.wi_expecteddeliverydate,
						a.trk_acceptedutime,
						a.wi_itemqty,
						a.item_uom,
						a.trk_acceptedqty,
						a.trk_canceledqty,
						a.wi_deliveredremarks,
						a.wi_refnum3,
						wh_code,
						c.comm__name,
						d.CardName AS CName,
						a.wi_type AS type,
						a.wi_deliveredstatus,
						a.truck_company,
						a.truck_platenum,
						a.truck_driver,
						a.trk_arrivedstatus,
						a.trk_acceptedstatus,
						a.trk_arrivedtime,
						a.trk_arriveddate,
						a.trk_acceptedremarks,
						a.trk_canceledremarks,
						e.cust_rmks,
						e.arr_remarks,
						e.can_remarks,
						e.cdel_date,
						e.cdel_time,
						e.confirm,
						e.cust_username,
						e.dr_remarks,
						a.trk_canceledstatus,
						CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
						FROM whir a
						LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
						LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
						LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode
						INNER JOIN cdel e ON a.wi_PONum=e.po_num AND a.wi_refnum = e.refnum AND a.wi_refnum2 = e.refnum2
						WHERE wi_type = 1
							AND wi_deliveredstatus IN (0,1)
							AND wi_status=1
							AND wi_deltype IN ('Delivery In','Delivery Out')
							AND email_cdel in (0,1)
							AND wi_reftype <> 'Count Sheet'
							AND wi_refname LIKE '$cust_code'
							AND wi_approvestatus = 1
							AND wi_status = 1
							AND e.confirm = 1
							AND a.wi_createdatetime 
								BETWEEN '".$this->input->post('sdate_from')." 00:00:01'
								AND '".$this->input->post('sdate_to')." 23:59:59'
						ORDER BY wi_id");

				if($q->num_rows() == true){
					return $q->result();
				}
			
		}

	}

	function confirm_del_report_sort(){

		$cname = $this->input->post('cname');
		$sfrom = $this->input->post('sdate_from');
		$sto = $this->input->post('sdate_to');
		$desti = $this->input->post('desti');

		if($this->input->post('Search')){

			if($cname){
				$q = $this->db->query("SELECT * 
					FROM cdel 
					WHERE cust_code = '$cname' 
					AND ship_date
						BETWEEN '".$this->input->post('sdate_from')."'
						AND '".$this->input->post('sdate_to')."'");

				if($q->num_rows > 0){
					foreach ($q->result() as $row) {
						$data[] = $row;
					}
					return $data;
				}else{
					return false;
				}
			}
			
		}

	}

	function truck_list(){
		$qry = "SELECT * FROM dtrk ORDER BY Transporter_Name";
		$q = $this->db->query($qry);

		if ($q->num_rows==true){
			foreach($q->result_array() as $r){
				$data[$r['Transporter_Name']]=$r['Transporter_Name'];
			}
			return $data;
		}
	}

	function truck_add(){

		$truck_name = $this->input->post('truck_list');

		if(isset($truck_name)){
			$qry = "SELECT * FROM dtrk WHERE Transporter_Name LIKE '$truck_name' ";
			$q = $this->db->query($qry);

			if($q->num_rows()==false){
				$data = array('Transporter_Name'=>$truck_name);
				$this->db->insert('dtrk',$data);
			}
		}

	}

	function truck_add_sap(){

		$truck_name = $this->input->post('truck_company');

		if(isset($truck_name)){
			$qry = "SELECT * FROM dtrk WHERE Transporter_Name LIKE '$truck_name' ";
			$q = $this->db->query($qry);

			if($q->num_rows()==false){
				$data = array('Transporter_Name'=>$truck_name);
				$this->db->insert('dtrk',$data);
			}
		}

	}

	function uom_list(){
		$qry = "SELECT * FROM duom ORDER BY Order_List";
		$q = $this->db->query($qry);

		if ($q->num_rows==true){
			foreach($q->result_array() as $r){
				$data[$r['UOM_Name']]=$r['UOM_Name'];
			}
			return $data;
		}
	}

	function uom_add(){

		$uom_name = $this->input->post('uom');

		if(isset($truck_name)){
			$qry = "SELECT * FROM duom WHERE UOM_Name LIKE '$uom_name' ";
			$q = $this->db->query($qry);

			if($q->num_rows()==false){
				$data = array('UOM_Name'=>$truck_name);
				$this->db->insert('duom',$data);
			}
		}

	}

	function uom_add_sap(){

		$uom_name = $this->input->post('uom');

		if(isset($truck_name)){
			$qry = "SELECT * FROM duom WHERE UOM_Name LIKE '$uom_name' ";
			$q = $this->db->query($qry);

			if($q->num_rows()==false){
				$data = array('UOM_Name'=>$truck_name);
				$this->db->insert('duom',$data);
			}
		}

	}

	function customer_list(){
		$this->db->order_by('CardName');
		$q=$this->db->get_where('ocrd',array('Status'=>1,'Type'=>'C'));
		if($q->num_rows==true){
			return $q->result();
		}
	}

	function customer_delete(){
		$this->db->where('CardCode', $this->uri->segment(3));
		$this->db->delete('ocrd');
	}

	function customer_code_validation(){

		$this->db->where('CardCode', $this->input->post('ccode'));
		$q=$this->db->get('ocrd');
		if($q->num_rows() == true){
			return true;
		}
	}

	function customer_name_validation(){

		$this->db->where('CardName', $this->input->post('cname'));
		$q=$this->db->get('ocrd');
		if($q->num_rows() == true){
			return true;
		}
	}

	function customer_create(){

		$data = array(
			'CardCode'=>$this->input->post('ccode'),
			'CardName'=>$this->input->post('cname'),
			'Address'=>$this->input->post('location'),
			'Address2'=>$this->input->post('location2'),
			'Address3'=>$this->input->post('location3'),
			'Address4'=>$this->input->post('location4'),
			'Address5'=>$this->input->post('location5'),
			'Address6'=>$this->input->post('location6'),
			'Account_Executive'=>$this->input->post('ae'),
			'AE_Email'=>$this->input->post('ae_email'),
			'Logistics'=>$this->input->post('log'),
			'Logistics_Email'=>$this->input->post('log_email'),
			'Customer_Addressee'=>$this->input->post('cadd'),
			'Customer_Email'=>$this->input->post('cust_email'),
			'Customer_Email2'=>$this->input->post('cust_email2'),
			'Status'=>1,
			'CusType'=>'OPEN MARKET',
			'Type'=>'C',
			'AE_Mobile'=>$this->input->post('ae_mobile'),
			'Logistics_Mobile'=>$this->input->post('logistics_mobile')

		);

		$this->db->insert('ocrd', $data);

	}

	function customer_get(){

		$card_code = $this->uri->segment(3);
		$q=$this->db->get_where('ocrd',array('CardCode'=>$card_code));

		if ($q->num_rows()==1){
			return $q->result();
		}
	}

	function customer_update(){
		$data = array(
			'Customer_Addressee' => $this->input->post('cadd'),
			'Customer_Email' => $this->input->post('cust_email'),
			'Customer_Email2' => $this->input->post('cust_email2'),
			'Account_Executive' => $this->input->post('ae'),
			'AE_Email' => $this->input->post('ae_email'),
			'Logistics' => $this->input->post('log'),
			'Logistics_Email' => $this->input->post('log_email'),
			'Address' => $this->input->post('location'),
			'Address2' => $this->input->post('location2'),
			'Address3' => $this->input->post('location3'),
			'Address4' => $this->input->post('location4'),
			'Address5' => $this->input->post('location5'),
			'Address6' => $this->input->post('location6'),
			'AE_Mobile'=>$this->input->post('ae_mobile'),
			'Logistics_Mobile'=>$this->input->post('logistics_mobile'),
			'truck_seal'=>$this->input->post('truck_seal')
		);
		$this->db->where('CardCode',$this->uri->segment(3));
		$this->db->update('ocrd',$data);
	}

	function check_if_cust_trans(){

		$this->db->where('wi_refname', $this->uri->segment(3));
		$qry = $this->db->get('whir');

		if($qry->num_rows() > 0){
			return true;
		}

	}
	
	function sub_type_del_out(){

		// $this->db->order_by('Name');
		$this->db->where('Status', 1);
		$this->db->where('Type', 'Delivery Out');
		$qry = $this->db->get('aodt2');
		foreach ($qry->result_array() as $r){
			$data[$r['Code']]=$r['Name'];
		}
		return $data;
	}

	function sub_type_del_in(){

		$this->db->order_by('Class');
		$this->db->where('Status', 1);
		$this->db->where('Type', 'Delivery In');
		$qry = $this->db->get('aodt2');
		foreach ($qry->result_array() as $r){
			$data[$r['Code']]=$r['Name'];
		}
		return $data;
	}

	function get_del_type(){

		$id = $this->uri->segment(3);
		$id = explode('/', $id);

		// $this->db->where('wi_id', $id);
		// $qry = $this->db->get('whir');

		$qry = $this->db->query("SELECT *, b.wh_code 
			FROM whir a
			LEFT JOIN mwhr b ON b.wh_name = a.wh_name 
			WHERE wi_id = '$id[0]' ");

		if($qry->num_rows() == 1){
			return $qry->result();
		}

	}


	function del_in_edit(){

		$tokens = explode('/', current_url());
		$wi_id = $tokens[sizeof($tokens)-1];

		$this->db->where('wi_id', $wi_id);
		$qry = $this->db->get('whir');

		if($qry->num_rows() == 1){
			return $qry->result();
		}
	}

	function del_out_edit(){

		$tokens = explode('/', current_url());
		$wi_id = $tokens[sizeof($tokens)-1];

		$this->db->where('wi_id', $wi_id);
		$qry = $this->db->get('whir');

		if($qry->num_rows() == 1){
			return $qry->result();
		}
	}

	function check_deltype(){

		$tokens = explode ('_',current_url());
		$id = $tokens[sizeof($tokens)-1];

		$this->db->where('wi_id',$id);
		$qry = $this->db->get('whir');

		if($qry->num_rows() == 1){
			return $qry->result();
		}
	}

	function check_if_dout_approve_exist($whouse_id){

		// $tokens = explode ('_',current_url());
		// $id = $tokens[sizeof($tokens)-1];

		$data = array(
			'wi_id'=>$whouse_id['wi_id']
		);

		$this->db->where($data);
		$qry = $this->db->get('dspr_dout_approve');

		if($qry->num_rows() == 1){
			$this->db->set('approve_status', 1);
			$this->db->where($data);
			$this->db->update('dspr_dout_approve');
		}

	}

	// function dout_approve_delete(){

	// 	$tokens = explode ('_',current_url());
	// 	$id = $tokens[sizeof($tokens)-1];

	// 	$this->db->where('wi_id',$id);
	// 	$this->db->delete('dspr_dout_approve');

	// }

	// function dspr(){
	// 	$date = 'deldate';
	// 	$today = date('Y-m-d'); 
	// 	$date1 = str_replace('-', '/', $today);
	// 	$yesterday = date('Y-m-d',strtotime($date1 . "-1 days"));
	// 	$yesterday = $yesterday.' 23:59:59';
	// 	//$yesterday = '2016-05-30 23:59:59';
	// 	$whse = $this->input->post('whouse');

	// 	if($this->input->post('Search')){
	// 		$q = $this->db->query("
	// 			SELECT 
	// 				a.comm__id 'ID',
	// 				a.comm__name 'Dscription',
	// 				((SELECT IFNULL(SUM(`wi_itemqty`),0) FROM `whir` WHERE `wh_name` = '$whse' AND `wi_type` = 0 AND `wi_status` = 1 AND `wi_approvestatus` = 1 AND `item_id` = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday') - 
	// 					((SELECT IFNULL(SUM(`wi_itemqty`),0) FROM `whir` WHERE `wh_name` = '$whse' AND `wi_type` = 1 AND `wi_status` = 1 AND `wi_approvestatus` = 1 AND `item_id` = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday') + 
	// 						(SELECT IFNULL(SUM(`wi_itemqty`),0) FROM `whir` WHERE `wh_name` = '$whse' AND `wi_type` = 2 AND `wi_status` = 1 AND `wi_approvestatus` = 1 AND `item_id` = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday') ) ) AS  'BegBal',

	// 				(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'RR' ) + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'RR' )  AS 'RR',

	// 				((SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'DR') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'DR') + 
	// 					(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'DR') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'DR') ) AS 'DR',

	// 				((SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'WIS') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'WIS') + 
	// 					(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'WIS') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'WIS') ) AS 'WIS',

	// 				((SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'ATW') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'ATW') + 
	// 					(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'ATW') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'ATW') ) AS 'ATW',

	// 				((SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'WAR') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'WAR') +
	// 					(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'WAR') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'WAR') +
	// 					(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'WAR') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'WAR')) AS 'WAR',

	// 				((SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'DO') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'DO') +
	// 					(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'DO') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'DO') +
	// 					(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'DO') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'DO')) AS 'ITMQTY',

	// 				((SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'DO') + (SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'DO') +
	// 					(SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'DO') + (SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'DO') +
	// 					(SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'DO') + (SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'DO')) AS 'DOQTY',

	// 				((SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'ITO') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'ITO') +
	// 					(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'ITO') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'ITO') +
	// 					(SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'ITO') + (SELECT IFNULL(sum(wi_itemqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'ITO')) AS 'I_ITMQTY',

	// 				((SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'ITO') + (SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=0 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'ITO') +
	// 					(SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'ITO') + (SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=1 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'ITO') +
	// 					(SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype = 'ITO') + (SELECT IFNULL(sum(wi_doqty), 0) FROM whir WHERE wi_type=2 AND wi_status=1 AND wi_approvestatus=1 AND wh_name = '$whse' AND item_id = a.comm__id AND `wi_createdatetime` BETWEEN '2014-01-01 00:00:01' AND '$yesterday' AND wi_reftype2 = 'ITO')) AS 'I_DOQTY'

	// 			FROM ict_ims.ocmt a
				

	// 		");				
	// 		if ($q->num_rows() == true){
	// 			return $q->result();
	// 		}
	// 	}
	// 	else{
	// 		return false;
	// 	}
	// }


	function dspr(){
		$date = 'deldate';
		$today = date('Y-m-d');
		$date_today =  date('Y-m-d');
		$date1 = str_replace('-', '/', $today);
		$yesterday = date('Y-m-d',strtotime($date1 . "-1 days"));
		// $yesterday = $yesterday.' 23:59:59';
		//$yesterday = '2016-05-30 23:59:59';
		$whse = $this->input->post('whouse');

		if($this->input->post('Search')){
			$q = $this->db->query("
				SELECT 
					a.comm__id 'ID',
					a.comm__name 'Dscription',
					
					(((SELECT IFNULL(SUM(itemqty),0) FROM dspr_din WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_din) AND '$date_today') - 
						((SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout) AND '$date_today') + 
							(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout_approve) AND '$date_today'))) +
							 	(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout_approve) AND '$date_today' AND approve_status = 2)) AS 'BegBal',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_rr WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_rr) AND '$date_today') AS 'RR',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dr WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dr) AND '$date_today') +
						((SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout_approve) AND '$date_today' AND reftype = 'DR' AND approve_status = 2) + 
						(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout_approve) AND '$date_today' AND reftype2 = 'DR' AND approve_status = 2)) AS 'DR',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_wis WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_wis) AND '$date_today') +
						((SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout_approve) AND '$date_today' AND reftype = 'WIS' AND approve_status = 2) + 
						(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout_approve) AND '$date_today' AND reftype2 = 'WIS' AND approve_status = 2)) AS 'WIS',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_atw WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_atw) AND '$date_today') AS 'ATW',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_war WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_war) AND '$date_today') + 
						(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout_approve) AND '$date_today' AND reftype = 'WAR' AND approve_status = 2) + 
						(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout_approve) AND '$date_today' AND reftype2 = 'WAR' AND approve_status = 2) AS 'WAR',

					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout) AND '$date_today' AND reftype = 'DO') + 
						(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout) AND '$date_today' AND reftype2 = 'DO') + 
					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout) AND '$date_today' AND reftype = 'WIS') + 
						(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout) AND '$date_today' AND reftype2 = 'WIS') + 
					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout) AND '$date_today' AND reftype = 'ATW') + 
						(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout) AND '$date_today' AND reftype2 = 'ATW') AS 'UDO',

					(SELECT IFNULL(SUM(itoqty),0) FROM dspr_din_ito WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN (SELECT MAX(posting_date) FROM dspr_dout) AND '$date_today') AS 'ITO'

				FROM ict_ims.ocmt a
				
 
			");				
			if ($q->num_rows() == true){
				return $q->result();
			}
		}
		else{
			return false;
		}
	}

	function dspr_sort(){

		$pdate_start = $this->input->post('posting_date_start');
		$pdate_end = $this->input->post('posting_date_end');

		$whse = $this->input->post('whouse');

		if($this->input->post('Search')){
			$q = $this->db->query("
				SELECT 
					a.comm__id 'ID',
					a.comm__name 'Dscription',
					
					(((SELECT IFNULL(SUM(itemqty),0) FROM dspr_din WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end') - 
						((SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end') + 
							(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end'))) +
							 	(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND approve_status = 2)) AS 'BegBal',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_rr WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end') AS 'RR',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dr WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end') +
						((SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype = 'DR' AND approve_status = 2) + 
						(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype2 = 'DR' AND approve_status = 2)) AS 'DR',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_wis WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end') +
						((SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype = 'WIS' AND approve_status = 2) + 
						(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype2 = 'WIS' AND approve_status = 2)) AS 'WIS',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_atw WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end') AS 'ATW',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_war WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end') + 
						(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype = 'WAR' AND approve_status = 2) + 
						(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout_approve WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype2 = 'WAR' AND approve_status = 2) AS 'WAR',

					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype = 'DO') + 
						(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype2 = 'DO') + 
					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype = 'WIS') + 
						(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype2 = 'WIS') + 
					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype = 'ATW') + 
						(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end' AND reftype2 = 'ATW') AS 'UDO',

					(SELECT IFNULL(SUM(itoqty),0) FROM dspr_din_ito WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date BETWEEN '$pdate_start' AND '$pdate_end') AS 'ITO'

				FROM ict_ims.ocmt a
				
			");				
			if ($q->num_rows() == true){
				return $q->result();
			}
		}
		else{
			return false;
		}
	}

	// OLD QUERY 07032017
	// function dspr_sort_aofdate(){

	// 	$aofdate = $this->input->post('aofdate');
	// 	$aofdate2 = $this->input->post('aofdate');
	// 	$aofdate2 = date('Y-m-d',strtotime($aofdate2 . "-1 days"));

	// 	$whse = $this->input->post('whouse');

	// 	if($this->input->post('Search')){
	// 		$q = $this->db->query("
	// 			SELECT 
	// 				a.comm__id 'ID',
	// 				a.comm__name 'Dscription',

	// 				(((SELECT IFNULL(SUM(itemqty),0) FROM dspr_din WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date <= '$aofdate') - 
	// 				((SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date <= '$aofdate') +
	// 					(SELECT IFNULL(SUM(wi_itemqty),0) FROM whir WHERE wh_name = '$whse' AND item_id = a.comm__id AND deldate <= '$aofdate' AND wi_status = 1 AND wi_type = 2))) +
	// 					 	(SELECT IFNULL(SUM(wi_itemqty),0) FROM whir WHERE wh_name = '$whse' AND item_id = a.comm__id AND deldate <= '$aofdate' AND wi_status = 1 AND wi_type = 2)) AS 'BegBal',
					
	// 				(SELECT IFNULL(SUM(itemqty),0) FROM dspr_rr WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'RR',

	// 				(SELECT IFNULL(SUM(itemqty),0) FROM dspr_din WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype <> RR) AS 'OR',

	// 				(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dr WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'DR',

	// 				(SELECT IFNULL(SUM(itemqty),0) FROM dspr_wis WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'WIS',

	// 				(SELECT IFNULL(SUM(itemqty),0) FROM dspr_atw WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'ATW',

	// 				(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND (reftype <> 'DR') AND (reftype <> 'WIS') AND (reftype <> 'ATW') ) AS 'OI',

	// 				(SELECT IFNULL(SUM(itemqty),0) FROM dspr_war WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'WAR',

	// 				(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype = 'DO') + 
	// 					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype2 = 'DO') + 
	// 				(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype = 'WIS') + 
	// 					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype2 = 'WIS') + 
	// 				(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype = 'ATW') + 
	// 					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype2 = 'ATW') AS 'UDO',

	// 				(SELECT IFNULL(SUM(itoqty),0) FROM dspr_din_ito WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'ITO'

	// 			FROM ict_ims.ocmt a
				
	// 		");				
	// 		if ($q->num_rows() == true){
	// 			return $q->result();
	// 		}
	// 	}
	// 	else{
	// 		return false;
	// 	}
	// }

	function dspr_sort_aofdate(){

		$begbal_date = $this->input->post('aofdate');
		$begbal_date = date('Y-m-d',strtotime($begbal_date . "-1 days"));

		$aofdate = $this->input->post('aofdate');

		$whse = $this->input->post('whouse');

		if($this->input->post('Search')){
			$q = $this->db->query("
				SELECT 
					a.comm__id 'ID',
					a.comm__name 'Dscription',

					(((
						SELECT IFNULL(SUM(itemqty),0) FROM dspr_din WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = 
						(
							SELECT 
								CASE 
								WHEN STR_TO_DATE('$begbal_date', '%Y-%m-%d') <= MIN(DATE(deldate))
								THEN MIN(DATE(deldate))
								ELSE
									CASE 
								    WHEN STR_TO_DATE('$begbal_date', '%Y-%m-%d') >= MAX(DATE(deldate))
								    THEN IF((SELECT deldate FROM whir where wh_name = '$whse' AND item_id = '$whse' AND deldate = STR_TO_DATE('$begbal_date', '%Y-%m-%d')) IS NULL, MIN(DATE(deldate)), MAX(DATE(deldate)))
								    ELSE MIN(DATE(deldate))
								    END
								END 'deldate'
							FROM whir
							WHERE wh_name = '$whse' 
							AND item_id = a.comm__id
						)


						) - 
					((SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = 
						(
							
							SELECT 
								CASE 
								WHEN STR_TO_DATE('$begbal_date', '%Y-%m-%d') <= MIN(DATE(deldate))
								THEN MIN(DATE(deldate))
								ELSE
									CASE 
								    WHEN STR_TO_DATE('$begbal_date', '%Y-%m-%d') >= MAX(DATE(deldate))
								    THEN MAX(DATE(deldate))
								    ELSE MIN(DATE(deldate))
								    END
								END 'deldate'
							FROM whir
							WHERE wh_name = '$whse' 
							AND item_id = a.comm__id

						)

						) +
						(SELECT IFNULL(SUM(wi_itemqty),0) FROM whir WHERE wh_name = '$whse' AND item_id = a.comm__id AND deldate = 
							(
								SELECT 
									CASE 
									WHEN STR_TO_DATE('$begbal_date', '%Y-%m-%d') <= MIN(DATE(deldate))
									THEN MIN(DATE(deldate))
									ELSE
										CASE 
									    WHEN STR_TO_DATE('$begbal_date', '%Y-%m-%d') >= MAX(DATE(deldate))
									    THEN MAX(DATE(deldate))
									    ELSE MIN(DATE(deldate))
									    END
									END 'deldate'
								FROM whir
								WHERE wh_name = '$whse' 
								AND item_id = a.comm__id
							) 
							AND wi_status = 1 AND wi_type = 2))) +

						 	(SELECT IFNULL(SUM(wi_itemqty),0) FROM whir WHERE wh_name = '$whse' AND item_id = a.comm__id AND deldate = 
						 		(
									SELECT 
										CASE 
										WHEN STR_TO_DATE('$begbal_date', '%Y-%m-%d') <= MIN(DATE(deldate))
										THEN MIN(DATE(deldate))
										ELSE
											CASE 
										    WHEN STR_TO_DATE('$begbal_date', '%Y-%m-%d') >= MAX(DATE(deldate))
										    THEN MAX(DATE(deldate))
										    ELSE MIN(DATE(deldate))
										    END
										END 'deldate'
									FROM whir
									WHERE wh_name = '$whse' 
									AND item_id = a.comm__id
								) 
						 		AND wi_status = 1 AND wi_type = 2)) AS 'BegBal',
					
					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_rr WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'RR',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_din WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype NOT IN ('RR')) AS 'OR',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dr WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'DR',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_wis WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'WIS',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_atw WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'ATW',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype NOT IN ('DR', 'WIS', 'ATW', 'DO') ) AS 'OI',

					(SELECT IFNULL(SUM(itemqty),0) FROM dspr_war WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'WAR',

					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype = 'DO') + 
						(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype2 = 'DO') + 
					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype = 'WIS') + 
						(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype2 = 'WIS') + 
					(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype = 'ATW') + 
						(SELECT IFNULL(SUM(udoqty),0) FROM dspr_dout WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate' AND reftype2 = 'ATW') AS 'UDO',

					(SELECT IFNULL(SUM(itoqty),0) FROM dspr_din_ito WHERE wh_name = '$whse' AND item_id = a.comm__id AND posting_date = '$aofdate') AS 'ITO'

				FROM ict_ims.ocmt a

			");				
			if ($q->num_rows() == true){
				return $q->result();
			}
		}
		else{
			return false;
		}
	}


	function dspr_din($din_rec){

		$data = array(
			'wi_id'=>$din_rec['wi_id'],
			'item_id'=>$din_rec['item_id'],
			'wh_name'=>$din_rec['wh_name'],
			'deltype'=>$din_rec['deltype'],
			'itemqty'=>$din_rec['itemqty'],
			'trans_datetime'=>$din_rec['trans_datetime'],
			'posting_date'=>$din_rec['posting_date'],
			'reftype'=>$din_rec['reftype'],
			'refnum'=>$din_rec['refnum'],
			'reftype2'=>$din_rec['reftype2'],
			'refnum2'=>$din_rec['refnum2']
		);

		$this->db->insert('dspr_din', $data);

	}

	function dspr_din_rr($din_rec_rr){

		$data = array(
			'wi_id'=>$din_rec_rr['wi_id'],
			'item_id'=>$din_rec_rr['item_id'],
			'wh_name'=>$din_rec_rr['wh_name'],
			'deltype'=>$din_rec_rr['deltype'],
			'itemqty'=>$din_rec_rr['itemqty'],
			'trans_datetime'=>$din_rec_rr['trans_datetime'],
			'posting_date'=>$din_rec_rr['posting_date'],
			'reftype'=>$din_rec_rr['reftype'],
			'refnum'=>$din_rec_rr['refnum'],
			'reftype2'=>$din_rec_rr['reftype2'],
			'refnum2'=>$din_rec_rr['refnum2']
		);

		$this->db->insert('dspr_rr', $data);

	}

	function dspr_din_ito($din_rec_ito){

		$data = array(
			'wi_id'=>$din_rec_ito['wi_id'],
			'item_id'=>$din_rec_ito['item_id'],
			'wh_name'=>$din_rec_ito['wh_name'],
			'deltype'=>$din_rec_ito['deltype'],
			'doqty'=>$din_rec_ito['doqty'],
			'itemqty'=>$din_rec_ito['itemqty'],
			'itoqty'=>$din_rec_ito['itoqty'],
			'trans_datetime'=>$din_rec_ito['trans_datetime'],
			'posting_date'=>$din_rec_ito['posting_date'],
			'reftype'=>$din_rec_ito['reftype'],
			'refnum'=>$din_rec_ito['refnum'],
			'reftype2'=>$din_rec_ito['reftype2'],
			'refnum2'=>$din_rec_ito['refnum2']
		);

		$this->db->insert('dspr_din_ito', $data);

	}

	function dspr_din_war($din_rec_war){

		$data = array(
			'wi_id'=>$din_rec_war['wi_id'],
			'item_id'=>$din_rec_war['item_id'],
			'wh_name'=>$din_rec_war['wh_name'],
			'deltype'=>$din_rec_war['deltype'],
			'doqty'=>$din_rec_war['doqty'],
			'itemqty'=>$din_rec_war['itemqty'],
			'trans_datetime'=>$din_rec_war['trans_datetime'],
			'posting_date'=>$din_rec_war['posting_date'],
			'reftype'=>$din_rec_war['reftype'],
			'refnum'=>$din_rec_war['refnum'],
			'reftype2'=>$din_rec_war['reftype2'],
			'refnum2'=>$din_rec_war['refnum2']
		);

		$this->db->insert('dspr_war', $data);

	}

	function dspr_dout($dout_rec){

		$data = array(
			'wi_id'=>$dout_rec['wi_id'],
			'item_id'=>$dout_rec['item_id'],
			'wh_name'=>$dout_rec['wh_name'],
			'deltype'=>$dout_rec['deltype'],
			'itemqty'=>$dout_rec['itemqty'],
			'doqty'=>$dout_rec['doqty'],
			'udoqty'=>$dout_rec['udoqty'],
			'trans_datetime'=>$dout_rec['trans_datetime'],
			'posting_date'=>$dout_rec['posting_date'],
			'reftype'=>$dout_rec['reftype'],
			'refnum'=>$dout_rec['refnum'],
			'reftype2'=>$dout_rec['reftype2'],
			'refnum2'=>$dout_rec['refnum2']
		);

		$this->db->insert('dspr_dout', $data);

	}

	function dspr_dout_approve($dout_rec_approve){

		$data = array(
			'wi_id'=>$dout_rec_approve['wi_id'],
			'item_id'=>$dout_rec_approve['item_id'],
			'wh_name'=>$dout_rec_approve['wh_name'],
			'deltype'=>$dout_rec_approve['deltype'],
			'itemqty'=>$dout_rec_approve['itemqty'],
			'doqty'=>$dout_rec_approve['doqty'],
			'trans_datetime'=>$dout_rec_approve['trans_datetime'],
			'posting_date'=>$dout_rec_approve['posting_date'],
			'reftype'=>$dout_rec_approve['reftype'],
			'refnum'=>$dout_rec_approve['refnum'],
			'reftype2'=>$dout_rec_approve['reftype2'],
			'refnum2'=>$dout_rec_approve['refnum2'],
			'approve_status'=>2
		);

		$this->db->insert('dspr_dout_approve', $data);

	}

	function dspr_dout_war($dout_rec_war){

		$data = array(
			'wi_id'=>$dout_rec_war['wi_id'],
			'item_id'=>$dout_rec_war['item_id'],
			'wh_name'=>$dout_rec_war['wh_name'],
			'deltype'=>$dout_rec_war['deltype'],
			'itemqty'=>$dout_rec_war['itemqty'],
			'trans_datetime'=>$dout_rec_war['trans_datetime'],
			'posting_date'=>$dout_rec_war['posting_date'],
			'reftype'=>$dout_rec_war['reftype'],
			'refnum'=>$dout_rec_war['refnum'],
			'reftype2'=>$dout_rec_war['reftype2'],
			'refnum2'=>$dout_rec_war['refnum2']
		);

		$this->db->insert('dspr_war', $data);

	}

	function dspr_dout_wis($dout_rec_wis){

		$data = array(
			'wi_id'=>$dout_rec_wis['wi_id'],
			'item_id'=>$dout_rec_wis['item_id'],
			'wh_name'=>$dout_rec_wis['wh_name'],
			'deltype'=>$dout_rec_wis['deltype'],
			'itemqty'=>$dout_rec_wis['itemqty'],
			'trans_datetime'=>$dout_rec_wis['trans_datetime'],
			'posting_date'=>$dout_rec_wis['posting_date'],
			'reftype'=>$dout_rec_wis['reftype'],
			'refnum'=>$dout_rec_wis['refnum'],
			'reftype2'=>$dout_rec_wis['reftype2'],
			'refnum2'=>$dout_rec_wis['refnum2']
		);

		$this->db->insert('dspr_wis', $data);

	}

	function dspr_dout_dr($dout_rec_dr){

		$data = array(
			'wi_id'=>$dout_rec_dr['wi_id'],
			'item_id'=>$dout_rec_dr['item_id'],
			'wh_name'=>$dout_rec_dr['wh_name'],
			'deltype'=>$dout_rec_dr['deltype'],
			'itemqty'=>$dout_rec_dr['itemqty'],
			'trans_datetime'=>$dout_rec_dr['trans_datetime'],
			'posting_date'=>$dout_rec_dr['posting_date'],
			'reftype'=>$dout_rec_dr['reftype'],
			'refnum'=>$dout_rec_dr['refnum'],
			'reftype2'=>$dout_rec_dr['reftype2'],
			'refnum2'=>$dout_rec_dr['refnum2']
		);

		$this->db->insert('dspr_dr', $data);

	}

	function dspr_dout_atw($dout_rec_atw){

		$data = array(
			'wi_id'=>$dout_rec_atw['wi_id'],
			'item_id'=>$dout_rec_atw['item_id'],
			'wh_name'=>$dout_rec_atw['wh_name'],
			'deltype'=>$dout_rec_atw['deltype'],
			'itemqty'=>$dout_rec_atw['itemqty'],
			'trans_datetime'=>$dout_rec_atw['trans_datetime'],
			'posting_date'=>$dout_rec_atw['posting_date'],
			'reftype'=>$dout_rec_atw['reftype'],
			'refnum'=>$dout_rec_atw['refnum'],
			'reftype2'=>$dout_rec_atw['reftype2'],
			'refnum2'=>$dout_rec_atw['refnum2']
		);

		$this->db->insert('dspr_atw', $data);

	}

	function summary_of_receipts(){

		$whse = $this->input->post('whouse');

		$qry = $this->db->query("SELECT * FROM whir a
								LEFT JOIN ocmt b
									ON a.item_id = b.comm__id
								LEFT JOIN ocrd c
									ON a.wi_refname = c.CardCode
								LEFT JOIN mwhr d
									ON a.wh_name = d.wh_name	
								WHERE a.wi_deltype  IN ('Delivery In', 'Material Management')
									AND a.wi_type = 0
									AND a.wi_approvestatus = 1 
									AND a.wh_name = '$whse'
									AND a.wi_status = 1 
								ORDER BY a.deldate DESC ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function summary_of_receipts_sort(){

		$whse = $this->input->post('whouse');
		$pstart = $this->input->post('posting_date_start');
		$pend = $this->input->post('posting_date_end');

		$qry = $this->db->query("SELECT * FROM whir a
								LEFT JOIN ocmt b
									ON a.item_id = b.comm__id
								LEFT JOIN ocrd c
									ON a.wi_refname = c.CardCode
								LEFT JOIN mwhr d
									ON a.wh_name = d.wh_name	
								WHERE a.wi_deltype  IN ('Delivery In', 'Material Management')
									AND a.wi_type = 0
									AND a.wi_approvestatus = 1 
									AND a.wh_name = '$whse'
									AND a.wi_status = 1 
									AND a.deldate 
										BETWEEN '$pstart' 
										AND '$pend' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function summary_of_issuance(){

		$whse = $this->input->post('whouse');

		$qry = $this->db->query("SELECT * FROM whir a
								LEFT JOIN ocmt b
									ON a.item_id = b.comm__id
								LEFT JOIN ocrd c
									ON a.wi_refname = c.CardCode
								LEFT JOIN mwhr d
									ON a.wh_name = d.wh_name	
								WHERE a.wi_deltype  IN ('Delivery Out', 'Material Management')
									AND a.wi_type = 1
									AND a.wi_approvestatus = 1 
									AND a.wh_name = '$whse'
									AND a.wi_status = 1 
								ORDER BY a.deldate DESC ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function summary_of_issuance_sort(){

		$whse = $this->input->post('whouse');
		$pstart = $this->input->post('posting_date_start');
		$pend = $this->input->post('posting_date_end');

		$qry = $this->db->query("SELECT * FROM whir a
								LEFT JOIN ocmt b
									ON a.item_id = b.comm__id
								LEFT JOIN ocrd c
									ON a.wi_refname = c.CardCode
								LEFT JOIN mwhr d
									ON a.wh_name = d.wh_name	
								WHERE a.wi_deltype  IN ('Delivery Out', 'Material Management')
									AND a.wi_type = 1
									AND a.wi_approvestatus = 1 
									AND a.wh_name = '$whse'
									AND a.wi_status = 1 
									AND a.deldate 
										BETWEEN '$pstart' 
										AND '$pend' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function check_intransit(){

		$dtype2 = $this->input->post('doctype2');
		$ref2 = $this->input->post('ref2');

		$qry = $this->db->query("SELECT wi_intransit FROM whir t1
								WHERE t1.wi_reftype2 = '$dtype2'
								AND t1.wi_refnum2 = '$ref2' 
								AND t1.deldate = (SELECT MAX(deldate) FROM whir)");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function get_item_name(){

		$item_no = $this->input->post('whitem');
		$this->db->where('comm__id', $item_no);

		$qry = $this->db->get('ocmt');

		foreach ($qry->result() as $row){
		  return $row->comm__name;
		}

	}

	function get_item_name_sap(){

		$item_no = $this->input->post('item_code');
		$this->db->where('comm__id', $item_no);

		$qry = $this->db->get('ocmt');

		foreach ($qry->result() as $row){
		  return $row->comm__name;
		}

	}

	function get_customer_name(){

		$cust_no = $this->input->post('sub_out_customer');
		$this->db->select('CardName');
		$this->db->where('CardCode', $cust_no);
		$qry = $this->db->get('ocrd');

		foreach ($qry->result() as $row){
		  return $row->CardName;
		}

	}

	function get_customer_name_sap(){

		$cust_no = $this->input->post('cust_code');
		$this->db->select('CardName');
		$this->db->where('CardCode', $cust_no);
		$qry = $this->db->get('ocrd');

		foreach ($qry->result() as $row){
		  return $row->CardName;
		}

	}

	function get_warehouse_name(){

		$wcode = $this->uri->segment(3);
		$this->db->select('wh_name');
		$this->db->where('wh_code', $wcode);
		$qry = $this->db->get('mwhr');

		foreach ($qry->result() as $row){
		  return $row->wh_name;
		}

	}

	function get_warehouse_name_sap(){

		$wcode = $this->uri->segment(3);
		$this->db->select('wh_name');
		$this->db->where('wh_code', $wcode);
		$qry = $this->db->get('mwhr');

		foreach ($qry->result() as $row){
		  return $row->wh_name;
		}

	}

	// function get_DO_from_SAP(){

	// 	$db2 = $this->load->database('db2',TRUE);
	// 	$q1 = $this->db->query("SELECT * FROM sap_do_data");

	// 	if($q1->num_rows() > 0){

	// 	}else{
	// 		$q3 = $db2->query("
				
	// 			SELECT   
	// 				CONVERT(DATE, A.DocDate) AS Dodate, 
	// 				CONVERT(DATE, A.DocDueDate) AS SAPExpDeldate,
	// 				DATEDIFF(dd,A.DocDate,A.DocDueDate) as 'DateGap',
	// 				A.DocNum AS DONo, 
	// 				A.Series, A.U_Drno AS DefDocType,
	// 				A.U_TransDate AS TransmitDate,
	// 				A.U_DOTransDate,
	// 				'o' AS Web_DoStat, 
	// 				A.CardCode, 
	// 				A.CardName, 
	// 				A.NumatCard AS PepsiSDRNo, 
	// 				A.Address2 AS Location, 
	// 				A.U_PONo AS MotherPO, 
					
	// 				T.Code AS TruckCode, 
	// 				T.Name AS TruckCo,
					 
	// 				B.WhsCode, 
					
	// 				W.WhsName AS 'Source', 
	// 				W.U_Whse_Type,
					
	// 				B.U_SAdockey, 
	// 				B.U_SADocNo, 
	// 				B.U_SADocLine1, 
	// 				B.U_SATransNo, 
	// 				B.U_SATransTyp,

	// 				B.ItemCode, 
	// 				B.Dscription, 
	// 				B.UnitMsr, 
	// 				B.Quantity as DoQty, 
	// 				B.OpenCreQty AS B1UnservedQty, 
	// 				(B.Quantity - B.OpenCreQty) AS Served,
	// 				B.U_PONo AS TransPO, 

	// 				C.SlpCode, 
	// 				S.SlpName AS AE, 
	// 				A.Comments AS DoRemarks, 
	// 				A.UpdateDate,
	// 				A.DocEntry,
	// 				A.ObjType,
	// 				B.LineNum

	// 			FROM ODLN A
	// 				INNER JOIN DLN1 B ON A.DocEntry = B.DocEntry
	// 				INNER JOIN OITM I ON B.ItemCode = I.ItemCode
	// 				INNER JOIN OCRD C ON A.CardCode = C.CardCode
	// 				INNER JOIN OWHS W ON B.WhsCode = W.WhsCode
	// 				LEFT JOIN OSLP S ON C.SlpCode = S.SlpCode 
	// 				LEFT JOIN dbo.[@TRUCKER] T ON T.Code = A.U_Trucker
	// 			WHERE 
	// 				A.DocStatus = 'O' AND 
	// 				A.DocType = 'I' AND 
	// 				Printed = 'Y' AND 
	// 				I.U_Commodity = '02'
	// 			ORDER BY B.WhsCode

	// 		");

	// 				// A.U_DOTransDate >=(GETDATE() - 15) AND 
	// 				// A.DocDueDate >= GETDATE()

	// 		if($q3->num_rows() > 0){
	// 			foreach($q3->result() as $r3){
	// 				$data = array(
	// 					'doc_entry'=>$r3->DocEntry,
	// 					'obj_type'=>$r3->ObjType,
	// 					'line_num'=>$r3->LineNum,
	// 					'do_date'=>$r3->Dodate,
	// 					'sap_exp_del_date'=>$r3->SAPExpDeldate,
	// 					'date_gap'=>$r3->DateGap,
	// 					'do_no'=>$r3->DONo,
	// 					'series'=>$r3->Series,
	// 					'def_doc_type'=>$r3->DefDocType,
	// 					'transmit_date'=>$r3->TransmitDate,
	// 					'web_do_stat'=>0,
	// 					'card_code'=>$r3->CardCode,
	// 					'card_name'=>$r3->CardName,
	// 					'sdr_no'=>$r3->PepsiSDRNo,
	// 					'location'=>$r3->Location,
	// 					'mother_po'=>$r3->MotherPO,
	// 					'truck_code'=>$r3->TruckCode,
	// 					'truck_company'=>$r3->TruckCo,
	// 					'whse_code'=>$r3->WhsCode,
	// 					'source'=>$r3->Source,
	// 					'whse_type'=>$r3->U_Whse_Type,
	// 					'item_code'=>$r3->ItemCode,
	// 					'item_desc'=>$r3->Dscription,
	// 					'uom'=>$r3->UnitMsr,
	// 					'do_qty'=>$r3->DoQty,
	// 					'b1_udo_qty'=>$r3->B1UnservedQty,
	// 					'served_qty'=>$r3->Served,
	// 					'trans_po'=>$r3->TransPO,
	// 					'ae_code'=>$r3->SlpCode,
	// 					'ae'=>$r3->AE,
	// 					'do_remarks'=>$r3->DoRemarks,
	// 					'update_date'=>$r3->UpdateDate,
	// 					'sa_dockey'=>$r3->U_SAdockey,
	// 					'sa_docno'=>$r3->U_SADocNo,
	// 					'sa_docline'=>$r3->U_SADocLine1,
	// 					'sa_transno'=>$r3->U_SATransNo,
	// 					'sa_transtype'=>$r3->U_SATransTyp,
	// 				);

	// 				$this->db->insert('sap_do_data', $data);
	// 			}
	// 		}

	// 	}

	// }

	function check_if_DO_is_deliver_in_SAP() {

		$ims_whsecode = $this->uri->segment(3);
		$ims_whsename = "";

		$qry_whse = $this->db->where('WhsCode', $ims_whsecode)
						     ->get('whse_integration_ims');

		if($qry_whse->num_rows() > 0){
			foreach($qry_whse->result_array() as $qrec){
				$ims_whsename = $qrec['SAP_WhsCode'];
			}
		}else{
			$qry_whse2 = $this->db->where('wh_code', $ims_whsecode)
						     ->get('mwhr');

			if($qry_whse2->num_rows() > 0){
				foreach($qry_whse2->result_array() as $qrec2){
					$ims_whsename = $qrec2['wh_sapcode'];
				}
			}
		}

		$donum = $this->input->post('ref');

		$db3 = $this->load->database('db3',TRUE);

			$db3->where('a.U_DoNo', (int)$donum);
			$db3->where('a.DocStatus', 'C');
			$db3->where('a.DocType', 'I');
			$db3->where('a.Printed', 'Y');
			$db3->where('c.U_Commodity', '02');
			$db3->where('b.WhsCode', $ims_whsename);
			$db3->where('a.DocDate > ', '2016-10-05');

			$db3->select('CAST(a.DocDate AS DATE) AS Dodate,
							CAST(a.DocDueDate AS DATE) AS SAPExpDeldate,
							DATEDIFF(dd, a.DocDate, A.DocDueDate) AS DateGap,
							a.DocNum AS DONo,
							a.Series,
							a.U_DRNo AS DefDocType, 
							CAST(a.U_TransDate AS DATE) AS TransmitDate,
							CAST(a.U_DOTransDate AS DATE) AS U_DOTransDate,
							a.DocStatus,
							a.CardCode,
							a.CardName,
							a.NumatCard AS PepsiSDRNo,
							a.Address2 AS Location,
							a.U_PONo AS MotherPO,
							a.Comments AS DoRemarks,
							CAST(a.UpdateDate AS DATE) AS UpdateDate,
							a.DocEntry,
							a.ObjType,
							  
							b.LineNum,
							b.WhsCode,
							b.U_SAdockey,
							b.U_SADocNo,
							b.U_SADocLine1,
							b.U_SATransNo,
							b.U_SATransTyp,
							b.ItemCode,
							b.Dscription,
							b.UnitMsr,
							b.Quantity AS DoQty,
							b.OpenCreQty AS B1UnservedQty,
							(b.Quantity - b.OpenCreQty) AS Served,
							b.U_PONo AS TransPO,
							b.BaseLine AS BaseLine,
							b.BaseEntry AS BaseEntry,
							b.BaseType,

							d.SlpCode,

							e.WhsName AS Source,
							e.U_Whse_Type,

							f.SlpName AS AE,

							g.Code AS TruckCode,
							g.Name AS TruckCo
							  
						');

			$db3->from('ORDR a');
			$db3->join('RDR1 b', 'b.DocEntry = a.DocEntry', 'INNER');
			$db3->join('OITM c', 'c.ItemCode = b.ItemCode', 'INNER');
			$db3->join('OCRD d', 'd.CardCode = a.CardCode','INNER');
			$db3->join('OWHS e', 'e.WhsCode = b.WhsCode', 'INNER');
			$db3->join('OSLP f', 'f.SlpCode = d.SlpCode', 'LEFT');
			$db3->join('dbo.[@TRUCKERS] g', 'g.Code = a.U_Trucker', 'LEFT');
				
			$qry3 = $db3->get();

			if($qry3->num_rows() > 0){
				return TRUE;
			}

	}

	function check_if_DO_exists_from_SAP(){

		$ims_whsecode = $this->uri->segment(3);
		$ims_whsename = "";

		// $qry_whse = $this->db->where('WhsCode', $ims_whsecode)
		// 				     ->get('whse_integration_ims');

		// $qry_whse = $this->db->query("SELECT * FROM whse_integration_ims WHERE WhsCode = '$ims_whsecode' ");

		// if($qry_whse->num_rows() > 0){
		// 	foreach($qry_whse->result_array() as $qrec){
		// 		$ims_whsename = $qrec['SAP_WhsCode'];
		// 	}
		// } else {

		// 	$qry_whse2 = $this->db->query("SELECT * FROM mwhr WHERE wh_code = $ims_whsecode ");

		// 	// $qry_whse2 = $this->db->where('wh_code', $ims_whsecode)
		// 	// 			     ->get('mwhr');

		// 	if($qry_whse2->num_rows() > 0){
		// 		foreach($qry_whse2->result_array() as $qrec2){
		// 			$ims_whsename = $qrec2['wh_sapcode'];
		// 		}
		// 	}
		// }

		$qry_whse2 = $this->db->query("SELECT * FROM mwhr WHERE wh_code = $ims_whsecode ");

		if($qry_whse2->num_rows() > 0){
			foreach($qry_whse2->result_array() as $qrec2){
				$ims_whsename = $qrec2['wh_sapcode'];
			}
		}

		$donum = $this->input->post('ref');

		$db3 = $this->load->database('db3',TRUE);

		$db3->where('a.U_DoNo', (int)$donum);
		$db3->where('a.DocStatus', 'O');
		$db3->where('a.DocType', 'I');
		$db3->where('a.Printed', 'Y');
		$db3->where('c.U_Commodity', '02');
		$db3->where('b.WhsCode', $ims_whsename);
		$db3->where('a.DocDate > ', '2016-10-05');

		$db3->select('CAST(a.DocDate AS DATE) AS Dodate,
							CAST(a.DocDueDate AS DATE) AS SAPExpDeldate,
							DATEDIFF(dd, a.DocDate, A.DocDueDate) AS DateGap,
							a.DocNum AS DONo,
							a.Series,
							a.U_DRNo AS DefDocType, 
							CAST(a.U_TransDate AS DATE) AS TransmitDate,
							CAST(a.U_DOTransDate AS DATE) AS U_DOTransDate,
							a.DocStatus,
							a.CardCode,
							a.CardName,
							a.NumatCard AS PepsiSDRNo,
							a.Address2 AS Location,
							a.U_PONo AS MotherPO,
							a.Comments AS DoRemarks,
							CAST(a.UpdateDate AS DATE) AS UpdateDate,
							a.DocEntry,
							a.ObjType,
							  
							b.LineNum,
							b.WhsCode,
							b.U_SAdockey,
							b.U_SADocNo,
							b.U_SADocLine1,
							b.U_SATransNo,
							b.U_SATransTyp,
							b.ItemCode,
							b.Dscription,
							b.UnitMsr,
							b.Quantity AS DoQty,
							b.OpenCreQty AS B1UnservedQty,
							(b.Quantity - b.OpenCreQty) AS Served,
							b.U_PONo AS TransPO,
							b.BaseLine AS BaseLine,
							b.BaseEntry AS BaseEntry,
							b.BaseType,

							d.SlpCode,

							e.WhsName AS Source,
							e.U_Whse_Type,

							f.SlpName AS AE,

							g.Code AS TruckCode,
							g.Name AS TruckCo
							  
						');

		$db3->from('ORDR a');
		$db3->join('RDR1 b', 'b.DocEntry = a.DocEntry', 'INNER');
		$db3->join('OITM c', 'c.ItemCode = b.ItemCode', 'INNER');
		$db3->join('OCRD d', 'd.CardCode = a.CardCode','INNER');
		$db3->join('OWHS e', 'e.WhsCode = b.WhsCode', 'INNER');
		$db3->join('OSLP f', 'f.SlpCode = d.SlpCode', 'LEFT');
		$db3->join('dbo.[@TRUCKERS] g', 'g.Code = a.U_Trucker', 'LEFT');
				
		$qry3 = $db3->get();

		if ($qry3->num_rows() > 0) {
			return TRUE;
		}

	}

	function check_if_DO_exists_from_SAP_DO_DATA(){

		$do_no = $this->input->post('ref');

		$qry = $this->db->get_where('sap_do_data', array('do_no'=>$do_no));

		if($qry->num_rows() > 0){
			return TRUE;
		}

	}

	function insert_DO_to_SAP_DO_DATA(){

		$ims_whsecode = $this->uri->segment(3);
		$ims_whsename = "";

		// $qry_whse = $this->db->where('WhsCode', $ims_whsecode)
		// 				     ->get('whse_integration_ims');

		// if($qry_whse->num_rows() > 0){
		// 	foreach($qry_whse->result_array() as $qrec){
		// 		$ims_whsename = $qrec['SAP_WhsCode'];
		// 	}
		// }else{
		// 	$qry_whse2 = $this->db->where('wh_code', $ims_whsecode)
		// 				     ->get('mwhr');

		// 	if($qry_whse2->num_rows() > 0){
		// 		foreach($qry_whse2->result_array() as $qrec2){
		// 			$ims_whsename = $qrec2['wh_sapcode'];
		// 		}
		// 	}
		// }

		$qry_whse2 = $this->db->query("SELECT * FROM mwhr WHERE wh_code = $ims_whsecode ");

		if($qry_whse2->num_rows() > 0){
			foreach($qry_whse2->result_array() as $qrec2){
				$ims_whsename = $qrec2['wh_sapcode'];
			}
		}


			$donum = $this->input->post('ref');

			$db3 = $this->load->database('db3',TRUE);
			$db3->where('a.U_DoNo', (int)$donum);
			$db3->where('a.DocStatus', 'O');
			$db3->where('a.DocType', 'I');
			$db3->where('a.Printed', 'Y');
			$db3->where('c.U_Commodity', '02');
			$db3->where('b.WhsCode', $ims_whsename);
			$db3->where('a.DocDate > ', '2016-10-05');

			$db3->select("CAST(a.DocDate AS DATE) AS Dodate,
							  CAST(a.DocDueDate AS DATE) AS SAPExpDeldate,
							  DATEDIFF(dd, CAST(a.DocDate AS DATE), CAST(a.DocDueDate AS DATE)) AS DateGap,
							  a.DocNum AS DONo,
							  a.Series,
							  a.U_DRNo AS DefDocType,

							  CASE 
							  WHEN a.U_TransDate IS NULL
							  THEN '1900-01-01'
							  ELSE CAST(a.U_TransDate AS DATE)
							  END 'TransmitDate',

							  CASE 
							  WHEN a.U_DOTransDate IS NULL
							  THEN '1900-01-01' 
							  ELSE CAST(a.U_DOTransDate AS DATE)
							  END 'U_DOTransDate',

							  a.DocStatus,
							  a.CardCode,
							  a.CardName,

							  CASE
							  WHEN a.NumatCard IS NULL
							  THEN '' 
							  ELSE a.NumatCard
							  END 'PepsiSDRNo',

							  CASE
							  WHEN a.Address2 IS NULL
							  THEN ''
							  ELSE a.Address2
							  END 'Location',

							  CASE
							  WHEN a.U_PONo IS NULL
							  THEN ''
							  ELSE a.U_PONo
							  END 'MotherPO',

							  CASE
							  WHEN a.Comments IS NULL
							  THEN '' 
							  ELSE a.Comments
							  END 'DoRemarks',

							  CASE
							  WHEN a.UpdateDate IS NULL
							  THEN '1900-01-01' 
							  ELSE CAST(a.UpdateDate AS DATE)
							  END 'UpdateDate',

							  a.DocEntry,
							  a.ObjType,
							  
							  b.LineNum,
							  b.WhsCode,

							  CASE
							  WHEN b.U_SAdockey IS NULL
							  THEN '0'
							  ELSE b.U_SAdockey
							  END 'U_SAdockey',

							  CASE
							  WHEN b.U_SADocNo IS NULL
							  THEN '0'
							  ELSE b.U_SADocNo
							  END 'U_SADocNo',

							  CASE
							  WHEN b.U_SADocLine1 IS NULL
							  THEN '0'
							  ELSE b.U_SADocLine1
							  END 'U_SADocLine1',

							  CASE 
							  WHEN b.U_SATransNo IS NULL
							  THEN '0'
							  ELSE b.U_SATransNo
							  END 'U_SATransNo',

							  b.U_SATransTyp,
							  b.ItemCode,
							  b.Dscription,
							  b.UnitMsr,
							  b.Quantity AS DoQty,

							  CASE
							  WHEN b.OpenCreQty IS NULL
							  THEN '0'
							  ELSE b.OpenCreQty
							  END 'B1UnservedQty',

							  (b.Quantity - b.OpenCreQty) AS Served,

							  b.U_PONo AS TransPO,

							  CASE 
							  WHEN b.LineNum IS NULL
							  THEN '' 
							  ELSE b.LineNum
							  END 'BaseLine',

							  CASE
							  WHEN a.DocEntry IS NULL
							  THEN '' 
							  ELSE a.DocEntry
							  END 'BaseEntry',

							  CASE
							  WHEN b.ObjType IS NULL
							  THEN ''
							  ELSE b.ObjType
							  END 'BaseType',

							  d.SlpCode,

							  e.WhsName AS Source,
							  e.U_Whse_Type,

							  f.SlpName AS AE,

							  g.Code AS TruckCode,
							  g.Name AS TruckCo,

							  a.U_DoNo
							  
							");

			$db3->from('ORDR a');
			$db3->join('RDR1 b', 'b.DocEntry = a.DocEntry', 'INNER');
			$db3->join('OITM c', 'c.ItemCode = b.ItemCode', 'INNER');
			$db3->join('OCRD d', 'd.CardCode = a.CardCode','INNER');
			$db3->join('OWHS e', 'e.WhsCode = b.WhsCode', 'INNER');
			$db3->join('OSLP f', 'f.SlpCode = d.SlpCode', 'LEFT');
			$db3->join('dbo.[@TRUCKERS] g', 'g.Code = a.U_Trucker', 'LEFT');
				
			$qry3 = $db3->get();

			if ($qry3->num_rows() > 0) {

					foreach ($qry3->result_array() as $r3) {

						$qry4 = $this->db->where('do_no', $r3['DONo'])
								->get('sap_do_data');

						if ($qry4->num_rows() == 0) {

							$data = array(
								'doc_entry'=>$r3['DocEntry'],
								'obj_type'=>$r3['ObjType'],
								'line_num'=>$r3['LineNum'],
								'do_date'=>$r3['Dodate'],
								'sap_exp_del_date'=>$r3['SAPExpDeldate'],
								'date_gap'=>$r3['DateGap'],
								'do_no'=>$r3['DONo'],
								'series'=>$r3['Series'],
								'def_doc_type'=>$r3['DefDocType'],
								'transmit_date'=>$r3['TransmitDate'],
								'do_transmit_date'=>$r3['U_DOTransDate'],
								'do_stat'=>$r3['DocStatus'],
								'card_code'=>$r3['CardCode'],
								'card_name'=>$r3['CardName'],
								'sdr_no'=>$r3['PepsiSDRNo'],
								'location'=>$r3['Location'],
								'mother_po'=>$r3['MotherPO'],
								'truck_code'=>$r3['TruckCode'],
								'truck_company'=>$r3['TruckCo'],
								'whse_code'=>$r3['WhsCode'],
								'source'=>$r3['Source'],
								'whse_type'=>$r3['U_Whse_Type'],
								'item_code'=>$r3['ItemCode'],
								'item_desc'=>$r3['Dscription'],
								'uom'=>$r3['UnitMsr'],
								'do_qty'=>$r3['DoQty'],
								'b1_udo_qty'=>$r3['B1UnservedQty'],
								'served_qty'=>$r3['Served'],
								'trans_po'=>$r3['TransPO'],
								'base_line'=>$r3['BaseLine'],
								'base_entry'=>$r3['BaseEntry'],
								'base_type'=>$r3['BaseType'],
								'ae_code'=>$r3['SlpCode'],
								'ae'=>$r3['AE'],
								'do_remarks'=>$r3['DoRemarks'],
								'update_date'=>$r3['UpdateDate'],
								'sa_dockey'=>$r3['U_SAdockey'],
								'sa_docno'=>$r3['U_SADocNo'],
								'sa_docline'=>$r3['U_SADocLine1'],
								'sa_transno'=>$r3['U_SATransNo'],
								'sa_transtype'=>$r3['U_SATransTyp'],
								'dbtype'=>'1',
								'u_do'=>$r3['U_DoNo']
							);

							$this->db->insert('sap_do_data', $data);
						}

					}

					return TRUE;

			}

	}


	function get_DO_from_SAP(){

		$ims_whsecode = $this->uri->segment(3);
		$ims_whsename = "";
		$whsename = "";

		// $qry_whse = $this->db->where('WhsCode', $ims_whsecode)
		// 				     ->get('whse_integration_ims');

		// if($qry_whse->num_rows() > 0){
		// 	foreach($qry_whse->result_array() as $qrec){
		// 		$ims_whsename = $qrec['SAP_WhsCode'];
		// 		$whsename = $qrec['WhsName'];
		// 	}
		// }else{
		// 	$qry_whse2 = $this->db->where('wh_code', $ims_whsecode)
		// 				     ->get('mwhr');

		// 	if($qry_whse2->num_rows() > 0){
		// 		foreach($qry_whse2->result_array() as $qrec2){
		// 			$ims_whsename = $qrec2['wh_sapcode'];
		// 			$whsename = $qrec2['wh_name'];
		// 		}
		// 	}
		// }

		$qry_whse2 = $this->db->query("SELECT * FROM mwhr WHERE wh_code = $ims_whsecode ");

		// $qry_whse3 = $this->db->query("SELECT * FROM whse_integration_ims WHERE WhsCode = '$ims_whsecode' ");

		// if ($qry_whse3->num_rows() > 0) {
		// 	foreach($qry_whse3->result_array() as $qrec3){
		// 		$whsename = $qrec3['SAP_WhsName'];
		// 	}
		// } else {
		// 	$qry_whse4 = $this->db->query("SELECT * FROM mwhr WHERE wh_code = $ims_whsecode ");

		// 	foreach($qry_whse4->result_array() as $qrec4){
		// 		$whsename = $qrec4['wh_name'];
		// 	}

		// }

		if($qry_whse2->num_rows() > 0){
			foreach($qry_whse2->result_array() as $qrec2){
				$ims_whsename = $qrec2['wh_sapcode'];
				$whsename = $qrec2['wh_name'];
			}
		}

		$donum = $this->input->post('ref');

		$db3 = $this->load->database('db3',TRUE);
		$db3->where('a.U_DoNo', (int)$donum);
		$db3->where('a.DocStatus', 'O');
		$db3->where('a.DocType', 'I');
		$db3->where('a.Printed', 'Y');
		$db3->where('c.U_Commodity', '02');
		$db3->where('b.WhsCode', $ims_whsename);
		$db3->where('a.DocDate > ', '2016-10-05');

		$db3->select("CAST(a.DocDate AS DATE) AS Dodate,
							CAST(a.DocDueDate AS DATE) AS SAPExpDeldate,
							DATEDIFF(dd, a.DocDate, A.DocDueDate) AS DateGap,
							a.DocNum AS DONo,
							a.Series,
							a.U_DRNo AS DefDocType, 
							CAST(a.U_TransDate AS DATE) AS TransmitDate,
							CAST(a.U_DOTransDate AS DATE) AS U_DOTransDate,
							a.DocStatus,
							a.CardCode,
						 	a.CardName,
							a.NumatCard AS PepsiSDRNo,
							a.Address2 AS Location,
							a.U_PONo AS MotherPO,
							a.Comments AS DoRemarks,
							CAST(a.UpdateDate AS DATE) AS UpdateDate,
							a.DocEntry,
							a.ObjType,
							  
							b.LineNum,
							b.WhsCode,
						 	b.U_SAdockey,
							b.U_SADocNo,
						 	b.U_SADocLine1,
							b.U_SATransNo,
							b.U_SATransTyp,
							b.ItemCode,
							b.Dscription,
							b.UnitMsr,
							b.Quantity AS DoQty,
							b.OpenCreQty AS B1UnservedQty,
							(b.Quantity - b.OpenCreQty) AS Served,
							b.U_PONo AS TransPO,
							b.BaseLine AS BaseLine,
							b.BaseEntry AS BaseEntry,
							b.BaseType,

							d.SlpCode,

							'$whsename' AS Source,
							e.U_Whse_Type,

							f.SlpName AS AE,

							a.U_Trucker AS TruckCode,
							g.Name AS TruckCo,
							
							b.SubCatNum AS CatalogNo
							  
						");

		$db3->from('ORDR a');
		$db3->join('RDR1 b', 'b.DocEntry = a.DocEntry', 'INNER');
		$db3->join('OITM c', 'c.ItemCode = b.ItemCode', 'INNER');
		$db3->join('OCRD d', 'd.CardCode = a.CardCode','INNER');
		$db3->join('OWHS e', 'e.WhsCode = b.WhsCode', 'INNER');
		$db3->join('OSLP f', 'f.SlpCode = d.SlpCode', 'LEFT');
		$db3->join('dbo.[@TRUCKERS] g', 'g.Code = a.U_Trucker', 'LEFT');
				
		$qry4 = $db3->get();

		if($qry4->num_rows() > 0){
			return $qry4->result();
		}
		
	}


	function check_if_DO_exists_from_WHIR(){

		$ims_whsecode = $this->uri->segment(3);
		$ims_whsename = "";
		$whsename = "";

		$qry_whse = $this->db->where('WhsCode', $ims_whsecode)
						     ->get('whse_integration_ims');

		if($qry_whse->num_rows() > 0){
			foreach($qry_whse->result_array() as $qrec){
				$ims_whsename = $qrec['SAP_WhsName'];
				$whsename = $qrec['WhsName'];
			}
		}else{
			$qry_whse2 = $this->db->where('wh_code', $ims_whsecode)
						     ->get('mwhr');

			if($qry_whse2->num_rows() > 0){
				foreach($qry_whse2->result_array() as $qrec2){
					$ims_whsename = $qrec2['wh_name'];
					$whsename = $qrec2['wh_name'];
				}
			}
		}


		$qry2 = $this->db->where('wi_reftype', 'DO')
						 ->where('wi_refnum', $this->input->post('ref'))
						 ->where('wh_name', $ims_whsename)
						 ->get('whir');

		if($qry2->num_rows() > 0){
			return TRUE;
		}
		

	}

	function check_DO_qty_from_whir(){

		$total_whir_out = 0;
		$total_whir_out_pending = 0;

		$ims_whsecode = $this->uri->segment(3);
		$ims_whsename = "";
		$whsename = "";

		$qry_whse = $this->db->where('WhsCode', $ims_whsecode)
						     ->get('whse_integration_ims');

		if($qry_whse->num_rows() > 0){
			foreach($qry_whse->result_array() as $qrec){
				$ims_whsename = $qrec['SAP_WhsName'];
				$whsename = $qrec['WhsName'];
			}
		}else{
			$qry_whse2 = $this->db->where('wh_code', $ims_whsecode)
						     ->get('mwhr');

			if($qry_whse2->num_rows() > 0){
				foreach($qry_whse2->result_array() as $qrec2){
					$ims_whsename = $qrec2['wh_name'];
					$whsename = $qrec2['wh_name'];
				}
			}
		}

		// GET THE TOTAL ITEM QTY THAT HAS BEEN OUT
		$qry2 = $this->db->where('wi_reftype', 'DO')
			             ->where('wi_refnum', $this->input->post('ref'))
			             ->where('wh_name', $ims_whsename)
			             ->where('wi_type', 1)
			             ->get('whir');

		foreach($qry2->result_array() as $r2){
			$total_whir_out += (int)$r2['wi_itemqty'];
		}

		// GET THE TOTAL ITEM QTY THAT IS PENDING FOR OUT
		$qry3 = $this->db->where('wi_reftype', 'DO')
			             ->where('wi_refnum', $this->input->post('ref'))
			             ->where('wh_name', $ims_whsename)
			             ->where('wi_type', 2)
			             ->get('whir');

		foreach($qry3->result_array() as $r3){
			$total_whir_out_pending += (int)$r3['wi_itemqty'];
		}

		$total_pending = (int)$total_whir_out + (int)$total_whir_out_pending;

		$qry4 = $this->db->where('do_no', $this->input->post('ref'))
						 ->get('sap_do_data');

		foreach($qry4->result_array() as $r4){
			if($total_whir_out == $r4['do_qty']){
				// $data = "DO QTY HAS BEEN ALL SERVED";
				return 1;
			}elseif($total_pending == $r4['do_qty']){
				// $data = "THERE'S A PENDING DOCUMENT TO BE APPROVED OR OUT";
				return 2;
			}else{

				if($total_pending > $r4['do_qty']){
					$do_qty_remain = (int)$total_pending - (int)$r4['do_qty'];
				}else{
					$do_qty_remain = (int)$r4['do_qty'] - (int)$total_pending;
				}

				$data['qty_remain'] = $do_qty_remain;

				return $data;

			}
		}

	}


	function get_sap_do_records(){

		
		$do_no = $this->input->post('ref');
		$wh_no = $this->uri->segment(3);
		$q3 = $this->db->query("SELECT * FROM whir WHERE wi_reftype = 'DO' AND wi_refnum = '$do_no' AND wi_intransit = 0 ");
		$q4 = $this->db->query("SELECT * FROM whir WHERE wi_reftype2 = 'DO' AND wi_refnum2 = '$do_no' AND wi_intransit = 0 ");

		if($q3->num_rows() == FALSE AND $q4->num_rows() == FALSE){
			$this->db->where('wh_code', $wh_no);
			$q1 = $this->db->get('mwhr');

			if($q1->num_rows()==1){
				foreach($q1->result() as $r){
					$wh_sap_code = $r->wh_sapcode;

					$q2 = $this->db->query("SELECT *, b.truck_seal FROM sap_do_data a
											LEFT JOIN ocrd b ON b.CardCode = a.card_code
											WHERE whse_code = '$wh_sap_code' AND do_no = '$do_no' ");

					if($q2->num_rows() > 0){
						return $q2->result();
					}

				}
			}

		}

	}

	function tag_sap_data(){

		$this->db->where('wi_reftype', $this->input->post('doctype1'));
		$this->db->where('wi_reftype2', $this->input->post('doctype2'));
		$this->db->where('wi_refnum', $this->input->post('ref'));
		$this->db->where('wi_refnum2', $this->input->post('ref2'));

		$data = array('from_sap'=> 1);

		$this->db->update('whir', $data);

	}

	function empty_sap_do_data(){
		$this->db->truncate('sap_do_data');
	}

	function check_intransit_sap(){

		$dtype = $this->input->post('doctype1');
		$ref = $this->input->post('ref');
		$dtype2 = $this->input->post('doctype2');
		$ref2 = $this->input->post('ref2');

		$qry = $this->db->query("SELECT wi_intransit FROM whir t1
								WHERE wi_intransit <> 0.00
								AND t1.wi_reftype = '$dtype'
								AND t1.wi_refnum = '$ref'
								OR t1.wi_reftype2 = '$dtype'
								OR t1.wi_refnum2 = '$ref' 
								AND t1.deldate = (SELECT MAX(deldate) FROM whir)");
		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function edit_mm(){

		$id = $this->uri->segment(3);

		$this->db->where('wi_id', $id);
		$qry = $this->db->get('whir');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}


	function edit_mm_list(){

		$temp = explode("_", $this->uri->segment(3));
		$id = $temp[1];

		$this->db->where('wi_transno', $id);
		$qry = $this->db->get('whir');
		// $qry = $this->db->query("SELECT * FROM whir WHERE wi_mmtnum = '$id' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function count_mm_rows(){

		$temp = explode("_", $this->uri->segment(3));
		$id = $temp[1];

		$qry = $this->db->query("SELECT COUNT(*) AS ctr FROM whir WHERE wi_transno = '$id' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}


	function mm_update(){

		$ctr = $this->input->post('ctr');
		$ctr = intval($ctr);
		$tnum = $this->input->post('tnum');

		for($x=1; $x<=$ctr; $x++){

			$inum = $this->input->post('icodeo_'.$x);

			$data = array(

				'wi_mmprocess'=>$this->input->post('process'),
				'wi_refname'=>$this->input->post('cusname'),
				'wi_reftype'=>$this->input->post('doctype1'),
				'wi_refnum'=>$this->input->post('ref'),
				'wi_reftype2'=>$this->input->post('doctype2'),
				'wi_refnum2'=>$this->input->post('ref2'),
				'wi_remarks'=>$this->input->post('remarks'),
				'wh_name'=>$this->input->post('loc'.$x),
				'item_id'=>$this->input->post('icode_'.$x),
				'item_uom'=>$this->input->post('iuom_'.$x),
				'wi_itemqty'=>$this->input->post('qty'.$x)

			);

			$this->db->where('wi_transno', $tnum);
			$this->db->where('item_id', $inum);
			$this->db->update('whir', $data);

		}

	} 

	function trans_rec($limit, $offset, $sort_by, $sort_order){

		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';

		$qry = $this->db->select('wi_id, wi_reftype, wi_refnum, wi_reftype2, wi_refnum2')
			   ->from('whir')
			   ->limit($limit, $offset)
			   ->order_by($sort_by, $sort_order);

		$ret['rcd'] = $qry->get()->result();

		$qry = $this->db->select('COUNT(*) AS count', FALSE)
			   ->from('whir');

		$tmp = $qry->get()->result();

		$ret['num_results'] = $tmp[0]->count; 

		return $ret;

	}


	function total_in_trans(){
		$q=$this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty

			FROM whir a 
			LEFT OUTER JOIN ousr b ON a.wi_createby=b.memb__id
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN (SELECT CardName,CardCode,Status FROM ocrd WHERE Status=1)d ON a.wi_refname=d.CardCode 
			WHERE a.wh_name='".$this->input->post('whlist')."' 
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_type=0
				AND a.wi_approvestatus=1
			ORDER BY a.wh_name,a.wi_status"
			);
		if($q->num_rows==true){
			return $q->result();
		}
	}

	function total_in_trans_sort(){
		$q=$this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty

			FROM whir a 
			LEFT OUTER JOIN ousr b ON a.wi_createby=b.memb__id
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN (SELECT CardName,CardCode,Status FROM ocrd WHERE Status=1)d ON a.wi_refname=d.CardCode 
			WHERE a.wh_name='".$this->input->post('whlist')."' 
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_type=0
				AND a.wi_approvestatus=1
				AND a.deldate
					BETWEEN '".$this->input->post('sdate')."'
					AND '".$this->input->post('edate')."'
			ORDER BY a.wh_name,a.wi_status"
			);
		if($q->num_rows==true){
			return $q->result();
		}
	}

	function total_out_trans(){
		$q=$this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty

			FROM whir a 
			LEFT OUTER JOIN ousr b ON a.wi_createby=b.memb__id
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN (SELECT CardName,CardCode,Status FROM ocrd WHERE Status=1)d ON a.wi_refname=d.CardCode 
			WHERE a.wh_name='".$this->input->post('whlist')."'
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_type=1
				AND a.wi_approvestatus=1
			ORDER BY a.wh_name,a.wi_status"
			);
		if($q->num_rows==true){
			return $q->result();
		}
	}

	function total_out_trans_sort(){
		$q=$this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty

			FROM whir a 
			LEFT OUTER JOIN ousr b ON a.wi_createby=b.memb__id
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN (SELECT CardName,CardCode,Status FROM ocrd WHERE Status=1)d ON a.wi_refname=d.CardCode 
			WHERE a.wh_name='".$this->input->post('whlist')."'
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_type=1
				AND a.wi_approvestatus=1
				AND a.deldate
					BETWEEN '".$this->input->post('sdate')."'
					AND '".$this->input->post('edate')."'
			ORDER BY a.wh_name,a.wi_status"
			);
		if($q->num_rows==true){
			return $q->result();
		}
	}

	function total_trans(){
		$q=$this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty

			FROM whir a 
			LEFT OUTER JOIN ousr b ON a.wi_createby=b.memb__id
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN (SELECT CardName,CardCode,Status FROM ocrd WHERE Status=1)d ON a.wi_refname=d.CardCode 
			WHERE a.wh_name='".$this->input->post('whlist')."' 
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_type IN (1,0)
				AND a.wi_approvestatus=1
			ORDER BY a.wh_name,a.wi_status"
			);
		if($q->num_rows==true){
			return $q->result();
		}
	}

	function total_trans_sort(){
		$q=$this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty

			FROM whir a 
			LEFT OUTER JOIN ousr b ON a.wi_createby=b.memb__id
			LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
			LEFT OUTER JOIN (SELECT CardName,CardCode,Status FROM ocrd WHERE Status=1)d ON a.wi_refname=d.CardCode 
			WHERE a.wh_name='".$this->input->post('whlist')."' 
				AND a.wi_refnum LIKE '%".$this->input->post('ref')."%'
				AND a.wi_refname LIKE '%".$this->input->post('bpname')."%'
				AND a.wi_type IN (1,0)
				AND a.wi_approvestatus=1
				AND a.deldate 
					BETWEEN '".$this->input->post('sdate')."'
					AND '".$this->input->post('edate')."'
			ORDER BY a.wh_name,a.wi_status"
			);
		if($q->num_rows==true){
			return $q->result();
		}
	}

	function total_dr(){
		
		$qry = $this->db->query("SELECT IFNULL(SUM(b.wi_itemqty), 0) AS wi_itemqty, IFNULL(SUM(b.wi_doqty), 0) AS wi_doqty 
								 FROM dspr_dr a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function total_dr_sort(){
		
		$qry = $this->db->query("SELECT IFNULL(SUM(b.wi_itemqty), 0) AS wi_itemqty, IFNULL(SUM(b.wi_doqty), 0) AS wi_doqty 
								 FROM dspr_dr a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."' 
									AND b.deldate
										BETWEEN '".$this->input->post('sdate')."' 
										AND '".$this->input->post('edate')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function total_rr(){

		$qry = $this->db->query("SELECT IFNULL(SUM(b.wi_itemqty), 0)  AS wi_itemqty FROM dspr_rr a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function total_rr_sort(){

		$qry = $this->db->query("SELECT IFNULL(SUM(b.wi_itemqty), 0)  AS wi_itemqty FROM dspr_rr a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."'
									AND b.deldate
										BETWEEN '".$this->input->post('sdate')."'
										AND '".$this->input->post('edate')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function total_wis(){
		
		$qry = $this->db->query("SELECT IFNULL(SUM(b.wi_itemqty), 0) AS wi_itemqty FROM dspr_wis a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function total_wis_sort(){
		
		$qry = $this->db->query("SELECT IFNULL(SUM(b.wi_itemqty), 0) AS wi_itemqty FROM dspr_wis a 
									INNER JOIN whir b ON b.wi_id = a.wi_id
									INNER JOIN mwhr c ON c.wh_name = a.wh_name
									INNER JOIN ocmt d ON d.comm__id = a.item_id
									INNER JOIN ocrd e ON e.CardCode = b.wi_refname
								WHERE
									a.wh_name = '".$this->input->post('whouse')."' 
									AND b.deldate
										BETWEEN '".$this->input->post('sdate')."'
										AND '".$this->input->post('edate')."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function total_sor(){

		$whse = $this->input->post('whouse');

		$qry = $this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty FROM whir a
								LEFT JOIN ocmt b
									ON a.item_id = b.comm__id
								LEFT JOIN ocrd c
									ON a.wi_refname = c.CardCode
								LEFT JOIN mwhr d
									ON a.wh_name = d.wh_name	
								WHERE a.wi_deltype  IN ('Delivery In', 'Material Management')
									AND a.wi_type = 0
									AND a.wi_approvestatus = 1 
									AND a.wh_name = '$whse'
									AND a.wi_status = 1 
								ORDER BY a.deldate DESC ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function total_sor_sort(){

		$whse = $this->input->post('whouse');
		$pstart = $this->input->post('posting_date_start');
		$pend = $this->input->post('posting_date_end');

		$qry = $this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty FROM whir a
								LEFT JOIN ocmt b
									ON a.item_id = b.comm__id
								LEFT JOIN ocrd c
									ON a.wi_refname = c.CardCode
								LEFT JOIN mwhr d
									ON a.wh_name = d.wh_name	
								WHERE a.wi_deltype  IN ('Delivery In', 'Material Management')
									AND a.wi_type = 0
									AND a.wi_approvestatus = 1 
									AND a.wh_name = '$whse'
									AND a.wi_status = 1 
									AND a.deldate 
										BETWEEN '$pstart' 
										AND '$pend' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function total_soi(){

		$whse = $this->input->post('whouse');

		$qry = $this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty, IFNULL(SUM(a.wi_doqty), 0) AS wi_doqty FROM whir a
								LEFT JOIN ocmt b
									ON a.item_id = b.comm__id
								LEFT JOIN ocrd c
									ON a.wi_refname = c.CardCode
								LEFT JOIN mwhr d
									ON a.wh_name = d.wh_name	
								WHERE a.wi_deltype  IN ('Delivery Out', 'Material Management')
									AND a.wi_type = 1
									AND a.wi_approvestatus = 1 
									AND a.wh_name = '$whse'
									AND a.wi_status = 1 
								ORDER BY a.deldate DESC ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function total_soi_sort(){

		$whse = $this->input->post('whouse');
		$pstart = $this->input->post('posting_date_start');
		$pend = $this->input->post('posting_date_end');

		$qry = $this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty, IFNULL(SUM(a.wi_doqty), 0) AS wi_doqty FROM whir a
								LEFT JOIN ocmt b
									ON a.item_id = b.comm__id
								LEFT JOIN ocrd c
									ON a.wi_refname = c.CardCode
								LEFT JOIN mwhr d
									ON a.wh_name = d.wh_name	
								WHERE a.wi_deltype  IN ('Delivery Out', 'Material Management')
									AND a.wi_type = 1
									AND a.wi_approvestatus = 1 
									AND a.wh_name = '$whse'
									AND a.wi_status = 1 
									AND a.deldate 
										BETWEEN '$pstart' 
										AND '$pend' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function total_unl(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty, 
		IFNULL(SUM(a.wi_doqty), 0) AS wi_doqty
		FROM whir a
		LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
		LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
		LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
		WHERE wi_type=1
			AND wi_doqty <> 0
			AND udo_active <> 1 
			AND wi_status=1 
			AND a.wh_name='".$this->input->post('whouse')."'
		ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function total_unl_sort(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT IFNULL(SUM(a.wi_itemqty), 0) AS wi_itemqty,
			IFNULL(SUM(a.wi_doqty), 0) AS wi_doqty
		FROM whir a
		LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
		LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
		LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
		WHERE wi_type=1
			AND wi_doqty <> 0 
			AND wi_status=1
			AND udo_active <> 1
			AND a.wh_name='".$this->input->post('whouse')."' 
			AND a.deldate
				BETWEEN '".$this->input->post('sdate')."'
				AND '".$this->input->post('edate')."'
		ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function trans_series(){

		$wh_code = $this->input->post('wh');

		$qry = $this->db->select('*')
					->from('sndr a')
					->join('mwhr b', 'b.wh_code = a.whse_code', 'LEFT')
					->where('a.whse_code', $wh_code)
		            ->get();

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function check_fno_trans_series(){

		$temp = $this->input->post('fno');
		$qry = $this->db->query("SELECT * FROM sndr WHERE '$temp' BETWEEN sn_firstnum AND sn_lastnum AND sn_status = 1");

		if($qry->num_rows() > 0){
			return true;
		}
	}

	function check_lno_trans_series(){
		$temp = $this->input->post('lno');
		$qry = $this->db->query("SELECT * FROM sndr WHERE '$temp' BETWEEN sn_firstnum AND sn_lastnum AND sn_status = 1");

		if($qry->num_rows() > 0){
			return true;
		}
	}

	function add_trans_series(){

		$qry = $this->db->where('dtype_code', $this->input->post('trans_type'))
					->get('dtype');

		if($qry->num_rows() > 0){
			foreach($qry->result_array() as $r){
				$data = array(
					'whse_code'=>$this->uri->segment(3),
					'sn_code_1'=>$this->input->post('trans_type'),
					'sn_code'=>$r['dtype_short_code'],
					'sn_name'=>$r['dtype_desc'],
					'sn_firstnum'=>$this->input->post('fno'),
					'sn_lastnum'=>$this->input->post('lno'),
					'sn_nextnum'=>$this->input->post('fno'),
					'sn_status'=>1,
					'validity_date'=>$this->input->post('vdate')
				);

				$this->db->insert('sndr', $data);
			}
		}

	}

	function trans_series_edit_rec(){

		$temp = explode('-', $this->uri->segment(3));
		$wh_code = $temp[0];
		$dtcode = $temp[1];

		$qry = $this->db->query("SELECT *, b.wh_name FROM sndr a
									LEFT JOIN mwhr b ON b.wh_code = a.whse_code
									WHERE a.whse_code = '$wh_code' AND a.sn_code_1 = '$dtcode' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function update_trans_series(){

		$temp = explode('-', $this->uri->segment(3));
		$wh_code = $temp[0];
		$dtcode = $temp[1];
	
		$data = array(
			'sn_firstnum'=>$this->input->post('fno'),
			'sn_lastnum'=>$this->input->post('lno'),
			'sn_nextnum'=>$this->input->post('nno'),
			'sn_status'=>$this->input->post('stats'),
			'validity_date'=>$this->input->post('vdate')
		);

		$this->db->where('whse_code', $wh_code)
			 ->where('sn_code_1', $dtcode)
		     ->update('sndr', $data);

	}

	function whlist_trans(){
		$q=$this->db->query("SELECT a.wh_code,
				a.wh_name,
				a.wh_status  FROM mwhr a 
			LEFT JOIN ouaw b ON a.wh_name = b.accessname
			where a.wh_status=1 and b.usercode='".$this->session->userdata('usr_uname')."' and b.status=1 
			ORDER BY a.wh_name 
		");
		if ($q->num_rows==true){
			foreach($q->result_array() as $r){
				$data[$r['wh_code']]=$r['wh_name'];
			}
			return $data;
		}
	}

	function trans_type_list(){

		$qry = $this->db->get('dtype');

		foreach($qry->result_array() as $r){
			$data[$r['dtype_code']] = $r['dtype_desc'];
		}

		return $data;

	}

	function get_whse_name(){
		$qry = $this->db->where('wh_code', $this->uri->segment(3))
					->get('mwhr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function check_if_transcode_wcode_exists(){
		$qry = $this->db->where('whse_code', $this->uri->segment(3))
					->where('sn_code_1', $this->input->post('trans_type'))
					->where('sn_status', 1)
					->get('sndr');
		if($qry->num_rows()  > 0){
			return TRUE;
		}
	}

	function check_if_nextno_used_in_trans(){
		$qry =$this->db->where('wi_transno', $this->input->post('nno'))
				   ->get('whir');

		if($qry->num_rows() > 0){
			return TRUE;
		}
	}

	function check_fno_trans_series_edit(){

		$temp = $this->input->post('fno');
		$temp2 = explode('-', $this->uri->segment(3));
		$wh_code = $temp2[0];

		$qry = $this->db->query("SELECT * FROM sndr WHERE whse_code <> '$wh_code' AND '$temp' BETWEEN sn_firstnum AND sn_lastnum AND sn_status = 1");

		if($qry->num_rows() > 0){
			return true;
		}
	}

	function check_lno_trans_series_edit(){
		$temp = $this->input->post('lno');
		$temp2 = explode('-', $this->uri->segment(3));
		$wh_code = $temp2[0];

		$qry = $this->db->query("SELECT * FROM sndr WHERE whse_code <> '$wh_code' AND '$temp' BETWEEN sn_firstnum AND sn_lastnum AND sn_status = 1");

		if($qry->num_rows() > 0){
			return true;
		}
	}

	function check_validity_date_din(){

		date_default_timezone_set("Asia/Manila");
		$datetoday = date('Y-m-d');

		$qry = $this->db->where('whse_code', $this->uri->segment(3))
					->where('validity_date', $datetoday)
					->where('sn_code', 'DI')
					->get('sndr');

		if($qry->num_rows() > 0){
			return TRUE;
		}
	}

	function check_validity_date_dout(){

		date_default_timezone_set("Asia/Manila");
		$datetoday = date('Y-m-d');

		$qry = $this->db->where('whse_code', $this->uri->segment(3))
					->where('validity_date', $datetoday)
					->where('sn_code', 'DO')
					->get('sndr');

		if($qry->num_rows() > 0){
			return TRUE;
		}
	}

	function check_validity_date_dout_ho(){

		date_default_timezone_set("Asia/Manila");
		$datetoday = date('Y-m-d');

		$qry = $this->db->where('whse_code', $this->uri->segment(3))
					->where('validity_date', $datetoday)
					->where('sn_code', 'DO HO')
					->get('sndr');

		if($qry->num_rows() > 0){
			return TRUE;
		}
	}

	function check_validity_date_tout(){

		date_default_timezone_set("Asia/Manila");
		$datetoday = date('Y-m-d');

		$qry = $this->db->where('whse_code', $this->uri->segment(3))
					->where('validity_date', $datetoday)
					->where('sn_code', 'TOUT')
					->get('sndr');

		if($qry->num_rows() > 0){
			return TRUE;
		}
	}

	function check_validity_date_tin(){

		date_default_timezone_set("Asia/Manila");
		$datetoday = date('Y-m-d');

		$qry = $this->db->where('whse_code', $this->uri->segment(3))
					->where('validity_date', $datetoday)
					->where('sn_code', 'TIN')
					->get('sndr');

		if($qry->num_rows() > 0){
			return TRUE;
		}
	}

	function check_validity_date_mm(){

		date_default_timezone_set("Asia/Manila");
		$datetoday = date('Y-m-d');

		$qry = $this->db->where('whse_code', $this->uri->segment(3))
					->where('validity_date', $datetoday)
					->where('sn_code', 'MM')
					->get('sndr');

		if($qry->num_rows() > 0){
			return TRUE;
		}
	}

	function trans_no_din(){

		$qry = $this->db->select('sn_nextnum')
					->where('whse_code', $this->uri->segment(3))
					->where('sn_code', 'DI')
		            ->get_where('sndr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function trans_no_dout(){

		$qry = $this->db->select('sn_nextnum')
					->where('whse_code', $this->uri->segment(3))
					->where('sn_code', 'DO')
		            ->get_where('sndr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function trans_no_dout_ho(){

		$qry = $this->db->select('sn_nextnum')
					->where('whse_code', $this->uri->segment(3))
					->where('sn_code', 'DO HO')
		            ->get_where('sndr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function trans_no_tito(){

		$qry = $this->db->select('sn_nextnum')
					->where('whse_code', $this->uri->segment(3))
					->where('sn_code', 'TITO')
		            ->get_where('sndr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function trans_no_tin(){

		$qry = $this->db->select('sn_nextnum')
					->where('whse_code', $this->uri->segment(3))
					->where('sn_code', 'TIN')
		            ->get_where('sndr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function trans_no_tout(){

		$qry = $this->db->select('sn_nextnum')
					->where('whse_code', $this->uri->segment(3))
					->where('sn_code', 'TOUT')
		            ->get_where('sndr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function trans_no_mm(){

		$qry = $this->db->select('sn_nextnum')
					->where('whse_code', $this->uri->segment(3))
					->where('sn_code', 'MM')
		            ->get_where('sndr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}
	

	function opening_bal_list(){

		$wname = $this->input->post('wh');

		$cutoff_date = $this->input->post('cutoff_date');

		$q = $this->db->query("SELECT a.comm__id, a.comm__code2, b.item_uom, a.comm__name,b.sqty, c.tqty ,d.rqty
			FROM ocmt a
			LEFT OUTER JOIN (SELECT whir.item_id, whir.item_uom, mwhr.wh_name,sum(whir.wi_itemqty) as sqty 
				FROM whir INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=0 
					AND whir.wi_status=1 
					AND whir.wi_approvestatus=1
					AND whir.wh_name = '$wname'
					AND deldate <= '$cutoff_date'
				GROUP BY item_id)b 
			ON a.comm__id = b.item_id
			LEFT OUTER JOIN(SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as tqty 
				FROM whir INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=1 
					AND whir.wi_status=1
					AND whir.wh_name = '$wname'
					AND deldate <= '$cutoff_date' 
				GROUP BY item_id)c 
			ON a.comm__id = c.item_id
			LEFT OUTER JOIN(
				SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as rqty 
				FROM whir 
				INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=2 
					AND whir.wi_status=1 
					AND wi_status=1
					AND whir.wh_name = '$wname'
					AND deldate <= '$cutoff_date' 
				GROUP BY item_id
			)d 
			ON a.comm__id = d.item_id");
		if ($q->num_rows() == true){
			return $q->result();
		}
	}

	function total_items(){
		$qry = $this->db->query('SELECT COUNT(*) AS ctr FROM ocmt');

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function begbal_in_wvar($icode2, $cbal2, $count2, $var2, $wname2, $wname_var2, $unit2, $codate2){
		
		date_default_timezone_set("Asia/Manila");
		$datetime = date('Y-m-d H:m:s');

		// $qry = $this->db->query("SELECT CardCode, CardName FROM ocrd WHERE CardName = '$wname2' ");

		$qry = $this->db->query("SELECT wh_code, wh_name FROM mwhr WHERE wh_name = '$wname2' ");

		// GET THE LAST REFNUM OF ADJ FOR DELIVERY IN
		$q2 = $this->db->query("SELECT MAX(wi_refnum) AS bbnum FROM whir WHERE wi_reftype = 'ADJ' AND wi_type = 0 AND wi_subtype = 'DI_06' GROUP BY wi_id ");

		$bbnum_di = "1000000";

		// SERIES FOR DELIVERY IN
		foreach($q2->result_array() as $rs){
			$bbnum_di = $rs['bbnum'];

			if($bbnum_di == ""){
				$bbnum_di = "1000000";
			}elseif(empty($bbnum_di)){
				$bbnum_di = "1000000";
			}elseif(!is_numeric($bbnum_di)){
				$bbnum_di = "1000000";
			}else{
				(int)$bbnum_di;
				$bbnum_di+=1;
			}

		}

		foreach($qry->result_array() as $r){

			// ARRAY DATA FOR IN TO ORIGINAL LOCATION
			$dd2 = array(
				'wi_type'=>0,
				'wi_deltype'=>'Delivery In',
				'wi_subtype'=>'DI_06',
				'wh_name'=>$wname2,
				'item_id'=>$icode2,
				'item_uom'=>$unit2,
				'deldate'=>$codate2,
				'wi_itemqty'=>$count2,
				'wi_refname'=>$r['wh_code'],
				'wi_status'=>1,
				'wi_approvestatus'=>1,
				'wi_createby'=>$this->session->userdata('usr_uname'),
				'wi_reftype'=>'ADJ',
				'wi_remarks'=>'Beginning Balance',
				'wi_refnum'=>$bbnum_di,
				'wi_dtcode'=>'DT_01'
			);

			$this->db->insert('whir', $dd2);

			$qry4 = $this->db->query("SELECT MAX(wi_id) AS wi_id FROM whir");

			foreach($qry4->result_array() as $rid){

				// ARRAY DATA FOR DSPR DIN
				$dd6 = array(
					'wi_id'=>$rid['wi_id'],
					'item_id'=>$icode2,
					'wh_name'=>$wname2,
					'deltype'=>'Delivery In',
					'itemqty'=>$count2,
					'trans_datetime'=>$codate2,
					'posting_date'=>$codate2,
					'reftype'=>'ADJ',
					'refnum'=>$bbnum_di
				);

				$this->db->insert('dspr_din', $dd6);

			}

		}

	}

	function begbal_in_nvar($icode2, $cbal2, $count2, $wname2, $unit2, $codate2){

		date_default_timezone_set("Asia/Manila");
		$datetime = date('Y-m-d H:m:s');

		// $qry = $this->db->query("SELECT CardCode, CardName FROM ocrd WHERE CardName = '$wname2' ");

		$qry = $this->db->query("SELECT wh_code, wh_name FROM mwhr WHERE wh_name = '$wname2' ");

		// GET THE LAST REFNUM OF ADJ FOR DELIVERY OUT
		$q1 = $this->db->query("SELECT MAX(wi_refnum) AS bbnum FROM whir WHERE wi_reftype = 'ADJ' AND wi_type = 1 AND wi_subtype = 'DO_06' ");
		
		// GET THE LAST REFNUM OF ADJ FOR DELIVERY IN
		$q2 = $this->db->query("SELECT MAX(wi_refnum) AS bbnum FROM whir WHERE wi_reftype = 'ADJ' AND wi_type = 0 AND wi_subtype = 'DI_06' GROUP BY wi_id");

		$bbnum_do = "2000000";
		$bbnum_di = "1000000";

		// SERIES FOR DELIVERY OUT
		foreach($q1->result_array() as $rr){
			$bbnum_do = $rr['bbnum'];

			if($bbnum_do == ""){
				$bbnum_do = "2000000";
			}elseif(empty($bbnum_do)){
				$bbnum_do = "2000000";
			}elseif(!is_numeric($bbnum_do)){
				$bbnum_do = "2000000";
			}else{
				(int)$bbnum_do;
				$bbnum_do+=1;
			}
		}

		// SERIES FOR DELIVERY IN
		foreach($q2->result_array() as $rs){

			$bbnum_di = $rs['bbnum'];

			if($bbnum_di == ""){
				$bbnum_di = "1000000";
			}elseif(empty($bbnum_di)){
				$bbnum_di = "1000000";
			}elseif(!is_numeric($bbnum_di)){
				$bbnum_di = "1000000";
			}else{
				(int)$bbnum_di;
				$bbnum_di+=1;
			}

		}

		foreach($qry->result_array() as $r){

			if($cbal2 <> "0.00"){
				// ARRAY DATA FOR OUT
				$dd1 = array(
					'wi_type'=>1,
					'wi_deltype'=>'Delivery Out',
					'wi_subtype'=>'DO_06',
					'wi_outby'=>$this->session->userdata('usr_uname'),
					'wi_outdatetime'=>$datetime,
					'wi_updateby'=>$this->session->userdata('usr_uname'),
					'wi_updatedatetime'=>$datetime,
					'wh_name'=>$wname2,
					'wi_reftype'=>'ADJ',
					'wi_refnum'=>$bbnum_do,
					'wi_refname'=>$r['wh_code'],
					'item_id'=>$icode2,
					'wi_itemqty'=>$count2,
					'wi_createby'=>$this->session->userdata('usr_uname'),
					'wi_status'=>1,
					'wi_approvestatus'=>1,
					'deldate'=>$codate2,
					'wi_remarks'=>'Beginning Balance',
					'wi_doqty'=>$count2,
					'item_uom'=>$unit2,
					'ship_date'=>$codate2,
					'wi_dtcode'=>'DT_02'
				);

				$this->db->insert('whir', $dd1);

				// ARRAY DATA FOR DSPR DOUT
				$dd8 = array(
					'item_id'=>$icode2,
					'wh_name'=>$wname2,
					'deltype'=>'Delivery Out',
					'itemqty'=>$count2,
					'doqty'=>$count2,
					'udoqty'=>0,
					'posting_date'=>$codate2,
					'reftype'=>'ADJ',
					'refnum'=>$bbnum_do
				);

				$this->db->insert('dspr_dout', $dd8);
			}

			// ARRAY DATA FOR IN TO ORIGINAL LOCATION
			$data = array(
				'wi_type'=>0,
				'wi_deltype'=>'Delivery In',
				'wi_subtype'=>'DI_06',
				'wh_name'=>$wname2,
				'item_id'=>$icode2,
				'item_uom'=>$unit2,
				'deldate'=>$codate2,
				'wi_itemqty'=>$count2,
				'wi_refname'=>$r['wh_code'],
				'wi_status'=>1,
				'wi_approvestatus'=>1,
				'wi_createby'=>$this->session->userdata('usr_uname'),
				'wi_reftype'=>'ADJ',
				'wi_remarks'=>'Beginning Balance',
				'wi_refnum'=>$bbnum_di,
				'wi_dtcode'=>'DT_01'
			);

			$this->db->insert('whir', $data);


			$qry4 = $this->db->query("SELECT MAX(wi_id) AS wi_id FROM whir");

			foreach($qry4->result_array() as $rid){
				// ARRAY DATA FOR DSPR DIN
				$dd7 = array(
					'wi_id'=>$rid['wi_id'],
					'item_id'=>$icode2,
					'wh_name'=>$wname2,
					'deltype'=>'Delivery In',
					'itemqty'=>$count2,
					'trans_datetime'=>$codate2,
					'posting_date'=>$codate2,
					'reftype'=>'ADJ',
					'refnum'=>$bbnum_di
				);

				$this->db->insert('dspr_din', $dd7);
			}

		}

	}

	function tout_itemqty_validation($dout_data){

		$item = $dout_data['item_dout'];
		$qty = $dout_data['qty_dout'];
		$get = $dout_data['from_dout'];

		$q=$this->db->query("
			SELECT a.comm__id,a.comm__name,b.sqty,c.tqty,d.rqty
			FROM ocmt a
			LEFT OUTER JOIN (
				SELECT item_id,sum(wi_itemqty) as sqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=0 AND wi_approvestatus=1 AND wi_status=1 
				GROUP BY item_id
			)b ON a.comm__id = b.item_id
			LEFT OUTER JOIN (
				SELECT item_id,sum(wi_itemqty) as tqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=1 AND wi_status=1 
				GROUP BY item_id
			)c ON a.comm__id = c.item_id
			LEFT OUTER JOIN (
				SELECT item_id,sum(wi_itemqty) as rqty 
				FROM whir 
				WHERE wh_name='".$get."' AND item_id='".$item."' AND wi_type=2 AND wi_status=1 
				GROUP BY item_id
			)d ON a.comm__id = d.item_id
			WHERE comm__id='".$item."'
			");
		if ($q->num_rows() == true){
			//return $q->result();
			foreach($q->result() as $r){
				if ($r->sqty == null){
					$sqty = 0;
				}
				else{
					$sqty = $r->sqty;
				}
				if ($r->tqty == null){
					$tqty = 0;
				}
				else{
					$tqty = $r->tqty;
				}
				if ($r->rqty == null){
					$rqty = 0;
				}
				else{
					$rqty = $r->rqty;
				}
				$total = ($sqty - ($tqty + $rqty));
			}
			if ($qty > $total){
				return true;
			}
			//echo($total);
		}
	}

	function validation_del_in_ref_tin($din_data){

			if ($din_data['reftype1_din'] <> 'DO'){
				if($din_data['reftype1_din'] <> 'DO'){
					if ($din_data['reftype2_din'] <> 'ITO'){
						if($din_data['reftype2_din'] <> 'ITO'){
							$q = $this->db->query("
								SELECT *
								FROM whir
								WHERE wi_type=0
									AND wi_status=1
									AND wi_reftype='".$din_data['reftype1_din']."'
									AND wi_refnum='".$din_data['refnum1_din']."'
									AND wh_name='".$din_data['from_din']."'
								OR wi_type=0
									AND wi_status=1
									AND wi_reftype='".$din_data['reftype2_din']."'
									AND wi_refnum='".$din_data['refnum2_din']."'
									AND wh_name='".$din_data['from_din']."'
							");
							if($q->num_rows() == true){
								return true;
							}
						}
					}
				}
			}

	}


	function validation_del_in_ref_tout($dout_data){

		$qry = $this->db->query("SELECT * FROM whir
								WHERE wi_type=2
									AND wi_status=1
									AND wi_reftype='".$dout_data['reftype1_dout']."' 
									AND wi_refnum='".$dout_data['refnum1_dout']."'
									AND wh_name='".$dout_data['from_dout']."' 
								OR wi_type=2
									AND wi_status=1
									AND wi_reftype2='".$dout_data['reftype2_dout']."'
									AND wi_refnum2='".$dout_data['refnum2_dout']."'
									AND wh_name='".$dout_data['from_dout']."' ");
		if($qry->num_rows() > 0){
			return TRUE;
		}

	}

	function insert_tout($dout_data){

		$wname = $dout_data['to_dout'];

		// $qry = $this->db->query("SELECT CardCode FROM ocrd WHERE CardName = '$wname' ");

		$qry = $this->db->query("SELECT wh_code, wh_sapcode FROM mwhr WHERE wh_name = '$wname' ");

		foreach($qry->result_array() as $r){

			$wcode = "";

			if ($r['wh_sapcode'] <> "") {
				$wcode = $r['wh_sapcode'];
			}else{
				$wcode = $r['wh_code'];
			}

			$data = array(
				'wi_type'=>2,
				'wi_deltype'=>'Delivery Out',
				'doc_series_no'=> $dout_data['ds_no_dout'],
				'deldate'=>$this->input->post('pdate_dout'),
				'wi_refname'=>$wcode,
				'wh_name'=>$dout_data['from_dout'],
				'wi_reftype'=>$dout_data['reftype1_dout'],
				'wi_refnum'=>$dout_data['refnum1_dout'],
				'wi_reftype2'=>$dout_data['reftype2_dout'],
				'wi_refnum2'=>$dout_data['refnum2_dout'],
				'item_id'=>$dout_data['item_dout'],
				'item_uom'=>$dout_data['uom_dout'],
				'wi_itemqty'=>$dout_data['qty_dout'],
				'wi_doqty'=>$dout_data['qty_dout'],
				'wi_location'=>$dout_data['to_dout'],
				'truck_company'=>$dout_data['truck_comp_dout'],
				'truck_driver'=>$dout_data['tdrvr_dout'],
				'truck_platenum'=>$dout_data['tpnum_dout'],
				'truck_driver'=>$dout_data['tdrvr_dout'],
				'ship_date'=>$dout_data['apdate_dout'],
				'wi_remarks'=>$dout_data['remarks_dout'],
				'wi_createby'=>$this->session->userdata('usr_uname'),
				'wi_subtype'=>'DO_02',
				'wi_status'=>1,
				'wi_dtcode'=>'DT_06',
				'wi_transno'=>$this->input->post('tno_tout'),

				'no_of_print'=>1,
				'print_status'=>1
			);

			$this->db->insert('whir', $data);

			//UPDATE SNDR TABLE
			$qry2  = $this->db->select('sn_nextnum')
						  ->where('sn_code', 'TOUT')
						  ->where('whse_code', $this->uri->segment(3))
			              ->get('sndr');

			foreach($qry2->result_array() as $tn){

				(int)$next_no = $tn['sn_nextnum'];
				$next_no+=1;

				$data2 = array(
					'sn_nextnum'=>$next_no
				);

				$this->db->where('sn_code', 'TOUT')
						 ->where('whse_code', $this->uri->segment(3))
				         ->update('sndr', $data2);

			}
		}

	}

	function get_tout_data($ds_no_din){

		$this->db->where('wi_dtcode', 'DT_06');
		$this->db->where('wi_type', 1);
		$this->db->where('doc_series_no', $ds_no_din);
		$qry = $this->db->get('whir');

		if($qry->num_rows() > 0){
			$data['row'] = $qry->result();
			return $data;
		}

	}
	
	function get_return_data($do_num){

		$this->db->where('wi_dtcode', 'DT_04');
		$this->db->where('wi_type', 1);
		$this->db->where('wi_refnum', $do_num);
		$qry = $this->db->get('whir');
		
		if($qry->num_rows() > 0){
			$data['row'] = $qry->result();
			return $data;
		}

	}

	function insert_tin($din_data){

		$wname = $din_data['from_din'];

		// $qry = $this->db->query("SELECT CardCode FROM ocrd WHERE CardName = '$wname' ");

		$qry = $this->db->query("SELECT wh_code, wh_sapcode, wh_name FROM mwhr WHERE wh_name = '$wname' ");

		foreach($qry->result_array() as $r){

			$wcode = "";

			if ($r['wh_sapcode'] <> "") {
				$wcode = $r['wh_sapcode'];
			}else{
				$wcode = $r['wh_code'];
			}

			$data = array(
				'wi_type'=>0,
				'wi_deltype'=>'Delivery In',
				'deldate'=>$this->input->post('pdate_din'),
				'wi_refname'=>$wcode,
				'wh_name'=>$din_data['to_din'],
				'wi_reftype'=>$din_data['reftype1_din'],
				'wi_refnum'=>$din_data['refnum1_din'],
				'wi_reftype2'=>$din_data['reftype2_din'],
				'wi_refnum2'=>$din_data['refnum2_din'],
				'item_id'=>$din_data['icode_din'],
				'item_uom'=>$din_data['uom_din'],
				'wi_itemqty'=>$din_data['qty_din'],
				'wi_location'=>$r['wh_name'],
				'wi_remarks'=>$din_data['remarks_din'],
				'wi_createby'=>$this->session->userdata('usr_uname'),
				'wi_subtype'=>'DI_01',
				'wi_status'=>1,
				'wi_dtcode'=>'DT_05',
				'wi_transno'=>$this->input->post('tno_tin'),
				'truck_company'=>$din_data['truck_company'],

				'no_of_print'=>1,
				'print_status'=>1
			);

			$this->db->insert('whir', $data);

			//UPDATE SNDR TABLE
			$qry2  = $this->db->select('sn_nextnum')
						  ->where('sn_code', 'TIN')
						  ->where('whse_code', $this->uri->segment(3))
			              ->get('sndr');

			foreach($qry2->result_array() as $tn){

				(int)$next_no = $tn['sn_nextnum'];
				$next_no+=1;

				$data2 = array(
					'sn_nextnum'=>$next_no
				);

				$this->db->where('sn_code', 'TIN')
						 ->where('whse_code', $this->uri->segment(3))
				         ->update('sndr', $data2);

			}
		}

	}

	function do_nappr_dout($dout_data){

		$wname = $dout_data['to_dout'];

		$qry = $this->db->query("SELECT MAX(wi_id) AS wi_id FROM whir");
		$qry2 = $this->db->query("SELECT CardCode FROM ocrd WHERE CardName = '$wname' ");

		foreach($qry->result_array() as $r){
			foreach($qry2->result_array() as $r2){
				$data = array(
					'wi_id'=>$r['wi_id'],
					'do_id'=>"do_".$r['wi_id'],
					'wi_type'=>2,
					'wh_name'=>$dout_data['from_dout'],
					'wi_reftype'=>$dout_data['reftype1_dout'],
					'wi_refnum'=>$dout_data['refnum1_dout'],
					'wi_reftype2'=>$dout_data['reftype2_dout'],
					'wi_refnum2'=>$dout_data['refnum2_dout'],
					'wi_refname'=>$r2['CardCode'],
					'item_id'=>$dout_data['item_dout'],
					'wi_itemqty'=>$dout_data['qty_dout'],
					'wi_createby'=>$this->session->userdata('usr_uname'),
					'wi_status'=>1,
					'deldate'=>$this->input->post('pdate_dout'),
					'wi_remarks'=>$dout_data['remarks_dout'],
					'item_uom'=>$dout_data['uom_dout'],
					'wi_deltype'=>'Delivery Out',
					'wi_location'=>$dout_data['to_dout'],
					'wi_subtype'=>'DO_02'
				);
						
				$this->db->insert('do_nappr',$data);
			}
		}

	}


	function di_nappr_din($din_data){

		$qry = $this->db->query("SELECT MAX(wi_id) AS wi_id FROM whir");

		foreach($qry->result_array() as $r){

			$data = array(
				'wi_id'=>$r['wi_id'],
				'di_id'=>"di_".$r['wi_id'],
				'wi_type'=>0,
				'wh_name'=>$din_data['from_din'],
				'wi_reftype'=>$din_data['reftype1_din'],
				'wi_refnum'=>$din_data['refnum1_din'],
				'wi_reftype2'=>$din_data['reftype2_din'],
				'wi_refnum2'=>$din_data['refnum2_din'],
				'wi_refname'=>$din_data['to_din'],
				'item_id'=>$din_data['icode_din'],
				'wi_itemqty'=>$din_data['qty_din'],
				'wi_createby'=>$this->session->userdata('usr_uname'),
				'wi_status'=>1,
				'deldate'=>$this->input->post('pdate_din'),
				'wi_remarks'=>$din_data['remarks_din'],
				'item_uom'=>$din_data['uom_din'],
				'wi_deltype'=>'Delivery In',
				'wi_location'=>$din_data['to_din'],
				'wi_subtype'=>'DI_01'
			);
					
			$this->db->insert('di_nappr',$data);
		}

	}

	function tin_print_layout(){

		$this->db->select_max('wi_id');
		$qry1 = $this->db->get('whir');

		foreach($qry1->result_array() as $row){

			$whse_id = $row['wi_id'];

			$qry2 = $this->db->query("SELECT * FROM whir a
										LEFT OUTER JOIN ocmt b ON b.comm__id = a.item_id
									WHERE a.wi_id = '$whse_id' ");

			if($qry2->num_rows() > 0){

				foreach($qry2->result_array() as $ra){

					// INSERT INTO PDOCS TABLE SUMMARY OF PRINT DOCUMENTS
					date_default_timezone_set("Asia/Manila");
					$datetime = date('Y-m-d h:i:s');

					$data = array(
							'whse_id'=>$row['wi_id'],
							'doc_type'=>'TIN',
							'whse_name'=>$ra['wh_name'],
							'print_datetime'=>$datetime,
							'print_by'=>$this->session->userdata('usr_uname')
					);

					$this->db->insert('pdocs', $data);

				}

				return $qry2->result();
			}
		}

	}

	function tout_print_layout(){

		$this->db->select_max('wi_id');
		$qry1 = $this->db->get('whir');

		foreach($qry1->result_array() as $row){

			$whse_id = $row['wi_id'];

			$qry2 = $this->db->query("SELECT * FROM whir a
										LEFT OUTER JOIN ocmt b ON b.comm__id = a.item_id
									WHERE a.wi_id = '$whse_id' ");

			if($qry2->num_rows() > 0){

				foreach($qry2->result_array() as $ra){

					// INSERT INTO PDOCS TABLE SUMMARY OF PRINT DOCUMENTS
					date_default_timezone_set("Asia/Manila");
					$datetime = date('Y-m-d h:i:s');

					$data = array(
							'whse_id'=>$row['wi_id'],
							'doc_type'=>'TOUT',
							'whse_name'=>$ra['wh_name'],
							'print_datetime'=>$datetime,
							'print_by'=>$this->session->userdata('usr_uname')
					);

					$this->db->insert('pdocs', $data);

				}

				return $qry2->result();
			}
		}

	}

	function get_last_inserted_id(){
		$this->db->select_max('wi_id');
     	$query = $this->db->get('whir');

     	if($query->num_rows() > 0){
     		return $query->result();
     	}
	}


	function wh_list_name(){

		$wcode = $this->uri->segment(3);

		$this->db->where('wh_code', $wcode);
		$qry = $this->db->get('mwhr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function get_available($item_code, $wh_code){

		// $wh_code = 7;
		$q = $this->db->query("SELECT a.comm__id, a.comm__code2, a.comm__name,b.sqty, c.tqty ,d.rqty
			FROM ocmt a
			LEFT OUTER JOIN (SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as sqty 
				FROM whir INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=0 
					AND whir.wi_status=1 
					AND whir.wi_approvestatus=1
					AND mwhr.wh_code='".$wh_code."'
				GROUP BY item_id)b 
			ON a.comm__id = b.item_id
			LEFT OUTER JOIN(SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as tqty 
				FROM whir INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=1 
					AND whir.wi_status=1 
					AND mwhr.wh_code='".$wh_code."'
				GROUP BY item_id)c 
			ON a.comm__id = c.item_id
			LEFT OUTER JOIN(
				SELECT whir.item_id,mwhr.wh_name,sum(whir.wi_itemqty) as rqty 
				FROM whir 
				INNER JOIN mwhr ON whir.wh_name = mwhr.wh_name
				WHERE whir.wi_type=2 
					AND whir.wi_status=1 
					AND wi_status=1 
					AND mwhr.wh_code='".$wh_code."'
				GROUP BY item_id
			)d 
			ON a.comm__id = d.item_id
			WHERE comm__id = '$item_code' ");

		if ($q->num_rows() == true){
			$rec['row'] = $q->result();
			return $rec;
		}
	}

	function whlist_mm(){
		$q=$this->db->query("SELECT a.wh_code,
				a.wh_name,
				a.wh_status  FROM mwhr a 
			LEFT JOIN ouaw b ON a.wh_name = b.accessname
			where a.wh_status=1 and b.usercode='".$this->session->userdata('usr_uname')."' and b.status=1 
			ORDER BY a.wh_name 
		");
		if ($q->num_rows==true){
			foreach($q->result_array() as $r){
				$data[$r['wh_code']]=$r['wh_name'];
			}
			return $data;
		}
	}


	function get_truck_plateno($truck_name){

		$this->db->where('Transporter_Name', $truck_name);
		$qry = $this->db->get('dtrk');

		foreach($qry->result_array() as $rec){

			$tcode = $rec['Transporter_Code'];
			$qry2 = $this->db->query("SELECT DISTINCT(Truck_PlateNo) FROM strk WHERE Transporter_Code = '$tcode' ");

			if($qry2->num_rows() > 0){
				$data['row'] = $qry2->result();
				return $data;
			}else{
				$data['row'] = "WALA";
				return $data;
			}
		}

	}

	function get_truck_driver($truck_plateno){

		$this->db->where('Truck_PlateNo', $truck_plateno);
		$qry = $this->db->get('strk');

		if($qry->num_rows() > 0){
			$data['row'] = $qry->result();
			return $data;
		}else{
			$data['row'] = "WALA";
			return $data;
		}

	}

	function get_truck_company_list(){

		$qry = $this->db->get('dtrk');
		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function truck_company_series(){
		$this->db->select_max('Transporter_Code');
		$qry = $this->db->get('dtrk');

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function check_truck_name_if_exists(){

		$name = $this->input->post('truck_short_name');
		$this->db->where('Transporter_Name', $name);

		$qry = $this->db->get('dtrk');

		if($qry->num_rows() > 0){
			return true;
		}

	}

	function insert_truck_company(){

		$data = array(
				'Transporter_Code'=>$this->input->post('truck_code'),
				'Transporter_Name'=>$this->input->post('truck_short_name'),
				'Transporter_Fullname'=>$this->input->post('truck_name'),
				'Status'=>$this->input->post('truck_status')
			);

		$this->db->insert('dtrk', $data);

	}


	function truck_company_edit_records(){

		$this->db->where('Transporter_Code', $this->uri->segment(3));
		$qry = $this->db->get('dtrk');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function update_truck_company(){

		$data = array(
				'Status'=>$this->input->post('truck_status')
			);

		$this->db->where('Transporter_Code', $this->uri->segment(3));
		$this->db->update('dtrk', $data);

	}

	function check_if_truck_company_trans(){

		$this->db->where('Transporter_Code', $this->uri->segment(3));
		$qry = $this->db->get('dtrk');

		foreach($qry->result_array() as $r){

			$tname = $r['Transporter_Name'];
			$qry2 = $this->db->query("SELECT * FROM whir WHERE truck_company = '$tname' ");

			if($qry2->num_rows() > 0){
				return true;
			}

		}

	}

	function truck_company_delete(){
		$this->db->where('Transporter_Code', $this->uri->segment(3));
		$this->db->delete('dtrk');
	}

	function get_truck_driver_list(){

		$qry = $this->db->query("SELECT *, a.Id AS tcode, a.Status AS tstats FROM strk a
									LEFT JOIN dtrk b ON b.Transporter_Code = a.Transporter_Code");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function get_truck_company(){

		$qry = $this->db->get('dtrk');

		foreach($qry->result_array() as $r){
			$data[$r['Transporter_Code']] = $r['Transporter_Name']; 
		}

		return $data;

	}

	function check_truck_name_plateno_if_exists(){

		$tcode = $this->input->post('truck_company');
		$dname = $this->input->post('driver_name');
		$tpnum = $this->input->post('truck_plateno');

		$qry = $this->db->select('*')
			->from('strk')
			->where('Transporter_Code', $tcode)
			->like('Truck_PlateNo', $tpnum, 'after')
			->like('Truck_Driver', $dname, 'after')
			->get();

		if($qry->num_rows() > 0){
			return TRUE;
		}

	}

	function insert_truck_driver(){

		$data= array(
			'Transporter_Code'=>$this->input->post('truck_company'),
			'Truck_PlateNo'=>$this->input->post('truck_plateno'),
			'Truck_Driver'=>$this->input->post('driver_name'),
			'Status'=>$this->input->post('status')
		);

		$this->db->insert('strk', $data);
	}

	function truck_driver_edit_records(){

		$this->db->where('Id', $this->uri->segment(3));
		$qry = $this->db->get('strk');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function update_truck_driver(){

		$data = array(
				'Status'=>$this->input->post('status')
			);

		$this->db->where('Id', $this->uri->segment(3));
		$this->db->update('strk', $data);

	}

	function check_if_truck_driver_trans(){

		$temp = explode('%20', $this->uri->segment(3));
		$name = $temp[0].' '.$temp[1];

		$qry = $this->db->select('*')
			->from('whir')
			->like('truck_driver', $name, 'after')
			->get();

		if($qry->num_rows() > 0){
			return TRUE;
		}

	}

	function truck_driver_delete(){

		$temp = explode('%20', $this->uri->segment(3));
		$name = $temp[0].' '.$temp[1];

		$this->db->like('Truck_Driver', $name, 'after');
		$this->db->delete('strk');
	}

	function prepared_list(){

		$wid = $this->uri->segment(3);

		$this->db->where('wh_code', $wid);
		$qry = $this->db->get('mwhr');

		foreach($qry->result_array() as $rec){
			$qry2 = $this->db->distinct()
				->select('prepared_by')
				->where('wh_name', $rec['wh_name'])
				->get('whir');

			if($qry2->num_rows() > 0){
				return $qry2->result();
			}
		}

	}

	function checked_list(){

		$wid = $this->uri->segment(3);

		$this->db->where('wh_code', $wid);
		$qry = $this->db->get('mwhr');

		foreach($qry->result_array() as $rec){
			$qry2 = $this->db->distinct()
				->select('checked_by')
				->where('wh_name', $rec['wh_name'])
				->get('whir');

			if($qry2->num_rows() > 0){
				return $qry2->result();
			}
		}

	}

	function guard_list(){

		$wid = $this->uri->segment(3);

		$this->db->where('wh_code', $wid);
		$qry = $this->db->get('mwhr');

		foreach($qry->result_array() as $rec){
			$qry2 = $this->db->distinct()
				->select('guard_duty')
				->where('wh_name', $rec['wh_name'])
				->get('whir');

			if($qry2->num_rows() > 0){
				return $qry2->result();
			}
		}

	}

	function print_dr_list(){

		$wh_name = $this->input->post('whouse');

		$qry = $this->db->query("SELECT * FROM whir a 
							LEFT JOIN ocmt b ON b.comm__id = a.item_id 
							WHERE a.wi_reftype2 = 'DR' AND
							a.wh_name = '$wh_name' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function open_dr_pdf(){

		$total = 0;

		$whse_id = $this->uri->segment(3);

		$qry = $this->db->query("SELECT * FROM whir a
									LEFT OUTER JOIN ocmt b ON b.comm__id = a.item_id
									LEFT OUTER JOIN ocrd c ON c.CardCode = a.wi_refname
								WHERE a.wi_id = '$whse_id' ");

		if($qry->num_rows() > 0){
			foreach($qry->result_array() as $r){
				$total = $r['no_of_print'];
				$total += $total;

				$data = array(
					'no_of_print'=>$total
				);

				$this->db->where('wi_id', $whse_id)
				     ->update('whir', $data);
			}

			return $qry->result();
		}

	}

	function print_wis_list(){

		$wh_name = $this->input->post('whouse');

		$qry = $this->db->query("SELECT * FROM whir a 
							LEFT JOIN ocmt b ON b.comm__id = a.item_id 
							WHERE a.wi_reftype2 = 'WIS' AND
							a.wh_name = '$wh_name' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function open_wis_pdf(){

		$whse_id = $this->uri->segment(3);

		$qry = $this->db->query("SELECT * FROM whir a
									LEFT OUTER JOIN ocmt b ON b.comm__id = a.item_id
									LEFT OUTER JOIN ocrd c ON c.CardCode = a.wi_refname
								WHERE a.wi_id = '$whse_id' ");

		if($qry->num_rows() > 0){
			foreach($qry->result_array() as $r){
				$total = $r['no_of_print'];
				$total += $total;

				$data = array(
					'no_of_print'=>$total
				);

				$this->db->where('wi_id', $whse_id)
				     ->update('whir', $data);
			}

			return $qry->result();
		}

	}

	function print_rr_list(){

		$wh_name = $this->input->post('whouse');

		$qry = $this->db->query("SELECT * FROM whir a 
							LEFT JOIN ocmt b ON b.comm__id = a.item_id 
							WHERE a.wi_reftype2 = 'RR' OR a.wi_reftype = 'RR' AND
							a.wh_name = '$wh_name' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function open_rr_pdf(){

		$whse_id = $this->uri->segment(3);

		$qry = $this->db->query("SELECT * FROM whir a
									LEFT OUTER JOIN ocmt b ON b.comm__id = a.item_id
									LEFT OUTER JOIN ocrd c ON c.CardCode = a.wi_refname
								WHERE a.wi_id = '$whse_id' ");

		if($qry->num_rows() > 0){

			foreach($qry->result_array() as $r){
				$total = $r['no_of_print'];
				$total += $total;

				$data = array(
					'no_of_print'=>$total
				);

				$this->db->where('wi_id', $whse_id)
				     ->update('whir', $data);
			}

			return $qry->result();
		}

	}

	function print_tout_list(){

		$wh_name = $this->input->post('whouse');

		$qry = $this->db->query("SELECT * FROM whir a 
							LEFT JOIN ocmt b ON b.comm__id = a.item_id 
							WHERE a.wi_dtcode = 'DT_06' AND
							a.wh_name = '$wh_name' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function open_tout_pdf(){

		$whse_id = $this->uri->segment(3);

		$qry = $this->db->query("SELECT * FROM whir a
									LEFT OUTER JOIN ocmt b ON b.comm__id = a.item_id
									LEFT OUTER JOIN ocrd c ON c.CardCode = a.wi_refname
								WHERE a.wi_id = '$whse_id' ");

		if($qry->num_rows() > 0){

			foreach($qry->result_array() as $r){
				$total = $r['no_of_print'];
				$total += $total;

				$data = array(
					'no_of_print'=>$total
				);

				$this->db->where('wi_id', $whse_id)
				     ->update('whir', $data);
			}

			return $qry->result();
		}

	}

	function print_tin_list(){

		$wh_name = $this->input->post('whouse');

		$qry = $this->db->query("SELECT * FROM whir a 
							LEFT JOIN ocmt b ON b.comm__id = a.item_id 
							WHERE a.wi_dtcode = 'DT_05' AND
							a.wh_name = '$wh_name' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function open_tin_pdf(){

		$whse_id = $this->uri->segment(3);

		$qry = $this->db->query("SELECT * FROM whir a
									LEFT OUTER JOIN ocmt b ON b.comm__id = a.item_id
									LEFT OUTER JOIN ocrd c ON c.CardCode = a.wi_refname
								WHERE a.wi_id = '$whse_id' ");

		if($qry->num_rows() > 0){

			foreach($qry->result_array() as $r){
				$total = $r['no_of_print'];
				$total += $total;

				$data = array(
					'no_of_print'=>$total
				);

				$this->db->where('wi_id', $whse_id)
				     ->update('whir', $data);
			}
			
			return $qry->result();
		}

	}

	function delete_dr_pdf(){

		$data = array('print_status'=>0);

		$this->db->where('wi_id', $this->uri->segment(3))
				 ->update('whir', $data);
	}

	function delete_wis_pdf(){

		$data = array('print_status'=>0);

		$this->db->where('wi_id', $this->uri->segment(3))
				 ->update('whir', $data);
	}


	function delete_rr_pdf(){

		$data = array('print_status'=>0);

		$this->db->where('wi_id', $this->uri->segment(3))
				 ->update('whir', $data);
	}

	function delete_tin_pdf(){

		$data = array('print_status'=>0);

		$this->db->where('wi_id', $this->uri->segment(3))
				 ->update('whir', $data);
	}
	
	function delete_tout_pdf(){

		$data = array('print_status'=>0);

		$this->db->where('wi_id', $this->uri->segment(3))
				 ->update('whir', $data);
	}

	function get_summary_print_list(){
		$dcode = $this->input->post('dtype');
		$wcode = $this->input->post('whouse');

		$qry = $this->db->where('doc_type', $dcode)
						->where('whse_name', $wcode)
						->get('pdocs');

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function get_sap_do_to_json($whse_json_id){

		// OLD QUERY
		// $qry = $this->db->query("SELECT *, c.wh_code AS WebWhsCode, c.wh_name AS WebWhsName FROM whir a
		// 							LEFT OUTER JOIN sap_do_data b ON b.do_no = a.wi_refnum 
		// 							LEFT OUTER JOIN mwhr c ON c.wh_name = a.wh_name
		// 						WHERE a.wi_reftype = 'DO' AND a.wi_id = '$whse_json_id' ");

		$qry = $this->db->query("SELECT 
									a.wi_id,
									a.wi_transno,

									CASE
									WHEN ISNULL(b.do_date) 
									THEN a.deldate
									ELSE b.do_date
									END 'do_date',

									CASE 
									WHEN ISNULL(b.sap_exp_del_date)
									THEN '1900-01-01'
									ELSE b.sap_exp_del_date
									END 'sap_exp_del_date',

									CASE 
									WHEN ISNULL(b.date_gap)
									THEN ''
									ELSE b.date_gap
									END 'date_gap',

									CASE
									WHEN ISNULL(b.do_no)
									THEN a.wi_refnum
									ELSE b.do_no
									END 'do_no',

									CASE
									WHEN ISNULL(b.series)
									THEN ''
									ELSE b.series
									END 'series',

									CASE 
									WHEN ISNULL(b.def_doc_type)
									THEN ''
									ELSE b.def_doc_type
									END 'def_doc_type',

									CASE
									WHEN ISNULL(b.transmit_date)
									THEN '1900-01-01'
									ELSE b.transmit_date
									END 'transmit_date',

									CASE
									WHEN ISNULL(b.do_transmit_date)
									THEN '1900-01-01'
									ELSE b.do_transmit_date
									END 'do_transmit_date',

									CASE 
									WHEN ISNULL(b.do_stat)
									THEN ''
									ELSE b.do_stat
									END 'do_stat',

									CASE
									WHEN ISNULL(b.card_code)
									THEN a.wi_refname
									ELSE b.card_code
									END 'card_code',

									CASE
									WHEN ISNULL(b.card_name)
									THEN d.CardName
									ELSE 
										CASE 
										WHEN ISNULL(b.card_name)
										THEN ''
										ELSE b.card_name
										END
									END 'card_name',

									CASE
									WHEN ISNULL(b.sdr_no)
									THEN ''
									ELSE b.sdr_no
									END 'sdr_no',

									CASE
									WHEN ISNULL(b.location)
									THEN a.wi_location
									ELSE b.location
									END 'location',

									CASE
									WHEN ISNULL(b.mother_po)
									THEN 
										CASE 
										WHEN ISNULL(a.wi_PONum)
										THEN ''
										ELSE a.wi_PONum
										END
									ELSE b.mother_po
									END 'mother_po',

									CASE
									WHEN ISNULL(b.truck_code)
									THEN ''
									ELSE b.truck_code
									END 'truck_code',

									CASE
									WHEN ISNULL(b.truck_company)
									THEN a.truck_company
									ELSE b.truck_company
									END 'truck_company',

									CASE
									WHEN ISNULL(b.whse_code)
									THEN ''
									ELSE b.whse_code
									END 'whse_code',

									CASE
									WHEN ISNULL(b.source)
									THEN a.wh_name
									ELSE b.source
									END 'source',

									CASE
									WHEN ISNULL(b.whse_type)
									THEN ''
									ELSE b.whse_type
									END 'whse_type',

									CASE
									WHEN ISNULL(b.sa_dockey)
									THEN ''
									ELSE b.sa_dockey
									END 'sa_dockey',

									CASE
									WHEN ISNULL(b.sa_docno)
									THEN ''
									ELSE b.sa_docno
									END 'sa_docno',

									CASE
									WHEN ISNULL(b.sa_docline)
									THEN ''
									ELSE b.sa_docline
									END 'sa_docline',

									CASE
									WHEN ISNULL(b.sa_transno)
									THEN ''
									ELSE b.sa_transno
									END 'sa_transno',

									CASE
									WHEN ISNULL(b.sa_transtype)
									THEN ''
									ELSE b.sa_transtype
									END 'sa_transtype',

									CASE
									WHEN ISNULL(b.item_code)
									THEN a.item_id
									ELSE b.item_code
									END 'item_code',

									CASE
									WHEN ISNULL(b.item_desc)
									THEN e.comm__name
									ELSE b.item_desc
									END 'item_desc',

									CASE
									WHEN ISNULL(b.uom)
									THEN a.item_uom
									ELSE b.uom
									END 'uom',

									CASE
									WHEN ISNULL(b.do_qty)
									THEN a.wi_doqty
									ELSE b.do_qty
									END 'do_qty',

									CASE
									WHEN ISNULL(b.b1_udo_qty)
									THEN ''
									ELSE b.b1_udo_qty
									END b1_udo_qty,

									CASE
									WHEN ISNULL(b.served_qty)
									THEN ''
									ELSE b.served_qty
									END 'served_qty',

									CASE
									WHEN ISNULL(b.trans_po)
									THEN 
										CASE
										WHEN ISNULL(a.wi_PONum)
										THEN ''
										ELSE a.wi_PONum
										END
									ELSE b.trans_po
									END 'trans_po',

									CASE 
									WHEN ISNULL(b.ae_code)
									THEN ''
									ELSE b.ae_code
									END 'ae_code',

									CASE
									WHEN ISNULL(b.ae)
									THEN ''
									ELSE b.ae
									END 'ae',

									CASE
									WHEN ISNULL(b.do_remarks)
									THEN ''
									ELSE b.do_remarks
									END 'do_remarks',

									CASE
									WHEN ISNULL(b.update_date)
									THEN '1900-01-01'
									ELSE b.update_date
									END 'update_date',

									a.wi_itemqty,
									a.deldate,

									CASE 
									WHEN ISNULL(b.base_line)
									THEN ''
									ELSE b.base_line
									END 'base_line',

									CASE
									WHEN ISNULL(b.base_entry)
									THEN ''
									ELSE b.base_entry
									END 'base_entry',

									CASE
									WHEN ISNULL(b.base_type)
									THEN ''
									ELSE b.base_type
									END 'base_type',

									CASE
									WHEN ISNULL(a.wi_remarks)
									THEN ''
									ELSE a.wi_remarks
									END 'wi_remarks',

									CASE
									WHEN ISNULL(a.wi_dtcode)
									THEN ''
									ELSE a.wi_dtcode
									END 'wi_dtcode',

									CASE
									WHEN ISNULL(a.pbatch_code)
									THEN ''
									ELSE a.pbatch_code
									END 'pbatch_code',

									CASE
									WHEN ISNULL(b.remarks)
									THEN ''
									ELSE b.remarks
									END 'remarks',

									CASE
									WHEN ISNULL(a.wi_refnum2)
									THEN ''
									ELSE a.wi_refnum2
									END 'drref',

									CASE
									WHEN ISNULL(b.u_do)
									THEN ''
									ELSE b.u_do
									END 'u_do',

									c.wh_code AS WebWhsCode, c.wh_name AS WebWhsName,

									a.wi_dtcode,

									a.wh_name AS awh_name,

									c.wh_sapcode AS mwh_code,

									d.CardCode,

									b.dbtype

								FROM whir a
									LEFT OUTER JOIN sap_do_data b ON b.do_no = a.wi_refnum
									OR b.u_do = a.wi_refnum 
									LEFT OUTER JOIN mwhr c ON c.wh_name = a.wh_name
									LEFT OUTER JOIN ocrd d ON d.CardName = a.wh_name
									LEFT OUTER JOIN ocmt e ON e.comm__id = a.item_id
								WHERE a.wi_reftype = 'DO' AND a.wi_id = '$whse_json_id' ");


		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function get_sap_return_to_json($whse_json_id){
		$qry = $this->db->query("SELECT 
									a.wi_id,
									a.wi_transno,
									a.wi_dtcode,

									CASE
									WHEN ISNULL(b.do_date) 
									THEN a.deldate
									ELSE b.do_date
									END 'do_date',

									CASE 
									WHEN ISNULL(b.sap_exp_del_date)
									THEN '1900-01-01'
									ELSE b.sap_exp_del_date
									END 'sap_exp_del_date',

									CASE 
									WHEN ISNULL(b.date_gap)
									THEN '0'
									ELSE b.date_gap
									END 'date_gap',

									CASE
									WHEN ISNULL(b.do_no)
									THEN a.wi_refnum
									ELSE b.do_no
									END 'do_no',

									CASE
									WHEN ISNULL(b.series)
									THEN ''
									ELSE b.series
									END 'series',

									CASE 
									WHEN ISNULL(b.def_doc_type)
									THEN ''
									ELSE b.def_doc_type
									END 'def_doc_type',

									CASE
									WHEN ISNULL(b.transmit_date)
									THEN '1900-01-01'
									ELSE b.transmit_date
									END 'transmit_date',

									CASE
									WHEN ISNULL(b.do_transmit_date)
									THEN '1900-01-01'
									ELSE b.do_transmit_date
									END 'do_transmit_date',

									CASE 
									WHEN ISNULL(b.do_stat)
									THEN '0'
									ELSE b.do_stat
									END 'do_stat',

									CASE
									WHEN ISNULL(b.card_code)
									THEN a.wi_refname
									ELSE b.card_code
									END 'card_code',

									CASE
									WHEN ISNULL(b.card_name)
									THEN d.CardName
									ELSE 
										CASE 
										WHEN ISNULL(b.card_name)
										THEN ''
										ELSE b.card_name
										END
									END 'card_name',

									CASE
									WHEN ISNULL(b.sdr_no)
									THEN ''
									ELSE b.sdr_no
									END 'sdr_no',

									CASE
									WHEN ISNULL(b.location)
									THEN a.wi_location
									ELSE b.location
									END 'location',

									CASE
									WHEN ISNULL(b.mother_po)
									THEN 
										CASE 
										WHEN ISNULL(a.wi_PONum)
										THEN ''
										ELSE a.wi_PONum
										END
									ELSE b.mother_po
									END 'mother_po',

									CASE
									WHEN ISNULL(b.truck_code)
									THEN ''
									ELSE b.truck_code
									END 'truck_code',

									CASE
									WHEN ISNULL(b.truck_company)
									THEN a.truck_company
									ELSE b.truck_company
									END 'truck_company',

									CASE
									WHEN ISNULL(b.whse_code)
									THEN ''
									ELSE b.whse_code
									END 'whse_code',

									CASE
									WHEN ISNULL(b.source)
									THEN a.wh_name
									ELSE b.source
									END 'source',

									CASE
									WHEN ISNULL(b.whse_type)
									THEN ''
									ELSE b.whse_type
									END 'whse_type',

									CASE
									WHEN ISNULL(b.sa_dockey)
									THEN '0'
									ELSE b.sa_dockey
									END 'sa_dockey',

									CASE
									WHEN ISNULL(b.sa_docno)
									THEN '0'
									ELSE b.sa_docno
									END 'sa_docno',

									CASE
									WHEN ISNULL(b.sa_docline)
									THEN '0'
									ELSE b.sa_docline
									END 'sa_docline',

									CASE
									WHEN ISNULL(b.sa_transno)
									THEN '0'
									ELSE b.sa_transno
									END 'sa_transno',

									CASE
									WHEN ISNULL(b.sa_transtype)
									THEN ''
									ELSE b.sa_transtype
									END 'sa_transtype',

									CASE
									WHEN ISNULL(b.item_code)
									THEN a.item_id
									ELSE b.item_code
									END 'item_code',

									CASE
									WHEN ISNULL(b.item_desc)
									THEN e.comm__name
									ELSE b.item_desc
									END 'item_desc',

									CASE
									WHEN ISNULL(b.uom)
									THEN a.item_uom
									ELSE b.uom
									END 'uom',

									CASE
									WHEN ISNULL(b.do_qty)
									THEN a.wi_doqty
									ELSE b.do_qty
									END 'do_qty',

									CASE
									WHEN ISNULL(b.b1_udo_qty)
									THEN '0'
									ELSE b.b1_udo_qty
									END b1_udo_qty,

									CASE
									WHEN ISNULL(b.served_qty)
									THEN '0'
									ELSE b.served_qty
									END 'served_qty',

									CASE
									WHEN ISNULL(b.trans_po)
									THEN 
										CASE
										WHEN ISNULL(a.wi_PONum)
										THEN ''
										ELSE a.wi_PONum
										END
									ELSE b.trans_po
									END 'trans_po',

									CASE 
									WHEN ISNULL(b.ae_code)
									THEN ''
									ELSE b.ae_code
									END 'ae_code',

									CASE
									WHEN ISNULL(b.ae)
									THEN ''
									ELSE b.ae
									END 'ae',

									CASE
									WHEN ISNULL(b.do_remarks)
									THEN ''
									ELSE b.do_remarks
									END 'do_remarks',

									CASE
									WHEN ISNULL(b.update_date)
									THEN '1900-01-01'
									ELSE b.update_date
									END 'update_date',

									a.wi_itemqty,
									a.deldate,

									CASE 
									WHEN ISNULL(b.base_line)
									THEN ''
									ELSE b.base_line
									END 'base_line',

									CASE
									WHEN ISNULL(b.base_entry)
									THEN ''
									ELSE b.base_entry
									END 'base_entry',

									CASE
									WHEN ISNULL(b.base_type)
									THEN ''
									ELSE b.base_type
									END 'base_type',

									CASE
									WHEN ISNULL(a.wi_remarks)
									THEN ''
									ELSE a.wi_remarks
									END 'wi_remarks',

									CASE
									WHEN ISNULL(a.wi_dtcode)
									THEN ''
									ELSE a.wi_dtcode
									END 'wi_dtcode',

									CASE
									WHEN ISNULL(a.pbatch_code)
									THEN ''
									ELSE a.pbatch_code
									END 'pbatch_code',

									CASE
									WHEN ISNULL(b.remarks)
									THEN ''
									ELSE b.remarks
									END 'remarks',

									CASE
									WHEN ISNULL(a.wi_refnum2)
									THEN ''
									ELSE a.wi_refnum2
									END 'drref',

									CASE
									WHEN ISNULL(b.u_do)
									THEN ''
									ELSE b.u_do
									END 'u_do',

									c.wh_code AS WebWhsCode, c.wh_name AS WebWhsName,

									d.CardCode,

									b.dbtype 

								FROM whir a
									LEFT OUTER JOIN sap_do_data b ON b.do_no = a.wi_refnum 
									OR b.u_do = a.wi_refnum
									LEFT OUTER JOIN mwhr c ON c.wh_name = a.wh_name
									LEFT OUTER JOIN ocrd d ON d.CardName = a.wh_name
									LEFT OUTER JOIN ocmt e ON e.comm__id = a.item_id
								WHERE a.wi_reftype2 = 'DR' AND a.wi_id = '$whse_json_id' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function get_sap_whse_record($web_whse_rec){
		$qry = $this->db->query("SELECT * FROM whse_integration a 
								WHERE WhsCode = '".$web_whse_rec['web_whse_code']."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function get_sap_whse_record_return($web_whse_rec){
		$qry = $this->db->query("SELECT * FROM whse_integration_ims a 
								WHERE WhsCode = '".$web_whse_rec['web_whse_code']."' ");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function warehouse_integration_list(){
		$qry = $this->db->get("whse_integration");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function whse_integ_list(){
		$qry = $this->db
				->where(array('wh_status'=>1))
				->get("mwhr");

		if($qry->num_rows() > 0){
			foreach($qry->result_array() as $r){
				$data[$r['wh_code']] = $r['wh_code']." - ".$r['wh_name']; 
			}

			return $data;
		}
	}

	function whse_integ_list_sap(){

		$qry = $this->db->where(array('Type'=>'W'))
						->where("Remarks IS NULL")
						->get("ocrd");

		if($qry->num_rows() > 0){
			foreach($qry->result_array() as $r){
				$data[$r['CardCode']] = $r['CardCode']." - ".$r['CardName']; 
			}

			return $data;
		}
	}


	function warehouse_integration_create(){

		$data = array(
				'WhsCode'=>$this->input->post('wcode'),
				'WhsName'=>$this->input->post('wh_name'),
				'SAP_WhsCode'=>$this->input->post('swcode'),
				'SAP_WhsName'=>$this->input->post('sap_wname')
			);

		$this->db->insert('whse_integration', $data);

	}

	function whse_integ_list_edit(){
		$this->db->where(array('Id'=>$this->uri->segment(3)));
		$qry = $this->db->get('whse_integration');

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function warehouse_integration_edit(){
		$data = array(
				'WhsCode'=>$this->input->post('wcode'),
				'WhsName'=>$this->input->post('wh_name'),
				'SAP_WhsCode'=>$this->input->post('swcode'),
				'SAP_WhsName'=>$this->input->post('sap_wname')
			);

		$this->db->where(array('Id'=>$this->uri->segment(3)));
		$this->db->update('whse_integration', $data);
	}

	function warehouse_integration_delete(){
		$this->db->where(array('Id'=>$this->uri->segment(3)));
		$this->db->delete('whse_integration');
	}

	// ============================================================

	function warehouse_integration_list_ims(){
		$qry = $this->db->get("whse_integration_ims");

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function whse_integ_list_ims(){
		$qry = $this->db
				->where(array('wh_status'=>1))
				->get("mwhr");

		if($qry->num_rows() > 0){
			foreach($qry->result_array() as $r){
				$data[$r['wh_code']] = $r['wh_code']." - ".$r['wh_name']; 
			}

			return $data;
		}
	}

	function whse_integ_list_sap_ims(){

		$qry = $this->db->where(array('Type'=>'W'))
						->where("Remarks IS NULL")
						->get("ocrd");

		if($qry->num_rows() > 0){
			foreach($qry->result_array() as $r){
				$data[$r['CardCode']] = $r['CardCode']." - ".$r['CardName']; 
			}

			return $data;
		}
	}


	function warehouse_integration_create_ims(){

		$data = array(
				'WhsCode'=>$this->input->post('wcode'),
				'WhsName'=>$this->input->post('wh_name'),
				'SAP_WhsCode'=>$this->input->post('swcode'),
				'SAP_WhsName'=>$this->input->post('sap_wname')
			);

		$this->db->insert('whse_integration_ims', $data);

	}

	function whse_integ_list_edit_ims(){
		$this->db->where(array('Id'=>$this->uri->segment(3)));
		$qry = $this->db->get('whse_integration_ims');

		if($qry->num_rows() > 0){
			return $qry->result();
		}
	}

	function warehouse_integration_edit_ims(){
		$data = array(
				'WhsCode'=>$this->input->post('wcode'),
				'WhsName'=>$this->input->post('wh_name'),
				'SAP_WhsCode'=>$this->input->post('swcode'),
				'SAP_WhsName'=>$this->input->post('sap_wname')
			);

		$this->db->where(array('Id'=>$this->uri->segment(3)));
		$this->db->update('whse_integration_ims', $data);
	}

	function warehouse_integration_delete_ims(){
		$this->db->where(array('Id'=>$this->uri->segment(3)));
		$this->db->delete('whse_integration_ims');
	}

	function download_sap_do_old(){
		
		$db2 = $this->load->database('db2', TRUE);

		$db2->where('a.DocStatus', 'O');
		$db2->where('a.DocType', 'I');
		$db2->where('a.Printed', 'Y');
		$db2->where('c.U_Commodity', '02');
		$db2->where('a.DocDate > ', '2017-05-01');

		$db2->select("CAST(a.DocDate AS DATE) AS Dodate,
						CAST(a.DocDueDate AS DATE) AS SAPExpDeldate,
						DATEDIFF(dd, a.DocDate, A.DocDueDate) AS DateGap,
						a.DocNum AS DONo,
						a.Series,
						a.U_DRNo AS DefDocType, 
						CAST(a.U_TransDate AS DATE) AS TransmitDate,
						CAST(a.U_DOTransDate AS DATE) AS U_DOTransDate,
						a.DocStatus,
						a.CardCode,
					 	a.CardName,
						a.NumatCard AS PepsiSDRNo,
						a.Address2 AS Location,
						a.U_PONo AS MotherPO,
						a.Comments AS DoRemarks,
						CAST(a.UpdateDate AS DATE) AS UpdateDate,
						a.DocEntry,
						a.ObjType,
						  
						b.LineNum,
						b.WhsCode,
					 	b.U_SAdockey,
						b.U_SADocNo,
					 	b.U_SADocLine1,
						b.U_SATransNo,
						b.U_SATransTyp,
						b.ItemCode,
						b.Dscription,
						b.UnitMsr,
						b.Quantity AS DoQty,
						b.OpenCreQty AS B1UnservedQty,
						(b.Quantity - b.OpenCreQty) AS Served,
						b.U_PONo AS TransPO,
						b.BaseLine AS BaseLine,
						b.BaseEntry AS BaseEntry,
						b.BaseType,

						d.SlpCode,

						e.WhsName AS Source,
						e.U_Whse_Type,

						f.SlpName AS AE,

						g.Code AS TruckCode,
						g.Name AS TruckCo,
						
						b.SubCatNum AS CatalogNo
						  
					");

		$db2->from('ODLN a');
		$db2->join('DLN1 b', 'b.DocEntry = a.DocEntry', 'INNER');
		$db2->join('OITM c', 'c.ItemCode = b.ItemCode', 'INNER');
		$db2->join('OCRD d', 'd.CardCode = a.CardCode','INNER');
		$db2->join('OWHS e', 'e.WhsCode = b.WhsCode', 'INNER');
		$db2->join('OSLP f', 'f.SlpCode = d.SlpCode', 'LEFT');
		$db2->join('dbo.[@TRUCKER] g', 'g.Code = a.U_Trucker', 'LEFT');
			
		$qry = $db2->get();

		if($qry->num_rows() > 0){
			foreach($qry->result_array() as $r3){
				$data = array(
							'doc_entry'=>$r3['DocEntry'],
							'obj_type'=>$r3['ObjType'],
							'line_num'=>$r3['LineNum'],
							'do_date'=>$r3['Dodate'],
							'sap_exp_del_date'=>$r3['SAPExpDeldate'],
							'date_gap'=>$r3['DateGap'],
							'do_no'=>$r3['DONo'],
							'series'=>$r3['Series'],
							'def_doc_type'=>$r3['DefDocType'],
							'transmit_date'=>$r3['TransmitDate'],
							'do_transmit_date'=>$r3['U_DOTransDate'],
							'do_stat'=>$r3['DocStatus'],
							'card_code'=>$r3['CardCode'],
							'card_name'=>$r3['CardName'],
							'sdr_no'=>$r3['PepsiSDRNo'],
							'location'=>$r3['Location'],
							'mother_po'=>$r3['MotherPO'],
							'truck_code'=>$r3['TruckCode'],
							'truck_company'=>$r3['TruckCo'],
							'whse_code'=>$r3['WhsCode'],
							'source'=>$r3['Source'],
							'whse_type'=>$r3['U_Whse_Type'],
							'item_code'=>$r3['ItemCode'],
							'item_desc'=>$r3['Dscription'],
							'uom'=>$r3['UnitMsr'],
							'do_qty'=>$r3['DoQty'],
							'b1_udo_qty'=>$r3['B1UnservedQty'],
							'served_qty'=>$r3['Served'],
							'trans_po'=>$r3['TransPO'],
							'base_line'=>$r3['BaseLine'],
							'base_entry'=>$r3['BaseEntry'],
							'base_type'=>$r3['BaseType'],
							'ae_code'=>$r3['SlpCode'],
							'ae'=>$r3['AE'],
							'do_remarks'=>$r3['DoRemarks'],
							'update_date'=>$r3['UpdateDate'],
							'sa_dockey'=>$r3['U_SAdockey'],
							'sa_docno'=>$r3['U_SADocNo'],
							'sa_docline'=>$r3['U_SADocLine1'],
							'sa_transno'=>$r3['U_SATransNo'],
							'sa_transtype'=>$r3['U_SATransTyp']
						);

						$this->db->insert('sap_do_data_temp_01', $data);
			}

		}

	}

	function NCR_data(){
		$q = $this->db->query("
			SELECT 
				a.wh_code,
				a.wh_name,
				CASE WHEN b.sqty IS NULL THEN 0 ELSE b.sqty END AS delin,
				CASE WHEN c.tqty IS NULL THEN 0 ELSE c.tqty END AS delout,
				CASE WHEN d.rqty IS NULL THEN 0 ELSE d.rqty END AS delres
			FROM mwhr a
			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as sqty 
				FROM whir 
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				where wi_type=0 AND wi_status=1 AND wi_approvestatus=1  group by wh_name
			)b ON a.wh_name=b.wh_name

			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as tqty 
				FROM whir 
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				WHERE whir.wi_type=1 
					AND whir.wi_status=1 
					AND whir.wi_approvestatus=1  
				group by wh_name
			)c ON a.wh_name=c.wh_name
			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as rqty 
				FROM whir
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				where wi_type=2 
					AND wi_status=1    
				group by wh_name
			)d ON a.wh_name=d.wh_name
			WHERE wh_status=1 AND hm_status=0 AND wh_location = 0"
		);
		if ($q->num_rows() == true){
			return $q->result();
		}
	}

	function SOUTH_data(){
		$q = $this->db->query("
			SELECT 
				a.wh_code,
				a.wh_name,
				CASE WHEN b.sqty IS NULL THEN 0 ELSE b.sqty END AS delin,
				CASE WHEN c.tqty IS NULL THEN 0 ELSE c.tqty END AS delout,
				CASE WHEN d.rqty IS NULL THEN 0 ELSE d.rqty END AS delres
			FROM mwhr a
			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as sqty 
				FROM whir 
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				where wi_type=0 AND wi_status=1 AND wi_approvestatus=1  group by wh_name
			)b ON a.wh_name=b.wh_name

			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as tqty 
				FROM whir 
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				WHERE whir.wi_type=1 
					AND whir.wi_status=1 
					AND whir.wi_approvestatus=1  
				group by wh_name
			)c ON a.wh_name=c.wh_name
			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as rqty 
				FROM whir
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				where wi_type=2 
					AND wi_status=1    
				group by wh_name
			)d ON a.wh_name=d.wh_name
			WHERE wh_status=1 AND hm_status=0 AND wh_location = 1"
		);
		if ($q->num_rows() == true){
			return $q->result();
		}
	}

	function NORTH_data(){
		$q = $this->db->query("
			SELECT 
				a.wh_code,
				a.wh_name,
				CASE WHEN b.sqty IS NULL THEN 0 ELSE b.sqty END AS delin,
				CASE WHEN c.tqty IS NULL THEN 0 ELSE c.tqty END AS delout,
				CASE WHEN d.rqty IS NULL THEN 0 ELSE d.rqty END AS delres
			FROM mwhr a
			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as sqty 
				FROM whir 
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				where wi_type=0 AND wi_status=1 AND wi_approvestatus=1  group by wh_name
			)b ON a.wh_name=b.wh_name

			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as tqty 
				FROM whir 
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				WHERE whir.wi_type=1 
					AND whir.wi_status=1 
					AND whir.wi_approvestatus=1  
				group by wh_name
			)c ON a.wh_name=c.wh_name
			LEFT OUTER JOIN(
				SELECT wh_name,sum(wi_itemqty) as rqty 
				FROM whir
				INNER JOIN ocmt on whir.item_id = ocmt.comm__id
				where wi_type=2 
					AND wi_status=1    
				group by wh_name
			)d ON a.wh_name=d.wh_name
			WHERE wh_status=1 AND hm_status=0 AND wh_location = 2"
		);
		if ($q->num_rows() == true){
			return $q->result();
		}
	}

	function whse_list_insight(){

		$qry = $this->db->get('mwhr');

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function whse_list_insight_edit(){

		$wcode = $this->uri->segment(3);
		$qry = $this->db->get_where('mwhr', array('wh_code'=>$wcode));

		if($qry->num_rows() > 0){
			return $qry->result();
		}

	}

	function inventory_insight_whse_update(){

		$wcode = $this->uri->segment(3);
		$wh_location = $this->input->post('wh_loc');

		$data = array('wh_location'=>$wh_location);

		$this->db->where('wh_code',$wcode);
		$this->db->update('mwhr', $data);

	}

	function get_ims_to_sap_logs() {

		$whs_name = "";
		$whs_name = $this->input->post('whouse');

		$db3 = $this->load->database('db3', TRUE);

		// CHECK IF SELECTED WAREHOUSE IS R2 THEN SELECT ALSO CY'S
		if (strtoupper($whs_name) == "R2 WAREHOUSE") {

				$qry = $db3->query("SELECT 
									T0.wi_refnum 'DO', T0.wi_refnum2 'DRWIS', 
									T0.wh_name 'WhsName',
									T2.ItemName,
									T0.wi_itemqty 'Qty',
									--T1.DocNum 'SAPDoc',
									CASE
										WHEN T1.DocNum IS NULL THEN
										(SELECT DocNum FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE T1.DocNum 
									END 'SAPDoc',
									CASE 
										WHEN CONVERT(date, T1.DocDate) IS NULL THEN
										(SELECT DocDate FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE CONVERT(date, T1.DocDate)
									END 'DelDate',
									--CAST(T1.DocDate AS DATE) 'DelDate',
									CASE 
										WHEN T1.DocStatus = 'O' THEN 'Open'
										ELSE 'Close'
									END 'Status',
									CASE
										WHEN T0.wi_approve = 1 THEN 'Approve'
										ELSE 'NA'
									END 'ApproveStats',
									CASE
										WHEN T0.wi_type = 1 THEN 'Out'
										ELSE 'NO'
									END 'OutStats',
									T0.wi_approvedate 'ApproveDate',
									T0.wi_approvetime 'ApproveTime',
									T0.wi_outdate 'OutDate',
									T0.wi_outtime 'OutTime',
									T0.wi_transno,
									T1.U_RefNo,
									T3.DocStatus 
								FROM IMS_Data T0
								LEFT OUTER JOIN ODLN T1 ON T0.wi_refnum = T1.U_DONo AND T1.U_DRNo = CAST(T0.wi_refnum2 AS VARCHAR) 
								LEFT OUTER JOIN OITM T2 ON '0' + CAST(T0.item_id AS VARCHAR) = T2.ItemCode 
								LEFT OUTER JOIN ORDR T3 ON T0.wi_refnum = T3.U_DoNo
								LEFT OUTER JOIN RDR1 T4 ON T4.DocEntry = T3.DocEntry
								WHERE T0.wi_reftype = 'DO'
								AND T3.CANCELED <> 'Y'
								AND T0.wh_name IN ('R2 WAREHOUSE', 'CY - ABOITIZ', 'CY - AMTC', 'CY - JOCKEY', 'CY - LORENZO', 'CY - OCEANIC') 
								AND T0.wi_refnum IS NOT NULL
								AND T0.wi_status = 1
								ORDER BY T1.DocDate DESC ");

			if ($qry->num_rows()) {
				return $qry->result();
			}

		} else {

				$qry = $db3->query("SELECT 
									T0.wi_refnum 'DO', T0.wi_refnum2 'DRWIS', 
									T0.wh_name 'WhsName',
									T2.ItemName,
									T0.wi_itemqty 'Qty',
									--T1.DocNum 'SAPDoc',
									CASE
										WHEN T1.DocNum IS NULL THEN
										(SELECT DocNum FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE T1.DocNum 
									END 'SAPDoc',
									CASE 
										WHEN CONVERT(date, T1.DocDate) IS NULL THEN
										(SELECT DocDate FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE CONVERT(date, T1.DocDate)
									END 'DelDate',
									--CAST(T1.DocDate AS DATE) 'DelDate',
									CASE 
										WHEN T1.DocStatus = 'O' THEN 'Open'
										ELSE 'Close'
									END 'Status',
									CASE
										WHEN T0.wi_approve = 1 THEN 'Approve'
										ELSE 'NA'
									END 'ApproveStats',
									CASE
										WHEN T0.wi_type = 1 THEN 'Out'
										ELSE 'NO'
									END 'OutStats',
									T0.wi_approvedate 'ApproveDate',
									T0.wi_approvetime 'ApproveTime',
									T0.wi_outdate 'OutDate',
									T0.wi_outtime 'OutTime',
									T0.wi_transno,
									T1.U_RefNo,
									T3.DocStatus 
								FROM IMS_Data T0
								LEFT OUTER JOIN ODLN T1 ON T0.wi_refnum = T1.U_DONo AND T1.U_DRNo = CAST(T0.wi_refnum2 AS VARCHAR) 
								LEFT OUTER JOIN OITM T2 ON '0' + CAST(T0.item_id AS VARCHAR) = T2.ItemCode 
								LEFT OUTER JOIN ORDR T3 ON T0.wi_refnum = T3.U_DoNo
								LEFT OUTER JOIN RDR1 T4 ON T4.DocEntry = T3.DocEntry
								WHERE T0.wi_reftype = 'DO'
								AND T3.CANCELED <> 'Y'
								AND T0.wh_name = '$whs_name' 
								AND T0.wi_refnum IS NOT NULL
								AND T0.wi_status = 1
								ORDER BY T1.DocDate DESC ");

			if ($qry->num_rows()) {
				return $qry->result();
			}

		}

	}

	function get_ims_to_sap_logs_do() {

		$whs_name = "";
		$whs_name = $this->input->post('whouse');

		$do_num = "";
		$do_num = $this->input->post('do_number');

		$db3 = $this->load->database('db3', TRUE);

		if (strtoupper($whs_name) == "R2 WAREHOUSE") {
				$qry = $db3->query("SELECT 
									T0.wi_refnum 'DO', T0.wi_refnum2 'DRWIS', 
									T0.wh_name 'WhsName',
									T2.ItemName,
									T0.wi_itemqty 'Qty',
									--T1.DocNum 'SAPDoc',
									CASE
										WHEN T1.DocNum IS NULL THEN
										(SELECT DocNum FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE T1.DocNum 
									END 'SAPDoc',
									CASE 
										WHEN CAST(T1.DocDate AS DATE) IS NULL THEN
										(SELECT DocDate FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE CAST(T1.DocDate AS DATE)
									END 'DelDate',
									--CAST(T1.DocDate AS DATE) 'DelDate',
									CASE 
										WHEN T1.DocStatus = 'O' THEN 'Open'
										ELSE 'Close'
									END 'Status',
									CASE
										WHEN T0.wi_approve = 1 THEN 'Approve'
										ELSE 'NA'
									END 'ApproveStats',
									CASE
										WHEN T0.wi_type = 1 THEN 'Out'
										ELSE 'NO'
									END 'OutStats',
									T0.wi_approvedate 'ApproveDate',
									T0.wi_approvetime 'ApproveTime',
									T0.wi_outdate 'OutDate',
									T0.wi_outtime 'OutTime',
									T0.wi_transno,
									T1.U_RefNo,
									T3.DocStatus 
								FROM IMS_Data T0
								LEFT OUTER JOIN ODLN T1 ON T0.wi_refnum = T1.U_DONo AND T1.U_DRNo = CAST(T0.wi_refnum2 AS VARCHAR) 
								LEFT OUTER JOIN OITM T2 ON '0' + CAST(T0.item_id AS VARCHAR) = T2.ItemCode 
								LEFT OUTER JOIN ORDR T3 ON T0.wi_refnum = T3.U_DoNo
								LEFT OUTER JOIN RDR1 T4 ON T4.DocEntry = T3.DocEntry
								WHERE T0.wi_reftype = 'DO'
								AND T3.CANCELED <> 'Y'
								AND T0.wh_name IN ('R2 WAREHOUSE', 'CY - ABOITIZ', 'CY - AMTC', 'CY - JOCKEY', 'CY - LORENZO', 'CY - OCEANIC') 
								AND T0.wi_refnum  = '$do_num'
								AND T0.wi_status = 1
								ORDER BY T1.DocDate DESC ");

			if ($qry->num_rows()) {
				return $qry->result();
			}

		} else {

			$qry = $db3->query("SELECT 
									T0.wi_refnum 'DO', T0.wi_refnum2 'DRWIS', 
									T0.wh_name 'WhsName',
									T2.ItemName,
									T0.wi_itemqty 'Qty',
									--T1.DocNum 'SAPDoc',
									CASE
										WHEN T1.DocNum IS NULL THEN
										(SELECT DocNum FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE T1.DocNum 
									END 'SAPDoc',
									CASE 
										WHEN CAST(T1.DocDate AS DATE) IS NULL THEN
										(SELECT DocDate FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE CAST(T1.DocDate AS DATE)
									END 'DelDate',
									--CAST(T1.DocDate AS DATE) 'DelDate',
									CASE 
										WHEN T1.DocStatus = 'O' THEN 'Open'
										ELSE 'Close'
									END 'Status',
									CASE
										WHEN T0.wi_approve = 1 THEN 'Approve'
										ELSE 'NA'
									END 'ApproveStats',
									CASE
										WHEN T0.wi_type = 1 THEN 'Out'
										ELSE 'NO'
									END 'OutStats',
									T0.wi_approvedate 'ApproveDate',
									T0.wi_approvetime 'ApproveTime',
									T0.wi_outdate 'OutDate',
									T0.wi_outtime 'OutTime',
									T0.wi_transno,
									T1.U_RefNo,
									T3.DocStatus 
								FROM IMS_Data T0
								LEFT OUTER JOIN ODLN T1 ON T0.wi_refnum = T1.U_DONo AND T1.U_DRNo = CAST(T0.wi_refnum2 AS VARCHAR) 
								LEFT OUTER JOIN OITM T2 ON '0' + CAST(T0.item_id AS VARCHAR) = T2.ItemCode 
								LEFT OUTER JOIN ORDR T3 ON T0.wi_refnum = T3.U_DoNo
								LEFT OUTER JOIN RDR1 T4 ON T4.DocEntry = T3.DocEntry
								WHERE T0.wi_reftype = 'DO'
								AND T3.CANCELED <> 'Y'
								AND T0.wh_name = '$whs_name'
								AND T0.wi_refnum  = '$do_num'
								AND T0.wi_status = 1
								ORDER BY T1.DocDate DESC ");

			if ($qry->num_rows()) {
				return $qry->result();
			}

		} 

		
		
	}

	function get_ims_to_sap_logs_dr() {

		$whs_name = "";
		$whs_name = $this->input->post('whouse');

		$dr_num = "";
		$dr_num = $this->input->post('dr_number');

		$db3 = $this->load->database('db3', TRUE);

		if (strtoupper($whs_name) == "R2 WAREHOUSE") {

				$qry = $db3->query("SELECT 
									T0.wi_refnum 'DO', T0.wi_refnum2 'DRWIS', 
									T0.wh_name 'WhsName',
									T2.ItemName,
									T0.wi_itemqty 'Qty',
									--T1.DocNum 'SAPDoc',
									CASE
										WHEN T1.DocNum IS NULL THEN
										(SELECT DocNum FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE T1.DocNum 
									END 'SAPDoc',
									CASE 
										WHEN CAST(T1.DocDate AS DATE) IS NULL THEN
										(SELECT DocDate FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE CAST(T1.DocDate AS DATE)
									END 'DelDate',
									--CAST(T1.DocDate AS DATE) 'DelDate',
									CASE 
										WHEN T1.DocStatus = 'O' THEN 'Open'
										ELSE 'Close'
									END 'Status',
									CASE
										WHEN T0.wi_approve = 1 THEN 'Approve'
										ELSE 'NA'
									END 'ApproveStats',
									CASE
										WHEN T0.wi_type = 1 THEN 'Out'
										ELSE 'NO'
									END 'OutStats',
									T0.wi_approvedate 'ApproveDate',
									T0.wi_approvetime 'ApproveTime',
									T0.wi_outdate 'OutDate',
									T0.wi_outtime 'OutTime',
									T0.wi_transno,
									T1.U_RefNo,
									T3.DocStatus 
								FROM IMS_Data T0
								LEFT OUTER JOIN ODLN T1 ON T0.wi_refnum = T1.U_DONo AND T1.U_DRNo = CAST(T0.wi_refnum2 AS VARCHAR) 
								LEFT OUTER JOIN OITM T2 ON '0' + CAST(T0.item_id AS VARCHAR) = T2.ItemCode 
								LEFT OUTER JOIN ORDR T3 ON T0.wi_refnum = T3.U_DoNo
								LEFT OUTER JOIN RDR1 T4 ON T4.DocEntry = T3.DocEntry
								WHERE T0.wi_reftype = 'DO'
								AND T3.CANCELED <> 'Y'
								AND T0.wh_name IN ('R2 WAREHOUSE', 'CY - ABOITIZ', 'CY - AMTC', 'CY - JOCKEY', 'CY - LORENZO', 'CY - OCEANIC') 
								AND T0.wi_refnum2 = '$dr_num'
								AND T0.wi_status = 1
								ORDER BY T1.DocDate DESC ");

			if ($qry->num_rows()) {
				return $qry->result();
			}

		} else {

			$qry = $db3->query("SELECT 
									T0.wi_refnum 'DO', T0.wi_refnum2 'DRWIS', 
									T0.wh_name 'WhsName',
									T2.ItemName,
									T0.wi_itemqty 'Qty',
									--T1.DocNum 'SAPDoc',
									CASE
										WHEN T1.DocNum IS NULL THEN
										(SELECT DocNum FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE T1.DocNum 
									END 'SAPDoc',
									CASE 
										WHEN CAST(T1.DocDate AS DATE) IS NULL THEN
										(SELECT DocDate FROM ODLN a WHERE a.U_DoNo = T0.wi_refnum
											AND T4.TrgetEntry = a.DocEntry)
										ELSE CAST(T1.DocDate AS DATE)
									END 'DelDate',
									--CAST(T1.DocDate AS DATE) 'DelDate',
									CASE 
										WHEN T1.DocStatus = 'O' THEN 'Open'
										ELSE 'Close'
									END 'Status',
									CASE
										WHEN T0.wi_approve = 1 THEN 'Approve'
										ELSE 'NA'
									END 'ApproveStats',
									CASE
										WHEN T0.wi_type = 1 THEN 'Out'
										ELSE 'NO'
									END 'OutStats',
									T0.wi_approvedate 'ApproveDate',
									T0.wi_approvetime 'ApproveTime',
									T0.wi_outdate 'OutDate',
									T0.wi_outtime 'OutTime',
									T0.wi_transno,
									T1.U_RefNo,
									T3.DocStatus 
								FROM IMS_Data T0
								LEFT OUTER JOIN ODLN T1 ON T0.wi_refnum = T1.U_DONo AND T1.U_DRNo = CAST(T0.wi_refnum2 AS VARCHAR) 
								LEFT OUTER JOIN OITM T2 ON '0' + CAST(T0.item_id AS VARCHAR) = T2.ItemCode 
								LEFT OUTER JOIN ORDR T3 ON T0.wi_refnum = T3.U_DoNo
								LEFT OUTER JOIN RDR1 T4 ON T4.DocEntry = T3.DocEntry
								WHERE T0.wi_reftype = 'DO'
								AND T3.CANCELED <> 'Y'
								AND T0.wh_name = '$whs_name' 
								AND T0.wi_refnum2 = '$dr_num'
								AND T0.wi_status = 1
								ORDER BY T1.DocDate DESC ");

			if ($qry->num_rows()) {
				return $qry->result();
			}

		}

	}


	function get_print_number() {

		$trans_no = "";
		$trans_no = $this->uri->segment(3);

		$qry = $this->db->query("SELECT * FROM whir WHERE wi_transno = '$trans_no' ");

		if ($qry->num_rows() > 0) {
			return $qry->result();
		}

	}
	
	function temp_insert_sap_do_data()
	{
		$db3 = $this->load->database('db3', TRUE);
	
		$donum = '148820';

			$db3 = $this->load->database('db3',TRUE);
			$db3->where('a.U_DoNo', (int)$donum);
			$db3->where('a.DocStatus', 'O');
			$db3->where('a.DocType', 'I');
			$db3->where('a.Printed', 'Y');
			$db3->where('c.U_Commodity', '02');
			$db3->where('b.WhsCode', '120202');
			$db3->where('a.DocDate > ', '2016-10-05');

			$db3->select("CAST(a.DocDate AS DATE) AS Dodate,
							  CAST(a.DocDueDate AS DATE) AS SAPExpDeldate,
							  DATEDIFF(dd, CAST(a.DocDate AS DATE), CAST(a.DocDueDate AS DATE)) AS DateGap,
							  a.DocNum AS DONo,
							  a.Series,
							  a.U_DRNo AS DefDocType,

							  CASE 
							  WHEN a.U_TransDate IS NULL
							  THEN '1900-01-01'
							  ELSE CAST(a.U_TransDate AS DATE)
							  END 'TransmitDate',

							  CASE 
							  WHEN a.U_DOTransDate IS NULL
							  THEN '1900-01-01' 
							  ELSE CAST(a.U_DOTransDate AS DATE)
							  END 'U_DOTransDate',

							  a.DocStatus,
							  a.CardCode,
							  a.CardName,

							  CASE
							  WHEN a.NumatCard IS NULL
							  THEN '' 
							  ELSE a.NumatCard
							  END 'PepsiSDRNo',

							  CASE
							  WHEN a.Address2 IS NULL
							  THEN ''
							  ELSE a.Address2
							  END 'Location',

							  CASE
							  WHEN a.U_PONo IS NULL
							  THEN ''
							  ELSE a.U_PONo
							  END 'MotherPO',

							  CASE
							  WHEN a.Comments IS NULL
							  THEN '' 
							  ELSE a.Comments
							  END 'DoRemarks',

							  CASE
							  WHEN a.UpdateDate IS NULL
							  THEN '1900-01-01' 
							  ELSE CAST(a.UpdateDate AS DATE)
							  END 'UpdateDate',

							  a.DocEntry,
							  a.ObjType,
							  
							  b.LineNum,
							  b.WhsCode,

							  CASE
							  WHEN b.U_SAdockey IS NULL
							  THEN '0'
							  ELSE b.U_SAdockey
							  END 'U_SAdockey',

							  CASE
							  WHEN b.U_SADocNo IS NULL
							  THEN '0'
							  ELSE b.U_SADocNo
							  END 'U_SADocNo',

							  CASE
							  WHEN b.U_SADocLine1 IS NULL
							  THEN '0'
							  ELSE b.U_SADocLine1
							  END 'U_SADocLine1',

							  CASE 
							  WHEN b.U_SATransNo IS NULL
							  THEN '0'
							  ELSE b.U_SATransNo
							  END 'U_SATransNo',

							  b.U_SATransTyp,
							  b.ItemCode,
							  b.Dscription,
							  b.UnitMsr,
							  b.Quantity AS DoQty,

							  CASE
							  WHEN b.OpenCreQty IS NULL
							  THEN '0'
							  ELSE b.OpenCreQty
							  END 'B1UnservedQty',

							  (b.Quantity - b.OpenCreQty) AS Served,

							  b.U_PONo AS TransPO,

							  CASE 
							  WHEN b.LineNum IS NULL
							  THEN '' 
							  ELSE b.LineNum
							  END 'BaseLine',

							  CASE
							  WHEN a.DocEntry IS NULL
							  THEN '' 
							  ELSE a.DocEntry
							  END 'BaseEntry',

							  CASE
							  WHEN b.ObjType IS NULL
							  THEN ''
							  ELSE b.ObjType
							  END 'BaseType',

							  d.SlpCode,

							  e.WhsName AS Source,
							  e.U_Whse_Type,

							  f.SlpName AS AE,

							  g.Code AS TruckCode,
							  g.Name AS TruckCo,

							  a.U_DoNo
							  
							");

			$db3->from('ORDR a');
			$db3->join('RDR1 b', 'b.DocEntry = a.DocEntry', 'INNER');
			$db3->join('OITM c', 'c.ItemCode = b.ItemCode', 'INNER');
			$db3->join('OCRD d', 'd.CardCode = a.CardCode','INNER');
			$db3->join('OWHS e', 'e.WhsCode = b.WhsCode', 'INNER');
			$db3->join('OSLP f', 'f.SlpCode = d.SlpCode', 'LEFT');
			$db3->join('dbo.[@TRUCKERS] g', 'g.Code = a.U_Trucker', 'LEFT');
				
			$qry3 = $db3->get();

			if ($qry3->num_rows() > 0) {
				
				return 'GOOD'; die;

					foreach ($qry3->result_array() as $r3) {

						$qry4 = $this->db->where('do_no', $r3['DONo'])
								->get('sap_do_data');

						if ($qry4->num_rows() == 0) {

							$data = array(
								'doc_entry'=>$r3['DocEntry'],
								'obj_type'=>$r3['ObjType'],
								'line_num'=>$r3['LineNum'],
								'do_date'=>$r3['Dodate'],
								'sap_exp_del_date'=>$r3['SAPExpDeldate'],
								'date_gap'=>$r3['DateGap'],
								'do_no'=>$r3['DONo'],
								'series'=>$r3['Series'],
								'def_doc_type'=>$r3['DefDocType'],
								'transmit_date'=>$r3['TransmitDate'],
								'do_transmit_date'=>$r3['U_DOTransDate'],
								'do_stat'=>$r3['DocStatus'],
								'card_code'=>$r3['CardCode'],
								'card_name'=>$r3['CardName'],
								'sdr_no'=>$r3['PepsiSDRNo'],
								'location'=>$r3['Location'],
								'mother_po'=>$r3['MotherPO'],
								'truck_code'=>$r3['TruckCode'],
								'truck_company'=>$r3['TruckCo'],
								'whse_code'=>$r3['WhsCode'],
								'source'=>$r3['Source'],
								'whse_type'=>$r3['U_Whse_Type'],
								'item_code'=>$r3['ItemCode'],
								'item_desc'=>$r3['Dscription'],
								'uom'=>$r3['UnitMsr'],
								'do_qty'=>$r3['DoQty'],
								'b1_udo_qty'=>$r3['B1UnservedQty'],
								'served_qty'=>$r3['Served'],
								'trans_po'=>$r3['TransPO'],
								'base_line'=>$r3['BaseLine'],
								'base_entry'=>$r3['BaseEntry'],
								'base_type'=>$r3['BaseType'],
								'ae_code'=>$r3['SlpCode'],
								'ae'=>$r3['AE'],
								'do_remarks'=>$r3['DoRemarks'],
								'update_date'=>$r3['UpdateDate'],
								'sa_dockey'=>$r3['U_SAdockey'],
								'sa_docno'=>$r3['U_SADocNo'],
								'sa_docline'=>$r3['U_SADocLine1'],
								'sa_transno'=>$r3['U_SATransNo'],
								'sa_transtype'=>$r3['U_SATransTyp'],
								'dbtype'=>'1',
								'u_do'=>$r3['U_DoNo']
							);

							$this->db->insert('sap_do_data', $data);
						}

					}

					return TRUE;

			} else {return "BAD"; die;}
		
	}


}

?>