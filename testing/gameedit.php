<?
include('./header.php');
session_start ();//grab info from cookie
if($_SESSION['a'] == 2){//only  admins may edit games.
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
	if($_SESSION['a'] > 0) user();
	else player();
	print "Only administrators may edit games.";
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
input.year{
	width: 45px;
}
input.month{
	width: 20px;
}
input.day{
	width: 20px;
}
</style>

<script type="text/javascript" src="scoring.js">
</script>
<script type="text/javascript">
var src;
function setCust(){
	src = document.getElementById("location").innerHTML;
	document.getElementById("location").innerHTML = "<input type='text'name='location' id='loc'><input type='button' value='cancel' onClick='restore()'>";
}
function restore(){
	document.getElementById("location").innerHTML = src;
}
function val(){
	if(parseInt(document.getElementById("year").value) && parseInt(document.getElementById("month").value) && parseInt(document.getElementById("day").value)){
	} else{
		alert("Year, Month, and Day must all be numbers");
		return false;
	}
	
	if(document.getElementById("loc").value != ""){
		return true;
	} else {
		alert("Location cannot be blank");
		return false;
	}
}
</script>

</head>
<body onLoad="<?button()?>">
<?
//create a centered heading
$q = "SELECT location, DATE_FORMAT(date, '%W, %M %D, %Y %l:%i%p'), YEAR(date), MONTH(date), DAY(date) FROM games WHERE game_id = $game_id";
$r = mysql_query($q);
$row = mysql_fetch_row($r);
?>
<center>
<?print "<font size=\"+1\">".$row[1]."</font><br/><font size=\"+2\">Played at ".$row[0]."</font><br/>\n"?>
<?print $row[2]."-".$row[3]."-".$row[4]."<br/>\n"?>
</center>

<form action="gamesubmit.php" method="post" onSubmit="return val()">
<input type="hidden" name="game_id" value="<?echo $game_id?>">
<b>yyyy-mm-dd</b>: <input name="year" id="year" class="year" type="text" maxlength="4" value="<?echo $row[2]?>"> - <input type="text" name="month" id="month" class="month" maxlength= 2 value="<?echo $row[3]?>"> - <input type="text" name="day" id="day" class="day" maxlength="2" value="<?echo $row[4]?>"><br/>
<?
print "<b>Location</b>: <span id=\"location\"><select name=\"location\" id=\"loc\" onClick=\"if(this.value == 'custom') setCust();\">\n";
	$query = "SELECT location, COUNT(location) AS num FROM games WHERE location != \"".$row[0]."\" GROUP BY location ORDER BY num DESC";
	$result = mysql_query($query);
	print "\t<option value=\"".$row[0]."\">".$row[0]."</option>\n";
	while($locArray = mysql_fetch_array($result)){
		list($loc, $num) = $locArray;
		print "\t<option value=\"$loc\">$loc</option>\n";
	}
	print "\t<option value=\"custom\">Custom:</option>\n</select></span>\n";
?>
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
	$q = "SELECT frame, b1, b2, b3 FROM scores WHERE game_id = $game_id AND player_id = $id ORDER BY frame ASC";
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
<input type="submit" name="edit" value="Save Changes" onClick="<?button()?>return(confirm('Do you wish to commit these scores to the database?'));"> 
<input type="submit" name="delete" value="Delete Game" onClick="alert('You have clicked Delete Game.'); return(confirm('Do you wish to delete this game from the database?'));">
</form>

<?
}//ends drawGame()

include('./footer.php');
?>