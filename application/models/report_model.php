<?php 
class report_model extends CI_Model {
	function __construct(){
	parent::__construct();
	}

	function war(){
		
		$qry = $this->db->query("SELECT * FROM dspr_war a 
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
	function war_date_search(){
		
		$qry = $this->db->query("SELECT * FROM dspr_war a 
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

	function total_war(){
		
		$qry = $this->db->query("SELECT IFNULL(SUM(b.wi_itemqty), 0) AS wi_itemqty FROM dspr_war a 
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

	function total_war_sort(){
		
		$qry = $this->db->query("SELECT IFNULL(SUM(b.wi_itemqty), 0) AS wi_itemqty FROM dspr_war a 
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

	// function war(){
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
	// 		a.wi_mmprocess,
	// 		a.wi_createdatetime,
	// 		CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName,
	// 		a.wi_remarks
	// 	FROM whir a
	// 	LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
	// 	LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
	// 	LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
	// 	WHERE wi_deltype = 'Material Management'
	// 		AND wi_approvestatus = 1
	// 		AND wi_status=1 
	// 		AND a.wh_name='".$this->input->post('whouse')."' 
	// 	OR wi_deltype = 'Material Management'
	// 		AND wi_status=1 
	// 		AND a.wh_name='".$this->input->post('whouse')."'
	// 	ORDER BY wi_id");
	// 	if($q->num_rows() == true){
	// 		return $q->result();
	// 	}
	// }


	// function war_date_search(){
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
	// 		a.wi_mmprocess,
	// 		a.wi_createdatetime,
	// 		CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName,
	// 		a.wi_remarks
	// 	FROM whir a
	// 	LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
	// 	LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
	// 	LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
	// 	WHERE wi_deltype = 'Material Management'
	// 		AND wi_approvestatus = 1
	// 		AND wi_status=1 
	// 		AND a.wh_name='".$this->input->post('whouse')."'
	// 		AND a.deldate
	// 			BETWEEN '".$this->input->post('sdate')." 00:00:01'
	// 			AND '".$this->input->post('edate')." 23:59:59'  
	// 	OR wi_deltype = 'Material Management'
	// 		AND wi_status=1 
	// 		AND a.wh_name='".$this->input->post('whouse')."'
	// 		AND a.deldate
	// 			BETWEEN '".$this->input->post('sdate')." 00:00:01'
	// 			AND '".$this->input->post('edate')." 23:59:59' 
	// 	ORDER BY wi_id");
	// 	if($q->num_rows() == true){
	// 		return $q->result();
	// 	}
	// }

	function cancel_DO(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT a.wi_reftype2,
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
			a.wi_doqty,
			wi_itemqty,wh_code,
			c.comm__name,
			d.CardName AS CName,
			a.wi_type,
			a.wi_mmprocess,
			a.wi_expecteddeliverydate,
			a.wi_exactdeliverydate,
			a.wi_cancelby,
			a.wi_canceldatetime,
			a.wi_cancelremarks,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
		FROM whir a
		LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
		LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
		LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
		WHERE wi_reftype='DO'
			AND wi_status=0 
			AND a.wh_name='".$this->input->post('whouse')."' 
		OR wi_reftype2='DO'
			AND wi_status=0
			AND a.wh_name='".$this->input->post('whouse')."'
		ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function cancel_DO_date_search(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT a.wi_reftype2,
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
			a.wi_doqty,
			wi_itemqty,wh_code,
			c.comm__name,
			d.CardName AS CName,
			a.wi_type,
			a.wi_mmprocess,
			a.wi_expecteddeliverydate,
			a.wi_exactdeliverydate,
			a.wi_cancelby,
			a.wi_canceldatetime,
			a.wi_cancelremarks,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
		FROM whir a
		LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
		LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
		LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
		WHERE wi_reftype='DO'
			AND wi_status=0 
			AND a.wh_name='".$this->input->post('whouse')."'
			AND a.deldate
				BETWEEN '".$this->input->post('sdate')." 00:00:01'
				AND '".$this->input->post('edate')." 23:59:59' 
		OR wi_reftype2='DO'
			AND wi_status=0
			AND a.wh_name='".$this->input->post('whouse')."'
			AND a.deldate
				BETWEEN '".$this->input->post('sdate')." 00:00:01'
				AND '".$this->input->post('edate')." 23:59:59'
		ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function total_can(){
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
		WHERE wi_reftype='DO'
			AND wi_status=0 
			AND a.wh_name='".$this->input->post('whouse')."' 
		OR wi_reftype2='DO'
			AND wi_status=0
			AND a.wh_name='".$this->input->post('whouse')."'
		ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function total_can_sort(){
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
		WHERE wi_reftype='DO'
			AND wi_status=0 
			AND a.wh_name='".$this->input->post('whouse')."'
			AND a.deldate
				BETWEEN '".$this->input->post('sdate')."'
				AND '".$this->input->post('edate')."' 
		OR wi_reftype2='DO'
			AND wi_status=0
			AND a.wh_name='".$this->input->post('whouse')."'
			AND a.deldate
				BETWEEN '".$this->input->post('sdate')."'
				AND '".$this->input->post('edate')."'
		ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}

	function ito(){
		if ($this->input->post('doctype') == '-Select-'){
			$dtype="";
		}
		else{
			$dtype=$this->input->post('doctype');
		}
		$q = $this->db->query("SELECT a.wi_reftype2,
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
			a.wi_doqty,
			wi_itemqty,
			wh_code,
			c.comm__name,
			d.CardName AS CName,
			a.wi_type,
			a.wi_mmprocess,
			a.wi_expecteddeliverydate,
			a.wi_exactdeliverydate,
			a.wi_cancelby,
			a.wi_canceldatetime,
			CASE WHEN d.CardName IS NULL THEN a.wi_refname ELSE d.CardName END AS CardName
		FROM whir a
		LEFT OUTER JOIN mwhr b ON a.wh_name=b.wh_name
		LEFT OUTER JOIN ocmt c ON a.item_id=c.comm__id
		LEFT OUTER JOIN ocrd d ON a.wi_refname=d.CardCode 
		WHERE wi_reftype='ITO'
			AND wi_status=1 
			AND a.wh_name='".$this->input->post('whouse')."' 
		OR wi_reftype2='ITO'
			AND wi_status=1
			AND a.wh_name='".$this->input->post('whouse')."'
		ORDER BY wi_id");
		if($q->num_rows() == true){
			return $q->result();
		}
	}
}
?>