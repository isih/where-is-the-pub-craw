<?php
header("X-Robots-Tag: noindex, nofollow", true);
try {
	//Insert here hosting page name
    $pageName = 'whereisthepubcrawl.com';
	//require_once($_SERVER['DOCUMENT_ROOT'].'/kickstart/wp-config.php');
	//require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
	/* Attempt to connect to MySQL database */
	//$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$link = new mysql('localhost', 'root', '', 'southtou_witp');
	// Check connection
	include("functions.php");
	if($link === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}

} catch (PDOException $e) {
    echo $e->getMessage();
}

?>