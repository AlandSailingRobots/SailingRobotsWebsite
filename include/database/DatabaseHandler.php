<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 7/24/18
 * Time: 4:05 PM
 */

class DatabaseHandler {
    private $pdo = null;
    private $stmt = null;
    /**
     * DatabaseHandler constructor.
     * @param DatabaseConnection $databaseConnection
     */
    public function __construct (DatabaseConnection $databaseConnection) {
        $dsn = $databaseConnection->getDsn();
        $usr = $databaseConnection->getUsername();
        $pwd = $databaseConnection->getPassword();
        $opt = $databaseConnection->getOptions();
        try {
            $this->pdo = new PDO($dsn, $usr, $pwd, $opt);
        } catch (PDOException $e) {
            print $e->getMessage();
        }
    }
}