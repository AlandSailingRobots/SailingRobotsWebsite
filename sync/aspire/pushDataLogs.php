<?php
// ASPire

/**
 * Inserts data into the database
 *
 * @param dbhandle $db         Database handle
 * @param string   $table_name Table
 * @param array    $entries    Values
 *
 * @return void
 */
function populateDatabase($db, $table_name, $entries)
{
    $param_stmt = "(";
    $param_to_fill = "(";
    $param_array = array();
    $debugstring = "";
    foreach ($entries as $column_name => $value) {
        $param_array[$column_name] = $value;
        $param_stmt = $param_stmt . ''.$column_name .',' ;
        $param_to_fill = $param_to_fill . ':'.$column_name.',';
        $debugstring .= "$column_name=\"$value\" ";
    }
    // Remove the extra comma
    $param_stmt = substr($param_stmt, 0, -1).')';

    // Now whe have something like (?, ?, ?, ?)
    $param_to_fill = substr($param_to_fill, 0, -1).')';
    $param_array['id'] = null;

    // Prepare the SQL Query
    $sql = "INSERT INTO $table_name $param_stmt VALUES $param_to_fill ;";
    $query = $db->prepare($sql);
    try {
        $query->execute($param_array);
    } catch (PDOException $e) {
        header(
            $_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error',
            true,
            500
        );
        error_log("500: ".$e->getMessage()." on \"$sql\"");
        die($e->getMessage());
    }
}

/**
 * Receives data from boat
 *
 * @param string $boat Boat ID
 * @param array  $data Array of data
 *
 * @return void
 */
function pushAllLogs($boat, $data)
{
    $db = $GLOBALS['db_connection'];
    if (!isset($db)) {
        header(
            $_SERVER['SERVER_PROTOCOL'].' 503 Service Unavailable',
            true,
            503
        );
        die('Error: No db handle!');
    }

    $data = json_decode($data, true);

    if (empty($data)) {
        header(
            $_SERVER['SERVER_PROTOCOL'].' 400 Bad Request',
            true,
            400
        );
        die('Error: no recognizable data');
    }
    $dataLogs_system = [];
    if (array_key_exists('dataLogs_system', $data)) {
        $idMap = array();

        $dataLogs_system = $data['dataLogs_system'][0];
        unset($data['dataLogs_system']);
    }

    foreach ($data as $table_name => $table) {
        // Generate the array to be bind with the prepared SQL query
        foreach ($table as $id_log => $log) {
            if (!empty($log)) {
                populateDatabase($db, $table_name, $log);

                $tableNamePrefix = "dataLogs_";

                // We have to insert the latest correct ID's into the
                // dataLogs_system, so save the id's of new entries and
                // insert dataLogs_system at the end
                if (strpos($table_name, $tableNamePrefix) !== false) {
                    $trueTableNameStart = substr(
                        $table_name,
                        strlen($tableNamePrefix)
                    );

                    $idMap[$trueTableNameStart] = $db->lastInsertId();
                }
            }
        }
    }

    foreach ($idMap as $column_name => $value) {
        $dataLogs_system[$column_name."_id"] = $value;
    }
    if (!empty($dataLogs_system)) {
        populateDatabase($db, "dataLogs_system", $dataLogs_system);
    }
}
