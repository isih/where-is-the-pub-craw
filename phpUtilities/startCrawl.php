<?php
	// Initialize the session
	session_start();
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