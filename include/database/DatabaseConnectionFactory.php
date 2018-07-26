<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 7/26/18
 * Time: 1:24 PM
 */
require_once ('AbstractDatabaseConnectionFactory.php');

class DatabaseConnectionFactory extends AbstractDatabaseConnectionFactory {

    /**
     * @param string $boatName
     * @return DatabaseConnection
     */
    static function getDatabaseConnection (string $boatName): DatabaseConnection {
        $host     = "localhost";
        $port     = "3306";
        $db       = "ithaax_ASPire_config";
        $usr      = "root";
        $pwd      = "";
        $charset  = 'utf8mb4';
        $opt = [PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $config;
        $connection;
        $handler;


        $config = new DatabaseConfiguration($host, $port, $db, $usr, $pwd, $charset, $opt);
        $connection = new DatabaseConnection($config);

        return $connection;
    }
}