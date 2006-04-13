<?
include('./header.php');
session_start ();//grab info from cookie

function getPlayers(){
?>
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
function choosePlayers($num){
	?>
	<script>
	function val(){
		namesArray = new Array();
		if(document.getElementById("location").value == ""){
			alert("Location cannot be blank.");
			return false;
		}
		
		for(x = 0; x < document.forms[0].length; x++){
			namesArray[x] = document.forms[0].elements[x].value;
		}
		
		for(x = 0; x < namesArray.length; x++){
			for(n = 0; n <= namesArray.length; n++){
				if(n != x && namesArray[n] == namesArray[x]){
					alert("Player "+(x+1)+" and Player "+(n+1)+" are identical.\n A bowler may only occur once in a game.");
					return false;
				}
			}
		}
		return true;
	}
	function setCust(){
		document.getElementById("location").innerHTML = "<input type='text'name='location' id='location'>";
	}
	</script>
	<?
	print "<p/><form action=\"game.php\" method=\"post\" onSubmit=\"return val();\">\n";
	$query = "SELECT name, username FROM users";
	$result = mysql_query($query);
	while($nameArray = mysql_fetch_array($result)){
		list($name, $username) = $nameArray;
		$out .= "\t<option value=\"$username\">$name($username)</option>\n";
	}
	for($x = 1; $x <= $num; $x++){
		print "Player $x: <select name=\"player$x\">\n";
		print "$out";
		print "</select>\n<br/>\n";
	}
	//options for locations
	print "Location: <span id=\"location\"><select name=\"location\" id=\"location\" onClick=\"if(this.value == 'custom') setCust();\">\n";
	$query = "SELECT location, COUNT(location) AS num FROM games GROUP BY location ORDER BY num DESC";
	$result = mysql_query($query);
	while($locArray = mysql_fetch_array($result)){
		list($loc, $num) = $locArray;
		print "\t<option value=\"$loc\">$loc</option>\n";
	}
	print "\t<option value=\"custom\">Custom:</option>\n</select></span>\n";
	
	
	
	print "<input type=\"submit\"></form>\n";
}
function createGames(){
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
	print "<table>\n";
	
	foreach($players as $key=>$value){
		$q = "SELECT name FROM users WHERE username = '$value'";
		$r = mysql_query($q);
		list($rn) = mysql_fetch_array($r);
		$realNames[$key] = $rn;
	}
	foreach($players as $key=>$value){
		$realName = $realNames[$key];
		?>
		<script type="text/javascript">
		<?echo $value?> = new Game();
		<?echo $value?>.name = "<?echo $value?>";
		</script>
		<?
		print "\t<tr>\n";
		createGrid($value, $realName, $key+1);
		print "\t</tr>\n";		
	}
	print "</table>\n";
	print "<input type=\"submit\" name=\"insert\" value=\"Save Records\">\n</form>\n";
	print "<p/><table class=\"key\">\n<tr>\n<td class=\"keye\"><b>Score</b></td>\n<td class=\"keyo\"><b>Input</b></td>\n</tr><tr>\n<td class=\"keye\">0-9</td>\n<td class=\"keyo\">0-9</td>\n</tr><tr>\n<td class=\"keye\">/(spare)</td>\n<td class=\"keyo\">/</td>\n</tr><tr>\n<td class=\"keye\">X(strike)</td>\n<td class=\"keyo\">X, x, or *</td>\n</tr>\n</table>\n";
}

function createGrid($name, $realName, $playerNum){
	print "\t\t<td class=\"name\">$realName <input type=\"hidden\" name=\"player$playerNum\" value=\"$name\"></td>\n";
	for($frame = 1; $frame < 10; $frame++){
		?>
		<td class="frame">
		<table>
			<tr>
				<td class="ball1"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b1"?>" name="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value=""></td>
				<td class="ball2"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b2"?>" name="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value ="" onFocus="<?echo $name?>.validate('<?echo $frame?>');" onChange="<?echo $name?>.validate('<?echo $frame?>'); <?echo $name?>.scoreGame();"></td>
			</tr>
			<tr>
				<td id="<?print $name."score".$frame?>" class="score" colspan='2'>&nbsp;</td>
			</tr>
		</table>
		</td>
		<?
	}
	?>
	<td class="frame">
	<table>
		<tr>
			<td class="ball1"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b1"?>" name="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value="" ></td>
			<td class="ball2"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b2"?>" name="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value="" onFocus="<?echo $name?>.validate('<?echo $frame?>');" onChange="<?echo $name?>.validate('10'); <?echo $name?>.scoreGame();"></td>
			<td class="ball3"><input class="frame" autocomplete="off" id="<?print $name."f".$frame."b3"?>" name="<?print $name."f".$frame."b3"?>" maxlength="1" type="text" value="" onFocus="<?echo $name?>.validate('<?echo $frame?>');" onChange="<?echo $name?>.validate('10'); <?echo $name?>.scoreGame();"></td>
		</tr>
		<tr>
			<td id="<?print $name."score".$frame?>" class="score" colspan='3'>&nbsp;</td>
			<input type="hidden" name="<?echo $name?>score" id="<?echo $name?>score">
		</tr>
	</table>
	</td>
	
	<?
}
?>
<html>
<head>
<title>Bowling Database/Statistics Management: New Game</title>
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
table.key{
	border-color: black;
	border-style: solid;
	border-width: 1px;
}
td.keye{
	border-style: solid;
	border-width: 1px;
	text-align: right;
	padding: 2px;
}
td.keyo{
	border-style: solid;
	border-width: 1px;
	text-align: left;
	padding: 2px;
}
input.frame{
	width:15px;
	text-align: center;
}
</style>
<script type="text/javascript" src="scoring.js">
</script>
<body>
<?
//draw links heading
if($_SESSION['a']){
	admin();
} else{
	user();
}
//Auth user and draw appropriately.
if($_SESSION['a']){
  
	
	if($_GET['numPlayers']){
		$numPlayers = $_GET['numPlayers'];
		choosePlayers($numPlayers);
	}elseif($_POST['numPlayers']){
		$numPlayers = $_POST['numPlayers'];
		choosePlayers($numPlayers);
	}elseif(!$_POST['player1']){
		getPlayers();
	}
	
	if($_POST['player1']) createGames();
} else{
	print "You must be logged in to use this page.";
}
	?>
</body>
</html>