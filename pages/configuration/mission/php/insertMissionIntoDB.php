<?php
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__.'/globalsettings.php');

function insertMissionIntoDB($name, $description = "")
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

    $query = $db->prepare('INSERT INTO mission (name, description) VALUES (:name, :description);');
    $query->execute(array(
            'name' => $name,
            'description' => $description)
        );

    // return $result;
}

session_start();
if (isset($_POST['name']) && isset($_POST['description']) && $_SESSION['right'] == 'admin')
{
    insertMissionIntoDB($_POST['name'], $_POST['description']);
}
elseif (isset($_POST['name']) && $_SESSION['right'] == 'admin')
{
    insertMissionIntoDB($_POST['name']);
}
?>
