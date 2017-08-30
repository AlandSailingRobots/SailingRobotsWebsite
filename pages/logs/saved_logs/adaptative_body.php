<?php
/* 
 * This file generates the body of the page to read saved logs from the past mission. 
 * The previous version used 5 different pages, there is only this one now. 
 */

/* Declaration of the variables for the script */
require('include/dbconnection.php');
$data = $_GET['data']; // If that page is called, that variable has been set & checked

if ($boatName == 'janet')
{   
    // List_column is used to only select some column of the db and not all.
    // That choice has not been made for aspire logs.
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
    $dataName = $data . '_dataLogs';
    if ($data == 'actuator_feedback')
    {
        $page_title = 'Actuator Feedback';
        $dataName = 'dataLogs_' . $data;
        $list_columns = array('id',
                                'rudder_position',
                                'wingsail_position',
                                'rc_on',
                                'wind_vane_angle',
                                'time_stamp'
                            );
    }
    elseif ($data == 'marine_sensors') 
    {
        $page_title = "Marine Sensors Measurements";
        $dataName = 'dataLogs_' . $data;
        $list_columns = array('id',
                                'temperature',
                                'conductivity',
                                'ph',
                                'time_stamp'
                            );
    }

    $pages  = getPages($dataName, 'janet');
    $result = getDataFromDB($dataName, $pages, 'janet');
}
elseif ($boatName == 'aspire')
{
    if ($data === 'gps')
    {
        $page_title = 'GPS Logs';
    }
    elseif ($data === 'course')
    {
        $page_title = 'Courses Logs';
        $data = 'course_calculation';   // Because the previous developper did not 
                                        // call that DB table the same way...
    }
    elseif ($data === 'windsensor')
    {
        $page_title = 'Windsensor Logs';
    }
    elseif ($data === 'compass')
    {
        $page_title = 'Compass Data';
    }
    elseif ($data === 'system')
    {
        $page_title = 'System Data';
    }
    elseif ($data == 'actuator_feedback')
    {
        $page_title = 'Actuator Feedback';
    }
    elseif ($data == 'marine_sensors') 
    {
        $page_title = "Marine Sensors Measurements";
    }
    elseif ($data == 'current_sensors')
    {
        $page_title = 'Current Sensors Logs';
    }
    elseif ($data == 'vessel_state')
    {
        $page_title = 'Vessel State Logs';
    }
    elseif ($data == 'wind_state')
    {
        $page_title = 'Wind State Logs';
    }

    $dataName = 'dataLogs_' . $data;

    $pages  = getPages($dataName, 'aspire');
    $result = getDataFromDB($dataName, $pages, 'aspire');
}
else
{
    echo '<h1 class="sub-header jumbotron col-md-offset-2">Error !</h1>';
}


?>

<?php
    $number =  isset($_GET['page']) ? $_GET['page'] : 1;
    $next   = $number + 1;
    $prev   = $number - 1;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-md-10 main">
        <h1 class="sub-header jumbotron"><?php echo $page_title ;?></h1>
            <div class="table-responsive">
                    <?php
                    if($result && count($result) > 0)
                    {
                        echo '<h3>Total pages: '. $pages .'</h3>'."\n";
                        echo '<p>' . $number .  '/' . $pages .'</p><br /> '."\n";
                        
                        if ($pages == 1)
                        {
                            echo '<span>&laquo; prev | next &raquo;</span>';
                        }
                        # first page
                        elseif($number <= 1)
                        {
                            echo '<span>&laquo; prev</span> |
                                 <a href="?boat='.$boatName.'&data='.$_GET['data'].'&page='.$next.'">next &raquo;</a>';
                        }
                        # last page
                        elseif($number == $pages)
                        {
                            echo '<a href="?boat='.$boatName.'&data='.$_GET['data'].'&page='.$prev.'">&laquo; prev</a> |
                                 <span>next &raquo;</span>';
                        }
                        # in range
                        else
                        {
                            echo '<a href="?boat='.$boatName.'&data='.$_GET['data'].'&page='.$prev.'">&laquo; prev</a> |
                                  <a href="?boat='.$boatName.'&data='.$_GET['data'].'&page='.$next.'">next &raquo;</a>';
                        }
                    }
                    else
                    {
                        echo "<p>No results found.</p>";
                    }
                    ?>
                    <table class="table table-striped">
                    <thead>
                        <tr>
                            <?php 
                            if($boatName == 'janet')
                            {
                                foreach ($list_columns as $column)
                                {
                                    echo '<th>' . $column . '</th>'."\n";
                                }
                            }
                            elseif ($boatName == 'aspire')
                            {
                                // This is the head of the table
                                foreach ($result[0] as $columnName => $value)
                                {
                                    echo '<th>' . $columnName . '</th>'."\n";
                                }
                            }
                            echo '<th>More</th>'."\n";
                            ?>
                        </tr>
                    </thead>

                    <tbody>
                            <?php
                            $i = 0;
                            $resultSize = count($result);
                            // print_r($result[0]['id_gps']);
                            for ($index = $resultSize - 1; $index >= 0; $index--)
                            {
                                echo '<tr>';
                                $i++;
                                // Each lines of the table
                                if ($boatName == 'janet')
                                {
                                    foreach($list_columns as $column)
                                    {
                                        echo '<td>' . $result[$index][$column] . '</td>' . "\n" ;
                                    }
                                    echo '<td>'. "\n";
                                        echo    '<a href=more_info.php?boat='.$boatName.'&number='.$i.'&name='.$list_columns[0].'&table='.$dataName.'&id='.$result[$index][$list_columns[0]].'>More</a>'. "\n";
                                    
                                }
                                elseif($boatName == 'aspire')
                                {
                                    foreach($result[$index] as $column => $value)
                                    {
                                        echo '<td>' . $value . '</td>' . "\n" ;
                                    }
                                    echo '<td>'. "\n";
                                        echo    '<a href=more_info_aspire.php?boat='.$boatName.'&number='.$i.'&name='.$column.'&table='.$dataName.'&id='.$result[$index]['id'].'>More</a>'. "\n";                                           
                                }
                                    echo '</td>'. "\n";
                                echo '</tr>'. "\n";
                                }
                            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
