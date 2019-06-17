// function map_leaflet()
// {
//*****************************************************************************
//                                                                            *
//                  Class to handle Waypoint/Checkpoint                       *
//                                                                            *
//*****************************************************************************


//*****************************************************************************
//                                                                            *
//                          Initialisation                                    *
//                                                                            *
//*****************************************************************************
//Global Variables:

const maxZoomLevel = 18;
const initialZoomLevel = 13;
const attribution_mapbox = "Map data &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors, <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, Imagery © <a href=\"http://mapbox.com\">Mapbox</a>";
const mapbox_url = "https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}@2x.png?access_token=";
const accessToken = "pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw";


// Initialisation of the map
var mymap = L.map('map');
var geoJsonWaterLayer;
initMap(60.1, 19.935, mymap);
var popup = L.popup();
var listOfPoints = document.getElementById('listOfPoints'); // To manage the list of item
var isOpen = false;                 // To handle the popup of creation of point
var numberOfPoints = listOfPoints.childElementCount;
var arrayOfPoints = [];             // To store the different waypoints & checkpoints
var arrayOfMarker = [];             // To store the different marker of the map.
var arrayOfCircle = [];
var coordGPS;                       // Coord of where we clicked on the map
var timeStamp = currentTimeStamp(); // We need an unique ID to link the html tag / the marker object / the point object
var polyline;                       // Declaration of a global variable. LeafletJS object (polyline)
var LatLngs = Array();              // Array used to store the coordinates of the markers to draw the polyline
var arrayOfPolylineSup = Array(),
    arrayOfPolylineInf = Array();

var name;
var lat;
var lon;
var rankInMission;

var depth_points = null;

// Initialize the map which is centered on the given lat, lng
function initMap(lat, lon, mymap) {

    var url_acces = mapbox_url + accessToken;
    mymap.setView([lat, lon], initialZoomLevel);
    var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png");
    var mapbox_streets = L.tileLayer(
        url_acces,
        {
            maxZoom: maxZoomLevel,
            attribution: attribution_mapbox,
            id: "mapbox.streets"
        });
    var mapbox_satellite = L.tileLayer(
        mapbox_url + accessToken,
        {
            maxZoom: maxZoomLevel,
            attribution: attribution_mapbox,
            id: "mapbox.satellite"
        });
    var base_maps = {
        "Mapbox Streets (Default)": mapbox_streets,
        "Open Street Maps": osm,
        "Satellite": mapbox_satellite
    };
    var golden = L.marker([60.1, 19.935]).bindPopup('This is the center point');
    var other = L.marker([60.2, 19.937]).bindPopup('This is the double point');
    depth_points = L.layerGroup([golden, other]);
    geoJsonWaterLayer = L.geoJSON(undefined);
    overlays = {
        "depth_overlay": depth_points,
        "water_overlay": geoJsonWaterLayer
    };
    mapbox_streets.addTo(mymap);
    L.control.layers(base_maps, overlays).addTo(mymap);

    // Event click on map
    mymap.on('click', onMapClick);
    mymap.on('moveend', onMapMove);
}

//*****************************************************************************
//                                                                            *
//                      Functions Used By The Events                          *
//                                                                            *
//*****************************************************************************

function onMapMove() {
    getMapBoundingBoxAndSendToBeProcessed();
}

// This function handles the click on the map.
// It will display a popup asking the user which point he would liek to add
// Then this function displays the modal form to add complete the creation
// of the waypoint / checkpoint. The cancel button is also managed here.
function onMapClick(e) {
    coordGPS = splitGPS(e.latlng.toString());

    // One click opens the popup, another closes it.
    if (isOpen) {
        mymap.closePopup();
        isOpen = false;
    } else {
        popup.setLatLng(e.latlng).setContent("You clicked the map at " + coordGPS + askNewPoint()).openOn(mymap);
        isOpen = true;
    }
}

// Event when click on a button of the popup
$('#map').on('click', '.addPoint', addPointModelExample);

