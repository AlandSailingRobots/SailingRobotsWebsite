<?php
// Body of the page when connected
require_once('include/configurationData.php');
require_once('include/printTables.php');
require_once('include/printWaypointList.php');
// require_once('include/insertWaypoint.php');
// require_once('include/updateDB.php');
// require_once('include/updateWaypoints.php');
?>


<div class="container">
    <div class="starter-template">
        <div class="jumbotron">
            <h1>Configuration page</h1>
            <p>On this page you can see and update the configurations for Aland Sailing Robots.</p>
        </div>
        <br>
        <br>
        <br>
        <div class="row">
                <?php // We get every table from the database
                    $bufferConfigArray            = getConfigData("buffer_config");
                    $courseCalculationConfigArray = getConfigData("course_calculation_config");
                    $maestroControllerConfigArray = getConfigData("maestro_controller_config");
                    $rudderCommandConfigArray     = getConfigData("rudder_command_config");
                    $rudderServoConfigArray       = getConfigData("rudder_servo_config");
                    $sailCommandConfigArray       = getConfigData("sail_command_config");
                    $sailServoConfigArray         = getConfigData("sail_servo_config");
                    $sailingRobotConfigArray      = getConfigData("sailing_robot_config");
                    $waypointRoutingConfigArray   = getConfigData("waypoint_routing_config");
                    $windVaneConfigArray          = getConfigData("wind_vane_config");
                    $windsensorConfigArray        = getConfigData("windsensor_config");
                    $xbeeConfigArray              = getConfigData("xbee_config");
                    $httpSyncConfigArray          = getConfigData("httpsync_config");
                ?>
            <div class="row">
                <?php
                printTables($bufferConfigArray            , "buffer_config");
                printTables($courseCalculationConfigArray , "course_calculation_config");
                printTables($maestroControllerConfigArray , "maestro_controller_config");
                ?>
                <?php
                printTables($rudderCommandConfigArray , "rudder_command_config");
                printTables($rudderServoConfigArray   , "rudder_servo_config");
                printTables($sailCommandConfigArray   , "sail_command_config");
                ?>
                <?php
                printTables($sailServoConfigArray       , "sail_servo_config");
                printTables($sailingRobotConfigArray    , "sailing_robot_config");
                printTables($waypointRoutingConfigArray , "waypoint_routing_config");
                ?>
                <?php
                printTables($windVaneConfigArray   , "wind_vane_config");
                printTables($windsensorConfigArray , "windsensor_config");
                printTables($httpSyncConfigArray   , "httpsync_config");
                printTables($xbeeConfigArray       , "xbee_config");
                ?>
            </div>
            <div class="row">
                <input type='button' value='Submit Configs' class='btn btn-success col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4' onclick='submitAllForms()'/>
            </div>
            <br>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
    </div>
</div>
