<?
include('./header.php');
session_start ();//grab info from cookie
if($_SESSION['a']){
	if($_GET['game_id']){
		$game_id = $_GET['game_id'];
		admin();
		drawGame();
	}else if($_POST['game_id']) {
		$game_id = $_POST['game_id'];
		admin();
		drawGame();
	} else {
		print "No game_id set, nothing to display.";
	}
} else{
	print "You must be logged in to use this page.";
}

function button(){
	global $game_id;
	$query = "SELECT users.username, games.player_id FROM users, games WHERE games.game_id =".$game_id." AND users.uid = games.player_id";
	$result = mysql_query($query);
	while($n = mysql_fetch_array($result)){// this creates as html table for each player in games.game_id
		list($name, $id) = $n;//get their name and their UID
		print "$name.scoreGame();";
	}	
}

function drawGame(){
	global $game_id;
?>
<html>
<head>
<style>
td{
	border-color: black;
}
td.name{
	border-style: solid;
	border-width: 2px;
	text-align: center;
	font-weight: bold;
}
td.frame{
	border-style: solid;
	border-width: 2px;
	padding: 0px;
}
td.ball1{
	text-align: center;
}
td.ball2{
	border-style: solid;
	border-width: 1px;
	text-align: center;
}
td.ball3{
	border-style: solid;
	border-width: 1px;
	text-align: center;
}
td.score{
	text-align: center;
}
input.frame{
	width:15px;
	text-align: center;
}
</style>

<script type="text/javascript" src="scoring.js">
</script>

</head>
<body onLoad="<?button()?>">
<form action="gamesubmit.php" method="post">
<input type="hidden" name="game_id" value="<?echo $game_id?>">
<table>
	<tr>

<?
$query = "SELECT game_id FROM games WHERE game_id = $game_id";
$result = mysql_query($query);
mysql_fetch_array($result) or exit("No game with id $game_id.");

$query = "SELECT users.username, games.player_id, users.name FROM users, games WHERE games.game_id = $game_id AND users.uid = games.player_id";
$result = mysql_query($query);
$playerNum = 0;
while($n = mysql_fetch_array($result)){// this creates as html table for each player in games.game_id
	$playerNum++;
	list($name, $id, $realName) = $n;//get their name and their UID
	//get the results for each player from games
	$q = "SELECT frame, b1, b2, b3 FROM scores WHERE game_id = $game_id AND player_id = $id";
	$r = mysql_query($q);
	?>
	<script type="text/javascript">
	<?echo $name?> = new Game();
	<?echo $name?>.name = "<?echo $name?>";
	<?echo $name?>.finalScore = "";
	</script>
	<?
	while($x = mysql_fetch_array($r)){
		list($frame, $b1, $b2, $b3) = $x;
		if($frame != 10){
			if($b1 == 10 ) $b1 = "X";
			if($b1 + $b2 == 10) $b2 = "/";
		}else{
			if($b1 == 10) $b1 = "X";
			if($b2 == 10) $b2 = "X";
			if($b3 == 10) $b3 = "X";
			
			if($b2 != 0 && $b1 + $b2 == 10) $b2 = "/";
			if($b3 != 0 && $b2 + $b3 == 10) $b3 = "/";
		}
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
			</table>
	<?
		} else {
	?>
			<table>
				<tr>
					<td class="ball1"><input class="frame" autocomplete="off" name="<?print $name."f".$frame."b1"?>" id="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value="<?echo $b1?>"></td>
					<td class="ball2"><input class="frame" autocomplete="off" name="<?print $name."f".$frame."b2"?>" id="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value="<?echo $b2?>" onFocus="<?echo $name?>.validate('<?echo $frame?>');" onChange="<?echo $name?>.validate('10'); <?echo $name?>.scoreGame();"></td>
					<td class="ball3"><input class="frame" autocomplete="off" name="<?print $name."f".$frame."b3"?>" id="<?print $name."f".$frame."b3"?>" maxlength="1" type="text" value="<?echo $b3?>" onFocus="<?echo $name?>.validate('<?echo $frame?>');" onChange="<?echo $name?>.validate('10'); <?echo $name?>.scoreGame();"></td>
				</tr>
				<tr>
					<td id="<?print $name."score".$frame?>" class="score" colspan='3'>&nbsp;</td>
					<input type="hidden" name="<?echo $name?>score" id="<?echo $name?>score">
				</tr>
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
<input type="submit" name="edit" value="Save Changes" onClick="<?button()?>; return(confirm('Do you wish to commit these scores to the database?'));">
</form>
</body>
</html>
<?
}//ends drawGame()