<?php
// ASPire
// Decode the json array and send queries to the DB to update the configuration of the boat
function pushConfigs($data) 
{
    $db = $GLOBALS['db_connection'];
    $data = json_decode($data,true);
    $id = 1;
    // Shorter version, PDO style
    foreach ($data as $table_name => $table) 
    {
        $param_stmt = "";
        // Generate the array to be bind with the prepared SQL query
        $param_array = array();
        foreach ($table as $column_name => $value) 
        {
            // Patch b/c Marc changed its DB compared to the website
            $column_name = $column_name == "is_checkpoint" ? "isCheckpoint" : $column_name; 

            $param_array[$column_name] = $value;

            $param_stmt = $param_stmt . ' ' . $column_name . '= :'.$column_name . ',';
        }
        // Remove the extra comma
        $param_stmt = substr($param_stmt, 0, -1);

        // Prepare the SQL Query
        $query = $db->prepare('UPDATE '.$table_name . ' SET ' . $param_stmt  .';');
        $query->execute($param_array);
        // $query->close()
    }
    return 1;
}

/*
class ASRService 
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
    
    function pushConfigs($data) 
    {
        $data = json_decode($data,true);
        $id = 1;

    /*  TO BE TESTED !
        // Shorter version, PDO style
        foreach ($data as $table_name => $table) 
        {
            $size = count($table);

            // Generate the parameter string like: ?, ?, ?
            $param_stmt = '?';
            for ($i = 0, $i<$size, $i++)
            {
                $param_stmt = $param_stmt . '?, ';
            }
            $param_stmt = substr($param_stmt, -1);

            // Prepare the SQL Query
            $query = $bd->prepare('UPDATE '.$table_name . 'SET ' . $param_stmt  .';');
            
            // Generate the array to be bind with the prepared SQL query
            $param_array = array_fill(0, size, NULL);
            $i = 0;
            foreach ($table as $column_name => $value) 
            {
                if ($column_name != 'id')   // Leave the first field NULL to get the auto_increment from the DB
                {
                    $param_array[$i] = $value;
                }
                $i++;
            }
            $query->execute($param_array);
            // $query->close()
        }
    */
    /*  TO BE TESTED ! VERSION 2 
        // Shorter version, PDO style
        foreach ($data as $table_name => $table) 
        {
            $param_stmt = "";
            // Generate the array to be bind with the prepared SQL query
            $param_array = array();
            foreach ($table as $column_name => $value) 
            {
                if ($column_name != 'id')   // Leave the first field NULL to get the auto_increment from the DB
                {
                    $param_array[$column_name] = $value;
                }
                $param_stmt = $param_stmt . ' ' . $column_name . '= :'.$column_name . ',';
            }
            // Remove the extra comma
            $param_stmt = substr($para, 0, -1);

            // Prepare the SQL Query
            $query = $bd->prepare('UPDATE '.$table_name . 'SET ' . $param_stmt  .';');
            $query->execute($param_array);
            // $query->close()
        } 
        */
    /*
        $aisStmt = $this->db->stmt_init();
        $aisStmt->prepare("UPDATE config_ais SET id=?, loop_time=?;");
        $aisStmt->bind_param(NULL, $data["config_ais"]["loopt_time"]);
        $aisStmt->execute();
        $aisStmt->close();

        $aisProcessingStmt = $this->db->stmt_init();
        $aisProcessingStmt->prepare("UPDATE config_ais_processing SET id=?, loopt_time=?, radius=?, mmsi_aspire=?;");
        $aisProcessingStmt->bind_param(NULL, 
                                        $data['config_ais_processing']['loop_time'],
                                        $data['config_ais_processing']['radius'],
                                        $data['config_ais_processing']['mmsi_aspire']
                                    );
        $aisProcessingStmt->execute();
        $aisProcessingStmt->close();

        $bufferStmt = $this->db->stmt_init();
        $bufferStmt->prepare("UPDATE config_buffer SET id=?, compass=?, true_wind=?, windsensor=?;");
        $bufferStmt->bind_param(NULL,
                                $data['config_buffer']['compass'],
                                $data['config_buffer']['true_wind'],
                                $data['config_buffer']['windsensor']
                            );
        $bufferStmt->execute();
        $bufferStmt->close();

        $canArduinoStmt = $this->db->stmt_init();
        $canArduinoStmt->prepare('UPDATE config_can_arduino SET id=?, loop_time=?;');
        $canArduinoStmt->bind_param(NULL, $data['config_can_arduino']['loop_time']);
        $canArduinoStmt->execute();
        $canArduinoStmt->close();

        $compassStmt = $this->db->stmt_init();
        $compassStmt->prepare('UPDATE config_compass SET id=?, loop_time=?, heading_buffer_size=?;');
        $compassStmt->bind_param(NULL,
                                $data['config_compass']['loop_time'],
                                $data['config_compass']['heading_buffer_size']
                            );
        $compassStmt->execute();
        $compassStmt->close();

        $courseRegulatorStmt = $this->db->stmt_init();
        $courseRegulatorStmt->prepare('UPDATE config_course_regulator SET id=?, loop_time=?, max_rudder_angle=?, p_gain=?, i_gain=?, d_gain=?;');
        $courseRegulatorStmt->bind_param(NULL,
                                        $data['config_course_regulator']['loop_time'],
                                        $data['config_course_regulator']['max_rudder_angle'],
                                        $data['config_course_regulator']['p_gain'],
                                        $data['config_course_regulator']['i_gain'],
                                        $data['config_course_regulator']['d_gain']
                                    );
        $courseRegulatorStmt->execute();
        $courseRegulatorStmt->close();

        $dbloggerStmt = $this->db->stmt_init();
        $dbloggerStmt->prepare('UPDATE config_dblogger SET id=?, loop_time=?;');
        $dbloggerStmt->bind_param(NULL, $data['config_dblogger']['loop_time']);
        $dbloggerStmt->execute();
        $dbloggerStmt->close();

        $gpsStmt = $this->db->stmt_init();
        $gpsStmt->prepare('UPDATE config_gps SET id=?, loop_time=?;');
        $gpsStmt->bind_param(NULL, $data['config_gps']['loop_time']);
        $gpsStmt->execute();
        $gpsStmt->close();


        $httpSyncStmt = $this->db->stmt_init();
        $httpSyncStmt->prepare('UPDATE config_httpsync SET id=?, loop_time=?, remove_logs=?, push_only_latest_logs=?, boat_id=?, boat_pwd=?, srv_addr=?, configs_updated=?, route_updated=?;');
        $httpSyncStmt->bind_param(NULL,
                                $data['config_httpsync']['loop_time'],
                                $data['config_httpsync']['remove_logs'],
                                $data['config_httpsync']['push_only_latest_logs'],
                                $data['config_httpsync']['boat_id'],
                                $data['config_httpsync']['boat_pwd'],
                                $data['config_httpsync']['srv_addr'],
                                $data['config_httpsync']['configs_updated'],
                                $data['config_httpsync']['route_updated']
                            );
        $httpSyncStmt->execute();
        $httpSyncStmt->close();

        $lineFollowStmt = $this->db->stmt_init();
        $lineFollowStmt->prepare('UPDATE config_line_follow SET id=?, loop_time=?;');
        $lineFollowStmt->bind_param(NULL, $data['config_line_follow']['loop_time']);
        $lineFollowStmt->execute();
        $lineFollowStmt->close();

        $sailControlStmt = $this->db->stmt_init();
        $sailControlStmt->prepare('UPDATE config_sail_control SET id=?, loop_time=?, max_sail_angle=?, min_sail_angle=?;');
        $sailControlStmt->bind_param(NULL, 
                                $data['']['loop_time'],
                                $data['']['max_sail_angle'],
                                $data['']['min_sail_angle']
                            );
        $sailControlStmt->execute();
        $sailControlStmt->control();

        $simulatorStmt = $this->db->stmt_init();
        $simulatorStmt->prepare('UPDATE config_simulator SET id=?, loop_time=?;');
        $simulatorStmt->bind_param(NULL, $data['config_simulator']['loop_time']);
        $simulatorStmt->execute();
        $simulatorStmt->close();

        $solarTrackerStmt = $this->db->stmt_init();
        $solarTrackerStmt->prepare('UPDATE config_solar_tracker SET id=?, loop_time=?;');
        $solarTrackerStmt->bind_param(NULL, $data['config_solar_tracker']['loop_time']);
        $solarTrackerStmt->execute();
        $solarTrackerStmt->close();        

        $vesselStateStmt = $this->db->stmt_init();
        $vesselStateStmt->prepare('UPDATE config_vessel_state SET id=?, loop_time=?, course_config_speed_1=?, course_config_speed_2=?;');
        $vesselStateStmt->bind_param(NULL,
                                    $data['config_vessel_state']['loop_time'],
                                    $data['config_vessel_state']['course_config_speed_1'],
                                    $data['config_vessel_state']['course_config_speed_2']
                                );
        $vesselStateStmt->execute();
        $vesselStateStmt->close();

        $voterSystemStmt = $this->db->stmt_init();
        $voterSystemStmt->prepare('UPDATE config_voter_system SET id=?, loop_time=?, max_vote=?, waypoint_voter_weight=?, wind_voter_weight=?, channel_voter_weight=?, midrange_voter_weight=?, proximity_voter_weight=?;');
        $voterSystemStmt->bind_param(NULL,
                                    $data['config_voter_system']['loop_time'],
                                    $data['config_voter_system']['max_vote'],
                                    $data['config_voter_system']['waypoint_voter_weight'],
                                    $data['config_voter_system']['wind_voter_weight'],
                                    $data['config_voter_system']['channel_voter_weight'],
                                    $data['config_voter_system']['midrange_voter_weight'],
                                    $data['config_voter_system']['proximity_voter_weight']
                                );
        $voterSystemStmt->execute();
        $voterSystemStmt->close();

        $windSensorStmt = $this->db->stmt_init();
        $windsensorStmt->prepare('UPDATE config_wind_sensor SET id=?, loop_time=?;');
        $windsensorStmt->bind_param(NULL, $data['config_wind_sensor']['loop_time']);
        $windsensorStmt->execute();
        $windsensorStmt->close();         

        $xbeeStmt = $this->db->stmt_init();
        $xbeeStmt->prepare('UPDATE config_xbee SET id=?, loop_time=?, send=?, receive=?, send_logs=?, push_only_latest_logs=?;');
        $xbeeStmt->bind_param(NULL, 
                                $data['config_xbee']['loop_time'],
                                $data['config_xbee']['send'],
                                $data['config_xbee']['receive'],
                                $data['config_xbee']['send_logs'],
                                $data['config_xbee']['push_only_latest_logs']
                            );
        $xbeeStmt->execute();
        $xbeeStmt->close();

        $currentMissionStmt = $this->db->stmt_init();
        $currentMissionStmt->prepare("UPDATE currentMission VALUES id=?, id_mission=?, rankInMission=?, isCheckpoint=?, name=?, latitude=?, longitude=?, declination=?, radius=?, stay_time=?, harvested=?;");
        $currentMissionStmt->bind_param($data['current_mission']['id'],
                                $data['current_mission']['id_mission'],
                                $data['current_mission']['rankInMission'],
                                $data['current_mission']['is_checkpoint'],
                                $data['current_mission']['name'],
                                $data['current_mission']['latitude'],
                                $data['current_mission']['longitude'],
                                $data['current_mission']['declination'],
                                $data['current_mission']['radius'],
                                $data['current_mission']['stay_time'],
                                $data['current_mission']['harvested']
                            );
        $currentMissionStmt->execute();
        $currentMissionStmt->close();

        //return $data["wind_vane_config"]["wind_sensor_self_steering"].$data["wind_vane_config"]["use_self_steering"];
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
