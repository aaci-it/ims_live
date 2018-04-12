<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php echo form_open();?>

<style type="text/css">

	#pbody label{
		width: 150px;
	}

	#footer{
			   position:fixed;
			   left:0px;
			   bottom:0px;
			   height:40px;
			   width:100%;
			   background:#337ab7;
			   padding: 15px;
			}

			#footer label{
				font-size: 11px;
				line-height: 0px;
				color: white;
				letter-spacing: 1.5px;
				font-weight: normal;
			}

</style>

<h5>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Confirmation Delivery Out</strong></div>
				<div class="panel-body form-inline" id="pbody">
					<div class="col-md-7">
					<?php if (isset($delinfo)): foreach ($delinfo as $r):?>
				
					<label>Reference Name</label>
					<?php $name=array('name'=>'refname','maxlength'=> '50','size'=> '50','value'=>$r->wi_refname, 'readonly'=>'readonly'); echo form_input($name);?><br/><br/>
					
					<!--<label>Remarks</label>
					<?php $addr=array('name'=>'remarks','maxlength'=>'100'); echo form_textarea($addr);?>-->

					<?php endforeach;?><?php endif;?>

					<input type="submit" name="update" value="Submit" class="btn btn-info"/>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</h5><br/>

<div id="footer">
	<p style="text-align:center;">
		<label>Inventory Monitoring | All Asian Countertrade Inc. | ICT Department | © 2014 - Warehouse Management System</label>
	</p>
</div>
	
<?php if(isset($duplicate)){
echo $duplicate;}?>
<?php echo validation_errors();?>

<?php echo form_close();?>
