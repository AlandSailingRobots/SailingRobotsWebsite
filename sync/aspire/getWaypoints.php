<?php
// ASPire
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

    // function checkIfNewWaypoints() 
    // {
    //     $sql = "SELECT waypoints_updated FROM config_updated";
    //     $preResult = $this->db->query($sql);
    //     if (!$preResult) {
    //         throw new Exception("Database Error [{$this->database->errno}] {$this->database->error}");
    //     }
    //     $result = $preResult->fetch_assoc();
    //     return $result['waypoints_updated'];
    // }
    
    // function setWaypointsUpdated() 
    // {
    //     $sql = "UPDATE config_updated SET waypoints_updated = 0 where id=1";
    //     $result = $this->db->query($sql);
    // }
    
    function getWaypoints() 
    {
        $this->setWaypointsUpdated();
        $preResult = $this->db->query("SELECT * FROM currentMission");
        if (!$preResult) 
        {
            throw new Exception("Database Error [{$this->database->errno}] {$this->database->error}");
        }

        $result = [];
        while ($row = $preResult->fetch_row()) 
        {
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
$server = new SoapServer(NULL,$options);
//attach the API class to the SOAP Server
$server->setClass('ASRService');
//start the SOAP requests handler
$server->handle();
?>
