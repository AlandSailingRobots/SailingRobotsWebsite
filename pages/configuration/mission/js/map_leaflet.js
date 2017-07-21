
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
console.log(listOfPoints.childElementCount);
if (listOfPoints.childElementCount == 0)
{
    listOfPoints.parentNode.style.display = "none";
}





function onMapClick(e) 
{ 
    var coordGPS = splitGPS(e.latlng.toString());
    if (!popup.isOpen())
    {
        console.log('popup closed');
        popup.setLatLng(e.latlng).setContent("You clicked the map at " + coordGPS + askNewPoint()).openOn(mymap); 
    }
    else if (popup.isOpen())
    {
        console.log('popup open');
        mymap.closePopup();
    }

    $('.addPoint').on('click', function(e)
        {
            // var coordGPS = splitGPS(e.latlng.toString());
            var listOfPoints = document.getElementById('listOfPoints');
            var newPoint = document.createElement('li');
            
            console.log('clicked !');
            
            newPoint.setAttribute("class", "point");
            newPoint.classList.add('list-group-item');
            
            if ($(this).attr('id') == 'newCheckpoint')
            {
                // console.warn('id : ' + $(this).attr('id'));
                // newPoint.setAttribute("class", "isCheckpoint");
                newPoint.classList.add('isCheckpoint');
            }
            newPoint.appendChild(document.createTextNode(displayNewPointName(coordGPS)));
            listOfPoints.appendChild(newPoint);
            listOfPoints.parentNode.style.display = "inline-block";
            
            marker = new L.marker( [coordGPS.split(',')[0], coordGPS.split(',')[1]], {draggable:'true'}).bindPopup($(this).attr('id'));
            marker.on('dragend', function(event){
                    var marker = event.target;
                    var position = marker.getLatLng();
                    console.log(position);
                    marker.setLatLng(position,{draggable:'true'}).bindPopup(position).update();
            });
            mymap.addLayer(marker);
        });
}

mymap.on('click', onMapClick); // Event click on map


function displayNewPointName(point)
{
    return "Hi ! You clicked here: " + point;
}

function askNewPoint()
{
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












//////////////////:
// L.marker([51.5, -0.09]).addTo(mymap).bindPopup("<b>Hello world!</b><br />I am a popup.").openPopup();
// L.circle([51.508, -0.11], 500, { color: 'red', fillColor: '#f03', fillOpacity: 0.5 }).addTo(mymap).bindPopup("I am a circle."); 
// L.polygon([ [51.509, -0.08], [51.503, -0.06], [51.51, -0.047] ]).addTo(mymap).bindPopup("I am a polygon."); 