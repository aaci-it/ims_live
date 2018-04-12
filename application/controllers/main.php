<?php class Main extends CI_Controller{
//USER

	function sample(){
		$this->load->view('t_sorter');
	}

	function index(){
		if($this->session->userdata('logged_in')){
			redirect('main/home');
		}
		else{
			$this->form_validation->set_rules('uname','Username','required');
			$this->form_validation->set_rules('pword','Password','required');
			if($this->form_validation->run() == false){
				$this->load->view('login');
			}
			else{
				$q = $this->usermodel->login_validation();
				if($q){
					$data =array(
						'logged_in' => true,
						'usr_uname' => $this->input->post('uname'),
						'user_lvl' => $q
					);
					$this->usermodel->system_logs();
					$this->session->set_userdata($data);
					redirect('main/home');
				}
				else{
					$data['error']='Username or Password incorrect';
					$this->load->view('login',$data);
				}
			}
		}
	}

	function register(){
		$data['reg_result'] = NULL;
		if($this->input->post('go')){
			$this->load->model('acl_model');
			$data['reg_result'] = $this->acl_model->register(); 
		}
		$this->load->view('register', $data);
	}
	
	function forgot_password(){
		$data['reset'] = NULL;
		if($this->input->post('go')){
			$this->load->model('acl_model');
			$data['reset'] = $this->acl_model->forgot_password(); 
		}
		$this->load->view('forgot_password', $data);
	}
	
	function change_password(){
		$data['reset'] = NULL;
		if($this->input->post('go')){
			$this->load->model('acl_model');
			$data['reset'] = $this->acl_model->change_password(); 
		}
		$this->load->view('change_password', $data);
	}
	
	function logout(){
		$this->session->sess_destroy('userdata');
		redirect('main');
	}
//master data
	function businesspartner_list(){
		if($this->session->userdata('logged_in')){
			$data = array();
			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records']=$this->usermodel->bp_list();
			$this->load->view('masterdata/bp_list',$data);
		}
		else{
			redirect('main');
		}
	}
	function businesspartner_create(){
		if($this->session->userdata('logged_in')){
			$data = array();
			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$this->form_validation->set_rules('bpname','Name','required');
			$this->form_validation->set_rules('bpcode','Code','required');
			if ($this->form_validation->run()==false){
				$this->load->view('masterdata/bp_create',$data);
			}
			else{
				if($q=$this->usermodel->bp_code_validate()){
					$data['duplicate']= 'Duplicate Code';
					$this->load->view('masterdata/bp_create',$data);
				}
				elseif($q=$this->usermodel->bp_name_validate()){
					$data['duplicate']='Duplicate Name';
					$this->load->view('masterdata/bp_create',$data);
				}
				else{
					$this->usermodel->bp_create();
					redirect('main/businesspartner_list');
				}
			}
		}
		else{
			redirect('main');
		}
	}
	function businesspartner_edit(){
		if($this->session->userdata('logged_in')){
			$data = array();
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records']=$this->usermodel->records_get();
			$this->form_validation->set_rules('bpname','Name','required');
			if($this->form_validation->run()==false){
				$this->load->view('masterdata/bp_edit',$data);
			}
			else{
				if($q=$this->usermodel->bp_name_validate()){
					$data['duplicate']='Duplicate Name: '.$this->input->post('bpname');
					$this->load->view('masterdata/bp_edit',$data);
				}
				elseif ($q=$this->usermodel->bp_edit_code_validate()){
					$data['duplicate']='Duplicate Code: '.$this->input->post('bpcode');
					$this->load->view('masterdata/bp_edit',$data);
				}
				else{
					$this->usermodel->bp_edit();
					redirect('main/businesspartner_list');
				}
			}
		}
	}
	//*
	function businesspartner_add_item(){
		if($this->session->userdata('logged_in')){
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			$data=array();
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records']=$this->usermodel->records_get();
			$this->form_validation->set_rules('bpcode','Code','required');
			$this->form_validation->set_rules('bpitem','Item','required');
			if($this->form_validation->run()==false){
				$data['bpitem']=$this->usermodel->bp_list_item();
				$data['item']=$this->usermodel->item_get();
				$this->load->view('masterdata/bp_add_item',$data);
			}
			else{
				if($q=$this->usermodel->bp_item_validation()){
					redirect('main/businesspartner_add_item/'.$get);
				}
				else{
					$this->usermodel->bp_add_item();
					redirect('main/businesspartner_add_item/'.$get);
				}
			}
		}
	}
	function businesspartner_remove_item(){
		if($this->session->userdata('logged_in')){
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-2];
			$data=array();
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records']=$this->usermodel->records_get();
			$this->usermodel->bp_remove_item();
			redirect('main/businesspartner_add_item/'.$get);
		}
	}
	
	function item_list(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records']=$this->usermodel->item_list();
			// $data['check_bal']=$this->usermodel->check_item_balance();

			if($this->uri->segment(3) <> ""){
				if($this->usermodel->check_if_item_has_trans()){
					$data['can_del'] = 1;
				}else{
					$data['can_del'] = 0;
				}
			}

			$this->load->view('masterdata/itm_list',$data);

			// if($this->input->post('del_yes') == 'delete'){
			// 	$this->usermodel->item_delete();
			// 	redirect('main/item_list');
			// }	

		}
		else{
			redirect('main');
		}		
	}

	function item_create(){
		if($this->session->userdata('logged_in')){
			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['item_group']=$this->usermodel->item_group();
			$data['item_type']=$this->usermodel->get_item_type();
			$data['item_subtype']=$this->usermodel->get_item_subtype();
			$this->form_validation->set_rules('icode','Code','required');
			$this->form_validation->set_rules('iname','Name','required');
			$this->form_validation->set_rules('new_grpname','Item Group','required');
			if ($this->form_validation->run() == false){
				$this->load->view('masterdata/itm_create',$data);
			}
			else{
				if($this->usermodel->item_code_validation()){
					$data['duplicate']='Duplicate Item Code';
					$this->load->view('masterdata/itm_create',$data);
				}elseif($this->usermodel->item_code2_validation()){
					$data['duplicate']='Duplicate Item Code 2';
					$this->load->view('masterdata/itm_create',$data);
				}elseif($this->usermodel->item_name_validation()){
					$data['duplicate']='Duplicate Item Name';
					$this->load->view('masterdata/itm_create',$data);
				}else{
					$this->usermodel->item_create();
					// redirect('main/item_list');
					// 1 means success
					redirect('main/item_create/'.'1');
				}
			}
		}
		else{
			redirect('main');
		}
	}

	function item_edit(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records']=$this->usermodel->records_get();
			$data['item_group']=$this->usermodel->item_group();
			$data['item_type']=$this->usermodel->get_item_type();
			$data['item_subtype']=$this->usermodel->get_item_subtype();
			$this->form_validation->set_rules('iname','Description','required');
			$this->form_validation->set_rules('new_grpname','Item Group','required');
			if($this->form_validation->run() == False){
				$this->load->view('masterdata/itm_edit',$data);
			}
			else{
				if($q=$this->usermodel->item_edit_name_validation()){
					$data['duplicate']='Duplicate Name: '.$this->input->post('iname');
					$this->load->view('masterdata/itm_edit',$data);	
				}elseif($q=$this->usermodel->item_edit_code_validation()){
					$data['duplicate']='Duplicate Code: '.$this->input->post('icode');
					$this->load->view('masterdata/itm_edit',$data);	
				}else{
					$this->usermodel->item_edit();
					redirect('main/item_list');
				}
			}

		}
		else{
			redirect('main');
		}
	}

	function item_delete(){
		$this->usermodel->item_delete();
		redirect('main/item_list');
	}

	function wh_delivery_approve_edit(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			
			$del_type = array();
			$del_type = $this->usermodel->get_del_type();
			$data['deltype']=$del_type;

			if($del_type){
				foreach($del_type as $rec){
					$deltype = $rec->wi_deltype;
					$wh_code = $rec->wh_code;
					$wi_id = $rec->wi_id;

					if($deltype == 'Delivery In'){
						redirect('main/wh_delivery_item_in_edit/'.$wh_code.'/'.$wi_id);
					}elseif($deltype == 'Delivery Out'){
						redirect('main/wh_delivery_item_reserve_edit/'.$wh_code.'/'.$wi_id);
					}

				}
			}

			// $this->load->view('main/wh_delivery_approve_edit', $data);

		}
		else{
			redirect('main');
		}
	}

	function warehouse_list(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records'] = $this->usermodel->wh_list();

			if($this->uri->segment(3) <> ""){
				if($this->usermodel->check_whouse_trans()){
					$data['can_delete'] = 1;
				}else{
					$data['can_delete'] = 0;
					$data['wname'] = $this->usermodel->get_wname();
				}
			}

			$this->load->view('masterdata/wh_list', $data);
		}
		else{

			redirect('main');
		}
	}
	function warehouse_create(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();

			$this->form_validation->set_rules('whname','Name','required|trim');
			$this->form_validation->set_rules('whaddr','Address','required|trim');

			if ($this->form_validation->run() == FALSE){
				$this->load->view('masterdata/wh_create',$data);
			}
			else{
				if($this->usermodel->wh_name_validation() == TRUE){
					$data['error'] = 'Warehouse Name already exists';
					$this->load->view('masterdata/wh_create',$data);
				// }elseif($this->usermodel->wh_sapcode_validation()){
				// 	$data['error'] = 'SAP Code already exists';
				// 	$this->load->view('masterdata/wh_create',$data);
				}else{
					$this->usermodel->wh_create();
					// redirect('main/warehouse_list');
					// 1 means success
					redirect('main/warehouse_create/'.'1');
				}
			}
		}
		else{
			redirect('main');
		}
	}
	function warehouse_edit(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records'] = $this->usermodel->records_get();
			$data['etypelist']=$this->usermodel->etype_active_list();
			$data['emailrecords']=$this->usermodel->email_list_active();
			$this->form_validation->set_rules('whname','Name','required');
			$this->form_validation->set_rules('whaddr','Address','required');
			if($this->form_validation->run() == false){
				$this->load->view('masterdata/wh_edit',$data);
			}
			else{
				if($q=$this->usermodel->wh_sapcode_validation_update()){
					$data['duplicate'] = 'SAP Code already encoded';
					$this->load->view('masterdata/wh_edit',$data);
				}
				else{
					$this->usermodel->wh_edit();
					$this->usermodel->wh_add_emailadd();
					redirect('main/warehouse_list');
				}
			}
		}
		else{
			redirect('main');
		}
	}

	function warehouse_delete(){

		if($this->session->userdata('logged_in')){
			$this->usermodel->warehouse_delete();
			redirect('main/warehouse_list');
		}
		else{
			redirect('main');
		}

	}
