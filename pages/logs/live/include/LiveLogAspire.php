<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2018-07-10
 * Time: 09:31
 */

define('OUT_OF_RANGE', -2000);

class LiveLogAspire
{

    /**
     * Constructor
     *
     */
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
            require_once(__ROOT__.'/globalsettings.php');
        }

    }

    /**
     * Prepare
     *
     * @return void
     **/
    public function prepare() {
        // SETUP
        $this->host     = $GLOBALS['hostname'];
        $this->db       = $GLOBALS['database_ASPire'];
        $this->usr      = $GLOBALS['username'];
        $this->pwd      = $GLOBALS['password'];
        $this->charset  = 'utf8mb4';
        $this->opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
    }

    /**
     * @return PDO Object
     */
    public function getDataSource() {
        $pdo = new PDO($this->dsn, $this->usr, $this->pwd, $this->opt);
        return $pdo;
    }

    /**
     * @param $table
     * @return array (single row)
     */
    public function getData($table) {
        $pdo = self::getDataSource();
        $tbl = self::__get("db") . '.' . $table;
        $query = "SELECT * FROM $tbl
                  ORDER BY id DESC 
                  LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $sqlResult = $stmt->fetch();

        return $sqlResult;
    }

    /**
     * @return array (all rows)
     */
    public function getMissionWaypoints() {
        $pdo = self::getDataSource();
        $query = 'SELECT * FROM ithaax_ASPire_config.currentMission';
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $sqlResult = $stmt->fetchAll();

        return $sqlResult;
    }

    /**
     * For now we use latest known position
     * @return array (single row)
     */
    public function getPosition() {
        return self::getLatestKnownPosition();
    }

    /**
     * Return latest known gps_id that is not out of range
     * @return array (single row)
     */
    public function getLatestKnownPosition() {
        $outOfRange = OUT_OF_RANGE; //because constant cannot be passed as reference into bindParam()
        $pdo = self::getDataSource();
        $gps = 'ithaax_ASPire_config.' . 'dataLogs_gps';
        $query = "SELECT * FROM $gps
                  WHERE latitude AND longitude != :outOfRange
                  ORDER BY id DESC
                  limit 1";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':outOfRange', $outOfRange, PDO::PARAM_INT);
        $stmt->execute();
        $sqlResult = $stmt->fetch();

        return $sqlResult;

    }

    /**
     * Magic function
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * Magic function
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }
}