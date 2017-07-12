<?php
function dbConn() {
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

function getPerPage()
{
    $perpage = 50;
    return $perpage;
}

function getPages($table) 
{
    $conn = dbConn();
    $total  = $conn->query("SELECT COUNT(*) as rows FROM $table") ->fetch(PDO::FETCH_OBJ);
    $perpage = getPerPage();
    $posts  = $total->rows;
    $pages  = ceil($posts / $perpage);
    return $pages;
}

function getNumber() 
{
    # default
    $pages = getPages("gps_dataLogs");
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

function getNext()
{
    $number = getNumber();
    $next = $number + 1;
    return $next;
}

function getPrev()
{
    $number = getNumber();
    $prev = $number - 1;
    return $prev;
}



function getData($table)
{
    $conn = dbConn();
    try 
    {
        $pages = getPages($table);
        $perpage = getPerPage();
        $number = getNumber();
        $range  = $perpage * ($number - 1);
        $stmt = $conn->prepare("SELECT * FROM $table LIMIT :limit, :perpage;");

        $stmt->bindParam(':perpage', $perpage, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $range, PDO::PARAM_INT);
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
