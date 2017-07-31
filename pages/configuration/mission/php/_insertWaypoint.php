<?php
/*
 * That file has first been created by the first developper of the website
 * As the developper of version 2, I did not deeply modify it (ie only variables)
 */

$hostname  = $GLOBALS['hostname'];
$username  = $GLOBALS['username'];
$password  = $GLOBALS['password'];
$dbname    = $GLOBALS['database_mission'];
try
{
    $bdd = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8;port=3306",
                    $username,
                    $password,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                );
}
catch(Exception $e)
{
    die('Error : '.$e->getMessage());
}

/*
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }


    $array = json_decode($_POST['json']);
    $waypointLat = 0;
    $waypointLng = 0;
    $waypointRadius = 0;

    //Not sure how this works or if it's needed - implement dynamic setting later?
    $HARDCODED_DECLINATION = 6;


    $conn->query("DELETE FROM waypoints;");
    $conn->query("ALTER TABLE waypoints AUTO_INCREMENT = 0;");


    if (!empty($array)){
        foreach ($array as $key => $value)
        { //ENTRIES: [0][1]...
            $stmt = "INSERT INTO waypoints (latitude, longitude, declination, radius) VALUES ";

            foreach($value as $key2 => $value2)
            { //ENTRY: KEY ID, KEY POSITION
                //$stmt.="[".$key2."]:";
                if (is_array($value2) || is_object($value2))
                {
                    foreach($value2 as $variable => $varvalue)
                    { //POSITION: KEY LANG, KEY LAT
                        if ($variable == "longitude")
                        {
                            $waypointLng = $varvalue;
                        }
                        if ($variable == "latitude")
                        {
                            $waypointLat = $varvalue;
                        }
                        if ($variable == "radius")
                        {
                            $waypointRadius = $varvalue;
                        }
                    }
                }
                if ($key2 == "id")
                {
                    $waypointId = $value2;
                }
                $stmt.="(".$waypointLat.",".$waypointLng.",".$HARDCODED_DECLINATION.",".$waypointRadius.");";
            }
            try 
            {
                //$stmt = substr($stmt, 0, strlen($stmt)-1);
                $conn->query($stmt);

                //$message = $conn->query("SELECT * FROM waypoints;");
                echo $stmt;
                //echo $message;
            } 
            catch (PDOException $e) 
            {
                die("Woah, you wrote some crappy sql statement - lol. This went wrong: " . $e->getMessage());
                echo "ERROR IN SQL STATEMENT";
            }
        }
    }
    $conn->query("UPDATE config_updated SET waypoints_updated = 1 WHERE id=1");
*/
?>
