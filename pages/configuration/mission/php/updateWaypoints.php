<?php
function updateWaypoint()
{
    
/*
 * TODO : Rewrite the scripts ?
 */
$servername = $GLOBALS['hostname'];
$username   = $GLOBALS['username'];
$password   = $GLOBALS['password'];
$dbname     = $GLOBALS['database_name_testdata'];
$conn       = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

/*
    $stmt  = "";
    $array = json_decode($_POST['json']);

    $waypointId  = 0;
    $waypointLat = 0;
    $waypointLng = 0;
    $lat_string  = "latitude = CASE id_waypoint";
    $lng_string  = "longitude = CASE id_waypoint";
    $all_ids     = "(";
    $stmt       .= "UPDATE waypoints SET ";
    $debug_stmt  = $stmt;
    //latitude   = CASE id_waypoint WHEN current_id THEN current_lat etc COMMA longitude SAME

    //BUILD LAT STRING:
    if (!empty($array))
    {
        $sqlquery = "UPDATE config_updated SET waypoints_updated = 1 WHERE id=1";
        foreach ($array as $key => $value)
        {   //ENTRIES: [0][1]...
            //$stmt.=" Waypoint: ".$key.". ";
            foreach($value as $key2 => $value2)
            {   //ENTRY: KEY ID, KEY POSITION
                //$stmt.="[".$key2."]:";
                if (is_array($value2) || is_object($value2))
                {
                    foreach($value2 as $variable => $varvalue)
                    {   //POSITION: KEY LANG, KEY LAT
                        //$stmt.=$variable."=".$varvalue.",";
                        if ($variable == "longitude")
                        {
                            $waypointLng = $varvalue;
                            //$stmt.="LANGITUDE: ".$varvalue;
                        }
                        if ($variable == "latitude")
                        {
                            $waypointLat = $varvalue;
                            //$stmt.="LATITUDE: ".$varvalue;
                        }
                    }
                }
                if ($key2 == "id")
                {
                    $waypointId = $value2;
                    $all_ids.=$waypointId.",";
                }
                //Using different id key for inserted waypoints
                if($key2 == "newId")
                {
                    $waypointId = $value2;
                    $all_ids.=$waypointId.",";
                }
            }

            $lat_string.=" WHEN ".$waypointId." THEN ".$waypointLat."";
            $lng_string.=" WHEN ".$waypointId." THEN ".$waypointLng."";

            //$stmt = substr($stmt, 0, strlen($stmt)-1);
            //$stmt.=" WHERE id_waypoint=".$waypointId.";";

        }
        $debug_stmt.="longitude = ".$waypointLng.", latitude = ".$waypointLat." WHERE id_waypoint = ".$waypointId."";
    }
    else
    {
        $stmt = "No POST data.";
    }

    $all_ids = substr($all_ids, 0, strlen($all_ids)-1);
    $stmt.=$lat_string." END, ".$lng_string." END WHERE id_waypoint IN ".$all_ids.")";

    try 
    {
        //$stmt = substr($stmt, 0, strlen($stmt)-1);
        $conn->query($stmt);
        //$message = $conn->query("SELECT * FROM waypoints;");
        echo $debug_stmt;
        //echo $message;
    } 
    catch (PDOException $e) 
    {
        die("Woah, you wrote some crappy sql statement - lol. This went wrong: " . $e->getMessage());
        echo "ERROR IN SQL STATEMENT";
    }
*/
}
?>
