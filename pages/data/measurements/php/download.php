<?php
session_start();
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once __ROOT__.'/globalsettings.php';
require_once 'measurements.php';
require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

ini_set('memory_limit', '-1'); #fulhack
ini_set('max_execution_time', 300); //prevent timeout

class ExportSpreadsheet
{

    public function __construct()
    {
        $this->measurements = new Measurements();
        $this->measurements->__set('boatName', 'aspire');
        $this->measurements->prepare();
        $this->init();
    }

    public function init()
    {
      #SETUP
        $xlsxMaxRows = 1048576;
        $sqlResult = $this->measurements->getSensorLogData(0, $xlsxMaxRows);
        $this->fileName = $this->measurements->__get('boatName');

      #CREATE SPREADSHEET
        $this->spreadsheet = new Spreadsheet();
        $this->spreadsheet->getActiveSheet()->setTitle('SensorLogData');
        $this->authorProperties();

      #ADD COLUMN HEADERS
        $this->spreadsheet->getActiveSheet()->fromArray(array_keys($sqlResult[0]), null, 'A1');

      #FILL CELLS
        $this->spreadsheet->getActiveSheet()->fromArray($sqlResult, null, 'A2');

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

    public function authorProperties()
    {
        $boatName = $this->measurements->__get('boatName');

        $this->spreadsheet->getProperties()
        ->setCreator("Åland Sailing Robots")
        ->setLastModifiedBy("Åland Sailing Robots")
        ->setTitle("$boatName Sensor Log Data")
        ->setSubject("Sensor Log Data")
        ->setDescription("Sensor Log Data document for $boatName by Åland Sailing Robots, generated using PhpSpreadsheet.")
        ->setKeywords("sailing robot")
        ->setCategory("sensor log data");
    }

    public function outputXLSX()
    {
        $fileName = $this->fileName;
        $fileName .= '.xlsx';
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        $writer = new Xlsx($this->spreadsheet);

        $writer->save('php://output');
    }

    public function outputODS()
    {
        $fileName = $this->fileName;
        $fileName .= '.ods';
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        $writer = new Ods($this->spreadsheet);

        $writer->save('php://output');
    }

    public function outputCSV()
    {
        $fileName = $this->fileName;
        $fileName .= '.csv';
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        $writer = new Csv($this->spreadsheet);

        $writer->save('php://output');
    }

    public function run()
    {
        if (isset($_GET['type'])) {
            $fileType = $_GET['type'];
            switch ($fileType) {
                case "xlsx":
                    $this->outputXLSX();
                    break;
                case "csv":
                    $this->outputCSV();
                    break;

                case "ods":
                    $this->outputODS();
                    break;

                default:
                    echo 'Please set file type -> download.php?type=EXTENSION';
            }
        }
    }
}

$exportspreadsheet = new ExportSpreadsheet();
$exportspreadsheet->run();
