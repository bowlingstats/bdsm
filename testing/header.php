<?
$sql_host = "localhost";//the hostname of your MySQL server, Deafualt is localhost
$sql_user = "";//the username for your MySQL database
$sql_pwd = "";//the password of username
$sql_db = "bdsm";//the name of the database you setup.  Default bdsm
$conn = mysql_pconnect($sql_host, $sql_user, $sql_pwd);
$db = mysql_select_db($sql_db);
?>