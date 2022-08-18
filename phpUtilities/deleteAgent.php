<?php
    require_once "../config.php";
    
    //deleteAgent.php is called by AJAX in Dashboard.js
    $d=$_POST['id'];

    $sql = 'DELETE FROM users WHERE id="'.$d.'"';

	if ($link->query($sql) === TRUE) {
	  echo "Record updated successfully";
	} else {
	  echo "Error updating record: " . $link->error;
	}
?>