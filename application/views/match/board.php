
<!DOCTYPE html>
<html>
	<head>
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/template.css">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
	<script>

		var otherUser = "<?= $otherUser->login ?>";
		var playerType = "<?= $playerType ?>";
		var user = "<?= $user->login ?>";
		var status = "<?= $status ?>";
		var gameLoop;

		var turn;
		if (playerType == "invitee"){
			turn = false;
		}
		else{
			turn = true;
		}

		// Game State indexes into the connection divs
		// 0 => Empty
		// 1 => Player 1 (Initiator Player)
		// 2 => Player 2 (Invited Player)
		function playerTypeToState(pType){
			if (pType == "inviter"){
				return 1;
			}
			else if (pType == "invitee"){
				return 2;
			}
		}

		// SIGHHHH, player2 or player1 according to the match_status table
		function getPlayerNum(){
			var flip = playerTypeToState(playerType);

			if (flip == 1){
				return 2;
			}

			if (flip == 2){
				return 1;
			}
		}


		// Translate playerNum to a status
		// in the match_status table
		function matchStatusPlayer(){
			if (getPlayerNum() == 1){
				return 2;
			}
			else if (getPlayerNum() == 2){
				return 3;
			}
		}

		function stopGame(){
			clearInterval(gameLoop);
		}

		// state => tie, lost, win
		function updateStatus(str, state){
			$("#status").text(str).addClass(state);
		}

		var gameState = new Array();
		for (var i = 0; i < 42; i++){
			gameState[i] = 0;
		}

		// IMPORTANT TO PRUNE OUT THE 
		// STATE ENTRIES '42' and '43'
		// IN THE CHECKWIN FUNCTIONALITY!

		// 42nd slot is for the turn index
		gameState[42] = 1;

		// 43rd slot is for the match_status
		// Follows ids in the match_status table
		gameState[43] = 1;

		function switchGameState(){
			if (gameState[42] == 1){
				gameState[42] = 2;
			}
			else if (gameState[42] == 2){
				gameState[42] = 1;
			}
		}

		function isMyTurn(){
			if (gameState[42] == playerTypeToState(playerType)){
				return true;
			}
			else{
				return false;
			}
		}

		// Find first open position in a single column
		function firstOpenColumn(one_d){
			var two_d = index_1D_to_2D(one_d);
			var col = two_d[0];
			for (var i = 6; i >= 0; i--){
				// check if the column has an open spot, if so return position
				var pos = index_2D_to_1D(col, i);

				if (gameState[pos] == 0){
					// first open spot
					return pos;
				}
			}
			return -1;
		}

		$(document).on('mouseenter','.connection', function(){
			
			if (isMyTurn()){
				var pos = $(this).attr('pos');
				var to_drop = firstOpenColumn(pos);
				$(".connection[pos='" + to_drop + "']").addClass("connection_available");				
			}

		}).on('mouseleave','.connection',function(){
			$(".connection").removeClass("connection_available");
		}).on('click','.connection', function(){
			var pos = $(this).attr('pos');
			var to_drop = firstOpenColumn(pos);		

			var droppedElement =	$(".connection[pos='" + to_drop + "']").addClass("connection_available");	
			droppedElement.addClass(playerType);

			to_drop = parseFloat(droppedElement.attr('pos'));
			gameState[to_drop] = playerTypeToState(playerType);
			switchGameState();	


			var untakenConnections = $(".connection").not(".invitee").not(".inviter").length;

	    	if (isWinnerAt(to_drop)){
	    		// Your last move made you the winner!

	    		// Match playerType from match table to 
	    		// a valid `match_status` state	    
	    		gameState[43] = matchStatusPlayer();
	    		updateStatus("You've beat <?= $otherUser->login ?>!", "win");		    		
	    	}
	    	else if (untakenConnections == 0){
	    		// Nobody won, but the last move created a tie!
	    		updateStatus("The game is a tie!", "tie");
	    		gameState[43] = 4;
	    	}		
	    	

			$.post('<?= base_url() ?>board/postBoardState',
			    {'board_state': gameState}, 
			    function(data){
			    	//toggleTurn();

			    	if (gameState[43] != 1){
			    		stopGame();
			    	}

			    }
			);	    		

			$(".connection").removeClass("connection_available");
		});


		// Check if the last move created a winner:

		// Since we are storing our game state in a array of size 42 
		// (43 if including the turn state, appended at the end)


		// Using the fact that our board has 7 columns and 6 rows

		// Transforms a 2-dimensional index into the Connect4 board
		// to it's corresponding position in our 1-dimensional
		// gameState array.
		function index_2D_to_1D(col, row){
			return (row * 7 + col);
		}


		// The reverse of the above
		function index_1D_to_2D(x){

			var _x = x % 7;    // % is the "modulo operator", the remainder of i / width;
			var _y = Math.floor(x / 7); 

			return[_x,_y];
		}

		// Checks if the chip we just inserted caused a win
		// x=> column
		// y=> row
		function isLinearMatch(x, y, stepX, stepY){
			var startVal = gameState[(index_2D_to_1D(x,y))];

			console.log("startVal: ", startVal);

			for (var i = 0; i < 4; i++){
				if (gameState[ index_2D_to_1D( x+i*stepX , y + i * stepY ) ] != startVal){
					// Game continues

					//console.log("gameState idx: ", index_2D_to_1D( x+i*stepX , y + i * stepY ));
					//console.log('no winner');
					return false;
				}
			}

			//alert("SOMEONE JUST WON THE GAME!");
			return true;
		}	

		function isWinnerAt(_x){
			var coordinates = index_1D_to_2D(_x);
			var x = coordinates[0];
			var y = coordinates[1];
			
			var horiz_right = isLinearMatch(x, y, 1,  0);
			var horiz_left = isLinearMatch(x, y, -1,  0);
			var vert_down = isLinearMatch(x, y, 0,  1);
			var vert_up = isLinearMatch(x, y, 0,  -1);
			var diag_down = isLinearMatch(x, y, 1,  1);
			var diag_up = isLinearMatch(x, y, 1, -1);
		
	
			if (horiz_left || horiz_right || vert_up || vert_down || diag_up || diag_down){
				return true;
			}
			else{
				return false;
			}
		}

		$( window ).resize(function() {
		  		$('body').css('zoom',$(window).width()/screen.width);
		});

		$(function(){
			
			$('body').css('zoom',$(window).width()/screen.width);			

			gameLoop = setInterval(function(){


					if (status == 'waiting') {
						$.getJSON('<?= base_url() ?>arcade/checkInvitation',function(data, text, jqZHR){
								if (data && data.status=='rejected') {
									alert("Sorry, your invitation to play was declined!");
									window.location.href = '<?= base_url() ?>arcade/index';
								}
								if (data && data.status=='accepted') {
									status = 'playing';
									//$('#status').html('Playing ' + otherUser);
								}
								
						});
					}

					$.get("<?= base_url() ?>board/getBoardState", function(data){
						// Update the board here!
						var pkg = $.parseJSON(data);
						var board = pkg.status;
						board = board.map(function(x){
								return parseFloat(x);
							});
						gameState = board;

						for (var i = 0; i < board.length; i++){
							if ( (board[i]) == 1){
								$(".connection[pos="+ i +"]").addClass("inviter");
							}
							else if ( (board[i]) == 2){
								$(".connection[pos="+ i +"]").addClass("invitee");
							}
						}

						// CHECK FOR GAME STATE FIRST!
						if (gameState[43] != 1){
							if (gameState[43] == 4){
								updateStatus("The game is a tie!","tie");
							}
							else{
								updateStatus("<?= $otherUser->login ?> has won the game!","lost");
							}
							stopGame();
						}


						// Checks updated gameState and switches states.
						if (isMyTurn() == false){

							$("#status").text(function(){
								return $(this).text().replace("Playing", "Waiting for");
							});
						}
						else{

							$("#status").text(function(){
								return $(this).text().replace("Waiting for", "Playing");
							});								
						}

					});

			},2000);

		});
	
	</script>
	</head> 
<body>  

	<?php
		echo $header;
	?>

	<div id='status'> 
		<?php 
			if ($playerType == "inviter")
				echo "Playing " . $otherUser->login;
			else
				echo "Waiting for " . $otherUser->login;
		?>
	</div>
	

	<div id="my_chip">
	</div>

	<div class="connect4">
		<?php
			for ($i = 0; $i < 42; $i++) {
    			echo "<div pos=".$i." class='connection'></div>";
			}
		?>
	</div>

<?php 
	
	// echo form_textarea('conversation');
	
	// echo form_open();
	// echo form_input('msg');
	// echo form_submit('Send','Send');
	// echo form_close();
	
?>
	
	
	
	
</body>

</html>

