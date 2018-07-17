//##### CONSTANTS ######
const VALUE_NOT_SET = 1;

//##### GLOBALS ######
let map, legend, boatMarker, windDirectionMarker, courseToSteerMarker, lineToWaypoint, routePolyline;
let route = [];
let boatInfoWindow = null;
let windInfoWindow = null;
let drawingInfoWindow = null;

let absolutePath;
let gpsData;
let windSensorData;
let courseData;
let compassData;
let currentSensorData;
let marineSensorData;

//var boatPos= new google.maps.LatLng(60.107900, 19.922975);
let boatPos     = VALUE_NOT_SET;
let boatHeading = VALUE_NOT_SET;
let windHeading = VALUE_NOT_SET;
let courseToSteerHeading = VALUE_NOT_SET;
let waypoints;
let waypointsArray = [];

var crispStyle = [
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#193341"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#2c5a71"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#29768a"
            },
            {
                "lightness": -37
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#406d80"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#406d80"
            }
        ]
    },
    {
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#3e606f"
            },
            {
                "weight": 2
            },
            {
                "gamma": 0.84
            }
        ]
    },
    {
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry",
        "stylers": [
            {
                "weight": 0.6
            },
            {
                "color": "#1a3541"
            }
        ]
    },
    {
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#2c5a71"
            }
        ]
    }
];

var boatIcon = {
    path: 'M -1,-2 L 0,-4 L 1,-2 Q1.5,0 1,2 L -1,2 Q -1.5,0 -1,-2',
    strokeColor: '#F89406',
    fillOpacity: 1,
    rotation: boatHeading,
    scale: 3,
    strokeweight: 1,
};

var windDirectionIcon = {
    path: 'M0,6 L-0.5,8 L0.5,8 L0,6 L0,14',
    strokeColor: '#26A65B',
    fillOpacity: 1,
    rotation: windHeading,
    scale:2,
    strokeweight: 0.1,
};

var courseToSteerIcon = {
    path: 'M0,0 L0,-8 L-0.5,-6 L0.5,-6 L0,-8',
    strokeColor: '#95A5A6',
    fillOpacity: 1,
    rotation: courseToSteerHeading,
    scale:2,
    strokeweight: 0.1,
};


//##### MAIN ######
$( document ).ready(function() {

    console.log( "loading functions" );

    absolutePath        = getAbsolutePath();

    waypoints           = getData("getMissionWaypoints");
    gpsData             = getData("getGpsData");
    courseData          = getData("getCourseData");
    windSensorData      = getData("getWindSensorData");
    compassData         = getData("getCompassData");
    currentSensorData   = getData("getCurrentSensorData");
    marineSensorData    = getData("getMarineSensorData");
    boatPos             = getNewBoatPos(gpsData);

    initMap();
    updateLiveData();
    debug();

    console.log( "ready!" );
});

//##### FUNCTIONS ######
function debug() {
    console.log("=================================================================" +'\n' + "DEBUG START");
    console.log(window.location);
    console.log(absolutePath);

    console.log("currentMisson -  waypoints: ");
    console.log(waypoints);

    console.log("dataLogs_gps - latest known location: ");
    console.log(gpsData);

    console.log("dataLogs_compass:");
    console.log(compassData);

    console.log("dataLogs_windsensor:");
    console.log(windSensorData);

    console.log("dataLogs_course_calculation");
    console.log(courseData);

    console.log("dataLogs_current_sensors");
    console.log(currentSensorData);

    console.log("dataLogs_marine_sensors");
    console.log(marineSensorData);
    console.log("=================================================================" +'\n' + "END");
}

function printLiveData(data, idKey, idValue) {
    let dataKey = null;
    let dataValue = null;
    Object.keys(data).forEach(function(key) {
        if (!dataKey) {
            dataKey = "<div>" + key + "</div>";
            dataValue = "<div>" + data[key] + "</div>";
        } else {
            dataKey += "<div>" + key + "</div>";
            dataValue += "<div>" + data[key] + "</div>";
        }
    })

    document.getElementById(idKey).innerHTML = dataKey;
    document.getElementById(idValue).innerHTML = dataValue;
}

