<?php
/**
 * Created by PhpStorm.
 * User: sailbot
 * Date: 7/24/18
 * Time: 11:41 AM
 */

use PHPUnit\Framework\TestCase;
require "../../include/database/DatabaseConfiguration.php";
require "../../include/database/DatabaseConnection.php";

class DatabaseConnectionTest extends TestCase
{
    private $host     = "testHostname";
    private $port     = "3306";
    private $db       = "testDataBaseName";
    private $usr      = "testUser";
    private $pwd      = "testPassword";
    private $charset  = 'testCharset';
    private $opt = [PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    private $databaseConnection;

    public function init () {
        $this->databaseConnection = self::setupDatabaseConnection();
    }

    public function setupDatabaseConfiguration (): DatabaseConfiguration {
        $databaseConfiguration = new DatabaseConfiguration( $this->host,
                                                            $this->port,
                                                            $this->db,
                                                            $this->usr,
                                                            $this->pwd,
                                                            $this->charset,
                                                            $this->opt);
        return $databaseConfiguration;
    }

    public function setupDatabaseConnection (): DatabaseConnection {
        $config = self::setupDatabaseConfiguration();
        $connection = new DatabaseConnection($config);

        return $connection;
    }

    public function testGetPassword () {
        self::init();
        self::assertEquals("testPassword", $this->databaseConnection->getPassword());
    }

    public function testGetUsername () {
        self::init();
        self::assertEquals("testUser", $this->databaseConnection->getUsername());
    }

    public function testGetOptions () {
        self::init();
        $opt = [PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES      => false,];
        self::assertEquals($opt, $this->databaseConnection->getOptions());
    }

    public function testGetDsn () {
        self::init();
        self::assertEquals("mysql:host=testHostname;dbname=testDataBaseName;charset=testCharset;port=3306",
                                    $this->databaseConnection->getDsn());
    }
}
