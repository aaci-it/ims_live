<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

<?php echo form_open();?>
<?php foreach ($records as $r):?>

<style type="text/css">
	
	#back_btn a{
		color: white;
		text-decoration: none;
	}

</style>

<script type="text/javascript">
	
	$(document).ready(function(){

		function toTitleCase(str){
    		return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
		}

		$('#new_item_group').keyup(function(){
		    if($(this).val()!=''){
				$(this).val( toTitleCase($(this).val()) );
		    }    
		});

		$('#new_item_group').attr('readonly', true);

		// ADD NEW ITEM GROUP LIST
		$('#btn-new').click(function(){
			$('#new_item_group').focus();
			$('#new_item_group').attr('readonly', false);

			if($("#new_item_group").val() == ""){

	    	}else{
	    		var newitem = $("#new_item_group").val();
	    		// newitem = toTitleCase(newitem);
	    		// newitem = newitem.toUpperCase();

	    		var exists = false;
				$('#item_group_list option').each(function(){
				    if ($(this).text() == newitem) {
				        exists = true;
				        return false;
				    }
				});

				if(exists == false){
					$("#item_group_list").append('<option value="' + newitem + '" selected>' + newitem + '</option>');
	    			$("#new_item_group").attr("readonly", true);
	    			$("#new_item_group").val("");
	    			$('#new_grpname').val(newitem);
				}
	    		
	    	}
		})

	    // DISABLE ADD ITEM GROUP FIELD
	     $("#item_group_list").click(function(){

	     	$("#new_item_group").attr("readonly", true);
	     	$("#new_item_group").val("");

	     });

	     $("#new_item_group").blur(function(){
	     	$(this).value = "";
	     	$(this).attr('readonly', true);
	     	$('#item_group_list').focus();
	     });

	     $('#item_group_list').change(function(){
	     	$('#new_grpname').val($("#item_group_list :selected").text());
	     });

	     <?php if($r->item_group == ""): ?>
	     	$('#new_grpname').val($("#item_group_list :selected").text());
	     <?php else: ?>
	     	$('#new_grpname').val($("#item_group_list :selected").text());
	     <?php endif; ?>

	});

</script>

<style type="text/css">
	.js #error_msg{
		display: none;
	}
</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php $stats = array('1'=>'Yes', '0'=>'No'); ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Edit Item</strong></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-danger" id="error_msg">
								<?php if(isset($duplicate)){
								echo $duplicate;}?>
								<?php echo validation_errors();?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label>Code</label>
							<input type="text" name="icode" value="<?php echo $r->comm__id;?>" readonly/><br/><br/>
					
							<label>Item Type</label>
							<?php echo form_dropdown('item_type', $item_type, $r->item_type); ?><br>

							<input type="submit" name="update" value="Update" class="btn btn-info" />
							<button class="btn btn-danger" id="back_btn"><?php echo anchor('main/item_list', 'Back'); ?></button>
						</div>
						<div class="col-md-3">
							<label>Code 2</label>
							<input type="text" name="code2" value="<?php echo $r->comm__code2; ?>"><br><br/>

							<label>Item Sub-Type</label>
							<?php echo form_dropdown('item_subtype', $item_subtype, $r->item_subtype); ?><br>

						</div>
						<div class="col-md-3">
							<label>Description</label>
							<!-- <input type="text" name="iname" value="<?php echo $r->comm__name;?>" maxlength="50" size="50" /> -->
							<textarea name="iname" style="width: 250px;"><?php echo $r->comm__name;?></textarea><br>
							<label>Active</label>
							<?php echo form_dropdown('item_status', $stats, $r->status); ?><br> 
						</div>
						<div class="col-md-3">
							<label>Group</label>
							<!-- <input type="text" name="iname" class="form-control" maxlength="50" size="50" required/> -->
							<?php echo form_dropdown('item_group', $item_group, $r->item_group, 'id="item_group_list"'); ?>
							<button type="button" class="btn btn-info btn-md" id="btn-new"><span class="glyphicon glyphicon-plus-sign"></span></button><br>
							<input type="text" name="new_item_group" id="new_item_group" class="form-control" placeholder="New Item Group Name">
							<input type="hidden" name="new_grpname" id="new_grpname" >
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php endforeach;?>
<?php echo form_close();?>

<?php $this->load->view('footer');?>

<script type="text/javascript">
	$('#error_msg').hide();

	<?php if(validation_errors() OR isset($duplicate)): ?>
	 $('#error_msg').show();
	<?php else: ?>
	 $('#error_msg').hide();
	<?php endif; ?>
</script>