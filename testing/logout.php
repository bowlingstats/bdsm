<?php

session_start();
session_unset();
if(session_destroy()){
  echo "Logout successful.<Br>Log back in <a href='login.php'>Here</a>\n";
} else {
  echo "Logout failed.\n";
}
?>

