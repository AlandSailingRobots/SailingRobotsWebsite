<?php

require_once '../globalsettings.php';
require_once 'php/is_pwd_correct.php';

/*
 *  This folder is used for the httpsync and it has no "interface" so you cant go to url/sync.
 *  The location's that have something like http://localhost/ is used to test the sync locally and the
 *  location's that have something like http://www.sailingrobots.com/ is used to test the sync on the website.
 */

if (!empty($_POST)) {
    $connected = false;
    //echo 'POST array: '."\n";
    //print_r($_POST);
    echo "\n";

    if (isset($_POST['gen']) && $_POST['gen'] == 'aspire') {
        // ASPire connection to the website
        if (isset($_POST['id']) && isset($_POST['pwd'])) {
            if (is_pwd_correct($_POST['pwd'])) {
                $hostname  = $GLOBALS['hostname'];
                $username  = $GLOBALS['username'];
                $password  = $GLOBALS['password'];
                $dbname    = $GLOBALS['database_ASPire'];
                try {
                    $db = new PDO(
                        "mysql:host=$hostname;dbname=$dbname;charset=utf8;port=3306",
                        $username,
                        $password,
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                    );
                } catch (Exception $e) {
                    header("HTTP/1.1 500 Internal Server Error");
                    die('Error : '.$e->getMessage());
                }
                $GLOBALS['db_connection'] = $db;

                include_once 'aspire/pushDataLogs.php';
                include_once 'aspire/getConfigs.php';
                include_once 'aspire/pushConfigs.php';
                include_once 'aspire/pushWaypoints.php';
                include_once 'aspire/getWaypoints.php';

                try {
                    switch ($_POST["serv"]) {
                    case "checkIfNewConfigs":
                        echo checkIfNewConfigs();
                        break;
                    case "checkIfNewWaypoints":
                        echo checkIfNewWaypoints();
                        break;
                    case "setConfigsUpdated":
                        print_r(setConfigsUpdated());
                        break;
                    case "getAllConfigs":
                        print_r(getAllConfigs($_POST["id"]));
                        break;
                    case "getWaypoints":
                        print_r(getWaypoints());
                        break;
                    case "pushConfigs":
                        print_r(pushConfigs($_POST["data"]));
                        break;
                    case "pushWaypoints":
                        print_r(pushWaypoint($_POST["data"]));
                        break;
                    case "pushAllLogs":
                        print_r(pushAllLogs($_POST["id"], $_POST["data"]));
                        break;
                    default:
                        break;
                    }
                } catch (Exception $e) {
                    print_r(
                        "ERROR: (exception thrown in sync/index.php): "
                        .$e->getMessage()
                    );
                }

                //$connected = true;
            } else {
                //echo "ERROR: Wrong Password ! \n";
                header("HTTP/1.1 401 Unauthorized");
                exit;
            }
        } else {
            //echo 'ERROR: Missing fild : "id" and/or "pwd"';
            header("HTTP/1.1 400 Bad Request");
            exit;
        }
    } elseif (isset($_POST['gen']) && $_POST['gen'] == 'janet') {
        // Janet connection
        // Much less secure
        if (isset($_POST['id']) && isset($_POST['pwd'])) {
            $optionsPushlogs      = array('location' => $GLOBALS['server'].'sync/janet/pushDatalogs.php',  'uri' => 'http://localhost/');
            $optionsGetConfigs    = array('location' => $GLOBALS['server'].'sync/janet/getConfigs.php',    'uri' => 'http://localhost/');
            $optionsPushConfigs   = array('location' => $GLOBALS['server'].'sync/janet/pushConfigs.php',   'uri' => 'http://localhost/');
            $optionsPushwaypoints = array('location' => $GLOBALS['server'].'sync/janet/pushWaypoints.php', 'uri' => 'http://localhost/');
            $optionsGetWaypoints  = array('location' => $GLOBALS['server'].'sync/janet/getWaypoints.php',  'uri' => 'http://localhost/');

            $connected = true;
        } else {
            echo ' ERROR: Missing fild : "id" and/or "pwd"';
        }
    } else {
        echo ' ERROR: "gen" field "aspire" or "janet" missing !';
    }

    // Janet connection
    if ($connected) {
        //create an instante of the SOAPClient (the API will be available)
        $pushLogsService     = new SoapClient(null, $optionsPushlogs);
        $getConfigsService   = new SoapClient(null, $optionsGetConfigs);
        $pushConfigsService  = new SoapClient(null, $optionsPushConfigs);
        $pushPushWaypoints   = new SoapClient(null, $optionsPushwaypoints);
        $getWaypointsService = new SoapClient(null, $optionsGetWaypoints);



        if (isset($_POST["serv"])) {
            try {
                switch ($_POST["serv"]) {
                case "_checkIfNewConfigs":
                    echo $getConfigsService->checkIfNewConfigs();
                    break;
                case "_checkIfNewWaypoints":
                    echo $getWaypointsService->checkIfNewWaypoints();
                    break;
                case "setConfigsUpdated":
                    print_r($getConfigsService->setConfigsUpdated());
                    break;
                case "getAllConfigs":
                    print_r($getConfigsService->getAllConfigs($_POST["id"]));
                    break;
                case "getWaypoints":
                    print_r($getWaypointsService->getWaypoints());
                    break;
                case "pushConfigs":
                    print_r($pushConfigsService->pushConfigs($_POST["data"]));
                    break;
                case "pushWaypoints":
                    print_r($pushPushWaypoints->pushWaypoint($_POST["data"]));
                    break;
                case "pushAllLogs":
                    //print_r($pushLogsService);
                    //print_r($pushLogsService->helloWorld());
                    print_r(pushAllLogs($_POST["id"], $_POST["data"]));
                    break;
                default:
                    break;
                }
            } catch (Exception $e) {
                print_r("ERROR: (exception thrown in sync/index.php): ".$e->getMessage());
            }
        } else {
            echo 'ERROR: "serv" field is empty';
        }
    }
} else {
    // echo "This folder is used for the httpsync and it has no \"interface\".";
    header('Location: ./..'); // Go back to the home page
}
