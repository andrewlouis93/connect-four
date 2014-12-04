
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
<?php 
	if (isset($errorMsg)) {
		echo "<div class='error_msg'>" . $errorMsg . "</div>";
	}

	echo "<form id='loginform' method='post' action='".base_url()."account/updatePassword'>";
		echo "<h1>CONNECT<span>4</span></h1>";	
		echo "<input name='oldPassword' type=\"password\" class=\"input\" placeholder=\"Current Password\" required/>"; 
		echo "<input id='pass1' name='newPassword' type=\"password\" class=\"input\" placeholder=\"New Password\" />";
		echo "<input id='pass2' name='passconf' type=\"password\" class=\"input\" placeholder=\"New Password Confirmation\" oninput='checkPassword();' required/>";
		echo "<input name='submit' type=\"submit\" class=\"loginbutton\" value=\"Change Password\" />";
	echo "</form>";


	// echo form_open('account/updatePassword');
	// echo form_password('oldPassword',set_value('oldPassword'),"required");
	// echo form_password('newPassword','',"id='pass1' required");
	// echo form_password('passconf','',"id='pass2' required oninput='checkPassword();'");
	// echo form_submit('submit', 'Change Password');
	// echo form_close();
?>	
</body>

</html>

