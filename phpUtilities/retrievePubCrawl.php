<?php
    require_once "../config.php";
    
    //deleteAgent.php is called by AJAX in Dashboard.js
    $d=$_POST['id'];
    $sql = mysqli_query($link,'SELECT p.city,p.start_time,p.duration,p.meeting_point,p.stops,p.durations,p.geojson FROM pubcrawls p WHERE p.id="'.$d.'"');


    $city="";
    $start_time ="";
    $duration="";
    $meeting_point="";
    $stops="";
    $durations="";
    $geojson="";

    if ($sql->num_rows > 0) {
        while($row1 = $sql->fetch_assoc()) {
            echo $city = $row1['city'];
            echo ';';
            echo $start_time= $row1['start_time'];
            echo ';';
            echo $duration = $row1['duration'];
            echo ';';
            echo $meeting_point = $row1['meeting_point'];
            echo ';';
            echo $stops = $row1['stops'];
            echo ';';
            echo $durations = $row1['durations'];
            echo ';';
            echo $geojson = $row1['geojson'];
        }
    }