function addPointModelExample() {
    // Edit the modal form depending of which kind of point we want to create.
    let examplePoint;
    if ($(this).attr('id') == 'newCheckpoint') {
        examplePoint = new CheckPoint();
    } else {
        examplePoint = new WayPoint();
    }
    editModalToPoint(examplePoint);

    // Change the default values depending on which type of point we want to add
    // We loose the 'feature' of having the previous entered value.
    $('#newPointRadius').val(examplePoint.defaultRadius);
    $('#newPointStay_time').val(examplePoint.defaultStay_time);

    // Modify the value of the longitude and latitude form to allow
    // the user to correct the value manually if needed.
    $('#newPointLatitude').val(coordGPS.split(', ')[0]);
    $('#newPointLongitude').val(coordGPS.split(', ')[1]);

    // Display the modal form
    $('#createPointModal').modal('show');
}

// Use the span tags inside the modal form to adapt the text for a waypoint or a checkpoint
function editModalToPoint(point) {
    var waypointOrCheckpoint = $('.waypointOrCheckpoint');

    for (var i = waypointOrCheckpoint.length - 1; i >= 0; i--) {
        // Deleting previous text
        waypointOrCheckpoint[i].removeChild(waypointOrCheckpoint[i].firstChild);

        // Creating a child (easier to remove than just using TextNode)
        var txt_env = document.createElement('span');
        txt_env.classList.add(point.classText);
        txt_env.appendChild(document.createTextNode(point.text));
        waypointOrCheckpoint[i].appendChild(txt_env);
    }
}

// Cancel
$('#cancelNewPoint').on('click', function () {
    // Reset to default values
    $(':input', '#createPointModal').val("");

    // Hide
    $('#createPointModal').modal('hide');
    mymap.closePopup();
    isOpen = false;
});

// It would be better to use an event in the script, but this doesn't work, I don't know why.
// $('#confirmNewPoint').on('click', createNewPoint());

// This function create a new point.
// It is called when the user confirm the creation of a new point
function createNewPoint() {
    var listOfPoints = document.getElementById('listOfPoints');
    // var newPoint       = document.createElement('li');
    var color; // Of Icon on the map

    // Var to create a new point object
    let name = escapeHtml($('#newPointName').val()),
        id_mission = $('#missionSelection').children(':selected').attr('id'),
        radius = escapeHtml($('#newPointRadius').val()),
        stay_time = parseInt($('#newPointStay_time').val()) * 60, // So we get seconds
        lat = escapeHtml($('#newPointLatitude').val()),
        lon = escapeHtml($('#newPointLongitude').val()),
        declination = escapeHtml($('#newPointDeclination').val()),
        rankInMission = ++numberOfPoints,

        // console.log("GPS : ", coordGPS, " lat ", coordGPS.split(',')[0], " lon ", coordGPS.split(',')[1]);
        // Update the timestamp for the new point
        timeStamp = currentTimeStamp();

    // Last thing to compute
    // declination = computeDeclination(lat, lon);

    // We can now create an instance of the class Point
    let newPoint_JS;
    if ($('.waypointOrCheckpoint')[0].firstChild.classList.contains('isCheckpoint')) {
        newPoint_JS = new CheckPoint(timeStamp, id_mission, rankInMission, name, lat, lon, declination, radius, stay_time);
    } else {
        newPoint_JS = new WayPoint(timeStamp, id_mission, rankInMission, name, lat, lon, declination, radius, stay_time);
    }

    // Add a marker on the map
    createMarker(newPoint_JS, lat, lon);

    // We now close the popup
    $('#createPointModal').modal('hide');
    mymap.closePopup();
    isOpen = false;
}

