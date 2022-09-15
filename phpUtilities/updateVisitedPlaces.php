<?php
// Initialize the session
session_start();
require_once "../config.php";

$lastVisitedPlace = $_POST['lastVisitedPlace'];
$updated = "UPDATE pubcrawls SET lastVisitedPlace = '$lastVisitedPlace' WHERE status='Started'";
if ($link->query($updated) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $link->error;
}
