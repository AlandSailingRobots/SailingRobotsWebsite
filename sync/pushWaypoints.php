<?php

function pushWaypoint($data) {
    fastcgi_finish_request();
    $result = json_decode($data, true);
    if (count($result)) {
        $db = $GLOBALS['db_connection'];

        $req = $db->prepare("DELETE FROM currentMission");
        $req->execute();    // Continue even if old data is there

        insertTables($result);
        return true;
    }
}
