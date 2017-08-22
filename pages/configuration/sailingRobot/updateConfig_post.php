<?php 
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once(__ROOT__.'/globalsettings.php');
session_start();

$i = 1; 

// This is not very secure.
foreach ($_POST as $key => $value) 
{
    // Update the DB
    if (!is_null($value) && !$i)
    {
        $exploded_key = explode('|', $key);
        if ($value)
        {
            $query = $db->prepare("UPDATE $exploded_key[0] SET $exploded_key[1] = ? ;");
            $query->execute(array(htmlspecialchars($value)));
            $query->closeCursor();
        }
    }

    // Just for the first loop, in order to get the name of the DB to update
    // Connection to the right DB
    if ($i)
    {
        if($value == "aspire")
        {
            $name       = "aspire";
            $dbname     = $GLOBALS['database_ASPire'];
            $table_name = "config_httpsync";
            $colum_name = "configs_updated"     ; 
        }
        elseif($value == "janet")
        {
            $name       = "janet";
            $dbname     = $GLOBALS['database_name_testdata'];
            $table_name = "config_updated";
            $colum_name = "updated";
        }

        $i--;
        
        $hostname  = $GLOBALS['hostname'];
        $username  = $GLOBALS['username'];
        $password  = $GLOBALS['password'];
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

        $req = $db->prepare('UPDATE ' . $table_name . ' SET ' . $colum_name . ' = 1 WHERE id = 1;');
        $req->execute();
        //$req->closeCursor();
    }

    header('Location: index.php?boat=' . $name);
}

