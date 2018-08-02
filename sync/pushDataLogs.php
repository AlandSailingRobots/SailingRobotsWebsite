<?php
// Generic, please move to separate common file

/**
 * @param dbhandle $db       Database handle
 * @param string   $selector SELECT xxx
 * @param string   $from     FROM yyy
 *
 * @return int value
 */
// TODO: try-catch and error checks
function selectFromAsInt($db, $selector, $from)
{
    $sql = "SELECT $selector FROM $from;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_BOTH);
    return $result[0];
}

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
/*function populateDatabase($db, $table_name, $entries)
{
    $param_stmt = "(";
    $param_to_fill = "(";
    $param_array = array();

    error_log("Table:".$table_name." \"".var_dump($entries)."\"");

    // TODO: This should be a ROLLBACKable TRANSACTION
    foreach ($entries as $column_name => $row) {
        foreach ($row as $values) {
            $param_array[$column_name] = $values;
            $param_stmt = $param_stmt . '' . $column_name . ',';
            $param_to_fill = $param_to_fill . ':' . $column_name . ',';
        }
        // Remove the extra comma
        $param_stmt = substr($param_stmt, 0, -1).')';

        // Now whe have something like (?, ?, ?, ?)
        $param_to_fill = substr($param_to_fill, 0, -1).')';
        $param_array['id'] = null;

        // Prepare the SQL Query
        $sql = "INSERT INTO $table_name $param_stmt VALUES $param_to_fill ;";
        error_log("DEBUG: \"$sql\"");
        $query = $db->prepare($sql);
        try {
            $query->execute($param_array);
        } catch (PDOException $e) {
            // DEBUG: Mon May 28 11:11:46 EEST 2018
            header(
                $_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error',
                true,
                500
            );
            error_log("500: ".$e->getMessage()." on \"$sql\"");
            die($e->getMessage());
        }
    }
}*/

/**
 * Receives data from boat
 *
 * @param string $boat Boat ID
 * @param array  $data Array of data
 *
 * @return void
 */
function pushAllLogs($boat, $json) {
    // Get the DB
    $db = $GLOBALS['db_connection'];
    if (!isset($db)) {
        header(
            $_SERVER['SERVER_PROTOCOL'].' 503 Service Unavailable',
            true,
            503
        );
        error_log("503: pushAllLogs() No db handle!".PHP_EOL);
        die('Error: No db handle!');
    }

    // Decode JSON
    $data = json_decode($json, true);
    if (empty($data)) {
        header(
            $_SERVER['SERVER_PROTOCOL'].' 400 Bad Request',
            true,
            400
        );
        error_log("400: pushAllLogs() No data in JSON!".PHP_EOL);
        die('Error: no recognizable data');
    }

    // We will not report anything more so just let the sender off the hook
    fastcgi_finish_request();

    // We will use the dataLogs_system table differently so remove it for now
    $dataLogs_system = array();
    if (array_key_exists('system', $data)) {
        $dataLogs_system = $data['system'];
        unset($data['system']);

        // The SQLite and MySQL dbs have different ID series
        $mysql_id = selectFromAsInt($db, "MAX(id)", 'dataLogs_system');
        $idColumn = array_search("id", $dataLogs_system[0]);
        $sqlite_id = $dataLogs_system[1][$idColumn];
        $idOffset = $mysql_id - $sqlite_id + 1;

        for ($i = 1; $i < count($dataLogs_system); $i++) {
            $dataLogs_system[$i][$idColumn] = $dataLogs_system[$i][$idColumn] + $idOffset;
        }
    }

    // Calculate row id offsets between SQLite DB on the boat and MySQL DB on the web server
    $tables = array();
    foreach ($data as $tablePartName => $rows) {
        $tableName = "dataLogs_$tablePartName";

        // The SQLite and MySQL dbs have different ID series
        $mysql_id = selectFromAsInt($db, "MAX(id)", $tableName);
        $idColumn = array_search("id", $rows[0]);
        $sqlite_id = $rows[1][$idColumn];
        $idOffset = $mysql_id - $sqlite_id + 1;

        $i = 0;
        foreach ($rows as $row) {
            if ($i > 0) {
                $row[$idColumn] = $row[$idColumn] + $idOffset;
                $systemColumn = array_search($tablePartName."_id", $dataLogs_system[0]);
                $dataLogs_system[$i][$systemColumn] = $dataLogs_system[$i][$systemColumn] + $idOffset;
            }
            $tables[$tableName][] = $row;
            $i++;
        }
    }
    $tables['dataLogs_system'] = $dataLogs_system;
    insertTables($tables);
}
