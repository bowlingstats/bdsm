<?
include('./header.php');// gets us our connection to the database;
session_start ();//grab info from cookie

//if($_SESSION['a']) admin();
//else user();

?>
<html>
<head>
<title>Bowling Database/Statistics Management(pre alpha)</title>
<style>
table.main{
	width: 800px;
}
td.players{
	width: 150px;
}
</style>
</head>
<?
if($_SESSION['a'] == 2){
	admin();
} elseif($_SESSION['a'] == 1){
	user();
}else{
	player();
}
?>
<table border="1" class="main">
<tr>
<?
//list of players
$q = "SELECT name, uid FROM users ORDER BY name ASC";
$r = mysql_query($q);
print "\t<td class=\"players\" valign=\"top\">\n";
print "<b>Players</b><br/>\n";
while($a = mysql_fetch_array($r)){
	list($name, $uid) = $a;
	print "\t\t<a href=\"usergames.php?uid=$uid\">$name</a><br/>\n";
}
print "\t</td>\n";

//averages
$q = "SELECT users.name, ROUND(AVG(games.score)) AS avg FROM users, games WHERE users.uid = games.player_id GROUP BY games.player_id ORDER BY avg DESC";
$r = mysql_query($q);
?>
	<td class="hsc" valign="top">
		<table border="1">
			<tr>
				<td colspan="2"><b>Averages</b></td>
			</tr>
<?
while($a = mysql_fetch_array($r)){
	list($name, $score) = $a;
	print "\t\t\t<tr>\n";
	print "\t\t\t\t<td>$name</td>\n";
	print "\t\t\t\t<td><b>$score</b></td>\n";
	print "\t\t\t</tr>\n";
}
?>
		</table>
<?
print "\t</td>\n";

//high scores
$q = "SELECT users.name, games.game_id, games.score FROM users, games WHERE users.uid = games.player_id ORDER BY games.score DESC LIMIT 10";
$r = mysql_query($q);
?>
	<td class="hsc" valign="top">
		<table border="1">
			<tr>
				<td colspan="2"><b>High Scores</b></td>
			</tr>
<?
while($a = mysql_fetch_array($r)){
	list($name, $game_id, $score) = $a;
	print "\t\t\t<tr>\n";
	print "\t\t\t\t<td><a href=\"result.php?game_id=$game_id\">$name</a></td>\n";
	print "\t\t\t\t<td><b>$score</b></td>\n";
	print "\t\t\t</tr>\n";
}
?>
		</table>
	</td>
</tr>
</table>
