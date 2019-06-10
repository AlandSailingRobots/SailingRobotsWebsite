//*****************************************************************************
//                                                                            *
//                            TOOLS & UTILITIES                               *
//                                                                            *
//*****************************************************************************
function deleteAllChildren(parentNode) {
    while (parentNode.firstChild) {
        parentNode.removeChild(parentNode.firstChild);
    }
}

function askNewPoint() {
    // Popup for creation of a point
    return "<br/> Do you want to add a point here ? <br/>\n" +
        "<div id=\'buttonContainerMap\'>\n    " +
        "<button name=\'newWaypoint\' class=\'btn btn-info addPoint\' id=\'newWaypoint\'>Waypoint</button>\n    " +
        "<button name=\'newCheckpoint\' class=\'btn btn-success addPoint\' id=\'newCheckpoint\'>Checkpoint</button>\n" +
        "</div>s";
}

function askEditPoint(point) {
    var type = "";
    if (point.isCheckpoint == "1") {
        type = "Checkpoint";
    } else {
        type = "Waypoint";
    }

    // Popup when click on a marker
    // I know I do something ugly with the id, but I din't find any better solution to get the marker on which the user clicks
    return type + ": " + point.rankInMission + " - " + point.name + "<br /> \n" +
        "Position: " + point.latitude + ", " + point.longitude + "<br /> \n" +
        "Radius: " + point.radius + " (m) | Stay_time: " + point.stay_time + " (sec) <br /> \n" +
        "<br /> \n" +
        "<div> \n" +
        "<button name='deletePointButton' class='btn btn-danger deletePoint'  id='rankInMission:" + point.rankInMission + "|id:" + point.id + "' >Delete Point</button> \n" +
        "<button name='editPointButton'   class='btn btn-info   editPointButton " + type + "' id='rankInMission:" + point.rankInMission + "|id:" + point.id + "' >Edit Point</button> \n" +
        "</div>";
}

function splitGPS(string) {
    // This function cleans the result send by leaflet when the user clicks on the map.
    // TODO : check if marker.getLatLng().lat; & marker.getLatLng().lon; do the same
    var res;

    res = string.split("(")[1].split(")")[0];
    return res;
}

function computeDeclination(lat, lon) {
    // TODO : write the function
    return 0;
}

function currentTimeStamp() {
    return Math.floor(Date.now() / 1000);
}

function escapeHtml(text) {
    var map = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        "\"": "&quot;",
        "'": "&#039;"
    };

    return text.replace(/[&<>"']/g, function (m) {
        return map[m];
    });
}

function roundNumber(number, digits) {
    var multiple = Math.pow(10, digits);
    return Math.round(number * multiple) / multiple;
}