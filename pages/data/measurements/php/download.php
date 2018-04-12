<?php
session_start();
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__.'/globalsettings.php');
require_once('measurements.php');
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$measurements = New Measurements();
$measurements->__set('boatName', 'aspire');
$measurements->prepare();

$sqlResult = $measurements->getSensorLogData(0, 10);
$spreadsheet = new Spreadsheet();
$spreadsheet->getActiveSheet()->fromArray(array_keys($sqlResult[0]), NULL, 'A1');
$spreadsheet->getActiveSheet()->fromArray($sqlResult, NULL, 'A2');
#$test = array_keys($sqlResult[0];
#print_r($test);

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
