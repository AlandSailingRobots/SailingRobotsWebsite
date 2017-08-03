<?php
function getConfigData($table)
{
    $user          = $GLOBALS['username'];
    $password      = $GLOBALS['password'];
    $hostname      = $GLOBALS['hostname'];
    $database_name = $GLOBALS['database_ASPire'];
    try
    {
        $db = new PDO("mysql:host=$hostname;
                        dbname=$database_name;
                        charset=utf8;port=3306", 
                        $user, 
                        $password, 
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e)
    {
        die('Connection failed : '.$e->getMessage());
    }

    // I tried to protect the sql query -- Don't know if it's necessary
    $allowed_query = array (0 => 'config_buffer',
                            1 => 'config_httpsync',
                            2 => 'config_HTTPSyncNode',
                            3 => 'config_i2c',
                            4 => 'config_StateEstimation',
                            5 => 'config_wind_vane',
                            6 => 'scanning_measurements'
                            );
    if (in_array($table, $allowed_query, true))
    {
        $index = array_search($table, $allowed_query);
        $protected_table = $allowed_query[$index];
    
        // SQL Query
        $req = $db->prepare('SELECT * FROM ' . $protected_table);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        $req->closeCursor();
        
        return ($result);
    }


}
?>
