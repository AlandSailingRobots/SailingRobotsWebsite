<?php
/**
 * File: getConfigs.php
 *
 * @see https://github.com/AlandSailingRobots/SailingRobotsWebsite
 */

/**
 * ASRService 
 */
class ASRService
{
    private $_db;

    /**
     * Constructor
     *
     * @return void
     */
    function __construct()
    {
        include_once '../../globalsettings.php';

        $servername = "localhost";
        $username = $GLOBALS['username'];
        $password = $GLOBALS['password'];
        $dbname = "ithaax_testdata";

        $this->_db = new mysqli($servername, $username, $password, $dbname);
    }
    
    /**
     * Destructor
     *
     * @return void
     */
    function __destruct()
    {
        $this->_db->close();
    }
    
    /**
     * Check if new configs have been received
     *
     * @return mixed[] Column 
     */
    function checkIfNewConfigs()
    {
        $sql = "SELECT updated FROM config_updated";
        $preResult = $this->_db->query($sql);
        if (!$preResult) {
            throw new Exception(
                "Database Error [{$this->database->errno}] {$this->database->error}"
            );
        }
        $result = $preResult->fetch_assoc();
        return $result['updated'];
    }
    
    /**
     * Set updated flag
     *
     * @return void
     */
    function setConfigsUpdated()
    {
        $sql = "UPDATE config_updated SET updated = 0 where id=1";
        $result = $this->_db->query($sql);
    }
    
    /**
     * Gets configs
     *
     * @param string $boat Boat ID
     *
     * @return string JSON data
     */
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

    /**
     * Get configuration data
     *
     * @param string $boat  Boat ID
     * @param string $table Table name
     *
     * @return array result
     */
    function getConfig($boat, $table)
    {
        $preResult = $this->_db->query("SELECT * FROM $table");
        if (!$preResult) {
            throw new Exception(
                "Database Error [{$this->database->errno}] {$this->database->error}"
            );
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
$server = new SoapServer(null, $options);
//attach the API class to the SOAP Server
$server->setClass('ASRService');
//start the SOAP requests handler
$server->handle();
