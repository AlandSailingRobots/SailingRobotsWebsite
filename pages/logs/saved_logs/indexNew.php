<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 7/26/18
 * Time: 12:59 PM
 */


session_start();
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once(__ROOT__.'/globalsettings.php');
$relative_path = './../../../';


require_once(__ROOT__.'/include/database/DatabaseConnectionFactory.php');
require_once(__ROOT__.'/include/database/Logs.php');



/**
 * @param array $patterns
 * @param array $replacements
 * @param array $subject
 * @param array $abbreviations
 * @return null|string|string[]
 */
function pregReplace(array $patterns, array $replacements, $subject, array $abbreviations) {
    $displayName =  preg_replace($patterns, $replacements, $subject);
    if (in_array($displayName, $abbreviations)) {
        $displayName = strtoupper($displayName);
    } else {
        $displayName = ucwords($displayName);
    }
    return $displayName;
}

function buildList (string $jsonResponse, $boatName): string {
    $tblList = "";
    $array = json_decode($jsonResponse, true);
    reset($array);
    $firstKey = key($array);

    $patterns = array();
    array_push($patterns, '/dataLogs_/');
    array_push($patterns, '/_/');

    $replacements = array();
    array_push($replacements, '');
    array_push($replacements, ' ');

    $listOfAbbreviation = array(
        "GPS" => "gps",
    );


    foreach ($array[$firstKey] as $tableName) {
        $displayName = pregReplace($patterns, $replacements, $tableName[0], $listOfAbbreviation);
        $displayName = $displayName . ' Data';


        $tblList = $tblList . '<li class="dtList"><a href="index.php?boat='.$boatName.'&data='.$tableName[0].'">'. ucwords($displayName) . '</a></li>';

    }
    return $tblList;
}


function buildHeaders (string $jsonResponse): string {
    $tblHeaders = "";
    $array = json_decode($jsonResponse, true);
    reset($array);
    $firstKey = key($array);
    foreach ($array[$firstKey][0] as $tableName) {
        $tblHeaders = $tblHeaders . "<th>" . $tableName . "</th>";

    }
    return $tblHeaders;
}

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

    $databaseConnection = DatabaseConnectionFactory::getDatabaseConnection("ASPire");
    $logs = new Logs($databaseConnection);

    // GET TABLE NAMES
    $prefix = 'dataLogs_';
    $tableNamesAsJSON = $logs->getTableNamesAsJSONByPrefix($prefix);
    //header('Content-Type: application/json');
    $dtList = buildList($tableNamesAsJSON, "aspire");

    // GET TABLE COLUMNS
    $columnNamesAsJSON = $logs->getColumnNamesByTableNameAsJSON("dataLogs_gps");
    $dtHeaders = buildHeaders($columnNamesAsJSON);

//require_once(__ROOT__.'/include/database/datatables/DataTablesRepositorypository.php');
//$dtc = new DataTablesRepository($databaseConnection);

$table = 'dataLogs_gps';
$primaryKey = 'id';

$selector   = '*';
$tableName  = 'dataLogs_gps';
$statements = 'LIMIT 1';
//$dtHeaders = $logs->getTables($tableName, $selector, $statements);

//$dtc->setup($table, $primaryKey, $columns);


include 'tpl/savedLogsBody.tpl';



