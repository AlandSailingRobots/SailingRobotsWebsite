<?php

/**
 * Returns value of a flag in the DB
 *
 * @return string JSON
 */
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

    // FIX for DB column being VARCHAR when it should be INT or BOOL
    foreach ($result[0] as $key => $value) {
        if ($value == "") {
            $result[0][$key] = "0";
        }
    }
    return json_encode($result[0]);
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
    $db = $GLOBALS['db_connection'];
    $req = $db->prepare("SELECT table_name FROM information_schema.tables WHERE table_schema='ithaax_".$boat."_config' AND table_name LIKE 'config_%'");
    $req->execute();
    $rows = $req->fetchAll(PDO::FETCH_ASSOC);

    $tables = array();
    foreach ($rows as $row) {
        $tables[] = "ithaax_".$boat."_config.".$row['table_name'];
    }

    // Id could be PDO bound to a named parameter but well ..
    $result = getTables($tables, "*", "WHERE id = 1");

    foreach ($result as $key => $value) {
        $result[str_replace("ithaax_".$boat."_config.config_", "", $key)] = $value;
        unset($result[$key]);
    }
    $result = json_encode($result);
    return $result;
}
/*    $allData = array_merge(
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
}*/