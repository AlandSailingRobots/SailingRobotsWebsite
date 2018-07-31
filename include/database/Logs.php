<?php
/**
 * Created by PhpStorm.
 * User: sailbot
 * Date: 7/25/18
 * Time: 3:47 PM
 */

require_once 'DatabaseRepository.php';

class Logs extends DatabaseRepository  {

    /**
     * @param string $prefix
     * @return array
     */
    public function getTableNames (string $prefix): array {
        $selector   = 'TABLE_NAME';
        $tableName  = 'INFORMATION_SCHEMA.TABLES';
        $statements = 'WHERE TABLE_NAME LIKE "' . $prefix . '%"'
            .' AND TABLE_SCHEMA="' . $this->dbName . '";';

        try {
            $result =  $this->getTables($tableName, $selector, $statements);

            array_shift($result['INFORMATION_SCHEMA.TABLES']);
            return $result;
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }
    /**
     * @param string $prefix
     * @return string JSON
     */
    public function getTableNamesAsJSONByPrefix (string $prefix): string {
        return json_encode(self::getTableNames($prefix));
    }
//select * from information_schema.columns where table_name = 'dataLogs_gps'
//$req = $db->prepare("SELECT $selector FROM $tableName $statements");
    public function getColumnNamesByTableName (string $table): array {
        $selector   = '*';
        $tableName  = 'INFORMATION_SCHEMA.COLUMNS';
        $statements = "where table_name = '$table'";
        try {
            $result =  $this->getTables($tableName, $selector, $statements);

            $needle = 'COLUMN_NAME';
            $haystack = $result['INFORMATION_SCHEMA.COLUMNS'][0];
            $columnNameIndex  = array_search($needle, $haystack);

            //
            //$result = $result['INFORMATION_SCHEMA.COLUMNS'][$columnNameIndex];

            //we can now remove the first array and get the column names
            $columnNames = array(
                "$table" => array()
            );
            array_shift($result['INFORMATION_SCHEMA.COLUMNS']);
            foreach ($result['INFORMATION_SCHEMA.COLUMNS'] as $columnInfo) {
                array_push($columnNames["$table"], $columnInfo[$columnNameIndex]);
            }



            return $columnNames;
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function getColumnNamesByTableNameAsJSON (string $table): string {
        return json_encode(self::getColumnNamesByTableName($table));
    }
}