<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<link rel="stylesheet" href="<?php echo base_url(); ?>des/report/style.css" />
	<meta charset="UTF-8">
	<title></title>
</head>
<body>	
	<div id="wrapper">
		<div id="heads1">Customer</div>
		<div id="heads1">DO Number</div>
		<div id="heads1">DO QTY</div>
		<div id="heads1">Actual Del Qty</div>
		<div id="heads1">Rem Del Qty</div>
		<?php 
			if($cardcode){
				foreach($cardcode as $row){
					//echo $row->wi_refname;
					echo "<div id='heads1'>".$row->wi_refname."</div>";
					echo "<div id='heads1'>&nbsp;</div>";
					echo "<div id='heads1'>&nbsp;</div>";
					echo "<div id='heads1'>&nbsp;</div>";
					echo "<div id='heads1'>&nbsp;</div>";
					$details = array();
					$details = $this->usermodel->get_do_summary($row->wi_refname);
					foreach($details as $dets){
						echo "<div id='heads1'>&nbsp;</div>";
						echo "<div id='heads1'>".$dets->wi_refnum."</div>";
						echo "<div id='heads1'>".$dets->wi_doqty."</div>";
						echo "<div id='heads1'>".$dets->wi_itemqty."</div>";
						echo "<div id='heads1'>".$dets->TRQ."</div>";
					}
				}
			}else{
				echo 'No Data';
			}
		?>
	</div>
	<?php //SELECT `wi_refname`,`wi_refnum`,`wi_doqty`,`wi_itemqty`, 'wi_doqty'-'wi_itemqty' as 'TRQ' FROM `whir` WHERE `wi_reftype` = 'DO' ?>
</body>
</html>