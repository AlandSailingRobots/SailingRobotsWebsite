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


if (!isset($_GET['boat'])) {
    // We force aspire by default
    $_GET['boat'] = "aspire";
}
if ($_GET['boat'] == 'janet') {
    $boatName = 'janet';
    $available_pages = array (  0 => 'compass',
                                1 => 'gps',
                                2 => 'course',
                                3 => 'windsensor',
                                4 => 'system',
                                5 => 'marine_sensors',
                                6 => 'actuator_feedback'
                            );
    $pageName = array ( 0 => 'Compass Data',
                        1 => 'GPS Data',
                        2 => 'Course Data',
                        3 => 'Wind Sensor Data',
                        4 => 'System Datalogs',
                        5 => 'Marine Sensors Measurements',
                        6 => 'Actuator Feedback'
                    );
} elseif ($_GET['boat'] == 'aspire') {
    $boatName = 'aspire';
    $available_pages = array (  0 => 'actuator_feedback',
                                1 => 'compass',
                                2 => 'course',
                                3 => 'current_sensors',
                                4 => 'gps',
                                5 => 'system',
                                6 => 'vessel_state',
                                7 => 'windsensor',
                                8 => 'wind_state'
                            );
    $pageName = array ( 0 => 'Actuator Feedback',
                        1 => 'Compass Data',
                        2 => 'Course Data',
                        3 => 'Current Sensors',
                        4 => 'GPS Data',
                        5 => 'System Datalogs',
                        6 => 'Vessel State',
                        7 => 'Wind Sensor Data',
                        8 => 'Wind State'
                    );
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php // Head of the HTML document
    include $relative_path . 'include/head.php';
?>
    <!-- Custom CSS for the page -->
    <!-- TODO Check if it's used or not -->
    <link href="main.css" rel="stylesheet">
    <meta http-equiv="refresh" content="6" >
</head>

<body class="nav-md">
  <div class="container body">
    <!-- sidebar -->
    <?php include $relative_path . 'include/sidebar.php'; ?>
    <!-- /sidebar -->

    <!-- top navigation -->
    <?php include $relative_path . 'include/top_navigation.php'; ?>
    <!-- /top navigation -->

    <!-- data content -->
    <div class="right_col" role="main">
        <?php
        if ($connected and $_SESSION['right'] == 'admin') {
            // echo '<p> You are an ' . $_SESSION['right'] . '! ';
        ?>
        <div class="container-fluid" >
            <div class="">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                    <?php
                    foreach ($pageName as $key => $displayedText) {
                        echo '<li><a href="index.php?boat='.$boatName.'&data='.$available_pages[$key].'">'.$displayedText.'</a></li>';
                    }
                    ?>
                    </ul>
                </div>
            </div>

            <?php
            if (isset($_GET['data'])) {
                $data = $_GET['data'];
                if (in_array($data, $available_pages)) {
                    include('adaptative_body.php');
                }
/*                    if ($boatName == 'janet' && in_array($data, $available_pages))
                {
                    include 'adaptative_body_janet.php';
                }
                elseif ($boatName == 'aspire' && in_array($data, $available_pages))
                {
                    include 'adaptative_body_aspire.php';
                }*/
            } else {
                echo '<div class="col-sm-9 col-md-10">';
                echo '<h1 class="sub-header jumbotron">Please choose which information you would like to visualize</h1>';
                echo '</div>';
                //include 'admin_body.php';
            }
        } elseif ($connected) {
            echo '<p> You don\'t have the right to view this webdata </p>';
        } else {
            echo '<p> You must log-in to view this data. Click <strong><a href=' . $relative_path . 'pages/users/login.php>here</a></strong> to log-in. </p>';
        }
            ?>
        </div>
    </div>
    <!-- /data content -->

    <!-- footer content -->
    <?php include $relative_path . 'include/footer.php'; ?>
    <!-- /footer content -->
  </div>

  <!-- ##########################    JAVASCRIPT     ########################## -->
    <?php // Not very clean, but the default configs includes too many JS for a beginner
        // That way, main file is 'clean' ?>
    <?php  //include $relative_path . 'include/js_scripts.php'; ?>
  <!-- jQuery -->
    <script src=<?php echo $relative_path . "assets/vendors/jquery/dist/jquery.min.js"?>></script>
    <!-- Bootstrap -->
    <script src=<?php echo $relative_path . "assets/vendors/bootstrap/dist/js/bootstrap.min.js"?>></script>
    <!-- Custom Theme Scripts -->
    <script src=<?php echo $relative_path . "assets/js/custom.min.js"?>></script>

    <!-- TODO Check if it's used or not  -->
    <!-- <script src="jsfunctions.js"></script> -->

  <!-- ##########################    JAVASCRIPT     ########################## -->
</body>
</html>
