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
  $connected = false;
  $_SESSION['username'] = 'Guest';
  $name = 'Guest';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shorcut icon" href="resources/images/forward_enabled_hover.png">

  <title>Sailing Robots v2 !</title>
  <!-- CSS Rules -->
    <!-- Bootstrap -->
    <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- jVectorMap -->
    <link href="assets/css/maps/jquery-jvectormap-2.0.3.css" rel="stylesheet"/>

    <!-- Custom Theme Style -->
    <link href="assets/css/custom.min.css" rel="stylesheet">
  <!-- /CSS Rules -->
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
  <?php  include 'include/js_scripts.php'; ?>
  <!-- ##########################    JAVASCRIPT     ########################## -->
</body>
</html>
