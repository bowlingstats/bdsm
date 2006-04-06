<?
include('./header.php');// gets us our connection to the database;
session_start ();//grab info from cookie

if($_SESSION['a']) admin();
else user();


$q = "SELECT name, uid FROM users";
$r = mysql_query($q);
print "Games by player<br/>\n";
while($a = mysql_fetch_array($r)){
	list($name, $uid) = $a;
	print "<a href=\"usergames.php?uid=$uid\">$name</a><br/>\n";
}

//high scores
$q = "SELECT users.name, games.game_id, games.score FROM users, games WHERE users.uid = games.player_id ORDER BY games.score DESC LIMIT 5";
$r = mysql_query($q);
?>
<table border="1">
	<tr>
		<td colspan="2">High Scores</td>
	</tr>
<?
while($a = mysql_fetch_array($r)){
	list($name, $game_id, $score) = $a;
	print "\t<tr>\n";
	print "\t\t<td><a href=\"result.php?game_id=$game_id\">$name</a></td>\n";
	print "\t\t<td><b>$score</b></td>\n";
	print "\t</tr>\n";
}
?>
</table>
