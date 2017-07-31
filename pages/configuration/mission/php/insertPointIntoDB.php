<?php
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__.'/globalsettings.php');
session_start();

function insertPointIntoDB($params)
{
    /* 
     * This function add a new entry in the DB.
     * After that, the mission will be selectable for its editing.
     */

    $hostname  = $GLOBALS['hostname'];
    $username  = $GLOBALS['username'];
    $password  = $GLOBALS['password'];
    $dbname    = $GLOBALS['database_mission'];
    try
    {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8;port=3306",
                        $username,
                        $password,
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                    );
    }
    catch(Exception $e)
    {
        die('Error : '.$e->getMessage());
    }

    // First we delete everything associated to the mission
    $query = $db->prepare('DELETE FROM pointList WHERE id_mission = ? ;');
    $query->execute(array($params['1']['id_mission']));
    $query->closeCursor();

    // Let's find a way to generate $arrayOfPoints :p
    // It should look like (id, id_mission, ...), (id, id_mission, ...), ...
    $emptyArray = "";
    $arrayOfPoints = array();
    $emptyParam = "( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    foreach ($params as $key => $value) 
    {
        $emptyArray = $emptyArray . ', ' . $emptyParam; 
        $arrayOfPoints = array_merge($arrayOfPoints, array_values($value));
    }
    $emptyArray = substr($emptyArray, 1);

    // Now we insert every waypoints / checkpoint into the DB 
    $query = $db->prepare('INSERT INTO pointList (  id, 
                                                    id_mission, 
                                                    rankInMission,
                                                    isCheckpoint, 
                                                    name, 
                                                    latitude, 
                                                    longitude, 
                                                    declination, 
                                                    radius, 
                                                    stay_time,
                                                    harvested
                                                ) VALUES '. $emptyArray .' ;');

    $exec = $query->execute($arrayOfPoints);

    if( false === $exec )
    {
        $msg = sprintf("Error while inserting into DB because execute() failed: %s\n<br />", htmlspecialchars($query->error));
    } 
    else 
    {
        $msg = sprintf("Success !\n<br />");
    }

    echo $msg;
}


if (is_ajax()) 
{
    // Parsing JSON object to 2D-Array
    $request = file_get_contents("php://input");    // gets the raw data
    $params = json_decode($request,true);           // true for return as array

    insertPointIntoDB($params);
}

// Function to check if the request is an AJAX request
function is_ajax() 
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
