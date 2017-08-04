<?php
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
                                1 => 'config_httpsync',
                                2 => 'config_HTTPSyncNode',
                                3 => 'config_i2c',
                                4 => 'config_StateEstimationNode',
                                5 => 'config_wind_vane',
                                6 => 'scanning_measurements'
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
