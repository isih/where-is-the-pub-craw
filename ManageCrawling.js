const ionSegment = document.querySelector('#agent-segment');

//handle click on dashboard segment
ionSegment.addEventListener("ionChange", (event) => {

    if (event.detail.value === 'Manage Pub Crawls') {
        document.getElementById("manage-pubcrawl").style.display = "block";
        document.getElementById("manage-agent-account").style.display = "none";

    } else if (event.detail.value === 'Manage Account') {
        document.getElementById("manage-pubcrawl").style.display = "none";
        document.getElementById("manage-agent-account").style.display = "block";

    }

});

var sendCoordsInterval = '';
var sendingCoords;

function startCrawl(id) {
    var data = {};
    data.id = id;

    $.ajax({
        url: 'phpUtilities/startCrawl.php',
        type: 'POST',
        data: data,

        success: (output) => {
            console.log(output);
            location.reload();
        }
    });
}

function deleteCrawl(id) {
    var data = {};
    data.id = id;

    const alert = document.createElement('ion-alert');
    alert.header = 'Delete this Route!';
    alert.message = "Do you really want to delete this pubcrawl?";

    alert.buttons = [
        {
            text: 'Cancel',
            role: 'no',
            cssClass: 'secondary',
            id: 'cancel-button',
            handler: () => {
                console.log('Cancel button pressed');
            }
        }, {
            text: 'Confirm',
            id: 'confirm-button',
            handler: () => {
                console.log('Confirm button pressed');

                $.ajax({
                    url: 'phpUtilities/deleteCrawl.php',
                    type: 'POST',
                    data: data,

                    success: (output) => {
                        console.log(output);
                        location.reload();
                    }
                });

            }
        }
    ];

    document.body.appendChild(alert);
    return alert.present();

}
//use ajax to send current coords to server
function sendCoords(position) {
    //console.log("sendingCoords: "+sendingCoords);

    var data = {};
    //data.id = pubCrawlId;
    data.lat = position.coords.latitude;
    data.lon = position.coords.longitude;

    $.ajax({
        url: 'phpUtilities/sendCoords.php',
        type: 'POST',
        data: data,

        success: (output) => {
            sendingCoords = output;//update sendingCoords
        }
    });

}



function stopCrawl(id) {
    var data = {};
    data.id = id;
    clearInterval(sendCoordsInterval);//stops sending coords to DB

    $.ajax({
        url: 'phpUtilities/stopCrawl.php',
        type: 'POST',
        data: data,

        success: (output) => {
            //console.log(output);
            location.reload();
        }
    });
}


/*Function to dismiss modal to change password*/
async function dismissModalChangePassword() {
    await document.querySelector('#change-password-modal').dismiss({
        'dismissed': true
    });
}

function changePassword() {
    //console.log('Change password requested');

    var data = {};

    $.ajax({
        url: 'phpUtilities/changePassword.php',
        type: 'POST',
        data: data,

        //success: (output) => {
        success: (output) => {
            console.log(output);

            if (output !== '') {
                document.getElementById('change-password-info').innerHTML = "<ion-item lines='none' color='main-bg'><ion-label color='white'>" + output + "</ion-label></ion-item>";
            } else {
                document.getElementById('change-password-info').innerHTML = "<ion-item lines='none' color='main-bg'><ion-label color='white'>Verification link sent to your email.</ion-label></ion-item>";

                setTimeout(function () {
                    window.location.replace("phpUtilities/logout.php");
                }, 5000);
            }
        }
    });
}

