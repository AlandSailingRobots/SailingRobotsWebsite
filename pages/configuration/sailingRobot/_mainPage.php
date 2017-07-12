<?php session_start();?>
<?php
    if(!isset($_SESSION['use']))  { // If session is not set that redirect to Login Page
         header("Location:index.php");
     }
?>
<!DOCTYPE html>
<!-- This site is supposed to send configs and waypoint's trought the sync to the boat.
    You can send configs but not waypoint's and the password check is working but we have had some
    problems calling it. -->


<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">


  <?php
    require('configurationData.php');
    require('printTables.php');
    require('printWaypointList.php');
    require('../live/dbconnection.php');
  ?>

  <script type="text/javascript" src="js/activateForms.js"></script>



    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <script src="../libs/jquery/jquery-1.11.1.min.js"></script>
    <script src="../libs/DataTables-1.10.0/media/js/jquery.dataTables.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="icon" href="https://image.freepik.com/free-icon/sailing-boat_318-54194.png">
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
    <script src="../libs/markerFunctions.js"></script>
    <script src="../libs/utility.js"></script>
    <script src="js/jsfunctions.js"></script>

    <link rel="stylesheet" type="text/css" href="css/main.css">

    <title>Config</title>

    <!-- Bootstrap core CSS -->
    <link href="Starter%20Template%20for%20Bootstrap_files/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="Starter%20Template%20for%20Bootstrap_files/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="Starter%20Template%20for%20Bootstrap_files/starter-template.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="Starter%20Template%20for%20Bootstrap_files/ie-emulation-modes-warning.js"></script>
    <script type="text/javascript" src="js/ajaxFormSubmit.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
      <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://sailingrobots.ax/">Sailing Robots</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li ><a href="http://www.sailingrobots.com/testdata/live/">Live</a></li>
            <li class="active"><a href="http://www.sailingrobots.com/testdata/config">Config</a></li>
            <li ><a href="http://www.sailingrobots.com/testdata/log/">Log</a></li>
          </ul>
          <form action="logout.php" class="navbar-form navbar-right">
            <button type="submit" class="btn btn-danger">Log out</button>
          </form>
        </div>

      </div>
    </nav>



    <div class="container">

      <div class="starter-template">
        <div class="jumbotron">
        <h1>Configuration page</h1>
          <p>On this page you can see and update the configurations for Aland Sailing Robots.</p>
        </div>
        <br><br><br>



      <div class="row">
        <?php
          $bufferConfigArray = getConfigData("buffer_config");
          $courseCalculationConfigArray = getConfigData("course_calculation_config");
          $maestroControllerConfigArray = getConfigData("maestro_controller_config");
          $rudderCommandConfigArray = getConfigData("rudder_command_config");
          $rudderServoConfigArray = getConfigData("rudder_servo_config");
          $sailCommandConfigArray = getConfigData("sail_command_config");
          $sailServoConfigArray = getConfigData("sail_servo_config");
          $sailingRobotConfigArray = getConfigData("sailing_robot_config");
          $waypointRoutingConfigArray = getConfigData("waypoint_routing_config");
          $windVaneConfigArray = getConfigData("wind_vane_config");
          $windsensorConfigArray = getConfigData("windsensor_config");
          $xbeeConfigArray = getConfigData("xbee_config");
          $httpSyncConfigArray = getConfigData("httpsync_config");
        ?>
    <div class="row">
        <?php
            printTables($bufferConfigArray, "buffer_config");
            printTables($courseCalculationConfigArray, "course_calculation_config");
            printTables($maestroControllerConfigArray, "maestro_controller_config");

        ?>
    </div>

    <div class="row">
        <?php
            printTables($rudderCommandConfigArray, "rudder_command_config");
            printTables($rudderServoConfigArray, "rudder_servo_config");
            printTables($sailCommandConfigArray, "sail_command_config");
        ?>
    </div>
    <div class="row">
        <?php
            printTables($sailServoConfigArray, "sail_servo_config");
            printTables($sailingRobotConfigArray, "sailing_robot_config");
            printTables($waypointRoutingConfigArray, "waypoint_routing_config");
        ?>
    </div>
    <div class="row">
        <?php
            printTables($windVaneConfigArray, "wind_vane_config");
            printTables($windsensorConfigArray, "windsensor_config");
            printTables($xbeeConfigArray, "xbee_config");
        ?>
    </div>
    <div class="row">
        <?php
            printTables($httpSyncConfigArray, "httpsync_config");
        ?>
        <div class="col-md-2">
          <input type='button' value='Submit Configs' class='btn btn-success col-md-12' onclick='submitAllForms()'/>
          <br>
        </div>
    </div>
    <div class ="row">
    <div class='panel panel-default'>
    <div class='panel-heading'>Waypoints</div>

            <div class="col-md-4" id = "map" ></div>
            <div class='panel panel-default'>
                <div class='panel-heading'>Selected Waypoint</div>
                <div class="input-group">
                    <span class="input-group-addon">Marker Latitude: </span>
                    <input type="text" id="latStatus" class="form-control" placeholder="Drag a marker"/>
                    <span class="input-group-addon">Marker Longitude: </span>
                    <input type="text" id="lngStatus" class="form-control" placeholder="Drag a marker"/>
                    <span class="input-group-addon">Marker ID: </span>
                    <input type="text" id="idStatus" class="form-control" placeholder="Drag a marker"/>
                </div>
                <div class='panel-heading'>Insertion settings</div>
                <div class="input-group">
                    <span class="input-group-addon">New waypoint radius: </span>
                    <input type="text" id="radSetting" class="form-control" value="15"/>
                </div>
            </div>

            <div class='panel panel-default'>
                <div class='panel-heading'>Waypoint list (reload to see changes)</div>
                <?php
                    printWaypointList();
                    //$waypointString = json_encode($getWaypointsService->getWaypoints());
                    //echo "<div>.$waypointString.</div>";
                ?>
            </div>
            <div class="col-md-4 col-md-offset-1">
                <input type='button' value='Undo changes' class='btn btn-danger btn-lg' onclick='reloadPage()'/>
                <br>
            </div>
            <div class="col-md-4 col-md-offset-3">
              <input type='button' value='Submit waypoint changes' class='btn btn-success btn-lg' onclick='waypointsToDatabase()'/>
              <br>
            </div>


    </div>
    </div>

      <br>


        </div>
          <br><br><br><br><br><br>
    </div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="Starter%20Template%20for%20Bootstrap_files/jquery.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="Starter%20Template%20for%20Bootstrap_files/bootstrap.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="Starter%20Template%20for%20Bootstrap_files/ie10-viewport-bug-workaround.js"></script>

</body>
</html>
