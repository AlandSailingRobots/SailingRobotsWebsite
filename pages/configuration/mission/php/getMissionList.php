<?php
function getMissionList()
{
    /* 
     * This function retrieves the different missions from the database
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
    
    $query = $db->prepare('SELECT * FROM mission ;');
    $query->execute();

    $result = $query->fetchAll();

    $query->closeCursor();

    return $result;
}
?>
