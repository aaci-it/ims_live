
<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>

<?php $this->load->view('header');?>


<!--Load the AJAX API--> 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>js/loader.js"></script>
<!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>  -->
<script type="text/javascript"> 
     
    // Load the Visualization API and the piechart package. 
    google.charts.load('current', {'packages':['corechart']}); 
       
    // Set a callback to run when the Google Visualization API is loaded. 
    google.charts.setOnLoadCallback(drawChart); 
       
    function drawChart() { 
      var jsonData = $.ajax({ 
          url: "<?php echo base_url() . 'index.php/main/getdata' ?>", 
          dataType: "json", 
          async: false 
          }).responseText; 
           
      // Create our data table out of JSON data loaded from server. 
      var data = new google.visualization.DataTable(jsonData); 
 
      // Instantiate and draw our chart, passing in some options. 
      var chart = new google.visualization.PieChart(document.getElementById('chart_div')); 
      chart.draw(data, {width: 700, height: 300, is3D: true}); 
    } 
 
</script> 


<script type="text/javascript">

	$(document).ready(function(){

		$('.col-sm-7').hide();
		$('.col-sm-7').fadeIn(1000);

		$('.col-sm-2').hide();
		$('.col-sm-2').slideDown(1000);

		

		$(".total_items").on("click" ,function(){
		    $("html, body").animate({ scrollTop: $(document).height() }, 500);
  			return false;
	   });

	});
</script>

<style type="text/css">
	
	tbody a{
		font-weight: bold;
		text-transform: uppercase;
		color: #3e3e40;
	}

	/* make sidebar nav vertical */ 
	@media (min-width: 768px) {
	  .sidebar-nav .navbar .navbar-collapse {
	    padding: 0;
	    max-height: none;
	  }
	  .sidebar-nav .navbar ul {
	    float: none;
	    display: block;
	  }
	  .sidebar-nav .navbar li {
	    float: none;
	    display: block;
	  }
	  .sidebar-nav .navbar li a {
	    padding-top: 12px;
	    padding-bottom: 12px;
	  }
	}

	#navi2{
		border-radius: 0px;
	}

	#navi2 a{
		color: white;
		background: #337ab7;
		text-transform: uppercase;
		font-weight: bold;
	}

	.col-sm-2{
		border-left: 1px solid gray;
	}

	.navbar-collapse .nav li a:hover{
		background: white;
	}

	.well{
		border-radius: 0px;
	}

	.well label{
		text-align: center;
	}

	#chart_div{
		display: table;
    	margin: 0 auto; 
	}

	.total_items{
		text-decoration: none;
	}

</style>

<?php echo form_open();?>
<br/>

<?php if(isset ($user)):foreach ($user as $ua):?>
<?php if($ua->memb_comp == 'AACI'): ?>



<div id="main">
<table>
	<thead>
		<tr id="t_header">
			<th><span class="glyphicon glyphicon-home">&nbsp;</span>WAREHOUSE LIST</th>
			<th><span class="glyphicon glyphicon-shopping-cart">&nbsp;</span>AVAILABLE ITEMS</th>
			<th><span class="glyphicon glyphicon-shopping-cart">&nbsp;</span>RESERVED ITEMS</th>
			<th><span class="glyphicon glyphicon-shopping-cart">&nbsp;</span>TOTAL ITEMS</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="7" class="ts-pager form-horizontal">
				<button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
				<button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
				<span class="pagedisplay"></span> <!-- this can be any element, including an input -->
				<button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
				<button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
				<select class="pagesize input-mini" title="Select page size">
					<option selected="selected" value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
				</select>
				<select class="pagenum input-mini" title="Select page number"></select>
			</th>
		</tr>
	</tfoot>
	<tbody>
			<?php if(isset($warehouses)): foreach ($warehouses as $r):?>
			<?php $totQty = ($r->delin -($r->delout + $r->delres));

					$total_items = $totQty + $r->delres;
					$total_items = number_format($total_items,2);
			?>
		<?php if(isset ($uaccess)):foreach ($uaccess as $a):?>
			<?php if($a->memb_comp == 'AACI'): ?>
			<?php if ($a->accessname==$r->wh_name):?>
				<tr style="font-size: 15px;">
					<?php if(isset($umaccess)):?>
					<td><?php echo anchor('main/wh_delivery_item_in/'.$r->wh_code,$r->wh_name);?></td>
					<?php else:?>
					<td><?php echo $r->wh_name;?></td>
					<?php endif;?>
					<td><span class="glyphicon glyphicon-plus-sign">&nbsp;</span><?php echo anchor('main/wh_delivery/in/'.$r->wh_code,number_format($totQty,2));?> 
					<td><span class="glyphicon glyphicon-plus-sign">&nbsp;</span><?php echo anchor('main/wh_delivery_reserve/update/'.$r->wh_code,number_format($r->delres,2));?></td>
					<td><strong><a href="#" class="total_items"><?php echo $total_items; ?></a></strong></td>
				</tr>
			<?php endif;?>
			<?php endif;?><?php endforeach;?>
		<?php endif;?>
			<?php endforeach;?>
			<?php else:?>
		<tr>
			<td colspan=4>No record</td>
		</tr>
			<?php endif;?>
	</tbody>
