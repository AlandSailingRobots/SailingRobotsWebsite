<?php
    /* This file generates the body of the page to read saved logs from the past mission. 
     * The previous version used 5 different pages, there is only this one now. 
     */

    /* Declaration of the variables for the script */
    $data = $_GET['data']; // If that page is called, that variable has been set & checked
    if ($data === 'gps')
    {
        $list_columns = array('id_gps', 
                                'time', 
                                'latitude', 
                                'speed', 
                                'heading', 
                                'satellites_used', 
                                'longitude', 
                                'Timestamp'
                            );
        $page_title = 'GPS Logs';
    }
    elseif ($data === 'course')
    {
        $list_columns = array('id_course_calculation',
                                'distance_to_waypoint',
                                'bearing_to_waypoint',
                                'course_to_steer', 
                                'tack', 
                                'going_starboard'
                            );
        $page_title = 'Courses Logs';
        $data = 'course_calculation';   // Because the previous developper did not 
                                        // call that DB table the same way...
    }
    elseif ($data === 'windsensor')
    {
        $list_columns = array('id_windsensor',
                                'direction',
                                'speed',
                                'temperature'
                            );
        $page_title = 'Windsensor Logs';
    }
    elseif ($data === 'compass')
    {
        $list_columns = array('id_compass_model',
                                'heading',
                                'pitch',
                                'roll'
                            );
        $page_title = 'Compass Data';
    }
    elseif ($data === 'system')
    {
        $list_columns = array('id_system',
                                'boat_id',
                                'sail_command_sail',
                                'rudder_command_rudder',
                                'sail_servo_position',
                                'rudder_servo_position'
                            );
        $page_title = 'System Data';
    }
    else
    {
        echo '<h1 class="sub-header jumbotron">Error !</h1>';
    }

?>

<?php
    require('include/dbconnection.php');
    $pages  = getPages($data . '_dataLogs');
    $result = getData($data . '_dataLogs', $pages);
    $number = getNumber($pages);
    $next   = $number + 1;//getNext($data . '_dataLogs');
    $prev   = $number - 1;// getPrev($data . '_dataLogs');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-md-10 main">
        <h1 class="sub-header jumbotron"><?php echo $page_title ;?></h1>
            <div class="table-responsive">
                <table class="table table-striped">
                    <?php
                    if($result && count($result) > 0)
                    {
                        $pages = $pages;
                        echo '<h3>Total pages: '. $pages .'</h3>';
                        echo '<p>' . $number .  '/' . $pages .'<br /> </p>';
                        # first page
                        if($number <= 1)
                        {
                            echo '<span>&laquo; prev</span> |
                                 <a href="?data='.$_GET['data'].'&page='.$next.'">next &raquo;</a>';
                        }
                        # last page
                        elseif($number == ($pages ))
                        {
                            echo '<a href="?data='.$_GET['data'].'&page='.$prev.'">&laquo; prev</a> |
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
                            <?php 
                                foreach ($list_columns as $column)
                                {
                                    echo '<th>' . $column . '</th>';
                                }
                            ?>
                        </tr>
                    </thead>

                    <tbody>
                            <?php
                                $i = 0;
                                foreach($result as $key => $row)
                                {
                                    echo '<tr>';
                                        $i++;
                                        foreach($list_columns as $column)
                                        {
                                            echo '<td>' . $row[$column] . '</td>' ;
                                        }
                                        echo '<td>';
                                            echo    '<a href=more_info.php?number='.$i.'&name='.$list_columns[0].'&table='.$data.'_dataLogs&id='.$row[0].'>More</a>';
                                        echo '</td>';
                                    echo '</tr>';
                                }
                            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
