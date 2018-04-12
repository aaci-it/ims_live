
<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php echo $this->load->view('header'); ?>

<?php $stat_list = array('1'=>'Yes', '0'=>'No'); ?>

<!-- DT Picker -->
<link rel="stylesheet" href="<?php echo base_url();?>DTPicker/css/bootstrap-datetimepicker.min.css" >
<script src="<?php echo base_url();?>DTPicker/js/bootstrap-datetimepicker.min.js"></script>
<!-- End of File -->

<style type="text/css">
	#back_btn a{
		color: white;
		text-decoration: none;
	}

	#error_msg{
		border-radius: 0px;
	}

	.js #error_msg, #dialog-message{
		display: none;
	}

	.ui-dialog-titlebar-close {
		visibility: hidden;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';

	//ALLOW NUMBER ON TEXTFIELD
	function isNumberKey(evt){

         var charCode = (evt.which) ? evt.which : evt.keyCode;
         if (charCode != 46 && charCode > 31 
           && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }

      $(document).ready(function(){

      	$('#datepicker').datetimepicker({
	      pickTime: false
		});

      });

</script>

<?php echo form_open(); ?>

<?php if(isset($record)): foreach($record as $r): ?>

<div class="container-fluid">
	<div class="col-md-12">
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Edit Transaction Series</strong></div>
				<div class="panel-body">

					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-danger" id="error_msg">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong><?php if(isset($error)){echo $error;} ?></strong>
								<strong><?php echo validation_errors(); ?></strong>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<label>Warehouse Name</label>
						<input	type="text" name="wh_name" value="<?php echo $r->wh_name; ?>" readonly><br><br>

						<label>Next Num</label>
						<?php if($this->input->post('nno')){$next_no = $this->input->post('nno');}else{$next_no = $r->sn_nextnum;} ?>
						<input type="text" name="nno" value="<?php echo $next_no; ?>" onkeypress="return isNumberKey(event)">
						<br><br>

						<input type="submit" name="submit" value="Update" class="btn btn-info">
						<button type="button" class="btn btn-danger" id="back_btn"><?php echo anchor('main/transaction_series', 'Back'); ?></button>	
					</div>
					<div class="col-md-3">
						<label>Transaction Type</label>
						<input  type="text" name="desc" value="<?php echo $r->sn_name ?>" readonly><br><br>
						
						<label>Validity Date</label>
						<div class='input-group date' id='datepicker' style="width: 200px;">
							<?php if($this->input->post('vdate')){$vdate = $this->input->post('vdate');}else{$vdate = $r->validity_date;} ?>
				            <input type="text" name="vdate" value="<?php echo $vdate; ?>" class="form-control" id="datepicker" data-format="yyyy-MM-dd">
								<span class="add-on input-group-addon">
									<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
								</span>
						</div>

					</div>
					<div class="col-md-3">
						<label>First No</label>
						<?php if($this->input->post('fno')){$fno = $this->input->post('fno');}else{$fno = $r->sn_firstnum;} ?>
						<input type="text" name="fno" id="fno" value="<?php echo $fno; ?>" onkeypress = "return isNumberKey(event)">
						<br><br>
						<label>Status</label>
						<?php echo form_dropdown('stats', $stat_list, $r->sn_status); ?>
					</div>
					<div class="col-md-3">
						<label>Last No</label>
						<?php if($this->input->post('lno')){$lno = $this->input->post('lno');}else{$lno = $r->sn_lastnum;} ?>
						<input type="text" name="lno" id="lno" value="<?php echo $lno ?>" onkeypress = "return isNumberKey(event)">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php endforeach; ?>
<?php endif; ?>

<?php echo form_close(); ?>

<div id="dialog-message" title="Transaction Series">
	<p>
	  <div id="success_msg">
	  <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 10px 0;"></span>
	    	Records has been successfully updated.
	  </div>
	</p>
</div>

<!-- PLUG-INS FOR SUCCESS MESSAGEBOX -->
 <link rel="stylesheet" href="<?php echo base_url() ?>jquery-ui/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="<?php echo base_url() ?>jquery-ui/jquery-ui.js"></script>
<!-- END OF FILE -->

<script type="text/javascript">
	
	<?php if(validation_errors() OR isset($error)): ?>
		$('#error_msg').show();
	<?php else: ?>
		$('#error_msg').hide();
	<?php endif; ?>

	<?php  

		$temp = explode('-', $this->uri->segment(3));
		$wh_code = $temp[0];
		$slen = 0;

		if(strlen($wh_code) == 2){
			$slen = -34;
		}else{
			$slen = -33;
		}

	?>

	<?php if($this->uri->segment(4) == "ats_02"): ?>
		$('#dialog-message').show();
		$("#dialog-message").show();
				$("#dialog-message").dialog({
					modal: true,
					buttons: {
						Ok: function() {
					  		$(this).dialog( "close" );
					  		var x = window.location.href.slice(0, <?php echo $slen ?>);
				          	window.location.href = x + '/transaction_series';
						}
					}
				});
				
	<?php endif; ?>

</script>