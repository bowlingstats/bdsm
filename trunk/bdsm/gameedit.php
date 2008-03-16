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

include('./header.php');
include('./functions.php');
session_start ();//grab info from cookie
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="bdsm.css">
<script type="text/javascript" src="scoring.js">
</script>
<script type="text/javascript">
</script>

</head>
<body>
<?
if($_SESSION['a'] == 2){//only  admins may edit games.
	//deal with GET and POST or no game_id's
	if(is_numeric($_GET['game_id'])){
		$game_id = $_GET['game_id'];
		admin();
		drawGame();
	}else if(is_numeric($_POST['game_id'])) {
		$game_id = $_POST['game_id'];
		admin();
		drawGame();
	} else {
		print "No game_id set, nothing to display.";
	}
} else{//deal with users who are not logged in or have insufficent privilages
	if($_SESSION['a'] > 0) user();
	else player();
	print "Only administrators may edit games.";
}
/*button creates a list of javascript commands for each player in this game, it calls the users javascript game class function scoreGame()*/
function button(){
	global $game_id;
	//query get the username and uid for each player in this game with game_id
	$query = "SELECT users.username, games.player_id FROM users, games WHERE games.game_id =".$game_id." AND users.uid = games.player_id";
	print "<pre>$query</pre>\n";
	$result = mysql_query($query);
	while($n = mysql_fetch_array($result)){
		list($name, $id) = $n;//get their username and their UID
		print "$name.scoreGame();";
	}
}

