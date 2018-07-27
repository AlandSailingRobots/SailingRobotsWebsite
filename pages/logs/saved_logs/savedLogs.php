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

$listOfAbbreviation = array(
    "GPS" => "gps",
);



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
        $displayName =  preg_replace($patterns, $replacements, $tableName[0]);
        if (in_array($displayName, $listOfAbbreviation)) {
            $displayName = strtoupper($displayName);
        } else {
            $displayName = ucwords($displayName);
        }
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

//header('Content-Type: application/json');
$jsonResponse = $logs->getTableNamesAsJSONByPrefix($prefix);
$array = json_decode($jsonResponse, true);
reset($array);
$firstKey = key($array);
//echo print_r($array[$firstKey]);
//json_decode($tblList);
//echo print_r(json_decode($tblList, true));
//echo current(array_keys($tblList, true));
//echo $tblList;

$dtList = buildList($jsonResponse, "aspire");
//include 'tpl/datatables.tpl';
include 'tpl/datatableList.tpl';