function updateLiveData() {
    printLiveData(gpsData, "gpsDataKey", "gpsDataValue");
    printLiveData(compassData, "compassDataKey", "compassDataValue");
    printLiveData(windSensorData, "windSensorDataKey", "windSensorDataValue");
    printLiveData(courseData, "courseDataKey", "courseDataValue");
    printLiveData(currentSensorData, "currentSensorDataKey", "currentSensorDataValue");
    printLiveData(marineSensorData, "marineSensorDataKey", "marineSensorDataValue");
}


//Initialise map and place markers
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(getMeanLat(waypoints), getMeanLng(waypoints)),
        zoom: calculateZoom(waypoints),
        streetViewControl: false,
        mapTypeControlOptions: {
            mapTypeIds: ['crispStyle', google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.TERRAIN, google.maps.MapTypeId.SATELLITE]
        },
    });
    map.mapTypes.set('crispStyle', new google.maps.StyledMapType(crispStyle, { name: 'Night Mode' }));

    createLegend();

    boatMarker = new google.maps.Marker({
        position: boatPos,
        icon: boatIcon,
        map: map
    });
    boatInfoWindow = new google.maps.InfoWindow({
        content:''
    });
    google.maps.event.addListener(boatMarker, 'click', function() {
        showInfoWindow(boatMarker, boatInfoWindow, getBoatInfo());
        boatInfoWindow.open(map, boatMarker);
    });

    windDirectionMarker = new google.maps.Marker({
        position: boatPos,
        icon: windDirectionIcon,
        map: map
    });
    windInfoWindow = new google.maps.InfoWindow({
        content:''
    });
    google.maps.event.addListener(windDirectionMarker, 'click', function() {
        showInfoWindow(windDirectionMarker, windInfoWindow, getWindInfo());
        windInfoWindow.open(map, windDirectionMarker);
    });

    courseToSteerMarker = new google.maps.Marker({
        position: boatPos,
        icon: courseToSteerIcon,
        map: map
    });

    lineToWaypoint = new google.maps.Polyline({
        path: [boatPos, getNextWaypointPos()],
        geodesic: true,
        strokeColor: '#D2527F',
        strokeOpacity: 0.7,
        strokeweight: 2,
        icons: [{
            icon: {
                path: google.maps.SymbolPath.FORWARD_OPEN_ARROW,
                strokeOpacity: 1,
                scale: 1.5,
            },
            offset: '0',
            repeat: '50px'
        }],
        map: map,
    });

    routePolyline = new google.maps.Polyline({
        path: route,
        geodesic: true,
        strokeColor: '#446CB3',
        strokeOpacity: 0.7,
        strokeweight: 1,
        map: map
    });

    for (var i=0; i<waypoints.length; i++){
        placeWaypoint(waypoints[i]);
    }

    drawWaypointLine();

    var drawing = new google.maps.drawing.DrawingManager({
        drawingControlOptions:{
            drawingModes:['polygon', 'marker', 'polyline', 'circle'],
        },
        polygonOptions:{
            geodesic: true,
            editable: true,
            draggable: true,
        },
        polylineOptions:{
            geodesic: true,
            editable: true,
            draggable: true,
        },
        markerOptions:{
            draggable: true,
        },
        circleOptions:{
            draggable: true,
            editable: true,
        },
        map:map,
    });
    drawingInfoWindow = new google.maps.InfoWindow({
        content:'',
    });

    google.maps.event.addListener(drawing, 'polygoncomplete', function (e){   //e is the object returned by the event, the rectangle in this case
        polygonManager(e);
    });
    google.maps.event.addListener(drawing, 'markercomplete', function (e) {
       markerManager(e);
    });
    google.maps.event.addListener(drawing, 'polylinecomplete', function (e) {
       polylineManager(e);
    });
    google.maps.event.addListener(drawing, 'circlecomplete', function (e) {
       circleManager(e);
    });
    google.maps.event.addListener(drawing, 'overlaycomplete', function () {
        drawing.setDrawingMode(null);
    });

    google.maps.event.addListener(map, 'zoom_changed', function(){
        rescaleMarkers();
        console.log('zoom changed: ' + map.getZoom());
    });
}

//##### GETTERS ######
function getData(logName) {
    var value = null;
    var url = absolutePath + 'include/live_mission.php';

    jQuery.ajax({
        type: 'GET',
        url: url,
        async: false,
        contentType: "application/json",
        data: {data: logName},
        dataType: 'json',
        success: function(json) {
            value = json;
        },
        error: function(e) {
            console.log("jQuery error message = "+e.message);
        }
    });
    return value;
}

