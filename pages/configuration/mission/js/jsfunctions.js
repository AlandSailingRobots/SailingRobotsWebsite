/****************************************************************************************
 *
 * Purpose:
 *		Handles waypoint configuration on the config page. Waypoints can be:
 *			-	Dragged (position changes)
 *			- 	Created (left click)
 *			-	Removed (right click)
 * Developer Notes:
 *
 *
 ***************************************************************************************/

	var firstUtilityCall = true;
	//database data objects acquired through utility.js
	var waypointsObj;
	var boatPosObj;

	//Used for displaying "selected waypoint" on the webpage
	var drag_lng_status;
	var drag_lat_status;
	var drag_id_status;
	var radius_setting_box;
	var setRadius = 15;

	var map;
	var ajaxBusy;

	$(document).ready(function(){
		drag_lng_status = document.getElementById ('lngStatus');
		drag_lat_status = document.getElementById ('latStatus');
		drag_id_status = document.getElementById ('idStatus');
		radius_setting_box = document.getElementById('radSetting');
		ajaxBusy = false;

		u_initWaypoints();
		u_initGpsData();

		$(document).ajaxStart( function() {
			ajaxBusy = true;
		}).ajaxStop( function() {
			ajaxBusy = false;
		});

	});

	//Called from utility.js when all initial ajax calls have finished
	function utilityCallback(){

		if (firstUtilityCall){
			waypointsObj = u_getWaypoints();
			initMap();
			initMarkers(map);
			boatMarker();
			firstUtilityCall = false;
		}
	}

	function refreshWhenReady(){

	    if (ajaxBusy){
	        setTimeout(function(){
	            refreshWhenReady();
	        }, 300);
	    }else{
	        window.location.reload(1);
	    }

	}

	//Called from button, hence the seemingly pointless function
	function reloadPage(){

		window.location.reload(1);

	}

	function initMap() {
		//Keep track of current id for later insertions
		markerFunctions_resetLoadedWaypoints();
		newWaypoints = [];

		console.log("initMap started");

		var mapCenter = new google.maps.LatLng(60.093610472518066, 19.938812255859375);
		if (typeof waypointsObj[0] != 'undefined'){
			mapCenter = new google.maps.LatLng(Number(waypointsObj[0].latitude), Number(waypointsObj[0].longitude));
		}

		map = new google.maps.Map(document.getElementById('map'), {
		  zoom: 13,
		  center: mapCenter
		});

		map.addListener('click', function(event) {
			var newMarker = markerFunctions_placeMarker(event.latLng, -1, -1, radius_setting_box.value, true);
			bindMarkerEvents(newMarker);
			markerFunctions_renderLine(newMarker);
		});


	}

	function initMarkers(setMap){
		markerFunctions_setMarkerMap(map);

		for (var i = 0; i < waypointsObj.length; i++) {
			var newMarker = markerFunctions_placeMarker(new google.maps.LatLng(waypointsObj[i].latitude, waypointsObj[i].longitude),
			 	waypointsObj[i].id_waypoint,i, waypointsObj[i].radius, false);

			bindMarkerEvents(newMarker);
			markerFunctions_renderLine(newMarker);
	  }
	}

	function boatMarker(){

		boatPosObj = u_getGpsData();
		var boatPos = {lat: Number(boatPosObj.latitude), lng: Number(boatPosObj.longitude)};
		var boatMarker = new google.maps.Marker({
			position: boatPos,
			map: map,
			icon :'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
			title: "Boat position"
		});

	}

	//Gets all loaded waypoints and pushes them to server (replacing old ones)
	function waypointsToDatabase(){
		var wp_data;
		var wps = [];
		var existing = markerFunctions_getLoadedWaypoints();

		//Collect all waypoint data in suitable array
		for (var i = 0; i < existing.length; i++){
			if(existing[i] != null){
				wp_data = {
					position: {latitude: existing[i].position.lat(), longitude: existing[i].position.lng(), radius: existing[i].radius}
				}
				wps.push(wp_data);
			}
		}

		u_pushWaypoints({json: JSON.stringify(wps)});
		refreshWhenReady();

	}

	var bindMarkerEvents = function(marker) {
	    google.maps.event.addListener(marker, "rightclick", function (e) {
	        var marker = this;
	        markerFunctions_removeMarker(marker);
	    });
		google.maps.event.addListener(marker, 'drag', function() {
			if (drag_lat_status!=null && drag_lng_status!=null){
				drag_lat_status.value = this.position.lat();
				drag_lng_status.value = this.position.lng();
				drag_id_status.value = this.db_id;
				markerFunctions_updateMarkerPosition(this);
			}
		  });
	}
