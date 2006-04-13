<?
include('./header.php');
session_start ();//grab info from cookie

if($_SESSION['a']) admin();
else user();

if($_GET['game_id']){
	$game_id = $_GET['game_id'];
	drawGame();
}elseif($_POST['game_id']){
	$game_id = $_POST['game_id'];
	drawGame();
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
?>

<?
function drawGame(){

	global $game_id;
?>
<html>
<head>
<title>Displaying Game <?echo $game_id?></title>
<script type="text/javascript">
var nonums = /^[0-9]*$/;//regex used to test if something is a number.

/*Game is our grand daddy object.  Everything goes on inside of an instance of Game.*/
function Game() {
	this.score = new Array();//totaled scores for each frame(the one under the ball scores)
	this.framescore = new Array();//individual scores for frames
	
	//define methods below.
	this.scoreGame = scoreGame;
}
/*
scoreGame() does the heavy lifting.  It stores every ball rolled in the framescore array.  It stores strikes as X's spares as #/'s and open frames as ##.
b1, b2, and b3 are the three possible balls in a frame.  They pull this information from the forms.  
It then processes each ball into framescore.
The 10th frame is dealt with specially, there is a quirk identifying 0 as an int, so I had to add a special case.
After filling framescore, it is processed to fill the score array.  
*/
function scoreGame(){
	this.framescore = new Array;//we'll need a fresh slate.
	for(this.frame = 1; this.frame <= 10; this.frame++){
		this.b1 = document.getElementById(this.name+'f'+this.frame+'b1').innerHTML;
		this.b2 = document.getElementById(this.name+'f'+this.frame+'b2').innerHTML;
		if(this.b2 == "&nbsp;/&nbsp;") this.b2 = "/";
		if(this.frame == 10) this.b3 = document.getElementById(this.name+'f10b3').innerHTML;
		
		if(this.frame != 10){//fill framescore with the first 9 frame's information.
			if(this.b1 == "X"){
				this.framescore = this.framescore.concat("X");
			}else if (this.b2 == "/") {
				this.framescore = this.framescore.concat(parseInt(this.b1), "/")
			}else if(this.frame == 10 && this.b2 == "X"){
				this.framescore = this.framescore.concat("X", 10);
			}else {
				this.framescore = this.framescore.concat(parseInt(this.b1), parseInt(this.b2));
			}
		}else {
			//turn all three balls in frame 10 into integers, because no bonuses are added in this frame.
			if(this.b1 == "X") this.b1 = 10;
			else if(parseInt(this.b1)) this.b1 = parseInt(this.b1);
			else if(this.b1 == "0") this.b1 = 0;
			if(this.b2 == "X") this.b2 = 10;
			else if(this.b2 == "/") this.b2 = 10 - this.b1;
			else if(parseInt(this.b2)) this.b2 = parseInt(this.b2);
			else if(this.b2 == "0") this.b2 = 0;
			if(this.b3 == "X") this.b3 = 10;
			else if(this.b3 == "/") this.b3 = 10 - this.b2;
			else if(this.b3 == "0") this.b3 = 0;
			else if(parseInt(this.b3)) this.b3 = parseInt(this.b3);
			else this.b3 = 0;
			
			this.framescore = this.framescore.concat(parseInt(this.b1), parseInt(this.b2), parseInt(this.b3));//append the last three balls to framescore
		}
	}
	
	//now chew on the freshly filled framescore array to generate the scores.
	this.score = new Array; //blank any scores that may be in the array, we need a fresh slate.
	this.fsp = 0;//keeps track of where we are in framescore. framescore's position
	for(this.frame = 0; this.frame < 10; this.frame++){
		if(this.frame != 9){//deal with all cases involving strikes.
			if(this.framescore[this.fsp] == "X"){ //the cases for strikes
				if(this.framescore[this.fsp + 1] =="X" && this.framescore[this.fsp + 2] == "X") this.score[this.frame] = 30;
				else if(this.framescore[this.fsp + 1] == "X" && this.framescore[this.fsp + 2] != "X") this.score[this.frame] = 20 + this.framescore[this.fsp + 2];
				else if(this.framescore[this.fsp + 2] == "/") this.score[this.frame] = 20;
				else this.score[this.frame] = 10 + this.framescore[this.fsp+1] + this.framescore[this.fsp+2];
				this.fsp += 1;//advnace fsp for the next pass
			}else if(this.framescore[this.fsp + 1] == "/"){//deal with all cases involving spares
				if(this.framescore[this.fsp + 2] == "X") this.score[this.frame] = 20;
				else this.score[this.frame] = 10 + this.framescore[this.fsp + 2];
				this.fsp += 2;//advance fsp for the next pass
			}else {//deal with an open frame.
				this.score[this.frame] = this.framescore[this.fsp] + this.framescore[this.fsp+1];
				this.fsp += 2;//advance fsp for the next pass
			}
		}else{//score the 10th frame.
			this.score[this.frame] = this.framescore[this.fsp] + this.framescore[this.fsp + 1] + this.framescore[this.fsp + 2];
		}
	}
	//now we use a for loop to calculate the total for each frame.  We then write to the innerHTML of a TD tag.
	this.total = 0;
	for(this.x = 0; this.x < 10; this.x++){
		if(nonums.test(this.total + this.score[this.x])){
			this.total += this.score[this.x];
			document.getElementById(this.name+'score'+ (this.x+1)).innerHTML = this.total;
		}else{
			document.getElementById(this.name+'score'+ (this.x+1)).innerHTML = "&nbsp;";
		}
	}
	
}

</script>
<style>
td{
	border-color: black;
}
td.head{
	text-align: center;
	font-weight: bold;
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
	text-align: right;
}
td.ball3{
	border-style: solid;
	border-width: 1px;
	text-align: center;
}
td.score{
	text-align: center;
}

</style>
</head>
<body onLoad="<?button()?>">
<?
$q = "SELECT location, DATE_FORMAT(date, '%W, %M %D, %Y %l:%i%p') FROM games WHERE game_id = $game_id";
$r = mysql_query($q);
$row = mysql_fetch_row($r);

?>
<center>
<?print "<font size=\"+1\">".$row[1]."</font><br/><font size=\"+2\">Played at ".$row[0]."</font><br/>\n"?>
<?if($_SESSION['a']) print "<a href=\"gameedit.php?game_id=$game_id\">edit this game</a>\n<p/>\n";?>
</center>
<table>
	<tr>
		<td class="head">&nbsp;</td>
<?
for($x = 1; $x <= 10; $x++){
	print "\t\t<td class=\"head\">$x</td>\n";
}
?>
	</tr>
	<tr>
<?
$query = "SELECT game_id FROM games WHERE game_id = $game_id";
$result = mysql_query($query);
mysql_fetch_array($result) or exit("No game with id $game_id.");

$query = "SELECT users.username, games.player_id, users.name FROM users, games WHERE games.game_id = $game_id AND users.uid = games.player_id";
$result = mysql_query($query);

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
	</script>
	<?
	while($x = mysql_fetch_array($r)){
		list($frame, $b1, $b2, $b3) = $x;
		if($frame != 10){
			if($b1 == 10 ) $b1 = "X";
			if($b1 + $b2 == 10) $b2 = "&nbsp;/&nbsp;";
		}else{
			if($b1 == 10) $b1 = "X";
			if($b2 == 10) $b2 = "X";
			if($b3 == 10) $b3 = "X";
			
			if($b2 != 0 && $b1 + $b2 == 10) $b2 = "&nbsp;/&nbsp;";
			if($b3 != 0 && $b2 + $b3 == 10) $b3 = "&nbsp;/&nbsp;";
		}
		if($frame == 1) print "\t\t<td class=\"name\"><a href=\"usergames.php?uid=$id\">$realName</a></td>\n";
		?>
		<td class="frame">
		<?
		if($frame != 10){
		?>
			<table>
				<tr>
					<td class="ball1" id="<?print $name."f".$frame."b1"?>"><?echo $b1?></td>
					<td class="ball2" id="<?print $name."f".$frame."b2"?>"><?echo $b2?></td>
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
					<td class="ball1" id="<?print $name."f".$frame."b1"?>"><?echo $b1?></td>
					<td class="ball2" id="<?print $name."f".$frame."b2"?>"><?echo $b2?></td>
					<td class="ball3" id="<?print $name."f".$frame."b3"?>"><?echo $b3?></td>
				</tr>
				<tr>
					<td id="<?print $name."score".$frame?>" class="score" colspan='3'>&nbsp;</td>
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
</body>
</html>

<?

}
?>