function getNewBoatPos(gpsData) {
    let latitude = gpsData.latitude;
    let longitude = gpsData.longitude;
    boatPos = new google.maps.LatLng(latitude, longitude);

    return boatPos;
}

function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

function getBoatHeading() {
    return compassData.heading;
}

function getWindHeading() {
    return windSensorData.direction;
}

function getSteerHeading(){
    return courseData.course_to_steer;
}

//##### SETTERS ######

//Refresh map without refreshing page
setInterval( function() {
    refreshInfo()
}, 5000);


//###### PLACEHOLDER FUNCTIONS #######

//Hardcoded waypoints
//waypoints = [{"id":1503486574,"id_mission":41,"rankInMission":1,"isCheckpoint":0,"name":"","latitude":60.1079,"longitude":19.92235,"declination":6,"radius":30,"stay_time":0,"harvested":1},{"id":1503486622,"id_mission":41,"rankInMission":2,"isCheckpoint":0,"name":"","latitude":60.10584,"longitude":19.92246,"declination":6,"radius":30,"stay_time":0,"harvested":0},{"id":1503486665,"id_mission":41,"rankInMission":3,"isCheckpoint":0,"name":"","latitude":60.10549,"longitude":19.92049,"declination":6,"radius":30,"stay_time":0,"harvested":0},{"id":1503486698,"id_mission":41,"rankInMission":4,"isCheckpoint":0,"name":"","latitude":60.10793,"longitude":19.9216,"declination":6,"radius":30,"stay_time":60,"harvested":0}]

/**
 var waypoints =[
 {id: 1, latitude: 60.107900, longitude: 19.922350, radius:30, harvested:1},
 {id: 2, latitude: 60.105840, longitude: 19.922460, radius:30, harvested:1},
 {id: 3, latitude: 60.105490, longitude: 19.920490, radius:30, harvested:0},
 {id: 4, latitude: 60.107930, longitude: 19.921600, radius:30, harvested:0},
 ];
 **/

//DEBUG can be used to test without live data
function getNewHeading(heading){
    // heading = heading+((Math.round(Math.random()) * 2 - 1) * Math.floor(Math.random()*10)); //rand between 0 and 10 multiply by -1 or 1
    heading += 10;
    return heading;
}

//DEBUG can be used to test without live data
function getNewPos(pos){
    return new google.maps.LatLng(pos.lat()+((Math.round(Math.random()) * 2 - 1)*0.00001), pos.lng()+((Math.round(Math.random()) * 2 - 1)*0.00001));

}
//#####################################

//##### MAP FUNCTIONS ######
function getBoatInfo(){
    var courseToSteer = courseData.course_to_steer.toFixed(0);
    var head = boatHeading.toString();
    var bearToWP = parseInt(google.maps.geometry.spherical.computeHeading(boatPos, getNextWaypointPos()).toFixed());
    if (bearToWP<0) { bearToWP = (360+bearToWP).toString() } //Convert heading in range [-180, 180] to range [0, 360]
    var distanceToWP = google.maps.geometry.spherical.computeDistanceBetween(boatPos, getNextWaypointPos()).toFixed();
    //var speed = Math.random().toFixed(3);
    var speed = gpsData.speed.toFixed(3);
    var tack = courseData.tack;
    if (tack) { tack = 'Yes'} else { tack = 'No'}

    var contentString = "<div class = 'title'>" +
        "<h3>" + "ASPire" + "</h3>" +
        "<div class = 'info'>" +
        "<h4> courseToSteer: " + courseToSteer + " °" + "</h4>" +
        "<h4> heading: " + head + " °" + "</h4>" +
        "<h4> bearingToWP: " + bearToWP + " °" + "</h4>" +
        "<h4> distanceToWP: " + distanceToWP + " m" + "</h4>" +
        "<h4> tacking: " + tack + "</h4>" +
        "<h4> speed: " + speed + " m/s" + "</h4>" +
        "</div>";

    return contentString
}

