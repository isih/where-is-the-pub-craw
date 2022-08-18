<?php 
    require_once "../config.php";

    //retrieve suggestions from DB
    $suggestions = mysqli_query($link, "SELECT stop, longitude, latitude FROM suggestions");

    if ($suggestions->num_rows > 0) {
        while($row = $suggestions->fetch_assoc()) {
            echo $row['stop'];
            echo 'coords';
            echo $row['longitude'];
            echo ';';
            echo $row['latitude'];
            echo '|';
        }
    }
?>