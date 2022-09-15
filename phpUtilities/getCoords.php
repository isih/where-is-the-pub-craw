<?php 
    require_once "../config.php";

    //retrieve coordinates from DB

    //sql to get current started pubcrawl
    $currentDate = date("Y-m-d");

    //only shows started pubcrawls
    $pendingStarted = mysqli_query($link,"SELECT p.agent_id FROM
    pubcrawls p WHERE
    DATE(p.start_time) = '$currentDate' AND p.status = 'Started'");

    $user_id = '';
    if ($pendingStarted->num_rows > 0) {
        while($row1 = $pendingStarted->fetch_assoc()) {
            $user_id = $row1["agent_id"];
        }
    }

    $coordinates = mysqli_query($link, "SELECT latitude, longitude FROM users WHERE id='".$user_id."'");

    //it will behave strangely if more than one pubcrawl is actiive
    
    while($row = $coordinates->fetch_assoc()) {
        echo $row['longitude'];
        echo ';';
        echo $row['latitude'];
    }
    
?>