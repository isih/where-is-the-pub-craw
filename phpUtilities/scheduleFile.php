<?php
  require_once "../config.php";

  // Check if the user is logged in, if not then redirect him to login page
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: signin.php");
    exit;
  }


  //insert scheduled pubcrawl to DB
  

?>