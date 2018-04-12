<script src="<?php  echo base_url();?>bs/js/jquery.min.js"></script>
<?php $this->load->view('header');?>

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

		$('#item_group_list').change(function(){
		  $('#new_grpname').val($("#item_group_list :selected").text());
		});

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

	});

</script>

<style type="text/css">

	#error_msg{
		border-radius: 0px;
	}

	.js #error_msg, #dialog-message{
		display: none;
	}

	#back_btn a{
		color: white;
		text-decoration: none;
	}

	.ui-dialog-titlebar-close {
    	visibility: hidden;
	}

</style>

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php echo form_open();?>

<h5>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #3e3e40; color:white;"><strong>Add Item</strong></div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-danger" id="error_msg">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<label><strong><?php if(isset($duplicate)){echo $duplicate;} echo validation_errors(); ?></strong></label>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<label>Code</label>
							<input type="text" name="icode" class="form-control" value="<?php echo $this->input->post('icode') ?>" required/><br><br><br><br>
								
							<label>Item Type</label>
							<?php echo form_dropdown('item_type', $item_type, $this->input->post('item_type')); ?><br><br>

							<input type="submit" name="create" value="Create" class="btn btn-info" formnovalidate/>
							<button class="btn btn-danger" type="button" id="back_btn"><?php echo anchor('main/item_list', 'Back'); ?></button>
						</div>
						<div class="col-md-3">
							<label>Code 2</label>
							<input type="text" name="code2" class="form-control" value="<?php echo $this->input->post('code2') ?>">
							
							<br><br><br><br>

							<label>Item Sub-Type</label>
							<?php echo form_dropdown('item_subtype', $item_subtype, $this->input->post('item_subtype')); ?>

						</div>
						<div class="col-md-3">
							<label>Description</label>
							<!-- <input type="text" name="iname" class="form-control" maxlength="50" size="50" required/> -->
							<textarea name="iname" style="width: 250px; height: 85px;"><?php echo $this->input->post('iname'); ?></textarea>
							<br>

							<label>Active</label>
							<select name="item_status" class="form-control">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>

						</div>
						<div class="col-md-3">
							<label>Group</label>
							<!-- <input type="text" name="iname" class="form-control" maxlength="50" size="50" required/> -->
							<?php echo form_dropdown('item_group', $item_group, $this->input->post('item_group'), 'id="item_group_list"'); ?>
							<button type="button" class="btn btn-info btn-md" id="btn-new"><span class="glyphicon glyphicon-plus-sign"></span></button><br>
							<input type="text" name="new_item_group" id="new_item_group" class="form-control" placeholder="New Item Group Name">
							<input type="hidden" name="new_grpname" id="new_grpname" >
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</h5>

<?php echo form_close();?>

<?php $this->load->view('footer');?>

<script type="text/javascript">
	
	<?php if(validation_errors() OR isset($duplicate)): ?>
	  $('#error_msg').show();
	<?php else: ?>
	  $('#error_msg').hide();
	<?php endif; ?>

</script>

<!-- PLUG-INS FOR SUCCESS MESSAGEBOX -->
 <link rel="stylesheet" href="<?php echo base_url() ?>jquery-ui/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="<?php echo base_url() ?>jquery-ui/jquery-ui.js"></script>
<!-- END OF FILE -->

<script>

	// ERROR MESSAGE
	<?php if(isset($error) OR validation_errors()): ?>
			$('#error_msg').show();
		<?php else: ?>
			$('#error_msg').hide();	
		<?php endif; ?>

  	// SUCCESS MESSAGE
  	<?php if($this->uri->segment(3) == "1"): ?>

  	$(function(){
  		$( "#dialog-message" ).dialog({
	      modal: true,
	      buttons: {
	        Ok: function() {
	          $( this ).dialog( "close" );

	          var x = window.location.href.slice(0,-2);

		      window.location.href = x;

	        }
	      }
	    });
  	});
	   
	<?php else: ?>
		$('#dialog-message').hide();
	<?php endif; ?>

 </script>

<div id="dialog-message" title="Item Lists Information">
<br>
  <p>
    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
    New Item Record was successfully added.
  </p>
</div>