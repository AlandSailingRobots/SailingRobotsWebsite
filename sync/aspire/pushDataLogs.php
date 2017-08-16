<?php
// ASPire

function pushAllLogs($boat, $data) 
{
    $db = $GLOBALS['db_connection'];
    $data = json_decode($data,true);
    // print_r($data);
    if (!empty($data))
    {
        echo '####################################################';
        foreach ($data as $table_name => $table) 
        {
            // Generate the array to be bind with the prepared SQL query
            // echo "\ntable name : $table_name\n";
            // print_r($table);
            // echo '---------------------------------------';
            foreach ($table as $id_log => $log) 
            {
                $param_stmt = "(";
                $param_array = array();
                foreach ($log as $column_name => $value) 
                {
                    $param_array[$column_name] = $value;
                    // if ($column_name != 'id')   // Leave the first field NULL to get the auto_increment from the DB
                    // {
                    //     $param_array[$column_name] = $value;
                    // }
                    $param_stmt = $param_stmt . ' ' . $column_name . '= :'.$column_name . ',';
                    // if ($column_name != 'id') 
                    // {
                    //     $param_stmt = $param_stmt . ' ' . $column_name . '= :'.$column_name . ',';
                    // }
                    // else
                    // {
                    //     $param_stmt = $param_stmt . 'NULL,';
                    // }
                }

                // Remove the extra comma
                $param_stmt = substr($param_stmt, 0, -1).')'; // Now whe hace something like (?, ?, ?, ?)
                // echo "\n@@@@@@@@@@@@@@@\n";
                // echo "table name : $table_name\n";
                // echo "param_stmt : ".$param_stmt . "\n";
                // echo "param_array: \n";
                // print_r($param_array);
                // echo "\n".'INSERT INTO '.$table_name . ' VALUES ' . $param_stmt  .';';
                // Prepare the SQL Query
                $query = $db->prepare('INSERT INTO '.$table_name . ' VALUES ' . $param_stmt  .';');
                $query->execute($param_array);
            }
            // $query->close()
        }     
        $result = array('result' => 1);
        return json_encode($result);
    }
}




