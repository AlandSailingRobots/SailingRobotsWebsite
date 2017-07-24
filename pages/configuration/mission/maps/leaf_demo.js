// var map = L.map( 'map', {
//     center: [20.0, 5.0],
//     minZoom: 2,
//     zoom: 2
// });

// L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
//     subdomains: ['a','b','c']
// }).addTo( map );

var mymap = L.map('map').setView([20.0, 5.09], 2); 

L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', 
        { 
            maxZoom: 18, 
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' + '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' + 'Imagery © <a href="http://mapbox.com">Mapbox</a>',
            id: 'mapbox.streets' 
        }).addTo(mymap);


// mapboxgl.accessToken = 'pk.eyJ1IjoiYnVja2xhZ2FjaGV0dGUiLCJhIjoiY2o1YzM4ZHF5MDViczJxcWI2bnBrdnl0ZiJ9.9bh4qCPTEojOI6gfsyvzVw';
// var map = new mapboxgl.Map({
//             container: 'map',
//             style: 'mapbox://styles/mapbox/satellite-streets-v10'
//         });

for ( var i=0; i < markers.length; ++i ) 
{
   L.marker( [markers[i].lat, markers[i].lng] )
      .bindPopup( '<a href="' + markers[i].url + '" target="_blank">' + markers[i].name + '</a>' )
      .addTo( mymap );
}