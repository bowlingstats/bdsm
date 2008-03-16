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
	if($_POST['edit'] == "Save Changes" && is_numeric($_POST['game_id']) && $_SESSION['a'] == 2) updateDb();
	if($_POST['delete'] == "Delete Game" && is_numeric($_POST['game_id'])) deleteDb();
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
	
	if($_POST["location"]) $location = mysql_real_escape_string($_POST['location']);
	else $location = "Not Specified";
	
	//determine if we are tracking pin fall or not.
	//$trackPinfall is used to tell the games table if this game has pinfall recorded in then pinfall table
	if($_POST['pins']) $trackPinfall = 1;
	else $trackPinfall = 0;
	
	//find the current max of game_id and add 1 to it.
	$query = "SELECT MAX(game_id) FROM games";
	$result = mysql_query($query);
	list($game_id) = mysql_fetch_array($result);
	$game_id++;
	
	foreach($players as $key=>$value){
		//make sure the values from the form are safe to put in the database.
		$value = mysql_real_escape_string($value);
		$score = mysql_real_escape_string($_POST[$value.'score']);
		
		
		//get the user's uid.
		$query = "SELECT uid FROM users WHERE username = '$value'";
		$result = mysql_query($query);
		list($uid) = mysql_fetch_array($result);
		
		//Insert the game into the games table.
		$query = "INSERT INTO games (game_id, player_id, score, location, track_pins, date) VALUES('$game_id', '$uid', '".$score."', '$location', '$trackPinfall',NOW())\n";
		$result = mysql_query($query);

		//Insert scores into the scores table.
		$query = "INSERT INTO scores (game_id, player_id, frame, b1, b2, b3) VALUES";
		//Insert pinfall into the pinfall table.
		$pinQuery = "INSERT INTO pinfall (game_id, player_id, rack, pin1, pin2, pin3, pin4, pin5, pin6, pin7, pin8, pin9, pin10) VALUES ";
		
		//do the first 9 regular frames.
		for($f = 1; $f <= 9; $f++){
			//first work the scores entered in the text boxes.
			if($_POST[$value."f".$f."b1"] == "X") $b1 = 10;
			else $b1 = $_POST[$value."f".$f."b1"];
			
			if($_POST[$value."f".$f."b2"] == "/") $b2 = 10 - $b1;
			elseif($_POST[$value."f".$f."b2"] == "") $b2 = 0;
			else $b2 = $_POST[$value."f".$f."b2"];
			
			$b3 = 0;
			
			//make the ball values safe to insert into the database
			$b1 = mysql_real_escape_string($b1);
			$b2 = mysql_real_escape_string($b2);
			
			$query .= "('$game_id', '$uid', '$f', '$b1', '$b2', '$b3'), ";
			
			//next work the pinfall information, if applicable
			if($trackPinfall){
				$pinQuery .= " ($game_id, $uid, $f, ";//finish this with the pinfall.
				for ($p = 1; $p <= 10; $p++){
					$pinQuery .= "\"".mysql_real_escape_string($_POST[$value."f".$f."p".$p])."\"";
					if($p != 10) $pinQuery .= ", ";
					else $pinQuery .= "), ";
				}
			}
		}
		//now tack on the special case of the 10th frame.
		if($_POST[$value."f10b1"] == "X") $b1 = 10;
			else $b1 = $_POST[$value."f10b1"];
		
		if($_POST[$value."f10b2"]== "X") $b2 = 10;
		elseif($_POST[$value."f10b2"]== "/") $b2 = 10 - $b1;
		else $b2 = $_POST[$value."f10b2"];
		
		if($_POST[$value."f10b3"] == "X") $b3 = 10;
		elseif($_POST[$value."f10b3"] == "/") $b3 = 10 - $b2;
		else $b3 = $_POST[$value."f10b3"];
		
		//make the ball values safe to insert into the database
		$b1 = mysql_real_escape_string($b1);
		$b2 = mysql_real_escape_string($b2);
		$b3 = mysql_real_escape_string($b3);
		
		//insert the scores into the games table.
		$query .= "('$game_id', '$uid', '$f', '$b1', '$b2', '$b3')";
		$result = mysql_query($query);
		
		//next work on the special extra ball in the 10th frame for pinfall
		if($trackPinfall){
		$pinQuery .= " ($game_id, $uid, 10, ";
		for ($p = 1; $p <= 10; $p++){
			$pinQuery .= "\"".mysql_real_escape_string($_POST[$value."f10p".$p])."\"";
			if($p != 10) $pinQuery .= ", ";
			else $pinQuery .= "), ";
		}
		
		
		$pinQuery .= " ($game_id, $uid, 11, ";
		for ($p = 1; $p <= 10; $p++){
			$pinQuery .= "\"".mysql_real_escape_string($_POST[$value."f11p".$p])."\"";
			if($p != 10) $pinQuery .= ", ";
			else $pinQuery .= "), ";
		}
		
		$pinQuery .= " ($game_id, $uid, 12, ";
		for ($p = 1; $p <= 10; $p++){
			$pinQuery .= "\"".mysql_real_escape_string($_POST[$value."f12p".$p])."\"";
			if($p != 10) $pinQuery .= ", ";
			else $pinQuery .= ")";
		}
		
		$pinResult = mysql_query($pinQuery) or die(mysql_error());
}

	}
	header("Location: result.php?game_id=$game_id"); /* Redirect browser */
	
}

