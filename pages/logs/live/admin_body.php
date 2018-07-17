<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbBYAJitbjLYDoNJKQN4APRL5-_wDcUxQ&libraries=geometry,drawing"></script>
<script src="js/liveMap.js"></script>
<link rel="stylesheet" href="css/live.css">


<div class="container-fluid">
    <div class="row">

        <div class="col-sm-6">
            <h2>Current Sensors</h2>
            <div id="currentSensorDataKey" class="liveData col-sm-3">currentSensorDataKey</div>
            <div id="currentSensorDataValue" class="liveData col-sm-3">currentSensorDataValue</div>
        </div>


        <div class="col-sm-6">
            <h2>GPS Data</h2>
            <div id="gpsDataKey" class="liveData col-sm-3"></div>
            <div id="gpsDataValue" class="liveData col-sm-3"></div>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-6">
            <h2>Compass Data</h2>
            <div id="compassDataKey" class="liveData col-sm-3">s</div>
            <div id="compassDataValue" class="liveData col-sm-3"></div>
        </div>


        <div class="col-sm-6">
            <h2>Wind Sensor Data</h2>
            <div id="windsensorDataKey" class="liveData col-sm-3"></div>
            <div id="windsensorDataValue" class="liveData col-sm-3"></div>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-6">
            <h2>Course Data</h2>
            <div id="courseDataKey" class="liveData col-sm-3">courseDataKey</div>
            <div id="courseDataValue" class="liveData col-sm-3">courseDataValue</div>
        </div>


        <div class="col-sm-6">
            <h2>Marine Sensor Data</h2>
            <div id="marineSensorDataKey" class="liveData col-sm-3">

            </div>
            <div id="marineSensorDataValue" class="liveData col-sm-3">

            </div>
        </div>
    </div>
</div>

<div class="container">


    <div class = "mapClass">
        <div id="map"></div>
    </div>
</div>