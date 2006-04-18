<?PHP
include('./header.php');
session_start();

if(!isset($a)){
  $a = false;
}

if($_POST['uname'] && $_POST['pwd']){//1
  if(authorize($_POST['uname'], $_POST['pwd'])){//2
    $_SESSION['a'] = true;
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
  $query = "SELECT username, password FROM users WHERE username = '$un' AND password = password('$pw') AND admin = 1";
  $result = mysql_query($query);
  $num = mysql_numrows($result);
  if($num == 1){
    $a = True;
    return True;
  } else {
    return false;
  }// end conditional
}
