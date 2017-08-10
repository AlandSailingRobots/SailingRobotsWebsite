<?php
/*
 * This function get the content of the different table as long as the name of
 * the column in order to display it.
 */
function getConfigData($table, $boatName)
{
    if ($boatName == "janet")
    {
        $database_name = $GLOBALS['database_name_testdata'];
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
    }
    elseif ($boatName == "aspire") 
    {
        $database_name = $GLOBALS['database_ASPire'];
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
                               15 => 'config_can_arduino'
                                );
    }

    $user          = $GLOBALS['username'];
    $password      = $GLOBALS['password'];
    $hostname      = $GLOBALS['hostname'];

    try
    {
        $db = new PDO("mysql:host=$hostname;
                        dbname=$database_name;
                        charset=utf8;port=3306", 
                        $user, 
                        $password, 
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e)
    {
        die('Connection failed : '.$e->getMessage());
    }

    // I tried to protect the sql query -- Don't know if it's necessary

    if (in_array($table, $allowed_query, true))
    {
        $index = array_search($table, $allowed_query);
        $protected_table = $allowed_query[$index];
    
        // SQL Query
        $req = $db->prepare('SELECT * FROM ' . $protected_table);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        $req->closeCursor();
        
        return ($result);
    }


}
?>
