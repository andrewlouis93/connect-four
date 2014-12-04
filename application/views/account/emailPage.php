
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
	<h1>Password Recovery</h1>
	
	<p>Please check your email for your new password.
	</p>
	
	
	
<?php 
	if (isset($errorMsg)) {
		echo "<div class='error_msg'>" . $errorMsg . "</div>";
	}

	echo anchor('/','Login','class="login_link"');
?>	
</body>

</html>

