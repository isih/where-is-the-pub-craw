<?php 

require_once "../config.php";

$currentDate = date("Y-m-d");

$lastVP = mysqli_query($link,"SELECT p.lastVisitedPlace FROM
    pubcrawls p WHERE
    DATE(p.start_time) = '$currentDate' AND p.status = 'Started'");


$lastVisitedPlace = '';

if ($lastVP->num_rows > 0) {
    while($row1 = $lastVP->fetch_assoc()) {
        echo $lastVisitedPlace = $row1["lastVisitedPlace"];
    }
}