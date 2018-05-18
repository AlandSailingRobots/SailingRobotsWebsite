<?php
/**
 * File: getWaypoints.php
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
     * Retreives updated waypoints from DB
     *
     * @return mixed[] Column
     */
    function checkIfNewWaypoints()
    {
        $sql = "SELECT waypoints_updated FROM config_updated";
        $preResult = $this->_db->query($sql);
        if (!$preResult) {
            throw new Exception(
                "Database Error [{$this->database->errno}] {$this->database->error}"
            );
        }
        $result = $preResult->fetch_assoc();
        return $result['waypoints_updated'];
    }
    
    /**
     * Resets waypoints_updated with id=1 in DB
     *
     * @return void
     */
    function setWaypointsUpdated()
    {
        $sql = "UPDATE config_updated SET waypoints_updated = 0 where id = 1";
        $this->_db->query($sql);
    }
    
    /**
     * Retreives waypoints from DB
     *
     * @return string JSON-encoded array
     */
    function getWaypoints()
    {
        $this->setWaypointsUpdated();
        $preResult = $this->_db->query("SELECT * FROM waypoints");
        if (!$preResult) {
            throw new Exception(
                // NOTE: Should $this->database be $this->_db here?
                "Database Error [{$this->database->errno}] {$this->database->error}"
            );
        }

        $result = [];
        while ($row = $preResult->fetch_row()) {
            $result[] = $row;
        }
        $array = array("waypoints" => 0);
        $array["waypoints"] = $result;
        
        return json_encode($array);
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
