<?php 
    require_once "../config.php";

    //retrieve lon,lat  from DB
    $name = trim($_POST["name"]);
    
    $pendingLonLat = mysqli_query($link,"SELECT c.latitude, c.longitude FROM
    city c WHERE
    c.name = '$name'");

    while($row = $pendingLonLat->fetch_assoc()) {
        echo $row['longitude'];
        echo ';';
        echo $row['latitude'];
    }
