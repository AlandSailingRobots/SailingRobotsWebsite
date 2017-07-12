<!-- <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Log</a>
            <a class="navbar-brand" href="http://sailingrobots.com/testdata/live/">Live</a>
            <a class="navbar-brand" href="http://sailingrobots.com/testdata/config/">Config</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php">GpsData</a></li>
                <li><a href="course.php">CourseData</a></li>
                <li><a href="windsensor.php">WindSensorData</a></li>
                <li><a href="compass.php">CompassData</a></li>
                <li><a href="system.php">SystemDatalogs</a></li>
            </ul>
        </div>
    </div>
</nav> -->

<?php
    require('include/dbconnection.php');
    $result = getData("windsensor_dataLogs");
    $pages = getPages("windsensor_dataLogs");
    $number = getNumber();
    $next = getNext();
    $prev = getPrev();
?>
<div class="container-fluid">
    <div class="row">
<!--         <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li ><a href="index.php">GpsData </a></li>
                <li><a href="course.php">CourseData</a></li>
                <li><a href="windsensor.php">WindSensorData</a></li>
                <li><a href="compass.php">CompassData</a></li>
                <li><a href="system.php">SystemDatalogs</a></li>
            </ul>
        </div> -->
        <div class="col-sm-9 col-md-10 main">
            <h2 class="sub-header">Windsensor Data</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <?php

                    if($result && count($result) > 0)
                    {
                        echo "<h3>Total pages ($pages)</h3>";
                        # first page
                        if($number <= 1)
                        {
                            echo '<span>&laquo; prev</span> |
                                 <a href="?data='.$_GET['data'].'&page='.$next.'">next &raquo;</a>';
                        }
                        # last page
                        elseif($number >= $pages)
                        {
                            echo '<a href="?data='.$_GET['data'].'&page='.$prev.'>&laquo; prev</a> |
                                 <span>next &raquo;</span';
                        }
                        # in range
                        else
                        {
                            echo '<a href="?data='.$_GET['data'].'&page='.$prev.'">&laquo; prev</a> |
                                  <a href="?data='.$_GET['data'].'&page='.$next.'">next &raquo;</a>';
                        }
                    }
                    else
                    {
                        echo "<p>No results found.</p>";
                    }
                    ?>
                    <thead>
                        <tr>
                            <th>id_windsensor</th>
                            <th>direction</th>
                            <th>speed</th>
                            <th>temperature</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i=0;
                        foreach($result as $key => $row)
                        {
                            $i++;
                            echo "
                                <tr>
                                    <td>".$row["id_windsensor"]."</td>
                                    <td>".$row["direction"]."</td>
                                    <td>".$row["speed"]."</td>
                                    <td>".$row["temperature"]."</td>
                                    <td><a href=info.php?number=$i&name=id_windsensor&table=windsensor_dataLogs&id=".$row["id_windsensor"]." target='_blank'>display all</a></td>";

                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
