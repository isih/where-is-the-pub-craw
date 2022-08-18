<?php
session_start();
//require_once "../config.php"; 
require_once "config.php";

//request today's pucrawl coordinates
$currentDate = date("Y-m-d H:i:s");


//check if there are pubcrawls to be stopped
$allStartedCrawls = mysqli_query($link, "SELECT p.id, p.start_time, p.duration, p.enable_timeline, p.lastVisitedPlace FROM
    pubcrawls p WHERE p.status = 'Started'");

//$crawlingDateFinish = '';
//$current = '';
/*
    $crawlingDateHours = '';
    $currentDateHours = '';
    */
//$lastVisitedPlace = -1;
$duration = '';
$start_time = '';
if ($allStartedCrawls->num_rows > 0) {
    while ($row1 = $allStartedCrawls->fetch_assoc()) {
        $pubId = $row1["id"];
        $start_time = $row1["start_time"];
        $duration = $row1["duration"];

        //add duration in hours to complete datetime of pubcrawl, then confront the datetimes
        $crawlingDateFinish = new DateTime($start_time);
        $crawlingDateFinish->add(new DateInterval('PT' . $duration . 'H'));

        //$crawlingDateFinish = $time->format('Y-m-d H:i:s');
        $current = new DateTime($currentDate);

        if ($current > $crawlingDateFinish) {
            //stop pubcrawl
            include_once "phpUtilities/stopCrawl.php";
        }
        /*
            $crawlingDateHours = date('H', strtotime($start_time));
            $currentDateHours = date('H', strtotime($currentDate));

            if ($currentDateHours>($crawlingDateHours+$duration)) {
                //stop pubcrawl
                include_once "phpUtilities/stopCrawl.php";
            }
*/
    }
}


//only shows started pubcrawls
$pendingStarted = mysqli_query($link, "SELECT p.id, p.geojson, p.start_time, p.duration, p.durations, p.enable_timeline, p.lastVisitedPlace FROM
    pubcrawls p WHERE
    DATE(p.start_time) <= '$currentDate' AND p.status = 'Started'");


$pubs = '';
$durations = '';
$enable_timeline = '';
$pudId = '';
$lastVisitedPlace =  '';
if ($pendingStarted->num_rows > 0) {
    while ($row1 = $pendingStarted->fetch_assoc()) {
        $geojson = $row1["geojson"];
        $pubId = $row1["id"];
        $pubs = $geojson;
        $durations = $row1["durations"];
        $enable_timeline = $row1["enable_timeline"];
        $lastVisitedPlace = $row1["lastVisitedPlace"];
    }
}
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8" />

    <title>WhereIsThePubCrawl</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!--Import here static js scripts created with Geojson-->
    <script src="pubcrawl-geojson/test-marker.js"></script>
    <script src="pubcrawl-geojson/test-no-marker.js"></script>
    <script src="pubcrawl-geojson/testCrawl.js"></script>
    <!--<script src="pubcrawl-geojson/testAnotherSmallCrawl.js"></script>-->

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Leaflet geolocation plugin -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script>

    <!-- PolyLine plugin for Leaflet -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>

    <!--Leaflet clustering plugin-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>


    <!-- MapBox dependencies -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css' rel='stylesheet' />
    <!-- MapBox plugin to display route -->
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.css" type="text/css">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <?php
    include 'common_header.php';
    ?>
</head>

<body>

    <ion-app>
        <ion-header>
            <ion-toolbar id="toolbar" color="white">

                <ion-buttons slot="start">
                    <a id="main-logo" href="index.php">
                        <img src="imgs/logo.webp" alt="logo" />
                    </a>
                </ion-buttons>

                <ion-title id='title' size="large" class="ion-text-center">
                    <!--It is possible to switch interface to these values: 'alert' (default), 'popover', 'action-sheet'-->
                    <ion-select placeholder="Malaga" interface="popover">
                        <ion-select-option value="Malaga">Malaga</ion-select-option>
                        <ion-select-option value="Granada">Granada</ion-select-option>
                    </ion-select>
                </ion-title>

                <!-- Item as an Anchor -->
                <?php
                if (isset($_SESSION["loggedin"]) && isset($_SESSION["role"])) {
                    if ($_SESSION["role"] === 'Admin') {
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
            </ion-toolbar>
        </ion-header>

        <ion-content [fullscreen]="true">
            <div id="map"></div>

        </ion-content>


        <!-- Footer without a border -->
        <ion-footer class="ion-no-border" background-color="main-bg" id="desktop-footer">
            <ion-grid>
                <!--<ion-row class="ion-align-items-start">
                    <ion-col class="previous-stop">
                        Last stop
                    </ion-col>
                    <ion-col class="ion-text-center minutes-and-phrase">
                        Time until we reach the next pub:
                    </ion-col>
                    <ion-col class="ion-text-right next-stop">
                        Next stop
                    </ion-col> 
                </ion-row>-->

                <ion-row class="ion-align-items-center" id="footer-central-row" style="padding:50px">
                    <!-- <ion-col class='last-pub previous-stop'>
                        The Irish Pub -->
                    <!--To calculate with JS using GPS info-->
                    <!-- </ion-col> -->
                    <ion-col class="ion-text-center" id="footer-minutes">
                        <!--5 To calculate with JS using GPS info-->
                        <div class='time-until-next-pub'></div>
                    </ion-col>
                    <!-- <ion-col class="ion-text-right next-pub next-stop">
                        Camden lock -->
                    <!--To calculate with JS using GPS info-->
                    <!-- </ion-col> -->

                    <!-- </ion-row>

                <ion-row class="ion-align-items-end">
                    <ion-col class="previous-stop date-previous-stop">
                        22:45 - 23:15
                        <!-To calculate with JS using GPS info-->
                    <!-- </ion-col>
                    <ion-col class="ion-text-center minutes-and-phrase">
                        minutes
                    </ion-col>
                    <ion-col class="ion-text-right next-stop date-next-stop">
                        22:30 - 0:30
                        <!-To calculate with JS using GPS info-->
                    <!-- </ion-col>

                </ion-row>
            </ion-grid>
        </ion-footer>    -->

                    <!-- Sheet Modal: mobile footer -->
                    <ion-footer id="mobile-footer">
                        <ion-modal is-open="true" id="sheet-modal" class="mobile-footer" background="main-bg">
                            </br>
                            <ion-grid>
                                <ion-row>
                                    <ion-col size="12" class="ion-text-center minutes-and-phrase">
                                        Time until we reach the next pub:
                                    </ion-col>
                                    <ion-col size="12" class="ion-text-center" id="footer-minutes">
                                        <!--5 To calculate with JS using GPS info-->
                                        <div class='time-until-next-pub'></div>
                                    </ion-col>
                                    <ion-col size="12" class="ion-text-center minutes-and-phrase">
                                        minutes
                                    </ion-col>
                                </ion-row>

                                <ion-row class="ion-align-items-start ion-text-left muted previous-stop">
                                    <ion-col class="mobile-footer-secondary-text">
                                        Last stop
                                    </ion-col>
                                    <ion-col>

                                    </ion-col>
                                </ion-row>
                                <ion-row class="ion-align-items-start ion-text-left muted previous-stop">
                                    <ion-col class="mobile-footer-bold-text last-pub">
                                        The Irish Pub
                                        <!--To calculate with JS using GPS info-->
                                    </ion-col>
                                    <ion-col class="ion-text-right mobile-footer-bold-text date-previous-stop">
                                        22:45 - 23:15
                                        <!--To calculate with JS using GPS info-->
                                    </ion-col>
                                </ion-row>

                                <ion-row class="ion-align-items-start ion-text-left">
                                    <ion-col class="mobile-footer-secondary-text">
                                        Current stop
                                    </ion-col>
                                    <ion-col>

                                    </ion-col>
                                </ion-row>
                                <ion-row class="ion-align-items-start ion-text-left">
                                    <ion-col class="mobile-footer-bold-text current-pub">
                                        Camden Lock
                                    </ion-col>
                                    <ion-col class="ion-text-right mobile-footer-bold-text time-current-pub">
                                        23:30 - 0:30
                                    </ion-col>
                                </ion-row>

                                <ion-row class="ion-align-items-start ion-text-left muted next-stop">
                                    <ion-col class="mobile-footer-secondary-text ion-text-left">
                                        Next stop
                                    </ion-col>
                                    <ion-col>

                                    </ion-col>
                                </ion-row>
                                <ion-row class="ion-align-items-start ion-text-left muted next-stop">
                                    <ion-col class="mobile-footer-bold-text next-pub">
                                        Café Merced 14
                                    </ion-col>
                                    <ion-col class="ion-text-right mobile-footer-bold-text date-next-stop">
                                        0:45 - 1:30
                                    </ion-col>
                                </ion-row>
                            </ion-grid>
                        </ion-modal>
                    </ion-footer>


    </ion-app>
    <input id="pubs" name="pubs" type="hidden" value='<?= $pubs ?>'>
    <input id="durations" name="durations" type="hidden" value='<?= $durations ?>'>
    <input id="start_time" name="start_time" type="hidden" value='<?= $start_time ?>'>
    <input id="enable_timeline" name="enable_timeline" type="hidden" value='<?= $enable_timeline ?>'>

    <script src='App.js'></script>


    <?php
    /* *********************************************************************************************
    ************************************************************************************************
    ********************************************************************************************
    ************************************************************************************************
    ********************************************************************************************
    ************************************************************************************************
    SLUG SLUG SLUG SLUG SLUG Start
    functions.php file is converting the string to the slug...
    */
    //Experimenting with Slug
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBNAME', 'southtou_witp');
    $db = new PDO("mysql:host=".DBHOST. ";dbname=" .DBNAME, DBUSER, DBPASS);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $titleM = "malaga pub crawl malaga bar crawl";
    $slug = slugs($titleM);
    $stmt = $db->prepare('INSERT INTO pubcrawls (slug) VALUES (:slug)');
    $stmt->execute(array(
        ':slug' => $slug
    ));

        /* *********************************************************************************************
    ************************************************************************************************
    ********************************************************************************************
    ************************************************************************************************
    ********************************************************************************************
    ************************************************************************************************
    SLUG SLUG SLUG SLUG SLUG END
    */
    ?>

    <!--Leaflet Map-->
    <script>
        //mapbox token
        mapboxgl.accessToken = 'pk.eyJ1Ijoic291dGh0b3Vyc2l0IiwiYSI6ImNsMmdmYnp6MzAzZm0zZG5ucmZ3emJ4YXIifQ.Jm7ZjYNVrMd4UuWV3wl6eg';
        
        // initialize Leaflet
        var map = L.map('map', {
            center: [36.7199, -4.4231], //Malaga center
            zoom: 15
        });

        /*
        //mapbox tile service
        var map = new mapboxgl.Map({
            container: 'map',
            center: [36.7199,-4.4231],//Malaga center
            zoom: 15
        });
         */
        //setTimeout(function () { map.invalidateSize() }, 1000);


        // add the OpenStreetMap tiles
        /*L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
        }).addTo(map);
        */
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://carto.com/">carto.com contributors</a>'
        }).addTo(map);


        // show the scale bar on the lower left corner
        L.control.scale({
            imperial: true,
            metric: true
        }).addTo(map);

        var myIcon = L.icon({
            iconUrl: 'imgs/marker.png',
            iconSize: [60, 95],
            iconAnchor: [33, 94],
            popupAnchor: [-3, -76],
        });
        //agent-position-marker
        var agentPos = L.icon({
            iconUrl: 'imgs/GPS-9.png',
            iconSize: [25, 25],
            /*iconAnchor: [33, 94]*/
            /*popupAnchor: [-3, -76],*/
        });

        //uncomment to activate clustering:
        //var markers = L.markerClusterGroup();

        //get coordinates of the pubs
        var pubs = document.getElementById('pubs').value;
        //get durations
        var rawDurations = document.getElementById('durations').value;
        var durations = rawDurations.split(',');

        var enableTimeline = document.getElementById('enable_timeline').value;
        var agentPositionMarker;
        var nextBarMarker;
        var pubLoc;
        let visitedBars = -1
        let lastPlaceVariable = <?=$lastVisitedPlace?>
        //console.log(pubs);

        /**
         * function to determine if a Point is in the radious of another Point (interest)
         * @param point {Object} {latitude: Number, longitude: Number}
         * @param interest {Object} {latitude: Number, longitude: Number}
         * @param kms {Number}
         * @returns {boolean}
         */

        function findShortestDistance(pubCoords) {
            let R = 6371;
            let deg2rad = (n) => {
                return Math.tan(n * (Math.PI / 180))
            };
            let mind;

            for (i = 0; i < pubCoords.length; i++) {
                for (j = 0; j < pubCoords.length; j++) {
                    if (i != j) {
                        let dLat = deg2rad(pubCoords[i].geometry.coordinates[1] - pubCoords[j].geometry.coordinates[1]);
                        let dLon = deg2rad(pubCoords[i].geometry.coordinates[0] - pubCoords[j].geometry.coordinates[0]);

                        let a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(deg2rad(pubCoords[i].geometry.coordinates[1])) * Math.cos(deg2rad(pubCoords[j].geometry.coordinates[1])) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
                        let c = 2 * Math.asin(Math.sqrt(a));
                        let d = R * c;
                        console.log("Distance in km: " + d);
                        if (mind == undefined || d < mind) {
                            mind = d;
                        }
                        console.log("minD: " + mind)
                    }

                }
            }
            //console.log("mind: " + mind)
            return mind

        }

        function withinRadius(point, interest, kms) {
            let R = 6371;
            let deg2rad = (n) => {
                return Math.tan(n * (Math.PI / 180))
            };

            let dLat = deg2rad(interest.latitude - point.latitude);
            let dLon = deg2rad(interest.longitude - point.longitude);

            let a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(deg2rad(point.latitude)) * Math.cos(deg2rad(interest.latitude)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
            let c = 2 * Math.asin(Math.sqrt(a));
            let d = R * c;
            //console.log("Distance in km: " + d);
            return (d <= kms);
        }

        function addMinutes(date, minutes) {
            return new Date(date.getTime() + minutes * 60000);
        }

        /**
         * function to determine if current coords have to be considered (based on durations).
         * True if not in duration anymore
         * @param agentLat {Number} agent latitude
         * @param agentLon {Number} agent longitude
         * @param duration {Number} minutes to spend on this pub
         * @returns {boolean}
         */
        function considerGPS(agentLat, agentLon, duration, startEndCurrentPubDate) {
            let res = false;
            //before you have to save in an array the start and finish time of all the pubs, so that it can be displayed on the screen - OK (startEndAllPubs)
            //then get the start (and finish) time of current pub.
            let startTimePub = startEndCurrentPubDate.start;
            let endTimePub = startEndCurrentPubDate.end;
            //get current datetime
            let currentDateTime = new Date();
            //if current datetime is between start and finish, return false (-->Don't consider GPS). Otherwise true.
            if (currentDateTime.getTime() > startTimePub.getTime() && currentDateTime.getTime() < endTimePub.getTime()) {
                res = false;
            } else {
                res = true;
            }

            return res;
        }



        function displaypub(i, pubCoords, agentCoords, flag, startEndCurrentPub, startEndCurrentPubDate) {
            var myIcon = L.icon({
                iconUrl: 'imgs/' + (i + 1) + '.png',
                iconSize: [60, 95],
                iconAnchor: [33, 94],
                popupAnchor: [-3, -76],
            });

            if (flag == true) {
                if (agentPositionMarker != undefined) {
                    map.removeLayer(agentPositionMarker)
                }
                if (pubLoc != undefined) {
                    map.removeLayer(pubLoc)
                } //remove the previous layer and display the new one
                if (nextBarMarker != undefined) {
                    map.removeLayer(nextBarMarker)
                }
                pubLoc = L.marker({
                    lon: pubCoords[i].geometry.coordinates[0],
                    lat: pubCoords[i].geometry.coordinates[1]
                }, {
                    icon: myIcon
                });
                map.addLayer(pubLoc);
                pubLoc.bindPopup('<h2>' + pubCoords[i].properties.name + '</h2>');

            } else {
                if (pubLoc != undefined) {
                    map.removeLayer(pubLoc)
                }
                if (agentPositionMarker != undefined) {
                    map.removeLayer(agentPositionMarker)
                }
                if (nextBarMarker != undefined) {
                    map.removeLayer(nextBarMarker)
                }

                agentPositionMarker = L.marker({
                    lon: agentCoords[0],
                    lat: agentCoords[1]
                }, {
                    icon: agentPos
                });

                let x;
                if (parseInt(lastPlaceVariable) == -1) {
                    x = 0;
                } else if (parseInt(lastPlaceVariable) == 3) {
                    x = 3;
                } else {
                    x = parseInt(lastPlaceVariable) + 1;
                }


                var nextBarIcon = L.icon({
                    iconUrl: 'imgs/' + (x + 1) + '.png',
                    iconSize: [60, 95],
                    iconAnchor: [33, 94],
                    popupAnchor: [-3, -76],
                });

                console.log('x:' + x)
                nextBarMarker = L.marker({
                    lon: pubCoords[x].geometry.coordinates[0],
                    lat: pubCoords[x].geometry.coordinates[1]
                }, {
                    icon: nextBarIcon
                })
                map.addLayer(agentPositionMarker);
                agentPositionMarker.bindPopup('Current agent position');
                map.addLayer(nextBarMarker);
                nextBarMarker.bindPopup('<h2>' + pubCoords[x].properties.name + '</h2>')
            }

        }



        function checkAgentPosition(pubCoords, finalDurations, durations, map, agentPos, startEndAllPubs, startEndAllPubsDates) { //agentPos is just the marker image
            //to read the coords I have to know which agent started the pubCrawl (see DB)

            $.ajax({
                url: 'phpUtilities/getCoords.php',
                type: 'POST',
                //data: data,

                success: (output) => {
                    //display dot from coordinates (output) on the map
                    //output format:
                    //longitude;latitude
                    // console.log("outputCord: " + output)
                    let agentCoords = output.split(';');

                    var timeUntilNextPub = document.getElementsByClassName('time-until-next-pub');
                    var currentPub = document.getElementsByClassName('current-pub')[0]; //only on mobile
                    var timeCurrentPub = document.getElementsByClassName('time-current-pub')[0]; //only on mobile
                    var nextPub = document.getElementsByClassName('next-pub');
                    var lastPub = document.getElementsByClassName('last-pub');

                    //use these to show/hide elements
                    var nextStop = document.getElementsByClassName('next-stop');
                    var previousStop = document.getElementsByClassName('previous-stop');
                    var minutesAndPhrase = document.getElementsByClassName('minutes-and-phrase');

                    var dateNextStop = document.getElementsByClassName('date-next-stop');
                    var datePreviousStop = document.getElementsByClassName('date-previous-stop');

                    //current agent coordinates in agentCoods: agentCoords[0] = lon, agentCoords[1] = lat
                    //i-th pub coords
                    //let lon = pubCoords[i].geometry.coordinates[0];//longitude
                    //let lat = pubCoords[i].geometry.coordinates[1];//latitude
                    //i-th pub name
                    //pubs.features[i].properties.name;

                    //consider you are in the pub if GPS coords in 50 meter radius
                    var found = false;

                    for (let i = 0; i < pubs.features.length && !found; i++) {
                        let pubLon = pubCoords[i].geometry.coordinates[0]; //longitude
                        let pubLat = pubCoords[i].geometry.coordinates[1]; //latitude

                        let startEndCurrentPub = startEndAllPubs[i]; //use this to display in HTML
                        let startEndCurrentPubDate = startEndAllPubsDates[i];

                        let agentLat = agentCoords[1];
                        let agentLon = agentCoords[0];

                        for (let k = 0; k < nextPub.length; k++) {
                            nextPub[k].innerHTML = pubs.features[i].properties.name;
                        }
                        //console.log(nextPub)


                        //if not considering GPS set coordinates manually to current pub
                        if (enableTimeline === 'true') {
                            let consideringGPS = considerGPS(agentCoords[1], agentCoords[0], durations[i], startEndCurrentPubDate);

                            if (!consideringGPS) {
                                //set marker on map at coordinates of current pub instead of current position
                                map.removeLayer(agentPositionMarker);
                                agentPositionMarker = L.marker({
                                    lon: pubLon,
                                    lat: pubLat
                                }, {
                                    icon: agentPos
                                });
                                map.addLayer(agentPositionMarker);
                                agentPositionMarker.bindPopup('Current agent position');

                                agentLat = pubLat;
                                agentLon = pubLon;
                            }
                        }


                        if (withinRadius({
                                latitude: agentLat,
                                longitude: agentLon
                            }, {
                                latitude: pubLat,
                                longitude: pubLon
                            }, $minDistance)) { //change to bigger radius for testing

                            found = true;
                            //visitedBars = i;


                            var data = {};
                            data.lastVisitedPlace1 = i;

                            $.ajax({
                                url: 'phpUtilities/updateVisitedPlaces.php',
                                type: 'POST',
                                data: data,
                                success: (output) => {
                                    console.log(output);  
                                }
                            });

                            $.ajax({
                                url: 'phpUtilities/getLastVisitedPlace.php',
                                type: 'POST',
                                success: (output)=>{
                                    console.log("Output: " + output);
                                    let result = output;
                                    console.log("result:"+ result)
                                    lastPlaceVariable = output;
                                }
                                
                            })

                            


                            


                            displaypub(i, pubCoords, agentCoords, true, startEndCurrentPub, startEndCurrentPubDate)

                            console.log("lastVisitedVariable: "+ lastPlaceVariable)
                            for (let k = 0; k < timeUntilNextPub.length; k++) {
                                if (lastPlaceVariable == 3) {
                                    timeUntilNextPub[k].innerHTML = "We are in the last pub."
                                } else {
                                    timeUntilNextPub[k].innerHTML = "We are in " + pubCoords[i].properties.name;
                                }
                            }
                            for (let h = 0; h < minutesAndPhrase.length; h++) {
                                minutesAndPhrase[h].style.visibility = 'hidden';
                            }
                            break;


                            //set marker on map at coordinates of current pub instead of current position
                            //map.removeLayer(agentPositionMarker);
                            //agentMarkerLayerGroup.clearLayers();


                            // agentPositionMarker = L.marker({
                            //     lon: pubLon,
                            //     lat: pubLat
                            // }, {
                            //     icon: agentPos
                            // });
                            // map.addLayer(agentPositionMarker);
                            // agentPositionMarker.bindPopup('Current agent position');

                            // agentLat = pubLat;
                            // agentLon = pubLon;

                            // currentPub.innerHTML = pubs.features[i].properties.name; //currentPub is visible only on mobile
                            // //show current stop time
                            // timeCurrentPub.innerHTML = startEndAllPubs[i]; //current stop time is visible only on mobile

                            // //"time until next pub" and "minutes" are displayed
                            // for (let h = 0; h < minutesAndPhrase.length; h++) {
                            //     minutesAndPhrase[h].style.visibility = 'visible';
                            // }

                            // //set time until next pub
                            // for (let h = 0; h < timeUntilNextPub.length; h++) {
                            //     timeUntilNextPub[h].innerHTML = finalDurations[i];
                            // }

                            // //if first pub
                            // if (i == 0) {
                            //     //hide html of previous stop
                            //     for (let h = 0; h < previousStop.length; h++) {
                            //         previousStop[h].style.visibility = 'hidden';
                            //     }
                            //     //show html of next stop
                            //     for (let h = 0; h < nextStop.length; h++) {
                            //         nextStop[h].style.visibility = 'visible';
                            //     }

                            //     //show next stop time
                            //     for (let h = 0; h < dateNextStop.length; h++) {
                            //         dateNextStop[h].innerHTML = startEndAllPubs[i + 1];
                            //     }

                            //     //set next Pub name
                            //     for (let k = 0; k < nextPub.length; k++) {
                            //         nextPub[k].innerHTML = pubs.features[i + 1].properties.name;
                            //     }

                            // } else if (i == pubs.features.length - 1) { //if last pub
                            //     //hide html of next pub
                            //     for (let h = 0; h < nextStop.length; h++) {
                            //         nextStop[h].style.visibility = 'hidden';
                            //     }

                            //     //"time until next pub" and "minutes" won't be displayed
                            //     for (let h = 0; h < minutesAndPhrase.length; h++) {
                            //         minutesAndPhrase[h].style.visibility = 'hidden';
                            //     }

                            //     //show html of previous stop
                            //     for (let h = 0; h < previousStop.length; h++) {
                            //         previousStop[h].style.visibility = 'visible';
                            //     }

                            //     //change minutes to 'You're at the last pub'
                            //     for (let k = 0; k < timeUntilNextPub.length; k++) {
                            //         timeUntilNextPub[k].innerHTML = "You're at the last pub";
                            //     }

                            //     //set previous Pub name
                            //     for (let h = 0; h < lastPub.length; h++) {
                            //         lastPub[h].innerHTML = pubs.features[i - 1].properties.name;
                            //     }

                            //     //show previous stop time
                            //     for (let h = 0; h < datePreviousStop.length; h++) {
                            //         datePreviousStop[h].innerHTML = startEndAllPubs[i - 1];
                            //     }


                            // } else {
                            //     //show html of previous stop
                            //     for (let h = 0; h < previousStop.length; h++) {
                            //         previousStop[h].style.visibility = 'visible';
                            //     }

                            //     //show html of next stop
                            //     for (let h = 0; h < nextStop.length; h++) {
                            //         nextStop[h].style.visibility = 'visible';
                            //     }

                            //     //set previous Pub name
                            //     for (let h = 0; h < lastPub.length; h++) {
                            //         lastPub[h].innerHTML = pubs.features[i - 1].properties.name;
                            //     }

                            //     //show previous stop time
                            //     for (let h = 0; h < datePreviousStop.length; h++) {
                            //         datePreviousStop[h].innerHTML = startEndAllPubs[i - 1];
                            //     }

                            //     //set next Pub name
                            //     for (let k = 0; k < nextPub.length; k++) {
                            //         nextPub[k].innerHTML = pubs.features[i + 1].properties.name;
                            //     }

                            //     //show next stop time
                            //     for (let h = 0; h < dateNextStop.length; h++) {
                            //         dateNextStop[h].innerHTML = startEndAllPubs[i + 1];
                            //     }
                            // }


                        } else {
                            console.log('not in radius');
                            displaypub(i, pubCoords, agentCoords, false, startEndCurrentPub, startEndCurrentPubDate)
                            //change central text to 'Walking...'
                            let x;
                            if (lastPlaceVariable == 3) {
                                x = parseInt(lastPlaceVariable);
                            } else {
                                x = parseInt(lastPlaceVariable) + 1;
                            }
                            console.log("visitedBars: " + lastPlaceVariable + " x: " + x)

                            for (let k = 0; k < timeUntilNextPub.length; k++) {
                                timeUntilNextPub[k].innerHTML = "Walking to " + pubCoords[x].properties.name;
                            }
                            //"time until next pub" and "minutes" won't be displayed
                            for (let h = 0; h < minutesAndPhrase.length; h++) {
                                minutesAndPhrase[h].style.visibility = 'hidden';
                            }

                            // //mobile: set current stop to 'Walking...'
                            // currentPub.innerHTML = 'Walking...'; //currentPub is visible only on mobile

                        }
                    }
                }
            });
        }

        //notCrawling
        if (pubs === '') {
            //redirect to notCrawling page
            window.location.replace("notCrawling.php");

        } else { //crawling

            pubs = JSON.parse(pubs);

            //make request to mapbox API
            //call to MapBox API
            var startUrl = 'https://api.mapbox.com/directions/v5/mapbox/walking/';
            var endUrl = '?alternatives=false&annotations=duration&continue_straight=false&geometries=geojson&overview=simplified&steps=false&access_token=' + mapboxgl.accessToken;
            //preparing the url with the coordinates: <lon>,<lat>;<lon>,<lat>
            let pubCoords = pubs.features;

            $minDistance = findShortestDistance(pubCoords);
            //console.log("$minDistance"+ $minDistance)
            let finalePubCoords = '';
            for (let i = 0; i < pubCoords.length; i++) {
                let lon = pubCoords[i].geometry.coordinates[0]; //longitude
                let lat = pubCoords[i].geometry.coordinates[1]; //latitude

                if (i == pubCoords.length - 1) {
                    finalePubCoords += lon + ',' + lat;
                } else {
                    finalePubCoords += lon + ',' + lat + ';';
                }
            }

            var url = startUrl + finalePubCoords + endUrl;
            var req = new XMLHttpRequest();
            req.responseType = 'json';
            req.open('GET', url, true);
            req.onload = function() { //code to execute after request is completed
                var jsonResponse = req.response;
                //console.log(jsonResponse);
                var distance = jsonResponse.routes[0].distance * 0.001; //km
                //console.log("distance: "+distance);
                var duration = jsonResponse.routes[0].duration / 60;
                //console.log("duration: "+duration);
                var coords = jsonResponse.routes[0].geometry;
                //console.log("coords: "+JSON.stringify(coords));

                var durationsBetween = jsonResponse.routes[0].legs; //stores duration 
                var finalDurations = [];
                for (let i = 0; i < durationsBetween.length; i++) {
                    finalDurations.push(Math.round(durationsBetween[i].duration / 60)); //in minutes
                }
                //console.log("finalDurations:"+finalDurations);//actual durations


                //show pubs
                // const crawlData = L.geoJSON(pubs, {
                //     style: {
                //         color: 'black',
                //         dashArray: "10 10",
                //         weight: 3
                //     },
                //     onEachFeature: function (feature, layer) {
                //         if(feature.properties.name!==undefined){
                //             layer.bindPopup('<h2>'+feature.properties.name+'</h2>');  
                //         }
                //     },
                //     pointToLayer: function(feature, latlng) {
                //         return /*markers.addLayer(*/L.marker(latlng, {icon: myIcon})/*)*/;//uncomment to activate clustering
                //     }
                // }).addTo(map);

                //add route to map
                // const routingPath = L.geoJSON(coords, {
                //     style: {
                //         color: 'black',
                //         dashArray: "10 10",
                //         weight: 3
                //     },
                // })/*.bindPopup( function(layer) {
                //     return layer.feature.properties.name;
                // })*/.addTo(map);

                // const markers = L.markerClusterGroup({
                //     polygonOptions: {
                //         fillColor: 'var(--ion-color-main-bg-shade)',
                //         color: ' var(--ion-color-main-bg)',
                //         weight: 0.5,
                //         opacity: 1,
                //         fillOpacity: 0.5
                //     }
                // }).addLayer(crawlData);


                //uncomment to activate clustering:
                // map.addLayer(markers);


                /*
                 * Real time geolocation of the website user with plugin
                 * https://github.com/domoritz/leaflet-locatecontrol
                 */
                /*
                //UNCOMMENT TO SEE CURRENT POSITION -->IF MAP BREAKS, COMMENT OUT AGENT MARKER
                L.control.locate({
                    setView: 'never',
                    flyTo: false,
                    enableHighAccuracy: true,
                }).addTo(map).start();
                */


                //might be better to calculate dates one by one and use current datetime as starting time. But it is necessary to save previous pub start and end time

                //ho la data di inizio del pubcrawl (pubCrawlStartTime), la durata di ogni pub (durations[i]) e la durata per passare da un pub all'altro (finalDurations[x])
                //quindi partendo dalla data iniziale posso ottenere il tempo in cui il primo pub finisce.
                //ottenuta la data in cui il primo pub finisce posso aggiungere a quella data la durata a piedi da quel pub al successivo.
                //Ottengo così la data di inizio del tempo del secondo pub poi anche in questo caso aggiungo la durata da passare all'interno di quel pub e ottengo la data finale.
                //ecc...
                var startEndAllPubsDates = [];
                var pubCrawlStartTime = document.getElementById("start_time").value;
                console.log('pubCrawlStartTime: ' + pubCrawlStartTime);
                //converting YYYY-MM-DD HH:MM:SS to YYYY-MM-DDTHH:mm:ss
                //var pieces = pubCrawlStartTime.split(' ');
                //var finale = pieces[0]+'T'+pieces[1];

                var pubCrawlStartDate = new Date(Date.parse(pubCrawlStartTime));
                //console.log("pubCrawlStartDate: "+pubCrawlStartDate.toString());
                //console.log((pubCrawlStartDate instanceof Date));
                //console.log("pubCrawlStartDate text: "+ pubCrawlStartDate.getFullYear()+'-'+pubCrawlStartDate.getMonth()+'-'+pubCrawlStartDate.getDate()+' '+pubCrawlStartDate.getHours()+':'+pubCrawlStartDate.getMinutes()+':'+pubCrawlStartDate.getSeconds());

                //calculate start and end hours for each pub. Store data in array to use in checkAgentPosition
                for (let i = 0; i < pubs.features.length; i++) {
                    //let result = '';

                    let stopTimeMinutes = durations[i];
                    let endStopDate;
                    let startStopDate;
                    if (i == 0) {
                        endStopDate = addMinutes(pubCrawlStartDate, stopTimeMinutes);

                        startEndAllPubsDates.push({
                            start: pubCrawlStartDate,
                            end: endStopDate
                        });

                    } else {
                        //!!! REMEMBER: finalDurations has one element less than the pub number !!! 
                        //starts from 0, ends at i-1
                        let walkingTime = finalDurations[i - 1]; //minutes

                        let prevEndingDate = startEndAllPubsDates[i - 1];

                        //set pub starting date taking last pub ending date and adding the minutes for the walk
                        startStopDate = addMinutes(prevEndingDate.end, walkingTime);
                        //console.log((startStopDate instanceof Date));

                        //set pub ending date by adding duration minutes to start date
                        endStopDate = addMinutes(startStopDate, stopTimeMinutes);
                        //console.log((endStopDate instanceof Date));

                        startEndAllPubsDates.push({
                            start: startStopDate,
                            end: endStopDate
                        });
                    }
                }

                console.log('startEndAllPubs: \n');
                var startEndAllPubs = []; //final array to take directly data from
                for (let i = 0; i < startEndAllPubsDates.length; i++) {
                    let el = startEndAllPubsDates[i];
                    console.log("element: " + el);
                    let result = '';

                    //transform dates into string
                    let startHour = el.start.getHours();
                    if (startHour < 10) {
                        startHour = '0' + startHour;
                    }
                    let startMinutes = el.start.getMinutes();
                    if (startMinutes < 10) {
                        startMinutes = '0' + startMinutes;
                    }
                    result += startHour + ':' + startMinutes;
                    result += ' - ';

                    let endHour = el.end.getHours();
                    if (endHour < 10) {
                        endHour = '0' + endHour;
                    }
                    let endMinutes = el.end.getMinutes();
                    if (endMinutes < 10) {
                        endMinutes = '0' + endMinutes;
                    }
                    result += endHour + ':' + endMinutes;

                    startEndAllPubs.push(result);

                    console.log(result + '\n');
                }




                setTimeout(function() {
                    map.invalidateSize()
                }, 1000);
                /*
                                    var agentMarkerLayerGroup = L.layerGroup().addTo(map);
                */
                checkAgentPosition(pubCoords, finalDurations, durations, map, /*agentMarkerLayerGroup,*/ agentPos, startEndAllPubs, startEndAllPubsDates);

                sendCoordsInterval = setInterval(checkAgentPosition,
                    5000, //call every 5 seconds
                    //checkAgentPosition parameters
                    pubCoords,
                    finalDurations,
                    durations,
                    map,
                    /*agentMarkerLayerGroup,*/
                    agentPos,
                    startEndAllPubs,
                    startEndAllPubsDates
                );


                //setTimeout(function () { map.invalidateSize() }, 1000);
                //window.dispatchEvent(new Event('resize'));


                /*Real time geolocation of the website user without plugin*/
                /*
                if(!navigator.geolocation){
                    //show alert that says that user's browser doesn't support geolocation
                    console.log("Your browser doesn't support geolocation");
                } else {
                    setInterval(() => {
                        navigator.geolocation.getCurrentPosition(getPosition)
                    }, 5000);//5 second interval
                }

                var realTimeMarker, circle;

                function getPosition(position){
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;
                    var accuracy = position.coords.accuracy;

                    if(realTimeMarker){
                        map.removeLayer(realTimeMarker);
                    }
                    
                    if(circle){
                        map.removeLayer(circle);
                    }

                    realTimeMarker = L.marker([lat, lon], {icon: realTimeMarkerImage});
                    circle = L.circle([lat, lon], {radius:accuracy});

                    var featureGroup = L.featureGroup([realTimeMarker, circle]).addTo(map);

                    console.log("Your coordinate is: Lat: "+lat +" Long: "+long+ " Accuracy: "+accuracy);
                }
                */

                //map.on('load', () => {
                //setTimeout(function () { map.invalidateSize() }, 1000);
                //window.dispatchEvent(new Event('resize'));    
                //});s

            };
            req.send();

        }





        /*
                            //change line color - doesn't work yet
                            const MultiColorsPolyline = L.FeatureGroup.extend({

                                initialize: function (latlngs, options) {
                                    var copyBaseOptions = options.multiOptions.copyBaseOptions;

                                    this._layers = {};
                                    this._options = options;
                                    if (copyBaseOptions === undefined || copyBaseOptions) {
                                        this._copyBaseOptions();
                                    }

                                    this.setLatLngs(latlngs);
                                },

                                _copyBaseOptions: function () {
                                    var multiOptions = this._options.multiOptions,
                                        baseOptions,
                                        optionsArray = multiOptions.options,
                                        i, len = optionsArray.length;

                                    baseOptions = L.extend({}, this._options);
                                    delete baseOptions.multiOptions;

                                    for (i = 0; i < len; ++i) {
                                        optionsArray[i] = L.extend({}, baseOptions, optionsArray[i]);
                                    }
                                },

                                setLatLngs: function (latlngs) {
                                    var i, len = latlngs.length,
                                        multiOptions = this._options.multiOptions,
                                        optionIdxFn = multiOptions.optionIdxFn,
                                        fnContext = multiOptions.fnContext || this,
                                        prevOptionIdx, optionIdx,
                                        segmentLatlngs;

                                    this._originalLatlngs = latlngs;
                                    this._detailData = [];

                                    this.eachLayer(function (layer) {
                                        this.removeLayer(layer);
                                    }, this);

                                    for (i = 1; i < len; ++i) {
                                        optionIdx = optionIdxFn.call(
                                            fnContext, latlngs[i], latlngs[i - 1], i, latlngs);

                                        if (i === 1) {
                                            segmentLatlngs = [latlngs[0]];
                                            prevOptionIdx = optionIdxFn.call(fnContext, latlngs[0], latlngs[0], 0, latlngs);
                                        }

                                        segmentLatlngs.push(latlngs[i]);
                                        if (prevOptionIdx !== optionIdx || i === len - 1) {
                                            if (typeof multiOptions.options === "function") {
                                                this.addLayer(L.polyline(segmentLatlngs, multiOptions.options(prevOptionIdx)));
                                            } else {
                                                this.addLayer(L.polyline(segmentLatlngs, multiOptions.options[prevOptionIdx]));
                                            }
                                            this._detailData.push(segmentLatlngs);
                                            prevOptionIdx = optionIdx;
                                            segmentLatlngs = [latlngs[i]];
                                        }
                                    }

                                    return this;
                                },

                                getLatLngs: function () {
                                    return this._originalLatlngs;
                                },

                                getLatLngsSegments: function () {
                                    var latlngs = [];

                                    this.eachLayer(function (layer) {
                                        latlngs.push(layer.getLatLngs());
                                    });

                                    return latlngs;
                                }
                                });

                            L.MultiColorsPolyline = MultiColorsPolyline;
                            */
    </script>

</body>

</html>