let map, boatMarker, windDirectionMarker, lineToWaypoint, routePolyline;
let route = [];
let boatInfoWindow = null;
let windInfoWindow = null;
var boatPos= new google.maps.LatLng(60.107900, 19.922975);
//var iconBase = './images';
//var img = new google.maps.MarkerImage(iconBase + '/boat.png', null, null, new google.maps.Point(32, 32), new google.maps.Size(64, 64));
var boatHeading = 0;
var windHeading = 180;
var boatIcon = {
    path: 'M -1,-2 L 0,-4 L 1,-2 Q1.5,0 1,2 L -1,2 Q -1.5,0 -1,-2',
    strokeColor: '#F89406',
    fillOpacity: 1,
    rotation: boatHeading,
    scale: 3,
    strokeweight: 1,
};
var boatArrowIcon = {
    path: 'M 0,0 L0,-2 L-1,-2 L0,-3 L1,-2 L0,-2 L0,3',
    strokeColor: '#F89406',
    fillOpacity: 1,
    rotation: boatHeading,
    scale: 3,
    strokeweight: 1,
};
var windDirectionIcon = {
    path: 'M0,0 L-1,2 L1,2 L0,0 L0,8',
    strokeColor: '#1F3A93',
    fillOpacity: 1,
    rotation: windHeading,
    scale:2,
    strokeweight: 0.1,
};

var waypoints;
let gpsData;

//MAIN
$( document ).ready(function() {

    console.log( "loading functions" )
    waypoints = getMissionWaypoints();
    gpsData = getGpsData();
    initMap();
    console.log( "ready!" );
    debug();
});

//FUNCTIONS

