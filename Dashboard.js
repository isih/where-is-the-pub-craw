const ionSegment =  document.querySelector('#dashboard-segment');

//handle click on dashboard segment
ionSegment.addEventListener("ionChange", (event) => {

    if(event.detail.value === 'Manage Pub Crawl Schedule'){
        document.getElementById("selected-agents").style.display="none";
        document.getElementById("selected-planner").style.display="block";
        document.getElementById("manage-account").style.display="none";

    } else if(event.detail.value === 'Manage Pub Crawl Agent'){
        document.getElementById("selected-agents").style.display="block";
        document.getElementById("selected-planner").style.display="none";
        document.getElementById("manage-account").style.display="none";

    } else if (event.detail.value === 'Manage Account') {
        document.getElementById("selected-agents").style.display="none";       
        document.getElementById("selected-planner").style.display="none";
        document.getElementById("manage-account").style.display="block";
    }
} );


/*Add agents. This code is useful to use to simulate a situation with 'numOfAgents' agents*/
/*
const pubAgents = document.querySelector('#pub-agents');
const numOfAgents = 10;

let genericPubAgent="<ion-row class='ion-align-items-center dashboard-bold-text' id='footer-central-row'>";
genericPubAgent+= "<ion-col>";
genericPubAgent+= "Name <span class='not-crawling-highlight'>Lastname</span><!--To get from DB-->";
genericPubAgent+= "</ion-col>";
genericPubAgent+= "<ion-col class='ion-text-center'>";
genericPubAgent+= "email@provider.com<!--To get from DB-->";
genericPubAgent+= "</ion-col>";
genericPubAgent+= "<ion-col>";
genericPubAgent+= "<ion-button color='main-bg' onclick='presentAlertConfirm()'>";
genericPubAgent+= "<ion-icon slot='icon-only' color='white' name='close-outline'></ion-icon>";
genericPubAgent+= "</ion-button>";
genericPubAgent+= "</ion-col>";
genericPubAgent+= "</ion-row>";

for(let i = 0; i<numOfAgents; i++){
    pubAgents.innerHTML+=genericPubAgent;
}
*/
/********************************************************************************************/

/*Function to dismiss modal to add agents in dashboard*/
async function dismissModal() {
    await document.querySelector('#add-agent-modal').dismiss({
      'dismissed': true
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

            if(output !== '') {
                document.getElementById('change-password-info').innerHTML = "<ion-item lines='none' color='main-bg'><ion-label color='white'>"+output+"</ion-label></ion-item>";
            } else {
                document.getElementById('change-password-info').innerHTML = "<ion-item lines='none' color='main-bg'><ion-label color='white'>Verification link sent to admin email.</ion-label></ion-item>";
                
                setTimeout(function() {
                    window.location.replace("phpUtilities/logout.php");
                }, 5000);
                //location.reload();

            }
            

            //document.getElementById().innerHTML='';
            //dismissModal();
        }
    });
}

/* Function to add a new agent */
function addAgent() {
    let name = document.getElementById('input-name').value;
    let email = document.getElementById('input-email').value;

    let out = document.getElementById('login-info');

    let emailCheck = /^(.+)@(.+)$/;

    if(name==='' || email=== '' || !emailCheck.test(email)){
        out.innerHTML = "<ion-item lines='none' color='main-bg'><ion-label color='white'>Invalid Agent name or email</ion-label></ion-item>";
    } else {

        var data = {};
        data.name = name;
        data.email = email;

        $.ajax({
            url: 'phpUtilities/addAgent.php',
            type: 'POST',
            data: data,

            //success: (output) => {
            success: (output) => {
                console.log(output);

                if(output !== '') {
                    document.getElementById('login-info').innerHTML = "<ion-item lines='none' color='main-bg'><ion-label color='white'>"+output+"</ion-label></ion-item>";
                } else {
                    document.getElementById('login-info').innerHTML = "<ion-item lines='none' color='main-bg'><ion-label color='white'>Message has been sent successfully to the new agent</ion-label></ion-item>";
                    
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                    //location.reload();

                }
                

                //document.getElementById().innerHTML='';
                //dismissModal();
            }/*,
            error: () => { 
                //change message

                //alert("Status: " + textStatus); alert("Error: " + errorThrown); 
            } */
        });

    }

}


