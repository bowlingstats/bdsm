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

if(is_numeric($_GET['game_id'])){
	$game_id = $_GET['game_id'];
	drawGame();
}elseif(is_numeric($_POST['game_id'])){
	$game_id = $_POST['game_id'];
	drawGame();
}

function button(){
	global $game_id;
	$query = "SELECT users.username, games.player_id FROM users, games WHERE games.game_id =".$game_id." AND users.uid = games.player_id";
	$result = mysql_query($query);
	while($n = mysql_fetch_array($result)){// this calls a javascript method to score the game for each player object.
		list($username, $id) = $n;//get their name and their UID
		print "$username.scoreGame();\n";
	}	
}

function drawGame(){

	global $game_id;
?>
<html>
<head>
<title>Displaying Game <?echo $game_id?></title>
<link rel="stylesheet" type="text/css" href="bdsm.css">
<script type="text/javascript" src="scoring.js">
</script>
</head>
<body>
<!--this is a kluge so this page will work in Opera-->
<div id="proto" style="visibility: hidden; z-index: -1;">&nbsp;/&nbsp;</div>
<?
//draw the header.
if($_SESSION['a'] == 2){
	admin();
} elseif($_SESSION['a'] == 1){
	user();
}else{
	player();
}

$q = "SELECT location, DATE_FORMAT(date, '%W, %M %D, %Y %l:%i%p') as date FROM games WHERE game_id = $game_id";
$r = mysql_query($q);
$row = mysql_fetch_array($r) or exit("No game with id $game_id.");//make sure the game really exists
?>
<center>
<?print "<font size=\"+1\">".$row[date]."</font><br/><font size=\"+2\">Played at ".$row[location]."</font><br/>\n"?>
<?if($_SESSION['a'] == 2) print "<a href=\"gameedit.php?game_id=$game_id\">edit this game</a>\n<p/>\n";?>
</center>
<table>
	<tr>
		<td class="head">&nbsp;</td>
<?
for($x = 1; $x <= 10; $x++){
	print "\t\t<th>$x</th>\n";
}
?>
	</tr>
	<tr>
<?
$query = "SELECT users.username, games.player_id, games.track_pins, users.name FROM users, games WHERE games.game_id = $game_id AND users.uid = games.player_id";
$result = mysql_query($query);

while($n = mysql_fetch_array($result)){// this creates as html table for each player in games.game_id
	$playerNum++;
	list($name, $id, $trackPins, $realName) = $n;//get their name and their UID
	//get the results for each player from games
	$q = "SELECT frame, b1, b2, b3 FROM scores WHERE game_id = $game_id AND player_id = $id ORDER BY frame ASC";
	$r = mysql_query($q);
	?>
	<script type="text/javascript">
	<?echo $name?> = new Game();
	<?echo $name?>.name = "<?echo $name?>";
	</script>
	<?
	while($x = mysql_fetch_array($r)){
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
		if($frame == 1) print "\t\t<td class=\"name\"><a href=\"usergames.php?uid=$id\">$realName</a></td>\n";
		?>
		<td class="frame">
		<?
		if($frame != 10){
		?>
			<table>
				<tr>
					<td class="ball1"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b1"?>" name="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value="<?echo $b1?>" readonly></td>
					<td class="ball2"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b2"?>" name="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value="<?echo $b2?>" readonly></td>
				</tr>
				<tr>
					<td id="<?print $name."score".$frame?>" class="score" colspan='2'>&nbsp;</td>
				</tr>
<?
		if($trackPins){
			//get an array of pins For the first 9 frames $frame will match $rack
			$qp = "SELECT pin1, pin2, pin3, pin4, pin5, pin6, pin7, pin8, pin9, pin10 FROM pinfall WHERE game_id = $game_id AND player_id = $id AND rack = $frame";
			$rp = mysql_query($qp);
			$pins = mysql_fetch_array($rp);
?>	
				<tr>
					<td class="pins" id="<?print $name."f".$frame."pins"?>" colspan="2">
						<?drawPins($name, $frame, $pins)?>
					</td>
				</tr>
<?
		}
?>
			</table>
		<?
		} else {
	?>
			<table>
				<tr>
					<td class="ball1"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b1"?>" name="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value="<?echo $b1?>" readonly></td>
					<td class="ball2"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b2"?>" name="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value="<?echo $b2?>" readonly></td>
					<td class="ball3"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b3"?>" name="<?print $name."f".$frame."b3"?>" maxlength="1" type="text" value="<?echo $b3?>" readonly></td>
					<td class="placeholder"></td>
				</tr>
				<tr>
					<td id="<?print $name."score10"?>" class="score" colspan='3'>&nbsp;</td>
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
				drawPins($name, $rack, $pins, 0); 
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
	
}
?>
</table>
<script type="text/javascript">
	<?button();?>
</script>
<?

}//end of draw

include('./footer.php');
?>
