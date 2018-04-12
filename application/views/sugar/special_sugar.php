
<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>

<?php $this->load->view('header');?>


<script type="text/javascript">

	$(document).ready(function(){

		$('.col-sm-7').hide();
		$('.col-sm-7').fadeIn(1000);

		$('.col-sm-2').hide();
		$('.col-sm-2').slideDown(1000);

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
					<td><strong><?php echo $total_items; ?></strong></td>
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
	            <li><?php echo anchor('main/home', 'Raw Sugar'); ?></li>
	            <li><?php echo anchor('main/premium_raw', 'Premium Raw (Washed) Sugar'); ?></li>
	            <li><?php echo anchor('main/plantation', 'Plantation White Sugar'); ?></li>
	            <li><?php echo anchor('main/standard_refined', 'Standard Refined Sugar'); ?></li>
	            <li><?php echo anchor('main/premium_refined', 'Premium Refined Sugar'); ?></li>
	            <li class="active"><?php echo anchor('main/special_sugar', 'Special Granulated Sugar', 'style="background: white; color: #3e3e40;"'); ?></li>
	            <li><?php echo anchor('main/turbinado', 'Turbinado'); ?></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </div>
	  </div>
	  <div class="col-sm-7">
	  	<div class="panel panel-default">
	  		<div class="panel-body">
	  			<div class="panel-heading" style="background-color: #337ab7; color:white;"><strong>Special Granulated Sugar</strong></div><br>
			
	  			<p style="text-align: justify;">Special Granulated Sugar is refined sugar that has been sifted in order to remove all but the finest of sugar crystals. This sifted sugar provides clients with minimal dissolving and churning time, allowing for greater operational efficiency.</p>
	  			<p style="text-align: justify;">Products that use SGS include dressings, creams and pastries.</p>

			</div>
	  	</div>
	  </div>
	  <div class="col-sm-2">
	  	<img src="<?php echo base_url();?>sugar/SGS.jpg">
	  </div>
	</div>
</div>

<br><br>

<?php endif;  ?>
<?php endforeach; ?>
<?php endif; ?>

<?php $this->load->view('footer');?>