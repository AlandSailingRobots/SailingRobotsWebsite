<?php
session_start();
require('dbconnection.php');

if (isset($_SESSION['id']) && isset($_SESSION['name']) && isset($_SESSION['table']) && isset($_SESSION['number']))
{
    $id        = $_SESSION['id'];
    $name      = $_SESSION['name'];
    $table     = $_SESSION['table'];
    $number    = $_SESSION['number'];
    $isSession = true;
}
else
{
    $isSession = false;
    $id        = 1;
    $name      = "";
    $table     = "";
    $number    = 1;
}

switch ($_REQUEST['action']) 
{
    case 'getAll':
        if ($isSession)
        {
            $tables = getAll($id,$name, $table);
            echo json_encode($tables);
        }
        else
        {
            echo "ERROR: Dbapi: Session not set";
        }
        break;

    case 'getRoute':
        if ($isSession)
        {
            $tables = getRoute($id);
            echo json_encode($tables);
        }
        else
        {
            echo "ERROR: Dbapi: Session not set";
        }
        break;

    case 'getAllRoutes':
        $tables = getAllRoutes();
        echo json_encode($tables);
        break;

    default:
        echo "!!! CONNY W T F !!!";
        break;
}
?>
