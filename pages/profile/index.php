<?php
  session_start();
  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/globalsettings.php');
  $relative_path = './../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php // Head of the HTML document
    include $relative_path . 'include/head.php';
?>
</head>

<body class="nav-md">
  <div class="container body">
    <!-- sidebar -->
    <?php include '../../include/sidebar.php'; ?>
    <!-- /sidebar -->

    <!-- top navigation -->
    <?php include '../../include/top_navigation.php'; ?>
    <!-- /top navigation -->

    <!-- page content -->
    <?php include 'profile_body.php' ?>
    <!-- /page content -->


    <!-- footer content -->
    <?php include '../../include/footer.php'; ?>
    <!-- /footer content -->
  </div>

  <!-- ##########################    JAVASCRIPT     ########################## -->
    <?php // Not very clean, but the default configs includes too many JS for a beginner
        // That way, main file is 'clean' ?>
    <?php //include '../../include/js_scripts.php'; ?>

    <!-- jQuery -->
    <script src="../../assets/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../../assets/js/custom.min.js"></script>

    <script src="js/script.js"></script>

  <!-- ##########################    JAVASCRIPT     ########################## -->
</body>
</html>
