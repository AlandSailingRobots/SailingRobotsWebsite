<?php
class ASRService
{

    private $db;

    function __construct()
    {
        //$this->db = new mysqli("localhost","ithaax_testdata","test123data","ithaax_testdata");
        require_once('../../globalsettings.php');

        $servername = 'localhost';
        $username = $GLOBALS['username'];
        $password = $GLOBALS['password'];
        $dbname = 'ithaax_testdata';
        $this->db = new mysqli($servername, $username, $password, $dbname);
    }
    
    function __destruct()
    {
        $this->db->close();
    }
    
    function pushAllLogs($boat, $data)
    {
        $data = json_decode($data, true);
        $compassStmt = $this->db->stmt_init();
        $courseCalculationStmt = $this->db->stmt_init();
        $gpsStmt = $this->db->stmt_init();
        $systemStmt = $this->db->stmt_init();
        $windSensorStmt = $this->db->stmt_init();
        $arduinoStmt = $this->db->stmt_init();

        $compassStmt->prepare("INSERT INTO compass_dataLogs VALUES(NULL,?,?,?, NULL);");
        $courseCalculationStmt->prepare("INSERT INTO course_calculation_dataLogs VALUES(NULL,?,?,?,?,?,NULL);");
        $gpsStmt->prepare("INSERT INTO gps_dataLogs VALUES(NULL,?,?,?,?,?,?,NULL,?);");
        $systemStmt->prepare("INSERT INTO system_dataLogs VALUES(NULL,?,?,?,?,?,?,?,NULL);");
        $windSensorStmt->prepare("INSERT INTO windsensor_dataLogs VALUES(NULL,?,?,?,NULL);");
        $arduinoStmt->prepare("INSERT INTO arduino_dataLogs VALUES(NULL,?,?,?,?);");

////////////////////
        $marineStmt = $this->db->stmt_init();
        $marineStmt->prepare("INSERT INTO dataLogs_marine_sensors VALUES (?,?,?,?,?);");
//////////////////////

        foreach ($data["gps_datalogs"] as $row) {
            $gpsStmt->bind_param(
                "sdddidi",
                $row["time"],
                $row["latitude"],
                $row["speed"],
                $row["heading"],
                $row["satellites_used"],
                $row["longitude"],
                $row["route_started"]
            );
                $gpsStmt->execute();
        }
        
        foreach ($data["course_calculation_datalogs"] as $row) {
            $courseCalculationStmt->bind_param(
                "dddii",
                $row["distance_to_waypoint"],
                $row["bearing_to_waypoint"],
                $row["course_to_steer"],
                $row["tack"],
                $row["going_starboard"]
            );
                $courseCalculationStmt->execute();
        }
    
        foreach ($data["compass_datalogs"] as $row) {
            $compassStmt->bind_param(
                "iii",
                $row["heading"],
                $row["pitch"],
                $row["roll"]
            );
                $compassStmt->execute();
        }
    
        foreach ($data["windsensor_datalogs"] as $row) {
            $windSensorStmt->bind_param(
                "idd",
                $row["direction"],
                $row["speed"],
                $row["temperature"]
            );
            $windSensorStmt->execute();
        }

        foreach ($data["arduino_datalogs"] as $row) {
            $arduinoStmt->bind_param(
                "iiii",
                $row["pressure"],
                $row["rudder"],
                $row["sheet"],
                $row["current"]
            );
            $arduinoStmt->execute();
        }

/////////////////////////
        foreach ($data["marine_sensors_dataLogs"] as $row) {
            $marineStmt->bind_param(
                "iiii",
                $row["temperature"],
                $row["conductivity"],
                $row["ph"],
                $row["time_stamp"]
            );
            $arduinoStmt->execute();
        }
///////////////////////////

        foreach ($data["system_datalogs"] as $row) {
            $systemStmt->bind_param(
                "siiiiid",
                $boat,
                $row["sail_command_sail_state"],
                $row["rudder_command_rudder_state"],
                $row["sail_servo_position"],
                $row["rudder_servo_position"],
                $row["waypoint_id"],
                $row["true_wind_direction_calc"]
            );
                $systemStmt->execute();

            if ($systemStmt->affected_rows === 1) {
                $foreignKey[$row["id"]] = $systemStmt->insert_id;
                $result[] = array("table" => "system_dataLogs", "id" => $row["id"]);
            } else {
                $foreignKey[$row["id"]] = null;
            }
        }

        $systemStmt->close();
        $windSensorStmt->close();
        $compassStmt->close();
        $courseCalculationStmt->close();
        $gpsStmt->close();
        $arduinoStmt->close();

//////////////
        $marineStmt->close();
/////////////

        return json_encode($result);
    }
}
//when in non-wsdl mode the uri option must be specified
$options=array('uri'=>'http://localhost/');
//create a new SOAP server
$server = new SoapServer(null, $options);
//attach the API class to the SOAP Server
$server->setClass('ASRService');
//start the SOAP requests handler
$server->handle();
