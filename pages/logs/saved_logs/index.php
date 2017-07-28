<?php
session_start();
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once(__ROOT__.'/globalsettings.php');
$relative_path = './../../../';

//  If we are connected
if (isset($_SESSION['id']) AND isset($_SESSION['username']))
{
  // TODO
  $connected = true;
  $name = $_SESSION['username'];
}
else
{
  session_destroy();
  $connected = false;
  $_SESSION['username'] = 'Guest';
  $name = 'Guest';
}
$available_pages = array (0 => 'compass',
                        1 => 'course',
                        2 => 'system',
                        3 => 'windsensor',
                        4 => 'gps'
                    );

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
        if ($connected and $_SESSION['right'] == 'admin')
        {
            // echo '<p> You are an ' . $_SESSION['right'] . '! ';
        ?>
        <div class="container-fluid" >
            <div class="">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                        <li><a href="index.php?data=gps"><i class="fa fa-desktop"></i> GPS Data</a></li>
                        <li><a href="index.php?data=course">Course Data</a></li>
                        <li><a href="index.php?data=windsensor">Wind Sensor Data</a></li>
                        <li><a href="index.php?data=compass">Compass Data</a></li>
                        <li><a href="index.php?data=system">System Datalogs</a></li>
                    </ul>
                </div>
            </div>

            <?php
                if (isset($_GET['data']))
                {
                    $data = $_GET['data'];
                    if (in_array($data, $available_pages))
                    {
                        /* include 'pages/' . $data . '_body.php'; */
                        include 'adaptative_body.php';
                    }
                }
                else
                {   
                    echo '<div class="col-sm-9 col-md-10">';
                    echo '<h1 class="sub-header jumbotron">Please choose which information you would like to visualize</h1>';
                    echo '</div>';
                    //include 'admin_body.php';
                }
            }
            elseif ($connected)
            {
                echo '<p> You don\'t have the right to view this webdata </p>';
            }
            else
            {
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
    <script src="jsfunctions.js"></script>

  <!-- ##########################    JAVASCRIPT     ########################## -->
</body>
</html>
