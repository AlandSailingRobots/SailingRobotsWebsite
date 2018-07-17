<?php
//$options = array('location' => 'http://www.sailingrobots.com/testdata/live/dbconnection.php', 'uri' => 'http://localhost/');
$options = array('location' => $GLOBALS['server'].'/pages/logs/live/DB_Connection.php', 'uri' => 'http://localhost/');
//create an instante of the SOAPClient (the API will be available)
$service = new SoapClient(null, $options);
//call an API method
switch ($_REQUEST['action']) {
    case 'idcheck':
        $id = $service->getLatestID();
        //$data = $service->getLatestData(end($id));
        echo json_encode($id);
        break;
    case 'getGpsData':
        $dataGps = $service->getLatestData("gps_dataLogs", "id_gps");
        echo json_encode($dataGps);
        break;
    case 'getCourseCalculationData':
        $dataCourse = $service->getLatestData("course_calculation_dataLogs", "id_course_calculation");
        echo json_encode($dataCourse);
        break;
    case 'getWindSensorData':
        $dataWindSensor = $service->getLatestData("windsensor_dataLogs", "id_windsensor");
        echo json_encode($dataWindSensor);
        break;
    case 'getSystemData':
        $dataSystem = $service->getLatestData("system_dataLogs", "id_system");
        echo json_encode($dataSystem);
        break;
    case 'getCompassData':
        $dataCompass = $service->getLatestData("compass_dataLogs", "id_compass_model");
        echo json_encode($dataCompass);
        break;
    case 'getWaypoints':
        $waypoints = $service->getWaypoints();
        echo json_encode($waypoints);
        break;
    default:
        echo "!!! CONNY W T F !!!";
        break;
}