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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
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
	
	//assign $startDate and $endDate default values if not assigned
	//$startDate and $endDate are used to limit queries and statistics between two YYYY-MM-DD dates.
	if(!$_GET['startDate'] || $_GET['startDate'] == "beginning"){
		$qDate = "SELECT DATE(date) AS date FROM games WHERE player_id = $uid ORDER BY date ASC LIMIT 1";
		$rDate = mysql_query($qDate);
		$startDate = mysql_fetch_array($rDate);
		$startDate = $startDate[date];
	}else $startDate = $_GET['startDate'];
	
	if(!$_GET['endDate'] || $_GET['endDate'] == "present"){
		$qDate = "SELECT DATE(date) AS date FROM games WHERE player_id = $uid ORDER BY date DESC LIMIT 1";
		$rDate = mysql_query($qDate);
		$endDate = mysql_fetch_array($rDate);
		$endDate = $endDate[date];
	}else $endDate = $_GET['endDate'];
	
	//Start building the HTML table that houses game/statistic information.
	$q = "SELECT name FROM users WHERE uid = $uid";
	$r = mysql_query($q);
	$n = mysql_fetch_row($r);
	
	print "<center><h1>".$n[0]."</h1></center>\n";
	print "<table style=\"width: 800px;\" border=1>\n<tr>\n\t<td valign=\"top\">\n";
	if($dispLen == "All"){
		$q = "SELECT game_id, score, DATE(date), location FROM games WHERE player_id = $uid AND DATE(date) >= \"$startDate\" AND DATE(date) <= \"$endDate\" ORDER BY $orderBy $order";
	}else{
		$q = "SELECT game_id, score, DATE(date), location FROM games WHERE player_id = $uid AND DATE(date) >= \"$startDate\" AND DATE(date) <= \"$endDate\" ORDER BY $orderBy $order LIMIT $dispLen";
	}
	$r = mysql_query($q);
	print "\t\t<b>Games Played</b>\n";
?>
		<form action="usergames.php" method="GET">
		<input type="hidden" name="uid" value="<?echo $uid?>">
		Order By:<select name="orderBy" style="font-size: 0.75em;">
			<option value="date" <?if($_GET['orderBy'] == "date") echo "selected"?>>Date</option>
			<option value="score" <?if($_GET['orderBy'] == "score") echo "selected"?>>Score</option>
			<option value="location" <?if($_GET['orderBy'] == "location") echo "selected"?>>Location</option>
		</select>
		<select name="order" style="font-size: 0.75em;">
			<option value="DESC" <?if($_GET['order'] == "DESC") echo "selected"?>>Descending</option>
			<option value="ASC" <?if($_GET['order'] == "ASC") echo "selected"?>>Ascending</option>
		</select>
		Limit to <select name="dispLen" style="font-size: 0.75em;">
			<option value="15" <?if($_GET['dispLen'] == "15") echo "selected"?>>15</option>
			<option value="20" <?if($_GET['dispLen'] == "20") echo "selected"?>>20</option>
			<option value="50" <?if($_GET['dispLen'] == "50") echo "selected"?>>50</option>
			<option value="All" <?if($_GET['dispLen'] == "All") echo "selected"?>>All</option>
		</select> games 
		<br/>
