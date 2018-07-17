<?PHP
#require('DB_Connection.php');
require('LiveLogAspire.php');

Class jsonResponse {

    public function __construct()
    {
        header('Content-Type: application/json');
    }

    public function run() {
        if(isset($_GET)) {
            self::getRequest();
        }
    }

    public function getRequest() {
        $live = new LiveLogAspire();
        $live->prepare();

        $request = $_GET['data'];
        switch ($request) {
            case 'getGpsData':
                $gps = $live->getPosition();
                echo json_encode($gps);
                break;
            case 'getMissionWaypoints':
                $mission = $live->getMissionWaypoints();
                echo json_encode($mission);
                break;
            case 'getCompassData':
                $gps = $live->getData("dataLogs_compass");
                echo json_encode($gps);
                break;
            case 'getCourseData':
                $course = $live->getData("dataLogs_course_calculation");
                echo json_encode($course);
                break;
            case 'getWindSensorData':
                $wind = $live->getData("dataLogs_windsensor");
                echo json_encode($wind);
                break;
            case 'getMarineSensorData':
                $sensor = $live->getData("dataLogs_marine_sensors");
                echo json_encode($sensor);
                break;
            case 'getCurrentSensorData':
                $sensor = $live->getData("dataLogs_current_sensors");
                echo json_encode($sensor);
                break;
            default:
                echo 'SOME ERROR';
                //$gps = $live->getPosition();
                //echo json_encode($gps);
                break;
        }
    }
}

$json = new jsonResponse();
$json->run();