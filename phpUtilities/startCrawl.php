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
    $d=$_POST['id'];
    $agentId=$_SESSION['id'];

    $sql = 'UPDATE pubcrawls SET status = "Started", agent_id = "'.$agentId.'" WHERE id="'.$d.'"';

	if ($link->query($sql) === TRUE) {
		$_SESSION["currentCoords"] = '';//
	  echo "Record updated successfully";
	} else {
	  echo "Error updating record: " . $link->error;
	}
?>