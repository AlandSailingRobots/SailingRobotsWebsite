<?php 
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once(__ROOT__.'/globalsettings.php');
session_start();

$i = 1; 


// This is not very secure.
foreach ($_POST as $key => $value) 
{
    if (!is_null($value) && !$i)
    {
        $exploded_key = explode('|', $key);
        if ($value)
        {
        // echo 'value changed : ' . $value . ' for the key ' . $exploded_key[1] . ' of the table ' . $exploded_key[0] .' <br/>' ;

        $query = $db->prepare("UPDATE $exploded_key[0] SET  $exploded_key[1] = ? ;");
        $query->execute(array(htmlspecialchars($value)));
        }
    }

    // Just for the first loop
    if ($i)
    {
        if($value == "aspire")
        {
            $name      = "aspire";
            $dbname    = $GLOBALS['database_ASPire'];
        }
        elseif($value == "janet")
        {
            $name      = "janet";
            $dbname    = $GLOBALS['database_name_testdata'];
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
    }

    header('Location: index.php?boat=' . $name);
}
?>