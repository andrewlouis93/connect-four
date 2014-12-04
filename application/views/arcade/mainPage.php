
<!DOCTYPE html>

<html>
	
	<head>

	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/template.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>

	<script src="http://code.jquery.com/jquery-latest.js"></script>

	<script>
		$(function(){

			setInterval(function(){
					$('#availableUsers').load('<?= base_url() ?>arcade/getAvailableUsers');
					$.getJSON('<?= base_url() ?>arcade/getInvitation',function(data, text, jqZHR){
							if (data && data.invited) {
								var user=data.login;
								var time=data.time;
								if(confirm('Play ' + user)) 
									$.getJSON('<?= base_url() ?>arcade/acceptInvitation',function(data, text, jqZHR){
										if (data && data.status == 'success')
											window.location.href = '<?= base_url() ?>board/index'
									});
								else  
									$.post("<?= base_url() ?>arcade/declineInvitation");
							}
						});
			},500);

		});
	</script>
	</head> 
<body>  




	
<?php 
	
	echo $header;

	if (isset($errmsg)) 
		echo "<p>$errmsg</p>";
?>
		<h2 id="available-users-title">Available Users</h2>
		<div id="availableUsers">
		</div>
	
	
	
</body>

</html>

