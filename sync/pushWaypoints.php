<?php

function pushWaypoint($data) {
    $result = $data;
    $data = json_decode($data, true);
    $size = count($data);

    $db = $GLOBALS['db_connection'];

    $req = $db->prepare("DELETE FROM currentMission");
    $req->execute();    // Continue even if old data is there

    insertTables($data);
    return true;
}
