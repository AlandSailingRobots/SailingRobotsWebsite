<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 7/30/18
 * Time: 11:25 AM
 */

define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once(__ROOT__.'/globalsettings.php');
require_once(__ROOT__.'/include/database/DatabaseConnectionFactory.php');
require_once(__ROOT__.'/include/database/Logs.php');


class dtConnection {
    private $sql_details, $table, $primaryKey, $columns;
    private $joinQuery = NULL;
    private $extraWhere = '';
    private $groupBy = '';
    private $having = '';

    public function __construct (DatabaseConnection $databaseConnection) {
        // SQL server connection information
        $this->sql_details = array(
            'user' => $databaseConnection->getUser(),
            'pass' => $databaseConnection->getPassword(),
            'db'   => $databaseConnection->getDbName(),
            'host' => $databaseConnection->getHostname()
        );
    }

    /**
     * @param $table
     * @param $primaryKey
     * @param $columns
     */
    public function setup ($table, $primaryKey, $columns): void{
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->columns = self::prepareColumns($columns);
    }


    public function run (): void {
        //require( 'ssp.class.php' );


        $joinQuery = "FROM `user` AS `u` JOIN `user_details` AS `ud` ON (`ud`.`user_id` = `u`.`id`)";
        $extraWhere = "`u`.`salary` >= 90000";
        $groupBy = "`u`.`office`";
        $having = "`u`.`salary` >= 140000";

        require('ssp.customized.class.php' );

        echo json_encode(
            //SSP::simple( $_GET, $this->sql_details, $this->table, $this->primaryKey, $this->columns )
            SSP::simple( $_GET, $this->sql_details, $this->table, $this->primaryKey, $this->columns,
                $this->joinQuery, $this->extraWhere, $this->groupBy, $this->having )
        );
    }

    /**
     * Array of database columns which should be read and sent back to DataTables.
     * The `db` parameter represents the column name in the database, while the `dt`
     * parameter represents the DataTables column identifier. In this case simple
     * indexes
     *
     * @param $columns
     * @return array
     *
     */
    private function prepareColumns ($columns) :array {

        $result = array();
        reset($columns);
        $firstKey = key($columns);

        foreach ($columns[$firstKey][0] as $key=>$value) {

            $tmp = array( 'db' => $value, 'dt' => $key, 'field' => $value);
            array_push($result, $tmp);


        }


        return $result;
    }
}

$databaseConnection = DatabaseConnectionFactory::getDatabaseConnection("ASPire");
$logs = new Logs($databaseConnection);
$dtc = new dtConnection($databaseConnection);

$table = 'dataLogs_gps';
$primaryKey = 'id';

$selector   = '*';
$tableName  = 'dataLogs_gps';
$statements = 'LIMIT 1';
$columns = $logs->getTables($tableName, $selector, $statements);

//header('Content-Type: application/json');
$dtc->setup($table, $primaryKey, $columns);
$dtc->run();

