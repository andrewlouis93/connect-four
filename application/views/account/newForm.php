<!DOCTYPE html>

<html>
	<head>

		<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/login.css">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>		
		<style>
			input {
				display: block;
			}
		</style>

		<style>
			input {
				display: block;
			}
		</style>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script>
			function checkPassword() {
				var p1 = $("#pass1"); 
				var p2 = $("#pass2");
				
				if (p1.val() == p2.val()) {
					p1.get(0).setCustomValidity("");  // All is well, clear error message
					return true;
				}	
				else	 {
					p1.get(0).setCustomValidity("Passwords do not match");
					return false;
				}
			}
		</script>
	</head> 
<body>  
<form id='newform' action="<?=base_url()?>account/createNew" method="post" accept-charset="utf-8">
	<h1>CONNECT<span>4</span></h1>
	<input type="text" name="username" class='input' placeholder="Username" value="" required="">
	<input type="password" name="password" class='input' placeholder="Password" value="" id="pass1" required="">
	<input type="password" name="passconf" class='input' placeholder="Password Confirmation" value="" id="pass2" required="" oninput="checkPassword();">
	<input type="text" name="first" value="" placeholder='First Name' class='input' required="">
	<input type="text" name="last" value="" placeholder='Last Name' class='input' class='input' required="">
	<input type="text" name="email" value="" placeholder='Email' class='input' required="">
	<input type="submit" name="submit" class="loginbutton" value="Register">
</form>

<?php 
	// echo form_open('account/createNew');
	// echo form_label('Username'); 
	// echo form_error('username');
	// echo form_input('username',set_value('username'),"required");
	// echo form_label('Password'); 
	// echo form_error('password');
	// echo form_password('password','',"id='pass1' required");
	// echo form_label('Password Confirmation'); 
	// echo form_error('passconf');
	// echo form_password('passconf','',"id='pass2' required oninput='checkPassword();'");
	// echo form_label('First');
	// echo form_error('first');
	// echo form_input('first',set_value('first'),"required");
	// echo form_label('Last');
	// echo form_error('last');
	// echo form_input('last',set_value('last'),"required");
	// echo form_label('Email');
	// echo form_error('email');
	// echo form_input('email',set_value('email'),"required");
	// echo form_submit('submit', 'Register');
	// echo form_close();
?>	
</body>

</html>