/* Show confirmation before deleting an agent */
function presentAlertConfirm(agentId, name) {
    const alert = document.createElement('ion-alert');
    alert.cssClass = 'alert-class';
    alert.header = 'Delete agent '+agentId;
    alert.message = 'Delete agent</br><strong>'+name+'</strong>';
    alert.buttons = [
        {
        text: 'No',
        role: 'no',
        cssClass: 'secondary',
        id: 'cancel-button',
        handler: () => {
            //console.log('Cancel button pressed');
        }
        }, {
        text: 'Yes',
        id: 'confirm-button',
        handler: () => {
            //console.log('Confirm Okay');

            var data= {};
            data.id = agentId;

            $.ajax({
                url: 'phpUtilities/deleteAgent.php',
                type: 'POST',
                data: data,
      
                //success: (output){
                success: () => {
                  //console.log(output);
                    document.getElementById(agentId).innerHTML='';
                }
            });

        }
        }
    ];

    document.body.appendChild(alert);
    return alert.present();
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

    let finalCompleteDateTime = ''+day+'/'+month+'/'+year+', '+finalh+':'+finalm;

    return finalCompleteDateTime;
};

const popoverDatetime2 = document.querySelector('#popover-datetime-2');
const dateInput2 = document.querySelector('#date-input-2');
popoverDatetime2.addEventListener('ionChange', ev => dateInput2.value = formatDate(ev.detail.value));


/* Reset fields in Agents Planner */
function resetPlanner(){
    let enableTimeline = document.getElementById('enable-timeline').checked;
    let datetimeInput = document.getElementById('date-input-2');
    let targetMeetingPoint = document.getElementById("meeting-point");
    let targetCity = document.getElementById('city');
    let targetDuration = document.getElementById('duration');

    let elements = document.getElementsByClassName("stops");
    let durations = document.getElementsByClassName("pub-stop-time");

    //console.log(elements.length);
    
    targetMeetingPoint.value = '';
    targetCity.value = '';
    datetimeInput.value = '';
    targetDuration.value = '';
    //enableTimeline = 'false';

    for(let i = 0; i<elements.length; i++){
        elements[i].value = '';
        durations[i].value = '';

        let lat = document.getElementById('lat-stop-'+(i+1));
        let lon = document.getElementById('lon-stop-'+(i+1));

        lat.value = '';
        lon.value = '';

        let coords = document.getElementById('coords-stop-'+(i+1));

        coords.style.display = 'none';

    }
}


/* Autocomplete for pub crawl meeting point*/
function suggestMeetingPoint(el){
    coordinates = [];
    let suggestions = [];
    let content = el.value;
    let target = document.getElementById("meeting-point-suggestions");

    let suggestionsHTML = "";

    /* Retrieve suggestions from DB */
    //let suggestions = [];
    target.style.display="block";

    //retrieveing suggestions:
    $.ajax({
        url: 'phpUtilities/getSuggestions.php',
        type: 'POST',

        success: (output) => {
            //console.log(output);
            //suggestions = output.split(',');

           let pieces=output.split("|");//

           for(let i = 0; i<pieces.length-1; i++){//
               let suggAndCoords = pieces[i].split('coords');//

               let sugg = suggAndCoords[0];

               let fragments = suggAndCoords[1].split(';');//

               suggestions.push(sugg);//suggestion//
               coordinates.push(fragments);//coordinates (longitude;latitude)//
           }

           if(content==='') {
               //do nothing: all suggestions should be displayed
           } else {
               //removes from the array every suggestion that doesn't contain what was typed
               suggestions = suggestions.filter(s=>~s.toLowerCase().indexOf(content.toLowerCase()));
               
               if(suggestions.length===0){
                   target.style.display="none";
               }
           }
           //always show the three dots
           suggestions.push("...");
        
            suggestions.forEach(element => {
                suggestionsHTML += "<ion-item>";
                suggestionsHTML += "<ion-label onclick='selectSuggestionMeetingPoint(this)'>"+element+"</ion-label>";
                suggestionsHTML += "</ion-item>";
            });
            target.innerHTML = suggestionsHTML;
       }
    });

}

