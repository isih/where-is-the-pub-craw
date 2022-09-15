<?php
header("X-Robots-Tag: noindex, nofollow", true);
try {
	//Insert here hosting page name
    $pageName = 'www.blusoft.org/witp-staging';
	//require_once($_SERVER['DOCUMENT_ROOT'].'/kickstart/wp-config.php');
	//require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
	/* Attempt to connect to MySQL database */
	//$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$link = mysqli_connect('127.0.0.1', 'root', '', 'southtou_witp');
	// Check connection
	if($link === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}

} catch (PDOException $e) {
    echo $e->getMessage();
}

?>