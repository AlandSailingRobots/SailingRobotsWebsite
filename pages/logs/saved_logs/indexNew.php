<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 7/26/18
 * Time: 12:59 PM
 */


session_start ();
define ('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once (__ROOT__.'/globalsettings.php');
$relative_path = './../../../';


require_once (__ROOT__.'/include/database/DatabaseConnectionFactory.php');
require_once (__ROOT__.'/include/database/Logs.php');
require_once (__ROOT__.'/include/view/DataLogView.php');



//  If we are connected
if (isset($_SESSION['id']) and isset($_SESSION['username'])) {
  // TODO
    $connected = true;
    $name = $_SESSION['username'];
} else {
    session_destroy();
    $connected = false;
    $_SESSION['username'] = 'Guest';
    $name = 'Guest';
}

if (!isset($_GET['boat'])) {
    // We force aspire by default
    $_GET['boat'] = "aspire";
}




    // GET TABLE NAMES

    //$prefix = 'dataLogs_';
    //$tableNamesAsJSON = $logs->getTableNamesAsJSONByPrefix($prefix);
    //header('Content-Type: application/json');
    //$dtList = DataLogView::buildList($tableNamesAsJSON, "aspire");

    // GET TABLE COLUMNS
    //$columnNamesAsJSON = $logs->getColumnNamesByTableNameAsJSON("dataLogs_gps");
    //$dtHeaders = DataLogView::buildHeaders($columnNamesAsJSON);

//require_once(__ROOT__.'/include/database/datatables/DataTablesRepositorypository.php');
//$dtc = new DataTablesRepository($databaseConnection);

$table = 'dataLogs_gps';
$primaryKey = 'id';

$selector   = '*';
$tableName  = 'dataLogs_gps';
$statements = 'LIMIT 1';
//$dtHeaders = $logs->getTables($tableName, $selector, $statements);

//$dtc->setup($table, $primaryKey, $columns);


if ($connected and $_SESSION['right'] == 'admin') {
    // echo '<p> You are an ' . $_SESSION['right'] . '! ';

    require_once(__ROOT__.'/include/handlers/RequestHandler.php');
    $controller = RequestHandler::handle();
    $controller = $controller->retrieveController ();
    $controller->run();

    if (!isset($_GET['dt']) && !isset($_GET['dtHeaders'])) {
        $view = $controller->getView();
        include 'tpl/savedLogsBody.tpl';
    }


} elseif ($connected) {
    $message = '<p> You don\'t have the right to view this webdata </p>';
    include 'tpl/default.tpl';
    //echo '<p> You don\'t have the right to view this webdata </p>';
} else {
    $message = '<p> You must log-in to view this data. Click <strong><a href="'.$relative_path.'pages/users/login.php">here</a></strong> to log-in. </p>';
    include 'tpl/default.tpl';
    //echo '<p> You must log-in to view this data. Click <strong><a href="'.$relative_path.'pages/users/login.php">here</a></strong> to log-in. </p>';
}