//main
	function home(){
		if($this->session->userdata('logged_in')){
			if($this->uri->segment(7)){
				$this->usermodel->download_pdf();
			}
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['warehouses']=$this->usermodel->wh_list_active();
			$data['uaccess']=$this->usermodel->access_valid();
			$data['umaccess']=$this->usermodel->access_module_valid();
			$this->load->view('main/home',$data);
		}
		else{
			redirect('main');
		}
	}
	function wh_item(){
		if($this->session->userdata('logged_in')){
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['item']=$this->usermodel->wh_item($get);
			$this->load->view('main/wh_item',$data);
		}
		else{
			redirect('main');
		}
	}
	function wh_delivery(){
		if($this->session->userdata('logged_in')){
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$this->form_validation->set_rules('whitem','Item','required');
			$this->form_validation->set_rules('whqty','Quantity','required|numeric');
			$data['itemrecord']=$this->usermodel->get_item_record($get);
			$data['item']=$this->usermodel->item_get();
			if($tokens[sizeof($tokens)-4] == 'get'){
				$data['itemvalue']= $tokens[sizeof($tokens)-3];
				$data['deftype']=1;
			}
			else{
				$data['itemvalue'] = 'select';
				$data['deftype']='select';
			}
			if ($this->form_validation->run() == false){
				$this->load->view('main/wh_del_add',$data);
				$this->load->view('footer',$data);
			}
			else{
				if ($this->input->post('deltype') == 0){
					$this->usermodel->home_wh_add();
					redirect('main');
				}
				else{
					if ($q=$this->usermodel->wh_itemqty_validation()){
						$data['error']='Invalid Transaction!';
						$this->load->view('main/wh_del_add',$data);
						$this->load->view('footer',$data);
					}
					else{
						$this->usermodel->home_wh_add();
						redirect('main');
					}
				}
			}
		}
		else{
			redirect('main');
		}
	}
	
	function wh_delivery_reserve(){
		if($this->session->userdata('logged_in')){
			$this->load->model('b_model');
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			$get1 = $tokens[sizeof($tokens)-2];

			$tokens2 = explode ('_',current_url());
			$id = $tokens2[sizeof($tokens2)-1];


			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['warehouse']=$this->usermodel->get_warehouse($get);
			$data['reserverecord']=$this->usermodel->wh_delivery_reserve_list_active($get);
			$data['item']=$this->usermodel->item_get();
			if ($tokens[sizeof($tokens)-2] == 'update'){
				$this->load->view('main/wh_del_reserve_out',$data);
			}elseif ($tokens[sizeof($tokens)-3] == 'cancel'){
				
				$this->usermodel->wh_delivery_reserve_cancel($get);
				redirect('main');
			}elseif ($tokens[sizeof($tokens)-3] == 'approve'){
				// echo '<script>alert("You Have Successfully updated this Record!");</script>';
				$this->usermodel->wh_delivery_reserve_approve($get);

				$this->usermodel->wh_delivery_reserve_approve_link();

				// $this->b_model->send_delivery_in();

				// DSPR GENERATION SCRIPT
				date_default_timezone_set("Asia/Manila");
				$datetime = date('Y-m-d h:i:s');

				$rec = array();
				$rec = $this->usermodel->check_deltype();

				if($rec){
					foreach($rec as $rcd){
						if($rcd->wi_deltype == 'Delivery In'){

							$this->usermodel->di_appr();	

							$din_rec = array(
								'wi_id'=>$rcd->wi_id,
								'item_id'=>$rcd->item_id,
								'wh_name'=>$rcd->wh_name,
								'deltype'=>$rcd->wi_deltype,
								'itemqty'=>$rcd->wi_itemqty,
								'posting_date'=>$rcd->deldate,
								'trans_datetime'=>$datetime,
								'reftype'=>$rcd->wi_reftype,
								'refnum'=>$rcd->wi_refnum,
								'reftype2'=>$rcd->wi_reftype2,
								'refnum2'=>$rcd->wi_refnum2

							);

							$this->usermodel->dspr_din($din_rec);

							// CREATE JSON FILE IF RETURN FROM CUSTOMER
							if($rcd->wi_subtype == 'DI_04'){
								//CREATION OF RETURN JSON FILE
								$whse_json_id = $rcd->wi_id;

								$json_record = array();
								$json_record = $this->usermodel->get_sap_return_to_json($whse_json_id);

								$trans_type = "";
								$db_type = "";

								if($json_record){
									foreach($json_record as $jrec){

										$web_whse_rec = array(
													'web_whse_code'=>$jrec->WebWhsCode,
													'web_whse_name'=>$jrec->WebWhsName
												);
										$sap_whse_record = array();
										$sap_whse_record = $this->usermodel->get_sap_whse_record_return($web_whse_rec);

										$sap_whse_code = "";
										$sap_whse_name  = "";

										if($sap_whse_record){
											foreach($sap_whse_record as $srec){
												$sap_whse_code = $srec->SAP_WhsCode;
												$sap_whse_name  = $srec->SAP_WhsName;
											}
										}else{
											$sap_whse_code = $jrec->CardCode;
											$sap_whse_name  = $jrec->source;
										}

										$trans_type = $jrec->wi_dtcode;
										$db_type = $jrec->dbtype;

										$json_rec = array(
											'WebDocKey'=>$jrec->wi_id,
											'WebDocNum'=>$jrec->wi_transno,
											'DODate'=>$jrec->do_date,
											'SAPExpDelDate'=>$jrec->sap_exp_del_date,
											'DateGap'=>$jrec->date_gap,
											'DONo'=>$jrec->do_no,
											'Series'=>$jrec->series,
											'DefDocType'=>$jrec->def_doc_type,
											'TransmitDate'=>$jrec->transmit_date,
											'DOTransDate'=>$jrec->do_transmit_date,
											'DOStat'=>$jrec->do_stat,
											'CardCode'=>$jrec->card_code,
											'CardName'=>$jrec->card_name,
											'PepsiDRNo'=>$jrec->sdr_no,
											'Location'=>$jrec->location,
											'MotherPO'=>$jrec->mother_po,
											'TruckCode'=>$jrec->truck_code,
											'TruckCo'=>$jrec->truck_company,
											'WhsCode'=>$jrec->whse_code,
											'Source'=>$jrec->source,
											'U_Whse_Type'=>$jrec->whse_type,
											'U_SADocKey'=>$jrec->sa_dockey,
											'U_SADocNo'=>$jrec->sa_docno,
											'U_SADocLine1'=>$jrec->sa_docline,
											'U_SATransNo'=>$jrec->sa_transno,
											'U_SATransTyp'=>$jrec->sa_transtype,
											'ItemCode'=>$jrec->item_code,
											'Dscription'=>$jrec->item_desc,
											'UnitMsr'=>$jrec->uom,
											'DOQty'=>$jrec->do_qty,
											'B1UnservedQty'=>$jrec->b1_udo_qty,
											'Served'=>$jrec->served_qty,
											'TransPO'=>$jrec->trans_po,
											'SlpCode'=>$jrec->ae_code,
											'AE'=>$jrec->ae,
											'DORemarks'=>$jrec->do_remarks,
											'UpdateDate'=>$jrec->update_date,
											'Quantity'=>$jrec->wi_itemqty,
											'PostDate'=>$jrec->deldate,
											'BaseLine'=>$jrec->base_line,
											'BaseEntry'=>$jrec->base_entry,
											'BaseType'=>$jrec->base_type,
											'WebRemarks'=>$jrec->wi_remarks,
											'Transtype'=>$jrec->wi_dtcode,
											'BatchCode'=>$jrec->pbatch_code,
											'WebWhsCode'=> $jrec->WebWhsCode,
											'WebWhsName'=> $jrec->WebWhsName,
											'SAPWhsCode'=> $sap_whse_code,
											'SAPWhsName'=> $sap_whse_name,
											'Remarks'=> $jrec->remarks,
											'DRRef'=> $jrec->drref,
											'U_DO'=> $jrec->u_do
										);
									}
								}

								$dorec['Delivery_In'][] = $json_rec;
								$drec = json_encode($dorec);

								// WRITE JSON FILE
								date_default_timezone_set("Asia/Manila");
								$datetime = date('m-d-Y h:i:s');

								$dt = str_replace('-', '', $datetime); // Replaces all spaces with hyphens.
								$dt2 = str_replace(' ', '_', $dt); // Replaces all spaces with hyphens.
								$dt3 = str_replace(':', '', $dt2); // Replaces all spaces with hyphens.

								//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/inventory_jayson/JSON_FILES/DO_'.$trans_type.'_'.$dt3.'.json', $drec);

								if($db_type	== '0'){
									$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/RR_'.$trans_type.'_'.$dt3.'_OLD.json';
								}elseif($db_type == '1'){
									$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/RR_'.$trans_type.'_'.$dt3.'_NEW.json';
								}else{
									$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/RR_'.$trans_type.'_'.$dt3.'.json';
								}

								// if($db_type == '1'){
								// 	$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/RR_'.$trans_type.'_'.$dt3.'.json';
								
								// 	//Store in the filesystem.
					   //              $fp = fopen($filepath , "w");
					   //              fwrite($fp, $drec);
					   //              fclose($fp);
								// }

								//Store in the filesystem.
					            $fp = fopen($filepath , "w");
					            fwrite($fp, $drec);
					            fclose($fp);

							}

							if($rcd->wi_reftype == 'RR' OR $rcd->wi_reftype2 == 'RR'){

								$din_rec_rr = array(
									'wi_id'=>$rcd->wi_id,
									'item_id'=>$rcd->item_id,
									'wh_name'=>$rcd->wh_name,
									'deltype'=>$rcd->wi_deltype,
									'itemqty'=>$rcd->wi_itemqty,
									'posting_date'=>$rcd->deldate,
									'trans_datetime'=>$datetime,
									'reftype'=>$rcd->wi_reftype,
									'refnum'=>$rcd->wi_refnum,
									'reftype2'=>$rcd->wi_reftype2,
									'refnum2'=>$rcd->wi_refnum2 

								);

								$this->usermodel->dspr_din_rr($din_rec_rr);

							}elseif($rcd->wi_reftype == 'ITO' OR $rcd->wi_reftype2 == 'ITO'){

								$din_rec_ito = array(
									'wi_id'=>$rcd->wi_id,
									'item_id'=>$rcd->item_id,
									'wh_name'=>$rcd->wh_name,
									'deltype'=>$rcd->wi_deltype,
									'doqty'=>$rcd->wi_doqty,
									'itemqty'=>$rcd->wi_itemqty,
									'itoqty'=>$rcd->wi_doqty - $rcd->wi_itemqty,
									'posting_date'=>$rcd->deldate,
									'trans_datetime'=>$datetime,
									'reftype'=>$rcd->wi_reftype,
									'refnum'=>$rcd->wi_refnum,
									'reftype2'=>$rcd->wi_reftype2,
									'refnum2'=>$rcd->wi_refnum2 

								);

								$this->usermodel->dspr_din_ito($din_rec_ito);

							}elseif($rcd->wi_reftype == 'WAR' OR $rcd->wi_reftype2 == 'WAR'){

								$din_rec_war = array(
									'wi_id'=>$rcd->wi_id,
									'item_id'=>$rcd->item_id,
									'wh_name'=>$rcd->wh_name,
									'deltype'=>$rcd->wi_deltype,
									'doqty'=>$rcd->wi_doqty,
									'itemqty'=>$rcd->wi_itemqty,
									'itoqty'=>$rcd->wi_doqty - $rcd->wi_itemqty,
									'posting_date'=>$rcd->deldate,
									'trans_datetime'=>$datetime,
									'reftype'=>$rcd->wi_reftype,
									'refnum'=>$rcd->wi_refnum,
									'reftype2'=>$rcd->wi_reftype2,
									'refnum2'=>$rcd->wi_refnum2 

								);

								$this->usermodel->dspr_din_war($din_rec_war);

							}

						}elseif($rcd->wi_deltype == 'Delivery Out'){

							$this->usermodel->do_appr();	

							$dout_rec_approve = array(
								'wi_id'=>$rcd->wi_id,
								'item_id'=>$rcd->item_id,
								'wh_name'=>$rcd->wh_name,
								'deltype'=>$rcd->wi_deltype,
								'itemqty'=>$rcd->wi_itemqty,
								'doqty'=>$rcd->wi_doqty,
								'posting_date'=>$rcd->deldate,
								'trans_datetime'=>$datetime,
								'reftype'=>$rcd->wi_reftype,
								'refnum'=>$rcd->wi_refnum,
								'reftype2'=>$rcd->wi_reftype2,
								'refnum2'=>$rcd->wi_refnum2

							);

							$this->usermodel->dspr_dout_approve($dout_rec_approve);

						}elseif($rcd->wi_deltype == 'Material Management' AND $rcd->wi_type == 0){

							$din_rec = array(
								'wi_id'=>$rcd->wi_id,
								'item_id'=>$rcd->item_id,
								'wh_name'=>$rcd->wh_name,
								'deltype'=>$rcd->wi_deltype,
								'itemqty'=>$rcd->wi_itemqty,
								'posting_date'=>$rcd->deldate,
								'trans_datetime'=>$datetime,
								'reftype'=>$rcd->wi_reftype,
								'refnum'=>$rcd->wi_refnum,
								'reftype2'=>$rcd->wi_reftype2,
								'refnum2'=>$rcd->wi_refnum2

							);

							$this->usermodel->dspr_din($din_rec);

						}elseif($rcd->wi_deltype == 'Material Management' AND $rcd->wi_type == 2){

							$dout_rec_approve = array(
								'wi_id'=>$rcd->wi_id,
								'item_id'=>$rcd->item_id,
								'wh_name'=>$rcd->wh_name,
								'deltype'=>$rcd->wi_deltype,
								'itemqty'=>$rcd->wi_itemqty,
								'doqty'=>$rcd->wi_doqty,
								'posting_date'=>$rcd->deldate,
								'trans_datetime'=>$datetime,
								'reftype'=>$rcd->wi_reftype,
								'refnum'=>$rcd->wi_refnum,
								'reftype2'=>$rcd->wi_reftype2,
								'refnum2'=>$rcd->wi_refnum2

							);

							$this->usermodel->dspr_dout_approve($dout_rec_approve);

						}

						
					}
				}

				$wh_name = array();
				$wh_name = $this->usermodel->get_wh_name();
				$data['wname']=$wh_name;

				if($wh_name){
					foreach($wh_name as $rec){
						$warehouse_name = $rec->wh_name;
					}
				}

				redirect('main/wh_delivery_approve_list/'.$warehouse_name);
			}elseif ($tokens[sizeof($tokens)-3] == 'approve_mm'){
				$this->usermodel->wh_delivery_reserve_approve($get);
	
				// DSPR GENERATION SCRIPT
				date_default_timezone_set("Asia/Manila");
				$datetime = date('Y-m-d h:i:s');

				$rec = array();
				$rec = $this->usermodel->check_deltype();

				if($rec){
					foreach($rec as $rcd){
						if($rcd->wi_deltype == 'Delivery In'){

							$din_rec = array(
								'wi_id'=>$rcd->wi_id,
								'item_id'=>$rcd->item_id,
								'wh_name'=>$rcd->wh_name,
								'deltype'=>$rcd->wi_deltype,
								'itemqty'=>$rcd->wi_itemqty,
								'posting_date'=>$rcd->deldate,
								'trans_datetime'=>$datetime,
								'reftype'=>$rcd->wi_reftype,
								'refnum'=>$rcd->wi_refnum,
								'reftype2'=>$rcd->wi_reftype2,
								'refnum2'=>$rcd->wi_refnum2

							);

							$this->usermodel->dspr_din($din_rec);

							if($rcd->wi_reftype == 'RR' OR $rcd->wi_reftype2 == 'RR'){

								$din_rec_rr = array(
									'wi_id'=>$rcd->wi_id,
									'item_id'=>$rcd->item_id,
									'wh_name'=>$rcd->wh_name,
									'deltype'=>$rcd->wi_deltype,
									'itemqty'=>$rcd->wi_itemqty,
									'posting_date'=>$rcd->deldate,
									'trans_datetime'=>$datetime,
									'reftype'=>$rcd->wi_reftype,
									'refnum'=>$rcd->wi_refnum,
									'reftype2'=>$rcd->wi_reftype2,
									'refnum2'=>$rcd->wi_refnum2 

								);

								$this->usermodel->dspr_din_rr($din_rec_rr);

							}elseif($rcd->wi_reftype == 'ITO' OR $rcd->wi_reftype2 == 'ITO'){

								$din_rec_ito = array(
									'wi_id'=>$rcd->wi_id,
									'item_id'=>$rcd->item_id,
									'wh_name'=>$rcd->wh_name,
									'deltype'=>$rcd->wi_deltype,
									'doqty'=>$rcd->wi_doqty,
									'itemqty'=>$rcd->wi_itemqty,
									'itoqty'=>$rcd->wi_doqty - $rcd->wi_itemqty,
									'posting_date'=>$rcd->deldate,
									'trans_datetime'=>$datetime,
									'reftype'=>$rcd->wi_reftype,
									'refnum'=>$rcd->wi_refnum,
									'reftype2'=>$rcd->wi_reftype2,
									'refnum2'=>$rcd->wi_refnum2 

								);

								$this->usermodel->dspr_din_ito($din_rec_ito);

							}elseif($rcd->wi_reftype == 'WAR' OR $rcd->wi_reftype2 == 'WAR'){

								$din_rec_war = array(
									'wi_id'=>$rcd->wi_id,
									'item_id'=>$rcd->item_id,
									'wh_name'=>$rcd->wh_name,
									'deltype'=>$rcd->wi_deltype,
									'doqty'=>$rcd->wi_doqty,
									'itemqty'=>$rcd->wi_itemqty,
									'itoqty'=>$rcd->wi_doqty - $rcd->wi_itemqty,
									'posting_date'=>$rcd->deldate,
									'trans_datetime'=>$datetime,
									'reftype'=>$rcd->wi_reftype,
									'refnum'=>$rcd->wi_refnum,
									'reftype2'=>$rcd->wi_reftype2,
									'refnum2'=>$rcd->wi_refnum2 

								);

								$this->usermodel->dspr_din_war($din_rec_war);

							}

						}elseif($rcd->wi_deltype == 'Delivery Out'){

							$dout_rec_approve = array(
								'wi_id'=>$rcd->wi_id,
								'item_id'=>$rcd->item_id,
								'wh_name'=>$rcd->wh_name,
								'deltype'=>$rcd->wi_deltype,
								'itemqty'=>$rcd->wi_itemqty,
								'doqty'=>$rcd->wi_doqty,
								'posting_date'=>$rcd->deldate,
								'trans_datetime'=>$datetime,
								'reftype'=>$rcd->wi_reftype,
								'refnum'=>$rcd->wi_refnum,
								'reftype2'=>$rcd->wi_reftype2,
								'refnum2'=>$rcd->wi_refnum2

							);

							$this->usermodel->dspr_dout_approve($dout_rec_approve);

						}elseif($rcd->wi_deltype == 'Material Management' AND $rcd->wi_type == 0){

							$din_rec = array(
								'wi_id'=>$rcd->wi_id,
								'item_id'=>$rcd->item_id,
								'wh_name'=>$rcd->wh_name,
								'deltype'=>$rcd->wi_deltype,
								'itemqty'=>$rcd->wi_itemqty,
								'posting_date'=>$rcd->deldate,
								'trans_datetime'=>$datetime,
								'reftype'=>$rcd->wi_reftype,
								'refnum'=>$rcd->wi_refnum,
								'reftype2'=>$rcd->wi_reftype2,
								'refnum2'=>$rcd->wi_refnum2

							);

							$this->usermodel->dspr_din($din_rec);

						}elseif($rcd->wi_deltype == 'Material Management' AND $rcd->wi_type == 2){

							$dout_rec_approve = array(
								'wi_id'=>$rcd->wi_id,
								'item_id'=>$rcd->item_id,
								'wh_name'=>$rcd->wh_name,
								'deltype'=>$rcd->wi_deltype,
								'itemqty'=>$rcd->wi_itemqty,
								'doqty'=>$rcd->wi_doqty,
								'posting_date'=>$rcd->deldate,
								'trans_datetime'=>$datetime,
								'reftype'=>$rcd->wi_reftype,
								'refnum'=>$rcd->wi_refnum,
								'reftype2'=>$rcd->wi_reftype2,
								'refnum2'=>$rcd->wi_refnum2

							);

							$this->usermodel->dspr_dout_approve($dout_rec_approve);

						}

						
					}
				}

				$wh_name = array();
				$wh_name = $this->usermodel->get_wh_name();
				$data['wname']=$wh_name;

				if($wh_name){
					foreach($wh_name as $rec){
						$warehouse_name = $rec->wh_name;
					}
				}

				redirect('main/wh_delivery_approve_mm_list/'.$warehouse_name);

			}else{
				//redirect('main/wh_delivery_out');
				//$this->usermodel->wh_delivery_reserve_out($get);
				//redirect('main');
			}
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_cancel(){
		if($this->session->userdata('logged_in')){
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			$get1 = $tokens[sizeof($tokens)-2];
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$this->form_validation->set_rules('remarks','Remarks','required');
			if($this->form_validation->run() == false){
				$this->load->view('main/wh_del_cancel',$data);
			}
			else{
				$this->usermodel->wh_delivery_reserve_cancel($get);
				$this->usermodel->wh_delivery_reserve_cancel_link();

				$wh_name = array();
				$wh_name = $this->usermodel->get_wh_name();
				$data['wname']=$wh_name;

				if($wh_name){
					foreach($wh_name as $rec){
						$warehouse_name = $rec->wh_name;
					}
				}

				redirect('main/wh_delivery_cancel_list/'.$warehouse_name);
			}
		}
		else{
			redirect('main');
		}
	}

	// FUNCTIONS MADE BY SIR ARMAN
	// function wh_delivery_out(){ //write 
	// 	if($this->session->userdata('logged_in')){
	// 		$data['user'] = $this->usermodel->signin_user();
	// 		$data['modaccess']=$this->usermodel->access_module_approve();
	// 		$data['delinfo']=$this->usermodel->out_del_info();
	// 		$data['DR'] = NULL;
	// 		$data['error'] = '';
	// 		$this->form_validation->set_rules('dr_num','DR Number','required');
	// 		if($this->form_validation->run() == false){
	// 			$this->load->view('main/wh_del_out',$data);
	// 		}
	// 		else{
	// 			if($this->usermodel->wh_delivery_reserve_out() == 'DR_E'){
	// 				$data['DR'] = 'DR already existed!';
	// 				$this->load->view('main/wh_del_out',$data);
	// 			}
	// 			else{
	// 				//redirect('main');
	// 				//check the source if came from the release of delivery
	// 				$three = rand(1, 20);
	// 				$four = rand(1, 20);
	// 				$five = rand(1, 20);
	// 				$six = rand(1, 20);
					
	// 				$refresh = 'main/home/'.$three.'/'.$four.'/'.$five.'/'.$six.'/'.$this->input->post('id');
	// 				redirect($refresh);	
	// 			}
	// 		}
	// 	}
	// 	else{
	// 		redirect('main');
	// 	}
	// }
	
	function wh_delivery_out(){
		if($this->session->userdata('logged_in')){
			$this->load->model('b_model');
			
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['delinfo']=$this->usermodel->out_del_info();
			// $this->form_validation->set_rules('refname','Reference Name','required');
			// if($this->form_validation->run() == false){
			// 	$this->load->view('main/wh_del_out',$data);
			// }
			// else{
				$this->usermodel->wh_delivery_reserve_out();
				$this->usermodel->wh_delivery_reserve_out_link();
				// $this->b_model->send_delivery_out();
				// $this->b_model->send_delivery_out_ae();


				// DSPR GENERATION SCRIPT
				date_default_timezone_set("Asia/Manila");
				$datetime = date('Y-m-d h:i:s');

				$rec = array();
				$rec = $this->usermodel->check_deltype();

				if($rec){
					foreach($rec as $rcd){
						if($rcd->wi_deltype == 'Delivery Out'){

							$this->usermodel->do_out();

							$dout_rec = array(
								'wi_id'=>$rcd->wi_id,
								'item_id'=>$rcd->item_id,
								'wh_name'=>$rcd->wh_name,
								'deltype'=>$rcd->wi_deltype,
								'itemqty'=>$rcd->wi_itemqty,
								'doqty'=>$rcd->wi_doqty,
								'udoqty'=>$rcd->wi_doqty - $rcd->wi_itemqty,
								'trans_datetime'=>$datetime,
								'posting_date'=>$rcd->deldate,
								'reftype'=>$rcd->wi_reftype,
								'refnum'=>$rcd->wi_refnum,
								'reftype2'=>$rcd->wi_reftype2,
								'refnum2'=>$rcd->wi_refnum2
							);

							$this->usermodel->dspr_dout($dout_rec);

							// CREATE JSON FILES
							// $whse_json_id = $rcd->wi_id;

							// $json_record = array();
							// $json_record = $this->usermodel->get_sap_do_to_json($whse_json_id);

							// $trans_type = "";

							// if($json_record){
							// 	foreach($json_record as $jrec){

							// 		$web_whse_rec = array(
							// 					'web_whse_code'=>$jrec->WebWhsCode,
							// 					'web_whse_name'=>$jrec->WebWhsName
							// 				);
							// 		$sap_whse_record = array();
							// 		$sap_whse_record = $this->usermodel->get_sap_whse_record($web_whse_rec);

							// 		$sap_whse_code = "";
							// 		$sap_whse_name  = "";

							// 		if($jrec->wi_dtcode == "DT_02"){
							// 			if($sap_whse_record){
							// 				foreach($sap_whse_record as $srec){
							// 					$sap_whse_code = $srec->SAP_WhsCode;
							// 					$sap_whse_name  = $srec->SAP_WhsName;
							// 				}
							// 			}else{
							// 				$sap_whse_code = $jrec->mwh_code;
							// 				$sap_whse_name  = $jrec->awh_name;
							// 			}
							// 		}else{
							// 			if($sap_whse_record){
							// 				foreach($sap_whse_record as $srec){
							// 					$sap_whse_code = $srec->SAP_WhsCode;
							// 					$sap_whse_name  = $srec->SAP_WhsName;
							// 				}
							// 			}else{
							// 				$sap_whse_code = $jrec->CardCode;
							// 				$sap_whse_name  = $jrec->source;
							// 			}
							// 		}

							// 		$trans_type = $jrec->wi_dtcode;

							// 		$json_rec = array(
							// 			'WebDocKey'=>$jrec->wi_id,
							// 			'WebDocNum'=>$jrec->wi_transno,
							// 			'DODate'=>$jrec->do_date,
							// 			'SAPExpDelDate'=>$jrec->sap_exp_del_date,
							// 			'DateGap'=>$jrec->date_gap,
							// 			'DONo'=>$jrec->do_no,
							// 			'Series'=>$jrec->series,
							// 			'DefDocType'=>$jrec->def_doc_type,
							// 			'TransmitDate'=>$jrec->transmit_date,
							// 			'DOTransDate'=>$jrec->do_transmit_date,
							// 			'DOStat'=>$jrec->do_stat,
							// 			'CardCode'=>$jrec->card_code,
							// 			'CardName'=>$jrec->card_name,
							// 			'PepsiDRNo'=>$jrec->sdr_no,
							// 			'Location'=>$jrec->location,
							// 			'MotherPO'=>$jrec->mother_po,
							// 			'TruckCode'=>$jrec->truck_code,
							// 			'TruckCo'=>$jrec->truck_company,
							// 			'WhsCode'=>$jrec->whse_code,
							// 			'Source'=>$jrec->source,
							// 			'U_Whse_Type'=>$jrec->whse_type,
							// 			'U_SADocKey'=>$jrec->sa_dockey,
							// 			'U_SADocNo'=>$jrec->sa_docno,
							// 			'U_SADocLine1'=>$jrec->sa_docline,
							// 			'U_SATransNo'=>$jrec->sa_transno,
							// 			'U_SATransTyp'=>$jrec->sa_transtype,
							// 			'ItemCode'=>$jrec->item_code,
							// 			'Dscription'=>$jrec->item_desc,
							// 			'UnitMsr'=>$jrec->uom,
							// 			'DOQty'=>$jrec->do_qty,
							// 			'B1UnservedQty'=>$jrec->b1_udo_qty,
							// 			'Served'=>$jrec->served_qty,
							// 			'TransPO'=>$jrec->trans_po,
							// 			'SlpCode'=>$jrec->ae_code,
							// 			'AE'=>$jrec->ae,
							// 			'DORemarks'=>$jrec->do_remarks,
							// 			'UpdateDate'=>$jrec->update_date,
							// 			'Quantity'=>$jrec->wi_itemqty,
							// 			'PostDate'=>$jrec->deldate,
							// 			'BaseLine'=>$jrec->base_line,
							// 			'BaseEntry'=>$jrec->base_entry,
							// 			'BaseType'=>$jrec->base_type,
							// 			'WebRemarks'=>$jrec->wi_remarks,
							// 			'Transtype'=>$jrec->wi_dtcode,
							// 			'BatchCode'=>$jrec->pbatch_code,
							// 			'WebWhsCode'=> $jrec->WebWhsCode,
							// 			'WebWhsName'=> $jrec->WebWhsName,
							// 			'SAPWhsCode'=> $sap_whse_code,
							// 			'SAPWhsName'=> $sap_whse_name,
							// 			'Remarks'=> $jrec->remarks,
							// 			'DRRef'=> $jrec->drref,
							// 			'U_DO'=> $jrec->u_do
							// 		);
							// 	}
							// }

							// $dorec['Delivery_Out'][] = $json_rec;
							// $drec = json_encode($dorec);

							// // WRITE JSON FILE
							// date_default_timezone_set("Asia/Manila");
							// $datetime = date('m-d-Y h:i:s');

							// $dt = str_replace('-', '', $datetime); // Replaces all spaces with hyphens.
							// $dt2 = str_replace(' ', '_', $dt); // Replaces all spaces with hyphens.
							// $dt3 = str_replace(':', '', $dt2); // Replaces all spaces with hyphens.

							// //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/inventory_jayson/JSON_FILES/DO_'.$trans_type.'_'.$dt3.'.json', $drec);

			    //             $filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/DO_'.$trans_type.'_'.$dt3.'.json';
							// // $filepath = '//192.168.1.253/C/Upload Files/DO_'.$trans_type.'_'.$dt3.'.json';
			    //             //Store in the filesystem.
			    //             $fp = fopen($filepath , "w");
			    //             fwrite($fp, $drec);
			    //             fclose($fp);

							// GENERATE TEXT FILE
							// $myFile = "DO_".$dt3.".txt";
							// $fh = fopen($myFile, 'w');
							// $dorec = array();f
							// $dorec[] = 'wi_id';
							// $dorec[] = 'wh_name';
							// fputcsv($fh, $dorec."\n"); 

							// $dorec = $this->usermodel->get_sample_do();

							// foreach($dorec as $row){
							// 	fputcsv($fh, $row, "\t");
							// }

							// fclose($fh);

							//DELETE THE APPROVE D_OUT 
							$whouse_id = array(
								'wi_id'=>$rcd->wi_id
							);

							$this->usermodel->check_if_dout_approve_exist($whouse_id);

							if($rcd->wi_reftype == 'WIS' OR $rcd->wi_reftype2 == 'WIS'){

								$dout_rec_wis = array(
									'wi_id'=>$rcd->wi_id,
									'item_id'=>$rcd->item_id,
									'wh_name'=>$rcd->wh_name,
									'deltype'=>$rcd->wi_deltype,
									'itemqty'=>$rcd->wi_itemqty,
									'trans_datetime'=>$datetime,
									'posting_date'=>$rcd->deldate,
									'reftype'=>$rcd->wi_reftype,
									'refnum'=>$rcd->wi_refnum,
									'reftype2'=>$rcd->wi_reftype2,
									'refnum2'=>$rcd->wi_refnum2
								);

								$this->usermodel->dspr_dout_wis($dout_rec_wis);

								// CREATE JSON FILE
								$whse_json_id = $rcd->wi_id;

								$json_record = array();
								$json_record = $this->usermodel->get_sap_do_to_json($whse_json_id);

								$trans_type = "";
								$db_type = "";
								$webkey = "";
								$webnum = "";

								if($json_record){
									foreach($json_record as $jrec){

										$web_whse_rec = array(
													'web_whse_code'=>$jrec->WebWhsCode,
													'web_whse_name'=>$jrec->WebWhsName
												);
										$sap_whse_record = array();
										$sap_whse_record = $this->usermodel->get_sap_whse_record($web_whse_rec);

										$sap_whse_code = "";
										$sap_whse_name  = "";

										if($jrec->wi_dtcode == "DT_02"){
											if($sap_whse_record){
												foreach($sap_whse_record as $srec){
													$sap_whse_code = $srec->SAP_WhsCode;
													$sap_whse_name  = $srec->SAP_WhsName;
												}
											}else{
												$sap_whse_code = $jrec->mwh_code;
												$sap_whse_name  = $jrec->awh_name;
											}
										}else{
											if($sap_whse_record){
												foreach($sap_whse_record as $srec){
													$sap_whse_code = $srec->SAP_WhsCode;
													$sap_whse_name  = $srec->SAP_WhsName;
												}
											}else{
												$sap_whse_code = $jrec->CardCode;
												$sap_whse_name  = $jrec->source;
											}
										}

										$trans_type = $jrec->wi_dtcode;
										$db_type = $jrec->dbtype;
										$webkey = $jrec->wi_id;
										$webnum = $jrec->wi_transno;

										$json_rec = array(
											'WebDocKey'=>$jrec->wi_id,
											'WebDocNum'=>$jrec->wi_transno,
											'DODate'=>$jrec->do_date,
											'SAPExpDelDate'=>$jrec->sap_exp_del_date,
											'DateGap'=>$jrec->date_gap,
											'DONo'=>$jrec->do_no,
											'Series'=>$jrec->series,
											'DefDocType'=>$jrec->def_doc_type,
											'TransmitDate'=>$jrec->transmit_date,
											'DOTransDate'=>$jrec->do_transmit_date,
											'DOStat'=>$jrec->do_stat,
											'CardCode'=>$jrec->card_code,
											'CardName'=>$jrec->card_name,
											'PepsiDRNo'=>$jrec->sdr_no,
											'Location'=>$jrec->location,
											'MotherPO'=>$jrec->mother_po,
											'TruckCode'=>$jrec->truck_code,
											'TruckCo'=>$jrec->truck_company,
											'WhsCode'=>$jrec->whse_code,
											'Source'=>$jrec->source,
											'U_Whse_Type'=>$jrec->whse_type,
											'U_SADocKey'=>$jrec->sa_dockey,
											'U_SADocNo'=>$jrec->sa_docno,
											'U_SADocLine1'=>$jrec->sa_docline,
											'U_SATransNo'=>$jrec->sa_transno,
											'U_SATransTyp'=>$jrec->sa_transtype,
											'ItemCode'=>$jrec->item_code,
											'Dscription'=>$jrec->item_desc,
											'UnitMsr'=>$jrec->uom,
											'DOQty'=>$jrec->do_qty,
											'B1UnservedQty'=>$jrec->b1_udo_qty,
											'Served'=>$jrec->served_qty,
											'TransPO'=>$jrec->trans_po,
											'SlpCode'=>$jrec->ae_code,
											'AE'=>$jrec->ae,
											'DORemarks'=>$jrec->do_remarks,
											'UpdateDate'=>$jrec->update_date,
											'Quantity'=>$jrec->wi_itemqty,
											'PostDate'=>$jrec->deldate,
											'BaseLine'=>$jrec->base_line,
											'BaseEntry'=>$jrec->base_entry,
											'BaseType'=>$jrec->base_type,
											'WebRemarks'=>$jrec->wi_remarks,
											'Transtype'=>$jrec->wi_dtcode,
											'BatchCode'=>$jrec->pbatch_code,
											'WebWhsCode'=> $jrec->WebWhsCode,
											'WebWhsName'=> $jrec->WebWhsName,
											'SAPWhsCode'=> $sap_whse_code,
											'SAPWhsName'=> $sap_whse_name,
											'Remarks'=> $jrec->remarks,
											'DRRef'=> $jrec->drref,
											'U_DO'=> $jrec->u_do
										);
									}
								}

								$dorec['Delivery_Out'][] = $json_rec;
								$drec = json_encode($dorec);

								// WRITE JSON FILE
								date_default_timezone_set("Asia/Manila");
								$datetime = date('m-d-Y h:i:s');

								$dt = str_replace('-', '', $datetime); // Replaces all spaces with hyphens.
								$dt2 = str_replace(' ', '_', $dt); // Replaces all spaces with hyphens.
								$dt3 = str_replace(':', '', $dt2); // Replaces all spaces with hyphens.

								//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/inventory_jayson/JSON_FILES/DO_'.$trans_type.'_'.$dt3.'.json', $drec);

								if($db_type == '0'){
									$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/DO_'.$webkey.'_'.$webnum.'_'.$trans_type.'_'.$dt3.'_OLD.json';
								}elseif($db_type == '1'){
									$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/DO_'.$webkey.'_'.$webnum.'_'.$trans_type.'_'.$dt3.'_NEW.json';
								}else{
									// $filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/DO_'.$trans_type.'_'.$dt3.'.json';
								}

								// if($db_type == '1'){
								// 	$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/DO_'.$trans_type.'_'.$dt3.'.json';
									
								// 	//Store in the filesystem.
					   //              $fp = fopen($filepath , "w");
					   //              fwrite($fp, $drec);
					   //              fclose($fp);
								// }

								//Store in the filesystem.
					            $fp = fopen($filepath , "w");
					            fwrite($fp, $drec);
					            fclose($fp);

							}elseif($rcd->wi_reftype == 'ATW' OR $rcd->wi_reftype2 == 'ATW'){

								$dout_rec_atw = array(
									'wi_id'=>$rcd->wi_id,
									'item_id'=>$rcd->item_id,
									'wh_name'=>$rcd->wh_name,
									'deltype'=>$rcd->wi_deltype,
									'itemqty'=>$rcd->wi_itemqty,
									'trans_datetime'=>$datetime,
									'posting_date'=>$rcd->deldate,
									'reftype'=>$rcd->wi_reftype,
									'refnum'=>$rcd->wi_refnum,
									'reftype2'=>$rcd->wi_reftype2,
									'refnum2'=>$rcd->wi_refnum2

								);

								$this->usermodel->dspr_dout_atw($dout_rec_atw);

							}elseif($rcd->wi_reftype == 'DR' OR $rcd->wi_reftype2 == 'DR'){

								$dout_rec_dr = array(
									'wi_id'=>$rcd->wi_id,
									'item_id'=>$rcd->item_id,
									'wh_name'=>$rcd->wh_name,
									'deltype'=>$rcd->wi_deltype,
									'itemqty'=>$rcd->wi_itemqty,
									'trans_datetime'=>$datetime,
									'posting_date'=>$rcd->deldate,
									'reftype'=>$rcd->wi_reftype,
									'refnum'=>$rcd->wi_refnum,
									'reftype2'=>$rcd->wi_reftype2,
									'refnum2'=>$rcd->wi_refnum2

								);

								$this->usermodel->dspr_dout_dr($dout_rec_dr);

								// CREATE JSON FILES
								$whse_json_id = $rcd->wi_id;

								$json_record = array();
								$json_record = $this->usermodel->get_sap_do_to_json($whse_json_id);

								$trans_type = "";
								$db_type = "";
								$webkey = "";
								$webnum = "";

								if($json_record){
									foreach($json_record as $jrec){

										$web_whse_rec = array(
													'web_whse_code'=>$jrec->WebWhsCode,
													'web_whse_name'=>$jrec->WebWhsName
												);
										$sap_whse_record = array();
										$sap_whse_record = $this->usermodel->get_sap_whse_record($web_whse_rec);

										$sap_whse_code = "";
										$sap_whse_name  = "";

										if($jrec->wi_dtcode == "DT_02"){
											if($sap_whse_record){
												foreach($sap_whse_record as $srec){
													$sap_whse_code = $srec->SAP_WhsCode;
													$sap_whse_name  = $srec->SAP_WhsName;
												}
											}else{
												$sap_whse_code = $jrec->mwh_code;
												$sap_whse_name  = $jrec->awh_name;
											}
										}else{
											if($sap_whse_record){
												foreach($sap_whse_record as $srec){
													$sap_whse_code = $srec->SAP_WhsCode;
													$sap_whse_name  = $srec->SAP_WhsName;
												}
											}else{
												$sap_whse_code = $jrec->CardCode;
												$sap_whse_name  = $jrec->source;
											}
										}

										$trans_type = $jrec->wi_dtcode;
										$db_type = $jrec->dbtype;
										$webkey = $jrec->wi_id;
										$webnum = $jrec->wi_transno;

										$json_rec = array(
											'WebDocKey'=>$jrec->wi_id,
											'WebDocNum'=>$jrec->wi_transno,
											'DODate'=>$jrec->do_date,
											'SAPExpDelDate'=>$jrec->sap_exp_del_date,
											'DateGap'=>$jrec->date_gap,
											'DONo'=>$jrec->do_no,
											'Series'=>$jrec->series,
											'DefDocType'=>$jrec->def_doc_type,
											'TransmitDate'=>$jrec->transmit_date,
											'DOTransDate'=>$jrec->do_transmit_date,
											'DOStat'=>$jrec->do_stat,
											'CardCode'=>$jrec->card_code,
											'CardName'=>$jrec->card_name,
											'PepsiDRNo'=>$jrec->sdr_no,
											'Location'=>$jrec->location,
											'MotherPO'=>$jrec->mother_po,
											'TruckCode'=>$jrec->truck_code,
											'TruckCo'=>$jrec->truck_company,
											'WhsCode'=>$jrec->whse_code,
											'Source'=>$jrec->source,
											'U_Whse_Type'=>$jrec->whse_type,
											'U_SADocKey'=>$jrec->sa_dockey,
											'U_SADocNo'=>$jrec->sa_docno,
											'U_SADocLine1'=>$jrec->sa_docline,
											'U_SATransNo'=>$jrec->sa_transno,
											'U_SATransTyp'=>$jrec->sa_transtype,
											'ItemCode'=>$jrec->item_code,
											'Dscription'=>$jrec->item_desc,
											'UnitMsr'=>$jrec->uom,
											'DOQty'=>$jrec->do_qty,
											'B1UnservedQty'=>$jrec->b1_udo_qty,
											'Served'=>$jrec->served_qty,
											'TransPO'=>$jrec->trans_po,
											'SlpCode'=>$jrec->ae_code,
											'AE'=>$jrec->ae,
											'DORemarks'=>$jrec->do_remarks,
											'UpdateDate'=>$jrec->update_date,
											'Quantity'=>$jrec->wi_itemqty,
											'PostDate'=>$jrec->deldate,
											'BaseLine'=>$jrec->base_line,
											'BaseEntry'=>$jrec->base_entry,
											'BaseType'=>$jrec->base_type,
											'WebRemarks'=>$jrec->wi_remarks,
											'Transtype'=>$jrec->wi_dtcode,
											'BatchCode'=>$jrec->pbatch_code,
											'WebWhsCode'=> $jrec->WebWhsCode,
											'WebWhsName'=> $jrec->WebWhsName,
											'SAPWhsCode'=> $sap_whse_code,
											'SAPWhsName'=> $sap_whse_name,
											'Remarks'=> $jrec->remarks,
											'DRRef'=> $jrec->drref,
											'U_DO'=> $jrec->u_do
										);
									}
								}

								$dorec['Delivery_Out'][] = $json_rec;
								$drec = json_encode($dorec);

								// WRITE JSON FILE
								date_default_timezone_set("Asia/Manila");
								$datetime = date('m-d-Y h:i:s');

								$dt = str_replace('-', '', $datetime); // Replaces all spaces with hyphens.
								$dt2 = str_replace(' ', '_', $dt); // Replaces all spaces with hyphens.
								$dt3 = str_replace(':', '', $dt2); // Replaces all spaces with hyphens.

								//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/inventory_jayson/JSON_FILES/DO_'.$trans_type.'_'.$dt3.'.json', $drec);

								if($db_type	== '0'){
									$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/DO_'.$webkey.'_'.$webnum.'_'.$trans_type.'_'.$dt3.'_OLD.json';
								}elseif($db_type == '1'){
									$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/DO_'.$webkey.'_'.$webnum.'_'.$trans_type.'_'.$dt3.'_NEW.json';
								}else{
									// $filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/DO_'.$trans_type.'_'.$dt3.'.json';
								}

								// if($db_type == '1'){
								// 	$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/DO_'.$trans_type.'_'.$dt3.'.json';
								
								// 	//Store in the filesystem.
					   //              $fp = fopen($filepath , "w");
					   //              fwrite($fp, $drec);
					   //              fclose($fp);
								// }

								//Store in the filesystem.
					            $fp = fopen($filepath , "w");
					            fwrite($fp, $drec);
					            fclose($fp);

							}elseif($rcd->wi_reftype == 'WAR' OR $rcd->wi_reftype2 == 'WAR'){

								$dout_rec_war = array(
									'wi_id'=>$rcd->wi_id,
									'item_id'=>$rcd->item_id,
									'wh_name'=>$rcd->wh_name,
									'deltype'=>$rcd->wi_deltype,
									'itemqty'=>$rcd->wi_itemqty,
									'trans_datetime'=>$datetime,
									'posting_date'=>$rcd->deldate,
									'reftype'=>$rcd->wi_reftype,
									'refnum'=>$rcd->wi_refnum,
									'reftype2'=>$rcd->wi_reftype2,
									'refnum2'=>$rcd->wi_refnum2

								);

								$this->usermodel->dspr_dout_war($dout_rec_war);

							}
						}
						
					}
				}


				$wh_name = array();
				$wh_name = $this->usermodel->get_wh_name();
				$data['wname']=$wh_name;

				if($wh_name){
					foreach($wh_name as $rec){
						$warehouse_name = $rec->wh_name;
					}
				}

				redirect('main/wh_delivery_out_list/'.$warehouse_name);
			
		}
		else{
			redirect('main');
		}
	}

	function businesspartner(){
		if($this->session->userdata('logged_in')){
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			$select = $tokens[sizeof($tokens)-2];
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['bplist'] = $this->usermodel->bp_list_active();
			$data['itemlist']=$this->usermodel->bpselect_itemlist($get);
			if ($tokens[sizeof($tokens)-3] == 'warehouse'){
				$data['whlist']=$this->usermodel->bpselect_whlist($select);
			}
			$this->load->view('main/bp_list',$data);
		}
		else{
			redirect('main');
		}
	}
	function businesspartner_select(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['record'] = $this->usermodel->wh_active();
			$this->load->view('main/bp_warehouse',$data);
		}
	}
	function transactionsearch(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['refname']= $this->input->post('bpname');
			$data['refno']= $this->input->post('ref');
			$data['wh']= $this->input->post('whlist');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			if ($this->input->post('sdate') =='' AND $this->input->post('edate')==''){
				$data['deltran']=$this->usermodel->whlist_sort_nodate();
				$data['total_in_trans']=$this->usermodel->total_in_trans();
				$data['total_out_trans']=$this->usermodel->total_out_trans();
				$data['total_trans']=$this->usermodel->total_trans();
			}
			else{
				$data['deltran']=$this->usermodel->whlist_sort();
				$data['total_in_trans']=$this->usermodel->total_in_trans_sort();
				$data['total_out_trans']=$this->usermodel->total_out_trans_sort();
				$data['total_trans']=$this->usermodel->total_trans_sort();
			}
			$this->load->view('main/transaction_search',$data);
		}
		else{
			redirect('main');
		}
	}
	function wh_delivery_approve_list(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			//$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['refno']=$this->input->post('refno');
			$data['doctype']=$this->usermodel->get_doctype();
			
			$this->form_validation->set_rules('whouse','Warehouse','required');
			if ($this->form_validation->run() == true){
				$bot = array();
				$bot = $this->usermodel->wh_delivery_reserve_list();
				$data['reserverecord']=$bot;
				$rape = null;
				if($bot){
					foreach($bot as $bet){
						if($bet->wi_reftype == 'DO'){
							$rape = 'DO';
						}
						if($bet->wi_reftype == 'ITO'){
							$rape = 'ITO';
						}
						if($bet->wi_reftype2 == 'DO'){
							$rape = 'DO';
						}
						if($bet->wi_reftype2 == 'ITO'){
							$rape = 'ITO';
						}
						if($rape != null){ $data['mand'] = $this->usermodel->if_del_out($bet->wi_refnum, $rape, $bet->item_id, $bet->wi_itemqty); }
					}
				}
			}
			$data['vwhouse']=$this->input->post('whouse');
			$this->load->view('main/wh_del_approve',$data);
		}
		else{
			redirect('main');
		}
	}
	
	function wh_delivery_approve_mm_list(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			//$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['refno']=$this->input->post('refno');
			$data['doctype']=$this->usermodel->get_doctype();
			
			$this->form_validation->set_rules('whouse','Warehouse','required');
			if ($this->form_validation->run() == true){
				$data['reserverecord']=$this->usermodel->wh_delivery_reserve_list_mm();
			}
			$data['vwhouse']=$this->input->post('whouse');
			$this->load->view('main/wh_del_approve_mm',$data);
		}
		else{
			redirect('main');
		}
	}
	
	function wh_delivery_out_list(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['vwhouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['refno']=$this->input->post('refno');
			$data['doctype']=$this->usermodel->get_doctype();
			$data['reserverecord']=$this->usermodel->wh_delivery_out_list();
			$this->load->view('main/wh_del_out_list',$data);
		}
		else{

			
			redirect('main');
		}
	}
	function wh_delivery_cancel_list(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['vwhouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['refno']=$this->input->post('refno');
			$data['doctype']=$this->usermodel->get_doctype();
			$data['reserverecord']=$this->usermodel->wh_delivery_cancel_list_active_search();
			$this->load->view('main/wh_del_cancel_list',$data);
		}
		else{
			redirect('main');
		}
	}

	// CODE BY SIR ARMAN

	// function wh_delivery_item_reserve(){
	// 	//SAP88
	// 	if($this->session->userdata('logged_in')){
	// 		$this->load->model('b_model');
	// 		$tokens = explode('/', current_url());
	// 		$get = $tokens[sizeof($tokens)-1];
	// 		date_default_timezone_set("Asia/Manila");
	// 		$date = date('Y-m-d');
	// 		$date1 = str_replace('-', '/', $date);
	// 		$tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));
	// 		$otherday = date('Y-m-d',strtotime($date1 . "+2 days"));
	// 		$data['cdate'] = date('Y-m-d');
	// 		$data['tdate'] = $tomorrow;
	// 		$data['tdate1'] = $otherday;
	// 		$data['user'] = $this->usermodel->signin_user();
	// 		$data['modaccess']=$this->usermodel->access_module_approve();
	// 		$data['whlist']=$this->usermodel->whlist();
	// 		$data['deltype']=$this->usermodel->get_deltype($get);
	// 		$data['doctype']=$this->usermodel->get_doctype_Out();
	// 		$data['warehouse']=$this->usermodel->whname_select($get);
	// 		//From SAP1
	// 		$data['bpname']=$this->usermodel->get_bplistDdown_SAP($get);
	// 		$data['item']=$this->usermodel->item_get_SAP();
			
	// 		//$data['bpname']=$this->usermodel->get_bplistDdown($get);
	// 		//$data['item']=$this->usermodel->item_get();
	// 		$data['sapdetails'] = false;
	// 		if($this->input->post('sapdo')){
	// 			//echo 'SAP';
	// 			if($this->input->post('ref') != ''){
	// 				$data['sapdetails']=$this->usermodel->getDO_SAP();
	// 				if($data['sapdetails'] == false){
	// 					$data['error']='DO/ITO is already closed.';
	// 				}
	// 				$this->load->view('main/wh_del_items_reserve',$data);	
	// 			}
	// 			else{
	// 				$data['error']='DO/ITO number is required.';
	// 				$this->load->view('main/wh_del_items_reserve',$data);
	// 			}
	// 		}
	// 		else{
	// 			$this->form_validation->set_rules('whitem','Item','required');
	// 			$this->form_validation->set_rules('whqty','Quantity','required|numeric');
	// 			$this->form_validation->set_rules('wh','Warehouse','required');
	// 			$this->form_validation->set_rules('bpname','Reference Name','required');
	// 			$this->form_validation->set_rules('ref','Reference No. 1','required');
	// 			$this->form_validation->set_rules('uom','Unit of Measurement','required');
	// 			//$this->form_validation->set_rules('tdate1','Expected Delivery Date','required');
	// 			if ($this->form_validation->run() == false){
	// 				$this->load->view('main/wh_del_items_reserve',$data);
	// 			}
	// 			else{
	// 				if($q=$this->usermodel->validation_del_in_ref()){
	// 					$data['error']="Reference Type and Number already encoded.";
	// 					$this->load->view('main/wh_del_items_reserve',$data);
	// 				}
	// 				else{
	// 					if ($this->usermodel->wh_itemqty_validation()){
	// 						$data['error']='Insufficient Quantity!';
	// 						$this->load->view('main/wh_del_items_reserve',$data);
	// 					}
	// 					else{
	// 						if($q=$this->usermodel->validation_del_in_ref()){
	// 							$data['error']="Reference Type and Number already encoded.";
	// 							$this->load->view('main/wh_del_items_reserve',$data);
	// 						}
	// 						if($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
	// 							$data['error']="DO / ITO Quantity is required.";
	// 							$this->load->view('main/wh_del_items_reserve',$data);
	// 						}
	// 						if($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
	// 							$data['error']="DO / ITO Quantity is required.";
	// 							$this->load->view('main/wh_del_items_reserve',$data);
	// 						}
	// 						if($this->input->post('ddate') > $this->input->post('tdate')){
	// 							$data['error']="Invalid Delivery Date.";
	// 							$this->load->view('main/wh_del_items_reserve',$data);
	// 						}
	// 						else{
	// 							//check if dr number already exist
	// 							if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR'){
	// 								if($this->input->post('doctype1') == 'DR'){
	// 									$dr = $this->input->post('ref');
	// 								}
										
	// 								if($this->input->post('doctype2') == 'DR'){
	// 									$dr = $this->input->post('ref2');
	// 								}
	// 								if($this->usermodel->check_dr($dr) == true){
	// 									$data['error']="DR Number already exist. Please enter the correct DR Number";
	// 									$this->load->view('main/wh_del_items_reserve',$data);
	// 								}
	// 							}
	// 							// validate first if the actual loaded will not surpass the total remaining quantity
	// 							$doctype1 = $this->input->post('doctype1'); 
	// 							$doctype2 = $this->input->post('doctype2'); 
	// 							$ref1 = $this->input->post('ref'); 
	// 							$ref2 = $this->input->post('ref2'); 
	// 							$whqty = $this->input->post('whqty'); //actual loaded
	// 							$doqty = $this->input->post('doqty'); // Figures from SAP 
								
	// 							if($doctype1 == 'DO' OR $doctype1 == 'ITO' OR $doctype2 == 'DO' OR $doctype2 == 'ITO'){
	// 								//echo $doctype1.' | '.$doctype2.' | '.$ref1.' | '.$ref2;
									
	// 								$data['sapdetails']=$this->usermodel->getDO_SAP();
	// 								if($data['sapdetails'] == false){
	// 									$data['error']='DO/ITO is already closed.';
	// 								}
	// 								else{
	// 									if($this->usermodel->check_if_refnum_existed($doctype1, $doctype2, $ref1, $ref2) == true){
	// 										$this->usermodel->update_running_balance();
	// 										if( $this->usermodel->check_if_to_be_posted_qty_is_greater_than_actual($doctype1, $doctype2, $ref1, $ref2, $whqty) == false){
	// 											$data['error']="Actual loaded quantity is greater than the remaining quantity.";
	// 										}
	// 										if( $whqty >= $doqty ){
	// 											$data['error']="Actual loaded quantity is greater than the DO/ITO quantity.";
	// 										}
	// 									}	
	// 								}
	// 								$this->load->view('main/wh_del_items_reserve',$data);
	// 							}
	// 							$this->usermodel->bp_upload();
	// 							$this->usermodel->home_wh_out();
	// 							//$this->b_model->create_send();
	// 							redirect('main/home');
	// 						}
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
	// 	else{
	// 		redirect('main');
	// 	}
	// }
	
	function wh_delivery_item_reserve(){
		//SAP88
		if($this->session->userdata('logged_in')){
			$this->load->model('b_model');
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			date_default_timezone_set("Asia/Manila");
			$date = date('Y-m-d');
			$date1 = str_replace('-', '/', $date);
			$tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));
			$data['expected_deldate'] = $date;
			$data['cdate'] = date('Y-m-d');
			$data['tdate'] = $tomorrow;
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['deltype']=$this->usermodel->get_deltype($get);
			$data['doctype']=$this->usermodel->get_doctype_Out();
			$data['warehouse']=$this->usermodel->whname_select($get);
			$data['trucks']=$this->usermodel->truck_list();
			$data['uom']=$this->usermodel->uom_list();
			$data['trans_no']=$this->usermodel->trans_no_dout();

			$data['cvdate_dout']=$this->usermodel->check_validity_date_dout();

			$data['prepared_list'] = $this->usermodel->prepared_list();
			$data['checked_list'] = $this->usermodel->checked_list();
			$data['guard_list'] = $this->usermodel->guard_list();

			$data['sub_type_del_out']=$this->usermodel->sub_type_del_out();

			$data['sub_del_type_out_customer']=$this->usermodel->get_bplistDdown_SAP_Customer($get);
			$data['sub_del_type_out_warehouse']=$this->usermodel->get_bplistDdown_SAP_Warehouse($get);

			//From SAP1
			$data['bpname']=$this->usermodel->get_bplistDdown_SAP($get);
			// $data['item']=$this->usermodel->item_get_SAP();
			
			//$data['bpname']=$this->usermodel->get_bplistDdown($get);
			$data['item']=$this->usermodel->item_get();
			if($this->input->post('sapdo') AND $this->input->post('ref')){
				//echo 'SAP';
				$data['sapdetails']=$this->usermodel->getDO_SAP();
				$this->load->view('header',$data);
				$this->load->view('main/wh_del_items_reserve',$data);
				// $this->load->view('footer');
			}elseif($this->input->post('check_intransit') == "cins"){
				$data['msg']="";
				$data['check_intransit']=$this->usermodel->check_intransit();
				$this->load->view('header',$data);
				$this->load->view('main/wh_del_items_reserve',$data);
			}else{

				$data['msg']="";
				$this->form_validation->set_rules('whitem','Item','required');
				$this->form_validation->set_rules('whqty','Actual Quantity Loaded','required|numeric');
				$this->form_validation->set_rules('wh','Warehouse','required');
				$this->form_validation->set_rules('bpname','Reference Name','required');
				$this->form_validation->set_rules('ref','Reference No. 1','required');
				$this->form_validation->set_rules('uom','Unit of Measurement','required');
				$this->form_validation->set_rules('ddate','Posting Date','required');

				// $this->form_validation->set_rules('trucktime','Truckers Arrival Time','required');
				// $this->form_validation->set_rules('tdate','Shipment Date','required');
				// $this->form_validation->set_rules('shiptime','Shipment Time','required');

				if($this->input->post('sub_type_del_out') == "DO_01"){
					$this->form_validation->set_rules('tpnum','Trucke Plate Number','required');
					$this->form_validation->set_rules('tdrvr','Truck Driver','required');
				}

				// $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

				if ($this->form_validation->run() == false){
					$this->load->view('header',$data);
					$this->load->view('main/wh_del_items_reserve',$data);
					// $this->load->view('footer');
				}else{
					if($q=$this->usermodel->validation_del_in_ref()){
						$data['error']="Reference Type and Number already encoded.";
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve',$data);
						// $this->load->view('footer');
					}elseif($this->usermodel->check_dr_if_exists_1()){
						$data['error']="DR Number already exists.";
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve',$data);
					}elseif($this->usermodel->check_dr_if_exists_2()){
						$data['error']="DR Number already exists.";
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve',$data);
					}elseif($this->usermodel->check_wis_if_exists_1()){
						$data['error']="WIS Number already exists.";
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve',$data);
					}elseif($this->usermodel->check_wis_if_exists_2()){
						$data['error']="WIS Number already exists.";
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve',$data);
					}else{
						if ($q=$this->usermodel->wh_itemqty_validation()){
							$data['error']='Insufficient Quantity!';
							$this->load->view('header',$data);
							$this->load->view('main/wh_del_items_reserve',$data);
							// $this->load->view('footer');
						}elseif($this->input->post('whqty') <= 0){
							$data['error']='Actual Quantity must be greater than 0';
							$this->load->view('header',$data);
							$this->load->view('main/wh_del_items_reserve',$data);
						}else{

							$src = $this->input->post('sub_type_del_out');
							if(isset($src)){

								// $pepsi_str = substr($src, 2, 6);

								//FOR CUSTOMER ONLY
								if($src=="DO_01"){

									if($this->input->post('doctype2') <> 'DR' AND $this->input->post('doctype2') <> 'WIS'){
										$data['error']="Reference No. 2 must be DR or WIS";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve',$data);
									}elseif($this->input->post('doctype1') <> 'DO'){
										$data['error']="Reference No. 1 must be DO";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve',$data);
									}else{

										if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR' OR $this->input->post('doctype1') == 'ATW' OR $this->input->post('doctype2') == 'ATW' 
											OR $this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){

											if($this->input->post('location') == ""){
												$data['error']='Location Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}elseif($this->input->post('trucktime') == ""){
												$data['error']='Truckers Arrival Time Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}elseif($this->input->post('truck_list') == ""){
												$data['error']='Truck Company Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}elseif($this->input->post('tpnum') == ""){
												$data['error']='Truck Plate Number Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}
											elseif($this->input->post('tdrvr') == ""){
												$data['error']='Truck Driver Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}elseif($this->input->post('expected_deldate') == ""){
												$data['error']='Expected Delivery Date Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
											}elseif($this->input->post('trucktime') == ""){
												$data['error']='Truckers Arrival Time Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
											}elseif($this->input->post('tdate') == ""){
												$data['error']='Shipment Date Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
											}elseif($this->input->post('shiptime') == ""){
												$data['error']='Shipment Time Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
											}else{

												if($q=$this->usermodel->validation_del_in_ref()){
													$data['error']="Reference Type and Number already encoded.";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
													// $this->load->view('footer');
												}
												elseif($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
													$data['error']="DO / ITO Quantity is required.";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
													//$this->load->view('footer');
												}
												elseif($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
													$data['error']="DO / ITO Quantity is required.";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
													//$this->load->view('footer');
												}
												elseif($this->input->post('ddate') > $this->input->post('tdate')){
													$data['error']="Invalid Delivery Date.";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
													//$this->load->view('footer');
												}elseif($src == 'C16500' OR $src == 'C16501' OR $src == 'C16502' OR $src == 'C16503'
													OR $src == 'C16504' OR $src == 'C16505' OR $src == 'C16506' OR $src == 'C16507'
													OR $src == 'C16508'){
														if($this->input->post('ref3') == ""){
															$data['error']="Reference No. 3 is required";
															$this->load->view('header',$data);
															$this->load->view('main/wh_del_items_reserve',$data);
															//$this->load->view('footer');
														}else{
															$this->usermodel->bp_upload();
															$this->usermodel->home_wh_out();

															$this->usermodel->do_nappr();

															$this->b_model->send_delivery_out();
															$this->b_model->send_delivery_out_ae();

															$this->usermodel->truck_add();
															$this->usermodel->uom_add();

															$trans_no = "";
															$trans_no = "/".$this->input->post('trans_no');

															if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR'){
																$doc_type = "/dout_01_DR";
															}elseif($this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){
																$doc_type = "/dout_01_WIS";
															}

															redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').$doc_type.$trans_no);

														}

												}elseif($this->input->post('doctype1') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
													$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
												}elseif($this->input->post('doctype2') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
													$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
												}else{

													$this->usermodel->bp_upload();
													$this->usermodel->home_wh_out();

													$this->usermodel->do_nappr();

													$this->b_model->send_delivery_out();
													$this->b_model->send_delivery_out_ae();

													$this->usermodel->truck_add();
													$this->usermodel->uom_add();

													$trans_no = "";
													$trans_no = "/".$this->input->post('trans_no');

													if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR'){
														$doc_type = "/dout_01_DR";
													}elseif($this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){
														$doc_type = "/dout_01_WIS";
													}

													redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').$doc_type.$trans_no);

												}
											}
										}else{
											// IF NOT DR OR ATW IS REFERENCE
											if($q=$this->usermodel->validation_del_in_ref()){
												$data['error']="Reference Type and Number already encoded.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}
											elseif($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
												$data['error']="DO / ITO Quantity is required.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}
											elseif($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
												$data['error']="DO / ITO Quantity is required.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}
											elseif($this->input->post('ddate') > $this->input->post('tdate')){
												$data['error']="Invalid Delivery Date.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}elseif($src == 'C16500' OR $src == 'C16501' OR $src == 'C16502' OR $src == 'C16503'
												OR $src == 'C16504' OR $src == 'C16505' OR $src == 'C16506' OR $src == 'C16507'
												OR $src == 'C16508'){
													if($this->input->post('ref3'
														) == ""){
														$data['error']="Reference No. 3 is required";
														$this->load->view('header',$data);
														$this->load->view('main/wh_del_items_reserve',$data);
														//$this->load->view('footer');
													}else{
														$this->usermodel->bp_upload();
														$this->usermodel->home_wh_out();

														$this->usermodel->do_nappr();

														$this->b_model->send_delivery_out();
														$this->b_model->send_delivery_out_ae();

														$this->usermodel->truck_add();
														$this->usermodel->uom_add();

														redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').'dout_01');
													}
											}elseif($this->input->post('doctype1') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
													$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
											}elseif($this->input->post('doctype2') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
													$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
											}else{
												$this->usermodel->bp_upload();
												$this->usermodel->home_wh_out();

												$this->usermodel->do_nappr();

												$this->b_model->send_delivery_out();
												$this->b_model->send_delivery_out_ae();

												$this->usermodel->truck_add();
												$this->usermodel->uom_add();

												redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').'dout_01');
											}
											
										}

									}

									
								}elseif($src=="DO_04"){

									if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR' OR $this->input->post('doctype1') == 'ATW' OR $this->input->post('doctype2') == 'ATW' 
										OR $this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){

										if($this->input->post('location') == ""){
											$data['error']='Location Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
											// $this->load->view('footer');
										}elseif($this->input->post('trucktime') == ""){
											$data['error']='Truckers Arrival Time Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
											// $this->load->view('footer');
										}elseif($this->input->post('truck_list') == ""){
											$data['error']='Truck Company Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
											// $this->load->view('footer');
										}elseif($this->input->post('tpnum') == ""){
											$data['error']='Truck Plate Number Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
											// $this->load->view('footer');
										}
										elseif($this->input->post('tdrvr') == ""){
											$data['error']='Truck Driver Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
											// $this->load->view('footer');
										}elseif($this->input->post('expected_deldate') == ""){
											$data['error']='Expected Delivery Date Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
										}elseif($this->input->post('trucktime') == ""){
											$data['error']='Truckers Arrival Time Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
										}elseif($this->input->post('tdate') == ""){
											$data['error']='Shipment Date Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
										}elseif($this->input->post('shiptime') == ""){
											$data['error']='Shipment Time Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
										}else{

											if($q=$this->usermodel->validation_del_in_ref()){
												$data['error']="Reference Type and Number already encoded.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}
											elseif($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
												$data['error']="DO / ITO Quantity is required.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}
											elseif($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
												$data['error']="DO / ITO Quantity is required.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}
											elseif($this->input->post('ddate') > $this->input->post('tdate')){
												$data['error']="Invalid Delivery Date.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}elseif($src == 'C16500' OR $src == 'C16501' OR $src == 'C16502' OR $src == 'C16503'
												OR $src == 'C16504' OR $src == 'C16505' OR $src == 'C16506' OR $src == 'C16507'
												OR $src == 'C16508'){
													if($this->input->post('ref3') == ""){
														$data['error']="Reference No. 3 is required";
														$this->load->view('header',$data);
														$this->load->view('main/wh_del_items_reserve',$data);
														//$this->load->view('footer');
													}else{
														$this->usermodel->bp_upload();
														$this->usermodel->home_wh_out();

														$this->usermodel->do_nappr();

														$this->b_model->send_delivery_out();
														$this->b_model->send_delivery_out_ae();

														$this->usermodel->truck_add();
														$this->usermodel->uom_add();

														$trans_no = "";
														$trans_no = "/".$this->input->post('trans_no');

														if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR'){
															$doc_type = "/dout_01_DR";
														}elseif($this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){
															$doc_type = "/dout_01_WIS";
														}

														redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').$doc_type.$trans_no);

													}
											}elseif($this->input->post('doctype1') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
												$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
											}elseif($this->input->post('doctype2') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
												$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
											}else{

												$this->usermodel->bp_upload();
												$this->usermodel->home_wh_out();

												$this->usermodel->do_nappr();

												$this->b_model->send_delivery_out();
												$this->b_model->send_delivery_out_ae();

												$this->usermodel->truck_add();
												$this->usermodel->uom_add();

												$trans_no = "";
												$trans_no = "/".$this->input->post('trans_no');

												if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR'){
													$doc_type = "/dout_01_DR";
												}elseif($this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){
													$doc_type = "/dout_01_WIS";
												}

												redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').$doc_type.$trans_no);

											}
										}
									}else{
										// IF NOT DR OR ATW IS REFERENCE
										if($q=$this->usermodel->validation_del_in_ref()){
											$data['error']="Reference Type and Number already encoded.";
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
											//$this->load->view('footer');
										}
										elseif($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
											$data['error']="DO / ITO Quantity is required.";
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
											//$this->load->view('footer');
										}
										elseif($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
											$data['error']="DO / ITO Quantity is required.";
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
											//$this->load->view('footer');
										}
										elseif($this->input->post('ddate') > $this->input->post('tdate')){
											$data['error']="Invalid Delivery Date.";
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve',$data);
											//$this->load->view('footer');
										}elseif($src == 'C16500' OR $src == 'C16501' OR $src == 'C16502' OR $src == 'C16503'
											OR $src == 'C16504' OR $src == 'C16505' OR $src == 'C16506' OR $src == 'C16507'
											OR $src == 'C16508'){
												if($this->input->post('ref3'
													) == ""){
													$data['error']="Reference No. 3 is required";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
													//$this->load->view('footer');
												}else{
													$this->usermodel->bp_upload();
													$this->usermodel->home_wh_out();

													$this->usermodel->do_nappr();

													$this->b_model->send_delivery_out();
													$this->b_model->send_delivery_out_ae();

													$this->usermodel->truck_add();
													$this->usermodel->uom_add();

													redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').'dout_01');
												}
										}elseif($this->input->post('doctype1') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
												$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
										}elseif($this->input->post('doctype2') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
												$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
										}else{
											$this->usermodel->bp_upload();
											$this->usermodel->home_wh_out();

											$this->usermodel->do_nappr();

											$this->b_model->send_delivery_out();
											$this->b_model->send_delivery_out_ae();

											$this->usermodel->truck_add();
											$this->usermodel->uom_add();

											redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').'dout_01');
										}
										
									}

								}elseif($src=="DO_05"){

									if($this->input->post('doctype2') <> 'WIS'){
										$data['error']="Reference No. 2 must be WIS";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve',$data);
									}elseif($this->input->post('doctype1') <> 'DO'){
										$data['error']="Reference No. 1 must be DO";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve',$data);
									}else{

										if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR' OR $this->input->post('doctype1') == 'ATW' OR $this->input->post('doctype2') == 'ATW' 
											OR $this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){

											if($this->input->post('location') == ""){
												$data['error']='Location Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}elseif($this->input->post('trucktime') == ""){
												$data['error']='Truckers Arrival Time Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}elseif($this->input->post('truck_list') == ""){
												$data['error']='Truck Company Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}elseif($this->input->post('tpnum') == ""){
												$data['error']='Truck Plate Number Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}
											elseif($this->input->post('tdrvr') == ""){
												$data['error']='Truck Driver Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												// $this->load->view('footer');
											}elseif($this->input->post('expected_deldate') == ""){
												$data['error']='Expected Delivery Date Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
											}elseif($this->input->post('trucktime') == ""){
												$data['error']='Truckers Arrival Time Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
											}elseif($this->input->post('tdate') == ""){
												$data['error']='Shipment Date Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
											}elseif($this->input->post('shiptime') == ""){
												$data['error']='Shipment Time Field is required';
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
											}else{

												if($q=$this->usermodel->validation_del_in_ref()){
													$data['error']="Reference Type and Number already encoded.";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
													// $this->load->view('footer');
												}
												elseif($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
													$data['error']="DO / ITO Quantity is required.";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
													//$this->load->view('footer');
												}
												elseif($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
													$data['error']="DO / ITO Quantity is required.";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
													//$this->load->view('footer');
												}
												elseif($this->input->post('ddate') > $this->input->post('tdate')){
													$data['error']="Invalid Delivery Date.";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
													//$this->load->view('footer');
												}elseif($src == 'C16500' OR $src == 'C16501' OR $src == 'C16502' OR $src == 'C16503'
													OR $src == 'C16504' OR $src == 'C16505' OR $src == 'C16506' OR $src == 'C16507'
													OR $src == 'C16508'){
														if($this->input->post('ref3') == ""){
															$data['error']="Reference No. 3 is required";
															$this->load->view('header',$data);
															$this->load->view('main/wh_del_items_reserve',$data);
															//$this->load->view('footer');
														}else{
															$this->usermodel->bp_upload();
															$this->usermodel->home_wh_out();

															$this->usermodel->do_nappr();

															$this->b_model->send_delivery_out();
															$this->b_model->send_delivery_out_ae();

															$this->usermodel->truck_add();
															$this->usermodel->uom_add();

															$trans_no = "";
															$trans_no = "/".$this->input->post('trans_no');

															if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR'){
																$doc_type = "/dout_01_DR";
															}elseif($this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){
																$doc_type = "/dout_01_WIS";
															}

															redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').$doc_type.$trans_no);

														}
												}elseif($this->input->post('doctype1') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
													$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
												}elseif($this->input->post('doctype2') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
													$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
												}else{

													$this->usermodel->bp_upload();
													$this->usermodel->home_wh_out();

													$this->usermodel->do_nappr();

													$this->b_model->send_delivery_out();
													$this->b_model->send_delivery_out_ae();

													$this->usermodel->truck_add();
													$this->usermodel->uom_add();

													$trans_no = "";
													$trans_no = "/".$this->input->post('trans_no');

													if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR'){
														$doc_type = "/dout_01_DR";
													}elseif($this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){
														$doc_type = "/dout_01_WIS";
													}

													redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').$doc_type.$trans_no);

												}
											}
										}else{
											// IF NOT DR OR ATW IS REFERENCE
											if($q=$this->usermodel->validation_del_in_ref()){
												$data['error']="Reference Type and Number already encoded.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}
											elseif($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
												$data['error']="DO / ITO Quantity is required.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}
											elseif($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
												$data['error']="DO / ITO Quantity is required.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}
											elseif($this->input->post('ddate') > $this->input->post('tdate')){
												$data['error']="Invalid Delivery Date.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve',$data);
												//$this->load->view('footer');
											}elseif($src == 'C16500' OR $src == 'C16501' OR $src == 'C16502' OR $src == 'C16503'
												OR $src == 'C16504' OR $src == 'C16505' OR $src == 'C16506' OR $src == 'C16507'
												OR $src == 'C16508'){
													if($this->input->post('ref3'
														) == ""){
														$data['error']="Reference No. 3 is required";
														$this->load->view('header',$data);
														$this->load->view('main/wh_del_items_reserve',$data);
														//$this->load->view('footer');
													}else{
														$this->usermodel->bp_upload();
														$this->usermodel->home_wh_out();

														$this->usermodel->do_nappr();

														$this->b_model->send_delivery_out();
														$this->b_model->send_delivery_out_ae();

														$this->usermodel->truck_add();
														$this->usermodel->uom_add();

														redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').'dout_01');
													}
											}elseif($this->input->post('doctype1') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
													$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
											}elseif($this->input->post('doctype2') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
													$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve',$data);
											}else{
												$this->usermodel->bp_upload();
												$this->usermodel->home_wh_out();

												$this->usermodel->do_nappr();

												$this->b_model->send_delivery_out();
												$this->b_model->send_delivery_out_ae();

												$this->usermodel->truck_add();
												$this->usermodel->uom_add();

												redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout').'dout_01');
											}
											
										}

									}

								}else{

									// FOR NON-CUSTOMER (SUPPLIER OR WAREHOUSE)
									if($q=$this->usermodel->validation_del_in_ref()){
										$data['error']="Reference Type and Number already encoded.";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve',$data);
										//$this->load->view('footer');
									}
									if($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
										$data['error']="DO / ITO Quantity is required.";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve',$data);
										//$this->load->view('footer');
									}
									if($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
										$data['error']="DO / ITO Quantity is required.";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve',$data);
										//$this->load->view('footer');
									}
									if($this->input->post('ddate') > $this->input->post('tdate')){
										$data['error']="Invalid Delivery Date.";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve',$data);
										//$this->load->view('footer');
									}
									if($this->input->post('doctype1') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
										$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve',$data);
									}
									if($this->input->post('doctype2') == 'DO' AND  $this->input->post('doqty') <> NULL AND $this->input->post('doqty') < $this->input->post('whqty')){
										$data['error']="DO/ ITO Quantity must not be less than the Actual Quantity";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve',$data);
									}
									
									$this->usermodel->bp_upload();
									$this->usermodel->home_wh_out();

									$this->usermodel->do_nappr();

									$this->usermodel->truck_add();
									$this->usermodel->uom_add();

									redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout'));

								}
							}			
						}
					}
				}
			}
		}
		else{
			redirect('main');
		}
	}

	// PRINT DR
	function print_dr_pdf(){
		$data['page_title'] = 'Delivery Receipt';
		$data['print'] = $this->usermodel->dr_print_layout();
		$this->load->view('pdf/fpdf');
		$this->load->view('dr_print_layout', $data);

		// // OPEN THE DOWNLOADED PDF
		$pdf_name = array();
		$pdf_name = $this->usermodel->get_print_number();
		// $pdf_name = $this->usermodel->get_last_inserted_id();

		foreach($pdf_name as $row){
			$filename = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/PRINT_DOCS/DR/'.$row->wi_id.".pdf";

			$this->output
             ->set_content_type('application/pdf')
             ->set_output(file_get_contents($filename)); 
		}

	}

	function print_wis_pdf(){
		$data['page_title'] = 'Warehouse Issue Slip';
		$data['print'] = $this->usermodel->wis_print_layout();
		$this->load->view('pdf/fpdf');
		$this->load->view('wis_print_layout', $data);

		// OPEN THE DOWNLOADED PDF
		$pdf_name = array();
		$pdf_name = $this->usermodel->get_print_number();
		// $pdf_name = $this->usermodel->get_last_inserted_id();

		foreach($pdf_name as $row){
			$filename = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/PRINT_DOCS/WIS/'.$row->wi_id.".pdf";

			$this->output
             ->set_content_type('application/pdf')
             ->set_output(file_get_contents($filename)); 
		}

	}

	function print_rr_pdf(){
		$data['page_title'] = 'Receiving Report';
		$data['print'] = $this->usermodel->rr_print_layout();
		$this->load->view('pdf/fpdf');
		$this->load->view('rr_print_layout', $data);

		// OPEN THE DOWNLOADED PDF
		$pdf_name = array();
		$pdf_name = $this->usermodel->get_last_inserted_id();

		foreach($pdf_name as $row){
			$filename = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/PRINT_DOCS/RR/'.$row->wi_id.".pdf";

			$this->output
             ->set_content_type('application/pdf')
             ->set_output(file_get_contents($filename)); 
		}

	}

	function wh_delivery_item_reserve_edit(){
		//SAP88
		if($this->session->userdata('logged_in')){
			$this->load->model('b_model');
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-2];

			$data['del_out_rec']=$this->usermodel->del_out_edit();

			date_default_timezone_set("Asia/Manila");
			$date = date('Y-m-d');
			$date1 = str_replace('-', '/', $date);
			$tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));
			$data['expected_deldate'] = $date;
			$data['cdate'] = date('Y-m-d');
			$data['tdate'] = $tomorrow;
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['deltype']=$this->usermodel->get_deltype($get);
			$data['doctype']=$this->usermodel->get_doctype_Out();
			$data['warehouse']=$this->usermodel->whname_select($get);
			$data['trucks']=$this->usermodel->truck_list();
			$data['uom']=$this->usermodel->uom_list();


			$data['sub_type_del_out']=$this->usermodel->sub_type_del_out();

			$data['sub_del_type_out_customer']=$this->usermodel->get_bplistDdown_SAP_Customer($get);
			$data['sub_del_type_out_warehouse']=$this->usermodel->get_bplistDdown_SAP_Warehouse($get);

			//From SAP1
			$data['bpname']=$this->usermodel->get_bplistDdown_SAP($get);
			// $data['item']=$this->usermodel->item_get_SAP();
			
			//$data['bpname']=$this->usermodel->get_bplistDdown($get);
			$data['item']=$this->usermodel->item_get();
			if($this->input->post('sapdo') AND $this->input->post('ref')){
				//echo 'SAP';
				$data['sapdetails']=$this->usermodel->getDO_SAP();
				$this->load->view('header',$data);
				$this->load->view('main/wh_del_items_reserve_edit',$data);
				// $this->load->view('footer');
			}else{

				$data['msg']="";
				$this->form_validation->set_rules('whitem','Item','required');
				$this->form_validation->set_rules('whqty','Actual Quantity Loaded','required|numeric');
				$this->form_validation->set_rules('wh','Warehouse','required');
				$this->form_validation->set_rules('bpname','Reference Name','required');
				$this->form_validation->set_rules('ref','Reference No. 1','required');
				$this->form_validation->set_rules('uom','Unit of Measurement','required');
				$this->form_validation->set_rules('ddate','Posting Date','required');

				$this->form_validation->set_rules('trucktime','Truckers Arrival Time','required');
				$this->form_validation->set_rules('tdate','Shipment Date','required');
				$this->form_validation->set_rules('shiptime','Shipment Time','required');

				// $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

				if ($this->form_validation->run() == false){
					$this->load->view('header',$data);
					$this->load->view('main/wh_del_items_reserve_edit',$data);
					// $this->load->view('footer');
				}else{
					
						// if ($q=$this->usermodel->wh_itemqty_validation()){
						// 	$data['error']='Insufficient Quantity!';
						// 	$this->load->view('header',$data);
						// 	$this->load->view('main/wh_del_items_reserve_edit',$data);
						// 	// $this->load->view('footer');
						// }else{

							$src = $this->input->post('sub_type_del_out');
							if(isset($src)){

								// $pepsi_str = substr($src, 2, 6);

								//FOR CUSTOMER ONLY
								if($src=="DO_01"){
									if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR' OR $this->input->post('doctype1') == 'ATW' OR $this->input->post('doctype2') == 'ATW' 
										OR $this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){

										if($this->input->post('location') == ""){
											$data['error']='Location Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve_edit',$data);
											// $this->load->view('footer');
										}elseif($this->input->post('trucktime') == ""){
											$data['error']='Truckers Arrival Time Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve_edit',$data);
											// $this->load->view('footer');
										}elseif($this->input->post('truck_list') == ""){
											$data['error']='Truck Company Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve_edit',$data);
											// $this->load->view('footer');
										}elseif($this->input->post('tpnum') == ""){
											$data['error']='Truck Plate Number Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve_edit',$data);
											// $this->load->view('footer');
										}
										elseif($this->input->post('tdrvr') == ""){
											$data['error']='Truck Driver Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve_edit',$data);
											// $this->load->view('footer');
										}elseif($this->input->post('expected_deldate') == ""){
											$data['error']='Expected Delivery Date Field is required';
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve_edit',$data);
										}else{

											if($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
												$data['error']="DO / ITO Quantity is required.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve_edit',$data);
												//$this->load->view('footer');
											}
											elseif($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
												$data['error']="DO / ITO Quantity is required.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve_edit',$data);
												//$this->load->view('footer');
											}
											elseif($this->input->post('ddate') > $this->input->post('tdate')){
												$data['error']="Invalid Delivery Date.";
												$this->load->view('header',$data);
												$this->load->view('main/wh_del_items_reserve_edit',$data);
												//$this->load->view('footer');
											}elseif($src == 'C16500' OR $src == 'C16501' OR $src == 'C16502' OR $src == 'C16503'
												OR $src == 'C16504' OR $src == 'C16505' OR $src == 'C16506' OR $src == 'C16507'
												OR $src == 'C16508'){
													if($this->input->post('ref3') == ""){
														$data['error']="Reference No. 3 is required";
														$this->load->view('header',$data);
														$this->load->view('main/wh_del_items_reserve_edit',$data);
														//$this->load->view('footer');
													}else{
														$this->usermodel->bp_upload();
														$this->usermodel->home_wh_out_edit();

														// $this->b_model->send_delivery_out();
														// $this->b_model->send_delivery_out_ae();

														$this->usermodel->truck_add();
														$this->usermodel->uom_add();

														redirect('main/wh_delivery_approve_list');

														// redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout'));
													}
											}else{
												$this->usermodel->bp_upload();
												$this->usermodel->home_wh_out_edit();

												// $this->b_model->send_delivery_out();
												// $this->b_model->send_delivery_out_ae();

												$this->usermodel->truck_add();
												$this->usermodel->uom_add();

												redirect('main/wh_delivery_approve_list');

												// redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout'));
											}
										}
									}else{
										// IF NOT DR OR ATW IS REFERENCE
										if($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
											$data['error']="DO / ITO Quantity is required.";
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve_edit',$data);
											//$this->load->view('footer');
										}
										elseif($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
											$data['error']="DO / ITO Quantity is required.";
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve_edit',$data);
											//$this->load->view('footer');
										}
										elseif($this->input->post('ddate') > $this->input->post('tdate')){
											$data['error']="Invalid Delivery Date.";
											$this->load->view('header',$data);
											$this->load->view('main/wh_del_items_reserve_edit',$data);
											//$this->load->view('footer');
										}elseif($src == 'C16500' OR $src == 'C16501' OR $src == 'C16502' OR $src == 'C16503'
											OR $src == 'C16504' OR $src == 'C16505' OR $src == 'C16506' OR $src == 'C16507'
											OR $src == 'C16508'){
												if($this->input->post('ref3'
													) == ""){
													$data['error']="Reference No. 3 is required";
													$this->load->view('header',$data);
													$this->load->view('main/wh_del_items_reserve_edit',$data);
													//$this->load->view('footer');
												}else{
													$this->usermodel->bp_upload();
													$this->usermodel->home_wh_out_edit();

													// $this->b_model->send_delivery_out();
													// $this->b_model->send_delivery_out_ae();

													$this->usermodel->truck_add();
													$this->usermodel->uom_add();

													redirect('main/wh_delivery_approve_list');

													// redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout'));
												}
										}else{
											$this->usermodel->bp_upload();
											$this->usermodel->home_wh_out_edit();

											// $this->b_model->send_delivery_out();
											// $this->b_model->send_delivery_out_ae();

											$this->usermodel->truck_add();
											$this->usermodel->uom_add();

											redirect('main/wh_delivery_approve_list');

											// redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout'));
										}
										
									}
								}else{
									// FOR NON-CUSTOMER (SUPPLIER OR WAREHOUSE)
								
									if($this->input->post('doctype1') == 'DO' and $this->input->post('doqty') == null){
										$data['error']="DO / ITO Quantity is required.";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve_edit',$data);
										//$this->load->view('footer');
									}
									if($this->input->post('doctype2') == 'DO' and $this->input->post('doqty') == null){
										$data['error']="DO / ITO Quantity is required.";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve_edit',$data);
										//$this->load->view('footer');
									}
									if($this->input->post('ddate') > $this->input->post('tdate')){
										$data['error']="Invalid Delivery Date.";
										$this->load->view('header',$data);
										$this->load->view('main/wh_del_items_reserve_edit',$data);
										//$this->load->view('footer');
									}
									
									$this->usermodel->bp_upload();
									$this->usermodel->home_wh_out_edit();

									$this->usermodel->truck_add();
									$this->usermodel->uom_add();

									redirect('main/wh_delivery_approve_list');

									// redirect('main/wh_delivery_item_reserve/'.$this->input->post('wh_code_dout'));

								}
							}			
						//}
					
				}
			}
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_item_reserve_ho(){
		
		if($this->session->userdata('logged_in')){

			$this->load->model('b_model');
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['trans_no']=$this->usermodel->trans_no_dout_ho();

			$data['prepared_list'] = $this->usermodel->prepared_list();
			$data['checked_list'] = $this->usermodel->checked_list();
			$data['guard_list'] = $this->usermodel->guard_list();

			$data['cvdate_dout_ho']=$this->usermodel->check_validity_date_dout_ho();

			$this->form_validation->set_rules('ref', 'Reference No. 1', 'required|trim');

			if($this->form_validation->run() == TRUE){
				if($this->input->post('get_do')){

					// CHECK IF DO EXISTS IN SAP DATABASE
					if ($this->usermodel->check_if_DO_is_deliver_in_SAP()) {
						$data['error'] = "DO has already been closed on SAP by our Makati Team";
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve_ho', $data);
						
					} else {

						if(!$this->usermodel->check_if_DO_exists_from_SAP()){
							$data['error'] = "No Record Found";
							$this->load->view('header',$data);
							$this->load->view('main/wh_del_items_reserve_ho', $data);
						}else{

							//  CHECK IF DO ALREADY EXISTS ON SAP DO DATA IMS
							if(!$this->usermodel->check_if_DO_exists_from_SAP_DO_DATA()){
								// INSERT DATA FROM SAP TO sap_do_data FOR HISTORICAL RECORDS
								$this->usermodel->insert_DO_to_SAP_DO_DATA();
							}

							//  CHECK IF DO ALREADY EXISTS ON IMS IF YES CHECK FOR DO BALANCE AND DISPLAY IT
							if($this->usermodel->check_if_DO_exists_from_WHIR()){

								$data['check_bal'] = $this->usermodel->check_DO_qty_from_whir();

								if($data['check_bal'] == 1){
									$data['info'] = "DO QTY HAS BEEN ALL SERVED";
									$this->load->view('header',$data);
									$this->load->view('main/wh_del_items_reserve_ho', $data);
								}elseif($data['check_bal'] == 2){
									$data['info'] = "There's a pending document to be APPROVE or OUT for this DO. No. ". $this->input->post('ref');
									$this->load->view('header',$data);
									$this->load->view('main/wh_del_items_reserve_ho', $data);
								}else{
									$data['records'] = $this->usermodel->get_DO_from_SAP();
									$temp = array();
									$temp = $this->usermodel->check_DO_qty_from_whir(); 
									$data['do_qty_remain'] = $temp;
									$this->load->view('header',$data);
									$this->load->view('main/wh_del_items_reserve_ho', $data);
								}
								
							}else{
								$data['records'] = $this->usermodel->get_DO_from_SAP();
								$this->load->view('header',$data);
								$this->load->view('main/wh_del_items_reserve_ho', $data);							
							}

						}
					}

				}

				if($this->input->post('submit_do')){

					if($this->input->post('ref2') == ""){
						$data['error']='Reference No.2 Field is required';
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve_ho',$data);
					}elseif($this->input->post('whqty') == ""){
						$data['error']='Actual Quantity Field is required';
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve_ho',$data);
					// }elseif($this->input->post('dr_remarks') == ""){
					// 	$data['error']='Warehouse Remarks Field is required';
					// 	$this->load->view('header',$data);
					// 	$this->load->view('main/wh_del_items_reserve_ho',$data);
					// }elseif($this->input->post('trucktime') == ""){
					// 	$data['error']='Truckers Arrival Time Field is required';
					// 	$this->load->view('header',$data);
					// 	$this->load->view('main/wh_del_items_reserve_ho',$data);
					}elseif($this->input->post('tpnum') == ""){
						$data['error']='Truck Plate No. Field is required';
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve_ho',$data);
					}elseif($this->input->post('tdrvr') == ""){
						$data['error']='Truck Driver Field is required';
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve_ho',$data);
					}elseif($this->input->post('shiptime') == ""){
						$data['error']='Shipment Time Field is required';
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_reserve_ho',$data);
					}else{
						// if ($this->usermodel->wh_itemqty_validation_sap()){
						// 	$data['error']='Insufficient Quantity!';
						// 	$this->load->view('header',$data);
						// 	$this->load->view('main/wh_del_items_reserve_ho',$data);
						// }else
						if($this->input->post('ddate') > $this->input->post('shipdate')){
							$data['error']="Invalid Shipment | Pick-up Date";
							$this->load->view('header',$data);
							$this->load->view('main/wh_del_items_reserve_ho',$data);
						}elseif($this->usermodel->check_dr_if_exists_1_sap()){
							$data['error']="DR Number already exists.";
							$this->load->view('header',$data);
							$this->load->view('main/wh_del_items_reserve_ho',$data);
						}elseif($this->usermodel->check_dr_if_exists_2_sap()){
							$data['error']="DR Number already exists.";
							$this->load->view('header',$data);
							$this->load->view('main/wh_del_items_reserve_ho',$data);
						}elseif($this->usermodel->check_wis_if_exists_1_sap()){
							$data['error']="WIS Number already exists.";
							$this->load->view('header',$data);
							$this->load->view('main/wh_del_items_reserve_ho',$data);
						}elseif($this->usermodel->check_wis_if_exists_2_sap()){
							$data['error']="WIS Number already exists.";
							$this->load->view('header',$data);
							$this->load->view('main/wh_del_items_reserve_ho',$data);
						}else{
							$this->usermodel->bp_upload_sap();
							$this->usermodel->home_wh_out_sap();
							$this->usermodel->home_wh_out_sap_link();

							$this->usermodel->do_nappr();

							// $this->b_model->send_delivery_out_sap();
							// $this->b_model->send_delivery_out_ae_sap();

							$this->usermodel->truck_add_sap();
							$this->usermodel->uom_add_sap();

							// TAG RECORD IN WHIR IF DATA IS FROM SAP
							$this->usermodel->tag_sap_data();

							$trans_no = "";
							$trans_no = "/".$this->input->post('trans_no');

							if($this->input->post('doctype1') == 'DR' OR $this->input->post('doctype2') == 'DR'){
								$doc_type = "/dout_01_DR";
							}elseif($this->input->post('doctype1') == 'WIS' OR $this->input->post('doctype2') == 'WIS'){
								$doc_type = "/dout_01_WIS";
							}

							// $this->usermodel->empty_sap_do_data();	

							redirect('main/wh_delivery_item_reserve_ho/'.$this->input->post('wh_code_dout').$doc_type.$trans_no);

						}
					}

				}
			}else{
				$this->load->view('header',$data);
				$this->load->view('main/wh_del_items_reserve_ho', $data);
			}

		}
	}

	function wh_delivery_item_out(){
		if($this->session->userdata('logged_in')){
			$tokens = explode('/', current_url());
			date_default_timezone_set("Asia/Manila");
			$data['cdate'] = date('Y-m-d');
			$get = $tokens[sizeof($tokens)-1];
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['deltype']=$this->usermodel->get_deltype($get);
			$data['doctype']=$this->usermodel->get_doctype_Out();
			//From SAP
			//$data['bpname']=$this->usermodel->get_bplistDdown_SAP($get);
			//$data['item']=$this->usermodel->item_get_SAP();
			
			$data['bpname']=$this->usermodel->get_bplistDdown($get);
			$data['item']=$this->usermodel->item_get();
			
			$data['warehouse']=$this->usermodel->whname_select($get);
			$this->form_validation->set_rules('whitem','Item','required');
			$this->form_validation->set_rules('whqty','Quantity','required|numeric');
			$this->form_validation->set_rules('wh','Warehouse','required');
			$this->form_validation->set_rules('bpname','Reference Name','required');
			$this->form_validation->set_rules('ref','Reference No. 1','required');
			if($tokens[sizeof($tokens)-4] == 'get'){
				$data['itemvalue']= $tokens[sizeof($tokens)-3];
				$data['deftype']=1;
			}
			else{
				$data['itemvalue'] = 'select';
				$data['deftype']='select';
			}
			if ($this->form_validation->run() == false){
				$this->load->view('main/wh_del_items_out',$data);
			}
			else{
				if ($this->input->post('wh') == "-Select-"){
					$data['error']='Warehouse field is required.';
					$this->load->view('main/wh_del_items_out',$data);
				}
				else{
					if ($q=$this->usermodel->wh_itemqty_validation()){
						$data['error']='Insufficient Quantity.';
						$this->load->view('main/wh_del_items_out',$data);
					}
					else{
						$this->usermodel->home_wh_add();
						redirect('main/home');
					}
				}
			}
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_item_in(){
		//SAP88
		$this->load->library('email');
		if($this->session->userdata('logged_in')){
			$this->load->model('b_model');
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			date_default_timezone_set("Asia/Manila");
			$data['cdate'] = date('Y-m-d');
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['deltype']=$this->usermodel->get_deltype();
			$data['doctype']=$this->usermodel->get_doctype_In();
			$data['warehouse']=$this->usermodel->whname_select($get);
			$data['rtn']=$this->usermodel->return_cat();
			$data['trucks']=$this->usermodel->truck_list();
			$data['uom']=$this->usermodel->uom_list();
			$data['trans_no']=$this->usermodel->trans_no_din();

			$data['prepared_list'] = $this->usermodel->prepared_list();
			$data['guard_list'] = $this->usermodel->guard_list();

			$data['cvdate_din'] = $this->usermodel->check_validity_date_din();

			//Modify
			//From SAP
			$data['bpname']=$this->usermodel->get_bplistDdown_SAP($get);
			// $data['item']=$this->usermodel->item_get_SAP();
			$data['item']=$this->usermodel->item_get();
			
			$data['sub_type_del_in']=$this->usermodel->sub_type_del_in();

			$data['sub_del_type_in_customer']=$this->usermodel->get_bplistDdown_SAP_Customer($get);
			$data['sub_del_type_in_supplier']=$this->usermodel->get_bplistDdown_SAP_Customer($get);
			$data['sub_del_type_in_warehouse']=$this->usermodel->get_bplistDdown_SAP_Warehouse($get);

			//Local
			///$data['bpname']=$this->usermodel->get_bplistDdown();
			//$data['item']=$this->usermodel->item_get();
			// echo $this->uri->segment(3);
			// if($this->uri->segment(3) == "Search"){
				// echo "Search";
			// }
			//else{
				
			//}
			if($this->input->post('sapdo')){
				//echo 'SAP';
				$data['sapdetails']=$this->usermodel->getDO_SAP();
				$this->load->view('header',$data);
				$this->load->view('main/wh_del_items_in',$data);
				// $this->load->view('footer');
			}
			else{
				$data['msg']="";
				$this->form_validation->set_rules('whitem','Item','required');
				$this->form_validation->set_rules('whqty','Actual Quantity','required|numeric');
				$this->form_validation->set_rules('wh','Warehouse','required');
				$this->form_validation->set_rules('bpname','Reference Name','required');
				$this->form_validation->set_rules('ref','Reference No. 1','required');
				$this->form_validation->set_rules('uom','Unit of Measurement','required');
				$this->form_validation->set_rules('ddate','Posting Date','required');

				$this->form_validation->set_rules('remarks','Remarks','required');

				if($this->input->post('sub_type_del_in') <> 'DI_06'){
					$this->form_validation->set_rules('tdate','Shipment Date','required');
				}
				
				// $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

				if ($this->form_validation->run() == false){
					$this->load->view('header',$data);
					$this->load->view('main/wh_del_items_in',$data);
					// $this->load->view('footer');
				}
				else{
					if($q=$this->usermodel->validation_del_in_ref()){
						$data['error']="Reference Type and Number already encoded.";
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_in',$data);
						// $this->load->view('footer');
					}
					elseif($this->input->post('doctype1')=='ITO' and $this->input->post('itoqty') == null or $this->input->post('doctype2')=='ITO' and $this->input->post('itoqty') == null){
						$data['error']="ITO Quantity is Required.";
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_in',$data);
						// $this->load->view('footer');
					}elseif($this->input->post('whqty') <= 0){
						$data['error']="Actual Quantity must be greater than 0";
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_in',$data);
					}else{

						$sub_type_del_in = $this->input->post('sub_type_del_in');

						if($sub_type_del_in == "DI_04"){
							if($this->input->post("doctype1") == "DO" AND $this->input->post("doctype2") == "DR"){
								$this->usermodel->bp_upload();
								$this->usermodel->SAP_item_add();
								$this->usermodel->home_wh_add();

								$this->usermodel->di_nappr();

								$this->b_model->send_delivery_in();
								
								$this->usermodel->truck_add();
								$this->usermodel->uom_add();

								// if($this->input->post('doctype1') == "RR" OR $this->input->post('doctype2') == "RR"){
								// 	$doc_type = "/din_01_RR";
								// 	redirect('main/wh_delivery_item_in/'.$this->input->post('wh_code_din').$doc_type);
								// }else{
								// 	$doc_type = "/din_01_RR";
								// 	redirect('main/wh_delivery_item_in/'.$this->input->post('wh_code_din').$doc_type);
								// }
								
							}else{
								$data['error']="Reference Type 1 must be DO and Reference Type 2 must be DR";
								$this->load->view('header',$data);
								$this->load->view('main/wh_del_items_in',$data);
							}

							// IN FOR OTHER REFERENCES
							$this->usermodel->bp_upload();
							$this->usermodel->SAP_item_add();
							$this->usermodel->home_wh_add();

							$this->usermodel->di_nappr();

							$this->b_model->send_delivery_in();
								
							$this->usermodel->truck_add();
							$this->usermodel->uom_add();

							if($this->input->post('doctype1') == "RR" OR $this->input->post('doctype2') == "RR"){
								$doc_type = "/din_01_RR";
								redirect('main/wh_delivery_item_in/'.$this->input->post('wh_code_din').$doc_type);
							}else{
								$doc_type = "/din_01_RR";
								redirect('main/wh_delivery_item_in/'.$this->input->post('wh_code_din').$doc_type);
							}

						}else{
							$this->usermodel->bp_upload();
							$this->usermodel->SAP_item_add();
							$this->usermodel->home_wh_add();

							$this->usermodel->di_nappr();

							$this->usermodel->truck_add();
							$this->usermodel->uom_add();

							$doc_type = "/din_01_RR";
							redirect('main/wh_delivery_item_in/'.$this->input->post('wh_code_din').$doc_type);
							
						}

					}
					
				}
			}
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_item_in_edit(){
		//SAP88
		$this->load->library('email');
		if($this->session->userdata('logged_in')){
			$this->load->model('b_model');
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-2];

			$data['del_in_rec']=$this->usermodel->del_in_edit();

			date_default_timezone_set("Asia/Manila");
			$data['cdate'] = date('Y-m-d');
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['deltype']=$this->usermodel->get_deltype();
			$data['doctype']=$this->usermodel->get_doctype_In();
			$data['warehouse']=$this->usermodel->whname_select($get);
			$data['rtn']=$this->usermodel->return_cat();
			$data['trucks']=$this->usermodel->truck_list();
			$data['uom']=$this->usermodel->uom_list();
			//Modify
			//From SAP

			$data['bpname']=$this->usermodel->get_bplistDdown_SAP($get);
			// $data['item']=$this->usermodel->item_get_SAP();
			$data['item']=$this->usermodel->item_get();
			

			$data['sub_type_del_in']=$this->usermodel->sub_type_del_in();

			$data['sub_del_type_in_customer']=$this->usermodel->get_bplistDdown_SAP_Customer($get);
			$data['sub_del_type_in_supplier']=$this->usermodel->get_bplistDdown_SAP_Supplier($get);
			$data['sub_del_type_in_warehouse']=$this->usermodel->get_bplistDdown_SAP_Warehouse($get);


			if($this->input->post('sapdo')){
				//echo 'SAP';
				$data['sapdetails']=$this->usermodel->getDO_SAP();
				$this->load->view('header',$data);
				$this->load->view('main/wh_del_items_in_edit',$data);
				// $this->load->view('footer');
			}
			else{
				$data['msg']="";
				$this->form_validation->set_rules('whitem','Item','required');
				$this->form_validation->set_rules('whqty','Actual Quantity','required|numeric');
				$this->form_validation->set_rules('wh','Warehouse','required');
				$this->form_validation->set_rules('bpname','Reference Name','required');
				$this->form_validation->set_rules('ref','Reference No. 1','required');
				$this->form_validation->set_rules('uom','Unit of Measurement','required');
				$this->form_validation->set_rules('ddate','Posting Date','required');

				$this->form_validation->set_rules('remarks','Remarks','required');

				if($this->input->post('sub_type_del_in') <> 'DI_06'){
					$this->form_validation->set_rules('tdate','Shipment Date','required');
				}


				// $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

				if ($this->form_validation->run() == false){
					$this->load->view('header',$data);
					$this->load->view('main/wh_del_items_in_edit',$data);
					// $this->load->view('footer');
				}
				else{
					if($this->input->post('doctype1')=='ITO' and $this->input->post('itoqty') == null or $this->input->post('doctype2')=='ITO' and $this->input->post('itoqty') == null){
						$data['error']="ITO Quantity is Required.";
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_in_edit',$data);
						// $this->load->view('footer');
					}
					else{

						$sub_type_del_in = $this->input->post('sub_type_del_in');

						$this->usermodel->bp_upload();
						$this->usermodel->SAP_item_add();
						$this->usermodel->home_wh_add_edit();

						// if($sub_type_del_in == "DI_04"){
						// 	$this->b_model->send_delivery_in();
						// }

						$this->usermodel->truck_add();
						$this->usermodel->uom_add();

						redirect('main/wh_delivery_approve_list');

					}
					
				}
			}
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_item_mm(){ 
		if($this->session->userdata('logged_in')){
			$this->load->model('b_model');
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			date_default_timezone_set("Asia/Manila");
			$data['cdate'] = date('Y-m-d');
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whlist_code']=$this->usermodel->whlist_mm();
			//$data['doctype']=$this->usermodel->get_doctype_In();
			$data['warehouse']=$this->usermodel->whname_select($get);
			$data['process']=$this->usermodel->def_mm_process_active();
			$data['dtype']=$this->usermodel->def_defaultdeltype_active();
			$data['snmm']=$this->usermodel->sn_mm();
			$data['reftype1']=$this->usermodel->ref_mm_primary();
			$data['reftype2']=$this->usermodel->ref_mm_secondary();
			$data['uom']=$this->usermodel->uom_list();

			$data['trans_no']=$this->usermodel->trans_no_mm();

			$data['cvdate_mm']=$this->usermodel->check_validity_date_mm();

			$error1 = array();
			$data['ARI'] = NULL;
			//Modify
			//From SAP
			$data['bpname']=$this->usermodel->customer_mm($get);
			// $data['item']=$this->usermodel->item_get_SAP();
			$data['item']=$this->usermodel->item_get();
			
			//Local
			//$data['bpname']=$this->usermodel->get_bplistDdown();
			//$data['item']=$this->usermodel->item_get();
			$this->form_validation->set_rules('ref','Reference no.1','required');

			if ($this->form_validation->run() == false){
				$this->load->view('header',$data);
				$this->load->view('main/wh_del_items_mm',$data);
				//$this->load->view('footer',$data);
			}
			else{
				if($q=$this->usermodel->validation_del_in_ref()){
					$data['error']="Reference Type and Number already encoded.";
					$this->load->view('main/wh_del_items_mm',$data);
				}
				else{
					$val = 0;
					$data1 = array();
					
					$i=1;
					$count1 = 0;
					while ($i<=10){
						if ($this->input->post('chk'.$i) == true){
							$get = $this->input->post('iloc'.$i);
							$item = $this->input->post('idesc'.$i);
							$qty = $this->input->post('iqty'.$i);
							if($q=$this->usermodel->wh_mm_itemqty_validation($get,$item,$qty)){
								$val += 1;		
								$error1[$val]='Insufficient Item '.$i.' Quantity!';
							}
							foreach ($data1 as  $r){
								if ($r == $item){
									$data['idescerror']='Delivery Out:Same Item is not allowed!';
									$val +=1;
								}
							}
							$data1[$count1] = $item;
						}
						$count1++;
						$i++;
					}
					//Delivery In
					$o=1;
					$data2 = array();
					$count2=0;
					while ($o<=10){
						if ($this->input->post('ochk'.$o) == true){
							$get = $this->input->post('oiloc'.$o);
							$item = $this->input->post('oidesc'.$o);
							$qty = $this->input->post('oiqty'.$o);
							foreach ($data2 as  $r){
								if ($r == $item){
									$data['idescerror2']='Delivery In:Same Item is not allowed!';
									$val +=1;
								}
							}
							$data2[$count2] = $item;
						}
						$count2++;
						$o++;
					}
					if ($val == 0){
						$ii=1;
						while($ii<=10){
							if ($this->input->post('chk'.$ii) == true){
								$whname= $this->input->post('iloc'.$ii);
								$idesc=$this->input->post('idesc'.$ii);
								$iuom=$this->input->post('iunit'.$ii);
								$iqty=$this->input->post('iqty'.$ii);	
								$this->usermodel->del_mm_item3($whname,$idesc,$iuom,$iqty);
							}
							if ($this->input->post('ochk'.$ii) == true){
								$owhname= $this->input->post('oiloc'.$ii);
								$oidesc=$this->input->post('oidesc'.$ii);
								$oiuom=$this->input->post('oiunit'.$ii);
								$oiqty=$this->input->post('oiqty'.$ii);	
								$this->usermodel->del_mm_item_in($owhname,$oidesc,$oiuom,$oiqty);
							}
							$ii++;
						}
						//Modify
						$this->usermodel->bp_upload();
						$this->usermodel->SAP_item_add();
						
						// $this->usermodel->add_transnum();
						$this->b_model->create_send();
						redirect('main/wh_delivery_item_mm/'.$this->input->post('wh_code_mm'));
					}
					else{
						$data['outval'] = $error1;
						$this->load->view('header',$data,$error1);
						$this->load->view('main/wh_del_items_mm',$error1,$data);
						//$this->load->view('footer',$data,$error1);
					}
				}
			}
		}
		else{
			redirect('main');
		}
	}

	function get_available(){

		$item_code = $this->input->post('itemcode');
		$wh_code = $this->input->post('whcode');
		// $dd = array();
		$result = $this->usermodel->get_available($item_code, $wh_code);
		$dd['avail_data'] = $result['row'];
		// foreach($dd as $r1){

		// 	if ($r1->sqty == null){
		// 		$sqty = 0;}
		// 	else{
		// 		$sqty = $r1->sqty;}
		// 	if ($r1->tqty == null){
		// 		$tqty = 0;}
		// 	else{
		// 		$tqty = $r1->tqty;}
		// 	if ($r1->rqty == null){
		// 		$rqty = 0;}
		// 	else{
		// 		$rqty = $r1->rqty;}
		// 	$qty = ($sqty - ($tqty + $rqty));

		// 	// echo $qty;

		// }

		echo json_encode($dd);

	}

	function user_access_list(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records']=$this->usermodel->userlist();

			if($this->uri->segment(3) <> ""){
				if($this->usermodel->check_if_user_can_delete()){
					$data['can_del'] = 0;
					$data['del_msg'] = "User cannot be deleted";
				}else{
					$data['can_del'] = 1;
					$data['del_msg'] = "Are you sure you want to delete this user <em>". $this->uri->segment(3)."</em> ?";
					
				}	

				if($this->input->post('del_yes')){
					$this->usermodel->user_delete();
				}

			}

			
			$this->load->view('masterdata/usr_list',$data);
		}
		else{
			redirect('main');
		}
	}

	function user_access_update_wh(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->all_whlist();
			//$data['aclist']=$this->usermodel->useraccesslist();
			$data['uwhlist']=$this->usermodel->user_whauselist();
			//$data['ualist']=$this->usermodel->user_accesslist();
			$this->load->view('masterdata/usr_add_access_wh',$data);
			$this->form_validation->set_rules('uname','Name','required');
			if ($this->form_validation->run() == true){
				if ($this->input->post('whouse')<>'-Select-'){
					if ($q=$this->usermodel->access_add_module1()){
						$this->usermodel->access_update_wh();
					}
					else{
						$this->usermodel->access_add_wh();
					}
					// redirect('main/user_access_list');
					redirect('main/user_access_update_wh/'.$this->input->post('uname'));
				}
			}
		}
		else{
			redirect('main');
		}
	}

	function user_access_update_mod(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['aclist']=$this->usermodel->useraccesslist();
			$data['ualist']=$this->usermodel->user_accesslist();
			$this->load->view('masterdata/usr_add_access_module',$data);
			$this->form_validation->set_rules('uname','Name','required');
			if ($this->form_validation->run() == true){
				if ($this->input->post('access')<>'-Select-'){
					if($q=$this->usermodel->access_add_module2()){
						$this->usermodel->access_update_mod();
					}
					else{
						$this->usermodel->access_add_mod();
					}
					// redirect('main/user_access_list');
					redirect('main/user_access_update_mod/'.$this->input->post('uname'));
				}
			}
		}
		else{
			redirect('main');
		}
	}

	function add_user(){

		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();

			$this->form_validation->set_rules('uname','Username','trim|xss_clean|required');
			$this->form_validation->set_rules('fullname', 'Name', 'trim|xss_clean|required');
			$this->form_validation->set_rules('pass', 'Password', 'trim|xss_clean|required');
			$this->form_validation->set_rules('eadd', 'Email Address', 'trim|xss_clean|valid_email');
			$this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
			$this->form_validation->set_rules('mobile_no', 'Address', 'trim|xss_clean');

			// $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

			if ($this->form_validation->run() == true){

				if($this->usermodel->check_if_user_id_exists()){
					$data['error']="Username already exists";
					$this->load->view('masterdata/add_user',$data);
				}elseif($this->usermodel->check_if_fullname_exists()){
					$data['error']="Name already exists";
					$this->load->view('masterdata/add_user',$data);
				}elseif($this->usermodel->check_if_user_email_exists()){
					$data['error']="Email Address already exists";
					$this->load->view('masterdata/add_user',$data);
				}else{
					$this->usermodel->add_user();
					// redirect('main/user_access_list');
					// 1 means succes
					redirect('main/add_user/'.'1');
				}
			}else{
				$this->load->view('masterdata/add_user',$data);
			}
		}
		else{
			redirect('main');
		}

	}

	function user_edit(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();

			$data['records']=$this->usermodel->user_edit_records();

			// $this->form_validation->set_rules('uname','Username','trim|xss_clean|required');
			// $this->form_validation->set_rules('fullname', 'Name', 'trim|xss_clean|required');
			// $this->form_validation->set_rules('pass', 'Password', 'trim|xss_clean|required');
			$this->form_validation->set_rules('eadd', 'Email Address', 'trim|xss_clean|valid_email');
			$this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
			$this->form_validation->set_rules('mobile_no', 'Address', 'trim|xss_clean');

			$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

			if ($this->form_validation->run() == true){

				if($this->usermodel->check_edit_if_fullname_exists()){
					$data['error']="Name already exists";
					$this->load->view('masterdata/user_edit',$data);
				}elseif($this->usermodel->check_edit_if_email_exists()){
					$data['error']="Email Address already exists";
					$this->load->view('masterdata/user_edit',$data);
				}else{
					$this->usermodel->user_edit();
					redirect('main/user_access_list');
				}
			}else{
				$this->load->view('masterdata/user_edit',$data);
			}
		}
		else{
			redirect('main');
		}
	}

	function user_delete(){
		if($this->session->userdata('logged_in')){
			$this->usermodel->user_delete();
			redirect('main/user_access_list');
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_unserve_list(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			
			if ($this->input->post('sdate')  == '' AND $this->input->post('edate') == ''){
				$data['reserverecord']=$this->usermodel->wh_delivery_unserve_list_active_search();
				$data['wn'] = $this->input->post('whouse');
				$data['total_unl']=$this->usermodel->total_unl();
			}
			else{
				$data['reserverecord']=$this->usermodel->wh_delivery_unserve_list_active_search_date_search();
				$data['wn'] = $this->input->post('whouse');
				$data['total_unl']=$this->usermodel->total_unl_sort();
			}
			$this->load->view('reports/wh_del_unserve_list',$data);

		}
		else{
			redirect('main');
		}
	}
	
	function ito_summary(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			$data['reserverecord'] = $this->usermodel->ito_summary();
			$this->load->view('main/ito_summary_report',$data);
		}
		else{
			redirect('main');
		}
	}
	
	function jo_summary(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			$data['reserverecord'] = $this->usermodel->jo_summary();
			$this->load->view('main/jo_summary',$data);
		}
		else{
			redirect('main');
		}
	}
	
	function wh_delivery_rr_summary(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			if ($this->input->post('sdate')  == '' AND $this->input->post('edate') == ''){
				$data['reserverecord']=$this->usermodel->wh_delivery_rr_summary_search();
				$data['wn'] = $this->input->post('whouse');
				$data['total_rr']=$this->usermodel->total_rr();
			}
			else{
				$data['reserverecord']=$this->usermodel->wh_delivery_rr_summary_date_search();
				$data['wn'] = $this->input->post('whouse');
				$data['total_rr']=$this->usermodel->total_rr_sort();
			}
			$this->load->view('reports/wh_del_rr_list',$data);
		}
		else{
			redirect('main');
		}
	}
	function wh_delivery_dr_summary(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			if ($this->input->post('sdate')  == '' AND $this->input->post('edate') == ''){
				$data['reserverecord']=$this->usermodel->wh_delivery_dr_summary_search();
				$data['wn'] = $this->input->post('whouse');
				$data['total_dr']=$this->usermodel->total_dr();
			}
			else{
				$data['reserverecord']=$this->usermodel->wh_delivery_dr_summary_date_search();
				$data['wn'] = $this->input->post('whouse');
				$data['total_dr']=$this->usermodel->total_dr_sort();
			}
			$this->load->view('reports/wh_del_dr_list',$data);
		}
		else{
			redirect('main');
		}
	}
	
	function wh_delivery_wis_summary(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			if ($this->input->post('sdate')  == '' AND $this->input->post('edate') == ''){
				$data['reserverecord']=$this->usermodel->wh_delivery_wis_summary_search();
				$data['wn'] = $this->input->post('whouse');
				$data['total_wis']=$this->usermodel->total_wis();
			}
			else{
				$data['reserverecord']=$this->usermodel->wh_delivery_wis_summary_date_search();
				$data['wn'] = $this->input->post('whouse');
				$data['total_wis']=$this->usermodel->total_wis_sort();
			}
			$this->load->view('reports/wh_del_wis_list',$data);
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_war_summary(){
		if($this->session->userdata('logged_in')){	
			$this->load->model('report_model');
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			
			if ($this->input->post('sdate')  == '' AND $this->input->post('edate') == ''){
				$data['reserverecord']=$this->report_model->war();
				$data['wn'] = $this->input->post('whouse');
				$data['total_war']=$this->report_model->total_war();
			}
			else{
				$data['reserverecord']=$this->report_model->war_date_search();
				$data['wn'] = $this->input->post('whouse');
				$data['total_war']=$this->report_model->total_war_sort();
			}

			$this->load->view('reports/wh_del_war_list',$data);
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_cancelDO_list(){
		if($this->session->userdata('logged_in')){
			$this->load->model('report_model');
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			
			if ($this->input->post('sdate')  == '' AND $this->input->post('edate') == ''){
				$data['reserverecord']=$this->report_model->cancel_DO();
				$data['wn'] = $this->input->post('whouse');
				$data['total_can']=$this->report_model->total_can();
			}
			else{
				$data['reserverecord']=$this->report_model->cancel_DO_date_search();
				$data['wn'] = $this->input->post('whouse');
				$data['total_can']=$this->report_model->total_can_sort();
			}

			$this->load->view('reports/wh_del_cancelDO_list',$data);
		}
		else{
			redirect('main');
		}
	}
	
	function wh_delivery_ito_list(){
		if($this->session->userdata('logged_in')){
			$this->load->model('report_model');
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			//if ($this->input->post('sdate')  == '' AND $this->input->post('edate') == ''){
			$data['reserverecord']=$this->report_model->ito();
			$this->load->view('main/wh_del_ito_list',$data);
		}
		else{
			redirect('main');
		}
	}
	function item_in_readonly(){
		if($this->session->userdata('logged_in')){
			
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();

			$data['records']=$this->usermodel->get_item_active();
			
			$this->form_validation->set_rules('desti','Destination','required');
			if($this->form_validation->run() == true){
				redirect('main/home');
			}
			else{
				//$this->load->view('header',$data);
				$this->load->view('main/wh_del_items_in_readonly',$data);
				//$this->load->view('footer',$data);
			}
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_trckng_cdel(){
		if($this->session->userdata('logged_in')){
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['user'] = $this->usermodel->signin_user();
			$data['whlist']=$this->usermodel->whlist();
			$data['refname']= $this->input->post('refname');
			$data['refno']=$this->input->post('refno');
			$data['doctype']=$this->usermodel->get_doctype();
			
			$this->form_validation->set_rules('whouse','Warehouse','required');
			if ($this->form_validation->run() == true){
				$data['cdel_record'] = $this->usermodel->wh_delivery_condel_list();

			}
			$data['vwhouse']=$this->input->post('whouse');
			$this->load->view('main/wh_del_trckng_cdel',$data);
		}

		else{
			redirect('main');
		}
	}


	function wh_delivery_trckng_list(){
		if($this->session->userdata('logged_in')){
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['user'] = $this->usermodel->signin_user();
			$data['whlist']=$this->usermodel->whlist();
			//$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['refno']=$this->input->post('refno');
			$data['doctype']=$this->usermodel->get_doctype();
			
			$this->form_validation->set_rules('whouse','Warehouse','required');
			if ($this->form_validation->run() == true){
				$data['reserverecord']=$this->usermodel->wh_delivery_trckng_list();
			}
			$data['vwhouse']=$this->input->post('whouse');
			$this->load->view('main/wh_del_trckng',$data);
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_trckng_list_internal(){
		if($this->session->userdata('logged_in')){
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['user'] = $this->usermodel->signin_user();
			$data['whlist']=$this->usermodel->whlist();
			//$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['refno']=$this->input->post('refno');
			$data['doctype']=$this->usermodel->get_doctype();
			
			$this->form_validation->set_rules('whouse','Warehouse','required');
			if ($this->form_validation->run() == true){
				$data['reserverecord']=$this->usermodel->wh_delivery_trckng_list_internal();
			}
			$data['vwhouse']=$this->input->post('whouse');
			$this->load->view('main/wh_del_trckng_internal',$data);
		}
		else{
			redirect('main');
		}
	}

	function wh_delivery_trckng_monitor(){
		if($this->session->userdata('logged_in')){

			// DATE FORMAT VALIDATION
			function validateDate($date){
			    $d = DateTime::createFromFormat('Y-m-d', $date);
			    return $d && $d->format('Y-m-d') === $date;
			}

			if($this->input->post('emails')){
				$this->load->model('acl_model');
			}
			
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
	
			date_default_timezone_set("Asia/Manila");
			$data['cdate'] = date('Y-m-d');
			$data['records']=$this->usermodel->get_item_active();

			$data['arr_stat']=$this->input->post('arrived');
			$data['acc_stat']=$this->input->post('accepted');
			$data['can_stat']=$this->input->post('canceled');

			$artime_temp = $this->input->post('arr_time');
			$artime = substr($artime_temp, 0,4);

			if($this->input->post('email')){
				//redirect('main/wh_delivery_trckng_list');
				$this->load->model('acl_model');
				//$data['trn_data'] = $this->acl_model->trn_data();
				//$this->load->view('main/email_client',$data);
				$this->acl_model->email_clients();
				redirect('main/wh_delivery_trckng_list');
			}
			else{
			//$this->form_validation->set_rules('source','Source','required');
				$this->form_validation->set_rules('source','Source','required');

				if($this->form_validation->run() == false){
					$this->load->view('main/wh_del_trckng_monitor',$data);
				}
				else{
					// FOR ARRIVED ONLY
					if($this->usermodel->trckng_arrived_validation() == TRUE AND $this->input->post('arrived') == TRUE AND $this->usermodel->trckng_accepted_validation() == TRUE
						AND $this->usermodel->trckng_canceled_validation() == TRUE AND $this->input->post('accepted') == FALSE AND $this->input->post('canceled') == FALSE){
						if($this->input->post('arr_time') == ""){
							$data['error']="Arrived Time Field is required";
							$this->load->view('main/wh_del_trckng_monitor',$data);
						}elseif($this->input->post('arrdate') == ""){
							$data['error']="Arrived Date Field is required";
							$this->load->view('main/wh_del_trckng_monitor', $data);
						}elseif(validateDate($this->input->post('arrdate')) == FALSE){
							$data['error']="Invalid Arrived Date Format";
							$this->load->view('main/wh_del_trckng_monitor', $data);
						}else{
							$this->usermodel->trckng_arrived();
							redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');
						}
						
					// FOR ARRIVED AND ACCEPTED
					}elseif($this->usermodel->trckng_arrived_validation() == TRUE AND $this->usermodel->trckng_accepted_validation() == TRUE AND $this->usermodel->trckng_canceled_validation() == TRUE
						AND $this->input->post('arrived') == TRUE AND $this->input->post('accepted') == TRUE AND $this->input->post('canceled') == FALSE){

						if($this->input->post('qty') == $this->input->post('aqa')+ $this->input->post('aqc')){
							if($this->input->post('accRemarks') == ""){
								$data['error']="Accepted Remarks is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
							}elseif($this->input->post('accdate') == ""){
								$data['error']="Accepted Date Field is required";
								$this->load->view('main/wh_del_trckng_monitor', $data);
							}elseif($this->input->post('accdate') < $this->input->post('arrdate')){
								$data['error']="Unloading Date must be greater than the Arrived Date";
								$this->load->view('main/wh_del_trckng_monitor',$data);
							}elseif($this->input->post('accdate') == $this->input->post('arrdate')){
								if($this->input->post('uld_time') < $artime){
									$data['error']="Unloading Time must be greater than Arrived Time";
									$this->load->view('main/wh_del_trckng_monitor',$data);
								}
							}elseif($this->input->post('uld_time') == ""){
								$data['error']="Unloading Time field is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
							}else{
								$this->usermodel->trckng_arrived();
								$this->usermodel->trckng_accepted();
								redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');
							}
						}else{
							$data['error']="Invalid Quantity Accepted and Canceled.";
							$this->load->view('main/wh_del_trckng_monitor',$data);
						}
					// FOR ARRIVED AND CANCELED
					}elseif($this->usermodel->trckng_arrived_validation() == TRUE AND $this->usermodel->trckng_canceled_validation() == TRUE AND $this->usermodel->trckng_accepted_validation() == TRUE
						AND $this->input->post('arrived') == TRUE AND $this->input->post('canceled') == TRUE AND $this->input->post('accepted') == FALSE){
						if($this->input->post('qty') == $this->input->post('aqa')+ $this->input->post('aqc')){
							if($this->input->post('arrdate') == ""){
								$data['error']="Arrived Date field is required";
								$this->load->view('main/wh_del_trckng_monitor', $data);
							}elseif($this->input->post('candate') == ""){
								$data['error']="Cancel Date field is required";
								$this->load->view('main/wh_del_trckng_monitor', $data);
							}elseif($this->input->post('canRemarks') == ""){
								$data['error']="Cancel Remarks is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
								//$this->load->view('footer',$data);
							}else{
								$this->usermodel->trckng_arrived();
								$this->usermodel->trckng_canceled();
								redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');
							}
						}else{
							$data['error']="Invalid Quantity Accepted and Canceled.";
							$this->load->view('main/wh_del_trckng_monitor',$data);
							//$this->load->view('footer',$data);
						}
					// FOR ARRIVED AND ACCEPTED AND CANCELED
					}elseif($this->usermodel->trckng_arrived_validation() == TRUE AND $this->usermodel->trckng_accepted_validation() == TRUE AND $this->usermodel->trckng_canceled_validation() == TRUE
						AND $this->input->post('arrived') == TRUE AND $this->input->post('accepted') == TRUE AND $this->input->post('canceled') == TRUE){

						if($this->input->post('qty') == $this->input->post('aqa')+ $this->input->post('aqc')){
							if($this->input->post('arrdate') == ""){
								$data['error']="Arrived Date Field is required";
								$this->load->view('main/wh_del_trckng_monitor', $data);
							}elseif($this->input->post('accdate') == ""){
								$data['error']="Accepted Date Field is required";
								$this->load->view('main/wh_del_trckng_monitor', $data);
							}elseif($this->input->post('accRemarks') == "" AND $this->input->post('canRemarks') == ""){
								$data['error']="Accepted and Canceled Remarks is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
								//$this->load->view('footer',$data);
							}elseif($this->input->post('accRemarks') == ""){
								$data['error']="Accepted Remarks is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
								//$this->load->view('footer',$data);
							}elseif($this->input->post('canRemarks') == ""){
								$data['error']="Cancel Remarks is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
								//$this->load->view('footer',$data);
							}elseif($this->input->post('accdate') == $this->input->post('arrdate')){
								if($this->input->post('uld_time') < $artime){
									$data['error']="Unloading Time must be greater than Arrived Time";
									$this->load->view('main/wh_del_trckng_monitor',$data);
								}
							}elseif($this->input->post('uld_time') == ""){
								$data['error']="Unloading Time field is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
							}else{
								$this->usermodel->trckng_arrived();
								$this->usermodel->trckng_canceled();
								$this->usermodel->trckng_accepted();
								redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');
							}
						}else{
							$data['error']="Invalid Quantity Accepted and Canceled.";
							$this->load->view('main/wh_del_trckng_monitor',$data);
							//$this->load->view('footer',$data);
						}
					// FOR ACCEPTED AND CANCELED	
					}elseif($this->usermodel->trckng_accepted_validation() == TRUE AND $this->usermodel->trckng_canceled_validation() == TRUE
						AND $this->input->post('accepted') == TRUE AND $this->input->post('canceled') == TRUE){

						if($this->input->post('qty') == $this->input->post('aqa')+ $this->input->post('aqc')){
							if($this->input->post('accRemarks') == "" AND $this->input->post('canRemarks') == ""){
								$data['error']="Accepted and Canceled Remarks is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
								//$this->load->view('footer',$data);
							}elseif($this->input->post('accRemarks') == ""){
								$data['error']="Accepted Remarks is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
								//$this->load->view('footer',$data);
							}elseif($this->input->post('canRemarks') == ""){
								$data['error']="Cancel Remarks is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
								//$this->load->view('footer',$data);
							}elseif($this->input->post('accdate') == $this->input->post('arrdate')){
								if($this->input->post('uld_time') < $artime){
									$data['error']="Unloading Time must be greater than Arrived Time";
									$this->load->view('main/wh_del_trckng_monitor',$data);
								}
							}elseif($this->input->post('uld_time') == ""){
								$data['error']="Unloading Time field is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
							}else{
								$this->usermodel->trckng_accepted();
								$this->usermodel->trckng_canceled();
								redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');
							}
						}else{
							$data['error']="Invalid Quantity Accepted and Canceled.";
							$this->load->view('main/wh_del_trckng_monitor',$data);
							//$this->load->view('footer',$data);
						}
					// FOR ACCEPTED ONLY
					}elseif($this->usermodel->trckng_accepted_validation() == TRUE AND $this->usermodel->trckng_canceled_validation() == TRUE
						AND $this->input->post('accepted') == TRUE AND $this->input->post('canceled') == FALSE){

						if($this->input->post('qty') == $this->input->post('aqa')){
							if($this->input->post('accRemarks') == ""){
								$data['error']="Accepted Remarks is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
								//$this->load->view('footer',$data);
							}elseif($this->input->post('accdate') == $this->input->post('arrdate')){
								if($this->input->post('uld_time') < $artime){
									$data['error']="Unloading Time must be greater than Arrived Time";
									$this->load->view('main/wh_del_trckng_monitor',$data);
								}
							}elseif($this->input->post('uld_time') == ""){
								$data['error']="Unloading Time field is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
							}else{
								$this->usermodel->trckng_accepted();
								redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');
							}
						}else{
							$data['error']="Invalid Quantity Accepted";
							$this->load->view('main/wh_del_trckng_monitor',$data);
							//$this->load->view('footer',$data);
						}
					// FOR CANCELED ONLY
					}elseif($this->usermodel->trckng_accepted_validation() == TRUE AND $this->usermodel->trckng_canceled_validation() == TRUE
						AND $this->input->post('accepted') == FALSE AND $this->input->post('canceled') == TRUE){
						if($this->input->post('qty') == $this->input->post('aqc')){
							if($this->input->post('canRemarks') == ""){
								$data['error']="Cancel Remarks is required";
								$this->load->view('main/wh_del_trckng_monitor',$data);
								//$this->load->view('footer',$data);
							}else{
								$this->usermodel->trckng_canceled();
								redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');
							}
						}else{
							$data['error']="Invalid Quantity Canceled.";
							$this->load->view('main/wh_del_trckng_monitor',$data);
							//$this->load->view('footer',$data);
						}
					}elseif($this->usermodel->trckng_arrived_validation() == TRUE AND $this->usermodel->trckng_accepted_validation() == FALSE AND $this->usermodel->trckng_canceled_validation() == TRUE
						AND $this->input->post('arrived') == TRUE AND $this->input->post('accepted') == TRUE AND $this->input->post('canceled') == FALSE){

						$this->usermodel->trckng_arrived();
						redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');
					
					}elseif($this->usermodel->trckng_arrived_validation() == TRUE AND $this->usermodel->trckng_accepted_validation() == FALSE AND $this->usermodel->trckng_canceled_validation() == FALSE
						AND $this->input->post('arrived') == TRUE AND $this->input->post('accepted') == TRUE AND $this->input->post('canceled') == TRUE){

						$this->usermodel->trckng_arrived();
						redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');
					
					}else{
						redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');	
					}

					// if($this->input->post('qty') == $this->input->post('aqa')+ $this->input->post('aqc')){
					// // if($this->input->post('qty') == $this->input->post('aqa')+ $this->input->post('aqc') AND $this->input->post('arrived') == true){
					// 	if($q = $this->usermodel->trckng_accepted_validation() == true and $this->input->post('accepted') == true){
					// 		$this->usermodel->trckng_accepted();
					// 	}
					// 	if($q = $this->usermodel->trckng_canceled_validation() == true and $this->input->post('canceled') == true){
					// 		$this->usermodel->trckng_canceled();
					// 	}
				
					// 	// redirect('main/wh_delivery_trckng_list');

					// 	redirect('main/wh_delivery_trckng_monitor/'.$this->uri->segment(3), 'refresh');
					// }
					
					// else{
					// 	$data['error']="Invalid Quantity Accepted and Canceled.";
					// 	$this->load->view('main/wh_del_trckng_monitor',$data);
					// 	$this->load->view('footer',$data);
					// }
				}
			}
		}
		else{
			redirect('main');
		}
	}
	
	function wh_delivery_item_in_getsap(){
		//SAP88
		$this->load->library('email');
		if($this->session->userdata('logged_in')){
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			date_default_timezone_set("Asia/Manila");
			$data['cdate'] = date('Y-m-d');
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['deltype']=$this->usermodel->get_deltype();
			$data['doctype']=$this->usermodel->get_doctype_In();
			$data['warehouse']=$this->usermodel->whname_select($get);
			
			$data['ponum']=$this->input->post('doctype1');
			//echo $this->input->post('doctype1');
			//Modify
			//From SAP
			//$data['bpname']=$this->usermodel->get_bplistDdown_SAP($get);
			//$data['item']=$this->usermodel->item_get_SAP();
			
			//Local
			$data['bpname']=$this->usermodel->get_bplistDdown();
			$data['item']=$this->usermodel->item_get();

			$this->form_validation->set_rules('whitem','Item','required');
			$this->form_validation->set_rules('whqty','Quantity','required|numeric');
			$this->form_validation->set_rules('wh','Warehouse','required');
			$this->form_validation->set_rules('bpname','Reference Name','required');
			$this->form_validation->set_rules('ref','Reference No. 1','required');
			$this->form_validation->set_rules('uom','Unit of Measurement','required');
			if ($this->form_validation->run() == false){
				$this->load->view('header',$data);
				$this->load->view('main/wh_del_items_in_getsap',$data);
				$this->load->view('footer');
			}
			else{
				if($q=$this->usermodel->validation_del_in_ref()){
					$data['error']="Reference Type and Number already encoded.";
					$this->load->view('main/wh_del_items_in',$data);
				}
				else{
					//$this->usermodel->bp_upload();
					//$this->usermodel->SAP_item_add();
					$this->usermodel->home_wh_add();

					redirect('main/home');
				}
			}
		}
		else{
			redirect('main');
		}
	}
	function mm_list(){
		if($this->session->userdata('logged_in')){
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['user'] = $this->usermodel->signin_user();
			$data['whlist']=$this->usermodel->whlist();
			
			$data['refname']= $this->input->post('refname');
			$data['refno']=$this->input->post('refno');
			$data['doctype']=$this->usermodel->get_doctype();
			$this->form_validation->set_rules('whouse','Warehouse','required');
			if ($this->form_validation->run() == true){
				$data['reserverecord']=$this->usermodel->wh_delivery_mm_list();
			}
			$data['vwhouse']=$this->input->post('whouse');
			$this->load->view('main/wh_del_update_mm',$data);
		}
		else{
			redirect('main');
		}
	}
	
	function mm_update(){
		if($this->session->userdata('logged_in')){
			date_default_timezone_set("Asia/Manila");
			$data['cdate'] = date('Y-m-d');
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['process']=$this->usermodel->def_mm_process_active();
			$data['dtype']=$this->usermodel->def_defaultdeltype_active();
			$data['snmm']=$this->uri->segment(3);
			$error1 = array();
			$data['records'] = $this->usermodel->mm_update_get();
			$data['itemout']=$this->usermodel->mm_update_iteoout_get();
			$data['itemin']=$this->usermodel->mm_update_itemin_get();
			
			$data['item']=$this->usermodel->item_get();
			$this->form_validation->set_rules('ref','Reference no.1','required');
			if ($this->form_validation->run() == false){
				$this->load->view('header',$data);
				$this->load->view('main/wh_del_items_mm_update',$data);
				//$this->load->view('footer',$data);
			}
			else{
				if($q=$this->usermodel->validation_del_in_ref()){
					$data['error']="Reference Type and Number already encoded.";
					$this->load->view('header',$data);
					$this->load->view('main/wh_del_items_mm_update',$data);
					//$this->load->view('footer',$data);
				}
				else{
					$val = 0;
					$data1 = array();
					
					$i=1;
					$count1 = 0;
					while ($i<=10){
						if ($this->input->post('chk'.$i) == true){
							$get = $this->input->post('iloc'.$i);
							$item = $this->input->post('idesc'.$i);
							$qty = $this->input->post('iqty'.$i);
							if($q=$this->usermodel->wh_mm_itemqty_validation($get,$item,$qty)){
								$val += 1;		
								$error1[$val]='Insufficient Item '.$i.' Quantity!';
							}
							foreach ($data1 as  $r){
								if ($r == $item){
									$data['idescerror']='Delivery Out:Same Item is not allowed!';
									$val +=1;
								}
							}
							$data1[$count1] = $item;
						}
						$count1++;
						$i++;
					}
					//Delivery In
					$o=1;
					$data2 = array();
					$count2=0;
					while ($o<=10){
						if ($this->input->post('ochk'.$o) == true){
							$get = $this->input->post('oiloc'.$o);
							$item = $this->input->post('oidesc'.$o);
							$qty = $this->input->post('oiqty'.$o);
							foreach ($data2 as  $r){
								if ($r == $item){
									$data['idescerror2']='Delivery In:Same Item is not allowed!';
									$val +=1;
								}
							}
							$data2[$count2] = $item;
						}
						$count2++;
						$o++;
					}
					if ($val == 0){
						$ii=1;
						while($ii<=10){
							if ($this->input->post('chk'.$ii) == true){
								$whname= $this->input->post('iloc'.$ii);
								$idesc=$this->input->post('idesc'.$ii);
								$iuom=$this->input->post('iunit'.$ii);
								$iqty=$this->input->post('iqty'.$ii);	
								$this->usermodel->del_mm_item3($whname,$idesc,$iuom,$iqty);
							}
							if ($this->input->post('ochk'.$ii) == true){
								$owhname= $this->input->post('oiloc'.$ii);
								$oidesc=$this->input->post('oidesc'.$ii);
								$oiuom=$this->input->post('oiunit'.$ii);
								$oiqty=$this->input->post('oiqty'.$ii);	
								$this->usermodel->del_mm_item_in($owhname,$oidesc,$oiuom,$oiqty);
							}
							$ii++;
						}
						redirect('main');
					}
					else{
						$data['outval'] = $error1;
						$this->load->view('header',$data);
						$this->load->view('main/wh_del_items_mm_update',$data);
						//$this->load->view('footer',$data);
					}
				}
			}
		}
		else{
			redirect('main');
		}
	}
	function mm_done(){
		if($this->session->userdata('logged_in')){
			$this->usermodel->mm_done();
			redirect('main/mm_list');
		}
		else{
			redirect('main');
		}
	}	
	function print_dr(){
		$this->load->model('acl_model');
		$data['page_title'] = 'DR';
		$data['print'] = $this->acl_model->dr_layout();
		$this->load->view('print_dr', $data);
		
	}
	
	function notif_end_day(){
		$this->load->model('acl_model');	
		$this->load->view('email_them');
	}

	function send_del_ret_count(){
		$this->load->model('acl_model');	
		$this->load->view('send_del_ret_count');
	}

	function notif_end_day_test(){
		$this->load->model('acl_model');	
		$this->load->view('email_them_test');
	}

	function update_mand(){
		$this->usermodel->update_stock_bal_tbl();
	}
	
	function test_page(){
		$this->load->view('test_page'); 
	}
	
	function dspr(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			$data['reserverecord'] = $this->usermodel->dspr();
			$this->load->view('reports/dspr',$data);
		}
		else{
			redirect('main');
		}
	}

	function dspr2(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			// $data['dspr_record'] = $this->usermodel->dspr();
			$data['wn'] = $this->input->post('whouse');


			if($this->input->post('posting_date_start') <> "" AND $this->input->post('posting_date_end') <> ""){
				$data['dspr_record'] = $this->usermodel->dspr_sort();
				$data['wn'] = $this->input->post('whouse');
				$data['bb'] = 'Y';
			}

			elseif($this->input->post('aofdate') <> ""){
				$data['dspr_record'] = $this->usermodel->dspr_sort_aofdate();
				$data['wn'] = $this->input->post('whouse');
				$data['bb'] = '';
			}else{

				$data['dspr_record'] = "";
			}

			$this->load->view('reports/dspr2',$data);
		}
		else{
			redirect('main');
		}
	}

	function summary_of_receipts(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			

			if($this->input->post('posting_date_start') == "" AND $this->input->post('posting_date_end') == ""){
				$data['summary_of_receipts'] = $this->usermodel->summary_of_receipts();
				$data['wn'] = $this->input->post('whouse');
				$data['total_sor']=$this->usermodel->total_sor();
			}else{
				$data['summary_of_receipts'] = $this->usermodel->summary_of_receipts_sort();
				$data['wn'] = $this->input->post('whouse');
				$data['total_sor']=$this->usermodel->total_sor_sort();
			}

			$this->load->view('reports/summary_of_receipts', $data);

		}
		else{
			redirect('main');
		}
	}

	function summary_of_issuance(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();

			if($this->input->post('posting_date_start') == "" AND $this->input->post('posting_date_end') == ""){
				$data['summary_of_issuance'] = $this->usermodel->summary_of_issuance();
				$data['wn'] = $this->input->post('whouse');
				$data['total_soi']=$this->usermodel->total_soi();
			}else{
				$data['summary_of_issuance'] = $this->usermodel->summary_of_issuance_sort();
				$data['wn'] = $this->input->post('whouse');
				$data['total_soi']=$this->usermodel->total_soi_sort();
			}

			$this->load->view('reports/summary_of_issuance', $data);
		}
		else{
			redirect('main');
		}
	}


	function confirmation_delivery_report(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['cuslist']=$this->usermodel->get_customer_SAP();

			if($this->input->post('sdate_from') <> "" AND $this->input->post('sdate_to') <> ""){
				$data['records'] = $this->usermodel->confirm_del_report_aaci_sort();
				$this->load->view('reports/confirm_delivery_report',$data);
			}else{
				$data['records'] = $this->usermodel->confirm_del_report_aaci();
				$this->load->view('reports/confirm_delivery_report',$data);
			}
		}
		else{
			redirect('main');
		}
	}

	function confirmation_delivery_report_trucker(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['cuslist']=$this->usermodel->get_customer_SAP();

			if($this->input->post('sdate_from') <> "" AND $this->input->post('sdate_to') <> ""){
				$data['records'] = $this->usermodel->confirm_del_report_trucker_sort();
				$this->load->view('reports/confirm_delivery_report_trucker',$data);
			}else{
				$data['records'] = $this->usermodel->confirm_del_report_trucker();
				$this->load->view('reports/confirm_delivery_report_trucker',$data);
			}
		}
		else{
			redirect('main');
		}
	}

	function confirmation_delivery_report_customer(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['customer'] = $this->usermodel->signin_customer();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['cuslist']=$this->usermodel->get_customer_SAP();

			if($this->input->post('sdate_from') <> "" AND $this->input->post('sdate_to') <> ""){
				$data['records'] = $this->usermodel->confirm_del_report_customer_sort();
				$this->load->view('reports/confirm_delivery_report_customer',$data);
			}else{
				$data['records'] = $this->usermodel->confirm_del_report_customer();
				$this->load->view('reports/confirm_delivery_report_customer',$data);
			}
		}
		else{
			redirect('main');
		}
	}

	function confirmation_delivery_report_customer_onsite(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['customer'] = $this->usermodel->signin_customer();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			
			if($this->input->post('sdate_from') <> "" AND $this->input->post('sdate_to') <> ""){
				$data['records'] = $this->usermodel->confirm_del_report_customer_onsite_sort();
				$this->load->view('reports/confirm_delivery_report_customer_onsite',$data);
			}else{
				$data['records'] = $this->usermodel->confirm_del_report_customer_onsite();
				$this->load->view('reports/confirm_delivery_report_customer_onsite',$data);
			}
		}
		else{
			redirect('main');
		}
	}

	function modal(){
		$data["msg"]="";
	    $this->form_validation->set_rules('email', 'email', 'required');
	    $this->form_validation->set_rules('pass', 'password', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('main/modal', $data);
		}
		else 
		{
			$data["msg"]="Login Successful";
			$this->load->view("main/modal", $data);
		}
	}

	function test_mail(){

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
		//$this->email->set_newline("\r\n");

		$this->email->from('webinvty@aaci.ph','IMS');
		$this->email->to('jhayg12@gmail.com');
		$this->email->subject('This is an email test');
		$this->email->message('It is working great');

		if($this->email->send()){
			echo 'Your email was sent, fool.';
		}else{
			show_error($this->email->print_debugger());
		}
	}

	function confirmation_delivery(){

		$string = $this->uri->segment(3);  
		$var = explode('_', $string);
 
		$data['po_num'] = $var[1];
		$data['do_num'] = $var[2];

		$data['code'] = $this->uri->segment(3);  
		
		$this->load->model('acl_model');
		
		if($this->acl_model->condel_data()){
			$data['cdel_rec'] = $this->acl_model->condel_data();
			$this->load->view('confirm_del', $data);
		}else{
			$this->load->view('confirm_error');
		}

	}

	function confirm_success(){

		$data['code'] = $this->input->post('code');
		$qa = $this->input->post('qa');
		$qr = $this->input->post('qr');
		$total = $this->input->post('total');

		if($qa < 0 OR $qr < 0){
			$this->load->view('confirm_amount_error');
		}else{
			$total_temp = $qa + $qr;
			if($total_temp <> $total){
				$this->load->view('confirm_amount_error');
			}else{
				$this->load->model('acl_model');
				$this->acl_model->update_condel();
				$this->acl_model->condel_email_success();
				$this->load->view('condel_thankyou', $data);
			}
		}
		
	}

	function mail_test(){
		$this->load->view('test_page');
	}

	function info(){
		$this->load->view('test');
	}

	function transaction_count(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['refname']= $this->input->post('bpname');
			$data['refno']= $this->input->post('ref');
			$data['wh']= $this->input->post('whlist');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			if ($this->input->post('sdate') =='' AND $this->input->post('edate')==''){
				$data['deltran']=$this->usermodel->whlist_sort_nodate();
			}
			else{
				$data['deltran']=$this->usermodel->whlist_sort();
			}
			$this->load->view('main/trans_count',$data);
		}
		else{
			redirect('main');
		}
	}

	function customer_list(){
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records']=$this->usermodel->customer_list();

			if($this->uri->segment(3) <> ""){
				if($this->usermodel->check_if_cust_trans()){
					$data['can_del'] = 0;
				}else{
					$data['can_del'] = 1;
				}
			}

			$this->load->view('masterdata/customer_list',$data);
		}
		else{
			redirect('main');
		}
	}

	function customer_delete(){
		$this->usermodel->customer_delete();
		redirect('main/customer_list');
	}

	function ajax_list_customer(){

		$this->load->model('customers_model','customers');
        $list = $this->customers->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $customers) {
            $no++;
            $row = array();
            $row[] = $customers->CardCode;
            $row[] = $customers->CardName;
            $row[] = $customers->Customer_Addressee;
            $row[] = $customers->Customer_Email;
            $row[] = $customers->Customer_Email2;
            $row[] = $customers->Account_Executive;
            $row[] = $customers->AE_Email;
            $row[] = $customers->AE_Mobile;
            $row[] = $customers->Logistics;
            $row[] = $customers->Logistics_Email;
            $row[] = $customers->Logistics_Mobile;
            $row[] = $customers->Address;
            $row[] = $customers->Address2;
            $row[] = $customers->Address3;
            $row[] = $customers->Address4;
            $row[] = $customers->Address5;
            $row[] = $customers->Address6;

            $row[] = $customers->CardCode;
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->customers->count_all(),
                        "recordsFiltered" => $this->customers->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

	function customer_edit(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records']=$this->usermodel->customer_get();

			$this->form_validation->set_rules('ae_email', 'Account Executive Email', 'valid_email');
			$this->form_validation->set_rules('cust_email', 'Customer Email', 'valid_email');
			$this->form_validation->set_rules('cust_email2', 'Customer Email 2', 'valid_email');
			$this->form_validation->set_rules('log_email', 'Logistics Email', 'valid_email');

			$this->form_validation->set_error_delimiters('<div class="alert alert-danger">','</div>');

			if($this->form_validation->run() == false){
				$this->load->view('masterdata/customer_edit',$data);
			}
			else{
				$this->usermodel->customer_update();
				redirect('main/customer_list');
			}
		}
		else{
			redirect('main');
		}
	}

	function customer_create(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$this->form_validation->set_rules('ccode','required');
			$this->form_validation->set_rules('cname','required');
			$this->form_validation->set_rules('ae_email', 'Account Executive Email', 'valid_email');
			$this->form_validation->set_rules('cust_email', 'Customer Email', 'valid_email');
			$this->form_validation->set_rules('cust_email2', 'Customer Email 2', 'valid_email');
			$this->form_validation->set_rules('log_email', 'Logistics Email', 'valid_email');

			// $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

			if($this->form_validation->run() == false){
				$this->load->view('masterdata/customer_create', $data);
			}else{
				if($this->usermodel->customer_code_validation()){
					$data['error'] = 'Customer Code already exists';
					$this->load->view('masterdata/customer_create',$data);
				}elseif($this->usermodel->customer_name_validation()){
					$data['error'] = 'Customer Name already exists';
					$this->load->view('masterdata/customer_create', $data);
				}else{
					$this->usermodel->customer_create();
					// redirect('main/customer_list');
					// 1 means success
					redirect('main/item_create/'.'1');
				}
			}
			
		}
		else{
			redirect('main');
		}
	}

	function customer_login(){
	
		$data['ccode'] = $this->uri->segment(3);
		$this->load->model('customer_model');
		$this->load->model('acl_model');
		// $this->load->view('customer/customer_login', $data);

		if($this->session->userdata('logged_in')){
			if($this->acl_model->condel_data()){
				redirect('main/confirmation_delivery/'.$this->uri->segment(3));
			}else{
				redirect('main/home/'.$this->uri->segment(3), 'refresh');
			}
		}
		else{
			$this->form_validation->set_rules('uname','Username','required');
			$this->form_validation->set_rules('pword','Password','required');
			if($this->form_validation->run() == false){
				$this->load->view('customer/customer_login', $data);
			}
			else{
				$q = $this->customer_model->customer_login_validation();
				$q2 = $this->customer_model->customer_login_validation2();
				if($q){
					$data =array(
						'logged_in' => true,
						'usr_uname' => $this->input->post('uname'),
					);
					$this->session->set_userdata($data);

					if($this->acl_model->condel_data()){
						redirect('main/confirmation_delivery/'.$this->uri->segment(3));
					}else{
						redirect('main/home/'.$this->uri->segment(3));
					}
				}elseif($q2){
					$data =array(
						'logged_in' => true,
						'usr_uname' => $this->input->post('uname'),
					);
					$this->session->set_userdata($data);
					if($this->acl_model->condel_data()){
						redirect('main/confirmation_delivery/'.$this->uri->segment(3));
					}else{
						redirect('main/home/'.$this->uri->segment(3));
					}
				}else{
					$data['error']='Username or Password incorrect';
					redirect('main/customer_login/'.$this->uri->segment(3));
				}
			}
		}

	}

	function customer_logout(){
		$this->session->sess_destroy('userdata');
		redirect('main/customer_login/'.$this->uri->segment(3));
	}

	function customer_forgot_password(){

		$data['ccode'] = $this->uri->segment(3);
		$data['reset'] = NULL;
		if($this->input->post('go')){
			$this->load->model('customer_model');
			$data['reset'] = $this->customer_model->customer_forgot_password(); 
		}
		$this->load->view('customer/customer_forgot_password', $data);
	}

	function customer_change_password(){

		$data['reset'] = NULL;
		if($this->input->post('go')){
			$this->load->model('customer_model');
			$data['reset'] = $this->customer_model->customer_change_password(); 
		}
		$this->load->view('customer/customer_change_password', $data);
	}

	function customer_registration(){

		$data['ccode'] = $this->uri->segment(3);
		$data['reg_result'] = NULL;

		$this->load->model('customer_model');

		$data['cust_list'] = $this->customer_model->get_customer_list();


		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');


		if($this->form_validation->run() == FALSE){
			$this->load->view('customer/customer_registration', $data);
		}elseif($this->customer_model->customer_email_count()){
			$data['error'] = 'Only Two accounts can be created per Customer';
			$this->load->view('customer/customer_registration', $data);
		}else{
			if($this->customer_model->customer_email_check()){
				$data['error'] = 'Email Already Exists';
				$this->load->view('customer/customer_registration', $data);
			}elseif($this->customer_model->customer_username_check()){
				$data['error'] = 'Username Already Exists';
				$this->load->view('customer/customer_registration', $data);
			}elseif($this->customer_model->customer_name_check()){
				$data['error'] = 'Name Already Exists';
				$this->load->view('customer/customer_registration', $data);
			}elseif($this->customer_model->customer_email_check2()){
				$data['error'] = 'Email Already Exists';
				$this->load->view('customer/customer_registration', $data);
			}elseif($this->customer_model->customer_username_check2()){
				$data['error'] = 'Username Already Exists';
				$this->load->view('customer/customer_registration', $data);
			}elseif($this->customer_model->customer_name_check2()){
				$data['error'] = 'Name Already Exists';
				$this->load->view('customer/customer_registration', $data);
			}elseif($this->customer_model->customer_email_check3()){
				$data['error'] = 'Email Already Exists';
				$this->load->view('customer/customer_registration', $data);
			}elseif($this->customer_model->customer_username_check3()){
				$data['error'] = 'Username Already Exists';
				$this->load->view('customer/customer_registration', $data);
			}elseif($this->customer_model->customer_name_check3()){
				$data['error'] = 'Name Already Exists';
				$this->load->view('customer/customer_registration', $data);
			}elseif($this->customer_model->customer_code_check()){
				$data['error'] = 'Customer Code does not exists';
				$this->load->view('customer/customer_registration', $data);
			}elseif($this->customer_model->customer_limit_reach()){
				$data['error'] = 'Only two approver is allowed per Customer';
				$this->load->view('customer/customer_registration', $data);
			}else{
				$this->load->model('customer_model');
				$data['reg_result'] = $this->customer_model->customer_registration();
				// $this->load->view('customer/customer_registration', $data);
				redirect('main/customer_login/'.$this->input->post('ccode'));
			}
		}

	}

	// SUGAR
	function premium_raw(){
		if($this->session->userdata('logged_in')){
			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['warehouses']=$this->usermodel->wh_list_active();
			$data['uaccess']=$this->usermodel->access_valid();
			$data['umaccess']=$this->usermodel->access_module_valid();
			$this->load->view('sugar/premium_raw', $data);
		}
		else{
			redirect('main');
		}
	}

	function plantation(){
		if($this->session->userdata('logged_in')){
			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['warehouses']=$this->usermodel->wh_list_active();
			$data['uaccess']=$this->usermodel->access_valid();
			$data['umaccess']=$this->usermodel->access_module_valid();
			$this->load->view('sugar/plantation', $data);
		}
		else{
			redirect('main');
		}
	}

	function standard_refined(){
		if($this->session->userdata('logged_in')){
			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['warehouses']=$this->usermodel->wh_list_active();
			$data['uaccess']=$this->usermodel->access_valid();
			$data['umaccess']=$this->usermodel->access_module_valid();
			$this->load->view('sugar/standard_refined', $data);
		}
		else{
			redirect('main');
		}
	}

	function premium_refined(){
		if($this->session->userdata('logged_in')){
			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['warehouses']=$this->usermodel->wh_list_active();
			$data['uaccess']=$this->usermodel->access_valid();
			$data['umaccess']=$this->usermodel->access_module_valid();
			$this->load->view('sugar/premium_refined', $data);
		}
		else{
			redirect('main');
		}
	}

	function special_sugar(){
		if($this->session->userdata('logged_in')){

			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['warehouses']=$this->usermodel->wh_list_active();
			$data['uaccess']=$this->usermodel->access_valid();
			$data['umaccess']=$this->usermodel->access_module_valid();
			$this->load->view('sugar/special_sugar', $data);
		}
		else{
			redirect('main');
		}
	}

	function turbinado(){
		if($this->session->userdata('logged_in')){
	
			$data['user'] =$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['warehouses']=$this->usermodel->wh_list_active();
			$data['uaccess']=$this->usermodel->access_valid();
			$data['umaccess']=$this->usermodel->access_module_valid();
			$this->load->view('sugar/turbinado', $data);
		}
		else{
			redirect('main');
		}
	}

	function sample2(){
		$this->load->view('test_page');
	}

	function edit_mm(){
		if($this->session->userdata('logged_in')){
			$tokens = explode('/', current_url());
			$get = $tokens[sizeof($tokens)-1];
			date_default_timezone_set("Asia/Manila");
			$data['cdate'] = date('Y-m-d');
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			//$data['doctype']=$this->usermodel->get_doctype_In();
			$data['warehouse']=$this->usermodel->whname_select($get);
			$data['process']=$this->usermodel->def_mm_process_active();
			$data['dtype']=$this->usermodel->def_defaultdeltype_active();
			$data['snmm']=$this->usermodel->sn_mm();
			$data['reftype1']=$this->usermodel->ref_mm_primary();
			$data['reftype2']=$this->usermodel->ref_mm_secondary();
			$data['uom']=$this->usermodel->uom_list();
			$error1 = array();
			$data['ARI'] = NULL;
			//Modify
			//From SAP
			$data['bpname']=$this->usermodel->customer_mm($get);
			$data['item']=$this->usermodel->item_get_SAP();
			
			$data['edit_record'] = $this->usermodel->edit_mm();
			$data['rec_list'] = $this->usermodel->edit_mm_list();

			$data['mm_ctr'] = $this->usermodel->count_mm_rows();

			$this->load->view('header', $data);
			$this->load->view('main/edit_mm_view');
			$this->load->view('footer');

			if($this->input->post('submit')){
				$this->usermodel->mm_update();
				redirect('main/edit_mm/'.$this->uri->segment(3)."/".$this->uri->segment(4));
			}

		}
		else{
			redirect('main');
		}
	}

	function transaction_series(){

		if($this->session->userdata('logged_in')){

			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist_trans();
		
			$this->form_validation->set_rules('wh', 'Warehouse Name', 'required|trim');

			if($this->form_validation->run()){
				$data['rcd'] = $this->usermodel->trans_series();
				$this->load->view('trans_series/transaction_series', $data);
				$this->load->view('footer', $data);
			}else{
				$this->load->view('trans_series/transaction_series', $data);
				$this->load->view('footer', $data);
			}


		}else{
			redirect('main');
		}

	}

	function add_trans_series(){

		if($this->session->userdata('logged_in')){

			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['trans_type']=$this->usermodel->trans_type_list();
			$data['whlist']=$this->usermodel->whlist();
			$data['wh_name']=$this->usermodel->get_whse_name();

			$this->form_validation->set_rules('fno', 'First Number', 'required|trim');
			$this->form_validation->set_rules('lno', 'Last Number', 'required|trim');
			$this->form_validation->set_rules('vdate', 'Validity Date', 'required|trim');

			if($this->form_validation->run() == TRUE){
				
				if($this->usermodel->check_if_transcode_wcode_exists()){
					$data['error']="Transaction Type already exists on this Warehouse";
					$this->load->view('trans_series/add_trans_series', $data);
					$this->load->view('footer', $data);
				}elseif($this->usermodel->check_fno_trans_series()){
					$data['error']="First No is within the range of other Transaction Series";
					$this->load->view('trans_series/add_trans_series', $data);
					$this->load->view('footer', $data);
				}elseif($this->usermodel->check_lno_trans_series()){
					$data['error']="Last No is within the range of other Transaction Series";
					$this->load->view('trans_series/add_trans_series', $data);
					$this->load->view('footer', $data);
				}elseif($this->input->post('fno') > $this->input->post('lno')){
					$data['error']="First No must be less than the Last No";
					$this->load->view('trans_series/add_trans_series', $data);
					$this->load->view('footer', $data);
				}elseif($this->input->post('lno') < $this->input->post('fno')){
					$data['error']="Last No must be greater than the First No";
					$this->load->view('trans_series/add_trans_series', $data);
					$this->load->view('footer', $data);
				}elseif($this->input->post('fno') == $this->input->post('lno')){
					$data['error']="Last No musts be greater than the First No";
					$this->load->view('trans_series/add_trans_series', $data);
					$this->load->view('footer', $data);
				}else{
					$this->usermodel->add_trans_series();
					redirect('main/add_trans_series/'.$this->uri->segment(3).'/ats_01');
				}
			}else{	
				$this->load->view('trans_series/add_trans_series', $data);
				$this->load->view('footer', $data);
			}

		}else{
			redirect('main');
		}

	}

	function edit_trans_series(){
		if($this->session->userdata('logged_in')){

			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['record'] = $this->usermodel->trans_series_edit_rec();

			$this->form_validation->set_rules('nno', 'Next No', 'required|trim');

			if($this->form_validation->run()){

				(int)$fno = $this->input->post('fno');
				(int)$lno = $this->input->post('lno');
				(int)$nno = $this->input->post('nno');

				if($this->usermodel->check_fno_trans_series_edit()){
					$data['error']="First No is within the range of other Transaction Series";
					$this->load->view('trans_series/edit_trans_series', $data);
					$this->load->view('footer', $data);
				}elseif($this->usermodel->check_lno_trans_series_edit()){
					$data['error']="Last No is within the range of other Transaction Series";
					$this->load->view('trans_series/edit_trans_series', $data);
					$this->load->view('footer', $data);
				}elseif($this->input->post('fno') > $this->input->post('lno')){
					$data['error']="First No must be less than the Last No";
					$this->load->view('trans_series/edit_trans_series', $data);
					$this->load->view('footer', $data);
				}elseif($this->input->post('lno') < $this->input->post('fno')){
					$data['error']="Last No must be greater than the First No";
					$this->load->view('trans_series/edit_trans_series', $data);
					$this->load->view('footer', $data);
				}elseif($this->input->post('fno') == $this->input->post('lno')){
					$data['error']="Last No musts be greater than the First No";
					$this->load->view('trans_series/edit_trans_series', $data);
					$this->load->view('footer', $data);
				}elseif($this->usermodel->check_if_nextno_used_in_trans()){
					$data['error']="Next Num is already used in transaction";
					$this->load->view('trans_series/edit_trans_series', $data);
					$this->load->view('footer', $data);
				}else{

					if(($nno >= $fno) && ($nno <= $lno)){
						$this->usermodel->update_trans_series();
						redirect('main/edit_trans_series/'.$this->uri->segment(3).'/ats_02');
					}else{
						$data['error']="Next Num must be in the range of First No and Last No";
						$this->load->view('trans_series/edit_trans_series', $data);
						$this->load->view('footer', $data);
					}
				}
			}else{
				$this->load->view('trans_series/edit_trans_series', $data);
				$this->load->view('footer');
			}

		}else{
			redirect('main');
		}
	}

	function opening_balance(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['total_items']=$this->usermodel->total_items();

			$this->form_validation->set_rules('cutoff_date', 'Cut Off Date', 'required|trim');

			if($this->form_validation->run() == TRUE){
				if($this->input->post('search')){
					$data['whse']=$this->input->post('wh');
					$data['records']=$this->usermodel->opening_bal_list();
				}
					if($this->input->post('post')){

						$rec = array();
						$rec = $this->usermodel->total_items();

						if($rec){
								foreach($rec as $rcd){
									$counter = $rcd->ctr;
								}

								$xx=0;
								while($xx<=$counter){

									if($this->input->post('counted_'.$xx) <> ""){
										if($this->input->post('variance_'.$xx) <> "0" OR $this->input->post('variance_'.$xx) <> "0.00"){
											$icode = $this->input->post('icode_'.$xx);
											$cbal = $this->input->post('cbal_'.$xx);
											$count = $this->input->post('counted_'.$xx);
											$var = $this->input->post('variance_'.$xx);
											$wname_var = $this->input->post('wh_'.$xx);
											$wname = $this->input->post('wh');
											$unit = $this->input->post('unit_'.$xx);
											$codate = $this->input->post('cutoff_date');

											$this->usermodel->begbal_in_wvar($icode, $cbal, $count, $var, $wname, $wname_var, $unit, $codate, $counter);

											// redirect('main/opening_balance');

										}else{

											$icode = $this->input->post('icode_'.$xx);
											$cbal = $this->input->post('cbal_'.$xx);
											$count = $this->input->post('counted_'.$xx);
											$wname = $this->input->post('wh');
											$unit = $this->input->post('unit_'.$xx);
											$codate = $this->input->post('cutoff_date');

											$this->usermodel->begbal_in_nvar($icode, $cbal, $count, $wname, $unit, $codate);

											// redirect('main/opening_balance');
										}
											
									}

									$xx++;

									if($xx == $counter){
										redirect('main/opening_balance');
									}

								}


						}

					}

			}

			$this->load->view('opening_balance', $data);

		}
		else{
			redirect('main');
		}		
	}

	function tin_tout(){

		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['wh_list_name']=$this->usermodel->wh_list_name();
			$data['doctype_out']=$this->usermodel->get_doctype_Out();
			$data['doctype_in']=$this->usermodel->get_doctype_In();
			$data['item']=$this->usermodel->item_get();
			$data['uom']=$this->usermodel->uom_list();
			$data['bpname']=$this->usermodel->get_bplistDdown_SAP_Customer();
			$data['trucks']=$this->usermodel->truck_list();
			$data['tno_tin']=$this->usermodel->trans_no_tin();
			$data['tno_tout']=$this->usermodel->trans_no_tout();

			$data['cvdate_tin'] = $this->usermodel->check_validity_date_tin();
			$data['cvdate_tout'] = $this->usermodel->check_validity_date_tout();

			$dout_data = array(
					'ds_no_dout'=> $this->input->post('ds_no_dout'),
					'reftype1_dout'=> $this->input->post('reftype1_dout'),
					'refnum1_dout'=> $this->input->post('refnum1_dout'),
					'reftype2_dout'=> $this->input->post('reftype2_dout'),
					'refnum2_dout'=> $this->input->post('refnum2_dout'),
					'from_dout'=> $this->input->post('from_dout'),
					'to_dout'=> $this->input->post('to_dout'),
					'item_dout'=> $this->input->post('item_dout'),
					'uom_dout'=> $this->input->post('uom_dout'),
					'qty_dout'=> $this->input->post('qty_dout'),
					'truck_comp_dout'=> $this->input->post('truck_comp_dout'),
					'tpnum_dout'=> $this->input->post('tpnum_dout'),
					'tdrvr_dout'=> $this->input->post('tdrvr_dout'),
					'pdate_dout'=> $this->input->post('pdate_dout'),
					'apdate_dout'=> $this->input->post('apdate_dout'),
					'remarks_dout'=> $this->input->post('remarks_dout')
				);

			if($this->input->post('submit_dout')){
				if($this->input->post('ds_no_dout') == ""){
					$data['error'] = "TRANSFER OUT - Document Series No. field is required";
				}elseif($this->input->post('refnum1_dout') == ""){
					$data['error'] = "TRANSFER OUT - Reference No. 1 field is required";
				}elseif($this->input->post('refnum2_dout') == ""){
					$data['error'] = "TRANSFER OUT - Reference No. 2 field is required";
				}elseif($this->input->post('icode_dout') == ""){
					$data['error'] = "TRANSFER OUT - No selected item";
				}elseif($this->input->post('qty_dout') == ""){
					$data['error'] = "TRANSFER OUT - Quantity field is required";
				}elseif($this->input->post('qty_dout') == "0"){
					$data['error'] = "TRANSFER OUT - Quantity must be greater than 0";
				}elseif($this->input->post('qty_dout') == "0.00"){
					$data['error'] = "TRANSFER OUT - Quantity must be greater than 0";
				}elseif($this->input->post('tpnum_dout') == ""){
					$data['error'] = "TRANSFER OUT - Truck Plate No. field is required";
				}elseif($this->input->post('tdrvr_dout') == ""){
					$data['error'] = "TRANSFER OUT - Truck Driver field is required";
				}elseif($this->input->post('pdate_dout') == ""){
					$data['error'] = "TRANSFER OUT - Posting Date field is required";
				}elseif($this->input->post('apdate_dout') == ""){
					$data['error'] = "TRANSFER OUT - Actual Pick-Up Date field is required";
				}elseif($this->usermodel->validation_del_in_ref_tout($dout_data)){
					$data['error'] = "TRANSFER OUT - Reference Type and Number already encoded"; 
				}elseif($this->usermodel->tout_itemqty_validation($dout_data)){
					$data['error'] = "TRANSFER OUT - Insufficient quantity";
				}else{
					$this->usermodel->insert_tout($dout_data);
					$this->usermodel->do_nappr_dout($dout_data);
					redirect('main/tin_tout/'.$this->uri->segment(3).'/tout_01');
				}
			}

			$din_data = array(
					'from_din'=> $this->input->post('from_din'),
					'to_din'=> $this->input->post('to_din'),
					'reftype1_din'=> $this->input->post('reftype1_din'),
					'refnum1_din'=> $this->input->post('refnum1_din'),
					'reftype2_din'=> $this->input->post('reftype2_din'),
					'refnum2_din'=> $this->input->post('refnum2_din'),
					'icode_din'=> $this->input->post('icode_din'),
					'uom_din'=> $this->input->post('uom_din'), 
					'qty_din'=> $this->input->post('qty_din'),
					'remarks_din'=> $this->input->post('remarks_din'),
					'truck_company'=> $this->input->post('truck_comp')
				);

			if($this->input->post('submit_din')){
				if($this->input->post('ds_no_din') == ""){
					$data['error_din'] = "TRANSFER IN - Document Series No. Field is required";
				}elseif($this->input->post('reftype1_din') == ""){
					$data['error_din'] = "TRANFER IN - No record was found";
				}elseif($this->input->post('pdate_din') == ""){
					$data['error_din'] = "TRANSFER IN - Posting Field is required";
				}elseif($this->usermodel->validation_del_in_ref_tin($din_data)){
					$data['error_din'] = "TRANSFER IN - Reference Type and Number already encoded"; 
				}else{
					$this->usermodel->insert_tin($din_data);
					$this->usermodel->di_nappr_din($din_data);
					redirect('main/tin_tout/'.$this->uri->segment(3).'/tin_01');
				}
			}

			$this->load->view('header', $data);
			$this->load->view('tin_tout', $data);
			$this->load->view('footer');

		}else{
			redirect('main');
		}

	}

	function get_tout_data(){

		$ds_no_din = $this->input->post('ds_no_din');
		$result = $this->usermodel->get_tout_data($ds_no_din);
		$dd['tout_data'] = $result['row'];
		echo json_encode($dd);

	}
	
	function get_return_data(){

		$do_num = $this->input->post('do_num');
		$result = $this->usermodel->get_return_data($do_num);
		$dd['return_data'] = $result['row'];
		echo json_encode($dd);

	}

	function print_tin_pdf(){
		$data['page_title'] = 'Transfer In';
		$data['print'] = $this->usermodel->tin_print_layout();
		$this->load->view('pdf/fpdf');
		$this->load->view('tin_print_layout', $data);

		// OPEN THE DOWNLOADED PDF
		$pdf_name = array();
		$pdf_name = $this->usermodel->get_last_inserted_id();

		foreach($pdf_name as $row){
			$filename = $_SERVER['DOCUMENT_ROOT'].'/inventory/application/PRINT_DOCS/TIN/'.$row->wi_id.".pdf";

			$this->output
             ->set_content_type('application/pdf')
             ->set_output(file_get_contents($filename)); 
		}

	}

	function print_tout_pdf(){
		$data['page_title'] = 'Transfer Out';
		$data['print'] = $this->usermodel->tout_print_layout();
		$this->load->view('pdf/fpdf');
		$this->load->view('tout_print_layout', $data);

		// OPEN THE DOWNLOADED PDF
		$pdf_name = array();
		$pdf_name = $this->usermodel->get_last_inserted_id();

		foreach($pdf_name as $row){
			$filename = $_SERVER['DOCUMENT_ROOT'].'/inventory/application/PRINT_DOCS/TOUT/'.$row->wi_id.".pdf";

			$this->output
             ->set_content_type('application/pdf')
             ->set_output(file_get_contents($filename)); 
		}

	}


	function getdata(){

        $data = $this->usermodel->wh_list_active(); 
 
        //data to json 
 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Topping", 
            "pattern" => "", 
            "type" => "string" 
        ); 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Total", 
            "pattern" => "", 
            "type" => "number" 
        ); 
        foreach($data as $cd) 
            { 

            $totQty = ($cd->delin - ($cd->delout + $cd->delres));
            $total_items = $totQty + $cd->delres;

            $responce->rows[]["c"] = array( 
                array( 
                    "v" => "$cd->wh_name", 
                    "f" => null 
                ) , 
                array( 
                    "v" => (int)$total_items, 
                    "f" => null 
                ) 
            ); 
            } 
 
        echo json_encode($responce); 
    }


    function get_truck_plateno(){

    	$truck_name = $this->input->post('truck_name');
    	$result = $this->usermodel->get_truck_plateno($truck_name);
    	$dd['truck_data'] = $result['row'];
    	echo json_encode($dd);

    }

    function get_truck_driver(){

    	$truck_plateno = $this->input->post('truck_plateno');
    	$result = $this->usermodel->get_truck_driver($truck_plateno);
    	$dd['truck_data2'] = $result['row'];
    	echo json_encode($dd);

    }

    function truck_company_list(){

    	if($this->session->userdata('logged_in')){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records'] = $this->usermodel->get_truck_company_list();

			if($this->uri->segment(3) <> ""){
				if($this->usermodel->check_if_truck_company_trans()){
					$data['can_del'] = 0;
				}else{
					$data['can_del'] = 1;
				}
			}

    		$this->load->view('masterdata/truck_company_list', $data);
    	}else{
    		redirect('main');
    	}

    }

    function truck_company_create(){

    	if($this->session->userdata('logged_in')){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['truck_series'] = $this->usermodel->truck_company_series();

			$this->form_validation->set_rules('truck_short_name', 'Short Name', 'required|trim');

			if($this->form_validation->run() == TRUE){

				if($this->usermodel->check_truck_name_if_exists()){
					$data['error'] = "Short Name already exists";
					$this->load->view('masterdata/truck_company_create', $data);
				}else{
					$this->usermodel->insert_truck_company();
					redirect('main/truck_company_create'.'/tc_01');
				}

			}else{
				$this->load->view('masterdata/truck_company_create', $data);
			}
    		
    	}else{	
    		redirect('main');
    	}

    }

    function truck_company_edit(){

    	if($this->session->userdata('logged_in')){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records']=$this->usermodel->truck_company_edit_records();

			$this->form_validation->set_rules('truck_short_name', 'Short Name', 'required|trim');
			$this->form_validation->set_rules('truck_name', 'Name', 'required|trim');

			if($this->form_validation->run() == TRUE){
				$this->usermodel->update_truck_company();
				redirect('main/truck_company_edit/'.$this->uri->segment(3).'/tc_02');
			}else{
				$this->load->view('masterdata/truck_company_edit', $data);
			}

    	}else{
    		redirect('main');
    	}

    }

    function truck_company_delete(){
    	$this->usermodel->truck_company_delete();
    	redirect('main/truck_company_list');
    }

    function truck_driver_list(){

    	if($this->session->userdata('logged_in')){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['records'] = $this->usermodel->get_truck_driver_list();

			if($this->uri->segment(3) <> ""){
				if($this->usermodel->check_if_truck_driver_trans()){
					$data['can_del'] = 0;
				}else{
					$data['can_del'] = 1;
				}
			}

    		$this->load->view('masterdata/truck_driver_list', $data);
    	}else{
    		redirect('main');
    	}

    }

    function truck_driver_create(){

    	if($this->session->userdata('logged_in')){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['truck_company'] = $this->usermodel->get_truck_company();

			$this->form_validation->set_rules('truck_plateno', 'Truck Plate No', 'required|trim');
			$this->form_validation->set_rules('driver_name', 'Driver Name', 'required|trim');

			if($this->form_validation->run() == TRUE){

				if($this->usermodel->check_truck_name_plateno_if_exists()){
					$data['error'] = "Truck Driver and Plate No already exists";
					$this->load->view('masterdata/truck_driver_create', $data);
				}else{
					$this->usermodel->insert_truck_driver();
					redirect('main/truck_driver_create'.'/td_01');
				}

			}else{
				$this->load->view('masterdata/truck_driver_create', $data);
			}
    		
    	}else{	
    		redirect('main');
    	}

    }

    function truck_driver_edit(){

    	if($this->session->userdata('logged_in')){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['truck_company'] = $this->usermodel->get_truck_company();
			$data['records']=$this->usermodel->truck_driver_edit_records();

			$this->form_validation->set_rules('truck_plateno', 'Truck Plate No', 'required|trim');
			$this->form_validation->set_rules('driver_name', 'Driver Name', 'required|trim');

			if($this->form_validation->run() == TRUE){
				$this->usermodel->update_truck_driver();
				redirect('main/truck_driver_edit/'.$this->uri->segment(3).'/td_02');
			}else{
				$this->load->view('masterdata/truck_driver_edit', $data);
			}

    	}else{
    		redirect('main');
    	}

    }

    function truck_driver_delete(){
    	$this->usermodel->truck_driver_delete();
    	redirect('main/truck_driver_list');
    }

    function print_dr_list(){
    	if($this->session->userdata("logged_in")){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
		
			if($this->input->post('submit') == "submit"){
				$data['records']=$this->usermodel->print_dr_list();
			}

			$this->load->view('print/print_dr_list', $data);

    	}else{
    		redirect("main");
    	}
    }

    function open_dr_pdf(){

		// OPEN THE DOWNLOADED PDF
		$pdf_name = array();
		$pdf_name = $this->usermodel->open_dr_pdf();

		foreach($pdf_name as $row){
			$filename = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/PRINT_DOCS/DR/'.$row->wi_id.".pdf";

			$this->output
             ->set_content_type('application/pdf')
             ->set_output(file_get_contents($filename)); 
		}

	}

	function print_wis_list(){
    	if($this->session->userdata("logged_in")){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
		
			if($this->input->post('submit') == "submit"){
				$data['records']=$this->usermodel->print_wis_list();
			}

			$this->load->view('print/print_wis_list', $data);

    	}else{
    		redirect("main");
    	}
    }

    function open_wis_pdf(){

		// OPEN THE DOWNLOADED PDF
		$pdf_name = array();
		$pdf_name = $this->usermodel->open_wis_pdf();

		foreach($pdf_name as $row){
			$filename = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/PRINT_DOCS/WIS/'.$row->wi_id.".pdf";

			$this->output
             ->set_content_type('application/pdf')
             ->set_output(file_get_contents($filename)); 
		}

	}

	function print_rr_list(){
    	if($this->session->userdata("logged_in")){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
		
			if($this->input->post('submit') == "submit"){
				$data['records']=$this->usermodel->print_rr_list();
			}

			$this->load->view('print/print_rr_list', $data);

    	}else{
    		redirect("main");
    	}
    }

    function open_rr_pdf(){

		// OPEN THE DOWNLOADED PDF
		$pdf_name = array();
		$pdf_name = $this->usermodel->open_rr_pdf();

		foreach($pdf_name as $row){
			$filename = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/PRINT_DOCS/RR/'.$row->wi_id.".pdf";

			$this->output
             ->set_content_type('application/pdf')
             ->set_output(file_get_contents($filename)); 
		}

	}

	function print_tout_list(){
    	if($this->session->userdata("logged_in")){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
		
			if($this->input->post('submit') == "submit"){
				$data['records']=$this->usermodel->print_tout_list();
			}

			$this->load->view('print/print_tout_list', $data);

    	}else{
    		redirect("main");
    	}
    }

    function open_tout_pdf(){

		// OPEN THE DOWNLOADED PDF
		$pdf_name = array();
		$pdf_name = $this->usermodel->open_tout_pdf();

		foreach($pdf_name as $row){
			$filename = $_SERVER['DOCUMENT_ROOT'].'/inventory/application/PRINT_DOCS/TOUT/'.$row->wi_id.".pdf";

			$this->output
             ->set_content_type('application/pdf')
             ->set_output(file_get_contents($filename)); 
		}

	}

	function print_tin_list(){
    	if($this->session->userdata("logged_in")){
    		$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
		
			if($this->input->post('submit') == "submit"){
				$data['records']=$this->usermodel->print_tin_list();
			}

			$this->load->view('print/print_tin_list', $data);

    	}else{
    		redirect("main");
    	}
    }

    function open_tin_pdf(){

		// OPEN THE DOWNLOADED PDF
		$pdf_name = array();
		$pdf_name = $this->usermodel->open_tin_pdf();

		foreach($pdf_name as $row){
			$filename = $_SERVER['DOCUMENT_ROOT'].'/inventory/application/PRINT_DOCS/TIN/'.$row->wi_id.".pdf";

			$this->output
             ->set_content_type('application/pdf')
             ->set_output(file_get_contents($filename)); 
		}

	}

	function delete_dr_pdf(){
		$this->usermodel->delete_dr_pdf();
		redirect('main/print_dr_list');
	}

	function delete_wis_pdf(){
		$this->usermodel->delete_wis_pdf();
		redirect('main/print_wis_list');
	}

	function delete_rr_pdf(){
		$this->usermodel->delete_rr_pdf();
		redirect('main/print_rr_list');
	}

	function delete_tin_pdf(){
		$this->usermodel->delete_tin_pdf();
		redirect('main/print_tin_list');
	}

	function delete_tout_pdf(){
		$this->usermodel->delete_tout_pdf();
		redirect('main/print_tout_list');
	}

	function summary_of_print_docs(){
		if($this->session->userdata("logged_in")){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();

			$this->form_validation->set_rules('whouse', 'Warehouse', 'required|trim');
			$this->form_validation->set_rules('dtype', 'Transaction Type', 'required|trim');

			if($this->form_validation->run()){
				$data['records'] = $this->usermodel->get_summary_print_list();
			}

			$this->load->view('reports/summary_of_print_docs', $data);

		}else{
			redirect('main');
		}
	}

	function warehouse_integration(){
		if($this->session->userdata("logged_in")){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['records'] = $this->usermodel->warehouse_integration_list();

			if($this->uri->segment(3) <> ""){
				//$this->usermodel->warehouse_integration_delete();
				$data['can_del'] = 1;
			}

			$this->load->view('warehouse_integration/warehouse_integration', $data);

		}else{
			redirect('main');
		}
	}

	function warehouse_integration_create(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['wh_list']=$this->usermodel->whse_integ_list();
			$data['wh_list_sap']=$this->usermodel->whse_integ_list_sap();

			$this->form_validation->set_rules('wh_name', 'Warehouse Name', 'required|trim');
			$this->form_validation->set_rules('sap_wname', 'SAP Warehouse Name', 'required|trim');

			if($this->form_validation->run() == TRUE){
				$this->usermodel->warehouse_integration_create();
				redirect('main/warehouse_integration');
			}else{
				$this->load->view('warehouse_integration/warehouse_integration_create', $data);
			}
		}
		else{
			redirect('main');
		}
	}

	function warehouse_integration_edit(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['wh_list']=$this->usermodel->whse_integ_list();
			$data['wh_list_edit']=$this->usermodel->whse_integ_list_edit();
			$data['wh_list_sap']=$this->usermodel->whse_integ_list_sap();

			$this->form_validation->set_rules('wh_name', 'Warehouse Name', 'required|trim');
			$this->form_validation->set_rules('sap_wname', 'SAP Warehouse Name', 'required|trim');

			if($this->form_validation->run() == TRUE){
				$this->usermodel->warehouse_integration_edit();
				redirect('main/warehouse_integration');
			}else{
				$this->load->view('warehouse_integration/warehouse_integration_edit', $data);
			}
		}
		else{
			redirect('main');
		}
	}

	function warehouse_integration_delete(){
		$this->usermodel->warehouse_integration_delete();
		redirect('main/warehouse_integration');
	}

	function warehouse_integration_ims(){
		if($this->session->userdata("logged_in")){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['records'] = $this->usermodel->warehouse_integration_list_ims();

			if($this->uri->segment(3) <> ""){
				//$this->usermodel->warehouse_integration_delete();
				$data['can_del'] = 1;
			}

			$this->load->view('warehouse_integration_ims/warehouse_integration_ims', $data);

		}else{
			redirect('main');
		}
	}

	function warehouse_integration_create_ims(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['wh_list']=$this->usermodel->whse_integ_list_ims();
			$data['wh_list_sap']=$this->usermodel->whse_integ_list_sap_ims();

			$this->form_validation->set_rules('wh_name', 'Warehouse Name', 'required|trim');
			$this->form_validation->set_rules('sap_wname', 'SAP Warehouse Name', 'required|trim');

			if($this->form_validation->run() == TRUE){
				$this->usermodel->warehouse_integration_create_ims();
				redirect('main/warehouse_integration_ims');
			}else{
				$this->load->view('warehouse_integration_ims/warehouse_integration_create_ims', $data);
			}
		}
		else{
			redirect('main');
		}
	}

	function warehouse_integration_edit_ims(){
		if($this->session->userdata('logged_in')){
			$data['user']=$this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['wh_list']=$this->usermodel->whse_integ_list_ims();
			$data['wh_list_edit']=$this->usermodel->whse_integ_list_edit_ims();
			$data['wh_list_sap']=$this->usermodel->whse_integ_list_sap_ims();

			$this->form_validation->set_rules('wh_name', 'Warehouse Name', 'required|trim');
			$this->form_validation->set_rules('sap_wname', 'SAP Warehouse Name', 'required|trim');

			if($this->form_validation->run() == TRUE){
				$this->usermodel->warehouse_integration_edit_ims();
				redirect('main/warehouse_integration_ims');
			}else{
				$this->load->view('warehouse_integration_ims/warehouse_integration_edit_ims', $data);
			}
		}
		else{
			redirect('main');
		}
	}

	function warehouse_integration_delete_ims(){
		$this->usermodel->warehouse_integration_delete_ims();
		redirect('main/warehouse_integration_ims');
	}

	function update_item_table(){
		$this->usermodel->update_item_table();
	}

	function testing_sample(){

		$test = array('sample'=>'test','test'=>'value');

		$dorec['Delivery_Out'][] = $test;
		$drec = json_encode($dorec);

		$filepath = $_SERVER['DOCUMENT_ROOT'].'/inventory_live/application/JSON_FILES/testing.json';
		//Store in the filesystem.
		$fp = fopen($filepath , "w");
		fwrite($fp, $drec);
		fclose($fp);

	}

	function download_sap_do_old(){

		$this->usermodel->download_sap_do_old();

	}


	// INVENTORY INSIGHTSs
	function inventory_shares(){

		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			
			$this->load->view('inventory_insight/inventory_shares',$data);
		}
		else{
			redirect('main');
		}

	}

	function NCR_data(){

        $data = $this->usermodel->NCR_data(); 
 
        //data to json 
 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Topping", 
            "pattern" => "", 
            "type" => "string" 
        ); 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Total", 
            "pattern" => "", 
            "type" => "number" 
        ); 
        foreach($data as $cd) 
            { 

            $totQty = ($cd->delin - ($cd->delout + $cd->delres));
            $total_items = $totQty + $cd->delres;

            $responce->rows[]["c"] = array( 
                array( 
                    "v" => (int)$total_items." - "."$cd->wh_name", 
                    "f" => null 
                ) , 
                array( 
                    "v" => (int)$total_items, 
                    "f" => null 
                ) 
            ); 
            } 
 
        echo json_encode($responce); 
    }

    function SOUTH_data(){

        $data = $this->usermodel->SOUTH_data(); 
 
        //data to json 
 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Topping", 
            "pattern" => "", 
            "type" => "string" 
        ); 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Total", 
            "pattern" => "", 
            "type" => "number" 
        ); 
        foreach($data as $cd) 
            { 

            $totQty = ($cd->delin - ($cd->delout + $cd->delres));
            $total_items = $totQty + $cd->delres;

            $responce->rows[]["c"] = array( 
                array( 
                    "v" => (int)$total_items." - "."$cd->wh_name", 
                    "f" => null 
                ) , 
                array( 
                    "v" => (int)$total_items, 
                    "f" => null 
                ) 
            ); 
            } 
 
        echo json_encode($responce); 
    }

    function NORTH_data(){

        $data = $this->usermodel->NORTH_data(); 
 
        //data to json 
 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Topping", 
            "pattern" => "", 
            "type" => "string" 
        ); 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Total", 
            "pattern" => "", 
            "type" => "number" 
        ); 
        foreach($data as $cd) 
            { 

            $totQty = ($cd->delin - ($cd->delout + $cd->delres));
            $total_items = $totQty + $cd->delres;

            $responce->rows[]["c"] = array( 
                array( 
                    "v" => (int)$total_items." - "."$cd->wh_name", 
                    "f" => null 
                ) , 
                array( 
                    "v" => (int)$total_items, 
                    "f" => null 
                ) 
            ); 
            } 
 
        echo json_encode($responce); 
    }


    function inventory_insight_whse(){

		if($this->session->userdata('logged_in')){
				$data['user'] = $this->usermodel->signin_user();
				$data['modaccess']=$this->usermodel->access_module_approve();
				$data['records']=$this->usermodel->whse_list_insight();
				
				$this->load->view('inventory_insight/inventory_insight_whse',$data);
		}
		else{
				redirect('main');
		}

	}


	function inventory_insight_whse_edit(){

		if($this->session->userdata('logged_in')){
				$data['user'] = $this->usermodel->signin_user();
				$data['modaccess']=$this->usermodel->access_module_approve();
				$data['records']=$this->usermodel->whse_list_insight_edit();
				
				$this->form_validation->set_rules('wh_loc', 'Warehouse Location', 'required|trim');

				if($this->form_validation->run()){
					$this->usermodel->inventory_insight_whse_update();
					redirect('main/inventory_insight_whse');
				}else{
					$this->load->view('inventory_insight/inventory_insight_whse_edit',$data);
				}
				
		}
		else{
				redirect('main');
		}

	}


	function ims_to_sap_logs() {
		if($this->session->userdata('logged_in')){
			$data['user'] = $this->usermodel->signin_user();
			$data['modaccess']=$this->usermodel->access_module_approve();
			$data['whlist']=$this->usermodel->whlist();
			$data['whouse']= $this->input->post('whouse');
			$data['refname']= $this->input->post('refname');
			$data['sdate']=$this->input->post('sdate');
			$data['edate']=$this->input->post('edate');
			
			if ($this->input->post('do_number') <> '' AND $this->input->post('dr_number') == '') {
				$data['rec']=$this->usermodel->get_ims_to_sap_logs_do();
			} elseif ($this->input->post('do_number') == '' AND $this->input->post('dr_number') <> '') {
				$data['rec']=$this->usermodel->get_ims_to_sap_logs_dr();
			} else {
				$data['rec']=$this->usermodel->get_ims_to_sap_logs();
			}

			$this->load->view('reports/ims_to_sap_logs', $data);

		}
		else{
			redirect('main');
		}
	}

	function babylyn() {
		echo "BABY LYN MANALO";
	} 

}

?>