// This function update the display of the list by manipulating the DOM
// elements of the page when a marker is "dragged'n'dropped"
function updateListItems(marker, editOrMove) {
    var index = marker.options.rankInMission,
        id = marker.options.id;

    if (editOrMove == "move") {
        // Update the position according to the marker
        arrayOfPoints[index].latitude = roundNumber(marker.getLatLng().lat, 5);
        arrayOfPoints[index].longitude = roundNumber(marker.getLatLng().lng, 5);

        // Update the position of the associated circle
        arrayOfCircle[index].setLatLng(marker.getLatLng(), {});
        // arrayOfCircle[index].options.lat  = roundNumber(marker.getLatLng().lat, 5);
        // arrayOfCircle[index].options.longitude = roundNumber(marker.getLatLng().lng, 5);
    }

    // We create another li element
    var listOfPoints = document.getElementById('listOfPoints');
    var newPoint = document.createElement('li');

    newPoint.setAttribute("id", id);
    newPoint.setAttribute("class", "point list-group-item");
    newPoint.appendChild(document.createTextNode(arrayOfPoints[index].print()));

    addEditSymbol(newPoint, arrayOfPoints[index]);
    addDeleteSymbol(newPoint, arrayOfPoints[index]);
    addCenterSymbol(newPoint, arrayOfPoints[index]);

    // Which is inserted at the the right place
    var listItem = document.getElementById(id);

    listOfPoints.insertBefore(newPoint, listItem);

    // And we delete the old child
    listOfPoints.removeChild(listItem);
}

function checkPoint(point) {
    let contained = false;
    geoJsonWaterLayer.eachLayer(function (layer) {
        if (layer.contains(point)) {
            contained = true;
        }
    });
    return contained;
}

function boundAroundPoint(sizeInMeters, overall_boundary) {
    var listOfPoints = []
    new_bound = overall_boundary.getNorthWest().toBounds(sizeInMeters);
    let count = 0;
    let max = 1000;
    while (overall_boundary.contains(new_bound.getSouthEast())) {
        let ne_begin = new_bound;
        while (overall_boundary.contains(new_bound.getSouthEast())) {
            let new_latlng = new_bound.getSouthEast();
            if (checkPoint(new_latlng) && listOfPoints.includes(new_latlng) === false) {
                listOfPoints.push(new_latlng);
                count += 1;
            }
            new_bound = new_latlng.toBounds(sizeInMeters);
            new_bound = L.latLng(new_bound.getNorth(), new_bound.getEast()).toBounds(sizeInMeters);
            if (count === max) {
                break;
            }
        }
        if (count === max) {
            break;
        }
        new_bound = L.latLng(ne_begin.getSouth(), overall_boundary.getWest()).toBounds(sizeInMeters);
    }
    console.log('end', listOfPoints.length);
    // for (let i = 0; i < listOfPoints.length; i++) {
    //     L.marker(listOfPoints[i]).addTo(mymap);
    // }

}

function GetGeoJsonForCurrentBoundingBox() {
    let bounds = mymap.getBounds();
    let jsoned = {
        "zoom": mymap.getZoom(),
        "box": {
            'ne': bounds.getNorthEast(),
            'sw': bounds.getSouthWest(),
            'nw': bounds.getNorthWest(),
            'se': bounds.getSouthEast()
        },
        "crs": 'epsg:4326'
    }
    $.ajax({
        type: 'POST',
        url: 'http://127.0.0.1:80/getGeoJson',
        async: false,
        timeout: 3000,
        contentType: 'application/json; charset=utf-8',
        data: JSON.stringify(jsoned),
        success: function (data) {
            geoJsonWaterLayer.addData(data);
        },
        error: function () {
            alert('Fail !');
        }
    })
}

function getMapBoundingBoxAndSendToBeProcessed() {
    if (!mymap.hasLayer(depth_points)) {
        return null
    }
    let currentZoomLevel = mymap.getZoom();
    if (currentZoomLevel < initialZoomLevel || currentZoomLevel > maxZoomLevel) {
        return null
    }
    GetGeoJsonForCurrentBoundingBox();
    boundAroundPoint(4, mymap.getBounds())
}

//*****************************************************************************
//                                                                            *
//                      Read Points to Save Them                              *
//                                                                            *
//*****************************************************************************

