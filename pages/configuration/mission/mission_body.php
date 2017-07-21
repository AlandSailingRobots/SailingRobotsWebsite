<?php
require_once('php/getMissionList.php');
require_once('php/getPointList.php');
require_once('php/insertWaypoint.php');
require_once('php/updateWaypoints.php');
require_once('php/printWaypointList.php');
?>

<!-- Mission Selection -->
<div class="row myrow" id="missionSelector">
    <div class="form-group col-xs-12 col-md-5"  >
        <label for="mission">Please select your mission:</label>
        <select class="selectpicker" id="missionSelection" title="Not Selected" placeholder="mission" data-live-search="true">
            <option id="0" selected > Choose a mission </option>
            <!-- <optgroup label="Mission" class="missionSelection"> -->
            <?php 
            $missionList = getMissionList();
            if (empty($missionList))
            {
                echo '<option id="0" > You don\'t have any saved mission yet. </option>';
            }
            foreach ($missionList as $aMission)
            {
                echo '<option data_token="'.$aMission['id']. '" id="'.$aMission['id'] .'">' . $aMission['id'] . ' – ' . $aMission['name'] . '</option>';
            }
            ?>
            <!-- </optgroup> -->
        </select>
    </div>
    <button type="button" class="btn btn-primary col-xs-12 col-md-offset-1 col-md-2 " id="createMissionButton" >Create Mission</button>
    <button type="button" class="btn btn-danger col-xs-12 col-md-offset-1 col-md-2 disabled" id="deleteMissionButton" >Delete Mission</button>
    <input type="button" value="Display selected mission" onclick="check();" />
    <span id="messageMission" class="messageMission">
    </span>
</div>

<!-- DELETE CONFIRMATIOn MODAL PART -->
<div class="modal fade" id="deleteConfirmationModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
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

<!-- ADD MISSION MODAL PART -->
<div class="modal fade" id="createMissionModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
          <h4 id="modalTitle" class="modal-title">New Mission</h4>
        </div>
        <div class="modal-body">
            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="Name" >Name</label>  
              <input id="newMissionName" name="Name" placeholder="ex: Sailing Test" class="form-control input-md" required="1" type="text" >
              <span class="help-block">Give the mission a name to help you find it in the list.</span>  
            </div>

            <!-- Textarea -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="description">Description</label>
              <textarea class="form-control" id="newMissionDescription" name="description" placeholder="Optional" ></textarea>
              <span class="help-block">Give the mission a description to help you remember its purpose.</span>  
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="cancelCreateButton">Cancel</button>
            <button type="button" class="btn btn-success" id="confirmCreateButton">Create New Mission</button>
        </div>
      </div>
    </div>
</div>

<br />
<br />

<div class="row top15">
    <!-- <div id="map" class="col-lg-5 embed-responsive embed-responsive-16by9 leaflet-container leaflet-fade-anim leaflet-grab leaflet-touch-drag"> -->

    <!-- HERE IS THE MAP -->
    <div id="map" class="pagination-centered col-xs-12 col-md-5">
        <p>Here stands a Mapbox map (using Leaflet JS Library). If you don't see it, enable JS in your browser or update it.</p>
    </div>

    <div class="panel panel-default col-xs-12 col-md-offset-1 col-md-6" >
        <ul class="list-group" id="listOfPoints">
        </ul>
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
