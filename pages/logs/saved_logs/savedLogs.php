<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 7/26/18
 * Time: 1:03 PM
 */
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once(__ROOT__.'/globalsettings.php');
require_once(__ROOT__.'/include/database/DatabaseConnectionFactory.php');
require_once(__ROOT__.'/include/database/Logs.php');

$patterns = array();
array_push($patterns, '/dataLogs_/');
array_push($patterns, '/_/');

$replacements = array();
array_push($replacements, '');
array_push($replacements, ' ');

$listOfAbbreviation = array(
    "GPS" => "gps",
);


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


        $tblList = $tblList . '<li><a href="index.php?boat='.$boatName.'&data='.$tableName[0].'">'. ucwords($displayName) . '</a></li>';

    }
    return $tblList;
}


function buildHeaders (string $jsonResponse): string {
    $tblHeaders = "";
    $array = json_decode($jsonResponse, true);
    reset($array);
    $firstKey = key($array);
    foreach ($array[$firstKey] as $tableName) {
        //echo $tableName[0];
        //echo print_r ($tableName);
        $tblHeaders = $tblHeaders . "<th>" . $tableName[0] . "</th>";

    }
    return $tblHeaders;
}


$databaseConnection = DatabaseConnectionFactory::getDatabaseConnection("ASPire");
$logs = new Logs($databaseConnection);
$prefix = 'dataLogs_';
$jsonResponse = $logs->getTableNamesAsJSONByPrefix($prefix);
//header('Content-Type: application/json');

$dtList = buildList($jsonResponse, "aspire");
//include 'tpl/datatables.tpl';
include 'tpl/datatableList.tpl';



