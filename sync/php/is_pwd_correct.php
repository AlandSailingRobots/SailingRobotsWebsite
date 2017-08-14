<?php

// Check in the DB if the password exists
function is_pwd_correct($password)
{
	$hostname   = $GLOBALS['hostname'];
	$username   = $GLOBALS['username'];
	$password   = $GLOBALS['password'];
	$dbname     = $GLOBALS['database_name'];

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
	$password = hash('sha256', $password);

	$req = $db->prepare('SELECT * FROM httpsync WHERE password = ? ;');
	$result = $req->execute(array($password));

	// If the execution failed
	if ($result == false)
	{
		return false;
	}

	if ($req->fetchAll(PDO::FETCH_ASSOC))
	{
		// It means that the array is not empty
		return true;
	}
	else
	{
		return false;
	}
}