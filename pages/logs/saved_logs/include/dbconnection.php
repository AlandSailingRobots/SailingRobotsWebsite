<?php

 /* This file contains function used to access to database. They have been written
  * by the first developer of this website. As I don't understand everything, I
  * didn't rewrite everything.
  *
  * TODO : Secure the SQL query
  */


function dbConn() 
{
    $user           = $GLOBALS['username'];
    $password       = $GLOBALS['password'];
    $hostname       = $GLOBALS['hostname'];
    $database_name  = $GLOBALS['database_name_testdata'];
    try
    {
        $conn = new PDO("mysql:host=$hostname;
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
    return $conn;
}

function dbConnASPire() 
{
    $user           = $GLOBALS['username'];
    $password       = $GLOBALS['password'];
    $hostname       = $GLOBALS['hostname'];
    $database_name  = $GLOBALS['database_ASPire'];
    try
    {
        $conn = new PDO("mysql:host=$hostname;
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
    return $conn;
}

function getPerPage()
{
    $perpage = 50;
    return $perpage;
}

// Gives the total number of pages
function getPages($table, $dbName) 
{
    if ($dbName == 'janet')
    {
        $conn    = dbConn();
    }
    elseif ($dbName == 'aspire')
    {
        $conn = dbConnASPire();
    }
    $total   = $conn->query("SELECT COUNT(*) as rows FROM $table") ->fetch(PDO::FETCH_OBJ);
    $perpage = getPerPage();
    $posts   = $total->rows;
    $pages   = ceil($posts / $perpage);

    $conn = null;
    return $pages;
}

// Return the total number of lines in the given table of the given DB
function getNumberOfEntries($table, $dbName) 
{
    if ($dbName == 'janet')
    {
        $conn    = dbConn();
    }
    elseif ($dbName == 'aspire')
    {
        $conn = dbConnASPire();
    }
    $total   = $conn->query("SELECT COUNT(*) as rows FROM $table") ->fetch(PDO::FETCH_OBJ);
    $perpage = getPerPage();
    $posts   = $total->rows;

    $conn = null;
    return $posts;
}

function getNumber($pages) 
{
    $get_pages = isset($_GET['page']) ? $_GET['page'] : 1;
    $data = array(
        'options' => array(
            'default'   => 1,
            'min_range' => 1,
            'max_range' => $pages
            )
    );

    $number = trim($get_pages);
    $number = filter_var($number, FILTER_VALIDATE_INT, $data);
    return $number;
}

function getData($table, $pages)
{
    $conn = dbConn();
    try 
    {
        $perpage = getPerPage();
        $number  = getNumber($pages);
        $range   = $perpage * ($number - 1);
        $stmt    = $conn->prepare("SELECT * FROM $table LIMIT :limit, :perpage;");
        echo $number, ' ';
        echo $range, ' ', $perpage;
        $stmt->bindParam(':limit', $range, PDO::PARAM_INT);
        $stmt->bindParam(':perpage', $perpage, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll();

    } 
    catch(PDOException $e) 
    {
        $error = $e->getMessage();
    }

    $conn = null;
    return $result;
}

function getDataInverted($table, $pages, $dbName)
{
    if ($dbName == 'janet')
    {
        $conn    = dbConn();
    }
    elseif ($dbName == 'aspire')
    {
        $conn = dbConnASPire();
    }
    
    $numberMaxPages = getNumber($table);
    $numberOfLines = getNumberOfEntries($table, $dbName);
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $perpage = 2;
    $range = min(abs($numberOfLines - $current_page*$perpage), 0);
    $range = $perpage * ($current_page - 1);
    // echo $range;
    echo $current_page, ' ', $range;
    try
    {
        $stmt    = $conn->prepare("SELECT * FROM $table  LIMIT :limit_min, :perpage ;"); // TODO : check for use of DESC

        echo ' --- '.$range . ' ' . $perpage;
        $stmt->bindParam(':limit_min', $range, PDO::PARAM_INT);
        $stmt->bindParam(':perpage', $perpage, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e) 
    {
        $error = $e->getMessage();
        $result = array();
    }
    print_r($result);
    return $result;
}

function getAll($id, $name, $table)
{
    $conn = dbConn();
    try 
    {
        $stmt = $conn->prepare("SELECT * FROM system_dataLogs
            RIGHT JOIN gps_dataLogs
            ON system_dataLogs.id_system=gps_dataLogs.id_gps
            RIGHT JOIN course_calculation_dataLogs
            ON system_dataLogs.id_system=course_calculation_dataLogs.id_course_calculation
            RIGHT JOIN windsensor_dataLogs
            ON system_dataLogs.id_system=windsensor_dataLogs.id_windsensor
            RIGHT JOIN compass_dataLogs
            ON system_dataLogs.id_system=compass_dataLogs.id_compass_model
            WHERE $table.$name = $id
            UNION
            SELECT * FROM system_dataLogs
            LEFT JOIN gps_dataLogs
            ON system_dataLogs.id_system=gps_dataLogs.id_gps
            LEFT JOIN course_calculation_dataLogs
            ON system_dataLogs.id_system=course_calculation_dataLogs.id_course_calculation
            LEFT JOIN windsensor_dataLogs
            ON system_dataLogs.id_system=windsensor_dataLogs.id_windsensor
            LEFT JOIN compass_dataLogs
            ON system_dataLogs.id_system=compass_dataLogs.id_compass_model
            WHERE $table.$name = $id");
        $stmt->execute();

        $result = $stmt->fetchAll();

    }
    catch(PDOException $e) 
    {
        $error = $e->getMessage();
    }

    $conn = null;
    return $result;
}

function getAllRoutes()
{
    $conn = dbConn();
    try 
    {
        $stmt = $conn->prepare("SELECT latitude, longitude, route_started, id_gps FROM gps_dataLogs");
        $stmt->execute();
        $result = $stmt->fetchAll();
    }
    catch(PDOException $e) 
    {
        $error = $e->getMessage();
    }
    return $result;
}

function getRoute($id)
{
    $conn = dbConn();
    try 
    {
        $stmt = $conn->prepare("SELECT latitude, longitude, route_started, id_gps FROM gps_dataLogs WHERE id_gps <= $id");
        $stmt->execute();
        $result = $stmt->fetchAll();
    }
    catch(PDOException $e) 
    {
        $error = $e->getMessage();
    }
    return $result;
}
?>
