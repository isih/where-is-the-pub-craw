<?php
require_once "../config.php";

$err = '';

//update scheduled pubcrawl to DB
// Validate pubcrawl
if (
    empty(trim($_POST["id"])) ||
    empty(trim($_POST["start_time"])) ||
    empty(trim($_POST["duration"])) ||
    empty(trim($_POST["meeting_point"])) ||
    empty(trim($_POST["stops"])) ||
    empty(trim($_POST["coordinates"]))

) {
    $err = "You didn't fill all the required fields to schedule the pubcrawl";
    echo $err;
} else {
    $id = trim($_POST["id"]);
    $start_time = trim($_POST["start_time"]);
    $duration = trim($_POST["duration"]);
    $meeting_point = trim($_POST["meeting_point"]);
    $stops = trim($_POST["stops"]);
    $coordinates = trim($_POST["coordinates"]);
    $durations = trim($_POST["durations"]);

    $exploded_stops = explode("_", $stops);
    $exploded_coords = explode("_", $coordinates);
    if (count($exploded_stops) !== count($exploded_coords)) {
        $err = "Check stops and coordinates";
        echo $err;
    }

    //preparing geojson file with pubs coordinates
    $geojson_file =
        '{
      "type": "FeatureCollection",
      "features": [
    ';

    for ($i = 0; $i < count($exploded_stops); $i++) {
        $stop = $exploded_stops[$i];

        $exploded_coords_lonlat = explode(",", $exploded_coords[$i]);

        $lon = $exploded_coords_lonlat[0];
        $lat = $exploded_coords_lonlat[1];

        $geojson_file .= '
        {
      "type": "Feature",';
        $geojson_file .= '
      "properties": {';
        $geojson_file .= '
        "marker-color": "#7e7e7e",
        "marker-size": "medium",
        "marker-symbol": "",
      ';
        $geojson_file .= '
        "name": "' . $stop . '"
      },';
        $geojson_file .= '
        "geometry": {
          "type": "Point",
          "coordinates": [
      ';
        $geojson_file .= '
            ' . $lon . ',
            ' . $lat . '
          ]
        }
      }
      ';

        if ($i != count($exploded_stops) - 1) {
            $geojson_file .= ',';
        }
    }

    $geojson_file .= '
      ]
    }';

    // Bind variables to the prepared statement as parameters
    $status = 'Ready'; //status is Ready for new PubCrawls
    if (strpos($start_time, "-") === false) {
        //Current date format: DD/MM/YYYY, HH:mm
        //Desired date format: YYYY-MM-DD HH:mm
        $exploded_datetime = explode(",", $start_time);
        $exploded_date = explode("/", $exploded_datetime[0]); //DD, MM, YYYY

        $final_start_time = '';
        for ($i = count($exploded_date) - 1; $i >= 0; $i--) {
            if ($i == 0) {
                $final_start_time .= $exploded_date[$i];
            } else {
                $final_start_time .= $exploded_date[$i] . '-';
            }
        }
        $final_start_time .= $exploded_datetime[1];
    } else {
        $final_start_time = $start_time;
    }

    //convert start_time to SQL format
    $sql = "UPDATE pubcrawls
        SET start_time='$final_start_time', duration='$duration', meeting_point='$meeting_point', stops='$stops', durations='$durations', geojson='$geojson_file'
        WHERE id=$id";

    if ($link->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $link->error;
    }
}
