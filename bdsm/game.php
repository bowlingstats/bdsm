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

include('./header.php');//connects to database, provides links by user's admin level.
include('./functions.php');//get the helper functions that game, result, and gameedit share.
session_start ();//grab info from cookie

?>
<html>
<head>
<title>Bowling Database/Statistics Management: New Game</title>
<link rel="stylesheet" type="text/css" href="bdsm.css">
<script type="text/javascript" src="scoring.js">
</script>
</head>
<body>
<?
//draw links heading
if($_SESSION['a'] == 2){
	admin();
} elseif($_SESSION['a'] == 1){
	user();
}else{
	player();
}

//Check if a user is authenticated.
//if admin or user they can add a game. else they must log in.
if($_SESSION['a'] == 2 || $_SESSION['a'] == 1){
	if($_GET['numPlayers']){
		$numPlayers = $_GET['numPlayers'];
		choosePlayers($numPlayers);//if using GET we have the number of players call choosePlayers() to setup the game.
	}elseif($_POST['numPlayers']){
		$numPlayers = $_POST['numPlayers'];//if using POST we have the number of players call choosePlayers() to setup the game.
		choosePlayers($numPlayers);
	}elseif(!$_POST['player1']){
		getPlayers();//default case is to call getPlayers() to find the number of players for this game
	}
	
	if($_POST['player1']) createGames();//if player1 is set, we can call createGames() to draw the scorecard.
} else{
	//deal with users who are not logged in.
	print "You must be logged in to use this page.";
}

/*getPlayers() creates an HTML selector box to establish the number of players in this game.*/
function getPlayers(){
?>
	<center><h1>Add A Game</h1></center>
	<p>
	<form action="game.php" method="post">
	Please select the number of players: <select name="numPlayers">
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
	</select>
	<input type="submit">
	</form>
<?
}//end of getPlayers()

/*choosePlayers() generates and HTML form that selects the players, and the location they played at.
$num is the number of players in this game.  It creates a selector box for each player.
It verifies these form values with javascript, to ensure not duplicate players, before submitting.*/
function choosePlayers($num){
?>
	
	<center><h1>Add A Game</h1></center>
<?
	print "<p/><form action=\"game.php\" method=\"post\" onSubmit=\"return val();\">\n";
	//create a list of each players $out is the <options> for each selector box
	$query = "SELECT name, username FROM users ORDER BY name ASC";
	$result = mysql_query($query);
	while($nameArray = mysql_fetch_array($result)){
		list($name, $username) = $nameArray;
		$out .= "\t<option value=\"$username\">$name($username)</option>\n";
	}
	//for the number of players specified in $num, create a <select>, and fill it with the <option>s from $out
	for($x = 1; $x <= $num; $x++){
		print "Player $x: <select name=\"player$x\">\n";
		print "$out";
		print "</select>\n<br/>\n";
	}
	
	//This query creates a list of <option>s, one for each location listed games,
	//this <select> when set to custom calls the javascript function setCust(), allowing a custom location to be set.
	print "Location: <span id=\"location\"><select name=\"location\" id=\"location\" onClick=\"if(this.value == 'custom') setCust();\">\n";
	$query = "SELECT location, COUNT(location) AS num FROM games GROUP BY location ORDER BY num DESC";
	$result = mysql_query($query);
	while($locArray = mysql_fetch_array($result)){
		list($loc, $num) = $locArray;
		print "\t<option value=\"$loc\">$loc</option>\n";
	}
	print "\t<option value=\"custom\">Custom:</option>\n</select></span>\n";
	
	print "<br/>\n<input type=\"checkbox\" name=\"pins\" checked> Track pinfall for this game?\n<br/>\n";
	
	print "<input type=\"submit\"></form>\n";
}

