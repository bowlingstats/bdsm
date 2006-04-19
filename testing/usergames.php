<?
include('./header.php');// gets us our connection to the database;
session_start ();//grab info from cookie
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title> </title>
</head>
<body>
<?
if($_SESSION['a'] == 2){
	admin();
} elseif($_SESSION['a'] == 1){
	user();
}else{
	player();
}

//take the GET options for sorting and length and create usable varaiables
switch($_GET['orderBy']){
	case "date":
		$orderBy = "date";
		break;
	case "score":
		$orderBy = "score";
		break;
	case "location":
		$orderBy = "location";
		break;
	default:
		$orderBy = "date";
}

switch($_GET['order']){
	case "ASC":
		$order = "ASC";
		break;
	case "DESC":
		$order = "DESC";
		break;
	default:
		$order = "DESC";
}

switch($_GET['dispLen']){
	case "20":
		$dispLen = "20";
		break;
	case "50":
		$dispLen = "50";
		break;
	case "All":
		$dispLen = "All";
		break;
	default:
		$dispLen = "15";
}

//build a table to house information
if($_GET['uid']){
	$uid = $_GET['uid'];
	
	$q = "SELECT name FROM users WHERE uid = $uid";
	$r = mysql_query($q);
	$n = mysql_fetch_row($r);
	
	print "<center><h1>".$n[0]."</h1></center>\n";
	print "<table style=\"width: 800px;\" border=1>\n<tr>\n\t<td valign=\"top\">\n";
	if($dispLen == "All"){
		$q = "SELECT game_id, score, DATE(date), location FROM games WHERE player_id = $uid ORDER BY $orderBy $order";
	}else{
		$q = "SELECT game_id, score, DATE(date), location FROM games WHERE player_id = $uid ORDER BY $orderBy $order LIMIT $dispLen";
	}
	$r = mysql_query($q);
	print "\t\t<b>Games Played</b>\n";
?>
		<form action="usergames.php" method="GET">
		<input type="hidden" name="uid" value="<?echo $uid?>">
		Order By:<select name="orderBy" style="font-size: 0.75em;">
			<option value="date">Date</option>
			<option value="score">Score</option>
			<option value="location">Location</option>
		</select>
		<select name="order" style="font-size: 0.75em;">
			<option value="DESC">Descending</option>
			<option value="ASC">Ascending</option>
		</select>
		Limit to <select name="dispLen" style="font-size: 0.75em;">
			<option value="15">15</option>
			<option value="20">20</option>
			<option value="50">50</option>
			<option value="All">All</option>
		</select> games 
		<input type="submit" value="Sort Games" style="font-size: 0.75em;">
		</form><br/>
<?	
	while($games = mysql_fetch_array($r)){
		list($gid, $score, $date, $location) = $games;
		print"\t\t<a href=\"result.php?game_id=$gid\">Score of $score</a>  at $location, on $date<br/>\n";
	}
?>
	</td>
	<td valign="top">
		<b>Statistics</b><br/>
<?
	//send in the statistics!
	//hold onto your hat, the queries get hairy.
	$q = "SELECT SUM(CASE 1 WHEN b1 = 10 AND b2 = 10 AND b3 = 10 THEN 3 WHEN b1 = 10 AND b2 = 10 AND b3 != 10 THEN 2 WHEN b1 != 10 AND b2 != 10 AND b3 = 10 THEN 1 WHEN b1 = 10 AND b2 != 10 AND b3 != 10 THEN 1 ELSE 0 END) AS strikes FROM scores WHERE player_id = $uid GROUP BY player_id";
	$r = mysql_query($q);
	$stat = mysql_fetch_row($r);
	print "\t\tStrikes: ".$stat[0]."\n\t\t<br/>\n";
	
	//spare shooting:
	$q = "SELECT (SELECT COUNT(frame) FROM scores WHERE CASE 1 WHEN frame != 10 AND b1 != 10 AND b1 + b2 = 10 THEN 1 WHEN frame = 10 AND b1!= 10 AND b1 + b2 = 10 THEN 1 WHEN frame = 10 AND b1 = 10 AND b2 != 10 AND b2 + b3 = 10 THEN 1 END AND player_id = $uid) AS spares,
	(SELECT COUNT(frame) FROM scores WHERE CASE 1 WHEN frame != 10 AND b1 != 10 THEN 1 WHEN frame = 10 AND b1 != 10 THEN 1 WHEN frame = 10 AND b1 = 10 AND b2 != 10 THEN 1 END AND player_id = $uid) AS frames";
	$r = mysql_query($q);
	$stat = mysql_fetch_row($r);
	list($spare, $frame) = $stat;
	if($frame != 0) $percentage = round(($spare/$frame)*100, 2);
	else $percentage = 100;
	print "\t\tSpare shooting: $spare for $frame($percentage%)\n\t\t<br/>\n";
	
	//consecutive strikes:
	$q = "SELECT DISTINCT(game_id) FROM scores WHERE b1 = 10 AND player_id = $uid";
	$r = mysql_query($q);
	//populate an array of games where there are strikes
	$c = 0;//counter for strikeArray
	while($x = mysql_fetch_row($r)){
		$strikeArray[$c] = $x[0];
		$c++;
	}
	if(count($strikeArray) == 0) $strikeArray[0] = 0; 
	/*Below are variables that will store how many doubles, poults, turkeys, four baggers, five baggers ... twelve baggers this user has.*/
	$double = 0;
	$poult = 0;
	$turkey = 0;
	$fourb = 0;
	$fiveb = 0;
	$sixeb = 0;
	$sevenb = 0;
	$eightb = 0;
	$nineb = 0;
	$tenb = 0;
	$elevenb = 0;
	$twelveb = 0;
	//run a query of each game, and process how many consecutive strikes there are, storing the information in arrays like $double, $turkey, $fourb ... $twelveb
	foreach($strikeArray as $key=>$value){
		$q = "SELECT $uid AS uid, $value AS gid, ";//We set uid and gid to refrence the player_id and game_id in the rest of the query.
		for($x = 1; $x < 10; $x++){//get the value for balls 1 and 2 from each frame up to the 10th, which is a special case.
			$q .= "(SELECT b1 FROM scores WHERE game_id = gid AND player_id = uid AND frame = $x) AS f".$x."b1, ";
			$q .= "(SELECT b2 FROM scores WHERE game_id = gid AND player_id = uid AND frame = $x) AS f".$x."b2, ";
		}
		$q .= "(SELECT b1 FROM scores WHERE game_id = gid AND player_id = uid AND frame = 10) AS f10b1, ";
		$q .= "(SELECT b2 FROM scores WHERE game_id = gid AND player_id = uid AND frame = 10) AS f10b2, ";
		$q .= "(SELECT b3 FROM scores WHERE game_id = gid AND player_id = uid AND frame = 10) AS f10b3";
		
		$r = mysql_query($q);
		while($row = mysql_fetch_array($r)){			
			strikes($row);//get the result for doubles on through perfect games for this user.
		}
	}
	//print the results from strikes() and continous()
	print "\t\tDoubles: $double<br/>\n";
	print "\t\tPoults: $poult<br/>\n";
	print "\t\tTurkeys: $turkey<br/>\n";
	print "\t\tFour Baggers: $fourb<br/>\n";
	print "\t\tFive Baggers: $fiveb<br/>\n";
	print "\t\tSix Baggers: $sixb<br/>\n";
	print "\t\tSeven Baggers: $sevenb<br/>\n";
	print "\t\tEight Baggers: $eightb<br/>\n";
	print "\t\tNine Baggers: $nineb<br/>\n";
	print "\t\tTen Baggers: $tenb<br/>\n";
	print "\t\tEleven Baggers: $elevenb<br/>\n";
	print "\t\tTwelve Baggers: $twelveb<br/>\n";
	
?>
	</td>
</tr>
</table>
<?
}else{
	print "<center><h1>Ten most recent games</h1></center>\n";
	$q = "SELECT DISTINCT(game_id), DATE(date), location FROM games ORDER BY date DESC LIMIT 10";
	$r = mysql_query($q);
	
	while($games = mysql_fetch_array($r)){
		list($gid, $date, $location) = $games;
		print "<a href=\"result.php?game_id=$gid\">$location</a> on $date<br/>\n";
	}
}
//takes an entire game, and determines the number of strikes in a row, it passess that information on to continous() and then resumes looking for new sets of strikes.
function strikes($gameArray){
	global $poult;
	$gameArray = array_slice($gameArray, 3);// removes game data from front of array
	$continous = 0;//counter of continous stirkes
	$x = 1;//counter for the while loop.
	//while loop handled frames 1 - 10's first balls
	while($x <= 10){
		if($gameArray[f.$x.b1] == 10){
			$continous++;
			$x++;
		} elseif($continous == 2 && $gameArray[f.$x.b1] == 9){
			//this is a special case to catch poults.
			$poult++;
			continous($continous);
			$continous = 0;
			$x++;
		}else{
			continous($continous);
			$continous = 0;
			$x++;
		}
	}
	//these conditionals handle the special case of 2nd and 3rd balls in the 10th frame.
	if($continous == 2 && $gameArray[f10b2] == 9){
		$poult++;
		continous($continous);
		$continous = 0;
	} elseif($continous == 1 && $gameArray[f10b2] == 10 && $gameArray[f10b3] == 9){
		$poult++;
		$continous += 1;
		continous($continous);
		$continous = 0;
	} elseif($gameArray[f10b2] == 10 && $gameArray[f10b3] == 10){
		$continous += 2;
		continous($continous);
		$continous = 0;
	} elseif($continous != 0 && $gameArray[f10b2] == 10){
		$continous += 1;
		continous($continous);
		$continous = 0;
	}
	
}//end strikes

//takes the counter from strikes() and increments the count of whatever type of bagger it is.
function continous($c){
	global $twelveb, $elevenb, $tenb, $nineb, $eightb, $sevenb, $sixb, $fiveb, $fourb, $turkey, $double;
	
	switch($c){
		case 12:
			$twelveb++;
			break;
		case 11:
			$elevenb++;
			break;
		case 10:
			$tenb++;
			break;
		case 9:
			$nineb++;
			break;
		case 8:
			$eigthb++;
			break;
		case 7:
			$sevenb++;
			break;
		case 6:
			$sixb++;
			break;
		case 5:
			$fiveb++;
			break;
		case 4:
			$fourb++;
			break;
		case 3:
			$turkey++;
			break;
		case 2:
			$double++;
			break;
	}
}//end continous
?>
</body>
</html>