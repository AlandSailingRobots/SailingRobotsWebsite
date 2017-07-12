<?php
    // Vérification du remplissage de tous les champs
    if (!(isset($_POST['username']) and isset($_POST['password'])))
    {
        // echo '<p> valeurs : ' . $username . ' | ' . $email . ' | ' . $password . '</p>';
        header('Location: connexion.php?message="Please fullfill the form"');
    }
    else
    {
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
            echo 'Wrong username or password !';
            header('Location: login.php?message=Wrong credentials');
        }
        else
        {
            session_start();
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
            // echo 'You are now logged-in !';
            // echo $username .' ' .$resultat['id'];
            header('Location: ../../index.php?message=You are now logged-in !');
        }
    }
?>
