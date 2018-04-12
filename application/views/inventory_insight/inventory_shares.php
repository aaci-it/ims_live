<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php 
	date_default_timezone_set("Asia/Manila");
	$datetime = date('Y-m-d');

	$xls_name = "Warehouse_Integration_Lists_".$datetime.".xls";

?>

<?php echo form_open(current_url());?>

<style type="text/css">
	
	button a, button a:hover{
		color: white;
	}

	th, td{width:100px !important;white-space:nowrap !important;}

	#export_xls a{
			color: white;
			text-decoration: none; 
	}

	.del, #del_msg, #del_info{
		border-radius: 0px;
	}

	.js #del_msg, #del_info{
		display: none;
	}

	.well{
		border-radius: 0px;
		background: white;
	}

	.well label{
		text-align: center;
	}

	#chart_ncr{
		display: table;
    	margin: 0 auto; 
	}

	#lbl_ncr{
		font-size: 20px;
	}

	#chart_south{
		display: table;
    	margin: 0 auto; 
	}

	#lbl_south{
		font-size: 20px;
	}

	#chart_north{
		display: table;
    	margin: 0 auto; 
	}

	#lbl_north{
		font-size: 20px;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>


<!--Load the AJAX API--> 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>js/loader.js"></script>
<!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>  -->
<script type="text/javascript"> 
     
    // Load the Visualization API and the piechart package. 
    google.charts.load('current', {'packages':['corechart']}); 
       
    // Set a callback to run when the Google Visualization API is loaded. 
    google.charts.setOnLoadCallback(NCR); 
       
    function NCR() { 
      var jsonData = $.ajax({ 
          url: "<?php echo base_url() . 'index.php/main/NCR_data' ?>", 
          dataType: "json", 
          async: false 
          }).responseText; 
           
      // Create our data table out of JSON data loaded from server. 
      var data = new google.visualization.DataTable(jsonData); 
 
      // Instantiate and draw our chart, passing in some options. 
      var chart = new google.visualization.PieChart(document.getElementById('chart_ncr')); 
      chart.draw(data, {width: 900, height: 500, is3D: true}); 
    } 

    // Set a callback to run when the Google Visualization API is loaded. 
    google.charts.setOnLoadCallback(SOUTH); 

    function SOUTH() { 
      var jsonData = $.ajax({ 
          url: "<?php echo base_url() . 'index.php/main/SOUTH_data' ?>", 
          dataType: "json", 
          async: false 
          }).responseText; 
           
      // Create our data table out of JSON data loaded from server. 
      var data = new google.visualization.DataTable(jsonData); 
 
      // Instantiate and draw our chart, passing in some options. 
      var chart = new google.visualization.PieChart(document.getElementById('chart_south')); 
      chart.draw(data, {width: 900, height: 500, is3D: true}); 
    } 


    // Set a callback to run when the Google Visualization API is loaded. 
    google.charts.setOnLoadCallback(NORTH); 
    
    function NORTH() { 
      var jsonData = $.ajax({ 
          url: "<?php echo base_url() . 'index.php/main/NORTH_data' ?>", 
          dataType: "json", 
          async: false 
          }).responseText; 
           
      // Create our data table out of JSON data loaded from server. 
      var data = new google.visualization.DataTable(jsonData); 
 
      // Instantiate and draw our chart, passing in some options. 
      var chart = new google.visualization.PieChart(document.getElementById('chart_north')); 
      chart.draw(data, {width: 900, height: 500, is3D: true}); 
    } 

 
</script> 


<h5>
	<div class="container-fluid">

		<div class="row">
			<div class="col-md-12">
				<div class="well">
					<button class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;<?php echo anchor('main/inventory_insight_whse', 'Settings');?></button><br><br>
					<label id="lbl_ncr"><strong>NCR - National Capital Region</strong></label>
					<div id="chart_ncr"></div> 
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="well">
					<label id="lbl_south"><strong>Southern Luzon</strong></label>
					<div id="chart_south"></div> 
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="well">
					<label id="lbl_north"><strong>Northern Luzon</strong></label>
					<div id="chart_north"></div> 
				</div>
			</div>
		</div>


	</div>
</h5>

<?php echo form_close();?>
<?php $this->load->view('footer');?>

<br><br>

<script type="text/javascript">
	
	$('#export_xls a').click(function(){
		$('#tft').remove();
		return ExcellentExport.excel(this, 'myTable', 'Warehouse_Integration_Lists');
	});

	$('#del_msg').hide();
	$('#del_info').hide();

	<?php if($can_del == 1): ?>
		$('#del_info').hide();
		$('#del_msg').fadeIn(1200);
		$('#del_msg').slideDown(1200);
		$('#del_msg').show();
	<?php endif; ?>

	$('#del_no').click(function(){
		$('#del_msg').fadeOut(1200);
	});

	$('#del_ok').click(function(){
		$('#del_info').fadeOut(1200);
	});

</script>