
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

	</head> 
<body>  
<?php 
	if (isset($errorMsg)) {
		echo "<div class='error_msg'>" . $errorMsg . "</div>";
	}


	echo "<form id='loginform' method='post' action='".base_url()."account/login'>";
	echo "<h1>CONNECT<span>4</span></h1>";	
	echo "<input name='username' type=\"text\" class=\"input\" placeholder=\"Username\" required/>"; 
	echo "<input name='password' type=\"password\" class=\"input\" placeholder=\"Password\" />";
	echo "<input name='submit' type=\"submit\" class=\"loginbutton\" value=\"Login\" />";
	echo anchor('account/newForm','Create Account');
	echo "<br />";
	echo "<br />";
	echo "<br />";	
	echo anchor('account/recoverPasswordForm','Recover Password');	
	echo "</form>";


	// echo form_open('account/login', array('id' => 'loginform'));
	// echo form_label('Username'); 
	// echo form_error('username');
	// echo form_input('username',set_value('username'),"required");
	// echo form_label('Password'); 
	// echo form_error('password');
	// echo form_password('password','',"required");
	
	// echo form_submit('submit', 'Login');
	

	
	
	echo form_close();
?>	
</body>

</html>

