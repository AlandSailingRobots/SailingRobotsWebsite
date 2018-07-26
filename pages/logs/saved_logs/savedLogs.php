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


$databaseConnection = DatabaseConnectionFactory::getDatabaseConnection("ASPire");
$logs = new Logs($databaseConnection);
$prefix = 'dataLogs_';

header('Content-Type: application/json');
$tblList = $logs->getTableNamesAsJSONByPrefix($prefix);
echo $tblList;
