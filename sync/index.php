<?php
    require_once('../globalsettings.php');
    require_once('php/is_pwd_correct.php');
    /*
        This folder is used for the httpsync and it has no "interface" so you cant go to url/sync.
        The location's that have something like http://localhost/ is used to test the sync localy and the
        location's that have something like http://www.sailingrobots.com/ is used to test the sync on the website.
    */
    if (!empty($_POST))
    {
        $connected = false;

        if ( isset($_POST['gen']) && $_POST['gen'] == 'aspire' )
        {
            // ASPire connection to the website
            if ( isset($_POST['id']) && isset($_POST['pwd']) )
            {
                if ( is_pwd_correct($_POST['pwd']) )
                {
                    $optionsPushlogs      = array('location' => $GLOBALS['server'].'/sync/aspire/pushDatalogs.php',  'uri' => 'http://localhost/');
                    $optionsGetConfigs    = array('location' => $GLOBALS['server'].'/sync/aspire/getConfigs.php',    'uri' => 'http://localhost/');
                    $optionsPushConfigs   = array('location' => $GLOBALS['server'].'/sync/aspire/pushConfigs.php',   'uri' => 'http://localhost/');
                    $optionsPushwaypoints = array('location' => $GLOBALS['server'].'/sync/aspire/pushWaypoints.php', 'uri' => 'http://localhost/');
                    $optionsGetWaypoints  = array('location' => $GLOBALS['server'].'/sync/aspire/getWaypoints.php',  'uri' => 'http://localhost/');

                    $connected = true;
                }
                else
                {
                    echo "ERROR: Wrong Password !";
                }
            }
            else
            {
                echo 'ERROR: Missing fild : "id" and/or "pwd"';
            }

        }
        elseif ( isset($_POST['gen']) && $_POST['gen'] == 'janet' )
        {
            // Janet connection
            // Much less secure
            if( isset($_POST['id']) && isset($_POST['pwd']) ) 
            {
                $optionsPushlogs      = array('location' => $GLOBALS['server'].'/sync/janet/pushDatalogs.php',  'uri' => 'http://localhost/');
                $optionsGetConfigs    = array('location' => $GLOBALS['server'].'/sync/janet/getConfigs.php',    'uri' => 'http://localhost/');
                $optionsPushConfigs   = array('location' => $GLOBALS['server'].'/sync/janet/pushConfigs.php',   'uri' => 'http://localhost/');
                $optionsPushwaypoints = array('location' => $GLOBALS['server'].'/sync/janet/pushWaypoints.php', 'uri' => 'http://localhost/');
                $optionsGetWaypoints  = array('location' => $GLOBALS['server'].'/sync/janet/getWaypoints.php',  'uri' => 'http://localhost/');

                $connected = true;
            }
            else
            {
                echo 'ERROR: Missing fild : "id" and/or "pwd"';
            }
        }
        else
        {
            echo 'ERROR: "gen" field "aspire" or "janet" missing !';
        }

        // Janet or ASPire connection
        if ($connected)
        {
            //create an instante of the SOAPClient (the API will be available)
            $pushLogsService     = new SoapClient(NULL, $optionsPushlogs);
            $getConfigsService   = new SoapClient(NULL, $optionsGetConfigs);
            $pushConfigsService  = new SoapClient(NULL, $optionsPushConfigs);
            $pushPushWaypoints   = new SoapClient(NULL, $optionsPushwaypoints);
            $getWaypointsService = new SoapClient(NULL, $optionsGetWaypoints);

            if(isset($_POST["serv"])) 
            {
                try 
                {
                    switch($_POST["serv"]) 
                    {
                        case "checkIfNewConfigs":
                            echo $getConfigsService->checkIfNewConfigs();
                            break;
                        case "checkIfNewWaypoints":
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
                            print_r($pushLogsService->pushAllLogs($_POST["id"], $_POST["data"]));
                            break;
                        default:
                            break;
                    }
                } 
                catch(Exception $e) 
                {
                    print_r("ERROR: (exception thrown in sync/index.php): ".$e->getMessage());
                }
            }
            else
            {
                echo 'ERROR: "serv" field is empty';
            }
        }
        else
        {
            echo 'ERROR: Something happened !';
        }

    }
    else 
    {
        // echo "This folder is used for the httpsync and it has no \"interface\".";
        header('Location: ./..'); // Go back to the home page
    }
?>
