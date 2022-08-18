const ionSegment =  document.querySelector('#agent-segment');

//handle click on dashboard segment
ionSegment.addEventListener("ionChange", (event) => {

    if(event.detail.value === 'Manage Pub Crawls'){
        document.getElementById("manage-pubcrawl").style.display="block";
        document.getElementById("manage-agent-account").style.display="none";

    } else if (event.detail.value === 'Manage Account') {
        document.getElementById("manage-pubcrawl").style.display="none";
        document.getElementById("manage-agent-account").style.display="block";

    }

});

var sendCoordsInterval = '';
var sendingCoords;

function startCrawl(id) {

    sendingCoords = document.getElementById('sending-coords').innerHTML.trim();//value or innerHTML
    //console.log(sendingCoords);
    if(sendingCoords==='false') {//startCrawl
        var data = {};
        data.id = id;

        $.ajax({
            url: 'phpUtilities/startCrawl.php',
            type: 'POST',
            data: data,

            success: (output) => {
            //success: () => {
                console.log(output);
                //sendingCoords = true;
                //sendCoordsInterval = setInterval(setSendCoords, 10000);//sends coords to DB every minute
                location.reload();
            }
        });
    }//send coords (sendCoords)

}

function setSendCoords() {

    sendCoordsInterval = setInterval(() => {
        sendingCoords = document.getElementById('sending-coords').innerHTML.trim();

        //let pubCrawlId = document.getElementById('chosen-pub-crawl-id').innerHTML.trim();
        console.log("SendingCoords HTML: "+sendingCoords);
        if (sendingCoords!=='false') {

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
        //success: () => {
            //console.log("Coordinates sent: "+output);

            //update the html of the coordinates with the output content:
            sendingCoords = output;//update sendingCoords
            //sendingCoords = true;
            //sendCoordsInterval = setInterval(()=>setSendCoords, 10000);//sends coords to DB every minute
            //location.reload();
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

            if(output !== '') {
                document.getElementById('change-password-info').innerHTML = "<ion-item lines='none' color='main-bg'><ion-label color='white'>"+output+"</ion-label></ion-item>";
            } else {
                document.getElementById('change-password-info').innerHTML = "<ion-item lines='none' color='main-bg'><ion-label color='white'>Verification link sent to your email.</ion-label></ion-item>";
                
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