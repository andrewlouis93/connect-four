
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

	echo "<form id='loginform' method='post' action='".base_url()."account/recoverPassword'>";
	echo "<h1>CONNECT<span>4</span></h1>";	
	echo "<input name='email' placeholder='Email' type=\"text\" class=\"input\"  required/>";
	echo "<input name='submit' type=\"submit\" class=\"loginbutton\" value=\"Recover Password\" />";
	echo "</form>";

	// echo form_open('account/recoverPassword');
	// echo form_label('Email'); 
	// echo form_error('email');
	// echo form_input('email',set_value('email'),"required");
	// echo form_submit('submit', 'Recover Password');
	// echo form_close();
?>	
</body>

</html>