function getWindInfo(){
    //var head = windHeading.toString();
    //var speed = (Math.random()*10).toFixed(3);
    var head = windHeading.toString();
    var speed = windSensorData.speed.toFixed(3).toString();
    var contentString = "<div class = 'title'>" +
        "<h3>" + "Wind" + "</h3>" +
        "<div class = 'info'>" +
        "<h4> heading: " + head + "°" + "</h4>" +
        "<h4> speed: " + speed + " m/s" + "</h4>" +
        "</div>";

    return contentString
}

//##########################

function refreshInfo(){
//###### FOR DEBUG ####################
    //uncomment and comment out the corrensponding lines bellow

    //boatHeading = getNewHeading(boatHeading);
    // windHeading = getNewHeading(windHeading);
    //boatPos = getNewPos(boatPos);
//#####################################

    gpsData = getData("getGpsData");
    waypoints = getData("getMissionWaypoints");
    boatPos = getNewBoatPos(gpsData);
    boatHeading = getBoatHeading();
    windHeading = getWindHeading();
    courseToSteerHeading = getSteerHeading();


    updateMarker(boatMarker, boatPos, boatHeading);
    updateMarker(windDirectionMarker, boatPos, windHeading);
    updateMarker(courseToSteerMarker, boatPos, courseToSteerHeading)
    refreshWaypoints();
    updateLineToWaypoint(lineToWaypoint, boatPos);

    showInfoWindow(boatMarker, boatInfoWindow, getBoatInfo());
    showInfoWindow(windDirectionMarker, windInfoWindow, getWindInfo());

    updateRoute();
    updateLiveData();
}

function degrees_to_radians(degrees){
    //Helper function for lat_rad()
    var pi = Math.PI;
    return degrees * (pi/180);
}

function lat_rad(lat){
    //Helper function for calculateZoom()
    var sinus = Math.sin(degrees_to_radians(lat + Math.PI/180));
    var rad_2 = Math.log((1 + sinus)/(1 - sinus))/2;
    return Math.max(Math.min(rad_2, Math.PI), -Math.PI) / 2
}

function calculateZoom(waypoints, mapHeight=550, mapWidth=900, maxZoom=22){
    // at zoom level 0 the entire world can be displayed in an area that is 256 x 256 pixels
    var world_heigth_pix = 256;
    var world_width_pix = 256;


    // get boundaries of the activity route
    var lats = getLats(waypoints);
    var lngs = getLngs(waypoints);
    var maxLat = Math.max(...lats);
    var minLat = Math.min(...lats);
    var maxLng = Math.max(...lngs);
    var minLng = Math.min(...lngs);

    // calculate longitude fraction
    var diff_lon = maxLng - minLng;
    var fraction_lon;
    if (diff_lon < 0) {
        fraction_lon = (diff_lon + 360) / 360;
    } else {
        fraction_lon = diff_lon / 360;
    }

    // calculate latitude fraction
    var fraction_lat = (lat_rad(maxLat) - lat_rad(minLat)) / Math.PI;

    // get zoom for both latitude and longitude
    var zoom_lat = Math.floor(Math.log(mapHeight / world_heigth_pix / fraction_lat) / Math.log(2));
    var zoom_lon = Math.floor(Math.log(mapWidth / world_width_pix / fraction_lon) / Math.log(2));

    return Math.min(zoom_lat, zoom_lon, maxZoom)
}

function placeWaypoint(waypoint){
    var marker = new google.maps.Marker({
        position: {lat: waypoint.latitude, lng: waypoint.longitude},
        map: map,
        label: waypoint.rankInMission.toString(),
        title: 'Waypoint : '+waypoint.rankInMission,
        icon: { //temp icon never shown because immediately replaced by red or green one
            url: 'http://maps.google.com/mapfiles/ms/micons/blue.png',
            labelOrigin: new google.maps.Point(16,12),
        }
    });
    var radius = new google.maps.Circle({
        strokeColor: '#6C7A89',
        fillColor: '#6C7A89',
        fillOpacity: 0.2,
        strokeweight: 0.5,
        map: map,
        center: marker.position,
        radius: waypoint.radius,
    });
    //Add this waypoint to the global array that associates it with it's marker and radius
    waypointsArray.push({wp:waypoint, wpMarker:marker, wpRadius:radius});

    updateWaypoint(waypoint, marker, radius)
}

