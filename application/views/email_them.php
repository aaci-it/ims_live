<?php

/*if($whse_summary){
	foreach($whse_summary as $row){
		$whse_name = $row->wh_name;
		echo $whse_name;
	}
}*/
echo $this->acl_model->whse_trn_summary();
//echo 'Email_them';
?>