// That function is used in the other JS file.
// I put it here to have access to the variables b/c the other file is
// executed in an IEFE (which should be done here too, to avoid any
// potential conflict with the variables namespace)
function saveMissionIntoDB() {
    // console.log('clicked !');
    // console.log(JSON.stringify(arrayOfPoints));
    array_data = {};
    for (let i = 1; i < arrayOfPoints.length; i++) {
        array_data[i] = (arrayOfPoints[i].getDBFormat())
    }
    $.ajax({
        type: 'POST',
        url: 'php/insertPointIntoDB.php',
        contentType: 'application/json; charset=utf-8', // What is sent
        data: JSON.stringify(array_data),
        // dataType: 'json',
        async: false,
        timeout: 3000,
        success: function (data) {
            alert(data);
        },
        error: function () {
            alert('Fail !');
        }
    });
}

//*****************************************************************************
//                                                                            *
//                      Receive & Display Points                              *
//                                                                            *
//*****************************************************************************

// We get the point list in a JSON format, parse
function getMissionPointFromDB(id_mission) {
    // Clean the map
    mymap.remove();
    mymap = L.map('map');

    // Clean the list
    deleteAllChildren(listOfPoints);


    // Get the marker list in JSON object
    $.ajax({
        type: 'POST',
        url: 'php/getPointList.php',
        data: {id_mission: id_mission},
        dataType: 'json', // What is expected
        async: true,
        timeout: 3000,
        success: function (data) {
            displayPointFromDB(data);
        },
        error: function () {
            alert('Fail !');
        }
    });
}

// This function gets a json object, convert it into Point() object,
// fill the array, and display them
function displayPointFromDB(data) {
    // We clean the variables
    arrayOfPoints = [];
    arrayOfMarker = [];
    arrayOfCircle = [];

    var len = data.length;
    // Initialization if there is not point in the mission
    // Centered on Mariehamn
    if (len === 0) {
        listOfPoints.parentNode.style.display = "none";
        initMap(60.1, 19.935, mymap);
    }

    // Adding all points
    for (var i = 0; i < len; i++) {
        let point;

        if (data[i].isCheckpoint == "1") {
            point = $.extend(new CheckPoint(), data[i]);
        } else {
            point = $.extend(new WayPoint(), data[i]);
        }

        // Fullfilling our variables
        lat = point.latitude;
        lon = point.longitude;

        // Centering the map on the first point
        if (i === 0) {
            initMap(lat, lon, mymap);
        }

        // Creating the markers
        createMarker(point, lat, lon);
    }
    numberOfPoints = listOfPoints.childElementCount;

    // Join the markers
    if (numberOfPoints > 0) {
        mymap.removeLayer(polyline);
    }
    // mymap.removeLayer(polylineSup);
    // mymap.removeLayer(polylineInf);

    getMapBoundingBoxAndSendToBeProcessed();
    drawLineBetweenMarkers();
}

//*****************************************************************************
//                                                                            *
//                      Marker & Markers Functions                            *
//                                                                            *
//*****************************************************************************

// Handle the creation of a marker
function createMarker(point, lat, lon) {

    // We add it to the array
    arrayOfPoints[point.rankInMission] = point;
    var newPoint = document.createElement('li');
    // Add class attribute to the <li> element
    newPoint.setAttribute("class", "point");
    newPoint.classList.add('list-group-item');
    newPoint.setAttribute("id", point.id);
    // Add an item to the HTML list
    newPoint.classList.add(point.classText);
    newPoint.appendChild(document.createTextNode(point.print()));
    addEditSymbol(newPoint, point);
    addDeleteSymbol(newPoint, point);
    addCenterSymbol(newPoint, point);
    listOfPoints.appendChild(newPoint);

    // Display the list (useful only once)
    if (listOfPoints.parentNode.style.display == 'none') {
        listOfPoints.parentNode.style.display = "inline-block";
    }

    // New draggable marker
    marker = new L.marker(
        [lat, lon],
        {
            draggable: 'true',
            icon: point.icon_color,
            rankInMission: point.rankInMission,
            id: point.id
        }
    );
    marker.bindPopup(askEditPoint(point));
    marker.on('dragend', markerDrag);
    // Add in marker array
    arrayOfMarker[point.rankInMission] = marker;

    // New 'following' circle
    // Store the circle in an array. It works the same way as it does for the marker
    arrayOfCircle[point.rankInMission] = L.circle(
        [lat, lon],
        parseFloat(point.radius),
        {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.3,
            rankInMission: point.rankInMission,
            id: point.id
        }
    ).addTo(mymap);

    // Update the polyline
    if (polyline != undefined) {
        mymap.removeLayer(polyline);
        //mymap.removeLayer(polylineSup);
        //mymap.removeLayer(polylineInf);
    }
    LatLngs.push(marker.getLatLng());
    polyline = L.polyline(LatLngs, {color: 'red'}).addTo(mymap);

    // Handle the Drag & Drop

    // Finally we display the marker on the map
    mymap.addLayer(marker);
}

