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
        if (this.isCheckpoint)
        {
            type = "checkpoint";
        }
        else
        {
            type = "waypoint";
        }

        result = "The " + type + " " + this.name + " is located at the coordinates (" 
                    + this.latitude + ", " + this.longitude + ")\n" + 
                   "Radius: " + this.radius + " (m) | Declination: " + this.declination + 
                   " | Stay time: " + this.stay_time +" (sec)"; 
        return result;
    };

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
    //                          Initialisation                                    *
    //                                                                            *
    //*****************************************************************************  


    // Initialisation of the map
    var mymap = L.map('map'); 
    initMap(60.1, 19.935, mymap);
    var popup = L.popup();

    var listOfPoints = document.getElementById('listOfPoints'); // To manage the list of item
    var isOpen = false; // To handle the popup of creation of point
    var numberOfPoints = listOfPoints.childElementCount;
    var arrayOfPoints = {}; // new Array(); // To store the different waypoints & checkpoints
    var arrayOfMarker = {}; // new Array(); // To store the different marker of the map.
    var coordGPS; // Coord of where we clicked on the map
    var timeStamp = currentTimeStamp(); // We need an unique ID to link the html tag / the marker object / the point object

    var name,
        lat, 
        lon,
        rankInMission;



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

        // Event when click on a button of the popup
        $('.addPoint').on('click', function(e)
            {   
                // Edit the modal form depending of which kind of point we want to create.
                var waypointOrCheckpoint = $('.waypointOrCheckpoint');
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
        var name           = $('#newPointName').val(),
            id_mission     = $('#missionSelection').children(':selected').attr('id'),
            radius         = $('#newPointRadius').val(),
            stay_time      = parseInt($('#newPointStay_time').val())*60, // So we get seconds
            lat            = $('#newPointLatitude').val(),
            lon            = $('#newPointLongitude').val(),
            rankInMission  = ++numberOfPoints, 
            isCheckpoint, 
            declination;

        // console.log("GPS : ", coordGPS, " lat ", coordGPS.split(',')[0], " lon ", coordGPS.split(',')[1]);
        // Update the timestamp for the new point
        timeStamp = currentTimeStamp();

        // Checkpoint or Waypoint
        if ($('.waypointOrCheckpoint')[0].firstChild.classList.contains('isCheckpoint'))
        {
            // newPoint.classList.add('isCheckpoint');
            isCheckpoint = 1;
            // color = greenIcon;
        }
        else
        {
            // newPoint.classList.add('isWaypoint');
            isCheckpoint = 0;
            // color = blueIcon;
        }
        // Last thing to compute
        declination = computeDeclination(lat, lon);

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
    // elements of the page
    function updateListItems(marker)
    {
        var index = marker.options.rankInMission,
            id    = marker.options.id;

        // Update the position according to the marker
        arrayOfPoints[index].latitude  = marker.getLatLng().lat;
        arrayOfPoints[index].longitude = marker.getLatLng().lng;

        // We create another li element
        var listOfPoints = document.getElementById('listOfPoints');
        var newPoint     = document.createElement('li');
        
        newPoint.setAttribute("id", id);
        newPoint.setAttribute("class", "point");
        newPoint.classList.add('list-group-item');
        newPoint.appendChild(document.createTextNode(arrayOfPoints[index].print()));
        
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
            async: true,
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
        arrayOfPoints = {},
        arrayOfMarker = {};

        var len = data.length;
        if (len == 0)
        {
            listOfPoints.parentNode.style.display = "none";
        }

        if (len == 0)
        {
            initMap(60.1, 19.935, mymap);
        }

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
    }

    function createMarker(point, lat, lon)
    {
        newPoint = document.createElement('li');

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
        marker.bindPopup(point.rankInMission + ' - ' + point.name);

        // Add in marker array
        arrayOfMarker[point.rankInMission] = marker;
       
        L.circle([lat, lon],
                    point.radius, 
                    {color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.3 
                }).addTo(mymap)
        
        // Drag & Drop management
        marker.on('dragend', function(event)
            {
                var marker = event.target;
                var position = marker.getLatLng();

                // Update the position on the map
                marker.setLatLng(position,{draggable:'true',
                                            rankInMission: marker.options.rankInMission,
                                            id: marker.options.id
                                        }).update();

                // Update the position in our lists.
                updateListItems(marker);

            });
        mymap.addLayer(marker);
    }

    //*****************************************************************************
    //                                                                            *
    //                          TOOLS & UTILITIES                                 *
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
    // return  {
    //             createNewPoint: createNewPoint()
    //         };

// }

// var main_leaflet_var = map_leaflet();
// map_leaflet();