function debug() {
    console.log("=================================================================" +'\n' + "DEBUG START");
    console.log("currentMisson -  waypoints: ");
    console.log(waypoints);
    console.log("dataLogs_gps - latest known location: ");
    console.log(gpsData);

    console.log("=================================================================" +'\n' + "END");
}
//TODO get currentMission waypoints
function getMissionWaypoints() {
    var value = null;
    //var url = '../include/live_mission.php';
    var url = 'http://wst.local/AlandSailingRobots/pages/logs/live/include/live_mission.php';
    console.log("running getSIngleValueUsingJQuery");
    jQuery.ajax({
        type: 'GET',
        url: url,
        async: false,
        contentType: "application/json",
        data: {data: 'getMissionWaypoints'},
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

function getGpsData() {
    var value = null;

    //var url = '../include/live_mission.php';
    var url = 'http://wst.local/AlandSailingRobots/pages/logs/live/include/live_mission.php';
    console.log("running getSIngleValueUsingJQuery");
    jQuery.ajax({
        type: 'GET',
        url: url,
        async: false,
        contentType: "application/json",
        data: {data: 'getGpsData'},
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
//TODO end



//waypoints = [{"id":1503486574,"id_mission":41,"rankInMission":1,"isCheckpoint":0,"name":"","latitude":60.1079,"longitude":19.92235,"declination":6,"radius":30,"stay_time":0,"harvested":1},{"id":1503486622,"id_mission":41,"rankInMission":2,"isCheckpoint":0,"name":"","latitude":60.10584,"longitude":19.92246,"declination":6,"radius":30,"stay_time":0,"harvested":0},{"id":1503486665,"id_mission":41,"rankInMission":3,"isCheckpoint":0,"name":"","latitude":60.10549,"longitude":19.92049,"declination":6,"radius":30,"stay_time":0,"harvested":0},{"id":1503486698,"id_mission":41,"rankInMission":4,"isCheckpoint":0,"name":"","latitude":60.10793,"longitude":19.9216,"declination":6,"radius":30,"stay_time":60,"harvested":0}]



/**
var waypoints =[
    {id: 1, latitude: 60.107900, longitude: 19.922350, radius:30, harvested:1},
    {id: 2, latitude: 60.105840, longitude: 19.922460, radius:30, harvested:1},
    {id: 3, latitude: 60.105490, longitude: 19.920490, radius:30, harvested:0},
    {id: 4, latitude: 60.107930, longitude: 19.921600, radius:30, harvested:0},
];
**/

//Initialise map and place first boat marker
function initMap() {
    //var latte = getMissionWaypoints();
    map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(getMeanLat(waypoints), getMeanLng(waypoints)),
        zoom: calculateZoom(waypoints),
    })

    boatMarker = new google.maps.Marker({
        position: boatPos, //TODO get position from function that creates some obj
        icon: boatIcon,
        map: map
    });
    boatInfoWindow = new google.maps.InfoWindow({
        content:''
    });
    google.maps.event.addListener(boatMarker, 'click', function() {
        showInfoWindow(boatMarker, boatInfoWindow, getBoatInfo())
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
        showInfoWindow(windDirectionMarker, windInfoWindow, getWindInfo())
        windInfoWindow.open(map, windDirectionMarker);
    });

    lineToWaypoint = new google.maps.Polyline({
        path: [boatPos, getNextWaypointPos()],
        geodesic: true,
        strokeColor: '#FFFF00',
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

    google.maps.event.addListener(map, 'drag', function() {
        refreshInfo();
    });

    google.maps.event.addListener(map, 'zoom_changed', function(){
        rescaleMarkers();
        console.log(map.getZoom());
    });
}
//initMap();

//Refresh map without refreshing page
setInterval( function() {
    refreshInfo()
}, 5000);

//###### PLACEHOLDER FUNCTIONS #######
//TODO get data from php script
function getNewHeading(heading){
    heading = heading+((Math.round(Math.random()) * 2 - 1) * Math.floor(Math.random()*10)); //rand between 0 and 10 multiply by -1 or 1
    return heading;
}

//TODO get data from php script
function getNewPos(pos){
    return new google.maps.LatLng(pos.lat()+((Math.round(Math.random()) * 2 - 1)*0.00001), pos.lng()+((Math.round(Math.random()) * 2 - 1)*0.00001));

}

function getBoatInfo(){
    var lat = boatPos.lat().toFixed(5);
    var lng = boatPos.lng().toFixed(5);
    var head = boatHeading.toString();
    var bearToWP = google.maps.geometry.spherical.computeHeading(boatPos, getNextWaypointPos()).toFixed();
    var speed = Math.random().toFixed(3);
    var contentString = "<div class = 'title'>" +
        "<h3>" + "ASPire" + "</h3>" +
        "<div class = 'info'>" +
        "<h4> lat: " + lat + " lng: " + lng + "</h4>" +
        "<h4> heading: " + head + "°" + "</h4>" +
        "<h4> bearingToWP: " + bearToWP + "°" + "</h4>" +
        "<h4> speed: " + speed + " m/s" + "</h4>" +
        "</div>";

    return contentString
}

function getWindInfo(){
    var head = windHeading.toString();
    var speed = (Math.random()*10).toFixed(3);
    var contentString = "<div class = 'title'>" +
        "<h3>" + "Wind" + "</h3>" +
        "<div class = 'info'>" +
        "<h4> heading: " + head + "°" + "</h4>" +
        "<h4> speed: " + speed + " m/s" + "</h4>" +
        "</div>";

    return contentString
}
//#####################################

function refreshInfo(){
    boatHeading = getNewHeading(boatHeading);
    windHeading = getNewHeading(windHeading);
    boatPos = getNewPos(boatPos);
    updateMarker(boatMarker, boatPos, boatHeading);
    updateMarker(windDirectionMarker, boatPos, windHeading);
    updateLineToWaypoint(lineToWaypoint, boatPos);
    showInfoWindow(boatMarker, boatInfoWindow, getBoatInfo());
    showInfoWindow(windDirectionMarker, windInfoWindow, getWindInfo());
    updateRoute();
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
    var zoom_lat = Math.floor(Math.log(mapHeight / world_heigth_pix / fraction_lat) / Math.log(2))
    var zoom_lon = Math.floor(Math.log(mapWidth / world_width_pix / fraction_lon) / Math.log(2))

    return Math.min(zoom_lat, zoom_lon, maxZoom)
}

function placeWaypoint(waypoint){
    var marker = new google.maps.Marker({
        position: {lat: waypoint.latitude, lng: waypoint.longitude},
        map: map,
        label: waypoint.rankInMission.toString(),
        title: 'Waypoint : '+waypoint.rankInMission,
        icon: { //temp icon never shown because immediately replace by red or green one
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

    updateWaypoint(waypoint, marker, radius)
}

function getNextWaypointPos(){
    for (var wp of waypoints){
        if (!wp.harvested){
            return new google.maps.LatLng(wp.latitude, wp.longitude)
        }
    }
    return boatPos
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

    boatMarker.setIcon(boatMarker.icon);
    windDirectionMarker.setIcon(windDirectionMarker.icon);
}

function updateRoute(){
    route.push(boatPos);
    // if (route.length>100){  //keep only last 100 points of boat's route
    //     route.shift();
    // }
    routePolyline.setPath(route);
}

//New function for InfoWindow prototype to check if it is open or not
google.maps.InfoWindow.prototype.isOpen = function(){
    var map = this.getMap();
    return (map !== null && typeof map !== "undefined");
}