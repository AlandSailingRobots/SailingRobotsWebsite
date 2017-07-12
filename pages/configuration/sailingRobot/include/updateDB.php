<?php
//header("Refresh:5; url=http://localhost/Remote-sailing-robots/config/mainPage.php");

$servername = $GLOBALS['hostname'];
$username   = $GLOBALS['username'];
$password   = $GLOBALS['password'];
$dbname     = $GLOBALS['database_name_testdata'];
$conn       = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$table = "";
$stmt = "";
$keys = array_keys($_POST);

foreach($_POST as $key => $value)
{
    if($keys[0] == $key)
    {
        $table = $value;
    }
    else
    {
        if(isset($value) && $value != "")
        {
            if (!is_numeric($value)) 
            {
                $stmt.= $key."=".'"'.$value.'"'.",";
            }
            else 
            {
                $stmt.= $key."=".$value.",";
            }
        $sqlquery = "UPDATE config_updated SET updated = 1 WHERE id=1";
        $conn->query($sqlquery);
        }
    }
}

$stmt = substr($stmt, 0, strlen($stmt)-1);
if(!empty($stmt))
{
    $sql = "UPDATE $table SET $stmt WHERE id=1";
    $conn->query($sql);
}
?>
