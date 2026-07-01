<?php session_start();
  if($_SESSION['user_type']!='parent')include "usershome.php";
        else include "parenthome.php";?>