/*class ASRService 
{
    private $db;

    function __construct() 
    {
        require_once('../../globalsettings.php');

        $servername = $GLOBALS['hostname'];
        $username   = $GLOBALS['username'];
        $password   = $GLOBALS['password'];
        $dbname     = $GLOBALS['database_ASPire'];

        $this->db = new mysqli($servername, $username, $password, $dbname);
    }
    
    function __destruct() 
    {
        $this->db->close();
    }
    function helloWorld()
    {
    	echo "Hello World !";
    }
    
    function pushAllLogs($boat, $data) 
    {
        $data                   = json_decode($data,true);
        echo ' pouet pouet ';
        $actuatorFeedbackStmt   = $this->db->stmt_init();
        $compassStmt            = $this->db->stmt_init();
        $courseCalculationStmt  = $this->db->stmt_init();
        $currentSensorsStmt     = $this->db->stmt_init();

        $gpsStmt                = $this->db->stmt_init();
        $marineSensorsStmt      = $this->db->stmt_init();
        $systemStmt             = $this->db->stmt_init();
        $vesselStateStmt        = $this->db->stmt_init();
        $windSensorStmt         = $this->db->stmt_init();
        $windStateStmt          = $this->db->stmt_init();
        
        $actuatorFeedbackStmt->prepare("INSERT INTO dataLogs_actuator_feedback VALUES (NULL,?,?,?,?,?)");
        $compassStmt->prepare("INSERT INTO dataLogs_compass VALUES(NULL,?,?,?,?);");
        $courseCalculationStmt->prepare("INSERT INTO dataLogs_course_calculation VALUES(NULL,?,?,?,?,?,?);");
        $currentSensorsStmt->prepare("INSERT INTO dataLogs_current_sensors VALUES (NULL,?,?,?,?)");
        
        $gpsStmt->prepare("INSERT INTO dataLogs_gps VALUES(NULL,?,?,?,?,?,?,?,?,?);");
        $marineSensorsStmt->prepare("INSERT INTO dataLogs_marine_sensors VALUES (NULL,?,?,?,?);");
        $systemStmt->prepare("INSERT INTO dataLogs_system VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?);");
        $vesselStateStmt->prepare("INSERT INTO dataLogs_vessel_state VALUES (NULL,?,?,?,?,?)");
        $windSensorStmt->prepare("INSERT INTO dataLogs_windsensor VALUES(NULL,?,?,?,?);");
        $windStateStmt->prepare("INSERT INTO dataLogs_wind_state VALUES (NULL,?,?,?,?,?)");
        
        
        foreach ($data['dataLogs_actuator_feedback'] as $row)
        {
            $actuatorFeedbackStmt->bind_param(NULL,
                    $row['rudder_position'],
                    $row['tail_wing_position'],
                    $row['rc_on'],
                    $row['wind_vane_angle'],
                    $row['t_timestamp']
                );
            $actuatorFeedbackStmt->execute();
        }
        $actuatorFeedbackStmt->close();

        foreach($data["dataLogs_compass"] as $row) 
        {
            $compassStmt->bind_param(NULL,
                    $row['heading'],
                    $row['pitch'],
                    $row['roll'],
                    $row['t_timestamp']
                );
            $compassStmt->execute();
        }
        $compassStmt->close();

        foreach($data["dataLogs_course_calculation"] as $row) 
        {
            $courseCalculationStmt->bind_param(NULL,
                    $row['distance_to_waypoint'],
                    $row['bearing_to_waypoint'],
                    $row['course_to_steer'],
                    $row['beating_state'],
                    $row['going_starboard'],
                    $row['t_timestamp']
                );
            $courseCalculationStmt->execute();
        }
        $courseCalculationStmt->close();

        foreach($data['dataLogs_current_sensors'])
        {
            $currentSensorsStmt->bind_param(NULL,
                    $row['actuator_unit'],
                    $row['navigation_unit'],
                    $row['wind_vane_angle'],
                    $row['wind_vane_clutch'],
                    $row['sailboat_drive'],
                    $row['t_timestamp']
                );
            $currentSensorsStmt->execute();
        }
        $currentSensorsStmt->close();

        foreach($data["dataLogs_gps"] as $row) 
        {
            $gpsStmt->bind_param(NULL,
                    $row['has_fix'],
                    $row['online'],
                    $row['time'],
                    $row['latitude'],
                    $row['longitude'],
                    $row['speed'],
                    $row['course'],
                    $row['satellites_used'],
                    $row['t_timestamp']
                );
            $gpsStmt->execute();
        }
        $gpsStmt->close();

        foreach($data['dataLogs_marine_sensors'])
        {
            $marineSensorsStmt->bind_param(NULL, 
                    $row['id'],
                    $row['temperature'],
                    $row['conductivity'],
                    $row['ph'],
                    $row['t_timestamp']
                );
            $marineSensorsStmt->execute();
        }
        $marineSensorsStmt->close();

        foreach($data["dataLogs_system"] as $row) 
        {
            $systemStmt->bind_param(NULL,
                    //$boat,
                    $row['actuator_feedback_id'],
                    $row['compass_id'],
                    $row['course_calculation_id'],
                    $row['current_sensors_id'],
                    $row['gps_id'],
                    $row['marine_sensors_id'],
                    $row['vessel_state_id'],
                    $row['wind_state_id'],
                    $row['windsensor_id'],
                    $row['current_mission_id']
                );
            $systemStmt->execute();

            // Whats does that do ? I have no idea...
            // if($systemStmt->affected_rows === 1) 
            // {
            //     $foreignKey[$row['id']] = $systemStmt->insert_id;
            //     $result[] = array("table" => "system_dataLogs", "id" => $row['id']);
            // } 
            // else 
            // {
            //     $foreignKey[$row['id']] = NULL;
            // }
        }
        $systemStmt->close();
        
        foreach ($data['dataLogs_vessel_state'] as $row)
        {
            $vesselStateStmt->bindParam(NULL,
                    $row['heading'],
                    $row['latitude'],
                    $row['longitude'],
                    $row['speed'],
                    $row['course'],
                    $row['t_timestamp']
                );
            $vesselStateStmt->execute();
        }
        $vesselStateStmt->close();
         
        foreach ($data['dataLogs_wind_state'] as $row)
        {
            $windStateStmt->bindParam(NULL,
                    $row['true_wind_speed'],
                    $row['true_wind_direction'],
                    $row['apparent_wind_speed'],
                    $row['apparent_direction'],
                    $row['t_timestamp']
                );
            $windStateStmt->execute();
        }
        $windStateStmt->close();

        foreach($data["dataLogs_windsensor"] as $row) 
        {
            $windSensorStmt->bind_param(NULL,
                    $row['direction'],
                    $row['speed'],
                    $row['temperature'],
                    $row['t_timestamp']
                );
            $windSensorStmt->execute();
        }
        $windSensorStmt->close();
	
	    $result = array('result' => 1);
        return json_encode($result);
    }
}
*/
// //when in non-wsdl mode the uri option must be specified
// $options=array('uri'=>'http://localhost/');
// //create a new SOAP server
// $server = new SoapServer(NULL,$options);
// //attach the API class to the SOAP Server
// $server->setClass('ASRService');
// //start the SOAP requests handler
// $server->handle();
?>
