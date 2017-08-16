<?php

   function pushAllLogs($boat, $data) 
    {
    
        $servername = $GLOBALS['hostname'];
        $username   = $GLOBALS['username'];
        $password   = $GLOBALS['password'];
        $dbname     = $GLOBALS['database_ASPire'];

        $db = new mysqli($servername, $username, $password, $dbname);
        print_r($data);
        $data                   = json_decode($data,true);
        echo "################################################";
        print_r($data);
        $actuatorFeedbackStmt   = $db->stmt_init();
        $compassStmt            = $db->stmt_init();
        $courseCalculationStmt  = $db->stmt_init();
        $currentSensorsStmt     = $db->stmt_init();

        $gpsStmt                = $db->stmt_init();
        $marineSensorsStmt      = $db->stmt_init();
        $systemStmt             = $db->stmt_init();
        $vesselStateStmt        = $db->stmt_init();
        $windSensorStmt         = $db->stmt_init();
        $windStateStmt          = $db->stmt_init();
        
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

        foreach($data['dataLogs_current_sensors'] as $row)
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

        foreach($data['dataLogs_marine_sensors'] as $row)
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
    require_once('../globalsettings.php');
    require_once('php/is_pwd_correct.php');
    /*
        This folder is used for the httpsync and it has no "interface" so you cant go to url/sync.
        The location's that have something like http://localhost/ is used to test the sync localy and the
        location's that have something like http://www.sailingrobots.com/ is used to test the sync on the website.
    */
    if (!empty($_POST))
    {
        $connected = false;
        //echo 'POST array: '."\n";
        //print_r($_POST);
        echo "\n";

        if ( isset($_POST['gen']) && $_POST['gen'] == 'aspire' )
        {
            // ASPire connection to the website
            if ( isset($_POST['id']) && isset($_POST['pwd']) )
            {
                if ( is_pwd_correct($_POST['pwd']) )
                {
                    $optionsPushlogs      = array('location' => $GLOBALS['server'].'sync/aspire/pushDataLogs.php',  'uri' => 'http://localhost/');
                    $optionsGetConfigs    = array('location' => $GLOBALS['server'].'sync/aspire/getConfigs.php',    'uri' => 'http://localhost/');
                    $optionsPushConfigs   = array('location' => $GLOBALS['server'].'sync/aspire/pushConfigs.php',   'uri' => 'http://localhost/');
                    $optionsPushwaypoints = array('location' => $GLOBALS['server'].'sync/aspire/pushWaypoints.php', 'uri' => 'http://localhost/');
                    $optionsGetWaypoints  = array('location' => $GLOBALS['server'].'sync/aspire/getWaypoints.php',  'uri' => 'http://localhost/');

                    $connected = true;
                }
                else
                {
                    echo "ERROR: Wrong Password ! \n";
                }
            }
            else
            {
                echo 'ERROR: Missing fild : "id" and/or "pwd"';
            }

        }
        elseif ( isset($_POST['gen']) && $_POST['gen'] == 'janet' )
        {
            // Janet connection
            // Much less secure
            if( isset($_POST['id']) && isset($_POST['pwd']) ) 
            {
                $optionsPushlogs      = array('location' => $GLOBALS['server'].'sync/janet/pushDatalogs.php',  'uri' => 'http://localhost/');
                $optionsGetConfigs    = array('location' => $GLOBALS['server'].'sync/janet/getConfigs.php',    'uri' => 'http://localhost/');
                $optionsPushConfigs   = array('location' => $GLOBALS['server'].'sync/janet/pushConfigs.php',   'uri' => 'http://localhost/');
                $optionsPushwaypoints = array('location' => $GLOBALS['server'].'sync/janet/pushWaypoints.php', 'uri' => 'http://localhost/');
                $optionsGetWaypoints  = array('location' => $GLOBALS['server'].'sync/janet/getWaypoints.php',  'uri' => 'http://localhost/');

                $connected = true;
            }
            else
            {
                echo ' ERROR: Missing fild : "id" and/or "pwd"';
            }
        }
        else
        {
            echo ' ERROR: "gen" field "aspire" or "janet" missing !';
        }

        // Janet or ASPire connection
        if ($connected)
        {
        
            //create an instante of the SOAPClient (the API will be available)
            $pushLogsService     = new SoapClient(NULL, $optionsPushlogs);
            $getConfigsService   = new SoapClient(NULL, $optionsGetConfigs);
            $pushConfigsService  = new SoapClient(NULL, $optionsPushConfigs);
            $pushPushWaypoints   = new SoapClient(NULL, $optionsPushwaypoints);
            $getWaypointsService = new SoapClient(NULL, $optionsGetWaypoints);

 

            if(isset($_POST["serv"])) 
            {
                try 
                {
                    switch($_POST["serv"]) 
                    {
                        case "_checkIfNewConfigs":
                            echo $getConfigsService->checkIfNewConfigs();
                            break;
                        case "_checkIfNewWaypoints":
                            echo $getWaypointsService->checkIfNewWaypoints();
                            break;
                        case "setConfigsUpdated":
                            print_r($getConfigsService->setConfigsUpdated());
                            break;
                        case "getAllConfigs":
                            print_r($getConfigsService->getAllConfigs($_POST["id"]));
                            break;
                        case "getWaypoints":
                            print_r($getWaypointsService->getWaypoints());
                            break;
                        case "pushConfigs":
                            print_r($pushConfigsService->pushConfigs($_POST["data"]));
                            break;
                        case "pushWaypoints":
                            print_r($pushPushWaypoints->pushWaypoint($_POST["data"]));
                            break;
                        case "pushAllLogs":
                            //print_r($pushLogsService);
                            //print_r($pushLogsService->helloWorld());
                            print_r(pushAllLogs($_POST["id"], $_POST["data"]));
                            break;
                        default:
                            break;
                    }
                } 
                catch(Exception $e) 
                {
                    print_r("ERROR: (exception thrown in sync/index.php): ".$e->getMessage());
                }
            }
            else
            {
                echo 'ERROR: "serv" field is empty';
            }
        }
        else
        {
            echo 'ERROR: Something happened !';
        }

    }
    else 
    {
        // echo "This folder is used for the httpsync and it has no \"interface\".";
        header('Location: ./..'); // Go back to the home page
    }
?>
