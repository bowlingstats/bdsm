<?
/*
Bowling Database/Statistics Management
Copyright (C) 2006 Jason Francis and BD/SM developement team

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*/

include('./header.php');// gets us our connection to the database;
session_start ();//grab info from cookie
if($_SESSION['a'] > 0){
	if($_POST['insert'] == "Save Records") insertDb();
	if($_POST['edit'] == "Save Changes" && $_POST['game_id'] && $_SESSION['a'] == 2) updateDb();
	if($_POST['delete'] == "Delete Game" && $_POST['game_id']) deleteDb();
} else {
	print "You must be logged in to use this page.";
}

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
	
	if($_POST["location"]) $location = $_POST['location'];
	else $location = "Not Specified";
	
	//find the current max of game_id and add 1 to it.
	$query = "SELECT MAX(game_id) FROM games";
	$result = mysql_query($query);
	list($game_id) = mysql_fetch_array($result);
	$game_id++;
	
	foreach($players as $key=>$value){
		$query = "SELECT uid FROM users WHERE username = '$value'";
		$result = mysql_query($query);
		list($uid) = mysql_fetch_array($result);
		
		$query = "INSERT INTO games (game_id, player_id, score, location, date) VALUES('$game_id', '$uid', '".$_POST[$value.'score']."', '$location', NOW())\n";
		
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
	header("Location: result.php?game_id=$game_id"); /* Redirect browser */
	
	print"Games successfully inserted into the database.";
}

function updateDb(){
	$game_id = $_POST['game_id'];
	$date = $_POST['year']."-".$_POST['month']."-".$_POST['day'];
	$location = $_POST['location'];
	if($_POST["player1"]) $players[0] = $_POST['player1'];
	if($_POST["player2"]) $players[1] = $_POST['player2'];
	if($_POST["player3"]) $players[2] = $_POST['player3'];
	if($_POST["player4"]) $players[3] = $_POST['player4'];
	if($_POST["player5"]) $players[4] = $_POST['player5'];
	if($_POST["player6"]) $players[5] = $_POST['player6'];
	if($_POST["player7"]) $players[6] = $_POST['player7'];
	if($_POST["player8"]) $players[7] = $_POST['player8'];
	
	foreach($players as $key=>$value){
		$id = $value;
		
		$q = "SELECT username FROM users WHERE uid = $value";
		$r = mysql_query($q);
		list($name) = mysql_fetch_array($r);
		for($f = 1; $f <=9; $f++){		
			if($_POST[$name."f".$f."b1"] == "X") $b1 = 10;
			else $b1 = $_POST[$name."f".$f."b1"];
			
			if($_POST[$name."f".$f."b2"] == "/") $b2 = 10 - $b1;
			elseif($_POST[$name."f".$f."b2"] == "") $b2 = 0;
			else $b2 = $_POST[$name."f".$f."b2"];
			
			$b3 = 0;
			
			$u = "UPDATE scores SET b1 = $b1, b2 = $b2, b3 = $b3 WHERE player_id = $id AND game_id = $game_id	AND frame = $f";
			$r = mysql_query($u);
		}
		if($_POST[$name."f10b1"] == "X") $b1 = 10;
			else $b1 = $_POST[$name."f10b1"];
		
		if($_POST[$name."f10b2"]== "X") $b2 = 10;
		elseif($_POST[$name."f10b2"]== "/") $b2 = 10 - $b1;
		else $b2 = $_POST[$name."f10b2"];
		
		if($_POST[$name."f10b3"] == "X") $b3 = 10;
		elseif($_POST[$name."f10b3"] == "/") $b3 = 10 - $b2;
		else $b3 = $_POST[$name."f10b3"];
		
		$u = "UPDATE scores SET b1 = $b1, b2 = $b2, b3 = $b3 WHERE player_id = $id AND game_id = $game_id	AND frame = $f";
		$r = mysql_query($u);
		
		$u = "UPDATE games SET score = '".$_POST[$name."score"]."', location = '$location', date = '$date' WHERE player_id = $id AND game_id = $game_id";
		$r = mysql_query($u);
	}
	header("Location: result.php?game_id=$game_id"); /* Redirect browser */
}

function deleteDb(){
	$gid = $_POST['game_id'];
	
	$q = "DELETE FROM games WHERE game_id = $gid";
	$r = mysql_query($q) or exit("Query \"$q\" failed.");
	
	$q = "DELETE FROM scores WHERE game_id = $gid";
	$r = mysql_query($q) or exit("Query \"$q\" failed.");
	
	$q = "DELETE FROM pinfall WHERE game_id = $gid";
	$r = mysql_query($q) or exit("Query \"$q\" failed.");
	
	header("Location: index.php"); /* Redirect browser */
}
?>