function markerDrag(event) {

    var marker = event.target;
    var position = marker.getLatLng();
    // console.log(position);

    // Update the position on the map
    marker.setLatLng(position, {
        draggable: 'true',
        rankInMission: marker.options.rankInMission,
        id: marker.options.id
    }).update();

    // Update the position in our lists.
    updateListItems(marker, "move");

    // Update the polyline
    mymap.removeLayer(polyline);
    removePolylineInfSup();
    drawLineBetweenMarkers();
}

// This functions uses the coordinates of all markers to draw a line between them
function drawLineBetweenMarkers() {
    var size = document.getElementById('listOfPoints').children.length;
    var coordSupInf;

    LatLngs = Array();
    var LatLngsSup = Array();
    var LatLngsInf = Array();
    // mymap.removeLayer(polylineSup);
    // mymap.removeLayer(polylineInf);

    if (size > 1) {
        // Because we start at i = 2 in the loop
        LatLngs.push(arrayOfMarker[1].getLatLng());

        for (var i = 2; i <= size; i++) {
            LatLngs.push(arrayOfMarker[i].getLatLng());

            // Compute points for upper and lower lines for the path
            coordSupInf = computeCoordinatesOfLine(arrayOfMarker[i - 1], arrayOfMarker[i]);

            // Upper line
            LatLngsSup[0] = coordSupInf[0];
            LatLngsSup[1] = coordSupInf[1];
            polylineSup = L.polyline(LatLngsSup, {fillOpacity: 0.3, color: '#afafaf'}).addTo(mymap);
            arrayOfPolylineSup.push(polylineSup);

            // Lower line
            LatLngsInf[0] = coordSupInf[2];
            LatLngsInf[1] = coordSupInf[3];
            polylineInf = L.polyline(LatLngsInf, {fillOpacity: 0.3, color: '#afafaf'}).addTo(mymap);
            arrayOfPolylineInf.push(polylineInf);
        }
    }
    // Draw the main polyline
    polyline = L.polyline(LatLngs, {color: 'red'}).addTo(mymap);
}