function updateDb(){
	if(is_numeric($_POST['game_id'])) $game_id = $_POST['game_id'];
	else die("Invalid game_id.");
	
	//strtotime creates a unix timestamp or returns false.  This will clean up the user provided date, avoiding SQL injections.
	$date = strtotime($_POST['year']."-".$_POST['month']."-".$_POST['day']." ".$_POST['hour'].":".$_POST['minute'].$_POST['meridiem']);
	if($date) $date = date("c", $date);
	else die("There was a problem with the date you provided.");
	
	$location = mysql_real_escape_string($_POST['location']);
	
	//for some reason I don't remember gameedit.php uses numbers for the players, rather than usernames.
	if($_POST["player1"]) $players[0] = $_POST['player1'];
	if($_POST["player2"]) $players[1] = $_POST['player2'];
	if($_POST["player3"]) $players[2] = $_POST['player3'];
	if($_POST["player4"]) $players[3] = $_POST['player4'];
	if($_POST["player5"]) $players[4] = $_POST['player5'];
	if($_POST["player6"]) $players[5] = $_POST['player6'];
	if($_POST["player7"]) $players[6] = $_POST['player7'];
	if($_POST["player8"]) $players[7] = $_POST['player8'];
	
	foreach($players as $key=>$value){
		if(is_numeric($value)) $id = $value;
		else die("There is a problem with player1: $value, it must be a number.");
		
		//get the username from the users table.
		$q = "SELECT username FROM users WHERE uid = $value";
		$r = mysql_query($q);
		list($name) = mysql_fetch_array($r);
		
		//update the first 9 frames
		for($f = 1; $f <=9; $f++){		
			if($_POST[$name."f".$f."b1"] == "X") $b1 = 10;
			else $b1 = $_POST[$name."f".$f."b1"];
			
			if($_POST[$name."f".$f."b2"] == "/") $b2 = 10 - $b1;
			elseif($_POST[$name."f".$f."b2"] == "") $b2 = 0;
			else $b2 = $_POST[$name."f".$f."b2"];
			
			$b3 = 0;
			
			//now make $b1 and $b2 safe to insert into the database.
			$b1 = mysql_real_escape_string($b1);
			$b2 = mysql_real_escape_string($b2);
			
			$u = "UPDATE scores SET b1 = $b1, b2 = $b2, b3 = $b3 WHERE player_id = $id AND game_id = $game_id	AND frame = $f";
			$r = mysql_query($u);
		}
		
		//now update the special 10th frame.
		if($_POST[$name."f10b1"] == "X") $b1 = 10;
			else $b1 = $_POST[$name."f10b1"];
		
		if($_POST[$name."f10b2"]== "X") $b2 = 10;
		elseif($_POST[$name."f10b2"]== "/") $b2 = 10 - $b1;
		else $b2 = $_POST[$name."f10b2"];
		
		if($_POST[$name."f10b3"] == "X") $b3 = 10;
		elseif($_POST[$name."f10b3"] == "/") $b3 = 10 - $b2;
		else $b3 = $_POST[$name."f10b3"];
		
		//now make $b1, $b2, and $b3 safe to insert into the database.
		$b1 = mysql_real_escape_string($b1);
		$b2 = mysql_real_escape_string($b2);
		$b3 = mysql_real_escape_string($b3);
		
		$u = "UPDATE scores SET b1 = $b1, b2 = $b2, b3 = $b3 WHERE player_id = $id AND game_id = $game_id	AND frame = $f";
		$r = mysql_query($u);
		
		
		//make the score safe to insert in the database
		$score = mysql_real_escape_string($_POST[$name.'score']);
		
		//and update the score, location, and date of the game.
		$u = "UPDATE games SET score = '".$score."', location = '$location', date = '$date' WHERE player_id = $id AND game_id = $game_id";
		$r = mysql_query($u);

		//determine if we are tracking pin fall or not.
		//$trackPinfall is used to tell the games table if this game has pinfall recorded in then pinfall table
		if($_POST['pins']) $trackPinfall = 1;
		else $trackPinfall = 0;
		
		//now do pinfall, there's 12 racks of ten pins each.
		if($trackPinfall){
			for($rack = 1; $rack <= 12; $rack++){
				$pinfallQuery = "UPDATE pinfall SET ";
				for($pin = 1; $pin <= 10; $pin++){
					$count = $_POST[$name."f".$rack."p".$pin];
					if(is_numeric($count) || $count == "") $pinfallQuery .= "pin$pin = \"".$count."\"";
					else die("Problem with rack $rack, pin $pin.");
				
					if($pin != 10) $pinfallQuery .= ", ";
				}
				$pinfallQuery .= " WHERE player_id = $id AND game_id = $game_id AND rack = $rack";
				$pinResult = mysql_query($pinfallQuery);
			}
		}
		
	}
	header("Location: result.php?game_id=$game_id"); /* Redirect browser */
}

function deleteDb(){
	if(is_numeric($_POST['game_id'])) $gid = $_POST['game_id'];
	else die("There was a problem with the game_id: $_POST[game_id], it must be numeric.");
	
	$q = "DELETE FROM games WHERE game_id = $gid";
	$r = mysql_query($q) or exit("Query \"$q\" failed.");
	
	$q = "DELETE FROM scores WHERE game_id = $gid";
	$r = mysql_query($q) or exit("Query \"$q\" failed.");
	
	$q = "DELETE FROM pinfall WHERE game_id = $gid";
	$r = mysql_query($q) or exit("Query \"$q\" failed.");
	
	header("Location: index.php"); /* Redirect browser */
}
?>
