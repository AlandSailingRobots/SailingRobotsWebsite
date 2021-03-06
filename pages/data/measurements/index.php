<?php
session_start();
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once(__ROOT__.'/globalsettings.php');
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
    include $relative_path . 'include/head.php';
?>
    <link rel="stylesheet" href="<?php echo $relative_path . 'assets/vendors/leafletjs/leaflet.css' ;?>" />
    <link rel="stylesheet" href="css/custom_style.css" />

</head>

<body class="nav-md">
  <div class="container body">
    <!-- sidebar -->
    <?php include $relative_path . 'include/sidebar.php'; ?>
    <!-- /sidebar -->

    <!-- top navigation -->
    <?php include $relative_path . 'include/top_navigation.php'; ?>
    <!-- /top navigation -->

    <!-- page content -->
    <div class="right_col" role="main">
        <?php
        if ($connected and $_SESSION['right'] == 'admin') {
            //echo '<p> You are an ' . $_SESSION['right'] . '! ';
            include 'measurements_body.php';
        } elseif ($connected) {
            echo '<p> You don\'t have the right to view this webpage </p>';
        } else {
            echo '<p> Vous must log-in to view this page. Click <strong><a href=' .
                $relative_path . 'pages/users/login.php>here</a></strong> to log-in. </p>';
        }
        ?>
    </div>
    <!-- /page content -->

    <!-- footer content -->
    <?php include $relative_path . 'include/footer.php'; ?>
    <!-- /footer content -->
  </div>

  <!-- ##########################    JAVASCRIPT     ########################## -->
    <?php // Not very clean, but the default configs includes too many JS for a beginner
        // That way, main file is 'clean' ?>
    <?php  //include $relative_path . 'include/js_scripts.php';
    ?>
    <!-- jQuery -->
    <script src=<?php echo $relative_path . "assets/vendors/jquery/dist/jquery.min.js"?>></script>
    <!-- Bootstrap -->
    <script src=<?php echo $relative_path . "assets/vendors/bootstrap/dist/js/bootstrap.min.js"?>></script>
    <!-- Custom Theme Scripts -->
    <script src=<?php echo $relative_path . "assets/js/custom.min.js"?>></script>
    <?php
    if ($connected) {
        echo '<script src="' . $relative_path . 'assets/vendors/leafletjs/leaflet.js"></script>';
      // echo '<script src="' . $relative_path . 'assets/vendors/geomagnetism/index.js"></script>';

        echo '<script src="./js/map_leaflet.js"></script>';
        echo '<script src="./js/script.js"></script>';

        echo '<script src="./js/leaflet-color-markers.js"></script>';
      // echo '<script src="' . $relative_path . 'assets/vendors/bootstrap-select-1.12.4/dist/js/bootstrap-select.min.js"</script>';
    }
    ?>


  <!-- ##########################    JAVASCRIPT     ########################## -->

</body>
</html>
