<div id='table' class='col-xs-12 col-lg-4'>
    <table class="table table-striped">
    <tbody>
    <?php
        if(isset($_GET["id"]) && isset($_GET["name"]) && isset($_GET["table"]) && $_GET["number"])
        {
            $id = $_GET["id"];
            $name = $_GET["name"];
            $table = $_GET["table"];
            $number = $_GET["number"];
            // $_SESSION['number'] = $number;
            // $_SESSION['idd'] = $id;
            // $_SESSION['name'] = $name;
            // $_SESSION['table'] = $table;
            $result = getAll($id, $name, $table);
            if (!empty($result))
            {
                foreach ($result[0] as $key => $value) 
                {
                    if (is_string($key))
                    {
                        echo '<tr>';
                            echo '<th>' . $key . '</th>';
                            echo '<td>' .  $value . '</td>';
                        echo '</tr>';
                    }
                }
            }
            else
            {
            ?>  
                <tr>
                    <td>ERROR:</td>
                    <td>Table empty; gps_datalogs id does not have a corresponding system_dataLogs entry</td>
                </tr>
            <?php
            }
        }
        else 
        {
        ?>
            <tr>
                <td>ID | TABLE | NAME | NUMBER has not been set</td>
            </tr>
        <?php
        }
    ?>

    </tbody>
    </table>
</div>

<div class="col-xs-12 col-lg-offset-1 col-lg-7 embed-responsive embed-responsive-16by9">
<!--
    <div id='mapbtn'>
        <input type="button" class="btn btn-success" value="maps/boat" onclick="hideShowMapBoat()" />
    </div>
    <label for="usr">Route range: </label>
    <div class="input-group">
        <input type="text" id="startPath" onChange="updatePath()" class="form-control" placeholder="Start"/>
        <span class="input-group-addon">-</span>
        <input type="text" id="endPath" onChange="updatePath()" class="form-control" placeholder="End"/>
    </div>
    <div id='map'>
    </div>
    <div id='boatCanvas'>
        <canvas width='900px' height='900px' id='pingCanvas' ></canvas>
        <canvas width='900px' height='900px' id='layerCanvas'></canvas>
        <canvas width='900px' height='900px' id='layerHeading'></canvas>
        <canvas width='900px' height='900px' id='layerTWD'></canvas>
        <canvas width='900px' height='900px' id='layerWaypoint'></canvas>
        <canvas width='900px' height='900px' id='layerCompasHeading'></canvas>
        <canvas width='900px' height='900px' id='layerBoatHeading'></canvas>
    </div> 
-->
        <!-- width="auto"  -->
        <!-- height="auto" -->
        <!-- size="100%" -->
    <div class="embed-responsive-item">
        <canvas id='pingCanvas'><p>Sorry, your Internet Browser does not support the canvas tag. Please update it !</p></canvas>
        <canvas id='layerCanvas'></canvas>
        <canvas id='layerHeading'></canvas>
        <canvas id='layerTWD'></canvas>
        <canvas id='layerWaypoint'></canvas>
        <canvas id='layerCompasHeading'></canvas>
        <canvas id='layerBoatHeading'></canvas>       
    </div>
    <iframe
        class="embed-responsive-item"
        frameborder="0" 
        scrolling="no" 
        marginheight="0" 
        marginwidth="0"
        <?php
            // That iframe is the embedded openstreetmap. It uses Leaflet. Maybe I should use it directly.
            $lat_marker = $result[0]['latitude'];
            $lon_marker = $result[0]['longitude'];
            $x_min = $lon_marker - 0.2;
            $x_max = $lon_marker + 0.2;
            $y_min = $lat_marker - 0.2;
            $y_max = $lat_marker + 0.2;
        ?>
        src=<?php echo '"https://www.openstreetmap.org/export/embed.html?bbox=' . $x_min . '%2C' . $y_min . '%2C' . $x_max . '%2C' . $y_max . '&amp;layer=mapnik&amp;marker=' . $lat_marker . '%2C' . $lon_marker . '"'; ?> 
        style="border: 1px solid black">
    </iframe>
    <br/>
    <small>
        <a href=<?php echo 'https://www.openstreetmap.org/?mlat=' . $lat_marker . '&amp;mlon=' . $lon_marker . '#map=16/' . $lat_marker .'/'. $lon_marker . '"' ?>>View Larger Map</a>
    </small>
</div>