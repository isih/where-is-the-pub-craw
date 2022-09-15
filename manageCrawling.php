<?php
// Initialize the session
session_start();
/*
	// Check if the user is already logged in, if yes then redirect him to his page
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		if($_SESSION["role"] === 'Admin'){
			header("location: dashboard.php");
		} else if ($_SESSION["role"] === 'Agent') {
			header("location: startCrawling.php");
		}
		exit;
	}
*/
require_once "config.php";

$currentDate = date("Y-m-d");
$cityID = "";
$pendingCity = mysqli_query($link, "SELECT u.agentCityID FROM
users u WHERE
u.email = '$_SESSION[email]' ORDER BY id");

if ($pendingCity->num_rows > 0) {
    while ($row = $pendingCity->fetch_assoc()) {
        $cityID = $row["agentCityID"];
    }
}
$cityName = "";
$findCityName = mysqli_query($link, "SELECT c.name FROM
city c WHERE c.id = $cityID");
if ($findCityName->num_rows > 0) {
    while ($row1 = $findCityName->fetch_assoc()) {
        $cityName = $row1["name"];
    }
}

// $pending = mysqli_query($link, "SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline FROM
//     pubcrawls p WHERE
//     TIMESTAMPDIFF(HOUR, DATE(p.start_time), '$currentDate') < p.duration  AND p.status = 'Ready'");


// $pendingStarted = mysqli_query($link, "SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline FROM
//     pubcrawls p WHERE
//     TIMESTAMPDIFF(HOUR, DATE(p.start_time), '$currentDate') < p.duration AND p.status = 'Started'");

if ($cityID === '0') {
    $pending = mysqli_query($link, "SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline  FROM
    pubcrawls p WHERE
    DATE(p.start_time) = '$currentDate' AND p.status = 'Ready'");

    $pendingStarted = mysqli_query($link, "SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline FROM
    pubcrawls p WHERE
    DATE(p.start_time) = '$currentDate' AND p.status = 'Started'");
} else {
    $pending = mysqli_query($link, "SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline  FROM
    pubcrawls p WHERE
    DATE(p.start_time) = '$currentDate' And p.city = '$cityName' AND p.status = 'Ready'");

    $pendingStarted = mysqli_query($link, "SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline FROM
    pubcrawls p WHERE
    DATE(p.start_time) = '$currentDate' and p.city = '$cityName' AND p.status = 'Started'");
}



?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8" />

    <title>Manage crawling</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />-->

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <?php
    include 'common_header.php';
    ?>
</head>

<body onload="setSendCoords()">

    <ion-app>
        <ion-header>
            <ion-toolbar id="toolbar" color="white" class="ion-text-center">

                <ion-buttons slot="start">
                    <a id="main-logo" href="city.php">
                        <img src="imgs/logo.webp" alt="logo" />
                    </a>
                </ion-buttons>

                <ion-button slot="end" color="main-bg" class="menuBtn-smallScreen" style="width:70px;">
                    <ion-menu-button menu="main-menu"></ion-menu-button>
                </ion-button>

                <?php
                if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
                    if ($_SESSION["role"] === 'Admin' || $_SESSION["role"] === 'SuperAdmin') {
                ?>
                        <?php if ($_SESSION["role"] === 'Admin') { ?>
                            <ion-title id='title' size="large" class="ion-text-center">
                                Admin
                            </ion-title>
                        <?php
                        } else if ($_SESSION["role"] === 'SuperAdmin') { ?>
                            <ion-title id='title' size="large" class="ion-text-center">
                                Super Admin
                            </ion-title>
                        <?php } ?>


                        <!-- Item as an Anchor -->
                        <ion-item href="dashboard.php" slot="end" lines="none" class="bigScreen">
                            <ion-label color="main-bg">
                                Dashboard
                            </ion-label>
                        </ion-item>
                        <ion-item href="phpUtilities/logout.php" slot="end" lines="none" class="bigScreen">
                            <ion-label color="main-bg">
                                Log out
                            </ion-label>
                        </ion-item>
                    <?php
                    } else {
                    ?>
                        <ion-title id='title' size="large" class="ion-text-center">
                            Agent
                        </ion-title>
                        <!-- Item as an Anchor -->
                        <ion-item href="phpUtilities/logout.php" slot="end" lines="none">
                            <ion-label color="main-bg">
                                Log out
                            </ion-label>
                    <?php
                    }
                }
                    ?>



            </ion-toolbar>
        </ion-header>

        <ion-menu side="end" menu-id="main-menu" class="main" content-id="main">
            <ion-header>
                <ion-toolbar color="main-bg">
                    <ion-title style="color:black">Menu</ion-title>
                </ion-toolbar>
            </ion-header>
            <ion-content>
                <ion-list>
                    <?php
                    if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
                        if ($_SESSION["role"] === 'Admin' || $_SESSION["role"] === 'SuperAdmin') {
                    ?>
                            <?php if ($_SESSION["role"] === 'Admin') { ?>
                                <ion-title id='title' size="large" class="ion-text-center">
                                    Admin
                                </ion-title>
                            <?php
                            } else if ($_SESSION["role"] === 'SuperAdmin') { ?>
                                <ion-title id='title' size="large" class="ion-text-center">
                                    Super Admin
                                </ion-title>
                            <?php } ?>
                            <!-- Item as an Anchor -->
                            <ion-item href="dashboard.php" slot="end" lines="none">
                                <ion-label color="main-bg">
                                    Dashboard
                                </ion-label>
                            </ion-item>
                            <ion-item href="phpUtilities/logout.php" slot="end" lines="none">
                                <ion-label color="main-bg">
                                    Log out
                                </ion-label>
                            </ion-item>
                        <?php
                        } else {
                        ?>
                            <ion-title id='title' size="large" class="ion-text-center">
                                Agent
                            </ion-title>
                            <!-- Item as an Anchor -->
                            <ion-item href="phpUtilities/logout.php" slot="end" lines="none">
                                <ion-label color="main-bg">
                                    Log out
                                </ion-label>
                        <?php
                        }
                    }
                        ?>
                </ion-list>
            </ion-content>
        </ion-menu>
        <ion-router-outlet id="main"></ion-router-outlet>

        <ion-content [fullscreen]="true">




            <!--Page content-->

            <!-- Default Segment -->
            <ion-segment id="agent-segment" value='Manage Pub Crawls' color="main-bg">
                <ion-segment-button value="Manage Pub Crawls">
                    <ion-label>Manage</br>Pub Crawls</ion-label>
                </ion-segment-button>

                <ion-segment-button value="Manage Account">
                    <ion-label>Manage</br>Account</ion-label>
                </ion-segment-button>
            </ion-segment>

            <div id='sending-coords' style='visibility: hidden;'>
                <?= $_SESSION['currentCoords'] ?>
            </div>
            <!--<div id='chosen-pub-crawl-id' style='visibility: hidden;'>
                //$_SESSION['chosenPubCrawlId']
                </div>-->

            <ion-grid id="grid-dashboard">
                <ion-row>
                    <ion-col size-md="8" offset-md='2'>
                        <ion-card class="card-container">
                            <!----------------------------------->
                            <!--Header of card: manage account-->
                            <div id="manage-agent-account">
                                <ion-grid>
                                    <row>
                                        <h1 class="dashboard-bold-text">Manage your account</h1>
                                        <ion-button href="#" fill="clear" color="main-bg" class="dashboard-bold-text" style='text-align: center' id="change-password">
                                            Change password
                                        </ion-button>
                                        <ion-modal trigger="change-password" color="white" id="change-password-modal">
                                            <ion-toolbar>
                                                <ion-title class="ion-text-center">Confirmation</ion-title>
                                                <ion-buttons slot="end">
                                                    <ion-button onClick="dismissModalChangePassword()">
                                                        <ion-icon slot="icon-only" name="close"></ion-icon>
                                                    </ion-button>
                                                </ion-buttons>
                                            </ion-toolbar>

                                            <ion-content>
                                                <h1 class='alert-class'>Are you sure you want to change your password?</h1>
                                                <p class='alert-class'>
                                                    After pressing 'Confirm' we will send an email to your inbox.</br>
                                                </p>

                                                <div id='change-password-info'>

                                                </div>

                                                <ion-footer id="add-agent-buttons">
                                                    <ion-grid>
                                                        <ion-row>
                                                            <ion-col>

                                                            </ion-col>
                                                            <ion-col align-self-end>
                                                                <ion-button color='main-bg' onClick="changePassword()">
                                                                    <a style="text-decoration: none; color: var(--ion-color-white)">Confirm</a>
                                                                </ion-button>
                                                            </ion-col>
                                                            <ion-col align-self-end>
                                                                <ion-button color="white" onClick="dismissModalChangePassword()">
                                                                    <a style="text-decoration: none; color: var(--ion-color-main-bg)">Cancel</a>
                                                                </ion-button>
                                                            </ion-col>
                                                            <ion-col>

                                                            </ion-col>
                                                        </ion-row>
                                                    </ion-grid>
                                                </ion-footer>
                                            </ion-content>

                                        </ion-modal>
                                    </row>
                                </ion-grid>
                            </div>



                            <!----------------------------------->
                            <!--Header of card: manage account-->
                            <div id="manage-pubcrawl" name="manage-pubcrawl">
                                <ion-grid>
                                    <row>

                                        <?php
                                        $noCrawls = true;
                                        if ($pending->num_rows > 0) {
                                            $noCrawls = false;
                                        ?>
                                            <h1 class="dashboard-bold-text">Start today's Pub Crawl</h1>
                                            </br></br>
                                            <ion-grid>
                                                <ion-row class='ion-align-items-center dashboard-bold-text footer-central-row'>
                                                    <ion-col>Id</ion-col>
                                                    <ion-col>City</ion-col>
                                                    <ion-col>Start time</ion-col>
                                                    <ion-col>Duration</ion-col>
                                                    <ion-col>Meeting point</ion-col>
                                                    <ion-col>Stops</ion-col>
                                                    <ion-col>Durations</ion-col>
                                                    <ion-col>Enable Timeline</ion-col>
                                                    <ion-col>Start PubCrawl</ion-col>
                                                    <?php
                                                    if ($_SESSION["role"] == "Admin" || $_SESSION["role"] == "SuperAdmin") { ?>
                                                        <ion-col>Delete PubCrawl</ion-col>
                                                    <?php } ?>
                                                    <ion-col>Edit PubCrawl</ion-col>

                                                </ion-row>
                                                <?php
                                                while ($row1 = $pending->fetch_assoc()) {
                                                    $id = $row1["id"];
                                                    $city = $row1["city"];
                                                    $start_time = $row1["start_time"];
                                                    $duration = $row1["duration"];
                                                    $meeting_point = $row1["meeting_point"];
                                                    $stops = $row1["stops"];
                                                    $durations = $row1["durations"];
                                                    $geojson = $row1["geojson"];
                                                    $enable_timeline = $row1["enable_timeline"];
                                                ?>
                                                    <ion-row>
                                                        <ion-col id="id-crawl"><?= $id ?></ion-col>
                                                        <ion-col id="city-crawl"><?= $city ?></ion-col>
                                                        <ion-col id="startTime-crawl"><?= $start_time ?></ion-col>
                                                        <ion-col id="duration-crawl"><?= $duration ?></ion-col>
                                                        <ion-col id="meetingPoint-crawl"><?= $meeting_point ?></ion-col>
                                                        <ion-col name="stops-crawl"><?= $stops ?></ion-col>
                                                        <ion-col name="durations-crawl"><?= $durations ?></ion-col>
                                                        <ion-col id="enableTimeline-crawl"><?= $enable_timeline ?></ion-col>
                                                        <ion-col>
                                                            <ion-button onclick=startCrawl(<?= $id ?>) class='sign-in-button' color='main-bg'>
                                                                <a class='sign-in-button'>Start</a>
                                                            </ion-button>
                                                        </ion-col>
                                                        <?php
                                                        if ($_SESSION["role"] == "Admin" || $_SESSION["role"] == "SuperAdmin") { ?>
                                                            <ion-col>
                                                                <ion-button onclick=deleteCrawl(<?= $id ?>) class='delete-button' color='var(--ion-color-main-bg-red)'>
                                                                    <a class='delete-button'>Delete</a>
                                                                </ion-button>
                                                            </ion-col>
                                                        <?php } ?>
                                                        <ion-col>
                                                            <ion-button onclick=appearDisappearEditBox(<?= $id ?>) class='edit-button' color='var(--ion-color-main-bg-edit)'>
                                                                <a class='edit-button'>Edit</a>
                                                            </ion-button>
                                                        </ion-col>

                                                    </ion-row>
                                                <?php
                                                }
                                                ?>
                                            </ion-grid>
                                        <?php
                                        }
                                        if ($pendingStarted->num_rows > 0) {
                                            $noCrawls = false;
                                        ?>

                                            <h1 class="dashboard-bold-text">Stop today's Pub Crawl</h1>
                                            </br></br>
                                            <ion-grid>
                                                <ion-row class='ion-align-items-center dashboard-bold-text footer-central-row'>
                                                    <ion-col>Id</ion-col>
                                                    <ion-col>city</ion-col>
                                                    <ion-col>start time</ion-col>
                                                    <ion-col>duration</ion-col>
                                                    <ion-col>Meeting point</ion-col>
                                                    <ion-col>stops</ion-col>
                                                    <ion-col>durations</ion-col>
                                                    <ion-col>enable Timeline</ion-col>
                                                    <ion-col>Stop PubCrawl</ion-col>
                                                    <!--<ion-col>geojson</ion-col>-->
                                                </ion-row>
                                                <?php
                                                while ($row1 = $pendingStarted->fetch_assoc()) {
                                                    $id = $row1["id"];
                                                    $city = $row1["city"];
                                                    $start_time = $row1["start_time"];
                                                    $duration = $row1["duration"];
                                                    $meeting_point = $row1["meeting_point"];
                                                    $stops = $row1["stops"];
                                                    $durations = $row1["durations"];
                                                    $enable_timeline = $row1["enable_timeline"];
                                                    //$geojson = $row1["geojson"];
                                                ?>
                                                    <ion-row>
                                                        <ion-col><?= $id ?></ion-col>
                                                        <ion-col><?= $city ?></ion-col>
                                                        <ion-col><?= $start_time ?></ion-col>
                                                        <ion-col><?= $duration ?></ion-col>
                                                        <ion-col><?= $meeting_point ?></ion-col>
                                                        <ion-col><?= $stops ?></ion-col>
                                                        <ion-col><?= $durations ?></ion-col>
                                                        <ion-col><?= $enable_timeline ?></ion-col>
                                                        <!--<ion-col>$geojson</ion-col>-->
                                                        <ion-col>
                                                            <ion-button onclick=stopCrawl(<?= $id ?>) class='sign-in-button' color='main-bg'>
                                                                <a class='sign-in-button'>Stop</a>
                                                            </ion-button>
                                                        </ion-col>
                                                    </ion-row>
                                                <?php
                                                }
                                                ?>
                                            </ion-grid>
                                        <?php
                                        }
                                        if ($noCrawls) {
                                        ?>
                                            </br></br>
                                            <h1 class="dashboard-bold-text">No Pub Crawl scheduled for today</h1>
                                        <?php
                                        }
                                        ?>

                                    </row>
                                </ion-grid>
                            </div>
                        </ion-card>
                        <div id="editPubcrwal" style="display: none;">
                            <ion-card>
                                <ion-row>
                                    <ion-col size-md="6" offset-md='3'>
                                        <ion-card class="card-container">
                                            <!--Header of card-->
                                            <!-- Header of card: selected-planner -->
                                            <div id="selected-planner">
                                                <!--class='ion-margin-vertical ion-text-center'-->
                                                <h1 class="dashboard-bold-text">Edit Pub Crawl </h1>
                                                <p id="editPubcrwalID" style="display:none"></p>
                                                <p id="previousDate" style="display:none"></p>
                                                <ion-grid id="grid-dashboard">
                                                    </br>
                                                    <ion-row>
                                                        <ion-col class="ion-text-center">
                                                            Date & Time
                                                            <!-- Datetime in popover with input -->
                                                            <ion-item>
                                                                <ion-input id="date-input-2" placeholder="dd/mm/yyyy, hh:mm"></ion-input>
                                                                <ion-button slot="end" fill="clear" id="open-date-input-2">
                                                                    <ion-icon icon="calendar"></ion-icon>
                                                                </ion-button>
                                                                <ion-popover trigger="open-date-input-2" show-backdrop="false">
                                                                    <ion-datetime id="popover-datetime-2"></ion-datetime>
                                                                </ion-popover>
                                                            </ion-item>
                                                        </ion-col>
                                                    </ion-row>
                                                    </br>
                                                    <ion-row>
                                                        <ion-col class="ion-text-center">
                                                            Duration
                                                            <!-- Datetime in popover with input -->
                                                            <ion-item>
                                                                <ion-input id="duration" placeholder="Duration in hours" type='number'></ion-input>
                                                            </ion-item>
                                                        </ion-col>
                                                    </ion-row>
                                                    </br>
                                                    <ion-row>
                                                        <ion-col class="ion-text-center">
                                                            City *(read only)
                                                            <!-- Datetime in popover with input -->
                                                            <ion-item>
                                                                <?php
                                                                if ($_SESSION["role"] == "Admin") { ?>
                                                                    <ion-input id="city" placeholder="Insert Pub Crawl city" value=<?= $cityName ?> readonly></ion-input>
                                                                <?php } else if ($_SESSION["role"] == "SuperAdmin") { ?>
                                                                    <ion-input onkeyup="suggestCity(this)" id="city" placeholder="Insert Pub Crawl city" readonly></ion-input>
                                                                <?php }
                                                                ?>
                                                            </ion-item>
                                                            <ion-list id="city-suggestions">

                                                            </ion-list>
                                                        </ion-col>
                                                    </ion-row>
                                                    </br>
                                                    <ion-row>
                                                        <ion-col>
                                                            Meeting point
                                                        </ion-col>
                                                    </ion-row>
                                                    <ion-row>
                                                        <ion-col>
                                                            <ion-item>
                                                                <ion-input onkeyup="suggestMeetingPoint(this,<?= $cityID ?>)" id="meeting-point" placeholder="Choose meeting point"></ion-input>
                                                            </ion-item>
                                                            <!-- List of Text Items -->
                                                            <ion-list id="meeting-point-suggestions">

                                                            </ion-list>
                                                        </ion-col>
                                                    </ion-row>
                                                </ion-grid>

                                                </br>

                                                <ion-grid>
                                                    <ion-col size-md="6" offset-md='3'>
                                                        <div id='pubcrawl-stops'>
                                                            <ion-row>
                                                                <ion-col>
                                                                    Stops
                                                                </ion-col>
                                                            </ion-row>

                                                            <ion-item>
                                                                <ion-input class='stops' onkeyup='suggestStop(this, 1,<?= $cityID ?>)' id='stop-1' placeholder='Choose Stop #1'></ion-input>
                                                            </ion-item>
                                                            <!-- List of Text Items -->
                                                            <ion-list id='stops-suggestions-1'>

                                                            </ion-list>
                                                            <ion-col>
                                                                <ion-item>
                                                                    <ion-input id='lat-stop-1' placeholder='Latitude stop #1'></ion-input>
                                                                </ion-item>
                                                            </ion-col>
                                                            <ion-col>
                                                                <ion-item>
                                                                    <ion-input id='lon-stop-1' placeholder='Longitude stop #1'></ion-input>
                                                                </ion-item>
                                                            </ion-col>

                                                            <!-- Time staying in pub in minutes -->
                                                            <ion-item>
                                                                <ion-input class='pub-stop-time' id='pub-stop-time-1' type="text" placeholder="Time staying in this pub (minutes)"></ion-input>
                                                            </ion-item>
                                                            </br></br>


                                                            <ion-item>
                                                                <ion-input class='stops' onkeyup='suggestStop(this, 2,<?= $cityID ?>)' id='stop-2' placeholder='Choose Stop #2'></ion-input>
                                                            </ion-item>
                                                            <!-- List of Text Items -->
                                                            <ion-list id='stops-suggestions-2'>

                                                            </ion-list>

                                                            <ion-col>
                                                                <ion-item>
                                                                    <ion-input id='lat-stop-2' placeholder='Latitude stop #2'></ion-input>
                                                                </ion-item>
                                                            </ion-col>
                                                            <ion-col>
                                                                <ion-item>
                                                                    <ion-input id='lon-stop-2' placeholder='Longitude stop #2'></ion-input>
                                                                </ion-item>
                                                            </ion-col>

                                                            <!-- Time staying in pub in minutes -->
                                                            <ion-item>
                                                                <ion-input class='pub-stop-time' id='pub-stop-time-2' type="text" placeholder="Time staying in this pub (minutes)"></ion-input>
                                                            </ion-item>
                                                            </br></br>

                                                            <ion-item>
                                                                <ion-input class='stops' onkeyup='suggestStop(this, 3,<?= $cityID ?>)' id='stop-3' placeholder='Choose Stop #3'></ion-input>
                                                            </ion-item>
                                                            <!-- List of Text Items -->
                                                            <ion-list id='stops-suggestions-3'>

                                                            </ion-list>
                                                            <ion-col>
                                                                <ion-item>
                                                                    <ion-input id='lat-stop-3' placeholder='Latitude stop #3'></ion-input>
                                                                </ion-item>
                                                            </ion-col>
                                                            <ion-col>
                                                                <ion-item>
                                                                    <ion-input id='lon-stop-3' placeholder='Longitude stop #3'></ion-input>
                                                                </ion-item>
                                                            </ion-col>


                                                            <!-- Time staying in pub in minutes -->
                                                            <ion-item>
                                                                <ion-input class='pub-stop-time' id='pub-stop-time-3' type="text" placeholder="Time staying in this pub (minutes)"></ion-input>
                                                            </ion-item>
                                                            </br></br>

                                                            <ion-item>
                                                                <ion-input class='stops' onkeyup='suggestStop(this, 4,<?= $cityID ?>)' id='stop-4' placeholder='Choose Stop #4'></ion-input>
                                                            </ion-item>
                                                            <!-- List of Text Items -->
                                                            <ion-list id='stops-suggestions-4'>

                                                            </ion-list>
                                                            <ion-col>
                                                                <ion-item>
                                                                    <ion-input id='lat-stop-4' placeholder='Latitude stop #4'></ion-input>
                                                                </ion-item>
                                                            </ion-col>
                                                            <ion-col>
                                                                <ion-item>
                                                                    <ion-input id='lon-stop-4' placeholder='Longitude stop #4'></ion-input>
                                                                </ion-item>
                                                            </ion-col>
                                                            <ion-item>
                                                                <ion-input class='pub-stop-time' id='pub-stop-time-4' type="text" placeholder="Time staying in this pub (minutes)"></ion-input>
                                                            </ion-item>
                                                            </br></br>


                                                        </div>
                                                    </ion-col>
                                                </ion-grid>
                                                </br>

                                                <ion-grid>
                                                    <ion-row>
                                                        <ion-col>

                                                        </ion-col>
                                                        <ion-col align-self-end>
                                                            <ion-button color="main-bg" onClick="confirmedPubCrawlPlanning()">
                                                                <a style="text-decoration: none; color: var(--ion-color-white)">Confirm</a>
                                                            </ion-button>
                                                        </ion-col>
                                                        <ion-col align-self-end>
                                                            <ion-button color="white" onClick="cancelCard()">
                                                                <a style="text-decoration: none; color: var(--ion-color-main-bg)">Cancel</a>
                                                            </ion-button>
                                                        </ion-col>
                                                        <ion-col>

                                                        </ion-col>
                                                    </ion-row>
                                                    <div id='schedule-pub-error'>

                                                    </div>
                                                </ion-grid>
                                            </div>

                                        </ion-card>
                                    </ion-col>
                                </ion-row>
                            </ion-card>
                        </div>
                    </ion-col>
                </ion-row>
            </ion-grid>

        </ion-content>


    </ion-app>

    <script src='ManageCrawling.js'></script>

</body>

</html>