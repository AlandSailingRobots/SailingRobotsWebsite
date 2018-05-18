<?php

function checkIfNewConfigs()
{
    $db = $GLOBALS['db_connection'];

    $req = $db->prepare("SELECT configs_updated FROM config_httpsync");
    $exec = $req->execute();
    
    if (!$exec) {
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
    return json_encode($allData);
}

function getConfig($boat, $table)
{
    $db = $GLOBALS['db_connection'];

    $req = $db->prepare("SELECT * FROM $table");
    $exec = $req->execute();

    if (!$exec) {
        throw new Exception("Database Error [{$db->errno}] {$req->error}");
    }
    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    $array = array($table => 0);
    $array[$table] = $result[0];
    return $array;
}
