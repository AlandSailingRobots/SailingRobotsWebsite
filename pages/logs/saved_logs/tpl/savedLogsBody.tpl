<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <?php // Head of the HTML document
    include __ROOT__ . '/include/head.php';
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
    <?php include __ROOT__ . '/include/sidebar.php'; ?>
    <!-- /sidebar -->

    <!-- top navigation -->
    <?php include __ROOT__ . '/include/top_navigation.php'; ?>
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
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script src="./../../../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="./../../../assets/js/custom.min.js"></script>

<!-- <script type="text/javascript" src="js/autoRefresh.js"></script> -->
<script src="./../../../assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" language="javascript" class="init">
    //$.fn.dataTable.ext.legacy.ajax = true;
    $(document).ready(function() {
        var clicked = [];
        var request = [];

        var url_string = window.location.href;
        var url = new URL(url_string);
        request.boat = url.searchParams.get("boat");
        request.data = url.searchParams.get("data");
        request.dt = true;
        console.log(request);

        if (request.data) {
            var url = "index.php";
            var dataTable = $('#datatables').DataTable( {
                destroy: true,
                processing: true,
                serverSide: true,
                //
                order: [[0,'desc']],
                ajax: {
                    url: url,
                    data: {
                        boat: request.boat,
                        //data: "dataLogs_gps",
                        data: request.data,
                        dt: request.dt,
                        success: function(data) {
                            var successmessage = 'Data was succesfully captured';

                            console.log(successmessage)
                        }
                    }
                }

            } );
        }




        // AUTO REFRESH
        let enabled = null;
        let timeout = 6000;
        let refreshTimer;

        $(document).ready(function () {
            CheckOpenMenu();
            init();
            updateButton();

        });

        function init () {
            if (document.cookie.indexOf("enabled") == -1 ) {
                setCookie("timeout", timeout);
                setCookie("enabled", true);
                setCookie("counter", 1);
            }
            if (document.cookie.indexOf("enabled") >= 0 && document.getElementById("dataTable")) {
                enabled = JSON.parse(getCookie("enabled"));
            } else {
                enabled = false;
            }

            updateButton();
            runTimer(enabled);

            //======= DEBUG =========
            counter = JSON.parse(getCookie("counter"));
            counter++;
            setCookie("counter", counter++);
            console.log("current cookie: ");
            console.log(document.cookie);
            //=======================

        }

        function runTimer (bool) {
            window.onload = function () {
                if (bool == true) {
                    refreshTimer = startTimer();
                } else {
                    stopTimer(refreshTimer);
                }
            }
        }

        function startTimer () {
            let timer = setInterval(function () {
                //window.location.reload();
                dataTable.ajax.reload( null, false ); // user paging is not reset on reload
            }, timeout);

            return timer;
        }

        function stopTimer (timer) {
            window.clearInterval(timer);
        }

        function toggleTimeout () {
            enabled = JSON.parse(getCookie("enabled"));

            if (enabled) {
                stopTimer(refreshTimer);
                setCookie("enabled", false);
                enabled = false;
                updateButton();
            } else {
                setCookie("enabled", true);
                enabled = true;
                updateButton();
                refreshTimer = startTimer(timeout);
            }
        }

        function updateButton () {
            if (enabled) {
                document.getElementById ("timeoutRefresh").innerText = " Auto refresh [ON]";
                document.getElementById ("timeoutRefresh").className = "fa fa-refresh";
            } else {
                document.getElementById ("timeoutRefresh").innerText = " Auto refresh [OFF]";
                document.getElementById ("timeoutRefresh").className = "fa fa-refresh fa-disabled";
            }
        }

        function CheckOpenMenu () {
            let checkClickInsideSidebarMenu = document.querySelector("#sidebar-menu");

            document.body.addEventListener('click', function (event) {
                if (checkClickInsideSidebarMenu.contains(event.target)) {
                    if (enabled) {
                        toggleTimeout ();
                    }
                }
            });
        }

        function setCookie (name, value) {
            document.cookie = name + "=" + value + ";";
        }

        function getCookie(name) {
            let value = "; " + document.cookie;
            let parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }

        document.getElementById ("timeoutBtn").onclick = function () {
            toggleTimeout ();
        };

        // NOT USED - FOLLOWING TO TEST LOADING COLUMNS ON LINK CLICK
        /**
        function generateHeaders(url, data) {
            $.getJSON(url, {dtHeaders: data},function(result){
                console.log(result);
                var key = Object.keys(result)[0];
                //console.log(Object.keys(result)[0]);
                console.log(result[key])
                $("#dtHeaders").empty();
                result[key][0].forEach(function(element) {
                    $('#dtHeaders').append('<th>' + element + '</th>');
                    console.log(element);
                });
                $.each(result, function(i, field){
                    //$("div").append(field + " ");
                });
                console.log("done header");
            });
        }


        //document.getElementById("dataLogList").addEventListener("click", function(event){
        $('.TMPdtLstLnk').on('click', function (event) {
            event.preventDefault();
            var clicked = [];
            var url = "indexNew.php";

            clicked.id = $(this).attr('id');
            clicked.boat = $(this).attr('boat');
            clicked.data = $(this).attr('dataLog');
            clicked.dt = $(this).attr('dt');
            generateHeaders(url, clicked.data);
            //alert(clicked.id);
            //dataTable.fnClearTable();
            console.log(clicked);
            if (dataTable) {
                dataTable.DataTable().destroy();
                dataTable.destroy();
                dataTable.empty();
            }
            var dataTable = $('#datatables').dataTable( {
                //destroy: true,
                processing: true,
                serverSide: true,
                //
                order: [[0,'desc']],
                ajax: {
                    url: url,
                    data: {
                        boat: clicked.boat,
                        //data: "dataLogs_gps",
                        data: clicked.data,
                        dt: clicked.dt,
                        success: function(data) {
                            var successmessage = 'Data was succesfully captured';

                            console.log(successmessage)
                        }
                    }
                }

            } );
        });
        **/

    } );




</script>


<!-- TODO Check if it's used or not  -->
<!-- <script src="jsfunctions.js"></script> -->

<!-- ##########################    JAVASCRIPT     ########################## -->
</body>
</html>
