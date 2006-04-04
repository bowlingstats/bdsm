<?
include('./header.php');// gets us our connection to the database;

if($_POST['insert'] == "Save Records") insertDb();

function insertDb(){
	//assign all the players to an array, and then use that array to process each players game
	if($_POST["player1"]) $players[0] = $_POST['player1'];
	if($_POST["player2"]) $players[1] = $_POST['player2'];
	if($_POST["player3"]) $players[2] = $_POST['player3'];
	if($_POST["player4"]) $players[3] = $_POST['player4'];
	if($_POST["player5"]) $players[4] = $_POST['player5'];
	if($_POST["player6"]) $players[5] = $_POST['player6'];
	if($_POST["player7"]) $players[6] = $_POST['player7'];
	if($_POST["player8"]) $players[7] = $_POST['player8'];
	
	//find the current max of game_id and add 1 to it.
	$query = "SELECT MAX(game_id) FROM games";
	$result = mysql_query($query);
	list($game_id) = mysql_fetch_array($result);
	$game_id++;
	
	foreach($players as $key=>$value){
		$query = "SELECT uid FROM users WHERE username = '$value'";
		$result = mysql_query($query);
		list($uid) = mysql_fetch_array($result);
		
		$query = "INSERT INTO games (game_id, player_id) VALUES('$game_id', '$uid')\n";
		$result = mysql_query($query);
		
		$query = "INSERT INTO scores (game_id, player_id, frame, b1, b2, b3) VALUES";
		
		for($f = 1; $f <= 9; $f++){
			if($_POST[$value."f".$f."b1"] == "X") $b1 = 10;
			else $b1 = $_POST[$value."f".$f."b1"];
			
			if($_POST[$value."f".$f."b2"] == "/") $b2 = 10 - $b1;
			elseif($_POST[$value."f".$f."b2"] == "") $b2 = 0;
			else $b2 = $_POST[$value."f".$f."b2"];
			
			$b3 = 0;
			$query .= "($game_id, $uid, $f, $b1, $b2, $b3), ";
		}
		if($_POST[$value."f10b1"] == "X") $b1 = 10;
			else $b1 = $_POST[$value."f10b1"];
		
		if($_POST[$value."f10b2"]== "X") $b2 = 10;
		elseif($_POST[$value."f10b2"]== "/") $b2 = 10 - $b1;
		else $b2 = $_POST[$value."f10b2"];
		
		if($_POST[$value."f10b3"] == "X") $b3 = 10;
		elseif($_POST[$value."f10b3"] == "/") $b3 = 10 - $b2;
		else $b3 = $_POST[$value."f10b3"];
		
		$query .= "($game_id, $uid, $f, $b1, $b2, $b3)";
		$result = mysql_query($query);
	}
	print"Games successfully inserted into the database.";
}
?>