/*createGames() create the actual HTML form where the scores are entered for each player in this game*/
function createGames(){
	//first create an array of players, but only create rows for how many players were specified in getPlayers()
	if($_POST["player1"]) $players[0] = $_POST['player1'];
	if($_POST["player2"]) $players[1] = $_POST['player2'];
	if($_POST["player3"]) $players[2] = $_POST['player3'];
	if($_POST["player4"]) $players[3] = $_POST['player4'];
	if($_POST["player5"]) $players[4] = $_POST['player5'];
	if($_POST["player6"]) $players[5] = $_POST['player6'];
	if($_POST["player7"]) $players[6] = $_POST['player7'];
	if($_POST["player8"]) $players[7] = $_POST['player8'];
	
	$location = $_POST["location"];
	
	print "<p/><center><font size=\"+2\">$location</font></center>\n";
	print "<p/>\n<form action=\"gamesubmit.php\" method=\"post\">\n";
	print "<input type=\"hidden\" name=\"location\" value=\"$location\">\n";
	//start the table that will contain the games.
	print "<table>\n";
	
	//first we're going to add frame numbers to the top of this table.
	print "\t<tr>\n";
	print "\t\t<th>&nbsp;</th>\n";
	for($x = 1; $x <= 10; $x++){
		print "\t\t<th>$x</th>\n";
	}
	print "\t</tr>\n";
	//also create an array $realNames with the same indexing as $players, use this to display names later.
	foreach($players as $key=>$value){
		$q = "SELECT name FROM users WHERE username = '$value'";
		$r = mysql_query($q);
		list($rn) = mysql_fetch_array($r);
		$realNames[$key] = $rn;
	}
	//for each player create an instance of the javascript class game.  This way javascript can score the game as it is entered by the user.
	foreach($players as $key=>$value){
		$realName = $realNames[$key];
?>
		<script type="text/javascript">
		<?echo $value?> = new Game();
		<?echo $value?>.name = "<?echo $value?>";
		</script>
<?
		//start the row of the table for this user, and invoke createGrid() to fill this row
		print "\t<tr>\n";
		createGrid($value, $realName, $key+1);
		print "\t</tr>\n";		
	}
	print "</table>\n";
	//the end of the table containing the entered games
	print "<input type=\"submit\" name=\"insert\" value=\"Save Records\">\n</form>\n";
	
	//print a key of what characters are valid entries.
	if(!$_POST['pins'])	print "<p>\n<table class=\"key\">\n<tr>\n<td class=\"keye\"><b>Score</b></td>\n<td class=\"keyo\"><b>Input</b></td>\n</tr><tr>\n<td class=\"keye\">0-9</td>\n<td class=\"keyo\">0-9</td>\n</tr><tr>\n<td class=\"keye\">/(spare)</td>\n<td class=\"keyo\">/</td>\n</tr><tr>\n<td class=\"keye\">X(strike)</td>\n<td class=\"keyo\">X, x, or *</td>\n</tr>\n</table>\n</p>\n";
	else print "<p>\n<table class=\"key\">\n\t<tr>\n\t\t<td class=\"keye\"><b>Key</b></td>\n\t\t<td class=\"keyo\"><b>Input</b></td>\n\n\t</tr>\n\t<tr>\n\t\t<td class=\"keye\">Hit by 1<sup>st</sup> ball</td>\n\t\t<td class=\"keyo\"><img src=\"images/down.png\"></td>\n\t</tr>\n\t<tr>\n\t\t<td class=\"keye\">Missed by 1<sup>st</sup> ball</td>\n\t\t<td class=\"keyo\"><img src=\"images/ball1.png\"></td>\n\t</tr>\n\t<tr>\n\t\t<td class=\"keye\">Missed by 2<sup>nd</sup> ball</td>\n\t\t<td class=\"keyo\"><img src=\"images/ball2.png\"></td>\n\t</tr>  </table>\n</p>\n";
}

