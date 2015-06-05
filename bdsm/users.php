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
$uname = $_SESSION['uname'];
?>
<html>
<head>
<script type="text/javascript">
var validUser = /^[a-zA-Z_0-9]{1,8}$/;
var validPwd = /^[a-zA-Z_0-9]{4,64}$/;
var validName = /^[a-zA-Z 0-9]{1,32}$/;
var validEditPwd = /^[a-zA-Z_0-9]{0,64}$/;

function val(){
	if(validUser.test(document.getElementById("username").value)){
	
	}else{
		alert("Username name must contain only a-z, A-Z, 0-9, and _.\nAnd may only be 8 characters long.");
		return false;
	}
	
	if(validPwd.test(document.getElementById("password").value)){
		if(document.getElementById("password").value == document.getElementById("conf").value){
			
		}else {
			alert("The passwords you typed do not match.");
			return false;
		}
	}else{
		alert("Password may only contain a-z, A-Z, 0-9 and _.\nPassword must be at least 4 characters long, and as long as 64 characters.");
		return false;
	}
	
	if(validName.test(document.getElementById("name").value)){
		return true;
	}else {
		alert("Name must contain only a-z, A-Z, 0-9, and spaces");
		return false;
	}
}

function editval(){
	if(validUser.test(document.getElementById("username").value)){
	
	}else{
		alert("Username name must contain only a-z, A-Z, 0-9, and _.\nAnd may only be 8 characters long.");
		return false;
	}
	
	if(validEditPwd.test(document.getElementById("password").value)){
		
	}else{
		alert("Password may only contain a-z, A-Z, 0-9 and _.\nPassword must be at least 4 characters long, and as long as 64 characters.");
		return false;
	}
	
	if(validName.test(document.getElementById("name").value)){
		return true;
	}else {
		alert("Name must contain only a-z, A-Z, 0-9, and spaces");
		return false;
	}
}
</script>
</head>
<body>
<?
//render top bar
if($_SESSION['a'] == 2){
	admin();
} elseif($_SESSION['a'] == 1){
	user();
}else{
	player();
}

if($_SESSION['a'] == 2){
	if($_POST['sub'] == "Add User") adduser();
	if($_POST['sub'] == "Delete User") deleteuser();
	if($_POST['sub'] == "Confirm Changes") changeuser();
	if($_POST['sub'] == "Edit User") edituser();
	else draw();
}else print "You do not have sufficient access level to view this page.";

function draw(){
global $uname;
?>
<center><h1>User Management</h1></center>
<table style="width: 800px;" border="1">
<tr>
	<td valign="top">
		<form action="users.php" method="POST" onSubmit="return val();">
		
			<b>Add User</b><br/>
			Username: <input type="text" name="username" id="username"><br/>
			Password: <input type="password" name="password" id="password"><br/>
			Confirm Password: <input type="password" name="conf" id="conf"><br/>
			Name: <input type="text" name="name" id="name"><br/>
			<input type="radio" name="admin" value="0" checked>Player <input type="radio" name="admin" value="1">User <input type="radio" name="admin" value="2">Admin<br/>
			<input type="submit" name="sub" value="Add User">
		
		</form>
	</td>
	<td valign="top">
		<form action="users.php" method="POST">
		<div>
		<b>Edit User:</b><br/>
			Username: <select name="uid" id="editbox">
		<?
			
			$query = "SELECT uid, name, username FROM users ORDER BY name ASC";
			$result = mysql_query($query);
			while($nameArray = mysql_fetch_array($result)){
				list($uid, $name, $username) = $nameArray;
				print "\t\t<option value=\"$uid\">$name($username)</option>\n";
			}
		?>
			</select> <input type="submit" name="sub" value="Edit User">
		</div>
		</form>
	</td>
	<td valign="top">
		<form action="users.php" method="POST">
			<b>Delete user</b><br/>
			Username: <select name="username">
		<?
			
			$query = "SELECT uid, name, username FROM users WHERE username != '$uname' ORDER BY name ASC";
			$result = mysql_query($query);
			while($nameArray = mysql_fetch_array($result)){
				list($uid, $name, $username) = $nameArray;
				print "\t\t<option value=\"$uid\">$name($username)</option>\n";
			}
		?>
			</select> <input type="submit" name="sub" value="Delete User" onClick="return confirm('Delete this user and all their scores?')">
		</form>
	</td>

</tr>
</table>
<?
}

