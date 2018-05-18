<?php

// Functions to used to retrieve the points from the DB for the boat
function checkIfNewWaypoints()
{
    
    $db = $GLOBALS['db_connection'];

    $req = $db->prepare("SELECT route_updated FROM config_httpsync");
    $exec = $req->execute();
    
    if (!$exec) {
        throw new Exception("Database Error {$req->error}");
    }
    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();

    return $result[0]['route_updated'];
}

function setWaypointsUpdated()
{
    $db = $GLOBALS['db_connection'];
    $req = $db->prepare("UPDATE config_httpsync SET route_updated = 0 where id=1");
    $result = $req->execute();
}

function getWaypoints()
{
    setWaypointsUpdated();
    
    $db = $GLOBALS['db_connection'];
    $req = $db->prepare("SELECT * FROM currentMission");
    $preResult = $req->execute();
    if (!$preResult) {
        throw new Exception("Database Error [{$db->errno}] {$req->error}");
    }

    $result = $req->fetchAll(PDO::FETCH_ASSOC);

    $array = array();
    $array["waypoints"] = $result;
    
    return json_encode($array);
}
