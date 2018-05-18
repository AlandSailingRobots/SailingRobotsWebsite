<?php
/**
 * File:
 *
 * Pages index
 *
 * @see https://github.com/AlandSailingRobots/SailingRobotsWebsite
 */
session_start();
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once __ROOT__.'/globalsettings.php';
$relative_path = './../../../';

//  If we are connected
if (isset($_SESSION['id']) and isset($_SESSION['username'])) {
    // TODO
    $connected = true;
    $name = $_SESSION['username'];
} else {
    session_destroy();
    $connected = false;
    $_SESSION['username'] = 'Guest';
    $name = 'Guest';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php // Head of the HTML document
require $relative_path.'include/head.php';
?>
</head>

<body class="nav-md">
    <div class="container body">
        <!-- sidebar -->
        <?php require $relative_path.'include/sidebar.php'; ?>
        <!-- /sidebar -->

        <!-- top navigation -->
        <?php require $relative_path.'include/top_navigation.php'; ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
<?php
if ($connected and $_SESSION['right'] == 'admin') {
    // echo '<p> You are an '.$_SESSION['right'].'! ';
    include 'admin_body.php';
} elseif ($connected) {
    echo '<p> You don\'t have the right to view this webpage </p>';
} else {
    echo '<p> You must log-in to view this page. Click <strong><a href='
        .$relative_path.'pages/users/login.php>here</a></strong> to log-in. </p>';
}
?>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <?php require $relative_path.'include/footer.php'; ?>
        <!-- /footer content -->
    </div>

    <!-- ##########################    JAVASCRIPT     ########################## -->
<?php // Not very clean, but the default configs includes too many JS for a beginner
// That way, main file is 'clean' ?>
    <?php  //require $relative_path.'include/js_scripts.php'; ?>
    <!-- jQuery -->
        <script src=<?php echo $relative_path."assets/vendors/jquery/dist/jquery.min.js"?>></script>
        <!-- Bootstrap -->
        <script src=<?php echo $relative_path."assets/vendors/bootstrap/dist/js/bootstrap.min.js"?>></script>
        <!-- Custom Theme Scripts -->
        <script src=<?php echo $relative_path."assets/js/custom.min.js"?>></script>


    <!-- ##########################    JAVASCRIPT     ########################## -->
</body>
</html>
