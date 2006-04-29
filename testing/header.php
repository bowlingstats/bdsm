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

$start = gettimeofday(true);//get the time to track page generation times.

$sql_host = "localhost";//the hostname of your MySQL server
$sql_user = "";//the username for your MySQL database
$sql_pwd = "";//the password of username
$sql_db = "bdsm";//the name of the database you setup.  Default bdsm
$conn = mysql_pconnect($sql_host, $sql_user, $sql_pwd);
$db = mysql_select_db($sql_db);


function player(){
?>
	<div class="main" style="width: 800px;">
	
	<table class="links" style="width: 800px;">
	<tr>
		<td class="links" colspan="2">
			<a href="index.php">Home</a> <a href="usergames.php">Recent Games</a> 
		</td>
		<td class="admin" style="text-align: right;">
			<a href="login.php">Login</a>
		</td>
	</tr>
	</table>
<?
}
function user(){
?>
	<div class="main" style="width: 800px;">
	
	<table class="links" style="width: 800px;">
	<tr>
		<td class="links" colspan="2">
			<a href="index.php">Home</a> <a href="usergames.php">Recent Games</a> <b><a href="game.php">Add Game</a></b>
		</td>
		<td class="admin" style="text-align: right;">
			<a href="logout.php">Logout(<i><?echo $_SESSION['uname']?></i>)</a>
		</td>
	</tr>
	</table>
<?
}
function admin(){
?>
	<div class="main" style="width: 800px;">
	
	<table class="links" style="width: 800px;">
	<tr>
		<td class="links" colspan="2">
			<a href="index.php">Home</a> <a href="usergames.php">Recent Games</a> <b><a href="game.php">Add Game</a> <a href="users.php">User Management</a></b>
		</td>
		<td class="admin" style="text-align: right;">
			<a href="logout.php">Logout(<i><?echo $_SESSION['uname']?></i>)</a>
		</td>
	</tr>
	</table>
<?
}
