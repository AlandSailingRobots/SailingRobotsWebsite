<?php
session_start();
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__.'/globalsettings.php');
require_once('measurements.php');
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
ini_set('memory_limit', '-1');

Class ExportSpreadsheet {

  public function __construct() {
    $this->measurements = New Measurements();
    $this->measurements->__set('boatName', 'aspire');
    $this->measurements->prepare();
    $this->init();
  }

  public function init() {
    $xlsxMaxRows = 1048576;
    $sqlResult = $this->measurements->getSensorLogData(0, $xlsxMaxRows);
    $this->fileName = $this->measurements->__get('boatName');

    $this->spreadsheet = new Spreadsheet();
    $this->spreadsheet->getActiveSheet()->fromArray(array_keys($sqlResult[0]), NULL, 'A1');
    $this->spreadsheet->getActiveSheet()->fromArray($sqlResult, NULL, 'A2');
  }

  /** TODO set column width and meta data **/
  public function outputXLSX() {
    $fileName = $this->fileName;
    $fileName .= '.xlsx';
    header('Content-Disposition: attachment; filename="'.$fileName.'"');
    $writer = new Xlsx($this->spreadsheet);

    $writer->save('php://output');
  }

  public function outputCSV() {
    $fileName = $this->fileName;
    $fileName .= '.csv';
    header('Content-Disposition: attachment; filename="'.$fileName.'"');
    $writer = new Csv($this->spreadsheet);

    $writer->save('php://output');
  }

  public function run() {
    if(isset($_GET['type'])) {
      $fileType = $_GET['type'];
      switch($fileType ) {
        case "xlsx":
          $this->outputXLSX();
          break;
        case "csv":
          $this->outputCSV();
          break;

        default:
          echo 'Please set file type -> download.php?type=EXTENSION';
      }
    }
  }
}

$exportspreadsheet = new ExportSpreadsheet();
$exportspreadsheet->run();

?>
