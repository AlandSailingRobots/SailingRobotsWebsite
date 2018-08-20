<?php

// Functions to used to retrieve the points from the DB for the boat

/**
 * Returns value of a flag in the DB
 *
 * @return string JSON
 * @throws Exception
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

    // FIX for DB column being VARCHAR when it should be INT or BOOL
    foreach ($result[0] as $key => $value) {
        if ($value == "") {
            $result[0][$key] = "0";
        }
    }
    return json_encode($result[0]);
}

/**
 *
 */
function setWaypointsUpdated()
{
    $db = $GLOBALS['db_connection'];
    $req = $db->prepare("UPDATE config_httpsync SET route_updated = 0 where id=1");
    $result = $req->execute();
}

/**
 * @return string
 * @throws Exception
 */
function getWaypoints()
{
    setWaypointsUpdated();
    $result = getTablesAsJSON("currentMission");
    return $result;
}

