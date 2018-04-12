<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/reset.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/text.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dr.css" />

	<script src="<?php echo base_url();?>tablesorter-master/js/jquery-3.0.0.min.js"></script>
	<title></title>

	<script type="text/javascript">

		$(document).ready(function() {
		   window.print();
		   history.go(-1);
		});

	</script>

</head>
<body>
<?php
/*if(!$this->uri->segment(3)){
	redirect('main\index');	
}*/



/*if($dr){
	foreach($dr as $diar){
		if($diar->dr_number == NULL ){
			echo form_open();
			echo 'Enter DR number :'.form_input('dr_no').' '.form_submit('add_dr_no', 'Add');
			echo '<input type="hidden" name="do_num" value="'.$diar->do_number.'" />';
			echo form_close();
			$show_layout = 0;
		}
		else{
			if($print){
				foreach($print as $row){
					$truck = $row->Truck;
					$address = $row->Address2;
					$del_date = $row->DocDueDate;
					$bp = $row->CardName;
					$docnum = $row->DocNum;
					$po_no = $row->U_PONo;
					$qty = number_format($row->Quantity,2,'.','');
					$uom = $row->unitMsr;
					$description = $row->Dscription;
					$rem = $row->Comments;
				}
			$show_layout = 1;
			}
		}
	}
}*/
$show_layout = 0;

if($print){
	foreach($print as $row){
		$truck = $row->Truck;
		$address = $row->Address2;
		$del_date = $row->DocDueDate;
		$bp = $row->CardName;
		$docnum = $row->DocNum;
		$po_no = $row->U_PONo;
		$qty = number_format($row->Quantity,2,'.','');
		$uom = $row->unitMsr;
		$description = $row->Dscription;
		$rem = $row->Comments;
	}
$show_layout = 1;
}


?>

<?php if($show_layout == 1){ ?>
	<div id="dr">
		<div id="l1">
			<div id="top">
				<?php
					echo $docnum;
					echo '<br />'.$del_date;
				?>
			</div>
		</div>
		
		<div id="l2">
			<div id="uno">
				<?php
					echo $truck;
				?>
			</div>
			<div id="dos">
				<?php
					// echo '<center>'..'</center>';
					echo $bp;
					echo '<br />'.'Deilver To: '.$address;
				?>
			</div>
		</div>
		
		<div id="l3">
			<div id="tres">&nbsp;</div>
			<div id="quatro">
				<?php
					echo $po_no;
				?>
			</div>
			<div id="cinco">&nbsp;</div>
			<div id="sais">&nbsp;</div>
			<div id="siete">
				<?php
					echo $docnum;
				?>
			</div>
		</div>
		
		<div id="l4">
			<div id="ocho">
				<?php
					echo $qty;
				?>
			</div>
			<div id="nueve">
				<?php
					echo $uom;
				?>
			</div>
			<div id="dyes">
				<?php
					echo $description;
				?>
			</div>
		</div>
		
		<div id="l5">
			<div id="onse">&nbsp;</div>
			<div id="dose">
				<?php
					echo $rem;
				?>
			</div>
		</div>	
	</div>
<?php } else{ 
	$do = array('placeholder'=>'DO Number','id'=>'do','name'=>'do'); 
	echo form_open(); 
	echo form_input($do); 
	echo form_submit('Submit', 'Submit'); 
	echo form_close(); }

?>
</body>
</html>
