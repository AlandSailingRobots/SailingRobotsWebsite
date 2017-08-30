<?php
/*
 * This function get the content of the different table as long as the name of
 * the column in order to display it.
 */
function getConfigData($table, $boatName)
{
    if ($boatName == "janet")
    {
        $database_name = $GLOBALS['database_name_testdata'];
    }
    elseif ($boatName == "aspire") 
    {
        $database_name = $GLOBALS['database_ASPire'];
    }

    $user          = $GLOBALS['username'];
    $password      = $GLOBALS['password'];
    $hostname      = $GLOBALS['hostname'];

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

    // SQL Query
    $req = $db->prepare('SELECT * FROM ' . $table);
    $req->execute();
    $result = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    
    return ($result);



}
?>
