<?php
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__ . '/globalsettings.php');
require_once('./is_ajax.php');
session_start();

function getMissionInfoFromDB($id_mission)
{
    /*
     * This function handles a connection to the database to retrieve the waypoints
     * and checkpoints associated to a specific mission.
     */

    $hostname = $GLOBALS['hostname'];
    $username = $GLOBALS['username'];
    $password = $GLOBALS['password'];
    $dbname = $GLOBALS['database_mission'];
    try {
        $db = new PDO(
            "mysql:host=$hostname;dbname=$dbname;charset=utf8;port=3306",
            $username,
            $password,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    } catch (Exception $e) {
        header(
            $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error',
            true,
            500
        );
        die('Error : ' . $e->getMessage());
    }

    $query = $db->prepare('SELECT * FROM mission WHERE id = ? ;');
    $query->execute(array($id_mission));

    try {
        $resultJSON = json_encode($query->fetchAll(PDO::FETCH_ASSOC));
        echo $resultJSON;
    } catch (Exception $e) {
        header(
            $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error',
            true,
            500
        );
        die('Error : ' . $e->getMessage());
        echo "";
    }

    $query->closeCursor();
}


if (is_ajax()) {
    // Get param & return JSON string
    if (isset($_POST['id_mission']) && $_SESSION['right'] == 'admin') {
        getMissionInfoFromDB($_POST['id_mission']);
    }
}