/* Actually select one of the suggestions*/
function selectSuggestionMeetingPoint(el){
    let content = el.innerHTML;
    let target = document.getElementById('meeting-point');

    document.getElementById('meeting-point-suggestions').style.display="none";

    target.value = content;
}

var coordinates = [];//retrieved from DB by suggestStop. Format: longitude;latitude
var allSuggestions = [];//retrieved from DB: all suggestions

/* Autocomplete for pub crawl stops*/
function suggestStop(el, num){
    coordinates = [];
    allSuggestions = [];
    let suggestions = [];
    let content = el.value;
    let target = document.getElementById("stops-suggestions-"+num);

    let suggestionsHTML = "";

    /*Show Latitude/Longitude options*/
    let coords = document.getElementById('coords-stop-'+num);
    coords.style.display = 'block';

    /* Data to retrieve from DB */
    //let suggestions = [];
    
    target.style.display="block";

     //retrieveing suggestions:
    $.ajax({
        url: 'phpUtilities/getSuggestions.php',
        type: 'POST',

        success: (output) => {
            //console.log(output);
            //suggestions = output.split(',');

            let pieces=output.split("|");//

            for(let i = 0; i<pieces.length-1; i++){//
                let suggAndCoords = pieces[i].split('coords');//

                let sugg = suggAndCoords[0];

                let fragments = suggAndCoords[1].split(';');//

                suggestions.push(sugg);//suggestion//
                allSuggestions.push(sugg);/////////////////////////////////////////////////////////////////////////////////
                coordinates.push(fragments);//coordinates (longitude;latitude)//
            }

            if(content==='') {
                //do nothing: all suggestions should be displayed
            } else {
                //removes from the array every suggestion that doesn't contain what was typed
                suggestions = suggestions.filter(s=>~s.toLowerCase().indexOf(content.toLowerCase()));
                
                if(suggestions.length===0){
                    target.style.display="none";
                }
            }
            //always show the three dots
            suggestions.push("...");
        
            suggestions.forEach(element => {
                suggestionsHTML += "<ion-item>";
                suggestionsHTML += "<ion-label onclick='selectSuggestionStop(this,"+ num +")'>"+element+"</ion-label>";
                suggestionsHTML += "</ion-item>";
            });

            target.innerHTML = suggestionsHTML;
        }

    });
    
}

function addStop() {
    //counts elements with class 'stops'
    let elements = document.getElementsByClassName("stops");
    let num = elements.length+1;

    //console.log(num);
    
    //let container = document.getElementById("pubcrawl-stops").innerHTML;
    //$('#pubcrawl-stops').append("<ion-item><ion-input class='stops' onkeyup='suggestStop(this, "+num+")' id='stop-"+num+"' placeholder='Choose Stop #"+num+"'></ion-input></ion-item><ion-list id='stops-suggestions-"+num+"'></ion-list>");
    $('#pubcrawl-stops').
        append("<ion-item><ion-input class='stops' onkeyup='suggestStop(this, "+num+")' id='stop-"+num+"' placeholder='Choose Stop #"+num+"'></ion-input></ion-item><ion-list id='stops-suggestions-"+num+"'></ion-list><ion-row id='coords-stop-"+num+"'><ion-col><ion-item><ion-input id='lat-stop-"+num+"' placeholder='Latitude stop #"+num+"'></ion-input></ion-item></ion-col><ion-col><ion-item><ion-input id='lon-stop-"+num+"' placeholder='Longitude stop #"+num+"'></ion-input></ion-item></ion-col></ion-row><ion-item><ion-input class='pub-stop-time' id='pub-stop-time-"+num+"' type='text' placeholder='Time staying in this pub (minutes)'></ion-input></ion-item></br></br>");

    let coords = document.getElementById('coords-stop-'+(num));

    coords.style.display = 'none';

    //console.log("Before ading new elements: "+container);

    //adds a new element with the count result +1 at element with id 'pubcrawl-stops'
    /*
    container+="<ion-item>";
    container+="<ion-input class='stops' onkeyup='suggestStop(this, "+num+")' id='stop-"+num+"' placeholder='Choose Stop #"+num+"'></ion-input>";
    container+="</ion-item>";
    container+="<ion-list id='stops-suggestions-"+num+"'>";
    container+="</ion-list>";
*/
    
   //console.log(container);

}

