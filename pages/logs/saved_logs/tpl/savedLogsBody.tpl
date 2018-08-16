<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <?php // Head of the HTML document
    include $relative_path . 'include/head.php';
    ?>
    <!-- Custom CSS for the page -->
    <!-- TODO Check if it's used or not -->

    <link href="main.css" rel="stylesheet">
    <link rel="stylesheet" href="css/autoRefresh.css">
    <link href="css/dataTables.css" rel="stylesheet">
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

        <div class="container-fluid" >
            <div class="">
                <?php include 'tpl/datatableList.tpl'; ?>

            </div>

            <?php include 'tpl/datatables.tpl'; ?>
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
<script src="./../../../assets/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->

<script src="./../../../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="./../../../assets/js/custom.min.js"></script>

<script type="text/javascript" src="js/autoRefresh.js"></script>
<script src="./../../../assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" class="init">
    //$.fn.dataTable.ext.legacy.ajax = true;
    $(document).ready(function() {
        var clicked = [];

        var table = $('#example').DataTable();

        if (clicked.data != null) {


        var dataTable = $('#datatables').dataTable( {
            processing: true,
            serverSide: true,
            order: [[0,'desc']],
            ajax: {
                url: "indexNew.php",

                data: {
                    boat: $(this).attr('boat'),
                    //data: "dataLogs_gps",
                    data: clicked.data,
                    dt: true,
                    success: function(data) {
                        var successmessage = 'Data was succesfully captured';

                        console.log(successmessage)
                    }
                }
            }

        } );
        }



        //document.getElementById("dataLogList").addEventListener("click", function(event){
        $('.dtLstLnk').on('click', function (event) {
            event.preventDefault();
            var clicked = [];
            clicked.id = $(this).attr('id');
            clicked.boat = $(this).attr('boat');
            clicked.data = $(this).attr('dataLog');
            clicked.dt = $(this).attr('dt');
            //alert(clicked.id);
            //dataTable.fnClearTable();
            console.log(clicked);
            var dataTable = $('#datatables').dataTable( {
                destroy: true,
                processing: true,
                serverSide: true,
                order: [[0,'desc']],
                ajax: {
                    url: "indexNew.php",

                    data: {
                        boat: clicked.boat,
                        //data: "dataLogs_gps",
                        data: clicked.data,
                        dt: true,
                        success: function(data) {
                            var successmessage = 'Data was succesfully captured';

                            console.log(successmessage)
                        }
                    }
                }

            } );
        });


    } );


    //linky: id="70" href="#" onclick="movieInfo(this.id)


</script>


<!-- TODO Check if it's used or not  -->
<!-- <script src="jsfunctions.js"></script> -->

<!-- ##########################    JAVASCRIPT     ########################## -->
</body>
</html>
