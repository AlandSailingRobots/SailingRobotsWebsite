(function()
{
    // Initialisation
    var mymap = L.map('map').setView([60.1, 19.935], 13); 

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', 
            { 
                maxZoom: 18, 
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' + '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' + 'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                id: 'mapbox.streets' 
            }).addTo(mymap);
    var popup = L.popup();


    var listOfPoints = document.getElementById('listOfPoints');
    // console.log(listOfPoints.childElementCount);
    if (listOfPoints.childElementCount == 0)
    {
        listOfPoints.parentNode.style.display = "none";
    }




    var isOpen = false;
    var numberOfPoints = listOfPoints.childElementCount;
    var arrayOfPoints = new Array();

    // This function handles the click on the map.
    function onMapClick(e) 
    { 
        var coordGPS = splitGPS(e.latlng.toString());
        // var coordGPS_2 = e.latlng.LatLng();

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
                var listOfPoints = document.getElementById('listOfPoints');
                var newPoint = document.createElement('li');
                var color;
                
                // Add class attribute to the <li> element
                newPoint.setAttribute("class", "point");
                newPoint.classList.add('list-group-item');
                
                // Checkpoint or Waypoint
                if ($(this).attr('id') == 'newCheckpoint')
                {
                    newPoint.classList.add('isCheckpoint');
                    color = greenIcon;
                }
                else
                {
                    newPoint.classList.add('isWaypoint');
                    color = blueIcon;
                }

                // Add an item to the list
                newPoint.appendChild(document.createTextNode(displayNewPointName(coordGPS)));
                listOfPoints.appendChild(newPoint);
                
                // Display the list (useful only once)
                listOfPoints.parentNode.style.display = "inline-block";
                
                // New draggable marker
                marker = new L.marker( [coordGPS.split(',')[0], coordGPS.split(',')[1]],
                                        {draggable:'true',
                                        icon: color
                                    });
                marker.bindPopup($(this).attr('id'));
                // marker.bindPopup('pouet opoeutzojfdkjs');
                marker.on('dragend', function(event)
                    {
                        var marker = event.target;
                        var position = marker.getLatLng();
                        console.log(position);

                        // Update the position
                        marker.setLatLng(position,{draggable:'true'}).bindPopup(position).update();
                        updateListItems();
                    });
                mymap.addLayer(marker);
                
                // We now close the popup
                mymap.closePopup();
                isOpen = false;
            });
    }

    mymap.on('click', onMapClick); // Event click on map

    function updateListItems()
    {
        return ;
    }
    function displayNewPointName(point)
    {
        return "Hi ! You clicked here: " + point;
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
        var res;

        res = string.split("(")[1].split(")")[0];
        return res;
    }

})();












//////////////////:
// L.marker([51.5, -0.09]).addTo(mymap).bindPopup("<b>Hello world!</b><br />I am a popup.").openPopup();
// L.circle([51.508, -0.11], 500, { color: 'red', fillColor: '#f03', fillOpacity: 0.5 }).addTo(mymap).bindPopup("I am a circle."); 
// L.polygon([ [51.509, -0.08], [51.503, -0.06], [51.51, -0.047] ]).addTo(mymap).bindPopup("I am a polygon."); 