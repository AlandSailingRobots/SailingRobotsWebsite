<?php
/**
 * File: updateMissionInfo.php
 *
 * Updates mission info
 *
 * @see https://github.com/AlandSailingRobots/SailingRobotsWebsite
 */
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once __ROOT__.'/globalsettings.php';
require_once 'is_ajax.php';
session_start();

/**
 * Updates mission info
 *
 * @param string $id_mission Mission ID
 * @param string $name
 * @param string $description
 *
 * @return
 */
function updateMissionInfo($id_mission, $name, $description = "")
{
    /*
     * This function update an entry of the DB.
     */

    $hostname  = $GLOBALS['hostname'];
    $username  = $GLOBALS['username'];
    $password  = $GLOBALS['password'];
    $dbname    = $GLOBALS['database_mission'];
    try {
        $db = new PDO(
            "mysql:host=$hostname;dbname=$dbname;charset=utf8;port=3306",
            $username,
            $password,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    } catch (Exception $e) {
        header(
            $_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error',
            true,
            500
        );
        die('Error : '.$e->getMessage());
    }

    $query = $db->prepare('UPDATE mission SET name = :name, description = :description WHERE id = :id ;');
    $exec = $query->execute(array(
                'name' => htmlspecialchars($name),
                'description' => htmlspecialchars($description),
                'id' => htmlspecialchars($id_mission)));

    if (false === $exec) {
        $msg = sprintf("Error while updating mission info into DB because execute() failed: %s\n<br />", htmlspecialchars($query->error));
    } else {
        $msg = sprintf("Success !");
    }

    echo $msg;
    $query->closeCursor();
}

if (is_ajax()) {
    if (isset($_POST['id_mission']) && isset($_POST['name']) && isset($_POST['description']) && $_SESSION['right'] == 'admin') {
        updateMissionInfo($_POST['id_mission'], $_POST['name'], $_POST['description']);
    } elseif (isset($_POST['id_mission']) && isset($_POST['name']) && $_SESSION['right'] == 'admin') {
        updateMissionInfo($_POST['id_mission'], $_POST['name']);
    }
}