/* Actually select one of the suggestions*/
function selectSuggestionStop(el, num){
    let content = el.innerHTML;
    console.log(content);
    let target = document.getElementById('stop-'+num);

    /*let coords = document.getElementById('coords-stop-'+num);*/
    //let lat = document.getElementById('lat-stop-'+num);
    //let lon = document.getElementById('lon-stop-'+num);

    document.getElementById('stops-suggestions-'+num).style.display="none";
    target.value = content;

    /*coords.style.display = 'block';*/
    //lat.style.display = 'block';
    //lon.style.display = 'block';
    //console.log(coordinates);

    console.log("coordinates: "+coordinates);
    console.log("suggestions: "+allSuggestions);
    
    //get coords
    //look for "content" in "suggestions" array and use its index to get coords
    let coordIndex;
    for(let i=0; i<coordinates.length; i++){
       if(content.trim()===allSuggestions[i]){
            coordIndex=i;//has to be reversed since the push reverses the order from DB
        }
    }

    let coords = coordinates[coordIndex];//
    let lon = coords[0];
    let lat = coords[1];

    document.getElementById('lat-stop-'+num).value = lat;
    document.getElementById('lon-stop-'+num).value = lon;
}

// var repeatRoute = document.getElementsByName('repeatRouteChoice');
// repeatRoute.addEventListener("click", repeatPeriodofRoute, false);

