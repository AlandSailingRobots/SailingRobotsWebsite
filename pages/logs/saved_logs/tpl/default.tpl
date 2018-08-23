<!DOCTYPE html>
<html lang="en">
<head>
    <?php // Head of the HTML document
    include $relative_path . 'include/head.php';
?>
    <!-- Custom CSS for the page -->
    <!-- TODO Check if it's used or not -->

    <link href="main.css" rel="stylesheet">
    <link rel="stylesheet" href="css/autoRefresh.css">
    <!-- <meta http-equiv="refresh" content="6" > <!-- SESSION COOKIE controls this with JavaScript-->
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
    <?php echo $message ?>
    </div>
<!-- /data content -->

<!-- footer content -->
<?php include $relative_path . 'include/footer.php'; ?>
<!-- /footer content -->
</div>

<!-- ##########################    JAVASCRIPT     ########################## -->
<?php // Not very clean, but the default configs includes too many JS for a beginner
        // That way, main file is 'clean' ?>
<?php  #include $relative_path . 'include/js_scripts.php'; ?>
<!-- jQuery -->
<script src=<?php echo $relative_path . "assets/vendors/jquery/dist/jquery.min.js"?>></script>
<!-- Bootstrap -->
<script src=<?php echo $relative_path . "assets/vendors/bootstrap/dist/js/bootstrap.min.js"?>></script>
<!-- Custom Theme Scripts -->
<script src=<?php echo $relative_path . "assets/js/custom.min.js"?>></script>

<script type="text/javascript" src="js/autoRefresh.js">


</script>
<!-- TODO Check if it's used or not  -->
<!-- <script src="jsfunctions.js"></script> -->

<!-- ##########################    JAVASCRIPT     ########################## -->
</body>
</html>
