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
