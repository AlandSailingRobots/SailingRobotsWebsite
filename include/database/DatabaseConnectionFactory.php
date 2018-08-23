<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 7/26/18
 * Time: 1:24 PM
 */
require_once(__ROOT__.'/globalsettings.php');
require_once ('AbstractDatabaseConnectionFactory.php');

class DatabaseConnectionFactory extends AbstractDatabaseConnectionFactory {

    /**
     * DatabaseConnectionFactory constructor.
     */
    private function __construct() {}

    /**
     * @param string $boatName
     * @return DatabaseConnection
     */
    static function getDatabaseConnection (string $boatName = null): DatabaseConnection {
        $host     =  $GLOBALS['hostname'];
        $port     = "3306";
        $db       = self::getDb($boatName);
        $usr      = $GLOBALS['username'];
        $pwd      = $GLOBALS['password'];
        $charset  = 'utf8mb4';
        $opt = [PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $config = new DatabaseConfiguration($host, $port, $db, $usr, $pwd, $charset, $opt);
        $connection = new DatabaseConnection($config);

        return $connection;
    }

    /**
     * @param string $boatName
     * @return string
     */
    private static function getDb(string $boatName) {
        $checkBoatName = strtolower($boatName);

        switch ($checkBoatName) {
            case "aspire":
                return $GLOBALS['database_ASPire'];
            case "janet":
                return $GLOBALS['database_name_testdata'];
            default:
                return $GLOBALS['database_ASPire'];
        }
    }
}