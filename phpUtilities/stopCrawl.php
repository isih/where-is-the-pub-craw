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
    }*/

    
    
    //stopCrawl.php is called by AJAX in manageCrawling.js
    $d = '';
    if(isset($_POST['id'])){
      $d=$_POST['id'];
      require_once "../config.php";
    } else if (isset($pubId)) {
      $d = $pubId;
    }
    

    $sql = 'UPDATE pubcrawls SET status = "Ready", agent_id = NULL WHERE id="'.$d.'"';
    $_SESSION["currentCoords"] = 'false';//

	if ($link->query($sql) === TRUE) {
		
	  echo "Record updated successfully";
	} else {
	  echo "Error updating record: " . $link->error;
	}
?>