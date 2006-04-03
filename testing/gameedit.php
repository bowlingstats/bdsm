<?
$sql_host = "localhost";
$sql_users = "jfrancis";
$sql_pwd = "thefish";
$conn = mysql_pconnect($sql_host, $sql_users, $sql_pwd);
$db = mysql_select_db("bdsm");

function button(){
	$query = "SELECT users.username, games.player_id FROM users, games WHERE games.game_id = 1 AND users.uid = games.player_id";
	$result = mysql_query($query);
	while($n = mysql_fetch_array($result)){// this creates as html table for each player in games.game_id
		list($name, $id) = $n;//get their name and their UID
		print "$name.scoreGame();";
	}	
}
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

<script type="text/javascript">
var nonums = /^[0-9]*$/;//regex used to test if something is a number.

/*Game is our grand daddy object.  Everything goes on inside of an instance of Game.*/
function Game() {
	this.score = new Array();//totaled scores for each frame(the one under the ball scores)
	this.framescore = new Array();//individual scores for frames
	
	//define methods below.
	this.validate = validate;
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
		this.b1 = document.getElementById(this.name+'f'+this.frame+'b1').value;
		this.b2 = document.getElementById(this.name+'f'+this.frame+'b2').value;
		if(this.frame == 10) this.b3 = document.getElementById(this.name+'f10b3').value;
		
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

/*
validate() is used to clean up input in the forms.  If a spare is entered as two numbers, it make ball2 a "/"
it makes certain that nothing else is entered with a strike.
It also ensures that no frame has a value higher than 10 entered into it.
*/
function validate(frame){
	//get elements, then get the vaules
	this.ball1 = document.getElementById(this.name + "f" + frame + "b1");
	this.ball2 = document.getElementById(this.name + "f" + frame + "b2");
	this.b1 = document.getElementById(this.name + "f" + frame + "b1").value;
	this.b2 = document.getElementById(this.name + "f" + frame + "b2").value;
	
	
	
	//verify b1 is a valid character.
	if(this.b1 != "X" && this.b1 != "x"){
		if(!nonums.test(this.b1)){
			alert("Ball 1 must be a number 0-9 or X");
			this.ball1.value = "";
		}
	}
	//verify that ball2 is a valid character.	
	if(this.b1 == "x" || this.b1 == "X"){
		this.ball1.value = "X";
		this.ball2.value = "";
	} else if(parseInt(this.b1) + parseInt(this.b2) == 10){
		this.ball2.value = "/";
	} else if(parseInt(this.b1) + parseInt(this.b2) > 10){
		alert("The maximum value for one frame is 10.");
		this.ball1.value = "";
		this.ball2.value = "";
	} else if(this.b2 != "/" && !nonums.test(this.b2)){
		alert("Ball 2 must be a number 0-9 or /");
		this.ball2.value = "";
	}
}


</script>

</head>
<body onLoad="<?button()?>">
<form>
<table>
	<tr>

<?


$query = "SELECT users.username, games.player_id, users.name FROM users, games WHERE games.game_id = 1 AND users.uid = games.player_id";
$result = mysql_query($query);

while($n = mysql_fetch_array($result)){// this creates as html table for each player in games.game_id
	list($name, $id, $realName) = $n;//get their name and their UID
	//get the results for each player from games
	$q = "SELECT frame, b1, b2, b3 FROM scores WHERE game_id = 1 AND player_id = $id";
	$r = mysql_query($q);
	?>
	<script type="text/javascript">
	<?echo $name?> = new Game();
	<?echo $name?>.name = "<?echo $name?>";
	</script>
	<?
	while($x = mysql_fetch_array($r)){
		list($frame, $b1, $b2, $b3) = $x;
		if($b1 == 10) $b1 = "X";
		if($b1 + $b2 == 10) $b2 = "/";
		
		if($frame == 1) print "\t\t\t\t<td class=\"name\">$realName</td>\n"
	?>	
		<td class="frame">
	<?
		if($frame != 10){
	?>
			<table>
				<tr>
					<td class="ball1"><input class="frame" id="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value="<?echo $b1?>"></td>
					<td class="ball2"><input class="frame" id="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value ="<?echo $b2?>" onBlur="<?echo $name?>.validate('<?echo $frame?>'); <?echo $name?>.scoreGame();"></td>
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
					<td class="ball1"><input class="frame" id="<?print $name."f".$frame."b1"?>" maxlength="1" type="text" value="<?echo $b1?>"></td>
					<td class="ball2"><input class="frame" id="<?print $name."f".$frame."b2"?>" maxlength="1" type="text" value="<?echo $b2?>"></td>
					<td class="ball3"><input class="frame" id="<?print $name."f".$frame."b3"?>" maxlength="1" type="text" value="<?echo $b3?>"></td>
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
<input type="button" value="Score Games" onClick="<?button()?>">
</form>
</body>
</html>
