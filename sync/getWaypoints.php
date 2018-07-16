<?php

// Functions to used to retrieve the points from the DB for the boat

/**
 * Returns value of a flag in the DB
 *
 * @return string JSON
 */
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

    return json_encode($result[0]);
    // return json_encode($result[0]['route_updated']);
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
    $array["current_Mission"][] = array_keys($result[0]);
    foreach ($result as $r) {
        $row = array();
        $j = 0;
        foreach (array_keys($result[0]) as $key) {
            $row[$j++] = $r[$key];
        }
        $array["current_Mission"][] = $row;
    }
    return json_encode($array);
}
