<?php
require_once('php/getMissionList.php');
?>

<div class="jumbotron">
    <h1>Mission Configuration</h1>      
    <p>On this page, you can configure missions : create or delete a mission, add waypoints & checkpoints, load a mission on ASPire.</p>
</div>

<!-- MISSION SELECTION -->
<div class="row myrow" id="missionSelector">
    <div class="form-group col-xs-12 col-md-6"  >
        <label for="mission">Please select your mission:</label>
        <select class="selectpicker" id="missionSelection" title="Not Selected" placeholder="mission" data-live-search="true">
            <option id="0" selected > Choose a mission </option>
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
        </select>
    </div>
    <button type="button" class="btn col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-0 col-md-5 disabled" id="editMissionButton" >Edit Mission Properties</button>
</div>
<div class="row">
    <button type="button" class="btn btn-primary col-xs-12 col-sm-offset-1 col-sm-5 col-md-offset-0 col-md-5 " id="createMissionButton" >Create Mission</button>
    <button type="button" class="btn btn-danger col-xs-12 col-sm-5 col-md-offset-1 col-md-5 disabled" id="deleteMissionButton" >Delete Mission</button>
</div>

<!-- BUTTON FOR CANCELLATION / SAVING -->
<div class="row">
    <button type="button" class="btn btn-success col-xs-12 col-sm-offset-1 col-sm-5 col-md-offset-0 col-md-5 hidden" id="saveMissionButton" >Save Mission</button>
    <button type="button" class="btn btn-warning col-xs-12 col-sm-5 col-md-offset-1 col-md-offset-1 col-md-5 hidden " id="cancelMissionButton" >Discard Changes</button>
</div>

<!-- DIV FOR MISSION NAME AND DESCRIPTION -->
<div id="missionPresentation" class="row col-xs-12 col-lg-11">
   <!-- Generated code with JS --> 
</div>

<!-- DELETE CONFIRMATION MODAL PART -->
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
            <button type="button" class="btn btn-primary myfooter" id="cancelDeleteButton">Cancel</button>
            <button type="button" class="btn btn-danger myfooter" id="confirmDeleteButton">Confirm</button>
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
        <div class="modal-footer ">
            <button type="button" class="btn btn-primary myfooter" id="cancelCreateButton">Cancel</button>
            <button type="button" class="btn btn-success myfooter" id="confirmCreateButton">Create New Mission</button>
        </div>
      </div>
    </div>
</div>

<!-- EDIT MISSION MODAL PART -->
<div class="modal fade" id="editMissionModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h4 id="modalTitle" class="modal-title">Edit Mission</h4>
        </div>
        <div class="modal-body">
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="Name" >Name</label>  
                <input id="editMissionName" name="Name" placeholder="Mariehamn 1" class="form-control input-md" required type="text" >
                <span class="help-block">Give the mission a name.</span>  
            </div>

            <!-- Textarea -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="description">Description</label>
              <textarea class="form-control" id="editMissionDescription" name="description" placeholder="Optional" ></textarea>
              <span class="help-block">Give the mission a description to help you remember its purpose.</span>  
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary myfooter" id="cancelEditMissionButton">Cancel</button>
            <button  type="submit" class="btn btn-success myfooter" id="confirmEditMissionButton">Edit Mission Properties</button>
        </div>
      </div>
    </div>
</div>

<!-- NEW POINT MODAL PART -->
<div class="modal fade" id="createPointModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h4 id="modalTitle" class="modal-title">New <span class="waypointOrCheckpoint"><span></span></span></h4>
        </div>
        <div class="modal-body">
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="Name" >Name</label>  
                <input id="newPointName" name="Name" placeholder="Mariehamn 1" class="form-control input-md" required type="text" >
                <span class="help-block">Give the <span class="waypointOrCheckpoint"><span></span></span> a name.</span>  
            </div>

            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="radius">Radius</label>
                <input type="number" class="form-control" id="newPointRadius" name="radius" value="15" required="1" ></input>
                <span class="input-group-addon">meters</span>
                <span class="help-block">Give the size of the <span class="waypointOrCheckpoint"><span></span></span>.</span>

                <label class="col-md-4 control-label" for="stay_time">Stay time</label>
                <input type="number" class="form-control" id="newPointStay_time" name="stay_time" value="1" required="1" ></input>
                <span class="input-group-addon">minutes</span>
                <span class="help-block">How long should the boat stay at the <span class="waypointOrCheckpoint"><span></span></span>?</span>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary myfooter" id="cancelNewPoint">Cancel</button>
            <button  type="submit" class="btn btn-success myfooter" id="confirmNewPoint" onClick="createNewPoint();">Create new <span class="waypointOrCheckpoint"><span></span></span></button>
        </div>
      </div>
    </div>
</div>

<!-- EDIT POINT MODAL PART -->
<div class="modal fade" id="editPointModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h4 id="modalTitle" class="modal-title">Edit <span class="waypointOrCheckpoint"><span></span></span></h4>
        </div>
        <div class="modal-body">
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="Name" >Name</label>  
                <input id="editPointName" name="Name" placeholder="Mariehamn 1" class="form-control input-md" required type="text" >
                <span class="help-block">Give the <span class="waypointOrCheckpoint"><span></span></span> a name.</span>  
            </div>

            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="radius">Radius</label>
                <input type="number" class="form-control" id="editPointRadius" name="radius" value="15" required="1" ></input>
                <span class="input-group-addon">meters</span>
                <span class="help-block">Give the size of the <span class="waypointOrCheckpoint"><span></span></span>.</span>

                <label class="col-md-4 control-label" for="stay_time">Stay time</label>
                <input type="number" class="form-control" id="editPointStay_time" name="stay_time" value="1" required="1" ></input>
                <span class="input-group-addon">minutes</span>
                <span class="help-block">How long should the boat stay at the <span class="waypointOrCheckpoint"><span></span></span>?</span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary myfooter" id="cancelEditPoint">Cancel</button>
            <button  type="submit" class="btn btn-success myfooter" id="confirmEditPoint" <!-- onClick="editPoint();"--> >Edit <span class="waypointOrCheckpoint"><span></span></span></button>
        </div>
      </div>
    </div>
</div>

<br />
<br />

<div class="row top15" id="myConfig">
    <!-- HERE IS THE MAP -->
    <div id="map" class="pagination-centered col-xs-12 col-lg-5">
        <p>Here stands a Mapbox map (using Leaflet JS Library). If you don't see it, enable JS in your browser or update it.</p>
    </div>

    <!-- DISPLAY OF THE POINTS ON THE SIDE OF BELOW --> 
    <div id="listPoint" class="panel panel-default col-xs-12 col-lg-offset-1 col-lg-6" >
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
            //printWaypointList();
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
