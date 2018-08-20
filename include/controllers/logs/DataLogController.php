<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 8/8/18
 * Time: 9:00 AM
 */
require_once ('AbstractLogController.php');
require_once (__ROOT__ . '/include/database/datatables/DataTablesRepository.php');
require_once (__ROOT__.'/include/view/DataLogView.php');

class DataLogController extends AbstractLogController {
    private $request;

    private $logs;
    private $dtc;

    private $view;

    public function __construct($request = null) {
        $this->request = $request;

        if ($request) {
            $databaseConnection = DatabaseConnectionFactory::getDatabaseConnection($request['boat']);
            $this->logs = new Logs($databaseConnection);
            if (isset($request['dt'])) {
                $this->dtc = new DataTablesRepository($databaseConnection);
            }
        }
    }

    public function run () : void {

        $this->getData();
    }

    public function getData() {

        $request = self::getRequest();

        $view['pageTitle'] = null;
        $view['dtList'] = null;
        $view['dtHeaders'] = null;

        //$page_title = null;
        //$dtHeaders = null;
        //$dtList = null;

        //$databaseConnection = DatabaseConnectionFactory::getDatabaseConnection($request['boat']);
        //$logs = new Logs($databaseConnection);


        //headers as JSON response
        if ( isset($request['dtHeaders']) ) {
            $table = $request['dtHeaders'];
            $columnNamesAsJSON = $this->logs->getColumnNamesByTableNameAsJSON($table);
            header('Content-Type: application/json');
            echo $columnNamesAsJSON;
        }

        if ( isset($request['dt']) && isset($request['data']) ) {
          /**
            echo '<pre>';
            echo 'run () ';
            print_r( $_GET );
            echo '</pre>';
           * */

            $table = $request['data'];
            $primaryKey = 'id';
            $columns = $this->logs->getColumnNamesByTableName($table);
            //$dtc = new DataTablesRepository($databaseConnection);
            $this->dtc->setup($table, $primaryKey, $columns);
            $this->dtc->run();
        }

        if ( !isset($request['dt']) && isset($request['boat']) ){
            // GET TABLE NAMES
            $prefix = 'dataLogs_';

            //$prefix = array();
            //preg_match("/(^.*)(_)/", $request['data'], $prefix);
            $tableNamesAsJSON = $this->logs->getTableNamesAsJSONByPrefix($prefix);
            //header('Content-Type: application/json');
            $view['dtList'] = DataLogView::buildList($tableNamesAsJSON, $request['boat']);
            //$dtList = DataLogView::buildList($tableNamesAsJSON, $request['boat']);


            if ( isset($request['data'])) {

                $view['pageTitle'] = $request['data'];

                // GET TABLE COLUMNS
                $columnNamesAsJSON = $this->logs->getColumnNamesByTableNameAsJSON($request['data']);
                $view['dtHeaders'] = DataLogView::buildHeaders($columnNamesAsJSON);
                //$dtHeaders = DataLogView::buildHeaders($columnNamesAsJSON);
            }
            $relative_path = __ROOT__ . '/';
            //include (__ROOT__.'/pages/logs/saved_logs/tpl/savedLogsBody.tpl');
            //include 'tpl/savedLogsBody.tpl';

            $this->view = $view;
        }

    }

    /**
     * @return array|null
     */
    public function getRequest () : ?array {
        return $this->request;
    }

    /**
    * @return mixed
    */
    public function getView()
    {
        return $this->view;
    }
}
