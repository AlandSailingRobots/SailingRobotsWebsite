<div class ="row">
    <div class='panel panel-default'>
        <div class='panel-heading'>Waypoints
        </div>
        <div class="col-md-4" id = "map" >
        </div>
        <div class='panel panel-default'>
            <div class='panel-heading'>Selected Waypoint
            </div>
            <div class="input-group">
                <span class="input-group-addon">Marker Latitude:
                </span>
                <input type="text" id="latStatus" class="form-control" placeholder="Drag a marker"/>
                <span class="input-group-addon">Marker Longitude:
                </span>
                <input type="text" id="lngStatus" class="form-control" placeholder="Drag a marker"/>
                <span class="input-group-addon">Marker ID:
                </span>
                <input type="text" id="idStatus" class="form-control" placeholder="Drag a marker"/>
            </div>
            <div class='panel-heading'>Insertion settings
            </div>
            <div class="input-group">
                <span class="input-group-addon">New waypoint radius:
                </span>
                <input type="text" id="radSetting" class="form-control" value="15"/>
            </div>
        </div>
        <div class='panel panel-default'>
            <div class='panel-heading'>Waypoint list (reload to see changes)
            </div>
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