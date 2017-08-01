<?php
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__.'/globalsettings.php');
session_start();

function updateMissionInfo($id_mission, $name, $description = "")
{
    /* 
     * This function update an entry of the DB.
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

    $query = $db->prepare('UPDATE mission SET name = :name, description = :description WHERE id = :id ;');
    $query->execute(array(
            'name' => $name,
            'description' => $description,
            'id' => $id_mission)
        );

    // add success / failure message

}

if(is_ajax())
{
    if (isset($_POST['id_mission']) && isset($_POST['name']) && isset($_POST['description']) && $_SESSION['right'] == 'admin')
    {
        updateMissionInfo($_POST['id_mission'], $_POST['name'], $_POST['description']);
    }
    elseif (isset($_POST['id_mission']) && isset($_POST['name']) && $_SESSION['right'] == 'admin')
    {
        updateMissionInfo($_POST['id_mission'], $_POST['name']);
    }
}

// Function to check if the request is an AJAX request
function is_ajax() 
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>
