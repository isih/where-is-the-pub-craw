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

// $pending = mysqli_query($link, "SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline FROM
//     pubcrawls p WHERE
//     TIMESTAMPDIFF(HOUR, DATE(p.start_time), '$currentDate') < p.duration  AND p.status = 'Ready'");


// $pendingStarted = mysqli_query($link, "SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline FROM
//     pubcrawls p WHERE
//     TIMESTAMPDIFF(HOUR, DATE(p.start_time), '$currentDate') < p.duration AND p.status = 'Started'");


    $pending = mysqli_query($link,"SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline  FROM
    pubcrawls p WHERE
    DATE(p.start_time) = '$currentDate' AND p.status = 'Ready'");


    $pendingStarted = mysqli_query($link,"SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline FROM
    pubcrawls p WHERE
    DATE(p.start_time) = '$currentDate' AND p.status = 'Started'");

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
                    <a id="main-logo" href="index.php">
                        <img src="imgs/logo.webp" alt="logo" />
                    </a>
                </ion-buttons>

                <?php
                if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
                    if ($_SESSION["role"] === 'Admin') {
                ?>
                    <ion-title id='title' size="large" class="ion-text-center">
                        Admin
                    </ion-title>
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
                }}
                    ?>


                   
            </ion-toolbar>
        </ion-header>

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
                    <ion-col size-md="6" offset-md='3'>
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
                            <div id="manage-pubcrawl">
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
                                                    <ion-col>city</ion-col>
                                                    <ion-col>start time</ion-col>
                                                    <ion-col>duration</ion-col>
                                                    <ion-col>Meeting point</ion-col>
                                                    <ion-col>stops</ion-col>
                                                    <ion-col>durations</ion-col>
                                                    <ion-col>enable Timeline</ion-col>
                                                    <!-- <ion-col>Last Visited Place</ion-col> -->
                                                    <ion-col>Start PubCrawl</ion-col>

                                                    <!--<ion-col>geojson</ion-col>"-->
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
                                                    // $lastVisitedPlace = $row1["lastVisitedPlace"]
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
                                                        <!-- <ion-col> $lastVisitedPlace</ion-col> -->
                                                        <ion-col>
                                                            <ion-button onclick=startCrawl(<?= $id ?>) class='sign-in-button' color='main-bg'>
                                                                <a class='sign-in-button'>Start</a>
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
                    </ion-col>
                </ion-row>
            </ion-grid>

        </ion-content>


    </ion-app>

    <script src='ManageCrawling.js'></script>

</body>

</html>