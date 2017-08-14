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

        $this->db = new mysqli($servername, $username, $password, $dbname);
    }
    
    function __destruct() 
    {
        $this->db->close();
    }
    
    function checkIfNewConfigs() 
    {
            $sql = "SELECT updated FROM config_updated";
            $preResult = $this->db->query($sql);
            if (!$preResult) 
            {
                throw new Exception("Database Error [{$this->database->errno}] {$this->database->error}");
            }
            $result = $preResult->fetch_assoc();
            return $result['updated'];
    }
    
    function setConfigsUpdated() 
    {
        $sql = "UPDATE config_updated SET updated = 0 where id=1";
        $result = $this->db->query($sql);
    }
    
    function getAllConfigs($boat) 
    {
        $this->setConfigsUpdated();
        $allData = array_merge(
                $this->getConfig($boat, "course_calculation_config"),
                $this->getConfig($boat, "maestro_controller_config"),
                $this->getConfig($boat, "rudder_command_config"),
                $this->getConfig($boat, "rudder_servo_config"),
                $this->getConfig($boat, "sailing_robot_config"),
                $this->getConfig($boat, "sail_command_config"),
                $this->getConfig($boat, "sail_servo_config"),
                $this->getConfig($boat, "waypoint_routing_config"),
                $this->getConfig($boat, "windsensor_config"),
                $this->getConfig($boat, "wind_vane_config"),
                $this->getConfig($boat, "httpsync_config"),
                $this->getConfig($boat, "xbee_config"),
                $this->getConfig($boat, "buffer_config")
            );
        return json_encode($allData);
    }

    function getConfig($boat, $table) 
    {
        $preResult = $this->db->query("SELECT * FROM $table");
        if (!$preResult) 
        {
            throw new Exception("Database Error [{$this->database->errno}] {$this->database->error}");
        }
        $result = $preResult->fetch_assoc();
        $array = array($table => 0);
        $array[$table] = $result;
        return $array;
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
