<?php

	// Initialize the session
	session_start();
	/*
	 // Check if the user is logged in, if not then redirect him to login page
	 if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        if( isset($_SESSION["role"]) && $_SESSION["role"]!=='Admin') {
            header("location: index.php");
        } else {
            header("location: signin.php");
        }
        exit;
    }
*/

    require_once "../config.php";
    
    //startCrawl.php is called by AJAX in manageCrawling.js
    //$d=$_POST['id'];
	$d=$_SESSION['id'];
	$lat=$_POST['lat'];
	$lon=$_POST['lon'];

    $sql = 'UPDATE users SET latitude = "'.$lat.'", longitude = "'.$lon.'" WHERE id="'.$d.'"';

	if ($link->query($sql) === TRUE) {
		$_SESSION["currentCoords"] = $lat.":".$lon;//
		echo $_SESSION["currentCoords"];
	} else {
	  	//echo "Error updating record: " . $link->error;
	}
?>