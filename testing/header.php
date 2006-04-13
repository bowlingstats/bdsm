<?
$sql_host = "localhost";//the hostname of your MySQL server
$sql_user = "";//the username for your MySQL database
$sql_pwd = "";//the password of username
$sql_db = "bdsm";//the name of the database you setup.  Default bdsm
$conn = mysql_pconnect($sql_host, $sql_user, $sql_pwd);
$db = mysql_select_db($sql_db);

/*Fucntions admin and user draw the links at the top a page, depending on if a user is logged in or not.*/
function admin(){
?>
	<table class="links" style="width: 800px;">
	<tr>
		<td class="links" colspan="2">
			<a href="index.php">Home</a> <a href="usergames.php">Recent Games</a> <b><a href="game.php">Add Game</a></b>
		</td>
		<td class="admin" style="text-align: right;">
			<a href="logout.php"><i>Logout</i></a>
		</td>
	</tr>
	</table>
<?
}

function user(){
?>
<table class="links" style="width: 800px;">
<tr>
	<td class="links" colspan="2">
		<a href="index.php">Home</a> <a href="usergames.php">Recent Games</a> 
	</td>
	<td class="admin" style="text-align: right;">
		<a href="login.php"><i>Login</i></a>
	</td>
</tr>
</table>
<?
}
?>