<?php
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__.'/globalsettings.php');

function insertPointIntoDB($name, $description = "")
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
    $id_mission = 0;
    $req = $bdd->prepare('DELETE * FROM pointList WHERE id_mission = ? ;')
    $req->execute(array($id_mission))
    $req->closeCursor();

    // Now we insert every waypoints / checkpoint into the DB 

}
?>