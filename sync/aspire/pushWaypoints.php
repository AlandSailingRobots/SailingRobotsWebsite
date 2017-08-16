<?php

function pushWaypoint($data)
{
    $result = $data;
    $data = json_decode($data,true);
    $size = count($data);


    $db = $GLOBALS['db_connection'];

    $req = $db->prepare("DELETE FROM currentMission");
    $req->execute();
    
    $req = $db->prepare("ALTER TABLE currentMission AUTO_INCREMENT = 0;");
    $req->execute();
    
    $req = $db->prepare("INSERT INTO currentMission VALUES(?,?,?,?,?,?,?,?,?,?,?);");
    for($i=1; $i <= $size; $i++) 
    {
        $waypoints = "waypoint_".$i;
        foreach($data[$waypoints] as $row) 
        {
            $waypoint->execute(array($row['id'],
                                    $row['id_mission'],
                                    $row['rankInMission'],
                                    $row['is_checkpoint'],
                                    $row['name'],
                                    $row['latitude'],
                                    $row['longitude'],
                                    $row['declination'],
                                    $row['radius'],
                                    $row['stay_time'],
                                    $row['harvested']
                                ));
        }
    }
    $req->closeCursor();

    return $result;
}

// ASPire
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

    function pushWaypoint($data)
    {
        $result = $data;
        $data = json_decode($data,true);
        $size = count($data);
        
        $waypoint = $this->db->stmt_init();
        $waypoint->prepare("DELETE FROM currentMission");
        $waypoint->execute();
        
        $waypoint->prepare("ALTER TABLE currentMission AUTO_INCREMENT = 0;");
        $waypoint->execute();
        
        $waypoint->prepare("INSERT INTO currentMission VALUES(?,?,?,?,?,?,?,?,?,?,?);");
        for($i=1; $i <= $size; $i++) 
        {
            $waypoints = "waypoint_".$i;
            foreach($data[$waypoints] as $row) 
            {
                $waypoint->bind_param($row['id'],
                        $row['id_mission'],
                        $row['rankInMission'],
                        $row['is_checkpoint'],
                        $row['name'],
                        $row['latitude'],
                        $row['longitude'],
                        $row['declination'],
                        $row['radius'],
                        $row['stay_time'],
                        $row['harvested']
                    );
                $waypoint->execute();
            }
        }
        $waypoint->close();

        return $result;
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
