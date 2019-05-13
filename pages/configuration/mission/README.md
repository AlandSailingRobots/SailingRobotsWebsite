# Purpose

Configure a mission which will be sent to ASPire DB.

# Organisation
- The file script.js handles the clicks on the button at the top of the body of 
the page
- The file map_leaflet.js handle everything related to the markers on the map 
and the points of the mission.

# How it works
Each created point is a JS object stored in an array. The Leaflet markers are 
also stored in an array. The way the code has been written, 1 point and its 
marker have the same index in their respective arrays.

On the other side, you have the child of the DOM element <ul>. The 'rank' of the
child is also the same as the index.

To get a specific point (object, marker or text in the DOM) you can use their 
index or their id. The HTML id element could not be a number like 'rankInMission'
because they were already used for the mission selector.

# NB
Use a JS minifier to reduce the size of the file on the server. Check the HTML 
files every time there is an update on the server.
