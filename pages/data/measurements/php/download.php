<?php
session_start();
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__.'/globalsettings.php');
require_once('measurements.php');
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
ini_set('memory_limit', '-1'); #fulhack

Class ExportSpreadsheet {

  public function __construct() {
    $this->measurements = New Measurements();
    $this->measurements->__set('boatName', 'aspire');
    $this->measurements->prepare();
    $this->init();
  }

  public function init() {
    #SETUP
    $xlsxMaxRows = 1048576;
    $sqlResult = $this->measurements->getSensorLogData(0, $xlsxMaxRows);
    $this->fileName = $this->measurements->__get('boatName');

    #CREATE SPREADSHEET
    $this->spreadsheet = new Spreadsheet();

    #ADD COLUMN HEADERS
    $this->spreadsheet->getActiveSheet()->fromArray(array_keys($sqlResult[0]), NULL, 'A1');

    #SET WIDTH FROM COLUMNS


    #FILL CELLS
    $this->spreadsheet->getActiveSheet()->fromArray($sqlResult, NULL, 'A2');

    #STYLING
    /** bold headers **/
    $this->spreadsheet->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);

    /** column width **/
    $highestColumn = $this->spreadsheet->getActiveSheet()->getHighestColumn(); // e.g 'F'
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
      $colName = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
      $this->spreadsheet->getActiveSheet()->getColumnDimension($colName)->setAutoSize(true);
    }

  }

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
