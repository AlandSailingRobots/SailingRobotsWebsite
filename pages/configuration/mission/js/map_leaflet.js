// function map_leaflet()
// {
    //*****************************************************************************
    //                                                                            *
    //                  Class to handle Waypoint/Checkpoint                       *
    //                                                                            *
    //*****************************************************************************  

    function Point(id, id_mission, isCheckpoint, rankInMission, name, lat, lon, decl, radius, stay_time, harvested)
    {
        this.id            = id; // Different to the primary key of the DB when it comes to add new points.
        this.id_mission    = id_mission;
        this.rankInMission = rankInMission;
        this.isCheckpoint  = isCheckpoint;
        this.name          = name;
        this.latitude      = lat;
        this.longitude     = lon;
        this.declination   = decl;
        this.radius        = radius;
        this.stay_time     = stay_time;
        // If no argument is provided, then this.harvested = false, otherwise, the provided argument
        this.harvested     = (harvested === undefined ? 0 : harvested);
    }

    Point.prototype.print = function() 
    {
        if (this.isCheckpoint == "1")
        {
            var type = "checkpoint";
        }
        else
        {
            var type = "waypoint";
        }

        var result = "The " + type + " " + this.rankInMission + " - " + this.name + " is located at the coordinates (" 
                    + this.latitude + ", " + this.longitude + ")\n" + 
                   "Radius: " + this.radius + " (m) | Declination: " + this.declination + 
                   " | Stay time: " + this.stay_time +" (sec)"; 
        return result;
    };


    //*****************************************************************************
    //                                                                            *
    //                          Initialisation                                    *
    //                                                                            *
    //*****************************************************************************  


    // Initialisation of the map
    var mymap = L.map('map'); 
    initMap(60.1, 19.935, mymap);
    var popup = L.popup();

    var listOfPoints = document.getElementById('listOfPoints'); // To manage the list of item
    var isOpen = false;                 // To handle the popup of creation of point
    var numberOfPoints = listOfPoints.childElementCount;
    var arrayOfPoints = {};             // To store the different waypoints & checkpoints
    var arrayOfMarker = {};             // To store the different marker of the map.
    var arrayOfCircle = {};
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



    // Initialize the map which is centered on the given lat, lng
    function initMap(lat, lon, mymap)
    {
        var accessToken = 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
    
        mymap.setView([lat, lon], 13);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+accessToken, 
        { 
            maxZoom: 18, 
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' + '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' + 'Imagery © <a href="http://mapbox.com">Mapbox</a>',
            id: 'mapbox.streets' 
        }).addTo(mymap);
        
        // Event click on map
        mymap.on('click', onMapClick);
    }

    //*****************************************************************************
    //                                                                            *
    //                      Functions Used By The Events                          *
    //                                                                            *
    //*****************************************************************************

    // This function handles the click on the map.
    // It will display a popup asking the user which point he would liek to add
    // Then this function displays the modal form to add complete the creation
    // of the waypoint / checkpoint. The cancel button is also managed here.
    function onMapClick(e) 
    { 
        coordGPS = splitGPS(e.latlng.toString());

        // One click opens the popup, another closes it.
        if (isOpen)
        {
            mymap.closePopup();
            isOpen = false;
        }
        else
        {
            popup.setLatLng(e.latlng).setContent("You clicked the map at " + coordGPS + askNewPoint()).openOn(mymap);
            isOpen = true;
        }
    }

    // Event when click on a button of the popup
    $('#map').on('click', '.addPoint', function(e)
        {   
            // Edit the modal form depending of which kind of point we want to create.
            if ($(this).attr('id') == 'newCheckpoint')
            {
                var text = 'checkpoint';
                var classText = 'isCheckpoint';
                var defaultRadius = 15;
                var defaultStay_time = 5;
            }
            else
            {
                var text = 'waypoint';
                var classText = 'isWaypoint';
                var defaultRadius = 50;
                var defaultStay_time = 1;
            }
            editModalToPoint(classText, text);

            // Change the default values depending on which type of point we want to add
            // We loose the 'feature' of having the previous entered value.
            $('#newPointRadius').val(defaultRadius);
            $('#newPointStay_time').val(defaultStay_time);

            // Modify the value of the longitude and latitude form to allow 
            // the user to correct the value manually if needed.
            $('#newPointLatitude').val(coordGPS.split(', ')[0]);
            $('#newPointLongitude').val(coordGPS.split(', ')[1]);

            // Display the modal form
            $('#createPointModal').modal('show');
        });

    // Use the span tags inside the modal form to adapt the text for a waypoint or a checkpoint
    function editModalToPoint(classText, text)
    {
        var waypointOrCheckpoint = $('.waypointOrCheckpoint');

        for (var i = waypointOrCheckpoint.length - 1; i >= 0; i--) 
        {
            // Deleting previous text
            waypointOrCheckpoint[i].removeChild(waypointOrCheckpoint[i].firstChild);

            // Creating a child (easier to remove than just using TexteNode)
            var txt_env = document.createElement('span');
            txt_env.classList.add(classText);
            txt_env.appendChild(document.createTextNode(text));
            waypointOrCheckpoint[i].appendChild(txt_env);
        }
    }

    // Cancel
    $('#cancelNewPoint').on('click', function()
        {
            // Reset to default values
            $(':input','#createPointModal').val("");

            // Hide
            $('#createPointModal').modal('hide');
            mymap.closePopup();
            isOpen = false;
        });

    // It would be better to use an event in the script, but this doesn't work, I don't know why.
    // $('#confirmNewPoint').on('click', createNewPoint());

    // This function create a new point. 
    // It is called when the user confirm the creation of a new point
    function createNewPoint()
    {
        var listOfPoints   = document.getElementById('listOfPoints');
        // var newPoint       = document.createElement('li');
        var color; // Of Icon on the map
        
        // Var to create a new point object
        var name           = escapeHtml($('#newPointName').val()),
            id_mission     = $('#missionSelection').children(':selected').attr('id'),
            radius         = escapeHtml($('#newPointRadius').val()),
            stay_time      = parseInt($('#newPointStay_time').val())*60, // So we get seconds
            lat            = escapeHtml($('#newPointLatitude').val()),
            lon            = escapeHtml($('#newPointLongitude').val()),
            declination    = escapeHtml($('#newPointDeclination').val()),
            rankInMission  = ++numberOfPoints, 
            isCheckpoint; 

        // console.log("GPS : ", coordGPS, " lat ", coordGPS.split(',')[0], " lon ", coordGPS.split(',')[1]);
        // Update the timestamp for the new point
        timeStamp = currentTimeStamp();

        // Checkpoint or Waypoint
        if ($('.waypointOrCheckpoint')[0].firstChild.classList.contains('isCheckpoint'))
        {
            isCheckpoint = 1;
        }
        else
        {
            isCheckpoint = 0;
        }

        // Last thing to compute
        // declination = computeDeclination(lat, lon);

        // We can now create an instance of the class Point
        var newPoint_JS = new Point(timeStamp, id_mission, isCheckpoint, rankInMission, name, lat, lon, declination, radius, stay_time);
        
        // Add a marker on the map
        createMarker(newPoint_JS, lat, lon);

        // We now close the popup
        $('#createPointModal').modal('hide');
        mymap.closePopup();
        isOpen = false;
    }

    // This function update the display of the list by manipulating the DOM
    // elements of the page when a marker is "dragged'n'dropped"
    function updateListItems(marker, editOrMove)
    {
        var index = marker.options.rankInMission,
            id    = marker.options.id;

        if (editOrMove == "move")
        {
            // Update the position according to the marker
            arrayOfPoints[index].latitude  = roundNumber(marker.getLatLng().lat, 5);
            arrayOfPoints[index].longitude = roundNumber(marker.getLatLng().lng, 5);
            
            // Update the position of the associated circle
            arrayOfCircle[index].setLatLng(marker.getLatLng(), {});
            // arrayOfCircle[index].options.lat  = roundNumber(marker.getLatLng().lat, 5);
            // arrayOfCircle[index].options.longitude = roundNumber(marker.getLatLng().lng, 5);
        }

        // We create another li element
        var listOfPoints = document.getElementById('listOfPoints');
        var newPoint     = document.createElement('li');

        newPoint.setAttribute("id", id);
        newPoint.setAttribute("class", "point list-group-item");
        newPoint.appendChild(document.createTextNode(arrayOfPoints[index].print()));
        
        addDeleteSymbol(newPoint);

        // Which is inserted at the the right place
        var listItem = document.getElementById(id);
        
        listOfPoints.insertBefore(newPoint, listItem);
        
        // And we delete the old child
        listOfPoints.removeChild(listItem);
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
    function saveMissionIntoDB()
    {
        // console.log('clicked !');
        // console.log(JSON.stringify(arrayOfPoints));
        $.ajax({
            type: 'POST',
            url: 'php/insertPointIntoDB.php',
            contentType: 'application/json; charset=utf-8', // What is sent
            data: JSON.stringify(arrayOfPoints),
            // dataType: 'json',
            async: false,
            timeout: 3000,
            success: function(data) {
                alert(data);},
            error: function() {
                alert('Fail !'); }
        });        
    }

    //*****************************************************************************
    //                                                                            *
    //                      Receive & Display Points                              *
    //                                                                            *
    //*****************************************************************************

    // We get the point list in a JSON format, parse
    function getMissionPointFromDB(id_mission)
    {
        // Clean the map
        mymap.remove();
        mymap = L.map('map');
        
        // Clean the list
        while (listOfPoints.firstChild) 
        {
            listOfPoints.removeChild(listOfPoints.firstChild);
        }

        // Clean the map
        //initMap(60.1, 19.935, mymap);

        // Get the marker list in JSON object
        $.ajax({
            type: 'POST',
            url: 'php/getPointList.php',
            data: {id_mission:id_mission},
            dataType: 'json', // What is expected
            async: true,
            timeout: 3000,
            success: function(data) {
                displayPointFromDB(data); },
            error: function() {
                alert('Fail !'); }
        }); 
    }
    
    // This function gets a json object, convert it into Point() object,
    // fill the array, and display them
    function displayPointFromDB(data)
    {
        // We clean the variables
        arrayOfPoints = {};
        arrayOfMarker = {};
        arrayOfCircle = {};

        var len = data.length;

        // Initialization if there is not point in the mission
        // Centered on Mariehamn
        if (len == 0)
        {
            listOfPoints.parentNode.style.display = "none";
            initMap(60.1, 19.935, mymap);
        }

        // Adding all points
        for (var i = 0; i < len; i++)
        {
            var point = $.extend(new Point(), data[i]);

            // Fullfilling our variables
            lat = point.latitude;
            lon = point.longitude;

            // Centering the map on the first point
            if (i == 0)
            {
                initMap(lat, lon, mymap);
            }

            // Creating the markers
            createMarker(point, lat, lon);
        }
        numberOfPoints = listOfPoints.childElementCount;

        // Join the markers
        mymap.removeLayer(polyline);
        // mymap.removeLayer(polylineSup);
        // mymap.removeLayer(polylineInf);
        drawLineBetweenMarkers();
    }

    // Handle the creation of a marker
    function createMarker(point, lat, lon)
    {
        var newPoint = document.createElement('li');

        // Add class attribute to the <li> element
        newPoint.setAttribute("class", "point");
        newPoint.classList.add('list-group-item');
        newPoint.setAttribute("id", point.id);

        if (point.isCheckpoint == "1") // TODO : parse to float / int / bool when mapping to JS object Point()
        {
            newPoint.classList.add('isCheckpoint');
            color = greenIcon;
        }
        else
        {
            newPoint.classList.add('isWaypoint');
            color = blueIcon;
        }

        // We add it to the array
        arrayOfPoints[point.rankInMission] = point;

        // Add an item to the HTML list
        newPoint.appendChild(document.createTextNode(point.print()));
        addDeleteSymbol(newPoint);
        listOfPoints.appendChild(newPoint);
        
        // Display the list (useful only once)
        if (listOfPoints.parentNode.style.display == 'none')
        {
            listOfPoints.parentNode.style.display = "inline-block";
        }
        
        // New draggable marker
        marker = new L.marker( [lat, lon],
                                {draggable:'true',
                                icon: color, 
                                rankInMission: point.rankInMission,
                                id: point.id
                            });
        marker.bindPopup(askEditPoint(point));

        // Add in marker array
        arrayOfMarker[point.rankInMission] = marker;
       
        // New 'following' circle
        var circle = L.circle([lat, lon],
                                parseFloat(point.radius), 
                                {color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.3,
                                rankInMission: point.rankInMission,
                                id: point.id
                            }).addTo(mymap)
        
        // Store the circle in an array. It works the same way as it does for the marker
        arrayOfCircle[point.rankInMission] = circle;

        // Update the polyline
        if (polyline != undefined)
        {
            mymap.removeLayer(polyline);
            //mymap.removeLayer(polylineSup);
            //mymap.removeLayer(polylineInf);
        }  
        LatLngs.push(marker.getLatLng());
        polyline = L.polyline(LatLngs, {color: 'red'}).addTo(mymap);

        // Handle the Drag & Drop
        marker.on('dragend', function(event)
            {
                var marker = event.target;
                var position = marker.getLatLng();
                // console.log(position);

                // Update the position on the map
                marker.setLatLng(position,{draggable:'true',
                                            rankInMission: marker.options.rankInMission,
                                            id: marker.options.id
                                        }).update();

                // Update the position in our lists.
                updateListItems(marker, "move");

                // Update the polyline
                mymap.removeLayer(polyline);
                removePolylineInfSup();
                drawLineBetweenMarkers();
            });

        // Finally we display the marker on the map
        mymap.addLayer(marker);
    }

    // This functions uses the coordinates of all markers to draw a line between them
    function drawLineBetweenMarkers()
    {
        var size   = document.getElementById('listOfPoints').children.length;
        var coordSupInf;

        LatLngs    = Array();
        var LatLngsSup = Array();
        var LatLngsInf = Array();
        // mymap.removeLayer(polylineSup);
        // mymap.removeLayer(polylineInf);

        if(size > 1)
        {
            // Because we start at i = 2 in the loop
            LatLngs.push(arrayOfMarker[1].getLatLng());

            for (var i = 2; i <= size; i++) 
            {
                LatLngs.push(arrayOfMarker[i].getLatLng());
                
                // Compute points for upper and lower lines for the path
                coordSupInf = computeCoordinatesOfLine(arrayOfMarker[i-1], arrayOfMarker[i]);
                
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

    function computeCoordinatesOfLine(markerA, markerB)
    {
        // Origin of theta is the North
        var theta = computeTheta(markerA.getLatLng(), markerB.getLatLng());
        var result = {};
        var radiusA = parseInt(arrayOfPoints[markerA.options.rankInMission].radius),
            radiusB = parseInt(arrayOfPoints[markerB.options.rankInMission].radius);

        // radius = Math.min(radiusA, radiusB);
        radiusA = parseFloat((radiusA + radiusB)/2);
        radiusB = radiusA;

        console.log('theta : ', theta*180/Math.PI, 'radiusA', radiusA, 'radiusB', radiusB);
        
        // For the upper line
        result[0] = rotationVector(theta + Math.PI/2,
                                    markerA.getLatLng()['lat'],
                                    markerA.getLatLng()['lng'],
                                    radiusA*(1 + Math.abs( Math.sin(theta)) ) );
        result[1] = rotationVector(theta + Math.PI/2,
                                    markerB.getLatLng()['lat'],
                                    markerB.getLatLng()['lng'],
                                    radiusB*(1 + Math.abs( Math.sin(theta)) ) );
        // For the lower line
        result[2] = rotationVector(theta - Math.PI/2,
                                    markerA.getLatLng()['lat'],
                                    markerA.getLatLng()['lng'],
                                    radiusA*(1 + Math.abs( Math.sin(theta)) ) );
        result[3] = rotationVector(theta - Math.PI/2,
                                    markerB.getLatLng()['lat'],
                                    markerB.getLatLng()['lng'],
                                    radiusB*(1 + Math.abs( Math.sin(theta)) ) );
        
        return result;
    }

    function computeTheta(vectorA, vectorB)
    {
        return Math.atan2(vectorB['lat'] - vectorA['lat'], vectorB['lng'] - vectorA['lng']);
    }

    function rotationVector(theta, vector_lat, vector_lng, radius)
    {
        var result = {};

        // According to this answer on Stack Overflow :
        // https://stackoverflow.com/questions/2187657/calculate-second-point-knowing-the-starting-point-and-distance
        result['lat'] = vector_lat + radius*Math.sin(theta)/(110540);
        result['lng'] = vector_lng + radius*Math.cos(theta)/(111320*Math.cos(vector_lng*Math.PI/180));

        return result;
    }

    function removePolylineInfSup()
    {
        for (var i = arrayOfPolylineSup.length - 1; i >= 0; i--) 
        {
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
    $('#map').on('click', '.editPointButton', function() {
            // Adapt the modal to the point properties we clicked on
            if ($(this).hasClass('Checkpoint'))
            {
                var text        = 'checkpoint';
                var classText   = 'isCheckpoint';
                // $('#isCheckpointButton').addClass('active');
            }
            else
            {
                var text        = 'waypoint';
                var classText   = 'isWaypoint';
                // $('#isWaypointButton').addClass('active');
            }
            editModalToPoint(classText, text);

            // Fill the form with the properties of the marker we selected
            var id_element      = $(this).attr('id');
            editedMarkerIndex   = id_element.split('|')[0].split(':')[1]; // It's ugly right ? :p
            var id_marker       = id_element.split('|')[1].split(':')[1];
            var point           = arrayOfPoints[editedMarkerIndex];

            $('#editPointName').val(point.name);
            $('#editPointLatitude').val(point.latitude);
            $('#editPointLongitude').val(point.longitude);
            $('#editPointRadius').val(point.radius);
            $('#editPointStay_time').val(parseInt(point.stay_time)/60);
            $('#editPointDeclination').val(point.declination);
            
            // Show the modal
            $('#editPointModal').modal('show');
        });

    // This works because the modal are already in the HTML document
    $('#cancelEditPointButton').on('click', function() {
            $('#editPointModal').modal('hide');
        });

    $('#confirmEditPointButton').on('click', function() {
            // $('#isCheckpointButton').removeClass('active');
            // $('#isWaypointButton').removeClass('active');

            $('#editPointModal').modal('hide');

            // Get the entered value, update the object in the array and the list
            var point = arrayOfPoints[editedMarkerIndex];

            // Name
            if( point.name != escapeHtml($('#editPointName').val()) )
            {
                point.name = escapeHtml($('#editPointName').val());
            }
            // Latitude
            if( point.latitude != escapeHtml($('#editPointLatitude').val()) )
            {
                point.latitude = parseFloat(escapeHtml($('#editPointLatitude').val()));
            }
            // Longitude
            if ( point.longitude != escapeHtml($('#editPointLongitude').val()) )
            {
                point.longitude = parseFloat(escapeHtml($('#editPointLongitude').val()));
            }
            // Radius
            if( point.radius != escapeHtml($('#editPointRadius').val()) )
            {
                point.radius = parseInt(escapeHtml($('#editPointRadius').val()));
            }
            // Stay time
            if( point.stay_time != parseInt(escapeHtml($('#editPointStay_time').val()))*60 )
            {
                point.stay_time = parseInt(escapeHtml($('#editPointStay_time').val()));
            }
            // Declination
            if( point.declination != escapeHtml($('#editPointDeclination').val()) )
            {
                point.declination = parseFloat(escapeHtml($('#editPointDeclination').val()));
            }

            // Update the list item
            updateListItems(arrayOfMarker[editedMarkerIndex], "drag");

            // Update the position of the marker on the map
            var position = [point.latitude, point.longitude];
            var marker   = arrayOfMarker[editedMarkerIndex];
            marker.setLatLng(position,{draggable:'true',
                                        rankInMission: marker.options.rankInMission,
                                        id: marker.options.id
                                    }).update();
        });


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
    $('#listOfPoints').on('click', '.customDelete', function()
        {
            var id_marker        = $(this).parent().attr('id');

            deleteMarker(id_marker);
        });
        
    $('#map').on('click', '.deletePoint', function()
        {
            var id_element      = $(this).attr('id');
            var id_marker       = id_element.split('|')[1].split(':')[1];

            deleteMarker(id_marker);

        });

    function deleteMarker(id_marker)
    {
        var parentNodeList   = document.getElementById('listOfPoints').children;
        var newArrayOfPoints = {},
            newArrayOfMarker = {},
            newArrayOfCircle = {};
        // var rank = 1 + Array.from(parentNodeList).indexOf(document.getElementById(id_marker));

        var changeRank = 0;
        var j;

        // Update this different array without the deleted point
        for( var i = 1, len = parentNodeList.length; i <= len; i++)
        {
            j = i;
            if (changeRank)
            {
                arrayOfPoints[i].rankInMission = i - 1;
                arrayOfMarker[i].options.rankInMission = i - 1;
                j = i-1;
            }
            if (arrayOfPoints[i].id == id_marker)
            {
                changeRank++;
                // Remove marker from the map
                mymap.removeLayer(arrayOfMarker[i]);
                mymap.removeLayer(arrayOfCircle[i]);
                // Delete node from the DOM
                document.getElementById('listOfPoints').removeChild(document.getElementById(id_marker));
            }
            else
            {
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
    function addDeleteSymbol(newNode)
    {
        var newDelete    = document.createElement('span');
        
        newDelete.setAttribute("class", "label label-default pull-right customDelete");
        newDelete.appendChild(document.createTextNode('Delete'));
        newNode.appendChild(newDelete);        
    }

    //*****************************************************************************
    //                                                                            *
    //                             DECLINATION                                    *
    //                                                                            *
    //*****************************************************************************         

    // declination=0;
    function setdecl(v){
        console.log("declination found: "+v);
        declination=v;
    }

    function lookupMag(lat, lon) {
        var url =
            "http://www.ngdc.noaa.gov/geomag-web/calculators/calculateIgrfgrid?lat1="+lat+"&lat2="+lat+"&lon1="+lon+"&lon2="+lon+
            "&latStepSize=0.1&lonStepSize=0.1&magneticComponent=d&resultFormat=xml";
        // $.get(url, function(xml, status){
        //      setdecl( $(xml).find('declination').text());
        // });
        var xmlHTTP = new XMLHttpRequest();
        xmlHTTP.onreadystatechange = function()
            {
                if (xmlHTTP.readyState == 4 && xmlHTTP.status == 200)
                {
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

    //*****************************************************************************
    //                                                                            *
    //                            TOOLS & UTILITIES                               *
    //                                                                            *
    //*****************************************************************************

    function deleteAllChildren(parentNode)
    {
        while(parentNode.firstChild)
        {
            parentNode.removeChild(parentNode.firstChild);
        }
    }

    function askNewPoint()
    {
        // Popup for creation of a point
        return  "<br/> Do you want to add a point here ? <br /> \n"+
                "<div id='buttonContainerMap'> \n" +
                    "<button name='newWaypoint'   class='btn btn-info addPoint'    id='newWaypoint' >Waypoint</button> \n" +
                    "<button name='newCheckpoint' class='btn btn-success addPoint' id='newCheckpoint' >Checkpoint</button> \n" +
                "</div>";
    }

    function askEditPoint(point)
    {
        if (point.isCheckpoint == "1")
        {
            var type = "Checkpoint";
        }
        else
        {
            var type = "Waypoint";
        }
 
        // Popup when click on a marker
        // I know I do something ugly with the id, but I din't find any better solution to get the marker on which the user clicks
        return  type + ": " + point.rankInMission + ' - ' + point.name + "<br /> \n" +
                "Position: " + point.latitude + ", " + point.longitude + "<br /> \n" +
                "Radius: " + point.radius + " (m) | Stay_time: " + point.stay_time +  " (sec) <br /> \n" +
                "<br /> \n" +
                "<div> \n"+
                    "<button name='deletePointButton' class='btn btn-danger deletePoint'  id='rankInMission:"+point.rankInMission+"|id:"+point.id+"' >Delete Point</button> \n" +
                    "<button name='editPointButton'   class='btn btn-info   editPointButton "+type+"' id='rankInMission:"+point.rankInMission+"|id:"+point.id+"' >Edit Point</button> \n" +
                "</div>";
    }

    function splitGPS(string)
    {
        // This function cleans the result send by leaflet when the user clicks on the map.
        // TODO : check if marker.getLatLng().lat; & marker.getLatLng().lon; do the same
        var res;

        res = string.split("(")[1].split(")")[0];
        return res;
    }

    function computeDeclination(lat, lon)
    {
        // TODO : write the function
        return 0;
    }

    function currentTimeStamp()
    {
        return Math.floor(Date.now() / 1000);
    }

    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function roundNumber(number, digits) 
    {
        var multiple = Math.pow(10, digits);
        var rndedNum = Math.round(number * multiple) / multiple;
        return rndedNum;
    }

    // Functions used to get the coordinates of the point on the circle in order to 
    // draw to the limits of the path of the sailing robot.




    // return  {
    //             createNewPoint: createNewPoint()
    //         };

// }

// var main_leaflet_var = map_leaflet();
// map_leaflet();

