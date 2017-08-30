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
                    $allowed_query = array (0 => 'buffer_config',
                        1 => 'course_calculation_config',
                        2 => 'maestro_controller_config',
                        3 => 'rudder_command_config',
                        4 => 'rudder_servo_config',
                        5 => 'sail_command_config',
                        6 => 'sail_servo_config',
                        7 => 'sailing_robot_config',
                        8 => 'waypoint_routing_config',
                        9 => 'wind_vane_config',
                        10 => 'windsensor_config',
                        11 => 'xbee_config',
                        12 => 'httpsync_config'
                    );
                    echo '<input type="text" class="hidden" name="boat|janet" value="janet" size="1">';
                    // We get every table from the database
                    
                    foreach ($allowed_query as $key => $value) 
                    {
                        $valueArray = getConfigData($value, "janet");
                        printTables($valueArray, $value);
                    }

                }
                else if (isset($_GET['boat']) && $_GET['boat'] == "aspire")
                {
                    $allowed_query = array (0 => 'config_buffer',
                        1 => 'config_course_regulator',
                        2 => 'config_dblogger',
                        3 => 'config_gps',
                        4 => 'config_i2c',
                        5 => 'config_httpsync',
                        6 => 'config_marine_sensors',
                        7 => 'config_simulator',
                        8 => 'config_solar_tracker',
                        9 => 'config_vessel_state',
                       10 => 'config_voter_system',
                       11 => 'config_wind_sensor',
                       12 => 'config_xbee',
                       13 => 'config_ais',
                       14 => 'config_ais_processing',
                       15 => 'config_can_arduino',
                       16 => 'config_line_follow',
                       17 => 'config_sail_control'
                    );

                    echo '<input type="text" class="hidden" name="boat|aspire" value="aspire" size="1">';
                    
                    foreach ($allowed_query as $key => $value) 
                    {
                        $valueArray = getConfigData($value, "aspire");
                        printTables($valueArray, $value);
                    }

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
