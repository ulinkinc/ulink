		<script type="text/javascript">


$(document).ready(function(){
  jQuery.validator.addMethod("noSpecialChars", function(value, element) {
            return this.optional(element) || /^[a-z0-9\_\s]+$/i.test(value);
        });

	$("#changeEmailForm").validate({
	rules: {
					
					'data[Admin][email]'				: {
															required: true,
															email:true,
															
													  },
				
			   },
			   messages: {
					
													
					'data[Admin][email]'			:	{ 
														required: "Please enter email",
														email: "Please enter valid email ",
														
														},	
					
					
												
				}	
			
	});
	
		$("#changePasswordForm").validate({
	rules: {
					
					'data[Admin][currentpassword]'			:	{
															required:true,
															minlength:5
														},
					'data[Admin][password]'			:	{
															required:true,
															minlength:5
														},
					'data[Admin][confirmpassword]'	:	{
															required:true,
															equalTo: "#AdminPassword"
														},
				
			   },
			   messages: {
					
													
						'data[Admin][currentpassword]'			:	{ 
														required:"Please enter current password",
														minlength: "Please enter at least 5 characters: letters"	
														},				
						'data[Admin][password]'			:	{ 
														required:"Please enter new password",
														minlength: "Please enter at least 5 characters: letters"	
														},
					'data[Admin][confirmpassword]'	:	{ 
														required:"Please enter confirm  password",
														equalTo:"Two password doesn't match"
														},	
					
					
												
				}	
			
	});
	
	
		$("#changeUserNameForm").validate({
	rules: {
					'data[Admin][username]'			:	{
															required: true,
															noSpecialChars:true
															
														},
				
				
			   },
			   messages: {
					
													
						'data[Admin][username]'			:	{ 
														required: "Please enter username",
														noSpecialChars:"Please enter valid data"
													
														},							
					
					
												
				}	
			
	});
	
			}); 
</script>
		
		<h2 class="form_head">Administrator Settings</h2>
		<?php if( $msg != "" ) { ?>
		<div class="success-msg"><?php e($msg); ?></div>
		
		<?php } ?>
		<br />
		<form class="form_clear" id="changeEmailForm" method="post" action="<?php e($html->url('/admin/admins/change_password/')) ?>">
		
		<div class="form_input_box">
			<label>Email:</label>
			<?php e($form->input('Admin.email', array('label'=>false,'div'=>false))); ?>
		</div>
		
		<?php e($form->input('Admin.id', array('label'=>''))); ?>
		<br /><br />
		<div class="form_input_box">
			<label>&nbsp;</label>
			<input type="submit" value="Change" />
		</div>
		</form>
		
		<br /><br />
				
		<form class="form_clear" id="changeUserNameForm" method="post"  action="<?php e($html->url('/admin/admins/change_password/')) ?>">
		<h2 class="form_head">Change User Name</h2>
		
		<div class="form_input_box">
			<label>User name:</label>
			<?php e($form->input('Admin.username', array('label'=>false,'div'=>false))); ?>
		</div>
		<?php e($form->input('Admin.id', array('label'=>''))); ?>
		
		<div class="form_input_box">
			<label>&nbsp;</label>
			<input type="submit" value="Change" />
		</div>
		</form>
		
			<form class="form_clear" id="changePasswordForm" method="post"  action="<?php e($html->url('/admin/admins/change_password/')) ?>">
			<h2 class="form_head">Change Password</h2>
		<div class="form_input_box">
			<label>Current password:</label>
			<?php e($form->input('Admin.currentpassword', array('label'=>false,'div'=>false, 'type'=>'password', 'value'=>''))); ?>
		</div>
		
		<div class="form_input_box">
			<label>New password:</label>
			<?php e($form->input('Admin.password', array('label'=>false,'div'=>false, 'value'=>''))); ?>
		</div>
		
		<div class="form_input_box">
			<label>Confirm new password:</label>
			<?php e($form->input('Admin.confirmpassword', array('label'=>false,'div'=>false, 'type'=>'password', 'value'=>''))); ?>
		</div>
		
		<?php e($form->input('Admin.id', array('label'=>''))); ?>
		
		
			<div class="form_input_box">
					<label>&nbsp;</label>
					<input type="submit" value="Change" />
			</div>
		</form>