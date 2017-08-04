<?php
function loadMissionToBoat($id_mission)
{
    /* 
     * This function retrieves the selected mission from one DB and load it into the other
     */

    $hostname   = $GLOBALS['hostname'];
    $username   = $GLOBALS['username'];
    $password   = $GLOBALS['password'];
    $dbname     = $GLOBALS['database_mission'];
    $dbname_ASP = $GLOBALS['database_ASPire'];

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
    try
    {
        $db_boat = new PDO("mysql:host=$hostname;dbname=$dbname_ASP;charset=utf8;port=3306",
                        $username,
                        $password,
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                    );
    }
    catch(Exception $e)
    {
        die('Error : '.$e->getMessage());
    }
    // We update the last_use value of the DB
    $query1 = $db->prepare('UPDATE mission SET last_use = CURDATE() WHERE id = ?');
    $exec1 = $query1->execute(array($id_mission));

    // Delete the table which on the ASPire config
    $query2 = $db_boat->prepare('DELETE FROM currentMission');
    $exec2 = $query2->execute();

    // We get the pointList to insert into another array
    $query3 = $db->prepare('SELECT * FROM pointList where id_mission = ?;');
    $exec3 = $query3->execute(array($id_mission));
    $results = $query3->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($results))
    {
        // We insert the pointList in the configuration DB of the boat
        // Let's find a way to generate $arrayOfPoints :p
        // It should look like (id, id_mission, ...), (id, id_mission, ...), ...
        $emptyArray = "";
        $arrayOfPoints = array();
        $emptyParam = "( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        foreach ($results as $key => $value) 
        {
            $emptyArray = $emptyArray . ', ' . $emptyParam; 
            $arrayOfPoints = array_merge($arrayOfPoints, array_values($value));
        }
        $emptyArray = substr($emptyArray, 1);

        // Now we insert every waypoints / checkpoint into the DB 
        $query4 = $db_boat->prepare('INSERT INTO currentMission (  id, 
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
        // print_r($results);
        $exec4 = $query4->execute($arrayOfPoints);
        if ($exec4 === false)
        {
            $msg = sprintf("Error while inserting pointList into DB (ASPire_config) because execute() failed: %s\n<br />", htmlspecialchars($query4->error));
        }
        $query4->closeCursor();

    }
    else
    {
        $msg = "Empty array ! Are you sure this mission has points to be loaded ?";
    }

    if( $exec1 === true && $exec2 === true && $exec3 === true && $exec4 === true )
    {
        $msg = sprintf("Success !");
    } 
    else if ($exec1 === false)
    {
        $msg = sprintf("Error while updating 'last_use' into DB (website) because execute() failed: %s\n<br />", htmlspecialchars($query1->error));
    }
    else if ($exec2 === false)
    {
        $msg = sprintf("Error while deleting old pointList into DB (ASPire_config) because execute() failed: %s\n<br />", htmlspecialchars($query2->error));
    }
     else if ($exec3 === false)
    {
        $msg = sprintf("Error while getting pointList from DB (website) because execute() failed: %s\n<br />", htmlspecialchars($query3->error));
    }   
    echo $msg;

    $query1->closeCursor();
    $query2->closeCursor();
    $query3->closeCursor();

}

if (is_ajax()) 
{
    define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
    require_once(__ROOT__.'/globalsettings.php');
    session_start();
    
    // Get param & return JSON string
    if ($_SESSION['right'] == 'admin')
    {
        loadMissionToBoat($_POST['id_mission']);
    }
}

// Function to check if the request is an AJAX request
function is_ajax() 
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

?>
