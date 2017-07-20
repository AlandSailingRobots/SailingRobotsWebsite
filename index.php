<?php
session_start();
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/globalsettings.php');
$relative_path = './';

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'include/head.php'; ?>
</head>

<body class="nav-md">
  <div class="container body">
    <!-- sidebar -->
    <?php include 'include/sidebar.php'; ?>
    <!-- /sidebar -->

    <!-- top navigation -->
    <?php include 'include/top_navigation.php'; ?>
    <!-- /top navigation -->

    <!-- page content -->
    <div class="right_col" role="main">
      <?php
        if ($connected)
        {
          echo '<h1> Welcome on board, ' . $name . ' ! </h1> ' ;
          echo '<p> You are an ' . $_SESSION['right'] . '! ';
        }
        else
        {
          echo '<h1>Welcome on board !</h1>';
        }
      ?>

        <!-- start of weather widget -->
        <?php include 'include/weather_widget.php' ?>
        <!-- end of weather widget -->


    </div>
    <!-- /page content -->

    <!-- footer content -->
    <?php include 'include/footer.php'; ?>
    <!-- /footer content -->
  </div>

  <!-- ##########################    JAVASCRIPT     ########################## -->
  <?php // Not very clean, but the default configs includes too many JS for a beginner
        // That way, main file is 'clean' ?>
  <?php  //include 'include/js_scripts.php'; ?>
      <!-- jQuery -->
    <script src=<?php echo $relative_path . "assets/vendors/jquery/dist/jquery.min.js"?>></script>
    <!-- Bootstrap -->
    <script src=<?php echo $relative_path . "assets/vendors/bootstrap/dist/js/bootstrap.min.js"?>></script>
    <!-- Custom Theme Scripts -->
    <script src=<?php echo $relative_path . "assets/js/custom.min.js"?>></script>
  <!-- ##########################    JAVASCRIPT     ########################## -->
</body>
</html>
