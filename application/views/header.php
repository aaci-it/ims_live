<?php
	if($this->uri->segment(2) == 'user_access_list' OR $this->uri->segment(2) == 'warehouse_list' OR $this->uri->segment(2) == 'item_list' ){
		if($this->session->userdata('user_lvl') != 'Admin'){ redirect('main/index'); }
	}
?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
	<?php if( $this->usermodel->download_pdf() != false ){ ?>
		<META http-equiv='refresh' content='1;URL=<?php echo $this->usermodel->download_pdf(); ?>'>
	<?php } ?>

		<link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.ico" />

		<script src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" >
		<!-- <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" > -->
		<!-- <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min-select.css"> -->
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/nav-bar.css" >

	<!-- jQuery -->
		<script src="<?php echo base_url();?>tablesorter-master/js/jquery-3.0.0.min.js"></script>


		<!-- EXPORT TO EXCEL -->
	    <script src="<?php echo base_url();?>exporttoexcel/js/excellentexport.js"></script>
	    <!-- END OF FILE -->

		<!-- Demo stuff -->
		<link rel="stylesheet" href="<?php echo base_url();?>tablesorter-master/docs/css/jq.css">
		<link rel="stylesheet" href="<?php echo base_url();?>tablesorter-master/docs/css/bootstrap-v2.min.css">
		<!-- <link href="<?php //echo base_url();?>tablesorter-master/docs/css/prettify.css" rel="stylesheet"> -->
		<script src="<?php echo base_url();?>tablesorter-master/docs/js/prettify.js"></script>
		<script src="<?php echo base_url();?>tablesorter-master/docs/js/docs.js"></script>

		<!-- Tablesorter: required for bootstrap -->
		<link rel="stylesheet" href="<?php echo base_url();?>tablesorter-master/css/theme.bootstrap_2.css">
		<script src="<?php echo base_url();?>tablesorter-master/js/jquery.tablesorter.js"></script>
		<script src="<?php echo base_url();?>tablesorter-master/js/jquery.tablesorter.widgets.js"></script>

		<!-- Tablesorter: optional -->
		<link rel="stylesheet" href="<?php echo base_url();?>tablesorter-master/addons/pager/jquery.tablesorter.pager.css">
		<script src="<?php echo base_url();?>tablesorter-master/addons/pager/jquery.tablesorter.pager.js"></script>


		<script id="js">$(function() {

		// NOTE: $.tablesorter.theme.bootstrap is ALREADY INCLUDED in the jquery.tablesorter.widgets.js
		// file for Bootstrap v3.x; it is included here to show how you can modify the default classes
		// for version 2.x (the iconSortAsc & iconSortDesc use different classes)
		$.tablesorter.themes.bootstrap = {
			// these classes are added to the table. To see other table classes available,
			// look here: http://getbootstrap.com/css/#tables
			table        : 'table table-bordered table-striped',
			caption      : 'caption',
			// header class names
			header       : 'bootstrap-header', // give the header a gradient background (theme.bootstrap_2.css)
			sortNone     : '',
			sortAsc      : '',
			sortDesc     : '',
			active       : '', // applied when column is sorted
			hover        : '', // custom css required - a defined bootstrap style may not override other classes
			// icon class names
			icons        : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
			iconSortNone : 'bootstrap-icon-unsorted', // class name added to icon when column is not sorted
			iconSortAsc  : 'icon-chevron-up', // class name added to icon when column has ascending sort
			iconSortDesc : 'icon-chevron-down', // class name added to icon when column has descending sort
			filterRow    : '', // filter row class
			footerRow    : '',
			footerCells  : '',
			even         : '', // even row zebra striping
			odd          : ''  // odd row zebra striping
		};

		// call the tablesorter plugin and apply the uitheme widget
		$("table").tablesorter({
			// this will apply the bootstrap theme if "uitheme" widget is included
			// the widgetOptions.uitheme is no longer required to be set
			theme : "bootstrap",

			widthFixed: true,

			headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

			// widget code contained in the jquery.tablesorter.widgets.js file
			// use the zebra stripe widget if you plan on hiding any rows (filter widget)
			widgets : [ "uitheme", "filter", "zebra" ],

			widgetOptions : {
				// using the default zebra striping class name, so it actually isn't included in the theme variable above
				// this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
				zebra : ["even", "odd"],

				// reset filters button
				filter_reset : ".reset",

				// hide the filter row when not active
				filter_hideFilters : true

			}
		})
		.tablesorterPager({

			// target the pager markup - see the HTML block below
			container: $(".ts-pager"),

			// target the pager page select dropdown - choose a page
			cssGoto  : ".pagenum",

			// remove rows from the table to speed up the sort of large tables.
			// setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
			removeRows: false,

			// output string - default is '{page}/{totalPages}';
			// possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
			output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'

		});

	});</script>

		<script>
		$(function(){

			// filter button demo code
			$('button.filter').click(function(){
				var col = $(this).data('column'),
					txt = $(this).data('filter');
				$('table').find('.tablesorter-filter').val('').eq(col).val(txt);
				$('table').trigger('search', false);
				return false;
			});

			// toggle zebra widget
			$('button.zebra').click(function(){
				var t = $(this).hasClass('btn-success');
				$('table')
					.toggleClass('table-striped')[0]
					.config.widgets = (t) ? ["uitheme", "filter"] : ["uitheme", "filter", "zebra"];
				$(this)
					.toggleClass('btn-danger btn-success')
					.find('i')
					.toggleClass('icon-ok icon-remove glyphicon-ok glyphicon-remove').end()
					.find('span')
					.text(t ? 'disabled' : 'enabled');
				$('table').trigger('refreshWidgets', [false]);
				return false;
			});
		});
		</script>

		<style type="text/css">
			body{
				background: #ebebeb;
				margin: 0;
				padding: 0;
			}
			a{
				color:#3a6f8f;
			}
			a:hover{
				text-decoration: none;
			}
			#t_header th{
				text-align: center;
				font-size: 18px;
				color: #3e3e40;
			}
	
			nav{
				margin-top: 10px;
			}

			#navi .nav-item a{
				text-transform: uppercase;
				font-weight: bold;
			}

			#footer{
			   position:fixed;
			   left:0px;
			   bottom:0px;
			   height:40px;
			   width:100%;
			   background:#337ab7;
			   padding: 15px;
			   z-index: 100;
			}

			#footer label{
				font-size: 11px;
				line-height: 0px;
				color: white;
				letter-spacing: 1.5px;
				font-weight: normal;
				padding-top: 5px;
			}

			#reports {
			    width: 300px !important;
			    font-family: Segoe UI;
			}

			#monitor, #monitor_t, #approve, #userdetails{
				font-family: Segoe UI;
			}

			#reports-t, #reports-c{
			    width: 200px !important;
			}

			#admin_list{
				width:	260px !important;
				font-weight: bold;
				font-family: Segoe UI;
			}

			#print_list{
				width: 230px !important;
				font-family: Segoe UI;
			}

			.navbar-right{
				margin-right: 15px;
			}

		</style>