function drawWaypointLine(){
    let waypointPath = [];
    for (var wp of waypoints){
        waypointPath.push({lat: wp.latitude, lng: wp.longitude});
    }

    var waypointPolyline = new google.maps.Polyline({
        path: waypointPath,
        geodesic: true,
        strokeColor: '#f0f8ff',
        strokeOpacity: 0.7,
        strokeweight: 1,
        map: map
    });

    return waypointPolyline
}

function getNextWaypointPos(){
    for (var wp of waypoints){
        if (!wp.harvested){
            return new google.maps.LatLng(wp.latitude, wp.longitude)
        }
    }
    return boatPos
}

function refreshWaypoints(){
    for (let i=0; i<waypoints.length; i++){
        // waypoints[i].harvested = Math.round(Math.random()); //DEBUG ONLY
        waypointsArray[i].wp = waypoints[i]; //Replace waypoint object in the array with new waypoint object
    }

    for (var node of waypointsArray){
        updateWaypoint(node.wp, node.wpMarker, node.wpRadius);
    }
}

function updateMarker(marker, pos, heading){
    marker.setPosition(pos);
    marker.icon.rotation = heading;
    marker.setIcon(marker.icon);
}

function updateLineToWaypoint(line, pos){
    line.setPath([pos, getNextWaypointPos()])
}

function updateWaypoint(waypoint, marker, radius){
    //Set green or red depending on harvested status
    if(waypoint.harvested){
        marker.icon.url='http://maps.google.com/mapfiles/ms/micons/green.png';
        marker.setIcon(marker.icon);
        radius.setOptions({
            fillColor: '#32CD32',
            strokeColor: '#32CD32'
        });
    } else{
        marker.icon.url='http://maps.google.com/mapfiles/ms/micons/red.png';
        marker.setIcon(marker.icon);
        radius.setOptions({
            fillColor: '#ff0000',
            strokeColor: '#ff0000'
        });
    }
}

function showInfoWindow(marker, infoWindow, content){
    infoWindow.setContent(content);
    if (infoWindow.isOpen()) {
        infoWindow.close();
        infoWindow.open(map, marker);
    }
}

function getLats(dict){
    var lats = [];
    for (var i=0; i<dict.length; i++){
        lats.push(dict[i].latitude);
    }
    return lats
}

function getLngs(dict){
    var lngs = [];
    for (var i=0; i<dict.length; i++){
        lngs.push(dict[i].longitude);
    }
    return lngs
}

function getMeanLat(dict){
    var latSum=0;
    for (var i=0; i<dict.length; i++){
        latSum += dict[i].latitude;
    }
    return latSum/dict.length
}

function getMeanLng(dict){
    var lngSum=0;
    for (var i=0; i<dict.length; i++){
        lngSum += dict[i].longitude;
    }
    return lngSum/dict.length
}

function rescaleMarkers(){
    var newScale = Math.max(1, map.getZoom()-13);
    boatMarker.icon.scale = newScale;
    windDirectionMarker.icon.scale = newScale - 1;
    courseToSteerMarker.icon.scale = newScale - 1;

    boatMarker.setIcon(boatMarker.icon);
    windDirectionMarker.setIcon(windDirectionMarker.icon);
    courseToSteerMarker.setIcon(courseToSteerMarker.icon);
}

function updateRoute(){
    route.push(boatPos);
    // if (route.length>100){  //keep only last 100 points of boat's route
    //     route.shift();
    // }
    routePolyline.setPath(route);
}

function polygonManager(polygon){
    // Click to get poly's area, rightclick to remove from map
    refreshDrawingInfoWindow();
    drawingInfoWindow.open(map);

    google.maps.event.addListener(polygon, 'click', function () {
        drawingInfoWindow.open(map);
        refreshDrawingInfoWindow();
    });
    google.maps.event.addListener(polygon.getPath(), 'set_at', function () {
        refreshDrawingInfoWindow();
    });
    google.maps.event.addListener(polygon.getPath(), 'insert_at', function () {
        refreshDrawingInfoWindow();
    });

    polygon.addListener('rightclick', function () {
        polygon.setMap(null);
        drawingInfoWindow.close();
    });

    function refreshDrawingInfoWindow(){
        let area = google.maps.geometry.spherical.computeArea(polygon.getPath());

        drawingInfoWindow.setPosition(polygon.my_getBounds().getCenter());
        drawingInfoWindow.setContent('<h4>' + 'Area: ' + area.toFixed() + ' m²' + '</h4>');
    };
}

