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

        $this->fetch_table
            = '(SELECT * FROM ithaax_ASPire_config.dataLogs_system) AS mission_dataLogs'
            .' JOIN ithaax_ASPire_config.dataLogs_marine_sensors'
            .' ON mission_dataLogs.marine_sensors_id = dataLogs_marine_sensors.id'
            .' JOIN ithaax_ASPire_config.dataLogs_gps'
            .' ON mission_dataLogs.gps_id = dataLogs_gps.id'
            .' JOIN ithaax_ASPire_config.currentMission'
            .' ON mission_dataLogs.current_mission_id = currentMission.id'
            .' JOIN ithaax_mission.mission'
            .' ON ithaax_ASPire_config.currentMission.id_mission = mission.id';



    }

    /**
     * @return PDO Object
     */
    public function getDataSource() {
        $pdo = new PDO($this->dsn, $this->usr, $this->pwd, $this->opt);
        return $pdo;
    }

    /**
     * Resolution for output data
     *
     * @param $milliseconds
     */
    public function resolution($milliseconds) {

    }

    public function getMissionWaypoints() {
        $pdo = self::getDataSource();
        $query = 'SELECT * FROM ithaax_ASPire_config.currentMission';
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $sqlResult = $stmt->fetchAll();

        return $sqlResult;
    }

    /**
     * @return array
     */
    public function getPosition() {
        return self::getLatestKnownPosition();
    }

    /**
     * Return latest known gps_id that is not out of range
     * @return array
     */
    public function getLatestKnownPosition() {
        $outOfRange = OUT_OF_RANGE; //because constant cannot be passed as reference into bindParam()
        $pdo = self::getDataSource();
        $query = 'SELECT * FROM ithaax_ASPire_config.dataLogs_gps
                  WHERE latitude AND longitude != :outOfRange
                  ORDER BY id DESC
                  limit 1';

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':outOfRange', $outOfRange, PDO::PARAM_INT);
        $stmt->execute();
        $sqlResult = $stmt->fetchAll();

        return $sqlResult[0];

    }

    /**
     * @return array
     */
    public function getCompassData() {
        $pdo = self::getDataSource();
        $query = 'SELECT * FROM ithaax_ASPire_config.dataLogs_compass
                  ORDER BY id DESC
                  LIMIT 1';
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $sqlResult = $stmt->fetchAll();

        return $sqlResult[0];
    }

    /**
     * @return array
     */
    public function getWindsensorData() {
        $pdo = self::getDataSource();
        $query = 'SELECT * FROM ithaax_ASPire_config.dataLogs_windsensor
                  ORDER BY id DESC
                  LIMIT 1';
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $sqlResult = $stmt->fetchAll();

        return $sqlResult[0];
    }

    public function getCourseData() {
        $pdo = self::getDataSource();
        $query = 'SELECT * FROM ithaax_ASPire_config.dataLogs_course_calculation
                  ORDER BY id DESC
                  LIMIT 1';
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $sqlResult = $stmt->fetchAll();

        return $sqlResult[0];
    }

    /**
    public function getNextWaypointPos() {

        $nextWayPointPos;
        for (var wp of waypoints){
            if (!wp.harvested){
                return new google.maps.LatLng(wp.lat, wp.lng)
                    }
        }
                return $nextWayPointPos;
            }
    **/

    public function getLatestData($table) {
        $pdo = self::getDataSource();
        $query = 'SELECT *
				  FROM $table
				  ORDER BY id
				  DESC LIMIT 1';

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':outOfRange', $outOfRange, PDO::PARAM_INT);
        $stmt->execute();
        $sqlResult = $stmt->fetchAll();

        return $sqlResult;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}