</table>

<div class="row">
	<div class="col-md-12">
		<div class="well">
			<label id="lbl_total_items"><strong>Total Items Per Warehouse</strong></label>
			<div id="chart_div"></div> 
		</div>
	</div>
</div>


<?php echo form_close();?>
</div>

<?php else: ?>

<div class="container-fluid">
	<div class="row">
	  <div class="col-sm-3">
	    <div class="sidebar-nav">
	      <!-- <div class="navbar navbar-default" role="navigation"> -->
	      <div class="navbar navbar-dark bg-primary" id="navi2" role="navigation">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <span class="visible-xs navbar-brand">Sidebar menu</span>
	        </div>
	        <div class="navbar-collapse collapse sidebar-navbar-collapse">
	          <ul class="nav navbar-nav">
	            <li class="active"><a href="#" style="background: white; color: #3e3e40;">Raw Sugar</a></li>
	            <li><?php echo anchor('main/premium_raw', 'Premium Raw (Washed) Sugar'); ?></li>
	            <li><?php echo anchor('main/plantation', 'Plantation White Sugar'); ?></li>
	            <li><?php echo anchor('main/standard_refined', 'Standard Refined Sugar'); ?></li>
	            <li><?php echo anchor('main/premium_refined', 'Premium Refined Sugar'); ?></li>
	            <li><?php echo anchor('main/special_sugar', 'Special Granulated Sugar'); ?></li>
	            <li><?php echo anchor('main/turbinado', 'Turbinado'); ?></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </div>
	  </div>
	  <div class="col-sm-7">
	    <div class="panel panel-default">
	  		<div class="panel-body">
	  			<div class="panel-heading" style="background-color: #337ab7; color:white;"><strong>Raw Sugar</strong></div><br>
			    
	  			<p style="text-align: justify;">Raw cane sugar is sugar that is still coated with a fine film of molasses. It is the most natural, least-refined form of sugar.</p>
	  			<p style="text-align: justify;">Raw sugar is obtained from sugar cane, which is first washed and shredded, and then crushed and squeezed for a sugar juice called cane juice. This juice is then heated and filtered, then passed through an evaporator and vacuum pan to remove much of the water and form a syrup. The syrup is then passed to a centrifuge that separates the sugar crystals from the syrup, producing sugar lightly covered with molasses.</p>
	  			<p style="text-align: justify;">Since raw sugar is the most basic form of sugar, it is also has the lowest cost to to produce. It also has a wide color spectrum, ranging from dark sugar to nearly colorless.</p>
	  			<p style="text-align: justify;">Many companies use raw sugar in their production of chocolate, ice cream cones, pastries, beer and sauces. It is a favorite among those looking to reduce costs and are not picky about the color of their product.</p>
	  			<p style="text-align: justify;">Raw sugar is also used for export to be refined in other countries. It can also be refined in the Philippines by our country’s many refiners.</p>
	  			<p style="text-align: justify;">Raw sugar is particularly popular in Mindanao and other areas where consumers are more cost conscious.</p>

	  		</div>
	  	</div>
	  </div>
	  <div class="col-sm-2">
	  	<img src="<?php echo base_url();?>sugar/RAW.jpg">
	  </div>
	</div>
</div>

<br><br>

<?php endif;  ?>
<?php endforeach; ?>
<?php endif; ?>

<?php $this->load->view('footer');?>