function markerManager(marker){
    var distancePolyline = new google.maps.Polyline({
        map:map,
        path: [boatPos, marker.getPosition()],
        strokeColor:'#000',
        strokeOpacity: 0.2,
    });

    drawingInfoWindow.open(map, marker);
    refreshDrawingInfoWindow();

    google.maps.event.addListener(marker, 'click', function () {
        drawingInfoWindow.open(map, marker);
        refreshDrawingInfoWindow();

    });
    google.maps.event.addListener(marker, 'dragstart', function () {
        drawingInfoWindow.open(map, marker);
        refreshDrawingInfoWindow();
    });
    google.maps.event.addListener(marker, 'drag', function () {
        distancePolyline.setPath([boatPos, marker.getPosition()]);
        refreshDrawingInfoWindow();
    });

    marker.addListener('rightclick', function () {
        distancePolyline.setMap(null);
        drawingInfoWindow.close();
        marker.setMap(null);
    });

    function refreshDrawingInfoWindow(){
        let dist = google.maps.geometry.spherical.computeDistanceBetween(boatPos, marker.getPosition());

        var contentString = '<h4>' + 'Distance: ' + dist.toFixed() + ' m' + '</h4>';
        drawingInfoWindow.setContent(contentString);
    };
}

function polylineManager(polyline){
    drawingInfoWindow.open(map);
    refreshDrawingInfoWindow();
    var polylinePath = polyline.getPath();

    google.maps.event.addListener(polyline, 'click', function () {
        drawingInfoWindow.open(map);
        refreshDrawingInfoWindow();
    });
    google.maps.event.addListener(polylinePath, 'set_at', function () {
        refreshDrawingInfoWindow();
    });
    google.maps.event.addListener(polylinePath, 'insert_at', function () {
        refreshDrawingInfoWindow();
    });

    function refreshDrawingInfoWindow(){
        let length = google.maps.geometry.spherical.computeLength(polyline.getPath());
        polylinePath = polyline.getPath();

        drawingInfoWindow.setPosition(polylinePath.getAt(polylinePath.getLength()-1));
        drawingInfoWindow.setContent('<h4> Length: ' + length.toFixed() + ' m </h4>');
    };

    polyline.addListener('rightclick', function () {
        polyline.setMap(null);
        drawingInfoWindow.close();
    });
}

function circleManager(circle){
    drawingInfoWindow.open(map);
    refreshDrawingInfoWindow();

    google.maps.event.addListener(circle, 'click', function () {
        drawingInfoWindow.open(map);
        refreshDrawingInfoWindow();
    });
    google.maps.event.addListener(circle, 'radius_changed', function () {
        refreshDrawingInfoWindow();
    });
    google.maps.event.addListener(circle, 'drag', function () {
        refreshDrawingInfoWindow();
    });

    circle.addListener('rightclick', function () {
        circle.setMap(null);
        drawingInfoWindow.close();
    });


    function refreshDrawingInfoWindow(){
        drawingInfoWindow.setPosition(circle.getCenter());
        drawingInfoWindow.setContent('<h4> Radius: ' + circle.getRadius().toFixed() + ' m </h4>');
    }
}

function createLegend(){
    legend = document.getElementById('legend');
    map.controls[google.maps.ControlPosition.LEFT_TOP].push(legend);

    var div = document.createElement('div');
    div.innerHTML = '<h5><p style="color:#F89406;font-weight:bold">ASPire</p></h5>'
                    + '<h5><p style="color:#26A65B;font-weight:bold">Wind</p></h5>'
                    + '<h5><p style="color:#95A5A6;font-weight:bold">Course to steer</p></h5>'
                    + '<h5><p style="color:#D2527F;font-weight:bold">Next waypoint</p></h5>'
                    + '<h5><p style="color:#446CB3;font-weight:bold">Route</p></h5>';
    legend.appendChild(div);
}

//New function for InfoWindow prototype to check if it is open or not
google.maps.InfoWindow.prototype.isOpen = function(){
    var map = this.getMap();
    return (map !== null && typeof map !== "undefined");
};

google.maps.Polygon.prototype.my_getBounds=function(){
    var bounds = new google.maps.LatLngBounds()
    this.getPath().forEach(function(element,index){bounds.extend(element)})
    return bounds
}