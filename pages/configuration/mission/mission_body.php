<?php
require_once('include/getMissionList.php');
require_once('include/getPointList.php');
require_once('include/insertWaypoint.php');
require_once('include/updateWaypoints.php');
require_once('include/printWaypointList.php');
?>
<!-- TENTATIVE 1 -->
<!-- <div class="row myrow">
    <div class="form-group col-xs-12 col-md-5" >
        <label for="mission">Please select your mission:</label>
        <select class="form-control" id="selectMission" data-live-search="true"> -->
            <?php 
            /* TODO : Insert PHP code here ! */

            $missionList = getMissionList();
            if (empty($missionList))
            {
                echo '<option id="0" > You don\'t have any saved mission yet. </option>';
            }
            foreach ($missionList as $aMission)
            {
                echo '<option id='.$aMission['id'] .'>' . $aMission['id'] . ' – ' . $aMission['name'] . '</option>';
            }
            ?>
<!--         </select>
    </div> 
    <button type="button" class="btn btn-primary col-xs-12 col-md-offset-1 col-md-2 ">Add Mission</button>
    <button type="button" class="btn btn-danger col-xs-12 col-md-offset-1 col-md-2 disabled">Delete A Mission </button>
</div> -->

<!-- TENTATIVE 3 -->
<div class="row myrow">
    <div class="form-group col-xs-12 col-md-5" >
        <label for="mission">Please select your mission:</label>
        <select class="selectpicker" id="missionSelection" title="Not Selected" placeholder="mission" data-live-search="true">
            <option id="0" selected > Choose a mission </option>
            <!-- <optgroup label="Mission" class="missionSelection"> -->
            <?php 
            /* TODO : Insert PHP code here ! */

            $missionList = getMissionList();
            if (empty($missionList))
            {
                echo '<option id="-1" > You don\'t have any saved mission yet. </option>';
            }
            foreach ($missionList as $aMission)
            {
                echo '<option data_token="'.$aMission['id']. '" id="'.$aMission['id'] .'">' . $aMission['id'] . ' – ' . $aMission['name'] . '</option>';
            }
            ?>
            <!-- </optgroup> -->
        </select>
    </div>
    <button type="button" class="btn btn-primary col-xs-12 col-md-offset-1 col-md-2 " id="addMission" href="#">Add Mission</button>
    <button type="button" class="btn btn-danger col-xs-12 col-md-offset-1 col-md-2 disabled" id="deleteMission" >Delete A Mission </button>
    <input type="button" value="Display selected mission" onclick="check();" />
    <span id="messageMission" class="messageMission">
    </span>
</div>
<div id="r">
</div>
<!-- MODAL PART -->
<div class="modal fade" id="infos" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
          <h4 id="modalTitle" class="modal-title">Confirmation</h4>
        </div>
        <div class="modal-body">
            <p>Do you really want to delete this mission from the Database ? This action is irreversible.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="cancelDeleteButton">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteButton">Confirm</button>
        </div>
      </div>
    </div>
</div>

<div class="row">
    <!-- <div id="map" class="col-lg-5 embed-responsive embed-responsive-16by9 leaflet-container leaflet-fade-anim leaflet-grab leaflet-touch-drag"> -->
    <!-- <div id="map" class="pagination-centered col-xs-12 col-md-5"> -->
    </div>

    <!-- Previous code  -->
    <!-- <div class='panel panel-default col-xs-12 col-md-offset-1 col-md-6'> -->
<!--         <div class='panel-heading'>Selected Waypoint
        <div class="input-group row">
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
        </div>
        <div class='panel-heading'>Insertion settings
        </div>
        <div class="input-group">
            <span class="input-group-addon">New waypoint radius:
            </span>
            <input type="text" id="radSetting" class="form-control" value="15"/>
        </div>
    </div> -->
</div>


 
<!-- <div class ="row">
    <div class='panel panel-default'>
        <div class='panel-heading'>Waypoints
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
 -->
