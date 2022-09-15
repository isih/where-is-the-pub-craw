<?php 
    require_once "../config.php";

    //retrieve suggestions from DB
    $suggestions = mysqli_query($link, "SELECT name FROM city");

    if ($suggestions->num_rows > 0) {
        while($row = $suggestions->fetch_assoc()) {
            echo $row['name'];
            echo '|';
        }
    }
?>