<?//query the 'games' table to find the dates on which games have been played
	$qDates = "SELECT DISTINCT DATE(date) AS fDate FROM games WHERE player_id = $uid ORDER BY fDate ASC";
	$rDates = mysql_query($qDates);
	
	//this while loop generates the <option>s for the <select>s startDate and endDate from games.date in the database.
	//the if()'s checks against submitted values to see if the current value should be selected by default	

	//start defining the default optoins "Beginning" and "Present", respectivly.
	$optStart = "<option value=\"beginning\" ";
	if(!$_GET['startDate'] || $_GET['startDate'] == "beginning") $optStart .= "selected";
	$optStart .= ">Beginning</option>";
	
	$optEnd = "<option value=\"present\" ";
	if(!$_GET['endDate'] || $_GET['endDate'] == "present") $optEnd .= "selected";
	$optEnd .= ">Present</option>";
	
	while($dates = mysql_fetch_array($rDates)){
		//startDate optoins
		$optStart .= "\t\t\t<option value=\"".$dates[fDate]."\"";
		if($_GET['startDate'] == $dates[fDate]) $optStart .= " selected";
		$optStart .= ">".$dates[fDate]."</option>\n";
		
		//endDate optoins
		$optEnd .= "\t\t\t<option value=\"".$dates[fDate]."\"";
		if($_GET['endDate'] == $dates[fDate]) $optEnd .= " selected";
		$optEnd .= ">".$dates[fDate]."</option>\n";
	}
?>

		From 
		<select name="startDate" style="font-size: 0.75em;">		
<?//print the <option>'s for startDate
	print $optStart;
?>
		</select>
		
		To
		<select name="endDate" style="font-size: 0.75em;">
<?
	print $optEnd;
?>
		</select>
		<input type="submit" value="Sort Games" style="font-size: 0.75em;">
		</form><br/>
<?	
	//find the number of games played between startDate and endDate
	//if more games were played than displayed, let the user know
	$qNumGames = "SELECT COUNT(game_id) FROM games WHERE player_id = $uid AND DATE(date) >= '$startDate' AND DATE(date) <= '$endDate'";
	$rNumGames = mysql_query($qNumGames);
	$numGames = mysql_fetch_array($rNumGames);
	$numGames = $numGames[0];

	if($dispLen != "All" && $numGames > $dispLen) print "Displaying $dispLen games of $numGames.<br/>\n";
	print "<br/>\n";
	
	while($games = mysql_fetch_array($r)){
		list($gid, $score, $date, $location) = $games;
		print"\t\t<a href=\"result.php?game_id=$gid\">Score of $score</a>  at $location, on $date<br/>\n";
	}
?>
	</td>
	<td valign="top">
		<b>Statistics</b>
		<i><?//say if statistics are Carreer or a timespan.
		if($_GET['startDate'] == "beginning" && $_GET['endDate'] == "present") print "Career";
		elseif(!$_GET['startDate'] || !$_GET['endDate']) print "Career";
		print "($startDate to $endDate)";?></i>
		<br/>
