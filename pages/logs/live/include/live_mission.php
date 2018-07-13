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
                $gps = $live->getCompassData();
                echo json_encode($gps);
                break;
            case 'getCourseData':
                $course = $live->getCourseData();
                echo json_encode($course);
                break;
            case 'getWindsensorData':
                $wind = $live->getWindsensorData();
                echo json_encode($wind);
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