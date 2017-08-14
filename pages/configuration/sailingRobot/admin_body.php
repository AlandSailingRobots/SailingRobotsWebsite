<?php
// Body of the page when connected
require_once('include/printTables.php');
require_once('include/getConfigData.php');
?>


<div class="container">
    <div class="row">
        <div class="jumbotron">
            <h1>Configuration page</h1>
            <p>On this page you can see and update the configurations for Aland Sailing Robots.</p>
        </div>
        <br>
        <br>
        <br>
        <form action='updateConfig_post.php' method='post'>
            <div class="row">
                <?php
                if (isset($_GET['boat']) && $_GET['boat'] == "janet")
                {
                    echo '<input type="text" class="hidden" name="boat|janet" value="janet" size="1">';
                    // We get every table from the database
                    $bufferConfigArray            = getConfigData("buffer_config"             , "janet");
                    $courseCalculationConfigArray = getConfigData("course_calculation_config" , "janet");
                    $maestroControllerConfigArray = getConfigData("maestro_controller_config" , "janet");
                    $rudderCommandConfigArray     = getConfigData("rudder_command_config"     , "janet");
                    $rudderServoConfigArray       = getConfigData("rudder_servo_config"       , "janet");
                    $sailCommandConfigArray       = getConfigData("sail_command_config"       , "janet");
                    $sailServoConfigArray         = getConfigData("sail_servo_config"         , "janet");
                    $sailingRobotConfigArray      = getConfigData("sailing_robot_config"      , "janet");
                    $waypointRoutingConfigArray   = getConfigData("waypoint_routing_config"   , "janet");
                    $windVaneConfigArray          = getConfigData("wind_vane_config"          , "janet");
                    $windsensorConfigArray        = getConfigData("windsensor_config"         , "janet");
                    $xbeeConfigArray              = getConfigData("xbee_config"               , "janet");
                    $httpSyncConfigArray          = getConfigData("httpsync_config"           , "janet");

                    // We display the different tables
                    printTables($bufferConfigArray            , "buffer_config");
                    printTables($courseCalculationConfigArray , "course_calculation_config");
                    printTables($maestroControllerConfigArray , "maestro_controller_config");

                    printTables($rudderCommandConfigArray , "rudder_command_config");
                    printTables($rudderServoConfigArray   , "rudder_servo_config");
                    printTables($sailCommandConfigArray   , "sail_command_config");
                    
                    printTables($sailServoConfigArray       , "sail_servo_config");
                    printTables($sailingRobotConfigArray    , "sailing_robot_config");
                    printTables($waypointRoutingConfigArray , "waypoint_routing_config");
                    
                    printTables($windVaneConfigArray   , "wind_vane_config");
                    printTables($windsensorConfigArray , "windsensor_config");
                    printTables($httpSyncConfigArray   , "httpsync_config");
                    printTables($xbeeConfigArray       , "xbee_config");
                }
                else if (isset($_GET['boat']) && $_GET['boat'] == "aspire")
                {
                    echo '<input type="text" class="hidden" name="boat|aspire" value="aspire" size="1">';
                    
                    $configAISArray              = getConfigData("config_ais"                 , "aspire");
                    $configAISProcessingArray    = getConfigData("config_ais_processing"      , "aspire");
                    $configCanArduinoArray       = getConfigData("config_can_arduino"         , "aspire");
                    $configBufferArray           = getConfigData("config_buffer"              , "aspire");
                    $configCourseRegulatorArray  = getConfigData("config_course_regulator"    , "aspire");
                    $configDBLoggerArray         = getConfigData("config_dblogger"            , "aspire");
                    $configGPSArray              = getConfigData("config_gps"                 , "aspire");
                    $configHTTPSyncArray         = getConfigData("config_httpsync"            , "aspire");
                    $configI2CArray              = getConfigData("config_i2c"                 , "aspire");
                    $configMarineSensorsArray    = getConfigData("config_marine_sensors"      , "aspire");
                    $configSimulatorArray        = getConfigData("config_simulator"           , "aspire");
                    
                    $configSolarTrackerArray     = getConfigData("config_solar_tracker"       , "aspire");
                    $configVesselStateArray      = getConfigData("config_vessel_state"        , "aspire");
                    $configVoterSystemArray      = getConfigData("config_voter_system"        , "aspire");
                    $configWindSensorArray       = getConfigData("config_wind_sensor"         , "aspire");
                    $configXBeeArray             = getConfigData("config_xbee"                , "aspire");

                    $configLineFollowArray       = getConfigData("config_line_follow"         , "aspire");
                    $configSailControlArray      = getConfigData("config_sail_control"        , "aspire");

                    // We display the different tables
                    printTables($configAISArray             , "config_ais");
                    printTables($configAISProcessingArray   , "config_ais_processing");
                    printTables($configCanArduinoArray      , "config_can_arduino");
                    printTables($configBufferArray          , "config_buffer");
                    printTables($configCourseRegulatorArray , "config_course_regulator");
                    printTables($configDBLoggerArray        , "config_dblogger");
                    printTables($configGPSArray             , "config_gps");
                    printTables($configI2CArray             , "config_i2c");
                    printTables($configMarineSensorsArray   , "config_marine_sensors");
                    printTables($configSimulatorArray       , "config_simulator");

                    printTables($configSolarTrackerArray    , "config_solar_tracker");
                    printTables($configVesselStateArray     , "config_vessel_state");
                    printTables($configWindSensorArray      , "config_wind_sensor");
                    printTables($configVoterSystemArray     , "config_voter_system");
                    printTables($configHTTPSyncArray        , "config_httpsync");
                    printTables($configXBeeArray            , "config_xbee");

                    printTables($configLineFollowArray      , "config_line_follow");
                    printTables($configSailControlArray     , "config_sail_control");

                }
                ?>
            </div>
            <div class="row">
                <input type="submit" 
                        id="submitButton" 
                        value='Submit Configs' 
                        class='btn btn-success submit col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4' 
                />
            </div>
            <br>
        </form>
    </div>
</div>
