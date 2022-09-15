<?php
//require_once "../config.php";
require_once "config.php";

// Initialize the session
session_start();
$currentDate = date("Y-m-d");

$pendingCrawl = mysqli_query($link, "SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline FROM
    pubcrawls p WHERE
    TIMESTAMPDIFF(HOUR, DATE(p.start_time), '$currentDate') < p.duration  AND p.status = 'Ready'");


$pendingStarted = mysqli_query($link, "SELECT p.id, p.city, p.start_time, p.duration, p.meeting_point, p.stops, p.durations, p.geojson, p.enable_timeline FROM
    pubcrawls p WHERE
    TIMESTAMPDIFF(HOUR, DATE(p.start_time), '$currentDate') < p.duration AND p.status = 'Started'");


// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    if ((isset($_SESSION["role"]) && $_SESSION["role"] !== 'SuperAdmin') || (isset($_SESSION["role"]) && $_SESSION["role"] !== 'Admin')) {
        header("location: city.php");
    } else {
        header("location: signin.php");
    }
    exit;
}



//find the city where the admin is located 
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

//retrieve agents from DB
if ($_SESSION["role"] == 'SuperAdmin') {
    $pending = mysqli_query($link, "SELECT u.id, u.email, u.name, u.agentCityID FROM
    users u WHERE
    u.role = 'Agent' ORDER BY id");

    $pendingAdmins = mysqli_query($link, "SELECT u.id, u.email, u.name, u.agentCityID FROM
    users u WHERE u.role = 'Admin' ORDER BY id");
} else if ($_SESSION["role"] == 'Admin') {

    //return every AGENT that is located in the same city as the admin
    $pending = mysqli_query($link, "SELECT u.id, u.email, u.name, u.agentCityID FROM
    users u WHERE
    u.role = 'Agent' And u.agentCityID = '$cityID' ORDER BY id");

    //return every ADMIN that is located in the same city as the admin
    $pendingAdmins = mysqli_query($link, "SELECT u.id, u.email, u.name, u.agentCityID FROM
    users u WHERE u.role = 'Admin' And u.agentCityID = '$cityID' ORDER BY id");
}


?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8" />

    <title>Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <?php
    include 'common_header.php';
    ?>

    <script src="https://www.jsdelivr.com/package/npm/date-fns@2.28.0/format"></script>
    <script src="https://www.jsdelivr.com/packag/npm/date-fns@2.28.0/parseISO"></script>
</head>

<body>

    <ion-app>
        <ion-header>
            <ion-toolbar id="toolbar" color="white" class="ion-text-center">

                <ion-buttons slot="start">
                    <a id="main-logo" href="city.php">
                        <img src="imgs/logo.webp" alt="logo" />
                    </a>
                </ion-buttons>

                <ion-title id='title' size="large" class="ion-text-center">
                    Dashboard
                </ion-title>

                <!--  toggle button that opens the side menu and appears only when the screen size is smaller than 510px.  -->
                <ion-button slot="end" color="main-bg" class="menuBtn-smallScreen" style="width:70px;">
                    <ion-menu-button menu="main-menu"></ion-menu-button>
                </ion-button>

                <!-- Item as an Anchor -->
                <ion-item href="manageCrawling.php" slot="end" lines="none" class="bigScreen">
                    <ion-label color="main-bg">
                        Manage Crawling
                    </ion-label>
                </ion-item>
                <ion-item href="phpUtilities/logout.php" slot="end" lines="none" class="bigScreen">
                    <ion-label color="main-bg">
                        Log out
                    </ion-label>
                </ion-item>
            </ion-toolbar>

        </ion-header>


        <!-- side menu if the screen size is smaller that 510px -->
        <ion-menu side="end" menu-id="main-menu" class="main" content-id="main">
            <ion-header>
                <ion-toolbar color="main-bg">
                    <ion-title style="color:black">Menu</ion-title>
                </ion-toolbar>
            </ion-header>
            <ion-content>
                <ion-list>
                    <ion-item href="manageCrawling.php" slot="end" lines="none">
                        <ion-label color="main-bg">
                            Manage Crawling
                        </ion-label>
                    </ion-item>
                    <ion-item href="phpUtilities/logout.php" slot="end" lines="none">
                        <ion-label color="main-bg">
                            Log out
                        </ion-label>
                    </ion-item>
                </ion-list>
            </ion-content>
        </ion-menu>
        <ion-router-outlet id="main"></ion-router-outlet>
        
        <ion-content [fullscreen]="true">
            <!-- Default Segment -->
            <ion-segment id="dashboard-segment" value='Manage Pub Crawl Schedule' color="main-bg">
                <ion-segment-button value="Manage Pub Crawl Schedule">
                    <ion-label>Manage</br>Pub Crawl</br>Schedule</ion-label>

                </ion-segment-button>
                <ion-segment-button value="Manage Pub Crawl Agent">
                    <ion-label>Manage</br>Pub Crawl</br>Agent</ion-label>
                </ion-segment-button>

                <ion-segment-button value="Manage Account">
                    <ion-label>Manage</br>Account</ion-label>
                </ion-segment-button>
            </ion-segment>

            <ion-grid id="grid-dashboard">
                <ion-row>
                    <ion-col size-md="6" offset-md='3'>
                        <ion-card class="card-container">
                            <!--Header of card-->
                            <!-- Header of card: selected-planner -->
                            <div id="selected-planner">
                                <!--class='ion-margin-vertical ion-text-center'-->
                                <h1 class="dashboard-bold-text">Pub Crawl Planner</h1>

                                <ion-grid id="grid-dashboard">
                                    <ion-row>
                                        <ion-col class="ion-text-center">
                                            <ion-label>Enable position by timeline</ion-label>
                                            <ion-icon name="information-circle-outline" id="trigger-button">
                                                <ion-popover trigger="trigger-button">
                                                    <ion-content>
                                                        <ion-item id="info-content" no-lines>
                                                            Less precise, enable only if the Agent is
                                                            carryng the GPS tracker and is moving from the
                                                            next place back to the other (so it can confuse the users)
                                                        </ion-item>
                                                    </ion-content>
                                                </ion-popover>
                                            </ion-icon>
                                            </br>
                                            <ion-toggle color="main-bg" id='enable-timeline'></ion-toggle>
                                        </ion-col>

                                    </ion-row>
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
                                            City
                                            <!-- Datetime in popover with input -->
                                            <ion-item>
                                                <?php
                                                if ($_SESSION["role"] == "Admin") { ?>
                                                    <ion-input id="city" placeholder="Insert Pub Crawl city" value=<?= $cityName ?> readonly></ion-input>
                                                <?php } else if ($_SESSION["role"] == "SuperAdmin") { ?>
                                                    <ion-input onkeyup="suggestCity(this)" id="city" placeholder="Insert Pub Crawl city"></ion-input>
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
                                            <ion-row id='coords-stop-1'>
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
                                            </ion-row>

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
                                            <ion-row id='coords-stop-2'>
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
                                            </ion-row>

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
                                            <ion-row id='coords-stop-3'>
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
                                            </ion-row>

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
                                            <ion-row id='coords-stop-4'>
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
                                            </ion-row>

                                            <!-- Time staying in pub in minutes -->
                                            <ion-item>
                                                <ion-input class='pub-stop-time' id='pub-stop-time-4' type="text" placeholder="Time staying in this pub (minutes)"></ion-input>
                                            </ion-item>
                                            </br></br>


                                        </div>
                                    </ion-col>
                                </ion-grid>
                                <ion-button fill='clear' color='main-bg' class='dashboard-bold-text' onClick='addStop()'>
                                    Add another stop
                                </ion-button>
                                </br>
                                </br>

                                <ion-label class="repeat-pubcrwal">Repeat </ion-label>
                                <ion-radio-group class="repeat-route">
                                    <ion-radio-group value="1">
                                        <ion-row>
                                            <ion-col>
                                                <ion-item>
                                                    <ion-label>Just Today</ion-label>
                                                    <ion-radio mode="md" item-left name="repeatRouteChoice" value="1" onClick="todayOrWeekly()"></ion-radio>
                                                </ion-item>
                                            </ion-col>
                                            <ion-col>
                                                <ion-item>
                                                    <ion-label>Weekly</ion-label>
                                                    <ion-radio mode="md" item-left name="repeatRouteChoice" value="weekly" onClick="todayOrWeekly()"></ion-radio>
                                                </ion-item>
                                            </ion-col>
                                        </ion-row>
                                        <!-- style="pointer-events: none;" -->
                                        <ion-row>
                                            <ion-col>
                                                <ion-item>
                                                    <ion-label>M</ion-label>
                                                    <ion-checkbox disabled="true" mode="md" item-left name="repeatRouteChoiceDay" value="Monday"></ion-checkbox>
                                                </ion-item>
                                            </ion-col>
                                            <ion-col>
                                                <ion-item>
                                                    <ion-label>T</ion-label>
                                                    <ion-checkbox disabled="true" mode="md" item-left name="repeatRouteChoiceDay" value="Tuesday"></ion-checkbox>
                                                </ion-item>
                                            </ion-col>
                                            <ion-col>
                                                <ion-item>
                                                    <ion-label>W</ion-label>
                                                    <ion-checkbox disabled="true" mode="md" item-left name="repeatRouteChoiceDay" value="Wednesday"></ion-checkbox>
                                                </ion-item>
                                            </ion-col>
                                            <ion-col>
                                                <ion-item>
                                                    <ion-label>T</ion-label>
                                                    <ion-checkbox disabled="true" mode="md" item-left name="repeatRouteChoiceDay" value="Thursday"></ion-checkbox>
                                                </ion-item>
                                            </ion-col>
                                            <ion-col>
                                                <ion-item>
                                                    <ion-label>F</ion-label>
                                                    <ion-checkbox disabled="true" mode="md" item-left name="repeatRouteChoiceDay" value="Friday"></ion-checkbox>
                                                </ion-item>
                                            </ion-col>
                                            <ion-col>
                                                <ion-item>
                                                    <ion-label>S</ion-label>
                                                    <ion-checkbox disabled="true" mode="md" item-left name="repeatRouteChoiceDay" value="Saturday"></ion-checkbox>
                                                </ion-item>
                                            </ion-col>
                                            <ion-col>
                                                <ion-item>
                                                    <ion-label>S</ion-label>
                                                    <ion-checkbox disabled="true" mode="md" item-left name="repeatRouteChoiceDay" value="Sunday"></ion-checkbox>
                                                </ion-item>
                                            </ion-col>
                                        </ion-row>
                                    </ion-radio-group>

                                    <ion-label class="repeat-pubcrwal-weeks">For : </ion-label>
                                    <ion-list>
                                        <ion-item>
                                            <ion-select [(ngModel)]="weekValue" disabled="true" name="weekItem" interface="popover" [interfaceOptions]="customOptions" placeholder="Select for how many weeks:">
                                                <ion-select-option name="repeatRouteChoiceWeek" value="1">1 Week</ion-select-option>
                                                <ion-select-option name="repeatRouteChoiceWeek" value="2">2 Weeks</ion-select-option>
                                                <ion-select-option name="repeatRouteChoiceWeek" value="3">3 Weeks</ion-select-option>
                                                <ion-select-option name="repeatRouteChoiceWeek" value="4">4 Weeks</ion-select-option>
                                                <ion-select-option name="repeatRouteChoiceWeek" value="5">5 Weeks</ion-select-option>
                                                <ion-select-option name="repeatRouteChoiceWeek" value="6">6 Weeks</ion-select-option>
                                                <ion-select-option name="repeatRouteChoiceWeek" value="7">7 Weeks</ion-select-option>
                                                <ion-select-option name="repeatRouteChoiceWeek" value="8">8 Weeks</ion-select-option>
                                                <ion-select-option name="repeatRouteChoiceWeek" value="9">9 Weeks</ion-select-option>
                                                <ion-select-option name="repeatRouteChoiceWeek" value="10">10 Weeks</ion-select-option>
                                            </ion-select>
                                        </ion-item>
                                    </ion-list>
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
                                                <ion-button color="white" onClick="resetPlanner()">
                                                    <a style="text-decoration: none; color: var(--ion-color-main-bg)">Reset</a>
                                                </ion-button>
                                            </ion-col>
                                            <ion-col>

                                            </ion-col>
                                        </ion-row>
                                        <div id='schedule-pub-error'>

                                        </div>
                                    </ion-grid>
                            </div>

                            <!----------------------------------->
                            <!--Header of card: selected-agents-->
                            <div id="selected-agents">
                                <!--class='ion-margin-vertical ion-text-center'-->
                                <ion-grid>
                                    <row>
                                        <h1 class="dashboard-bold-text">Pub Crawl Agents</h1>
                                        <ion-button href="#" fill="clear" color="main-bg" class="dashboard-bold-text" id="add-agent">
                                            + Add a new agent
                                        </ion-button>
                                        <ion-modal trigger="add-agent" color="white" id="add-agent-modal">
                                            <ion-toolbar>
                                                <ion-title class="ion-text-center">Add a new Agent</ion-title>
                                                <ion-buttons slot="end">
                                                    <ion-button onClick="dismissModal()">
                                                        <ion-icon slot="icon-only" name="close"></ion-icon>
                                                    </ion-button>
                                                </ion-buttons>
                                            </ion-toolbar>

                                            <ion-content>
                                                <!--Here there should be all the fields required to add a new Agent-->
                                                <!--Name input-->
                                                <ion-item class="login-form-element">
                                                    <ion-label class='login-label' position='floating'>Agent name</ion-label>
                                                    <ion-input type='name' id='input-name' clear-input="true" autocomplete="name" placeholder="Name"></ion-input>
                                                </ion-item>

                                                <!--email input-->
                                                <ion-item class="login-form-element">
                                                    <ion-label class='login-label' position='floating'>Agent email</ion-label>
                                                    <ion-input type='email' id='input-email' clear-input="true" autocomplete="email" placeholder="E-mail"></ion-input>
                                                </ion-item>

                                                <div id='login-info'>

                                                </div>


                                                <ion-footer id="add-agent-buttons">

                                                    <ion-grid>
                                                        <ion-row>
                                                            <ion-col>

                                                            </ion-col>
                                                            <ion-col align-self-end>
                                                                <ion-button color="main-bg" onClick="addAgent()">
                                                                    <a style="text-decoration: none; color: var(--ion-color-white)">Confirm</a>
                                                                </ion-button>
                                                            </ion-col>
                                                            <ion-col align-self-end>
                                                                <ion-button color="white" onClick="dismissModal()">
                                                                    <a style="text-decoration: none; color: var(--ion-color-main-bg)">Discard</a>
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


                                <ion-grid>
                                    <ion-row class="ion-align-items-start">
                                        <ion-col>
                                            Name
                                        </ion-col>
                                        <ion-col class="ion-text-center">
                                            Email
                                        </ion-col>
                                        <?php
                                        if ($_SESSION["role"] == 'SuperAdmin') { ?>
                                            <ion-col>
                                                City
                                            </ion-col>
                                        <?php } ?>
                                        <ion-col>
                                            Delete agent
                                        </ion-col>
                                    </ion-row>

                                    <div id="pub-agents">
                                        <!--See Dashboard.js commented code to understand how this div was populated before DB integration-->
                                        <?php
                                        if ($pending->num_rows > 0) {
                                            while ($row1 = $pending->fetch_assoc()) {
                                                $id = $row1["id"];
                                                $email = $row1["email"];
                                                $name = $row1["name"];
                                                $fullName = $row1["name"];
                                                $agentCity = $row1["agentCityID"];
                                                $lastname = '';


                                                $cityName_manage_pubcrawl_agent = "";
                                                $findCityName = mysqli_query($link, "SELECT c.name FROM
                                                city c WHERE c.id = $agentCity");
                                                if ($findCityName->num_rows > 0) {
                                                    while ($row1 = $findCityName->fetch_assoc()) {
                                                        $cityName_manage_pubcrawl_agent = $row1["name"];
                                                    }
                                                }

                                                $nameFragments = explode(" ", $name);
                                                if (count($nameFragments) > 1) {

                                                    for ($i = 1; $i < count($nameFragments); $i++) {
                                                        $lastname .= $nameFragments[$i] . ' ';
                                                    }
                                                    $lastname = trim($lastname);
                                                    $name = $nameFragments[0];
                                                }

                                                //not necessary
                                                //$password = $row1["password"];
                                                //$role = $row1["role"];

                                                echo "<ion-row class='ion-align-items-center dashboard-bold-text footer-central-row' id='$id'>";
                                        ?>
                                                <ion-col>
                                                    <?= $name ?>
                                                    <span class='not-crawling-highlight'><?= $lastname ?></span>
                                                </ion-col>
                                                <ion-col class='ion-text-center'>
                                                    <?= $email ?>
                                                </ion-col>
                                                <?php
                                                if ($_SESSION["role"] == 'SuperAdmin') { ?>
                                                    <ion-col>
                                                        <?= $cityName_manage_pubcrawl_agent ?>
                                                    </ion-col>
                                                <?php } ?>
                                                <ion-col>
                                                    <?php
                                                    echo "<ion-button color='main-bg' onclick='presentAlertConfirm($id, \"$fullName\")'>";
                                                    ?>
                                                    <ion-icon slot='icon-only' color='white' name='close-outline'></ion-icon>
                                                    </ion-button>
                                                </ion-col>
                </ion-row>
            <?php
                                            }
            ?>
            </table>
        <?php
                                        }
        ?>
        </div>

            </ion-grid>



            <div id="selectedAdmins" style="margin-top:40px; border-top:1px solid black">
                <ion-grid>
                    <row>
                        <h1 class="dashboard-bold-text">Pub Crawl Admin</h1>
                        <ion-button href="#" fill="clear" color="main-bg" class="dashboard-bold-text" id="add-admin">
                            + Add a new admin
                        </ion-button>
                        <ion-modal trigger="add-admin" color="white" id="add-agent-modal">
                            <ion-toolbar>
                                <ion-title class="ion-text-center">Add a new Admin</ion-title>
                                <ion-buttons slot="end">
                                    <ion-button onClick="dismissModal()">
                                        <ion-icon slot="icon-only" name="close"></ion-icon>
                                    </ion-button>
                                </ion-buttons>
                            </ion-toolbar>

                            <ion-content>
                                <!--Here there should be all the fields required to add a new Agent-->
                                <!--Name input-->
                                <ion-item class="login-form-element">
                                    <ion-label class='login-label' position='floating'>Admin name</ion-label>
                                    <ion-input type='name' id='input-name' clear-input="true" autocomplete="name" placeholder="Name"></ion-input>
                                </ion-item>

                                <!--email input-->
                                <ion-item class="login-form-element">
                                    <ion-label class='login-label' position='floating'>Admin email</ion-label>
                                    <ion-input type='email' id='input-email' clear-input="true" autocomplete="email" placeholder="E-mail"></ion-input>
                                </ion-item>

                                <div id='login-info'>

                                </div>


                                <ion-footer id="add-agent-buttons">
                                    <ion-grid>
                                        <ion-row>
                                            <ion-col>

                                            </ion-col>
                                            <ion-col align-self-end>
                                                <ion-button color="main-bg" onClick="addAgent()">
                                                    <a style="text-decoration: none; color: var(--ion-color-white)">Confirm</a>
                                                </ion-button>
                                            </ion-col>
                                            <ion-col align-self-end>
                                                <ion-button color="white" onClick="dismissModal()">
                                                    <a style="text-decoration: none; color: var(--ion-color-main-bg)">Discard</a>
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
                <ion-grid>
                    <ion-row class="ion-align-items-start">
                        <ion-col>
                            Name
                        </ion-col>
                        <ion-col class="ion-text-center">
                            Email
                        </ion-col>
                        <?php
                        if ($_SESSION["role"] == 'SuperAdmin') { ?>
                            <ion-col>
                                City
                            </ion-col>
                        <?php } ?>
                        <ion-col>
                            Delete Admin
                        </ion-col>
                    </ion-row>

                    <div id="pub-agents">
                        <!--See Dashboard.js commented code to understand how this div was populated before DB integration-->
                        <?php
                        if ($pendingAdmins->num_rows > 0) {
                            while ($row1 = $pendingAdmins->fetch_assoc()) {
                                $id = $row1["id"];
                                $email = $row1["email"];
                                $name = $row1["name"];
                                $fullName = $row1["name"];
                                $agentCity = $row1["agentCityID"];
                                $lastname = '';

                                $cityName_manage_pubcrawl_admin = "";
                                $findCityName = mysqli_query($link, "SELECT c.name FROM
                                city c WHERE c.id = $agentCity");
                                if ($findCityName->num_rows > 0) {
                                    while ($row1 = $findCityName->fetch_assoc()) {
                                        $cityName_manage_pubcrawl_admin = $row1["name"];
                                    }
                                }

                                $nameFragments = explode(" ", $name);
                                if (count($nameFragments) > 1) {

                                    for ($i = 1; $i < count($nameFragments); $i++) {
                                        $lastname .= $nameFragments[$i] . ' ';
                                    }
                                    $lastname = trim($lastname);
                                    $name = $nameFragments[0];
                                }

                                echo "<ion-row class='ion-align-items-center dashboard-bold-text footer-central-row' id='$id'>";
                        ?>
                                <ion-col>
                                    <?= $name ?>
                                    <span class='not-crawling-highlight'><?= $lastname ?></span>
                                </ion-col>
                                <ion-col class='ion-text-center'>
                                    <?= $email ?>
                                </ion-col>
                                <?php
                                if ($_SESSION["role"] == 'SuperAdmin') { ?>
                                    <ion-col>
                                        <?= $cityName_manage_pubcrawl_admin ?>
                                    </ion-col>
                                <?php } ?>
                                <ion-col>
                                    <?php
                                    echo "<ion-button color='main-bg' onclick='presentAlertConfirm($id, \"$fullName\")'>";
                                    ?>
                                    <ion-icon slot='icon-only' color='white' name='close-outline'></ion-icon>
                                    </ion-button>
                                </ion-col>
                                </ion-row>
                            <?php
                            }
                            ?>
                            </table>
                        <?php
                        }
                        ?>
                    </div>
                </ion-grid>

            </div>

            </div>
            <!----------------------------------->
            <!--Header of card: manage account-->
            <div id="manage-account">
                <ion-grid>
                    <row>
                        <h1 class="dashboard-bold-text">Manage your account</h1>
                        <ion-button href="#" fill="clear" color="main-bg" class="dashboard-bold-text" id="change-password">
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


            </ion-card>
            </ion-col>
            </ion-row>
            </ion-grid>
        </ion-content>
    </ion-app>
    <script src='Dashboard.js'></script>

</body>

</html>