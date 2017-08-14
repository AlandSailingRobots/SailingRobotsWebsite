<?php
class ASRService 
{
    private $db;
    function __construct() 
    {
        require_once('../../globalsettings.php');

        $servername = "localhost";
        $username = $GLOBALS['username'];
        $password = $GLOBALS['password'];
        $dbname = "ithaax_testdata";
        // username = ithaax_testdata , pass = test123data
        // Local: username = root, pass = ""
        $this->db = new mysqli($servername, $username, $password, $dbname);

        //$this->db = new mysqli("localhost","ithaax_testdata","test123data","ithaax_testdata");
        //$this->db = new mysqli("localhost","root","","ithaax_testdata");
    }
    
    function __destruct() 
    {
        $this->db->close();
    }
    
    function pushConfigs($data) 
    {
        $data = json_decode($data,true);
        $id = 1;

        $course_calculationStmt = $this->db->stmt_init();
        $maestro_controllerStmt = $this->db->stmt_init();
        $rudder_commandStmt     = $this->db->stmt_init();
        $rudder_servoStmt       = $this->db->stmt_init();
        $sailing_robotStmt      = $this->db->stmt_init();
        $sail_commandStmt       = $this->db->stmt_init();
        $sail_servoStmt         = $this->db->stmt_init();
        $waypoint_routingStmt   = $this->db->stmt_init();
        $windsensorStmt         = $this->db->stmt_init();
        $wind_vaneStmt          = $this->db->stmt_init();
        $XbeeStmt               = $this->db->stmt_init();
        $HttpsyncStmt           = $this->db->stmt_init();
        $BufferStmt             = $this->db->stmt_init();

        $course_calculationStmt->prepare("UPDATE course_calculation_config SET sector_angle=?,tack_angle=?,tack_max_angle=?,tack_min_speed=? WHERE id=$id;");
        $maestro_controllerStmt->prepare("UPDATE  maestro_controller_config SET port=? WHERE id=$id;");
        $rudder_commandStmt->prepare("UPDATE rudder_command_config SET extreme_command=?,midship_command=? WHERE id=$id;");
        $rudder_servoStmt->prepare("UPDATE rudder_servo_config SET acceleration=?, channel=?, speed=? WHERE id=$id;");
        $sailing_robotStmt->prepare("UPDATE sailing_robot_config SET flag_heading_compass=?, loop_time=?, scanning=?, line_follow=?, require_network=? WHERE id=$id;");
        $sail_commandStmt->prepare("UPDATE sail_command_config SET close_reach_command=?, run_command=? WHERE id=$id;");
        $sail_servoStmt->prepare("UPDATE sail_servo_config SET acceleration=?,channel=?, speed=? WHERE id=$id;");
        $waypoint_routingStmt->prepare("UPDATE waypoint_routing_config SET adjust_degree_limit=?, max_command_angle=?,radius_ratio=?, rudder_speed_min=?, sail_adjust_time=? WHERE id=$id;");
        $windsensorStmt->prepare("UPDATE windsensor_config SET baud_rate=?, port=? WHERE id=$id");
        $wind_vaneStmt->prepare("UPDATE wind_vane_config SET use_self_steering=?, wind_sensor_self_steering=?, self_steering_interval=? WHERE id=$id;");
        $XbeeStmt->prepare("UPDATE xbee_config SET send=?, recieve=?, send_logs=?, delay=? WHERE id=$id;");
        $HttpsyncStmt->prepare("UPDATE httpsync_config SET delay=? WHERE id=$id;");
                $BufferStmt->prepare("UPDATE buffer_config SET compass=?, true_wind=?, windsensor=? WHERE id=$id;");

        $course_calculationStmt->bind_param("dddd",$data["course_calculation_config"]["sector_angle"],
        $data["course_calculation_config"]["tack_angle"],
        $data["course_calculation_config"]["tack_max_angle"],
        $data["course_calculation_config"]["tack_min_speed"]);
        $course_calculationStmt->execute();

        $maestro_controllerStmt->bind_param("s",$data["maestro_controller_config"]["port"]);
        $maestro_controllerStmt->execute();

        $rudder_commandStmt->bind_param("ii",$data["rudder_command_config"]["extreme_command"],
        $data["rudder_command_config"]["midship_command"]);
        $rudder_commandStmt->execute();

        $rudder_servoStmt->bind_param("iii",$data["rudder_servo_config"]["acceleration"],
        $data["rudder_servo_config"]["channel"],
        $data["rudder_servo_config"]["speed"]);
        $rudder_servoStmt->execute();

        $sailing_robotStmt->bind_param("idiii", $data["sailing_robot_config"]["flag_heading_compass"],
        $data["sailing_robot_config"]["loop_time"],
        $data["sailing_robot_config"]["scanning"],
        $data["sailing_robot_config"]["line_follow"],
        $data["sailing_robot_config"]["require_network"]);
        $sailing_robotStmt->execute();

        $sail_commandStmt->bind_param("ii", $data["sail_command_config"]["close_reach_command"],
        $data["sail_command_config"]["run_command"]);
        $sail_commandStmt->execute();

        $sail_servoStmt->bind_param("iii", $data["sail_servo_config"]["acceleration"],
        $data["sail_servo_config"]["channel"],
        $data["sail_servo_config"]["speed"]);
        $sail_servoStmt->execute();

        $waypoint_routingStmt->bind_param("ddddd", $data["waypoint_routing_config"]["adjust_degree_limit"],
        $data["waypoint_routing_config"]["max_command_angle"],
        $data["waypoint_routing_config"]["radius_ratio"],
        $data["waypoint_routing_config"]["rudder_speed_min"],
        $data["waypoint_routing_config"]["sail_adjust_time"]);
        $waypoint_routingStmt->execute();

        $windsensorStmt->bind_param("is", $data["windsensor_config"]["baud_rate"],
        $data["windsensor_config"]["port"]);
        $windsensorStmt->execute();

        $wind_vaneStmt->bind_param("iid", $data["wind_vane_config"]["use_self_steering"],
        $data["wind_vane_config"]["wind_sensor_self_steering"],
        $data["wind_vane_config"]["self_steering_interval"]);
        $wind_vaneStmt->execute();

        $XbeeStmt->bind_param("iiii", $data["xbee_config"]["send"],
        $data["xbee_config"]["recieve"],
        $data["xbee_config"]["send_logs"],
        $data["xbee_config"]["delay"]);
        $XbeeStmt->execute();

        $HttpsyncStmt->bind_param("i", $data["httpsync_config"]["delay"]);
        $HttpsyncStmt->execute();

                $BufferStmt->bind_param("iii",$data["buffer_config"]["compass"],
        $data["buffer_config"]["true_wind"],
        $data["buffer_config"]["windsensor"]);
        $BufferStmt->execute();


        $course_calculationStmt->close();
        $maestro_controllerStmt->close();
        $rudder_commandStmt->close();
        $rudder_servoStmt->close();
        $sailing_robotStmt->close();
        $sail_commandStmt->close();
        $sail_servoStmt->close();
        $waypoint_routingStmt->close();
        $windsensorStmt->close();
        $wind_vaneStmt->close();
        $XbeeStmt->close();
        $HttpsyncStmt->close();
        $BufferStmt->close();

        return $data["wind_vane_config"]["wind_sensor_self_steering"].$data["wind_vane_config"]["use_self_steering"];
        }
    }
//when in non-wsdl mode the uri option must be specified
$options=array('uri'=>'http://localhost/');
//create a new SOAP server
$server = new SoapServer(NULL,$options);
//attach the API class to the SOAP Server
$server->setClass('ASRService');
//start the SOAP requests handler
$server->handle();
?>
