<?PHP
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


include('./header.php');
session_start();

if(!isset($a)){
  $a = false;
}

if($_POST['uname'] && $_POST['pwd']){//1
  if(authorize($_POST['uname'], $_POST['pwd'])){//2
    $q = "SELECT admin FROM users WHERE username = '".$_POST['uname']."'";
	$r = mysql_query($q);
	$row = mysql_fetch_array($r);
	$_SESSION['a'] = $row[admin];
    $_SESSION['uname'] = $_POST['uname'];
    header("Location: index.php");
	exit;
  } else{
    echo "Login incorrect.";
    $_SESSION['a'] = false;
    dispLogin();
  }//2
}else {
  dispLogin();
}//1

function dispLogin(){
  print "<form action=login.php method=post>
  <table>
  <tr><td align='right'>
  <b>Username:</b>
  </td>
  <td>
  <input type=text name='uname'>
  </td>
  </tr>
  <tr>
  <td align='right'>
  <b>Password:</b>
  </td>
  <td>
  <input type=password name='pwd'>
  </td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td align='right'>
  <input type='submit' value='Login'>
  </td>
  </tr>
  </table>
  </form>";
}//dispLogin

function authorize($un, $pw){
  $query = "SELECT username, password FROM users WHERE username = '$un' AND password = password('$pw') AND admin > 0";
  $result = mysql_query($query);
  $num = mysql_numrows($result);
  if($num == 1){
    $a = True;
    return True;
  } else {
    return false;
  }// end conditional
}
