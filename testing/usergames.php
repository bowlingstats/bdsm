<?
include('./header.php');// gets us our connection to the database;
session_start ();//grab info from cookie

if($_SESSION['a']) admin();
else user();

if($_GET['uid']) $uid = $_GET['uid'];
else exit("uid of player not provided, exiting.");
$q = "SELECT name FROM users WHERE uid = $uid";
$r = mysql_query($q);
$n = mysql_fetch_row($r);

print "<center><h1>Games Played By ".$n[0]."</h1></center>\n";

$q = "SELECT game_id FROM games WHERE player_id = $uid";
$r = mysql_query($q);

while($games = mysql_fetch_array($r)){
	list($gid) = $games;
	print"<a href=\"result.php?game_id=$gid\">Game $gid</a><br/>\n";
}

?>