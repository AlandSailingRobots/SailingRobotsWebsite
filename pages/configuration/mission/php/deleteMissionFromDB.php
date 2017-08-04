<?php
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__.'/globalsettings.php');
require_once('is_ajax.php');

function deleteMissionFromDB($id_mission)
{
    /* 
     * This function deletes a mission from the database as long as the waypoits 
     * and checkpoints linked to this mission.
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

    // Delete the mission
    $query1 = $db->prepare('DELETE FROM mission WHERE mission.id=?;');
    $exec1 = $query1->execute(array(htmlspecialchars($id_mission)));
    
    // Delete its waypoints and checkpoints
    $query2 = $db->prepare('DELETE FROM pointList WHERE pointList.id_mission=?;');
    $exec2 = $query2->execute(array(htmlspecialchars($id_mission)));

    // Check if success or not
    if ($exec1 == false)
    {
        $msg = sprintf("Error while deleting mission from DB (website) because execute() failed: %s\n<br />", htmlspecialchars($query1->error));
    }
    if ($exec2 == false)
    {
        $msg = sprintf("Error while deleting old pointList from DB (website) because execute() failed: %s\n<br />", htmlspecialchars($query2->error));
    }
    if ($exec1 && $exec2)
    {
        $msg = "Success !";
    }

    echo $msg;
    
    // End the queries properly
    $query1->closeCursor();
    $query2->closeCursor();
}

session_start();
if (is_ajax() && isset($_POST['id_mission']) && $_SESSION['right'] == 'admin')
{
    deleteMissionFromDB($_POST['id_mission']);
}
?>
