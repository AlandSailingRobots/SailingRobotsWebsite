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

function computeCoordinatesOfLine(arrayOfPoints, markerA, markerB) {
    // Origin of theta is the North
    var theta = computeTheta(markerA.getLatLng(), markerB.getLatLng());
    var result = {};
    var radiusA = parseInt(arrayOfPoints[markerA.options.rankInMission].radius),
        radiusB = parseInt(arrayOfPoints[markerB.options.rankInMission].radius);

    // radius = Math.min(radiusA, radiusB);
    var radius = parseFloat((radiusA + radiusB) / 2);
    var thetad_radius = radius * (1 + Math.abs(Math.sin(theta)));
    // console.log('theta : ', theta * 180 / Math.PI, 'radiusA', radiusA, 'radiusB', radiusB);

    // For the upper line
    for (let i = 0; i < 4; i++) {
        var result_theta = 0;
        if (i < 2) {
            result_theta = theta + Math.PI / 2
        } else {
            result_theta = theta - Math.PI / 2
        }
        if (i === 0 || i === 2) {
            result[i] = rotationVector(result_theta, markerA, thetad_radius);
        } else {
            result[i] = rotationVector(result_theta, markerB, thetad_radius);
        }
    }
    return result;
}

function computeTheta(vectorA, vectorB) {
    return Math.atan2(vectorB['lat'] - vectorA['lat'], vectorB['lng'] - vectorA['lng']);
}

function rotationVector(theta, marker_, radius) {
    var result = {};
    vector_lat = marker_.getLatLng()['lat'];
    vector_lng = marker_.getLatLng()['lng'];
    // According to this answer on Stack Overflow :
    // https://stackoverflow.com/questions/2187657/calculate-second-point-knowing-the-starting-point-and-distance
    result['lat'] = vector_lat + radius * Math.sin(theta) / (110540);
    result['lng'] = vector_lng + radius * Math.cos(theta) / (111320 * Math.cos(vector_lng * Math.PI / 180));

    return result;
}