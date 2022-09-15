<?php
require_once "../config.php";

$id = $_POST['cityID'];
$cityName = trim($_POST['cityName']);
if ($id === "0") {
    $findCityID = mysqli_query($link, "SELECT c.id FROM
        city c WHERE c.name = '$cityName'");
    if ($findCityID->num_rows > 0) {
        while ($row1 = $findCityID->fetch_assoc()) {
            $id = $row1["id"];
        }
    }
}
$suggestions = mysqli_query($link, "SELECT stop, longitude, latitude FROM suggestions Where cityId = $id");

//retrieve suggestions from DB
if ($suggestions->num_rows > 0) {
    while ($row = $suggestions->fetch_assoc()) {
        echo $row['stop'];
        echo 'coords';
        echo $row['longitude'];
        echo ';';
        echo $row['latitude'];
        echo '|';
    }
}