/*drawGame() creates a scorecard like the one from games.php, but has the scores that are in the database available to edit.*/
function drawGame(){
	global $game_id;
?>

<?
//create a centered heading
$q = "SELECT location, DATE_FORMAT(date, '%W, %M %D, %Y %l:%i%p') AS date, YEAR(date) AS year, MONTH(date) AS month, DAY(date) AS day, DATE_FORMAT(date, '%l') AS hour, DATE_FORMAT(date, '%i') AS minute, DATE_FORMAT(date, '%p') AS meridiem FROM games WHERE game_id = $game_id";
$r = mysql_query($q);
$row = mysql_fetch_array($r) or exit("No game with id $game_id.");//make sure the game really exists;
?>
<center>
<?print "<font size=\"+1\">".$row[date]."</font><br/><font size=\"+2\">Played at ".$row[location]."</font><br/>\n"?>
</center>

<form action="gamesubmit.php" method="post" onSubmit="return val()">
<?//make sure we have the game_id when we go to submit?>
<input type="hidden" name="game_id" value="<?echo $game_id?>">
<b>yyyy-mm-dd</b>: <input name="year" id="year" class="year" type="text" maxlength="4" value="<?echo $row[year]?>"> - <input type="text" name="month" id="month" class="month" maxlength= 2 value="<?echo $row[month]?>"> - <input type="text" name="day" id="day" class="day" maxlength="2" value="<?echo $row[day]?>">&nbsp;&nbsp;
<input name="hour" class="month" style="text-align: right;" type="text" maxlength="2" value="<?echo $row[hour]?>"> : <input name="minute" class="month" type="text" maxlength="2" value="<?echo $row[minute]?>">
<?//now churn out the AM/PM selector.
print "<select name=\"meridiem\">\n";
print "\t<option value=\"AM\"";
if($row[meridiem] == "AM") print " selected";
print ">am</option>\n";
print "\t<option value=\"PM\"";
if($row[meridiem] == "PM") print " selected";
print ">pm</option>\n";
print "</select>\n";
?>
<br/>
<?
print "<b>Location</b>: <span id=\"location\"><select name=\"location\" id=\"loc\" onClick=\"if(this.value == 'custom') setCust();\">\n";

//fill out the location <select>>
$query = "SELECT location, COUNT(location) AS num FROM games WHERE location != \"".$row[location]."\" GROUP BY location ORDER BY num DESC";
$result = mysql_query($query);
print "\t<option value=\"".$row[location]."\">".$row[location]."</option>\n";
while($locArray = mysql_fetch_array($result)){
	list($loc, $num) = $locArray;
	print "\t<option value=\"$loc\">$loc</option>\n";
}
print "\t<option value=\"custom\">Custom:</option>\n</select></span>\n";
?>
<table>
<?
//first we're going to add frame numbers to the top of this table.
print "\t<tr>\n";
print "\t\t<th>&nbsp;</th>\n";
for($x = 1; $x <= 10; $x++){
	print "\t\t<th>$x</th>\n";
}
print "\t</tr>\n";
?>

	<tr>

<?
$query = "SELECT users.username, games.player_id, users.name, games.track_pins FROM users, games WHERE games.game_id = $game_id AND users.uid = games.player_id";
$result = mysql_query($query);
$playerNum = 0;

while($n = mysql_fetch_array($result)){// this creates a row in the table for each player in games.game_id, much like createGrid() in game.php
	$playerNum++;
	list($name, $id, $realName, $trackPins) = $n;//get their name and their UID
	//get the results for each frame for this player from games
	$q = "SELECT frame, b1, b2, b3 FROM scores WHERE game_id = $game_id AND player_id = $id ORDER BY frame ASC";
	$r = mysql_query($q);
	?>
	<script type="text/javascript">
	<?echo $name?> = new Game();
	<?echo $name?>.name = "<?echo $name?>";
	</script>
	<?
	while($x = mysql_fetch_array($r)){//change the numeric values from the database into bowling symbols of X and /'s
		list($frame, $b1, $b2, $b3) = $x;
		if($frame != 10){
			if($b1 == 10 ){
				$b1 = "X";
				$b2 = "";
			}
			if($b1 + $b2 == 10) $b2 = "/";
		}else{
			if($b1 == 10) $b1 = "X";
			
			if(is_numeric($b1) && ($b1 + $b2) == 10) $b2 = "/";//because $b1 would be a string we don't have to test if it's a strike.
			
			if($b2 == 10) $b2 = "X";//if $b2 isn't a spare and $b2==10 it must be a strike.
			
			if(($b2 == "X" || $b2 == "/") && $b3 == 10) $b3 = "X";
			
			if(is_numeric($b2) && $b2 + $b3 == 10) $b3 = "/";//if $b2 was a mark it would be string so we don't have to check if the sum is 10 $b is a spare.
		}
		//hide the form player$playerNum for when we send the form to gamesubmit.php
		if($frame == 1) print "\t\t\t\t<td class=\"name\">$realName<input type=\"hidden\" name=\"player$playerNum\" value=\"$id\"></td>\n";
?>
		<td class="frame">
<?
		if($frame != 10){
?>
			<table>
				<tr>
					<td class="ball1"><input class="frame" autocomplete="off" name="<?print $name."f".$frame."b1"?>" id="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value="<?echo $b1?>" onChange="<?echo $name?>.validate('<?echo $frame?>');"></td>
					<td class="ball2"><input class="frame" autocomplete="off" name="<?print $name."f".$frame."b2"?>" id="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value ="<?echo $b2?>" onBlur="<?echo $name?>.validate('<?echo $frame?>'); <?echo $name?>.scoreGame();"></td>
				</tr>
				<tr>
					<td id="<?print $name."score".$frame?>" class="score" colspan='2'>&nbsp;</td>
				</tr>
<?
		if($trackPins){
			//get an array of pins For the first 9 frames $frame will match $rack
			$qp = "SELECT pin1, pin2, pin3, pin4, pin5, pin6, pin7, pin8, pin9, pin10 FROM pinfall WHERE game_id = $game_id AND player_id = $id AND rack = $frame";
			$rp = mysql_query($qp);
			$pins = mysql_fetch_row($rp);
?>
				<tr>
					<td class="pins" id="<?print $name."f".$frame."pins"?>" colspan="2">
						<?drawPins($name, $frame, $pins, 1)?>
					</td>
				</tr>
<?
		}
?>
			</table>
<?
		} else {//frame 10 is a speial case having 3 balls.
?>
			<table>
				<tr>
					<td class="ball1"><input class="frame" autocomplete="off" name="<?print $name."f".$frame."b1"?>" id="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value="<?echo $b1?>"></td>
					<td class="ball2"><input class="frame" autocomplete="off" name="<?print $name."f".$frame."b2"?>" id="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value="<?echo $b2?>" onFocus="<?echo $name?>.validate('<?echo $frame?>');" onChange="<?echo $name?>.validate('10'); <?echo $name?>.scoreGame();"></td>
					<td class="ball3"><input class="frame" autocomplete="off" name="<?print $name."f".$frame."b3"?>" id="<?print $name."f".$frame."b3"?>" maxlength="1" type="text" value="<?echo $b3?>" onFocus="<?echo $name?>.validate('<?echo $frame?>');" onChange="<?echo $name?>.validate('10'); <?echo $name?>.scoreGame();"></td>
					<td class="placeholder"></td><!--a placeholder-->
				</tr>
				<tr>
					<td id="<?print $name."score".$frame?>" class="score" colspan='3'>&nbsp;</td>
					<input type="hidden" name="<?echo $name?>score" id="<?echo $name?>score">
				</tr>
<?				
if($trackPins){
			//get an array of pins For the last 3 racks.
			$qp = "SELECT rack, pin1, pin2, pin3, pin4, pin5, pin6, pin7, pin8, pin9, pin10 FROM pinfall WHERE game_id = $game_id AND player_id = $id AND rack > 9 ORDER BY rack ASC";
			$rp = mysql_query($qp);
?>
				<tr>
<?
			while($rackDetails = mysql_fetch_array($rp)){
				$rack = $rackDetails[rack];
				//reset $pins
				unset($pins);
				for($n = 1; $n <= 10; $n++){//create the array of pin values for drawPins()
					$pins[] = $rackDetails["pin$n"];
				}
?>
					<td class="pins" id="<?print $name."pins".$rack?>" colspan="2" <?if($rack == "11" && ($b1 != "X" && $b2 != "/")) print "style=\"visibility: hidden;\""?><? if($rack == "12" && $b2 != "X")print "style=\"visibility: hidden;\""?> >
<?
				drawPins($name, $rack, $pins, 1); 
?>
					</td>
<?
			}
?>
				</tr>
<?
		}
?>
			</table>
<?
		}
?>
		</td>
<?
	}
?>
	</tr>
	
<?
	if($trackPins) print "<input type=\"hidden\" name=\"pins\" value=\"1\">\n";	
}

?>

</table>
<input type="submit" name="edit" value="Save Changes" onClick="<?button()?>return(confirm('Do you wish to commit these scores to the database?'));"> 
<input type="submit" name="delete" value="Delete Game" onClick="return(confirm('Do you wish to delete this game from the database?'));">
</form>

<?
}//ends drawGame()
?>
<script type="text/javascript">
<?button()?>
</script>

<?
include('./footer.php');
?>
