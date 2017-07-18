/****************************************************************************************
 *
 * Purpose:
 *      Handles display of logs and drawing of boat path up until log id or as defined by range fields.
 *
 *
 * Developer Notes:
 *      - Boat drawing is mostly duplicate code from /live and should probably be centratilzed
 *      - ROUTE_STARTED flag in waypoint database is still not implemented properly for route drawing
 *
 ***************************************************************************************/


    // $("#map").hide();
    var showMap               = false;
    var layerBoat             = null;
    var layerBoatctx          = null;
    var layerCompasheading    = null;
    var layerCompasheadingctx = null;
    var layerWaypoint         = null;
    var layerWaypointctx      = null;
    var layerTWD              = null;
    var layerTWDctx           = null;
    var layerHeading          = null;
    var layerHeadingctx       = null;
    var showMap               = false;
    var layerCanvasctx        = null;
    var pingCanvasctx         = null;
    var layerCanvas           = null;
    var pingCanvas            = null;
    var boat                  = null;
    var mainsail              = null;
    var jib                   = null;
    var rudder                = null;
    var wind                  = null;
    var compass               = null;
    var heading               = null;

    var waypoint = null;
    var tacking  = null;
    var ping     = null;

    var compasHeading     = null;
    var trueWindDirection = null;

    var vHEADING       = 0;
    var vWIND          = 0;
    var vSAIL          = 0;
    var vRUDDER        = 0;
    var vWAYPOINT      = 0;
    var vCTS           = 0;
    var vTACKING       = 0;
    var vCompasHeading = 0;
    var vTWD           = 0;
    var route          = null;

    var m_map      = null;
    var boatPath   = null;
    var log_marker = null;
    var path_endField;
    var path_startField;

    $(document).ready(function(){
        u_initLogData();
        u_initWaypoints();
        u_initRouteData();

    });

    function utilityCallback(){
        initBoat();

        var dataObj = u_getLogData();
        updateBoat(dataObj);
        initMap(dataObj.latitude, dataObj.longitude);
        getWaypoints();

        updatePath();

        drawBoat();

        hideShowMapBoat();
    }

    function initBoat() {
        layerBoatHeading    = document.getElementById("layerBoatHeading");
        layerBoatHeadingctx = layerBoatHeading.getContext("2d");

        layerCompasHeading    = document.getElementById("layerCompasHeading");
        layerCompasHeadingctx = layerCompasHeading.getContext("2d");

        layerWaypoint    = document.getElementById("layerWaypoint");
        layerWaypointctx = layerWaypoint.getContext("2d");

        layerTWD    = document.getElementById("layerTWD");
        layerTWDctx = layerTWD.getContext("2d");

        layerHeading    = document.getElementById("layerHeading");
        layerHeadingctx = layerHeading.getContext("2d");

        layerCanvas    = document.getElementById("layerCanvas");
        layerCanvasctx = layerCanvas.getContext("2d");

        pingCanvas    = document.getElementById("pingCanvas");
        pingCanvasctx = pingCanvas.getContext("2d");

        ping         = new Image();
        ping.src     = "images/ping.png";
        boat         = new Image();
        boat.src     = "images/boat.png";
        mainsail     = new Image();
        mainsail.src = "images/mainsail.png";
        jib          = new Image();
        jib.src      = "images/jib.png";
        rudder       = new Image();
        rudder.src   = "images/rudder.png";
        wind         = new Image();
        wind.src     = "images/windArrow.png";

        compasHeading = new Image();
        compasHeading.src = "images/compasHeading.png"

        trueWindArrow = new Image();
        trueWindArrow.src = "images/trueWindDirection.png";

        compass      = new Image();
        compass.src  = "images/compass.png";
        heading      = new Image();
        heading.src  = "images/headingArrow.png";
        waypoint     = new Image();
        waypoint.src = "images/waypointArrow.png";
        tacking      = new Image();
        tacking.src  = "images/tacking.png";

        pingCanvasctx.drawImage(ping,0,0);

    }

    function initMap(lati, lon) {

        var latLong = {lat: Number(lati), lng: Number(lon)}
        var mapDiv  = document.getElementById("map");

        m_map = new google.maps.Map(mapDiv, {
         center: latLong,
         zoom: 14
        });

        markerFunctions_setMarkerMap(m_map);

        log_marker = new google.maps.Marker({
         position: latLong,
         map: m_map,
         title: 'sailingrobots'
        });
    }


    function updateRangeFields(){
        path_endField = document.getElementById ('endPath');
        path_startField = document.getElementById ('startPath');

        if (route != null){
            //Can be shortened
            //Fills out the route range interface boxes
            if (!Number(path_endField.value)){
                path_endField.value = route[route.length-1].id_gps;
            }else if(Number(path_endField.value) > route[route.length-1].id_gps){
                path_endField.value = route[route.length-1].id_gps;
            }else if(Number(path_endField.value) < route[0].id_gps){
                path_endField.value = route[route.length-1].id_gps;
            }

            if (!Number(path_startField.value)){
                path_startField.value = route[0].id_gps;
            }else if(Number(path_startField.value) < route[0].id_gps){
                path_startField.value = route[0].id_gps;
            }else if(Number(path_startField.value) > route[route.length-1].id_gps){
                path_startField.value = route[0].id_gps;
            }
        }

    }

    function updatePath(){

        //console.log("route found.");
        route = u_getRouteData();
        //Port this to markerFunctions -> Draw squigglies on everything -> Profit
        updateRangeFields();

        var endValue = Number(path_endField.value);
        var startValue = Number(path_startField.value);

        boatPath = markerFunctions_initTrail();

        markerFunctions_drawTrailInRange(route, startValue, endValue);
    }


    function drawBoat() 
    {
        clearRectLayer(layerTWDctx);
        clearRectLayer(layerHeadingctx);
        clearRectLayer(layerWaypointctx);
        clearRectLayer(layerCompasHeadingctx);
        clearRectLayer(layerBoatHeadingctx);
        clearRectLayer(layerCanvasctx);

        if(vTACKING === 1) {
            drawZeroPosition(layerCanvasctx, tacking);
            drawZeroPosition(layerTWDctx, tacking);
            drawZeroPosition(layerHeadingctx, tacking);
            drawZeroPosition(layerWaypointctx, tacking);
            drawZeroPosition(layerCompasHeadingctx, tacking);
            drawZeroPosition(layerCompasHeadingctx, tacking);
        }

        drawCompass();
        drawTWD();
        drawHeading();
        drawWaypoint();
        drawCompasHeading();
        draw_BoatHeading_Rudder_And_Sails();

        restoreLayer(layerBoatHeadingctx);
        restoreLayer(layerCompasHeadingctx);
        restoreLayer(layerWaypointctx);
        restoreLayer(layerHeadingctx);
        restoreLayer(layerCanvasctx);
        restoreLayer(layerTWDctx);

        $("#pingCanvas").hide().fadeIn(50, function() {
            $("#pingCanvas").fadeOut(350);
            });

        function drawCompass() {
            drawZeroPosition(layerCompasHeadingctx, tacking);
            translateCanvas(layerCanvasctx);
        }
        function drawTWD() {
            drawComponent(layerTWDctx, vTWD, trueWindArrow);
        }
        function drawHeading() {
            drawComponent(layerHeadingctx, vCTS, heading);
        }
        function drawWaypoint() {
           drawComponent(layerWaypointctx, vWAYPOINT, waypoint);
        }
        
        function drawCompasHeading() {
            drawComponent(layerCompasHeadingctx, vCompasHeading, compasHeading);
        }

        function drawComponent(layerctx, vValue, image) {
            layerctx.drawImage(compass,0,0);
            translateCanvas(layerctx);
            rotateCanvas(layerctx, vValue);
            drawImage(layerctx, image);
        }

        function draw_BoatHeading_Rudder_And_Sails() {
            var jibdir = 1;
            if (vWIND > 180 && vWIND < 210) {
                jibdir = -1;
            }
            if (vWIND >= 0 && vWIND < 150) {
                jibdir = -1;
            }
            var maindir = 1;
            if (vWIND < 180) {
                maindir = -1;
            }
            var radians = Math.PI/180;

            drawComponent(layerBoatHeadingctx, vHEADING, boat);
            rotateCanvas(layerBoatHeadingctx, vWIND);
            drawImage(layerBoatHeadingctx, wind);
            layerBoatHeadingctx.rotate((maindir*vSAIL-vWIND)*radians);
            layerBoatHeadingctx.drawImage(mainsail,-layerCanvas.width/2,-layerCanvas.width/2);
            layerBoatHeadingctx.rotate((-maindir*vSAIL) * radians);
            layerBoatHeadingctx.translate(0,-layerCanvas.height/6);
            layerBoatHeadingctx.rotate(jibdir*vSAIL*radians);
            layerBoatHeadingctx.drawImage(jib,-layerCanvas.width/2,-layerCanvas.width/2);
            layerBoatHeadingctx.rotate(-jibdir*vSAIL*radians);
            layerBoatHeadingctx.translate(0,(layerCanvas.height/6)+(layerCanvas.height/3.6));
            layerBoatHeadingctx.rotate(vRUDDER*radians);
            layerBoatHeadingctx.drawImage(rudder,-layerCanvas.width/2,-layerCanvas.width/2);
        }

        function drawZeroPosition(layerctx, image) {
            layerctx.drawImage(image,0,0);
        }

        function restoreLayer(layerctx){
            layerctx.restore();
        }

        function translateCanvas(layerctx) {
            layerctx.translate(layerCanvas.width/2, layerCanvas.height/2);
        }

        function rotateCanvas(layerctx, vValue) {
            var radians = Math.PI/180;
            layerctx.rotate(vValue * radians);
        }

        function drawImage(layerctx, image) {
            layerctx.drawImage(image,-layerCanvas.width/2,-layerCanvas.width/2);
        }

        function clearRectLayer(layerctx) {
            layerctx.clearRect(0,0,layerCanvas.width,layerCanvas.height);
            layerctx.save();
        }
    }

    function getWaypoints() 
    {
        initWaypoints(u_getWaypoints());
    }

    function initWaypoints(waypointsObj){

        for (var i = 0; i < waypointsObj.length; i++) {

            var newMarker = markerFunctions_placeMarker(new google.maps.LatLng(waypointsObj[i].latitude, waypointsObj[i].longitude),
                waypointsObj[i].id_waypoint,i, waypointsObj[i].radius, false);

            newMarker.setDraggable(false);
            markerFunctions_renderLine(newMarker);
      }
    }


    function hideShowMapBoat() {
        if(showMap == true) {
            document.getElementById("map").disabled = true;
            document.getElementById("map").style.visibility = "hidden";
            document.getElementById("boatCanvas").disabled = false;
            document.getElementById("boatCanvas").style.visibility = "visible";
            showMap = false;
        }
        else{
            document.getElementById("map").disabled = false;
            document.getElementById("map").style.visibility = "visible";
            document.getElementById("boatCanvas").disabled = true;
            document.getElementById("boatCanvas").style.visibility = "hidden";
            showMap = true;
        }
    }

    function updateBoat(data) 
    {
        vHEADING       = parseFloat(data.heading);
        vWIND          = parseFloat(data.direction);
        vSAIL          = parseFloat(data.sail_command_sail);
        vRUDDER        = parseFloat(data.rudder_command_rudder);
        vWAYPOINT      = parseFloat(data.bearing_to_waypoint);
        vCTS           = parseFloat(data.course_to_steer);
        vTACKING       = parseFloat(data.tack);
        vTWD           = parseFloat(data.true_wind_direction_calc);
        vGpsHeading    = parseFloat(data.heading);
        vCompasHeading = parseFloat(data.heading);

        vSailMin = 5824;
        vSailMax = 7424;
        vSAIL    = (((vSAIL-vSailMin)/(vSailMax-vSailMin))*60)-60;
        vRUDDER  = ((((vRUDDER-4352)/(7616-4352))*90)-45)*-1;
        vWIND    = vWIND+180;
        if(vWIND > 360) 
        {
            vWIND = vWIND -360;
        }
        initBoat();
        drawBoat();
    }


      console.log('              |    |    | \n             )_)  )_)  )_) \n            )___))___))___)\\ \n           )____)____)_____)\\\\ \n         _____|____|____|____\\\\\\\__\n---------\\                   /---------\n  ^^^^^ ^^^^^^^^^^^^^^^^^^^^^\n    ^^^^      ^^^^     ^^^    ^^\n         ^^^^      ^^^');
