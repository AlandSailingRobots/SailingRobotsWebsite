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
