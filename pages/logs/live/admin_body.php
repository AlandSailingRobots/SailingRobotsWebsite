<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 550PX;
    }

    #legend {
        font-family: Arial, sans-serif;
        font-weight: bold;
        background: #fff;
        padding: 3px;
        margin: 3px;
    }
</style>


<!-- Marketing messaging and featurettes
    ================================================== -->
<!-- Wrap the rest of the page in another container to center all the content. -->
<div class="container marketing">
    <!-- START THE FEATURETTES -->
    <div class="container marketing">
        <br><br><br><br><br>
        <div class="row featurette">
            <div class="col-md-4">
                <div id='boatData'>
                    <div id='boatDataSystem'>
                        <h2>SystemDataLogs</h2>
                        <div id='dataNamesSystem'></div>
                        <div id='dataValuesSystem'></div>
                    </div>
                    <div id='boatDataCompass' >
                        <h2>CompassData</h2>
                        <div id='dataNamesCompass'></div>
                        <div id='dataValuesCompass'></div>
                    </div>
                    <div id='boatDataCourse' >
                        <h2>CourseData</h2>
                        <div id='dataNamesCourse'></div>
                        <div id='dataValuesCourse'></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div id='boatData'>
                    <div id='boatDataGps'>
                        <h2>Gps Data</h2>
                        <div id='dataNameGps'></div>
                        <div id='dataValueGps'></div>
                    </div>
                    <div id='boatDataWindSensor' >
                        <h2>WindSensorData</h2>
                        <div id='dataNamesWindSensor'></div>
                        <div id='dataValuesWindSensor'></div>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <!-- /END THE FEATURETTES -->
        <!-- FOOTER -->
    </div>
    <div class = "mapClass">
        <div id="map"></div>
        <div id="legend"></div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbBYAJitbjLYDoNJKQN4APRL5-_wDcUxQ&libraries=geometry,drawing"></script>
        <script src="js/liveMap.js"></script>
    </div>

    <!-- /.container -->