function adduser(){
	if($_POST['username']) $username = $_POST['username'] or exit("Username not provided");
	if($_POST['password']) $password = $_POST['password'] or exit("Password not provided");
	if($_POST['name']) $name = $_POST['name'] or exit("Name not provided");
	$admin = $_POST['admin'];
	
	$q = "SELECT COUNT(uid) FROM users WHERE username = '$username'";
	$r = mysql_query($q);
	$row = mysql_fetch_row($r);
	
	if($row[0] > 0){
		print "Username $username already exists in system.";
		
	}else{
		$q = "INSERT INTO users (username, password, name, admin) VALUES('$username', PASSWORD('$password'), '$name', $admin)";
		$r = mysql_query($q) or exit("Query \"$q\" failed.");
		print "User $username added successfuly\n<p/>\n";
	}
}
function deleteuser(){
	if($_POST['username']) $uid = $_POST['username'] or exit("Username/ID not provided");
	//delete from users
	$q = "DELETE FROM users WHERE uid = $uid";
	$r = mysql_query($q) or exit("Query ($q) failed.");
	//delete from games
	$q = "DELETE FROM games WHERE player_id = $uid";
	$r = mysql_query($q) or exit("Query ($q) failed.");
	//delete from scores
	$q = "DELETE FROM scores WHERE player_id = $uid";
	$r = mysql_query($q) or exit("Query ($q) failed.");
	//delete from pinfall
	$q = "DELETE FROM pinfall WHERE player_id = $uid";
	$r = mysql_query($q) or exit("Query ($q) failed.");
	print "User deleted.<br/>\n";
}

function edituser(){
	$uid = $_POST['uid'];
	
	$q = "SELECT username, name, admin FROM users WHERE uid = $uid";
	$r = mysql_query($q);
	
	$row = mysql_fetch_array($r);
?>
<form action="users.php" method="POST" onSubmit="return editval();">
<b>Edit <?echo $row[name]?></b><br/>
<input type="hidden" name="uid" value="<?echo $uid?>">
name: <input type="text" id="name" name="name" value="<?echo $row[name]?>"><br/>
username: <input type="text" id="username" name="username" value="<?echo $row[username]?>"><br/>
Change Password? <input type="checkbox" name="chpwd"> New Password <input type="password" id="password" name="password"><br/>
<?
if($row[username] == $_SESSION['uname']){//this is so a user cannot change their class.
	print "<input type=\"hidden\" name=\"admin\" value=\"".$row[admin]."\">\n";
}else{
	if($row[admin] == 0) print "Class: <input type=\"radio\" name=\"admin\" value=\"0\" checked>player <input type=\"radio\" name=\"admin\" value=\"1\">user <input type=\"radio\" name=\"admin\" value=\"2\">admin<br/>\n";
	elseif($row[admin] == 1) print "Class: <input type=\"radio\" name=\"admin\" value=\"0\">player <input type=\"radio\" name=\"admin\" value=\"1\" checked>user <input type=\"radio\" name=\"admin\" value=\"2\">admin<br/>\n";
	elseif($row[admin] == 2) print "Class: <input type=\"radio\" name=\"admin\" value=\"0\">player <input type=\"radio\" name=\"admin\" value=\"1\">user <input type=\"radio\" name=\"admin\" value=\"2\" checked>admin<br/>\n";
}
?>
<input type="submit" name="sub" value="Confirm Changes" onClick="return confirm('Save these changes?')">
</form>

<?
}

function changeuser(){
	$uid = $_POST['uid'] or exit("UID not provided");
	$name = $_POST['name'] or exit("name not provided");
	$username = $_POST['username'] or exit("name not provided");
	if($_POST['chpwd'] && $_POST['password']){
		$chpwd = 1;
		$password = $_POST['password'];
	}else {
		$chpwd = 0;
	}
	if($_POST['admin'] == 1) $admin = 1;
	elseif($_POST['admin'] == 2) $admin = 2;
	else $admin = 0;
	
	if($chpwd) $q = "UPDATE users SET username = '$username', name = '$name', password = PASSWORD('$password'), admin = $admin WHERE uid = $uid";
	else $q = "UPDATE users SET username = '$username', name = '$name', admin = $admin WHERE uid = $uid";
	
	$r = mysql_query($q) or exit("Query \"$q\" failed.");
	print "User $username edited successfully.<p/>\n";
}

include('./footer.php');
?>