function setSendCoords() {

    sendCoordsInterval = setInterval(() => {
        sendingCoords = document.getElementById('sending-coords').innerHTML.trim();

        //let pubCrawlId = document.getElementById('chosen-pub-crawl-id').innerHTML.trim();
        console.log("SendingCoords HTML: " + sendingCoords);
        if (sendingCoords !== 'false') {

            if (navigator.geolocation) {

                navigator.geolocation.watchPosition((position) => {
                    //console.log("Position: "+position.coords.latitude+":"+position.coords.longitude);
                    sendCoords(position);

                });
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
        }
    }, 5000);//sends coords to DB every 10 seconds


}

function appearDisappearEditBox(id) {
    //display the edit card and hide the card with the started pubcrawls
    editCard = document.getElementById('editPubcrwal');
    editPubCrawlID = document.getElementById('editPubcrwalID');
    previousTime = document.getElementById('previousDate');
    managePubcrawl = document.getElementById('manage-pubcrawl');
    editCard.style.display = "block";
    managePubcrawl.style.display = "none";
    editPubCrawlID.innerHTML = id;


    //get the elements from that i need to assign values
    date = document.getElementById("date-input-2");
    dur = document.getElementById('duration');
    city_card = document.getElementById("city");
    meetingPoint_card = document.getElementById("meeting-point");
    stop1_card = document.getElementById("stop-1");
    lat_stop1_card = document.getElementById("lat-stop-1");
    lon_stop1_card = document.getElementById("lon-stop-1")
    stop_time1_card = document.getElementById("pub-stop-time-1");

    stop2_card = document.getElementById("stop-2");
    lat_stop2_card = document.getElementById("lat-stop-2");
    lon_stop2_card = document.getElementById("lon-stop-2")
    stop_time2_card = document.getElementById("pub-stop-time-2");

    stop3_card = document.getElementById("stop-3");
    lat_stop3_card = document.getElementById("lat-stop-3");
    lon_stop3_card = document.getElementById("lon-stop-3")
    stop_time3_card = document.getElementById("pub-stop-time-3");

    stop4_card = document.getElementById("stop-4");
    lat_stop4_card = document.getElementById("lat-stop-4");
    lon_stop4_card = document.getElementById("lon-stop-4")
    stop_time4_card = document.getElementById("pub-stop-time-4");


    //call retrievePubCrawl.php file to get all the info about the pubcrawl with id= to the id of the pubcrawl we choosed
    var data = {};
    data.id = id;

    $.ajax({
        url: 'phpUtilities/retrievePubCrawl.php',
        type: 'POST',
        data: data,

        success: (output) => {
            let pubcrawl_info = output.split(";");//all the record in the order of the database 
            let pubcrawl_info_stop_time = pubcrawl_info[5].split(",");//the durations are in a row and sepearted with comma(,). So in order to assign them in the correct fields i need to split them first
            let pubcrawl_info_jsonFile = JSON.parse(pubcrawl_info[6]);//read the json file. I use it to take the name of the bars and thei coordinates
            city_card.value = pubcrawl_info[0];
            date.value = pubcrawl_info[1];
            previousTime.innerHTML = pubcrawl_info[1];
            dur.value = pubcrawl_info[2];
            meetingPoint_card.value = pubcrawl_info[3];
            stop1_card.value = pubcrawl_info_jsonFile.features[0].properties.name;
            stop2_card.value = pubcrawl_info_jsonFile.features[1].properties.name;
            stop3_card.value = pubcrawl_info_jsonFile.features[2].properties.name;
            stop4_card.value = pubcrawl_info_jsonFile.features[3].properties.name;

            lat_stop1_card.value = pubcrawl_info_jsonFile.features[0].geometry.coordinates[0];
            lon_stop1_card.value = pubcrawl_info_jsonFile.features[0].geometry.coordinates[1]
            stop_time1_card.value = pubcrawl_info_stop_time[0];

            lat_stop2_card.value = pubcrawl_info_jsonFile.features[1].geometry.coordinates[0]
            lon_stop2_card.value = pubcrawl_info_jsonFile.features[1].geometry.coordinates[1]
            stop_time2_card.value = pubcrawl_info_stop_time[1];

            lat_stop3_card.value = pubcrawl_info_jsonFile.features[2].geometry.coordinates[0]
            lon_stop3_card.value = pubcrawl_info_jsonFile.features[2].geometry.coordinates[1]
            stop_time3_card.value = pubcrawl_info_stop_time[2];

            lat_stop4_card.value = pubcrawl_info_jsonFile.features[3].geometry.coordinates[0]
            lon_stop4_card.value = pubcrawl_info_jsonFile.features[3].geometry.coordinates[1]
            stop_time4_card.value = pubcrawl_info_stop_time[3];

        }
    });
}

function cancelCard() {
    editCard = document.getElementById('editPubcrwal');
    managePubcrawl = document.getElementById('manage-pubcrawl');
    editCard.style.display = "none";
    managePubcrawl.style.display = "block";
}

const formatDate = (value) => {
    let date = value.split('T');

    //console.log('Date:');
    //console.log(date);

    let ymd = date[0].split('-');
    //console.log('ymd:');
    //console.log(ymd);
    let year = ymd[0];
    let month = ymd[1];
    let day = ymd[2];

    let hmSTDZ = date[1].split('+');
    //console.log('hmSTDZ:');
    //console.log(hmSTDZ);
    let hm = hmSTDZ[0].split(':');
    //console.log('hm:');
    //console.log(hm);
    let sTDZ = hmSTDZ[1].split(':');
    //console.log('sTDZ:');
    //console.log(sTDZ);

    let finalh = +hm[0];//+ +sTDZ[0];
    let finalm = +hm[1];//+ +sTDZ[1];
    //let finals = hm[2];

    let finalCompleteDateTime = '' + day + '/' + month + '/' + year + ', ' + finalh + ':' + finalm;

    return finalCompleteDateTime;
};

const popoverDatetime2 = document.querySelector('#popover-datetime-2');
const dateInput2 = document.querySelector('#date-input-2');
popoverDatetime2.addEventListener('ionChange', ev => dateInput2.value = formatDate(ev.detail.value));



function suggestStop(el, num, city) {
    let cityID = city;
    coordinates = [];
    allSuggestions = [];
    let suggestions = [];
    let content = el.value;
    let target = document.getElementById("stops-suggestions-" + num);
    let superAdminSelectedCity = document.getElementById("city");

    let suggestionsHTML = "";

    /*Show Latitude/Longitude options*/
    // let coords = document.getElementById('coords-stop-' + num);
    // coords.style.display = 'block';

    /* Data to retrieve from DB */
    //let suggestions = [];

    target.style.display = "block";

    let data = {}
    data.cityID = cityID;
    data.cityName = superAdminSelectedCity.value;
    //retrieveing suggestions:
    $.ajax({
        url: 'phpUtilities/getSuggestions.php',
        type: 'POST',
        data: data,

        success: (output) => {
            console.log(output);
            //suggestions = output.split(',');

            let pieces = output.split("|");//

            for (let i = 0; i < pieces.length - 1; i++) {//
                let suggAndCoords = pieces[i].split('coords');//

                let sugg = suggAndCoords[0];

                let fragments = suggAndCoords[1].split(';');//

                suggestions.push(sugg);//suggestion//
                allSuggestions.push(sugg);/////////////////////////////////////////////////////////////////////////////////
                coordinates.push(fragments);//coordinates (longitude;latitude)//
            }

            if (content === '') {
                //do nothing: all suggestions should be displayed
            } else {
                //removes from the array every suggestion that doesn't contain what was typed
                suggestions = suggestions.filter(s => ~s.toLowerCase().indexOf(content.toLowerCase()));

                if (suggestions.length === 0) {
                    target.style.display = "none";
                }
            }
            //always show the three dots
            suggestions.push("...");

            suggestions.forEach(element => {
                suggestionsHTML += "<ion-item>";
                suggestionsHTML += "<ion-label onclick='selectSuggestionStop(this," + num + ")'>" + element + "</ion-label>";
                suggestionsHTML += "</ion-item>";
            });

            target.innerHTML = suggestionsHTML;
        }

    });

}

function selectSuggestionStop(el, num) {
    let content = el.innerHTML;
    console.log(content);
    let target = document.getElementById('stop-' + num);

    /*let coords = document.getElementById('coords-stop-'+num);*/
    //let lat = document.getElementById('lat-stop-'+num);
    //let lon = document.getElementById('lon-stop-'+num);

    document.getElementById('stops-suggestions-' + num).style.display = "none";
    target.value = content;

    /*coords.style.display = 'block';*/
    //lat.style.display = 'block';
    //lon.style.display = 'block';
    //console.log(coordinates);

    console.log("coordinates: " + coordinates);
    console.log("suggestions: " + allSuggestions);

    //get coords
    //look for "content" in "suggestions" array and use its index to get coords
    let coordIndex;
    for (let i = 0; i < coordinates.length; i++) {
        if (content.trim() === allSuggestions[i]) {
            coordIndex = i;//has to be reversed since the push reverses the order from DB
        }
    }
    console.log("coordIndex: " + coordIndex)
    let coords = coordinates[coordIndex];//
    let lon = coords[0];
    let lat = coords[1];

    document.getElementById('lat-stop-' + num).value = lat;
    document.getElementById('lon-stop-' + num).value = lon;
}

function suggestMeetingPoint(el, city) {
    coordinates = [];
    let suggestions = [];
    let cityID = city;
    let content = el.value;
    let target = document.getElementById("meeting-point-suggestions");
    let superAdminSelectedCity = document.getElementById("city");
    let suggestionsHTML = "";

    /* Retrieve suggestions from DB */
    //let suggestions = [];
    target.style.display = "block";


    console.log(cityID);
    let data = {}

    data.cityName = superAdminSelectedCity.value;
    data.cityID = cityID;
    //retrieveing suggestions:
    $.ajax({
        url: 'phpUtilities/getSuggestions.php',
        type: 'POST',
        data: data,

        success: (output) => {
            //console.log(output);
            //suggestions = output.split(',');

            let pieces = output.split("|");//

            for (let i = 0; i < pieces.length - 1; i++) {//
                let suggAndCoords = pieces[i].split('coords');//

                let sugg = suggAndCoords[0];

                let fragments = suggAndCoords[1].split(';');//

                suggestions.push(sugg);//suggestion//
                coordinates.push(fragments);//coordinates (longitude;latitude)//
            }

            if (content === '') {
                //do nothing: all suggestions should be displayed
            } else {
                //removes from the array every suggestion that doesn't contain what was typed
                suggestions = suggestions.filter(s => ~s.toLowerCase().indexOf(content.toLowerCase()));

                if (suggestions.length === 0) {
                    target.style.display = "none";
                }
            }
            //always show the three dots
            suggestions.push("...");

            suggestions.forEach(element => {
                suggestionsHTML += "<ion-item>";
                suggestionsHTML += "<ion-label onclick='selectSuggestionMeetingPoint(this)'>" + element + "</ion-label>";
                suggestionsHTML += "</ion-item>";
            });
            target.innerHTML = suggestionsHTML;
        }
    });

}

/* Actually select one of the suggestions*/
function selectSuggestionMeetingPoint(el) {
    let content = el.innerHTML;
    let target = document.getElementById('meeting-point');

    document.getElementById('meeting-point-suggestions').style.display = "none";

    target.value = content;
}

function confirmedPubCrawlPlanning() {
    let datetimeInput = document.getElementById('date-input-2').value;
    let targetMeetingPoint = document.getElementById("meeting-point").value;
    let targetCity = document.getElementById('city').value;
    let targetDuration = document.getElementById('duration').value;
    let editPubCrawlID = document.getElementById('editPubcrwalID').innerHTML;
    let previousTime = document.getElementById('previousDate').innerHTML;

    let elements = document.getElementsByClassName("stops");
    let durations = document.getElementsByClassName("pub-stop-time");

    const alert = document.createElement('ion-alert');

    let totalDuration = 0;
    let ok = true;

    for (let i = 0; i < elements.length; i++) {
        let lat = document.getElementById('lat-stop-' + (i + 1)).value;
        let lon = document.getElementById('lon-stop-' + (i + 1)).value;


        console.log("elements[i].value = " + elements[i].value);
        console.log("durations[i].value = " + durations[i].value);


        if ((i < 4 &&
            (
                elements[i].value === '' || elements[i].value === undefined ||
                durations[i].value === '' || durations[i].value === undefined ||
                isNaN(parseInt(durations[i].value)) ||
                lat === '' || lon === ''
            )

        )) {
            ok = false;
        }
        totalDuration += parseInt(durations[i].value);
    }

    let splitDateTime = "";
    let splitedDate = "";
    let reverseArray = [];
    let joinArray = '';

    if (datetimeInput.includes("/") === true) {
        splitDateTime = datetimeInput.split(",");
        splitedDate = splitDateTime[0].split("/")
        reverseArray = splitedDate.reverse();
        joinArray = reverseArray.join("-");
        joinArray = joinArray.concat(splitDateTime[1])
    }
    else {
        joinArray = datetimeInput;
    }


    if (previousTime > joinArray) {

        alert.header = 'Check your input!';
        alert.subHeader = "We couldn't update this Pubcrawl";
        alert.message = "The date is older than the previous one. Please fill the date and time correctly.<br> Note that the previous one was: " + previousTime;

        alert.buttons = ['Ok'];
    }
    else {

        if (!ok || datetimeInput === '' || targetDuration === '' || targetMeetingPoint === '' || targetCity === '' || isNaN(parseInt(targetDuration)) || (parseInt(targetDuration) * 60) < parseInt(totalDuration)) {

            //alert.cssClass = 'my-custom-class';
            alert.header = 'Check your input!';
            alert.subHeader = "We couldn't schedule this Pubcrawl";
            alert.message = "You didn't fill all the fields correctly.";

            alert.buttons = ['Ok'];

        } else {
            alert.header = 'Confirm Pubcrawl Plan';
            alert.subHeader = 'Check if all the input is fine:';
            alert.message = "<div id='alert-confirm-pubcrawl-plan'>";
            alert.message += '<br><b>Date and time: </b><br>' + datetimeInput;
            alert.message += '<br><b>Total duration: </b><br>' + targetDuration + ' h (' + (parseInt(targetDuration) * 60) + ' m)';
            alert.message += '<br><b>Actual duration: </b><br>' + totalDuration + ' m';
            alert.message += '<br><b>Meeting point: </b><br>' + targetMeetingPoint;
            alert.message += '<br><b>City: </b><br>' + targetCity;

            alert.message += '<br><b>Stops: </b>';
            for (let i = 0; i < elements.length; i++) {
                if (elements[i].value !== '') {
                    alert.message += '<br>' + elements[i].value;
                }
            }

            alert.message += '</div>';

            alert.buttons = [
                {
                    text: 'Cancel',
                    role: 'no',
                    cssClass: 'secondary',
                    id: 'cancel-button',
                    handler: () => {
                        console.log('Cancel button pressed');
                    }
                }, {
                    text: 'Confirm',
                    id: 'confirm-button',
                    handler: () => {
                        console.log('Confirm button pressed');

                        //builds a JSON file based on the info given by the user.
                        //php still needs to check errors server side if front-end compromised

                        var data = {};
                        data.id = editPubCrawlID;
                        data.start_time = datetimeInput;//to format for mySQL on the backend
                        data.duration = targetDuration;
                        data.meeting_point = targetMeetingPoint;
                        var prov_stops = [];
                        data.coordinates = '';

                        for (let i = 0; i < elements.length; i++) {
                            prov_stops.push(elements[i].value);

                            let lat = document.getElementById('lat-stop-' + (i + 1)).value;
                            let lon = document.getElementById('lon-stop-' + (i + 1)).value;
                            //coordinates will be a matrix: data.coordinates[0][0] = longitude of el 0. data.coordinates[0][1] = latitude of el 0. 
                            data.coordinates += lon + "," + lat + "_";
                        }

                        data.stops = prov_stops.join('_');

                        var prov_durations = '';
                        for (let i = 0; i < durations.length; i++) {
                            if (i == durations.length - 1) {
                                prov_durations += durations[i].value;
                            } else {
                                prov_durations += durations[i].value + ',';//comma is not a problem, minutes are integers (parseInt)
                            }

                        }

                        data.durations = prov_durations;

                        // data.enable = enableTimeline;

                        $.ajax({
                            url: 'phpUtilities/updatePubCrawlRoute.php',
                            type: 'POST',
                            data: data,

                            success: (output) => {
                                console.log(output);
                                //document.getElementById('schedule-pub-error').innerHTML=output;
                                location.href = 'manageCrawling.php';
                            }

                        });

                    }
                }
            ];

        }
    }



    document.body.appendChild(alert);
    return alert.present();
}

