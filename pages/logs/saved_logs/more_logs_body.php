<div id='table' class='col-lg-4'>
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