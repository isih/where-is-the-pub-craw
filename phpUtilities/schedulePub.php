<?php
require_once "../config.php";

/*
  Quick summary of what this php script does:
  Given the parameters, insert the pub crawl schedule in the DB

  $_POST["city"];
  $_POST["start_time"]; //to format with mySQL ISO format
  $_POST["meeting_point"];
  $_POST["stops"];//array of the stops' names.
  $_POST["coordinates"];
  */

$err = '';

//insert scheduled pubcrawl to DB
// Validate pubcrawl
if (
  empty(trim($_POST["city"])) ||
  empty(trim($_POST["start_time"])) ||
  empty(trim($_POST["duration"])) ||
  empty(trim($_POST["meeting_point"])) ||
  empty(trim($_POST["stops"])) ||
  empty(trim($_POST["coordinates"])) ||
  empty(trim($_POST["enable"])) ||
  empty(trim($_POST["repeatDays"]))
) {

  $err = "You didn't fill all the required fields to schedule the pubcrawl";
  echo $err;
} else {

  $city = trim($_POST["city"]);
  $start_time = trim($_POST["start_time"]);
  $duration = trim($_POST["duration"]);
  $meeting_point = trim($_POST["meeting_point"]);
  $stops = trim($_POST["stops"]);
  $coordinates = trim($_POST["coordinates"]);
  $durations = trim($_POST["durations"]);
  $enable = trim($_POST["enable"]);
  $repeatDays = trim($_POST["repeatDays"]);


  //To test input from JS
  //echo "city: ".$city;
  //echo "\nstart_time:".$start_time;//to convert to datetima
  //echo "\nmeeting_point:".$meeting_point;
  //echo "\nstops: ".$stops;
  //echo "\ncoordinates: ".$coordinates;

  //$exploded_durations = explode(",",$durations);
  //echo $durations;


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



  $sql = "INSERT INTO pubcrawls (city, start_time, duration, meeting_point, stops, durations, geojson, status, enable_timeline) VALUES (?,?,?,?,?,?,?,?, ?)";

  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    if ($repeatDays == 1) {
      $status = 'Ready'; //status is Ready for new PubCrawls

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
      //obtained desired date format

      //convert start_time to SQL format


      mysqli_stmt_bind_param($stmt, "sssssssss", $city, $final_start_time, $duration, $meeting_point, $stops, $durations, $geojson_file, $status, $enable);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {

        echo 'success!';
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }

    } else {
      for ($j = 0; $j < $repeatDays; $j++) {
        $status = 'Ready'; //status is Ready for new PubCrawls

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
        $final_start_time = date('Y-m-d', strtotime($final_start_time . ' + ' . $j . ' days'));

        $final_start_time .= $exploded_datetime[1];
        //obtained desired date format

        //convert start_time to SQL format


        mysqli_stmt_bind_param($stmt, "sssssssss", $city, $final_start_time, $duration, $meeting_point, $stops, $durations, $geojson_file, $status, $enable);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {

          echo 'success!';
        } else {
          echo "Oops! Something went wrong. Please try again later.";
        }
      }
    }




    // Close statement
    mysqli_stmt_close($stmt);
  }
}
