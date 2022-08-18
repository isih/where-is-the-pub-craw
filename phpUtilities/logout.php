<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
//$_SESSION = array();
 
// Destroy the session.
//session_destroy();

//unset($_SESSION["request_rejected"]);
//unset($_SESSION["request_confirmed"]);
//unset($_SESSION["user_deleted"]);
//unset($_SESSION["admin_loggedin"]);
//unset($_SESSION["admin_email"]);
//unset($_SESSION["admin_id"]);

unset($_SESSION["loggedin"]);
unset($_SESSION["role"]);
unset($_SESSION["email"]);
unset($_SESSION["id"]);
unset($_SESSION["currentCoords"]);
//unset($_SESSION["chosenPubCrawlId"]);

// Redirect to login page
header("location: ../index.php");
exit;
?>