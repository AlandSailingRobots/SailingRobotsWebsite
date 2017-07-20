<?php
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__.'/globalsettings.php');
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

    $query = $db->prepare('DELETE FROM mission WHERE mission.id=?;');
    $query->execute(array($id_mission));
    $result = 1;
    $query = $db->prepare('DELETE FROM pointList WHERE pointList.id_mission=?;');
    $query->execute(array($id_mission));

    return $result;
}

if (isset($_POST['id_mission']))
{
    deleteMissionFromDB($_POST['id_mission']);
}
?>
