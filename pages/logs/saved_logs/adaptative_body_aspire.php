<?php
/* 
 * This file generates the body of the page to read saved logs from the past mission. 
 * The previous version used 5 different pages, there is only this one now. 
 */

/* Declaration of the variables for the script */
$data = $_GET['data']; // If that page is called, that variable has been set & checked
if ($boatName == 'aspire')
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
}
else
{
    echo '<h1 class="sub-header jumbotron col-md-offset-2">Error !</h1>';
}

?>

<?php
    require('include/dbconnection.php');
    $pages  = getPages($dataName, 'aspire');
    $result = getDataInverted($dataName, $pages, 'aspire');
    $number = getNumber($pages);
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
                        $pages = $pages;
                        echo '<h3>Total pages: '. $pages .'</h3>'."\n";
                        echo '<p>' . $number .  '/' . $pages .'</p><br /> '."\n";
                        # first page
                        if($number <= 1)
                        {
                            echo '<span>&laquo; prev</span> |
                                 <a href="?boat='.$boatName.'&data='.$_GET['data'].'&page='.$next.'">next &raquo;</a>';
                        }
                        # last page
                        elseif($number == ($pages ))
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
                            // This is the head of the table
                            foreach ($result[0] as $columnName => $value)
                            {
                                echo '<th>' . $columnName . '</th>'."\n";
                            }
                            echo '<th>More</th>'."\n";
                            ?>
                        </tr>
                    </thead>

                    <tbody>
                            <?php
                            // This is the table
                            $i = 0;
                            $resultSize = count($result);
                            for ($index = $resultSize - 1; $index >= 0; $index--)
                            {
                                echo '<tr>';
                                    $i++;
                                    // Each lines of the table
                                    foreach($result[$index] as $column => $value)
                                    {
                                        echo '<td>' . $value . '</td>' . "\n" ;
                                    }
                                    echo '<td>'. "\n";
                                        echo    '<a href=more_info_aspire.php?boat='.$boatName.'&number='.$i.'&name='.$column.'&table='.$dataName.'&id='.$result[$index]['id'].'>More</a>'. "\n";
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