<?
	
	
	//send in the statistics!
	//Average
	$q = "SELECT ROUND(AVG(score)) AS avg FROM games WHERE player_id = $uid AND DATE(date) >= '$startDate' AND DATE(date) <= '$endDate'";
	$r = mysql_query($q);
	$stat = mysql_fetch_array($r);
	print "\t\tAverage: ".$stat[avg]."<br/>\n";
	
	//games played
	$q = "SELECT COUNT(game_id) AS games_played FROM games WHERE player_id = $uid  AND DATE(date) >= \"$startDate\" AND DATE(date) <= \"$endDate\"";
	$r = mysql_query($q);
	$stat = mysql_fetch_array($r);
	print "\t\tGames Played: ".$stat[0]."\n\t\t<br/>\n";
	
	//strikes
	//hold onto your hat, the queries get hairy.
	$q = "SELECT SUM(CASE 1 WHEN scores.b1 = 10 AND scores.b2 = 10 AND scores.b3 = 10 THEN 3 WHEN scores.b1 = 10 AND scores.b2 = 10 AND scores.b3 != 10 THEN 2 
	WHEN scores.b1 != 10 AND scores.b2 != 10 AND scores.b3 = 10 THEN 1 
	WHEN scores.b1 = 10 AND scores.b2 != 10 AND scores.b3 != 10 THEN 1 
	ELSE 0 END) AS strikes 
	FROM scores INNER JOIN games ON scores.player_id = games.player_id AND scores.game_id = games.game_id
	WHERE scores.player_id = $uid AND DATE(games.date) >= \"$startDate\" AND DATE(games.date) <= \"$endDate\" GROUP BY scores.player_id";
	$r = mysql_query($q);
	$stat = mysql_fetch_row($r);
	print "\t\tStrikes: ".$stat[0]."\n\t\t<br/>\n";
	
	//spare shooting:
	$q = "SELECT 
		(SELECT COUNT(scores.frame) 
		FROM scores INNER JOIN games ON scores.player_id = games.player_id AND scores.game_id = games.game_id
		WHERE CASE 1 WHEN scores.frame != 10 AND scores.b1 != 10 AND scores.b1 + scores.b2 = 10 THEN 1 WHEN scores.frame = 10 AND scores.b1!= 10 AND scores.b1 + scores.b2 = 10 THEN 1 WHEN scores.frame = 10 AND scores.b1 = 10 AND scores.b2 != 10 AND scores.b2 + scores.b3 = 10 THEN 1 END AND scores.player_id = $uid AND DATE(games.date) >='$startDate' AND DATE(games.date) <='$endDate') AS spares,
		(SELECT COUNT(scores.frame) 
		FROM scores INNER JOIN games ON scores.player_id = games.player_id AND scores.game_id = games.game_id
		WHERE CASE 1 WHEN scores.frame != 10 AND scores.b1 != 10 THEN 1 WHEN scores.frame = 10 AND scores.b1 != 10 THEN 1 WHEN scores.frame = 10 AND scores.b1 = 10 AND scores.b2 != 10 THEN 1 END AND scores.player_id = $uid AND DATE(games.date) >='$startDate' AND DATE(games.date) <='$endDate') AS frames";
	$r = mysql_query($q);
	$stat = mysql_fetch_row($r);
	list($spare, $frame) = $stat;
	if($frame != 0) $percentage = round(($spare/$frame)*100, 2);
	else $percentage = 100;
	print "\t\tSpare shooting: $spare for $frame($percentage%)\n\t\t<br/>\n";
	
	//toal pinfall
	$q = "SELECT (SUM(scores.b1) + SUM(scores.b2) + SUM(scores.b3)) AS total_pinfall
		FROM scores INNER JOIN games ON scores.player_id = games.player_id AND scores.game_id = games.game_id
		WHERE scores.player_id = $uid AND DATE(games.date) >= '$startDate' AND DATE(games.date) <= '$endDate'";
	$r = mysql_query($q);
	$stat = mysql_fetch_row($r);
	print "\t\tTotal Pinfall: ".$stat[0]."\n\t\t<br/>\n";
	
	//consecutive strikes:
	//Jason, don't double take it's more elegant that it seems at first glance.-JF 04/14/07
	$q = "SELECT DISTINCT(scores.game_id)
		FROM scores INNER JOIN games ON scores.player_id = games.player_id AND scores.game_id = games.game_id
		WHERE scores.b1 = 10 AND scores.player_id = $uid AND DATE(games.date) >= '$startDate' AND DATE(games.date) <= '$endDate'";
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
	$sixb = 0;
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
	
	//catches poults where f9 is a X and f10b1 is a X
	if($continous == 2 && $gameArray[f10b2] == 9){
		$poult++;
		continous($continous);
		$continous = 0;
	}
	//catches poults that happen in f10
	if($continous == 1 && $gameArray[f10b2] == 10 && $gameArray[f10b3] == 9){
		$poult++;
		$continous += 1;
		continous($continous);
		$continous = 0;
	}
	//catches doubles tha happen in f10
	if($gameArray[f10b2] == 10 && $gameArray[f10b3] == 10){
		$continous += 2;
		continous($continous);
		$continous = 0;
	} 
	//default case for handling f10b2 strikes
	if($continous != 0 && $gameArray[f10b2] == 10){
		$continous += 1;
		continous($continous);
		$continous = 0;
	}
	//catch if f10b1 is a X and f10b2 is not a X
	if($gameArray[f10b2] != 10){
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

include('./footer.php');
?>
