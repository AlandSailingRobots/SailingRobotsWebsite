<?php
/**
 * Created by PhpStorm.
 * User: Kåre Hampf <khampf@users.sourceforge.net>
 * Date: 7/23/18
 * Time: 12:19 PM
 */

/**
 * Gets a single table as an array with tablename as key to an array with first row as column names
 * and all other rows the contents
 * @param $tableNames name of the table
 * @param string $selector columns or just "*" (default)
 * @param string $statements    for example "WHERE id=1 or LIMIT 1
 * @return mixed
 * @throws Exception
 */
function getTables($tableNames, $selector = "*", $statements = "")
{
    $array = array();

    if (!is_array($tableNames)) {
        $tables = array();
        $tables[] = $tableNames;
        $array = getTables($tables, $selector, $statements);
    } else {
        $db = $GLOBALS['db_connection'];
        foreach ($tableNames as $tableName) {
            $req = $db->prepare("SELECT $selector FROM $tableName $statements");
            $preResult = $req->execute();
            if (!$preResult) {
                throw new Exception("Database Error [{$db->errno}] {$req->error}");
            }
            $result = $req->fetchAll(PDO::FETCH_ASSOC);
            if (sizeof($result)) {
                $array[$tableName][] = array_keys($result[0]);

                foreach ($result as $r) {
                    $row = array();
                    $j = 0;
                    foreach (array_keys($result[0]) as $key) {
                        $row[$j++] = $r[$key];
                    }
                    $array[$tableName][] = $row;
                }
            }
        }
    }
    return $array;
}

/**
 * Returns the contents of a table as JSON
 * @param mixed $tableNames (or a single name as string)
 * @param string $selector
 * @param string $statements
 * @return string   JSON
 * @throws Exception
 */
function getTablesAsJSON($tableNames, $selector = "*", $statements = "") {
    return json_encode(getTables($tableNames, $selector, $statements));
}

/**
 * INSERT wrapper
 * @param $tables
 * @return array
 */
function insertTables($tables) {
    $db = $GLOBALS['db_connection'];
    $idmap = array();

    // TODO: BEGIN TRANSACTION

    foreach ($tables as $tableName => $rows) {
        // PDO binding
        $sql = "INSERT INTO $tableName(".implode(',', $rows[0]).") VALUES(:".implode(',:', $rows[0]).")";

        $query = $db->prepare($sql);
        foreach (array_splice($rows,1) as $row) {

            for ($i = 0; $i < count($rows[0]); $i++) {
                if (!$query->bindValue($tables[$tableName][0][$i], $row[$i])) {
                    error_log("insertTables(): Unable to bind $tableName parameter $i\"$rows[0][$i]\"=\"$row[$i]\"".PHP_EOL);
                }
            }

            try {
                if ($query->execute()) {
                    $idmap[$tableName."_id"] = $row[array_search('id', $tables[$tableName][0])];
                }
            } catch (PDOException $e) {
                header(
                    $_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error',
                    true,
                    500
                );
                error_log("500: insertTables():".$e->getMessage()." on \"$sql\"".PHP_EOL);
                die($e->getMessage());
            }
        }
    }
    // TODO: END TRANSACTION
    return $idmap;
}

/**
 * INSERT wrapper
 * @param $tables
 * @return bool
 */
function updateTables($tables) {
    $db = $GLOBALS['db_connection'];

    // TODO: BEGIN TRANSACTION
    foreach ($tables as $tableName => $rows) {
        // PDO binding


        $parts = array();
        foreach ($rows[0] as $name) {
            array_push($parts, "$name = :$name");
        }
        $sql = "UPDATE $tableName SET ".implode(', ', $parts).";";

        $query = $db->prepare($sql);
        foreach (array_splice($rows,1) as $row) {

            for ($i = 0; $i < count($rows[0]); $i++) {
                if (!$query->bindValue($tables[$tableName][0][$i], $row[$i])) {
                    error_log("updateTables(): Unable to bind $tableName parameter $i\"$rows[0][$i]\"=\"$row[$i]\"".PHP_EOL);
                }
            }

            try {
                $query->execute();
            } catch (PDOException $e) {
                // This will be silent so we can still try to update configs
/*                header(
                    $_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error',
                    true,
                    500
                );*/
                error_log("500: updateTables():".$e->getMessage()." on \"$sql\"".PHP_EOL);
                die($e->getMessage());
            }
        }
    }
    // TODO: END TRANSACTION
    return true;
}