// function map_leaflet()
// {
    //*****************************************************************************
    //                                                                            *
    //                  Class to handle Waypoint/Checkpoint                       *
    //                                                                            *
    //*****************************************************************************  

    function Point(id_mission, isCheckpoint, rankInMission, name, lat, lon, decl, radius, stay_time)
    {
        this.id_mission    = id_mission;
        this.isCheckpoint  = isCheckpoint;
        this.rankInMission = rankInMission;
        this.name          = name;
        this.latitude      = lat;
        this.longitude     = lon;
        this.declination   = decl;
        this.radius        = radius;
        this.stay_time     = stay_time;
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

        result = "The " + type + " " + this.name + " from mission n°" + this.id_mission + 
                   "is located at the coordinates (" + this.lat + ", " + this.lon + ")" + 
                   "Radius: " + this.radius + " | Declination: " + this.declination + 
                   " | Sty time: " + this.stay_time; 
        return result;
    };

    // declination=0;

        // function setdecl(v){
        //     console.log("declination found: "+v);
        //     declination=v;
        // }

        // function lookupMag(lat, lon) {
        //     var url =
        //         "http://www.ngdc.noaa.gov/geomag-web/calculators/calculateIgrfgrid?lat1="+lat+"&lat2="+lat+"&lon1="+lon+"&lon2="+lon+
        //         "&latStepSize=0.1&lonStepSize=0.1&magneticComponent=d&resultFormat=xml";
        //     // $.get(url, function(xml, status){
        //     //      setdecl( $(xml).find('declination').text());
        //     // });
        //     var xmlHTTP = new XMLHttpRequest();
        //     xmlHTTP.onreadystatechange = function()
        //         {
        //             if (xmlHTTP.readyState == 4 && xmlHTTP.status == 200)
        //             {
        //                 setdecl($(xml).find('declination').text());
        //             }
        //         }
        //     xmlHTTP.open("GET", url, true);
        //     xmlHTTP.send(null);
        // }

    // lookupMag(55.58552,12.1313);

    //*****************************************************************************
    //                                                                            *
    //                          Initialisation                                    *
    //                                                                            *
    //*****************************************************************************  

    // Initialisation of the map
    var mymap = L.map('map').setView([60.1, 19.935], 13); 

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', 
            { 
                maxZoom: 18, 
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' + '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' + 'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                id: 'mapbox.streets' 
            }).addTo(mymap);
    var popup = L.popup();


    var listOfPoints = document.getElementById('listOfPoints'); // To manage the list of item
    var isOpen = false; // To handle the popup of creation of point
    var numberOfPoints = listOfPoints.childElementCount;
    var arrayOfPoints = new Array(); // To store the different waypoints & checkpoints
    var arrayOfMarker = new Array(); // To store the different marker of the map.
    var coordGPS; // Coord of where we clicked on the map

    // Hide the list if there is no point in the mission
    if (listOfPoints.childElementCount == 0)
    {
        listOfPoints.parentNode.style.display = "none";
    }
    // Hide the map while no mission is selected
    document.getElementById('myConfig').style.display = 'none'; 

    // Event click on map
    mymap.on('click', onMapClick); 


    //*****************************************************************************
    //                                                                            *
    //                      Functions Used By The Events                          *
    //                                                                            *
    //*****************************************************************************    

    // This function handles the click on the map.
    // It will displau a popup asking the user which point he would liek to add
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
                }
                else
                {
                    var text = 'waypoint';
                    var classText = 'isWaypoint';
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

                // Display the modal form
                $('#createPointModal').modal('show');
                // Cancel
                $('#cancelNewPoint').on('click', function()
                    {
                        // Reset to default values
                        $(':input','#createPointModal').val("");
                        $('#newPointRadius').val("15");
                        $('#newPointStay_time').val("1");
                        
                        // Hide
                        $('#createPointModal').modal('hide');
                        mymap.closePopup();
                        isOpen = false;
                    })

            });
    }

    // It would be better to use an event in the script, but this doesn't work, I don't know why.
    // $('#confirmNewPoint').on('click', createNewPoint());

    // This function create a new point. 
    // It is called when the user confirm the creation of a new point
    function createNewPoint()
    {
        var listOfPoints   = document.getElementById('listOfPoints');
        var newPoint       = document.createElement('li');
        var color; // Of Icon on the map
        // Var to create a new point object
        var name           = $('#newPointName').val(),
            id_mission     = $('#missionSelection').children(':selected').attr('id'),
            radius         = $('#newPointRadius').val(),
            stay_time      = parseInt($('#newPointStay_time').val())*60, // So we get seconds
            lat            = coordGPS.split(',')[0],
            lon            = coordGPS.split(',')[1],
            rankInMission  = ++numberOfPoints, 
            isCheckpoint, 
            declination;

        // Add class attribute to the <li> element
        newPoint.setAttribute("class", "point");
        newPoint.classList.add('list-group-item');
        
        // Checkpoint or Waypoint
        if ($('.waypointOrCheckpoint')[0].firstChild.classList.contains('isCheckpoint'))
        {
            newPoint.classList.add('isCheckpoint');
            isCheckpoint = 1;
            color = greenIcon;
        }
        else
        {
            newPoint.classList.add('isWaypoint');
            isCheckpoint = 0;
            color = blueIcon;
        }
        // Last thing to compute
        declination = computeDeclination(lat, lon);

        // We can now create an instance of the class Point
        var newPoint_JS = new Point(id_mission, isCheckpoint, rankInMission, name, lat, lon, declination, radius, stay_time);
        
        // We add it to the array
        // listOfPoints.push(newPoint_JS); 
        arrayOfPoints[rankInMission] = newPoint_JS;
        console.log("rank ", rankInMission);
        console.log("item stored ", arrayOfPoints[rankInMission]);

        // Add an item to the HTML list
        newPoint.appendChild(document.createTextNode(displayNewPointName(coordGPS, name)));
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
                                rankInMission: rankInMission
                            });
        marker.bindPopup(name);

        // Add in marker array
        arrayOfMarker[rankInMission] = marker;
        
        // Drag & Drop management
        marker.on('dragend', function(event)
            {
                var marker = event.target;
                var position = marker.getLatLng();
                // console.log(position);

                // Update the position on the map
                marker.setLatLng(position,{draggable:'true'}).update();

                // Update the position in our lists.
                updateListItems(marker);

            });
        mymap.addLayer(marker);
        
        // We now close the popup
        $('#createPointModal').modal('hide');
        mymap.closePopup();
        isOpen = false;
    }


    function updateListItems(marker)
    {
        var index = marker.options.rankInMission;
        var indexPoint = indexMarker; // Because the lists are related to each other :p
        // console.log("index marker ", indexMarker);
        // console.log("list item :", arrayOfPoints[indexPoint]);
        var newLat = marker.getLatLng().lat,
            newLon = marker.getLatLng().lon;

        arrayOfPoints[index].lat = newLat;
        arrayOfPoints[index].lon = newLon;

        // TODO : update display

        return ;
    }
    function displayNewPointName(point, name)
    {
        return "Hi ! here is "+ name +". You clicked at: " + point;
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
        return 0;
    }

    // return  {
    //             createNewPoint: createNewPoint()
    //         };

// }

// var main_leaflet_var = map_leaflet();
// map_leaflet();


//////////////////:
// L.marker([51.5, -0.09]).addTo(mymap).bindPopup("<b>Hello world!</b><br />I am a popup.").openPopup();
// L.circle([51.508, -0.11], 500, { color: 'red', fillColor: '#f03', fillOpacity: 0.5 }).addTo(mymap).bindPopup("I am a circle."); 
// L.polygon([ [51.509, -0.08], [51.503, -0.06], [51.51, -0.047] ]).addTo(mymap).bindPopup("I am a polygon."); 
