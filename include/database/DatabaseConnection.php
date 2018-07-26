<?php
/**
 * Created by PhpStorm.
 * User: sailbot
 * Date: 7/23/18
 * Time: 2:33 PM
 */



class DatabaseConnection
{
    private $databaseConfiguration;

    /**
     * DatabaseConnection constructor.
     * @param DatabaseConfiguration $configuration
     */
    public function __construct (DatabaseConfiguration $configuration) {
        $this->databaseConfiguration = $configuration;
    }

    /**
     * @return string
     */
    public function getDsn (): string {
        return sprintf('mysql:host=%s;dbname=%s;charset=%s;port=%s',
            $this->databaseConfiguration->getHost(),
            $this->databaseConfiguration->getDbName(),
            $this->databaseConfiguration->getCharset(),
            $this->databaseConfiguration->getPort()
            );
    }

    public function getDbName (): string {
        return $this->databaseConfiguration->getDbName();
    }
    /**
     * @return string
     */
    public function getUser (): string {
        return $this->databaseConfiguration->getUsr();
    }

    /**
     * @return string
     */
    public function getPassword (): string {
        return $this->databaseConfiguration->getPwd();
    }

    /**
     * @return array
     */
    public function getOptions (): array {
        return $this->databaseConfiguration->getOpt();
    }
}