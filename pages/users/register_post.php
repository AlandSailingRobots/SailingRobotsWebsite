<?php
session_start();
// Connexion à la base de données
try
{
    $hostname = '127.0.0.1';
    $db_user = 'root';
    $db_password = '';
    $database_name = 'ithaax_ASPire_config';
    $bdd = new PDO("mysql:host=$hostname;dbname=$database_name;charset=utf8;port=3306", $db_user, $db_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

function checkNicknameInDB($nickname)
{
    global $bdd;
    $req = $bdd->prepare('SELECT username FROM users WHERE username=?');
    $req->execute(array($nickname));

    $membre = $req->fetch();
    if (empty($membre))
    {
        $bool = 0;
    }
    else
    {
        $bool = 1;
    }
    $req->closeCursor();
    return $bool;
}
function checkPwd_CPwdAreTheSame($pwd, $cpwd)
{
    if (strcmp($pwd, $cpwd) == 0)
    {
        return 1;
    }
    else
    {
        return 0;
    }
}
?>

<?php
$ok = 0;
// Check that the form has been completed
if (!(isset($_POST['username']) and
        isset($_POST['password']) and
        isset($_POST['password_confirmed']) and
        isset($_POST['email']))
    )
{
    echo '<p> valeurs : ' . $username . ' | ' . $email . ' | ' . $password . '</p>';
    header('Location: register.php?message="Please fullfill the form"');
}
else
{
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $pwd = htmlspecialchars($_POST['password']);
    $pwd_c = htmlspecialchars($_POST['password_confirmed']);

    // echo '<p> valeurs : ' . $username . ' | ' . $email . ' | ' . $pwd . ' | ' . $pwd_c . '</p>';
    // Checking username
    if (checkNicknameInDB($username) == 1)
    {
        header('Location: register.php?message=Username not available !');
    }
    // Checking password
    elseif (strcmp($pwd, $pwd_c) != 0)
    {
        // Password are not identical
        header('Location: register.php?message=Passwords are not identical !');
    }
    // Checking email
    elseif (!(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email)))
    {
        header('Location: register.php?message=Incorrect e-mail address');
    }
    else
    {
        // We can add the user
        // Hashing password
        $pass_hache = hash('sha256',$_POST['password']);

        // Insertion
        $req = $bdd->prepare('INSERT INTO users(username, email, password, registration_date) VALUES(:username, :email, :pass, NOW())');
        $req->execute(array(
            'username' => $username,
            'pass' => $pass_hache,
            'email' => $email));
        // $req->closeCursor();

        // Getting id of user
        $req = $bdd->prepare('SELECT id FROM users WHERE username = :username');
        $req->execute(array(
            'username' => $username));
        $resultat = $req->fetch();
        $_SESSION['id'] = $resultat['id'];
        $req->closeCursor();

        // Registration into the users_rights table
        $req = $bdd->prepare('INSERT INTO users_rights(id_user) VALUES (:id_user)');
        $req->execute(array(
            'id_user' => $_SESSION['id']));
        // $req->closeCursor();

        $_SESSION['right'] = 'user';
        $_SESSION['username'] = $username;
        header('Location: login.php?message=You are now logged-in :)');
    }
}
?>
