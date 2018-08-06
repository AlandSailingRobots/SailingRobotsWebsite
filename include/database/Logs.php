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

    /**
     * @param string $table
     * @return array
     */
    public function getColumnNamesByTableName (string $table): array {
        $selector   = '*';
        $tableName  = 'INFORMATION_SCHEMA.COLUMNS';
        $statements = "where table_name = '$table'";
        try {
            $result =  $this->getTables($tableName, $selector, $statements);

            $needle = 'COLUMN_NAME';
            $haystack = $result['INFORMATION_SCHEMA.COLUMNS'][0];
            $columnNameIndex  = array_search($needle, $haystack);

            //we can now remove the first array and get the column names
            array_shift($result['INFORMATION_SCHEMA.COLUMNS']);

            $columnNames = array(
                "$table" => array(
                    array()
                )
            );
            foreach ($result['INFORMATION_SCHEMA.COLUMNS'] as $columnInfo) {
                array_push($columnNames["$table"][0], $columnInfo[$columnNameIndex]);
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