</head>
<body>
<?php if(isset($user)): foreach ($user as $r):?>

<div class="container-fluid">
	
  	<nav class="navbar navbar-static-top navbar-dark bg-primary" role="navigation" style="min-width: 1200px!important;">
	  	
	  <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>                        
	      </button>
	  </div>


	  <ul class="nav navbar-nav" id="navi">
	  	
	  	<li class="navbar-brand" style="background: white;"><img style="height: 30px; width: 30px; margin-top: -5px;"src="<?php echo base_url(); ?>images/aaci_icon.ico"></li>
	   
	    <li class="nav-item">
	    	<?php if ($r->memb_comp == "CUSTOMER"):?>
	    		<a href='<?php echo base_url(); ?>index.php/main/home/<?php echo $this->uri->segment(3);?>'>Home</a>
	    	<?php else: ?>
	    		<?php echo anchor ('main','Home');?>
			<?php endif; ?>
		</li>

		<?php if(isset($modaccess)):foreach ($modaccess as $a):?>
		<?php if ($a->accessname == "Approve"):?>

		<!-- APPROVE MODULE -->
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">
                <strong style="text-transform:uppercase;">Approve</strong>
                <span class="glyphicon glyphicon-chevron-down"></span>
            </a>
            <ul class="dropdown-menu" id="approve">
	            <li><?php echo anchor('main/wh_delivery_approve_list','Delivery In - Out');?></li>
	            <li><?php echo anchor('main/wh_delivery_approve_mm_list/','Material Management');?></li>
	        </ul>
		</li>
		<?php endif;?>

		<!-- OUT MODULE -->
		<?php if ($a->accessname == "Out"):?>
		<li class="nav-item">
			<?php echo anchor('main/wh_delivery_out_list','Out');?>
		</li>
		<?php endif;?>

		<!-- MONITOR ALLSIAN MODULE -->
		<?php if ($a->accessname == "Monitor"):?>
		<li class="dropdown" id="monitor">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">
                <strong style="text-transform:uppercase;">Monitor</strong>
                <span class="glyphicon glyphicon-chevron-down"></span>
            </a>
            <ul class="dropdown-menu">
	            <li><?php echo anchor('main/wh_delivery_trckng_cdel','Monitored Deliveries');?></li>
	            <li><?php echo anchor('main/wh_delivery_trckng_list_internal','Monitored Internal');?></li>
	            <li><?php echo anchor('main/wh_delivery_trckng_list','In-Transit Deliveries');?></li>
	        </ul>

		</li>
		<?php endif;?>

		<!-- MONITOR TRUCKER MODULE -->
		<?php if ($a->accessname == "Monitor_ITD_AC_AGTI" OR $a->accessname == "Monitor_ITD_AC_WEI"):?>
		<li class="dropdown" id="monitor_t">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">
                <strong style="text-transform:uppercase;">Monitor</strong>
                <span class="glyphicon glyphicon-chevron-down"></span>
            </a>
            <ul class="dropdown-menu">
	            <li><?php echo anchor('main/wh_delivery_trckng_list','In-Transit Deliveries');?></li>
	        </ul>
		</li>
		<?php endif;?>

		<!-- CANCEL MODULE -->
		<?php if ($a->accessname == "Cancel"):?>
		<li class="nav-item">
			<?php echo anchor('main/wh_delivery_cancel_list','Cancel');?>
		</li>
		<?php endif?>

		<!-- MATERIAL MANAGEMENT LIST MODULE -->
		<?php if($a->accessname=="MM List"):?>
		<li class="nav-item">
			<?php echo anchor ('main/mm_list','MM List');?>
		</li>
		<?php endif?>

		<?php endforeach;?>
		<?php endif;?>

		<!-- ALLASIAN REPORTS -->
		<?php if ($r->memb_comp == "AACI"):?>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">
                <strong style="text-transform:uppercase;">Reports</strong>
                <span class="glyphicon glyphicon-chevron-down"></span>
            </a>

        	<ul class="dropdown-menu" id="reports">
	            <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Warehouse Reports</a>
                    <ul class="dropdown-menu">
                        <li><?php echo anchor('main/wh_delivery_unserve_list','Unserve Lists');?></li>
			            <li><?php echo anchor('main/wh_delivery_dr_summary','DR Summary Lists');?></li>
			            <li><?php echo anchor('main/wh_delivery_rr_summary','RR Summary Lists');?></li>
			            <li><?php echo anchor('main/wh_delivery_wis_summary','WIS Summary Lists');?></li>
			            <li><?php echo anchor('main/wh_delivery_war_summary','WAR Summary Lists');?></li>
			            <li><?php echo anchor('main/wh_delivery_cancelDO_list','Cancelled DO Lists');?></li>
			            <li><?php echo anchor('main/dspr2','Daily Stock Position Report');?></li>
			            <li><?php echo anchor('main/summary_of_receipts','Summary of Receipts'); ?></li>
			            <li><?php echo anchor('main/summary_of_issuance','Summary of Issuance'); ?></li>
			            <li><?php echo anchor('main/summary_of_print_docs','Summary of Print Documents'); ?></li>
			            <li><?php echo anchor('main/ims_to_sap_logs','IMS to SAP Logs'); ?></li>
                    </ul>
                </li>

                <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Delivery Confirmation List</a>
                    <ul class="dropdown-menu">
                        <li><?php echo anchor('main/confirmation_delivery_report','Delivery Confirmation - for AACI'); ?></li>
			            <li><?php echo anchor('main/confirmation_delivery_report_trucker','Delivery Confirmation - for Transporter'); ?></li>
			            <li><?php echo anchor('main/confirmation_delivery_report_customer','Delivery Confirmation - for Customer'); ?></li>
                    </ul>
                </li>

                <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Inventory Insight</a>
                    <ul class="dropdown-menu">
                        <li><?php echo anchor('main/inventory_shares','Inventory Shares'); ?></li>
                    </ul>
                </li>

	    	</ul>


		</li>
		<?php endif;?>

		<!-- TRUCKER REPORTS -->
		<?php if ($r->memb_comp == "AGTI" OR $r->memb_comp == "WEI"):?>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">
                <strong style="text-transform:uppercase;">Reports</strong>
                <span class="glyphicon glyphicon-chevron-down"></span>
            </a>

            <ul class="dropdown-menu" id="reports-t">
	                <li><?php echo anchor('main/confirmation_delivery_report_trucker','Delivery Confirmation'); ?></li>
	        </ul>
		</li>
		<?php endif; ?>

		<!-- CUSTOMER REPORTS -->
		<?php if ($r->memb_comp == "CUSTOMER"):?>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">
                <strong style="text-transform:uppercase;">Reports</strong>
                <span class="glyphicon glyphicon-chevron-down"></span>
            </a>

            <ul class="dropdown-menu" id="reports-c">
	                <li><a href='<?php echo base_url(); ?>index.php/main/confirmation_delivery_report_customer_onsite/<?php echo $this->uri->segment(3);?>'>Delivery Confirmation</a></li>
	        </ul>

		</li>
		<?php endif; ?>

		<!-- TRANSACTION LOGS MODULE -->
		<?php if ($r->memb_comp == "AACI"):?>
		<li class="nav-item">
			<?php echo anchor ('main/transactionsearch','T . Logs');?>
		</li>

		<!-- PRINT MODULE -->
		<li class="nav-item">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">
	                <strong style="text-transform:uppercase;">Print</strong>
	                <span class="glyphicon glyphicon-chevron-down"></span>
	            </a>
	            <ul class="dropdown-menu" id="print_list">
	                <li><a href="<?php echo base_url(); ?>index.php/main/print_dr_list">DR - Delivery Receipts</a></li>
	                <li><a href="<?php echo base_url(); ?>index.php/main/print_wis_list">WIS - Warehouse Issue Slip</a></li>
	                <li><a href="<?php echo base_url(); ?>index.php/main/print_rr_list">RR - Receiving Report</a></li>
	    			<li><a href="<?php echo base_url(); ?>index.php/main/print_tout_list">TOUT - Transfer Out</a></li>
	    			<li><a href="<?php echo base_url(); ?>index.php/main/print_tin_list">TIN - Transfer In</a></li>
	            </ul>
			</li>
		</li>
		<?php endif; ?>

		<!-- ADMINISTRATION MODULE -->
		<?php if($this->session->userdata('user_lvl') == 'Admin'):?>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">
               	<strong style="text-transform:uppercase;">Administration</strong>
               	<span class="glyphicon glyphicon-chevron-down"></span>
            </a>
            <ul class="dropdown-menu multi-level" id="admin_list">
                <li><?php echo anchor('main/user_access_list','User Access Lists');?></li>
                <li><?php echo anchor('main/warehouse_list','Warehouse Lists');?></span></li>
                <li><?php echo anchor('main/item_list','Item Lists');?></span></li>
                <!-- <li class="divider"></li> -->
                <li><?php echo anchor('main/customer_list','Customer Lists');?></li>
                <!-- <li class="divider"></li> -->
                <li><?php echo anchor('main/transaction_series','Transaction Series');?></li>
                <li><?php echo anchor('main/opening_balance','Opening Balance');?></li>
                <li><?php echo anchor('main/truck_company_list','Truck Company List');?></li>
                <li><?php echo anchor('main/truck_driver_list','Truck Driver List');?></li>
                <li><?php echo anchor('main/warehouse_integration','Warehouse Integration IMS to SAP');?></li>
                <li><?php echo anchor('main/warehouse_integration_ims','Warehouse Integration SAP to IMS');?></li>
            </ul>
		</li>
		<?php endif; ?>
	  </ul>

	  <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:#3e3e40; background:white;">
                        <span class="glyphicon glyphicon-user"></span> 
                        <strong><?php echo $r->memb__username;?></strong>
                        <span class="glyphicon glyphicon-chevron-down"></span>
                    </a>

                    <ul class="dropdown-menu" id="userdetails">

                    	<?php if ($r->memb_comp == "AACI"):?>
                    		<li><?php echo anchor('main/change_password', 'Change Password'); ?></li>
                        <?php else: ?>
                        	<li><a href='<?php echo base_url(); ?>index.php/main/customer_change_password/<?php echo $this->uri->segment(3);?>'>Change Password</a></li>
                        <?php endif; ?>

                        <?php if ($r->memb_comp == "AACI"):?>
                        	<li><?php echo anchor('main/logout','Sign out');?></li>
                        <?php elseif($r->memb_comp == "AGTI" OR $r->memb_comp == "WEI"): ?>
                        	<li><?php echo anchor('main/logout','Sign out');?></li>
                        <?php else: ?>
                        	<li><a href='<?php echo base_url(); ?>index.php/main/customer_logout/<?php echo $this->uri->segment(3);?>'>Sign Out</a></li>
                        <?php endif; ?>

                        <?php if($this->session->userdata('user_lvl') == 'Admin'): ?>
                        	<li><?php echo anchor('main/register','Register'); ?></li>                  
                        <?php endif; ?>

                        <?php if($this->session->userdata('user_lvl') == 'Admin'): ?>
                        	<li><?php echo anchor('main/user_access_list','Administration'); ?></li>          
                        <?php endif; ?>
                                            
			        </ul>

                </li>
            </ul>
	  
	</nav>
</div>

<?php endforeach;?>
<?php endif;?>
       
