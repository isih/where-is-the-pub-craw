<?php
session_start();
//require_once "../config.php"; 
require_once "config.php";

//request today's pucrawl coordinates
$currentDate = date('Y-m-d H:i:s');

$cityName = "";
$cityLongitude = "";
$cityLatitude = "";
if (isset($_GET['id'])) {
    $cityid = htmlspecialchars($_GET['id']);
} else {
    $cityid = 1;

    /*header("Location: https://whereisthepubcrawl.com/");
    exit();*/
}
$findCityName = mysqli_query($link, "SELECT c.name, c.longitude, c.latitude FROM
    city c WHERE c.id = $cityid");
if ($findCityName->num_rows > 0) {
    while ($row1 = $findCityName->fetch_assoc()) {
        $cityName = $row1["name"];
        $cityLongitude = $row1["longitude"];
        $cityLatitude = $row1["latitude"];
    }
}

//only shows started pubcrawls
$pendingStarted = mysqli_query($link, "
        SELECT *
        FROM pubcrawls p
        WHERE p.start_time > '$currentDate' AND p.city = '$cityName' AND p.status = 'Ready'
        ORDER BY p.start_time DESC
    ");

$start_time = '';
$meeting_point = '';
if ($pendingStarted->num_rows > 0) {

    while ($row = $pendingStarted->fetch_assoc()) {
        $start_time = $row["start_time"];
        $meeting_point = $row["meeting_point"];
    }
}

?>

<script>
    console.log(performance.navigation.type)
    if (performance.navigation.type == 1) {
        window.location.href = 'http://localhost:8080/witp-staging/city.php?id=' + <?= $cityid ?>;
    }
</script>


<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8" />

    <title>Not Crawling</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />-->

    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />

    <link rel="stylesheet" href="App.css" />
    <!--<script src="https://unpkg.com/@mui/material@latest/umd/material-ui.development.js"></script>-->
</head>

<body>

    <ion-app>
        <ion-header>
            <ion-toolbar id="toolbar" color="white">

                <ion-buttons slot="start">
                    <a id="main-logo" href="city.php">
                        <img src="imgs/logo.webp" alt="logo" />
                    </a>
                </ion-buttons>

                <ion-title id='title' size="large" class="ion-text-center">
                    <!--It is possible to switch interface to these values: 'alert' (default), 'popover', 'action-sheet'-->
                    <ion-select [(ngModel="cityOptions" )] name="cityDropdown" id="cityDropdownMenu" placeholder="<?= $cityName ?>" interface="popover">
                        <ion-select-option name="cityChoice" value="Malaga">Malaga</ion-select-option>
                        <ion-select-option name="cityChoice" value="Granada">Granada</ion-select-option>
                    </ion-select>
                </ion-title>
                <script>
                    function focusToCity() {
                        let x = document.getElementById("cityDropdownMenu");
                        let selectedCity = x.value;
                        var data = {};
                        data.name = selectedCity;
                        $.ajax({
                            url: 'phpUtilities/findCityLonLat.php',
                            type: 'POST',
                            data: data,

                            success: (output) => {
                                let cityCoords = output.split(';');
                                $cityLatitude = cityCoords[1];
                                $cityLongitude = cityCoords[0];
                                if (selectedCity == "Malaga") {
                                    $cityid = 1;
                                } else if (selectedCity == "Granada") {
                                    $cityid = 2;
                                }
                                window.location.href = "http://localhost:8080/witp-staging/city.php?id=" + $cityid;
                                //window.location.href = "https://whereisthepubcrawl.com/city.php?id=" + $cityid;
                            }

                        });
                    }
                    document.getElementById('cityDropdownMenu').addEventListener('ionChange', focusToCity);
                </script>

                <ion-button slot="end" color="main-bg" class="menuBtn-smallScreen" style="width:70px;">
                    <ion-menu-button menu="main-menu"></ion-menu-button>
                </ion-button>

                <!-- Item as an Anchor -->
                <?php
                if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
                    if ($_SESSION["role"] === 'Admin' || $_SESSION["role"] === 'SuperAdmin') {
                ?>
                        <ion-item href="dashboard.php" slot="end" lines="none" class="bigScreen">
                            <ion-label color="main-bg">
                                Dashboard
                            </ion-label>
                        </ion-item>
                    <?php
                    } else if ($_SESSION["role"] === 'Agent') {
                    ?>
                        <ion-item href="manageCrawling.php" slot="end" lines="none" class="bigScreen">
                            <ion-label color="main-bg">
                                Manage PubCrawl
                            </ion-label>
                        </ion-item>
                    <?php
                    }
                } else {
                    ?>
                    <ion-item href="signin.php" slot="end" lines="none" class="bigScreen">
                        <ion-label color="main-bg">
                            Sign in
                        </ion-label>
                    </ion-item>
                <?php
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
                            <ion-item href="dashboard.php" slot="end" lines="none">
                                <ion-label color="main-bg">
                                    Dashboard
                                </ion-label>
                            </ion-item>
                        <?php
                        } else if ($_SESSION["role"] === 'Agent') {
                        ?>
                            <ion-item href="manageCrawling.php" slot="end" lines="none">
                                <ion-label color="main-bg">
                                    Manage PubCrawl
                                </ion-label>
                            </ion-item>
                        <?php
                        }
                    } else {
                        ?>
                        <ion-item href="signin.php" slot="end" lines="none">
                            <ion-label color="main-bg">
                                Sign in
                            </ion-label>
                        </ion-item>
                    <?php
                    }
                    ?>
                </ion-list>
            </ion-content>
            <ion-router-outlet id="main"></ion-router-outlet>
        </ion-menu>


        <div id="show-bg">
            <div class="not-crawling-text-container">
                <h1 class="not-crawling-text">
                    We are <span class="not-crawling-highlight">NOT</span> crawling at the moment
                </h1>
                </br>
                <h2 class="not-crawling-text">
                    <!--Gather this info (time and place) through the DB-->
                    <?php
                    if ($start_time == '' || $meeting_point == '') {
                    ?>
                        There are no scheduled pubcrawls.
                    <?php
                    } else {
                    ?>
                        The next crawl starts on <?= $start_time ?> at <?= $meeting_point ?>
                    <?php
                    }
                    ?>
                </h2>
                </br>
                <h3 class="not-crawling-text">
                    You can visit the Pub Crawl
                    <a href="https://pubcrawlmalaga.com/" class="not-crawling-highlight">
                        website
                    </a>
                    for more information or you can
                    <a href='https://fareharbor.com/embeds/book/southtours/items/148437/calendar/2022/03/?flow=no&full-items=yes' class="not-crawling-highlight">
                        book now
                    </a>

                </h3>
            </div>
        </div>


        <ion-content [fullscreen]="true">

            <!--Page content-->

        </ion-content>


    </ion-app>

    <script src='App.js'>
        // if (window.location.reload() == true) {
        //     location.href = "http://localhost:8080/witp-staging/city.php?id=1"
        // }
    </script>


</body>

</html>