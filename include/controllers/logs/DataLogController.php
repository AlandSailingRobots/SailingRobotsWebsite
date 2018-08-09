<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 8/8/18
 * Time: 9:00 AM
 */
require_once ('AbstractLogController.php');
require_once (__ROOT__ . '/include/database/datatables/DataTablesRepository.php');

class DataLogController extends AbstractLogController {
    private $request;

    public function __construct($request = null) {
        $this->request = $request;
    }

    public function run () : void {
        $this->getData();
    }

    public function getData() {
        $request = self::getRequest();
        $databaseConnection = DatabaseConnectionFactory::getDatabaseConnection($request['boat']);
        $logs = new Logs($databaseConnection);
        //print_r ($_GET);
        if ($_GET['dt']) {
            //echo 'dt';
            $table = $request['data'];
            $primaryKey = 'id';
            $columns = $logs->getColumnNamesByTableName($table);
            $dtc = new DataTablesRepository($databaseConnection);
            $dtc->setup($table, $primaryKey, $columns);
            $dtc->run();
        } else {
            echo '<pre>';
            echo 'run () ';
            print_r( self::getRequest () );
            echo '</pre>';
        }

    }

    /**
     * @return array|null
     */
    public function getRequest () : ?array {
        return $this->request;
    }
}
