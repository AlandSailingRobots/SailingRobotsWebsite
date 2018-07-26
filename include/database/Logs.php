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
}