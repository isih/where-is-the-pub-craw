<?php
// Initialize the session
session_start();
require_once "../config.php";



//$d = trim($_POST['id']);
$lastVisitedPlace1 = $_POST['lastVisitedPlace1'];
print($lastVisitedPlace1);
$updated = "UPDATE pubcrawls SET lastVisitedPlace = '$lastVisitedPlace1' WHERE status='Started'";
if ($link->query($updated) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $link->error;
}
