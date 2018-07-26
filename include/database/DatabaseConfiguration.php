<?php
/**
 * Created by PhpStorm.
 * User: sailbot
 * Date: 7/23/18
 * Time: 2:32 PM
 */



class DatabaseConfiguration
{
    private $host;
    private $port;
    private $db;
    private $usr;
    private $pwd;
    private $charset;
    private $opt;

    /**
     * DatabaseConfiguration constructor.
     * @param string $host
     * @param int $port
     * @param string $db
     * @param string $usr
     * @param string $pwd
     * @param string $charset
     * @param array $opt
     */
    public function __construct (string $host, int $port = 3306, string $db, string $usr, string $pwd,
                                 string $charset = 'utf8mb4',
                                 array  $opt = [PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                                                PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
                                                PDO::ATTR_EMULATE_PREPARES      => false,]) {

        $this->host = $host;
        $this->port = $port;
        $this->db = $db;
        $this->usr = $usr;
        $this->pwd = $pwd;
        $this->charset = $charset;
        $this->opt = $opt;
    }

    /**
     * @return string
     */
    public function getHost (): string {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort (): int {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getDbName (): string {
        return $this->db;
    }

    /**
     * @return string
     */
    public function getUsr (): string {
        return $this->usr;
    }

    /**
     * @return string
     */
    public function getPwd (): string {
        return $this->pwd;
    }

    /**
     * @return string
     */
    public function getCharset (): string {
        return $this->charset;
    }

    /**
     * @return array
     */
    public function getOpt (): array {
        return $this->opt;
    }
}