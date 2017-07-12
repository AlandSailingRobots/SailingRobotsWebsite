<?php session_start();
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Log</title>

    <!-- Bootstrap core CSS -->
    <link href="Dashboard%20Template%20for%20Bootstrap_files/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="Dashboard%20Template%20for%20Bootstrap_files/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="Dashboard%20Template%20for%20Bootstrap_files/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="Dashboard%20Template%20for%20Bootstrap_files/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="main.css">
    <script src="../libs/jquery/jquery-1.11.1.min.js"></script>
    <script src="../libs/DataTables-1.10.0/media/js/jquery.dataTables.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
    <script src="../libs/markerFunctions.js"></script>
    <script src='../libs/utility.js'></script>
    <script src="jsfunctions.js"></script>
</head>
<body>

  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Log</a>
        <a class="navbar-brand" href="http://localhost/Remote-sailing-robots/live/">Live</a>
        <a class="navbar-brand" href="http://localhost/Remote-sailing-robots/config/">Config</a>
      </div>
    </div>
  </nav>
<div id='table'>

<table class="table table-striped">
<thead>
  <tr>
    <th>id_system</th>
    <th>boat_id</th>
    <th>sail_command_sail</th>
    <th>rudder_command_rudder</th>
    <th>sail_servo_position</th>
    <th>rudder_servo_position</th>
    <th>waypoint_id</th>
    <th>true_wind_direction_calc</th>
    <th>id_gps</th>
    <th>time</th>
    <th>latitude</th>
    <th>speed</th>
    <th>heading</th>
    <th>satellites_used</th>
    <th>longitude</th>
    <th>id_course_calculation</th>
    <th>distance_to_waypoint</th>
    <th>bearing_to_waypoint</th>
    <th>course_to_steer</th>
    <th>tack</th>
    <th>going_starboard</th>
    <th>id_windsensor</th>
    <th>direction</th>
    <th>speed</th>
    <th>temperature</th>
    <th>id_compass_model</th>
    <th>heading</th>
    <th>pitch</th>
    <th>roll</th>
  </tr>
</thead>
<tbody>
<?php

  require('dbconnection.php');
  if(isset($_GET["id"]))
  {
    $id = $_GET["id"];
    $name = $_GET["name"];
    $table = $_GET["table"];
    $number = $_GET["number"];
    $_SESSION['number'] = $number;
    $_SESSION['id'] = $id;
    $_SESSION['name'] = $name;
    $_SESSION['table'] = $table;
    $result = getAll($id, $name, $table);
    if (!empty($result)){

        foreach($result as $key => $row)
        {
          echo "
            <tr>
              <td>".$row["id_system"]."</td>
              <td>".$row["boat_id"]."</td>
              <td>".$row["sail_command_sail"]."</td>
              <td>".$row["rudder_command_rudder"]."</td>
              <td>".$row["sail_servo_position"]."</td>
              <td>".$row["rudder_servo_position"]."</td>
              <td>".$row["waypoint_id"]."</td>
              <td>".$row["true_wind_direction_calc"]."</td>
              <td>".$row["id_gps"]."</td>
              <td>".$row["time"]."</td>
              <td>".$row["latitude"]."</td>
              <td>".$row["speed"]."</td>
              <td>".$row["heading"]."</td>
              <td>".$row["satellites_used"]."</td>
              <td>".$row["longitude"]."</td>
              <td>".$row["id_course_calculation"]."</td>
              <td>".$row["distance_to_waypoint"]."</td>
              <td>".$row["bearing_to_waypoint"]."</td>
              <td>".$row["course_to_steer"]."</td>
              <td>".$row["tack"]."</td>
              <td>".$row["going_starboard"]."</td>
              <td>".$row["id_windsensor"]."</td>
              <td>".$row["direction"]."</td>
              <td>".$row["speed"]."</td>
              <td>".$row["temperature"]."</td>
              <td>".$row["id_compass_model"]."</td>
              <td>".$row["heading"]."</td>
              <td>".$row["pitch"]."</td>
              <td>".$row["roll"]."</td>
              </tr>";

        }
    }else{
        echo "
          <tr>
            <td>ERROR:</td>
            <td>Table empty; gps_datalogs id does not have a corresponding system_dataLogs entry</td>
            </tr>";

    }

    //session_destroy();
  }
  else {
    echo "
      <tr>
        <td>NOTHING</td>
        </tr>";
  }

?>

</tbody>
</table>
</div>
  <div class="col-md-5">
    <div id='mapbtn'>
      <input type="button" class="btn btn-success" value="maps/boat" onclick="hideShowMapBoat()" />
    </div>
    <label for="usr">Route range: </label>
    <div class="input-group">
        <input type="text" id="startPath" onChange="updatePath()" class="form-control" placeholder="Start"/>
        <span class="input-group-addon">-</span>
        <input type="text" id="endPath" onChange="updatePath()" class="form-control" placeholder="End"/>
    </div>
    <div id='map'></div>
    <div id='boatCanvas'>
      <canvas width='900px' height='900px' id='pingCanvas' ></canvas>
       <canvas width='900px' height='900px' id='layerCanvas'></canvas>
       <canvas width='900px' height='900px' id='layerHeading'></canvas>
       <canvas width='900px' height='900px' id='layerTWD'></canvas>
       <canvas width='900px' height='900px' id='layerWaypoint'></canvas>
       <canvas width='900px' height='900px' id='layerCompasHeading'></canvas>
       <canvas width='900px' height='900px' id='layerBoatHeading'></canvas>
    </div>
  </div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="Dashboard%20Template%20for%20Bootstrap_files/jquery.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="Dashboard%20Template%20for%20Bootstrap_files/bootstrap.js"></script>
<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="Dashboard%20Template%20for%20Bootstrap_files/holder.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="Dashboard%20Template%20for%20Bootstrap_files/ie10-viewport-bug-workaround.js"></script>


</body></html>
