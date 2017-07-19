<?php
function getPointList($id_mission)
{
    /* 
     * This function handles a connection to the database to retrieve the waypoints
     * and checkpoints associated to a specific mission.
     */

    $hostname  = $GLOBALS['hostname'];
    $username  = $GLOBALS['username'];
    $password  = $GLOBALS['password'];
    $dbname    = $GLOBALS['database_mission'];
    try
    {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8;port=3306",
                        $db_user,
                        $db_password,
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                    );
    }
    catch(Exception $e)
    {
        die('Error : '.$e->getMessage());
    }
    
    $query = $db->prepare('SELECT * FROM pointList WHERE id_mission = :id_mission ;');
    $query->execute(array('id_mission' => $id_mission)
                );

    $result = $query->fetchAll();

    $query->closeCursor();

    return $result;
}
?>