/* Retrieve all the info to add to the DB (after checking if all the info is ok)*/
function confirmedPubCrawlPlanning() {
    let enableTimeline = document.getElementById('enable-timeline').checked;//get property 'checked' of togggler
    let datetimeInput = document.getElementById('date-input-2').value;
    let targetMeetingPoint = document.getElementById("meeting-point").value;
    let targetCity = document.getElementById('city').value;
    let targetDuration = document.getElementById('duration').value;

    let elements = document.getElementsByClassName("stops");
    let durations = document.getElementsByClassName("pub-stop-time");

    
    let repeatRoute = document.getElementsByName('repeatRouteChoice');
    let repeatDays;
    for(i = 0; i < repeatRoute.length; i++) {
        if(repeatRoute[i].ariaChecked==="true"){
            repeatDays = (repeatRoute[i].value)
        }
    }
    // let targetStopOne = document.getElementById("stop-1").value;
    // let targetStopTwo = document.getElementById("stop-2").value;
    // let targetStopThree = document.getElementById("stop-3").value;
    // let targetStopFour = document.getElementById("stop-4").value;
    
    const alert = document.createElement('ion-alert');

    let totalDuration = 0;

    let ok = true;

    console.log("target Duration: "+targetDuration);

    for(let i = 0; i<elements.length; i++) {
        let lat = document.getElementById('lat-stop-'+(i+1)).value;
        let lon = document.getElementById('lon-stop-'+(i+1)).value;


        console.log("elements[i].value = "+elements[i].value);
        console.log("durations[i].value = "+durations[i].value);


        if((i<4 && 
            (
            elements[i].value=== '' || elements[i].value=== undefined ||
            durations[i].value=== '' || durations[i].value=== undefined ||
            isNaN(parseInt(durations[i].value)) ||
            lat === '' || lon === ''
            )

        )) {
            ok = false;
        }
        totalDuration+=parseInt(durations[i].value);
    }
/*
UNCOMMENT FOR TESTING
    console.log("targetDuration: "+(parseInt(targetDuration)*60));
    console.log("totalDuration: "+totalDuration);

    console.log("OK: "+ok);

    console.log("isNaN(parseInt(targetDuration)): "+isNaN(parseInt(targetDuration)));
    console.log("(parseInt(targetDuration)*60)<=parseInt(totalDuration): "+(parseInt(targetDuration)*60)<parseInt(totalDuration));
*/
    if(!ok || datetimeInput === '' || targetDuration === '' || targetMeetingPoint === '' || targetCity === '' || repeatDays === undefined|| isNaN(parseInt(targetDuration)) || (parseInt(targetDuration)*60)<parseInt(totalDuration)){

        //alert.cssClass = 'my-custom-class';
        alert.header = 'Check your input!';
        alert.subHeader = "We couldn't schedule this Pubcrawl";
        alert.message = "You didn't fill all the fields correctly.";

        alert.buttons = ['Ok'];

    } else {

        enableTimeline+='';

        //alert.cssClass = 'my-custom-class';
        alert.header = 'Confirm Pubcrawl Plan';
        alert.subHeader = 'Check if all the input is fine:';
        alert.message = "<div id='alert-confirm-pubcrawl-plan'>";
        alert.message += '<b>enable timeline: </b><br>' + enableTimeline;
        alert.message += '<br><b>Date and time: </b><br>' + datetimeInput;
        alert.message += '<br><b>Total duration: </b><br>' + targetDuration + ' h ('+(parseInt(targetDuration)*60)+' m)';
        alert.message += '<br><b>Actual duration: </b><br>' + totalDuration + ' m';
        alert.message += '<br><b>Meeting point: </b><br>' + targetMeetingPoint;
        alert.message += '<br><b>City: </b><br>' + targetCity;
        if (repeatDays == "1"){
            alert.message += '<br><b>Repeat this route for </b><br> Today.';
        }else{
            alert.message += '<br><b>Repeat this route for </b><br>' + repeatDays + " days.";
        }
        
        alert.message += '<br><b>Stops: </b>';
        for(let i = 0; i<elements.length; i++){
            if(elements[i].value!=='') {
                /* Uncomment to show coordinates on verification*/
                //let lat = document.getElementById('lat-stop-'+(i+1)).value;
                //let lon = document.getElementById('lon-stop-'+(i+1)).value;

                alert.message += '<br>' + elements[i].value;
                //alert.message += '<br>Coords: '+ lat + ';' + lon;
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

                var data= {};
                data.city = targetCity;
                data.start_time = datetimeInput;//to format for mySQL on the backend
                data.duration = targetDuration;
                data.meeting_point = targetMeetingPoint;
                data.repeatDays =  repeatDays;
                var prov_stops = [];
                data.coordinates = '';

                for(let i = 0; i<elements.length; i++){
                    prov_stops.push(elements[i].value);

                    let lat = document.getElementById('lat-stop-'+(i+1)).value;
                    let lon = document.getElementById('lon-stop-'+(i+1)).value;
                    //coordinates will be a matrix: data.coordinates[0][0] = longitude of el 0. data.coordinates[0][1] = latitude of el 0. 
                    data.coordinates+= lon+","+lat+"_";
                }

                data.stops = prov_stops.join('_');

                var prov_durations = '';
                for(let i = 0; i<durations.length; i++){
                    if(i==durations.length-1){
                        prov_durations+=durations[i].value;
                    } else {
                        prov_durations+=durations[i].value+',';//comma is not a problem, minutes are integers (parseInt)
                    }
                    
                }

                data.durations = prov_durations;

                data.enable = enableTimeline;
    
                $.ajax({
                    url: 'phpUtilities/schedulePub.php',
                    type: 'POST',
                    data: data,
                    
                    success: (output) => {
                        console.log(output);
                        //document.getElementById('schedule-pub-error').innerHTML=output;
                        location.href = 'dashboard.php';
                    }
                    
                });
                
            }
            }
        ];

    }
    
    document.body.appendChild(alert);
    return alert.present();
}