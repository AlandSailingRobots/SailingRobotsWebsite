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
function populateDatabase($db, $table_name, $entries)
{
    $param_stmt = "(";
    $param_to_fill = "(";
    $param_array = array();

    error_log("Table:".$table_name." \"".var_dump($entries)."\"");

    // TODO: This shuould be a ROLLBACKable TRANSACTION
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

    // We will use the dataLogs_system table differently so remove it for now
    $dataLogs_system = array();
    if (array_key_exists('system', $data)) {
        $dataLogs_system = $data['system'];
        unset($data['system']);
    }

    // Process each table but store the SQLite DB id offset to ours for each table
    $idmap = array();
    foreach ($data as $table => $rows) {
        $tableName = "dataLogs_$table";
        $columnNames = array_shift($rows);

        // The SQLite and MySQL dbs have different ID series
        $mysql_id = selectFromAsInt($db, "MAX(id)", $tableName);
        $sqlite_id = $rows[0][0];
        $idOffset = $mysql_id - $sqlite_id + 1;

        // We should probably get column types as well?

        // For PDO binding
        $columnBindings = $columnNames;
        array_walk(
            $columnBindings,
            function (&$value) {
                $value = ':'.$value;
            }
        );

        $sql = "INSERT INTO $tableName(".implode(',', $columnNames).") VALUES(".implode(',', $columnBindings).");";
        $query = $db->prepare($sql);

        foreach ($rows as $row) {
            $row[0] += $idOffset;

            for ($i=0; $i<count($columnNames); $i++) {
                if (!$query->bindValue($columnNames[$i], $row[$i])) {
                    error_log("pushAllLogs(): Unable to bind $tableName parameter $i\"$columnNames[$i]\"=\"$row[$i]\"".PHP_EOL);
                }
            }
            try {
                if ($query->execute()) {
                    $idmap[$table."_id"] = $row[0];
                }
            } catch (PDOException $e) {
                header(
                    $_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error',
                    true,
                    500
                );
                error_log("500: ".$e->getMessage()." on \"$sql\"".PHP_EOL);
                die($e->getMessage());
            }
        }
        // $offsets[$table."_id"] = ; // the id of the first row sent
    }

    // This value is not like the others
    $idmap["current_mission_id"] = $dataLogs_system[1][array_search("current_mission_id", $dataLogs_system[0])];

    // Let's do the master table
    $idmapBindings = array_keys($idmap);
    array_walk(
        $idmapBindings,
        function (&$value) {
            $value = ':'.$value;
        }
    );
    $sql = "INSERT INTO dataLogs_system(".implode(',', array_keys($idmap)).") VALUES(".implode(',', $idmapBindings).");";
    $query = $db->prepare($sql);
    foreach ($idmap as $key => $value) {
        if (!$query->bindValue(":".$key, $value, PDO::PARAM_INT)) {
            error_log("pushAllLogs(): Unable to bind dataLogs_system parameter $i\"$columnNames[$i]\"=\"$row[$i]\"".PHP_EOL);
        }
    }
    try {
        $query->execute();
    } catch (PDOException $e) {
        header(
            $_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error',
            true,
            500
        );
        error_log("500: ".$e->getMessage()." on \"$sql\"".PHP_EOL);
        die($e->getMessage());
    }

    return;

/*    foreach ($data as $table_name => $table) {
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
    }*/
}