function computeCoordinatesOfLine(markerA, markerB) {
    // Origin of theta is the North
    var theta = computeTheta(markerA.getLatLng(), markerB.getLatLng());
    var result = {};
    var radiusA = parseInt(arrayOfPoints[markerA.options.rankInMission].radius),
        radiusB = parseInt(arrayOfPoints[markerB.options.rankInMission].radius);

    // radius = Math.min(radiusA, radiusB);
    var radius = parseFloat((radiusA + radiusB) / 2);
    thetad_radius = radius * (1 + Math.abs(Math.sin(theta)));
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
            result[i] = rotationVector(result_theta, markerA, thetad_radius)
        } else {
            result[i] = rotationVector(result_theta, markerB, thetad_radius)
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

function removePolylineInfSup() {
    for (var i = arrayOfPolylineSup.length - 1; i >= 0; i--) {
        mymap.removeLayer(arrayOfPolylineSup[i]);
        mymap.removeLayer(arrayOfPolylineInf[i]);
    }
    arrayOfPolylineInf = Array();
    arrayOfPolylineSup = Array();
}

//*****************************************************************************
//                                                                            *
//                              EDIT A POINT                                  *
//                                                                            *
//*****************************************************************************

var editedMarkerIndex; // To keep the index of the marker we clicked on

// The HTML we put in bindPopup doesn't exist yet, so we can't just say
// $('#mybutton'). Instead, we listen for click events on the map element which
// will bubble up from the tooltip, once it's created and someone clicks on it.
$('#listOfPoints').on('click', '.customEdit', function () {
    var id_marker = $(this).parent().attr('id');
    editedMarkerIndex = $(this).attr('id').split(':')[1];
    editMarker(id_marker);
});

$('#map').on('click', '.editPointButton', function () {
    var id_element = $(this).attr('id');
    var id_marker = id_element.split('|')[1].split(':')[1];
    editedMarkerIndex = id_element.split('|')[0].split(':')[1]; // It's ugly right ? :p
    editMarker(id_marker);
});

$('#listOfPoints').on('click', '.customCenter', function () {
    var point = arrayOfPoints[$(this).attr('id').split(':')[1]];
    mymap.setView([parseFloat(point.latitude), parseFloat(point.longitude)])

});

function findIdMarker(item) {
    if (item !== undefined) {
        return parseInt(item.id) === parseInt(this.id_marker);
    } else {
        return false
    }
}

function findIndexOfMarker(id_marker) {
    return arrayOfPoints.findIndex(findIdMarker, {id_marker: id_marker})
}

function editMarker(id_marker) {
    // Adapt the modal to the point properties we clicked on

    var point = arrayOfPoints.find(findIdMarker, {id_marker: id_marker});
    editModalToPoint(point);

    // Fill the form with the properties of the marker we selected
    $('#editPointName').val(point.name);
    $('#editPointLatitude').val(point.latitude);
    $('#editPointLongitude').val(point.longitude);
    $('#editPointRadius').val(point.radius);
    $('#editPointStay_time').val(parseInt(point.stay_time) / 60);
    $('#editPointDeclination').val(point.declination);

    // Show the modal
    $('#editPointModal').modal('show');
}

// This works because the modal are already in the HTML document
$('#cancelEditPointButton').on('click', function () {
    $('#editPointModal').modal('hide');
});

$('#confirmEditPointButton').on('click', function (e) {
    // $('#isCheckpointButton').removeClass('active');
    // $('#isWaypointButton').removeClass('active');

    $('#editPointModal').modal('hide');
    // Get the entered value, update the object in the array and the list
    var point = arrayOfPoints[editedMarkerIndex];
    var changed_point;
    if (point.isCheckpoint === "1") {
        changed_point = new CheckPoint()
    } else {
        changed_point = new WayPoint()
    }
    // Name
    changed_point = Object.assign(changed_point, point);
    changed_point.name = escapeHtml($('#editPointName').val());
    changed_point.latitude = (escapeHtml($('#editPointLatitude').val()));
    changed_point.longitude = (escapeHtml($('#editPointLongitude').val()));
    changed_point.radius = (escapeHtml($('#editPointRadius').val()));
    changed_point.stay_time = "" + parseInt(escapeHtml($('#editPointStay_time').val())) * 60;
    changed_point.declination = (escapeHtml($('#editPointDeclination').val()));
    if (!point.equals(changed_point)) {
        point = changed_point
    }
    // Update the list item
    updateListItems(arrayOfMarker[editedMarkerIndex], "drag");

    // Update the position of the marker on the map
    var position = [point.latitude, point.longitude];
    var marker = arrayOfMarker[editedMarkerIndex];
    marker.setLatLng(position, {
        draggable: 'true',
        rankInMission: marker.options.rankInMission,
        id: marker.options.id
    }).update();
});

function createSpan(point, css_name, text) {
    var newEdit = document.createElement('span');
    newEdit.setAttribute("class", `label label-default pull-right ${css_name}`);
    newEdit.setAttribute("id", `rankInMission:${point.rankInMission}`);
    newEdit.setAttribute("point-id", `${point.id}`);

    newEdit.appendChild(document.createTextNode(text));
    return newEdit
}

// Add a edit span to the given node.
function addEditSymbol(newNode, point) {
    var newEdit = createSpan(point, "customEdit", "Edit");
    newNode.appendChild(newEdit);
}

// Add a edit span to the given node.
function addCenterSymbol(newNode, point) {
    var newEdit = createSpan(point, "customCenter", "Center");
    newNode.appendChild(newEdit);
}

//*****************************************************************************
//                                                                            *
//                            DELETE A POINT                                  *
//                                                                            *
//*****************************************************************************

// This is the event 'click' on one delete button.
// This function is pretty ugly because I used JS Object {} rather than Array []
// It turned out that using {} is simplier for the creation of an JSON Object
// The only solution I could come up with, is to copy every point & markers in
// a new object and change the 'rankInMission' of the points after the deleted one
$('#listOfPoints').on('click', '.customDelete', function () {
    var id_marker = $(this).parent().attr('id');
    deleteMarker(id_marker);
});

$('#map').on('click', '.deletePoint', function () {
    var id_element = $(this).attr('id');
    var id_marker = id_element.split('|')[1].split(':')[1];
    deleteMarker(id_marker);

});

function deleteMarker(id_marker) {
    var parentNodeList = document.getElementById('listOfPoints').children;
    var newArrayOfPoints = [],
        newArrayOfMarker = [],
        newArrayOfCircle = [];
    var changeRank = 0;
    var j;
    // Update this different array without the deleted point
    for (var i = 1, len = parentNodeList.length; i <= len; i++) {
        j = i;
        if (changeRank) {
            arrayOfPoints[i].rankInMission = i - 1;
            arrayOfMarker[i].options.rankInMission = i - 1;
            j = i - 1;
        }
        if (arrayOfPoints[i].id == id_marker) {
            changeRank++;
            // Remove marker from the map
            mymap.removeLayer(arrayOfMarker[i]);
            mymap.removeLayer(arrayOfCircle[i]);
            // Delete node from the DOM
            document.getElementById('listOfPoints').removeChild(document.getElementById(id_marker));
        } else {
            newArrayOfMarker[j] = arrayOfMarker[i];
            newArrayOfPoints[j] = arrayOfPoints[i];
            newArrayOfCircle[j] = arrayOfCircle[i];
        }
    }

    // The new points will correctly be indexed
    rankInMission--;
    numberOfPoints--;
    // We 'return' the modified arrayk
    arrayOfPoints = newArrayOfPoints;
    arrayOfMarker = newArrayOfMarker;
    arrayOfCircle = newArrayOfCircle;

    // Update the polyline
    mymap.removeLayer(polyline);    // Delete the central polyline
    removePolylineInfSup();         // Delete the upper and the lower polylines
    drawLineBetweenMarkers();       // Draw them again
};

// Add a delete span to the given node.
function addDeleteSymbol(newNode, point) {
    var newEdit = createSpan(point, "customDelete", "Delete");
    newNode.appendChild(newEdit);
}

//*****************************************************************************
//                                                                            *
//                             DECLINATION                                    *
//                                                                            *
//*****************************************************************************

// declination=0;
function setdecl(v) {
    console.log("declination found: " + v);
    declination = v;
}

function lookupMag(lat, lon) {
    var url =
        "http://www.ngdc.noaa.gov/geomag-web/calculators/calculateIgrfgrid?lat1=" + lat + "&lat2=" + lat + "&lon1=" + lon + "&lon2=" + lon +
        "&latStepSize=0.1&lonStepSize=0.1&magneticComponent=d&resultFormat=xml";
    // $.get(url, function(xml, status){
    //      setdecl( $(xml).find('declination').text());
    // });
    var xmlHTTP = new XMLHttpRequest();
    xmlHTTP.onreadystatechange = function () {
        if (xmlHTTP.readyState == 4 && xmlHTTP.status == 200) {
            setdecl($(xml).find('declination').text());
        }
    }
    xmlHTTP.open("GET", url, true);
    xmlHTTP.send(null);
}

// var geomagnetism = require('geomagnetism');

// // information for "right now"
// var info = geomagnetism.model().point([44.53461, -109.05572]);
// console.log('declination:', info.decl);

// // use a specific date
// var model = geomagnetism.model(new Date('12/25/2017'));
// var info = model.point([44.53461, -109.05572]);
// console.log('declination:', info.decl);
// lookupMag(55.58552,12.1313);



