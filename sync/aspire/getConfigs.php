<?php

function checkIfNewConfigs() 
{
    $db = $GLOBALS['db_connection'];

    $req = $db->prepare("SELECT configs_updated FROM config_httpsync");
    $exec = $req->execute();
    
    if (!$exec) 
    {
        throw new Exception("Database Error {$req->error}");
    }
    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $result[0]['configs_updated'];
}

function setConfigsUpdated() 
{
    $db = $GLOBALS['db_connection'];
    $req = $db->prepare("UPDATE config_httpsync SET configs_updated = 0 where id=1");
    $result = $req->execute();
}


function getAllConfigs($boat) 
{
    setConfigsUpdated();
    $allData = array_merge(
            getConfig($boat, "config_ais"),
            getConfig($boat, "config_ais_processing"),
            getConfig($boat, "config_buffer"),
            getConfig($boat, "config_can_arduino"),
            getConfig($boat, "config_course_regulator"),
            getConfig($boat, "config_dblogger"),

            getConfig($boat, "config_gps"),
            getConfig($boat, "config_httpsync"),
            getConfig($boat, "config_i2c"),
            getConfig($boat, "config_marine_sensors"),
            getConfig($boat, "config_simulator"),
            
            getConfig($boat, "config_solar_tracker"),
            getConfig($boat, "config_vessel_state"),
            getConfig($boat, "config_voter_system"),
            getConfig($boat, "config_wind_sensor"),
            getConfig($boat, "config_xbee")
        );
    print_r($allData);
    return json_encode($allData);
}

function getConfig($boat, $table) 
{
    $db = $GLOBALS['db_connection'];

    $req = $db->prepare("SELECT * FROM $table");
    $exec = $req->execute();

    if (!$exec) 
    {
        throw new Exception("Database Error [{$db->errno}] {$req->error}");
    }
    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    $array = array($table => 0);
    $array[$table] = $result[0];
    return $array;
}


// ASPire file
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
    
    // function checkIfNewConfigs() 
    // {
    //     $sql = "SELECT updated FROM config_updated";
    //     $preResult = $this->db->query($sql);
    //     if (!$preResult) 
    //     {
    //         throw new Exception("Database Error [{$this->database->errno}] {$this->database->error}");
    //     }
    //     $result = $preResult->fetch_assoc();
    //     return $result['updated'];
    // }
    
    // function setConfigsUpdated() 
    // {
    //     $sql = "UPDATE config_updated SET updated = 0 where id=1";
    //     $result = $this->db->query($sql);
    // }
    

    function getAllConfigs($boat) 
    {
        $this->setConfigsUpdated();
        $allData = array_merge(
                $this->getConfig($boat, "config_ais"),
                $this->getConfig($boat, "config_ais_processing"),
                $this->getConfig($boat, "config_buffer"),
                $this->getConfig($boat, "config_can_arduino"),
                $this->getConfig($boat, "config_course_regulator"),
                $this->getConfig($boat, "config_dblogger"),

                $this->getConfig($boat, "config_gps"),
                $this->getConfig($boat, "config_httpsync"),
                $this->getConfig($boat, "config_i2c"),
                $this->getConfig($boat, "config_marine_sensors"),
                $this->getConfig($boat, "config_simulator"),
                
                $this->getConfig($boat, "config_solar_tracker"),
                $this->getConfig($boat, "config_vessel_state"),
                $this->getConfig($boat, "config_voter_system"),
                $this->getConfig($boat, "config_wind_sensor"),
                $this->getConfig($boat, "config_xbee")
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
