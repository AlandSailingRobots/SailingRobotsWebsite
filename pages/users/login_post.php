<?php
require_once('./../../globalsettings.php');
// Vérification du remplissage de tous les champs
if ( !(isset($_POST['username']) and isset($_POST['password'])) )
{
    header('Location: connexion.php?message="Please fullfill the form"');
}
else
{
    $hostname  = $GLOBALS['hostname'];
    $username  = $GLOBALS['username'];
    $password  = $GLOBALS['password'];
    $dbname    = $GLOBALS['database_name'];
    try
    {
        $bdd = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8;port=3306",
                        $username,
                        $password,
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                    );
    }
    catch(Exception $e)
    {
        die('Error : '.$e->getMessage());
    }

    $username = htmlspecialchars($_POST['username']);

    // Hashing password
    $pass_hache = hash('sha256',$_POST['password']);

    // Checking credentials
    $req = $bdd->prepare('SELECT id FROM users WHERE username = :username AND password = :pass');
    $req->execute(array(
        'username' => $username,
        'pass' => $pass_hache));

    $resultat = $req->fetch();
    // echo 'pass : ' . $_POST['password'] . ' / ' . $pass_hache . ' / ';
    if (!$resultat)
    {
        header('Location: login.php?message=Wrong credentials');
    }
    else
    {
        // session_destroy();
        // session_start();
        $_SESSION['id'] = $resultat['id'];
        $_SESSION['username'] = $username;

        // This is for getting the rights of the current user
        $req-> closeCursor();
        $req = $bdd->prepare('SELECT rights FROM users_rights WHERE id_user = :id_user');
        $req->execute(array(
                'id_user' => $_SESSION['id']));
        $resultat = $req->fetch();
        $_SESSION['right'] = $resultat['rights'];
        $req->closeCursor();

        // Now logged-in !
        header('Location: ../../index.php?message=You are now logged-in !');
    }
}
