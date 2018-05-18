<?php
/**
 * File: is_pwd_correct.php
 *
 * @see https://github.com/AlandSailingRobots/SailingRobotsWebsite
 */

/**
 * Check in the DB if the password exists
 *
 * @param string $passwordToCheck Password in cleartext?
 *
 * @return bool Correct or fail
 */
function is_pwd_correct($passwordToCheck)
{
    $hostname   = $GLOBALS['hostname'];
    $username   = $GLOBALS['username'];
    $password   = $GLOBALS['password'];
    $dbname     = $GLOBALS['database_name'];

    try {
        $db = new PDO(
            "mysql:host=$hostname;dbname=$dbname;charset=utf8;port=3306",
            $username,
            $password,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    } catch (Exception $e) {
        header(
            $_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error',
            true,
            500
        );
        die('Error : '.$e->getMessage());
    }

    $passwordToCheck = hash('sha256', $passwordToCheck);

    $req = $db->prepare('SELECT * FROM httpsync WHERE password = ? ;');
    $result = $req->execute(array($passwordToCheck));

    // If the execution failed
    if ($result == false) {
        return false;
    }

    if ($req->fetchAll(PDO::FETCH_ASSOC)) {
        // It means that the array is not empty
        return true;
    } else {
        return false;
    }
}