/*createGrid() generates each set of 10 frames to be entered.
$name is the username, which is used to invoke their javascript instance of the game class
$realName is used to display the users name on the score sheet
$playerNum keeps the players in order by number*/
function createGrid($name, $realName, $playerNum){
	//first establish if we'll be tracking pinfall
	if($_POST['pins']) $trackPinfall = 1;
	else $trackPinfall = 0;
	print "\t\t<input type=\"hidden\" name=\"pins\" value=\"$trackPinfall\">\n";
	
	print "\t\t<td class=\"name\">$realName <input type=\"hidden\" name=\"player$playerNum\" value=\"$name\"></td>\n";
	//The first 9 frames will all be the same.
	for($frame = 1; $frame < 10; $frame++){
?>
		<td class="frame">
			<table>
				<tr>
					<td class="ball1"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b1"?>" name="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value="" <?if($trackPinfall) print "readonly"?>></td>
					<td class="ball2"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b2"?>" name="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value ="" onFocus="<?echo $name?>.validate('<?echo $frame?>');" onChange="<?echo $name?>.validate('<?echo $frame?>'); <?echo $name?>.scoreGame();" <?if($trackPinfall) print "readonly"?>></td>
	<?				// class ball2 gets a border.                                                  turn off anoying autocomplete| id needs to be in the usernamef5b1 format                           name is the same as id                                                                                                                                                                  when leaving b1, validate that it is OK                                                                                 when leaving b2 validate the frame again, and score the game?>
				</tr>
				<tr><?//this is where the scores get dropped by javascript's scoreGame()?>
					<td id="<?print $name."score".$frame?>" class="score" colspan='2'>&nbsp;</td>
				</tr>
<?
		if($trackPinfall){
?>
				<tr>
					<td id="<?print $name."pins".$frame?>" class="pins" colspan='2'>
						<?drawPins($name, $frame, array(0,0,0,0,0,0,0,0,0,0), 1);//for frames 1-9 the frame is the number of the rack?>
					</td>
				</tr>
<?
		}//end of if($pinfall == 1)
?>
			</table>
		</td>
<?
	}
	//the 10th frame is a special case having 3 balls.
?>
	<td class="frame">
		<table>
			<tr>
				<td class="ball1"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b1"?>" name="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value="" <?if($trackPinfall) print "readonly"?>></td>
				<td class="ball2"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b2"?>" name="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value="" onFocus="<?echo $name?>.validate('<?echo $frame?>');" onChange="<?echo $name?>.validate('10'); <?echo $name?>.scoreGame();" <?if($trackPinfall) print "readonly"?>></td>
				<td class="ball3"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b3"?>" name="<?print $name."f".$frame."b3"?>" maxlength="1" type="text" value="" onFocus="<?echo $name?>.validate('<?echo $frame?>');" onChange="<?echo $name?>.validate('10'); <?echo $name?>.scoreGame();" <?if($trackPinfall) print "readonly"?>></td>
				<td class="placeholder"></td><!--a placeholder-->
			</tr>
			<tr>
				<td id="<?print $name."score".$frame?>" class="score" colspan='3'>&nbsp;</td>
				<input type="hidden" name="<?echo $name?>score" id="<?echo $name?>score">
<?			//we hid the score javascript got here?>
			</tr>
<?
	$frame = 10;
	if($trackPinfall){
?>
			<tr>
				<td id="<?print $name."pins".$frame?>" class="pins" colspan='2'>
<?
				//draw rack 10
				drawPins($name, 10, array(0,0,0,0,0,0,0,0,0,0), 1);//for frames 1-9 the frame is the number of the rack
	}
	//now make the specail 11th rack
	$frame = 11;
	if($trackPinfall){
?>
				</td>
				<td id="<?print $name."pins".$frame?>" class="pins" colspan='2' style="visibility: hidden;">
<?
		//draw rack 11
		drawPins($name, 11, array(0,0,0,0,0,0,0,0,0,0), 1);//for frames 1-9 the frame is the number of the rack
	}
?>
				</td>
<?
	//now make the specail 12th rack
	$frame = 12;
	if($trackPinfall){
?>
				<td id="<?print $name."pins".$frame?>" class="pins" colspan='2' style="visibility: hidden;">
<?
		//draw rack 12
		drawPins($name, 12, array(0,0,0,0,0,0,0,0,0,0), 1);//for frames 1-9 the frame is the number of the rack
		
	}
?>
				</td>
			</tr>
		</table>
	</td>
	
<?
}

include('./footer.php');
?>
