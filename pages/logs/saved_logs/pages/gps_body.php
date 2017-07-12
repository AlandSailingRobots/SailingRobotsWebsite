<?php
    require('include/dbconnection.php');
    $result = getData("gps_dataLogs");
    $pages  = getPages("gps_dataLogs");
    $number = getNumber();
    $next   = getNext();
    $prev   = getPrev();
?>
<!-- <div class="container-fluid"> -->
    <!-- <div class="row"> -->
<!--         <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li><a href="index.php">GpsData </a></li>
                <li><a href="course.php">CourseData</a></li>
                <li><a href="windsensor.php">WindSensorData</a></li>
                <li><a href="compass.php">CompassData</a></li>
                <li><a href="system.php">SystemDatalogs</a></li>
            </ul>
        </div> -->
        <div class="col-sm-9 col-md-10 ">
            <h1 class="jumbotron header">Gps Logs</h1>
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
                            <th>id_gps</th>
                            <th>time</th>
                            <th>latitude</th>
                            <th>speed</th>
                            <th>heading</th>
                            <th>satellites_used</th>
                            <th>longitude</th>
                            <th>timestamp</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 0;
                        foreach($result as $key => $row)
                        {
                            $i++;
                            echo "
                                <tr>
                                    <td>".$row["id_gps"]."</td>
                                    <td>".$row["time"]."</td>
                                    <td>".$row["latitude"]."</td>
                                    <td>".$row["speed"]."</td>
                                    <td>".$row["heading"]."</td>
                                    <td>".$row["satellites_used"]."</td>
                                    <td>".$row["longitude"]."</td>
                                    <td>".$row["Timestamp"]."</td>
                                    <td><a href=info.php?number=$i&name=id_gps&table=gps_dataLogs&id=".$row["id_gps"]." target='_blank'>display all</a></td>
                                    ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
