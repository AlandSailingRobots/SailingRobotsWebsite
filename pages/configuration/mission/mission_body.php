<?php
require_once('php/getMissionList.php');
?>

<div class="jumbotron">
    <h1>Mission Configuration</h1>      
    <p>On this page, you can configure missions : create or delete a mission, add waypoints & checkpoints, load a mission on ASPire. <span class="btn" id="missionInstructionLink">More information.</span></p>
</div>

<!-- MISSION SELECTION -->
<div class="row myrow" id="missionSelector">
    <div class="form-group col-xs-12 col-md-6"  >
        <label for="mission">Please select your mission:</label>
        <select class="selectpicker" id="missionSelection" title="Not Selected" placeholder="mission" data-live-search="true">
            <option id="0" selected > Choose a mission </option>
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

<!-- INSTRUCTION MODAL PART -->
<div class="modal fade" id="instructionModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
          <h4 id="modalTitle" class="modal-title">How to use that page ?</h4>
        </div>
        <div class="modal-body">
            <h3>1 - Select your mission</h3>
            <p class="text-justify">
                First thing first, select your mission by using the selector just bellow the title of this page. 
                If you want to create a new mission, click on the right button and fill the different fields of the form.
                Then, you can select the new mission in the selector.<br/>
            </p>
            <h3>2 - Prepare your mssion</h3>
            <p class="text-justify">
                To add a point on the map, click wherever you would like to add it. Then you can choose whether you want to add a waypoint or a checkpoint. <br/>
                - A WAYPOINT is a point to help the boat with its route planning. If the boat does not exactly go that point, that is not a problem.<br/>
                - A CHECKPOINT is a point to which the boat will go to take measurements.<br/>
                You can move a marker position by drag'n'droping it on the map. It will automatically update its position on the page. But don't forget to save your changes before selecting another mission.<br/>
            </p>
            <h3>3 - Save your mission</h3>
            <p class="text-justify">
                Click on the 'Save Mission' button to save the mission into the database of the server.<br/>
            </p>
            <h3>4 - Launch a mission</h3>
            <p class="text-justify">
                To load a mission on ASPire, select your mission, and click on the 'Launch the Selected Mission' button. It will make a copy of the list of the waypoint & checkpoint to another database, which is synchronized with ASPire.<br/>
            </p>
            <h3>Remarks</h3>
            <p class="text-justify">
                Pay attention to the fact that, you cannot edit your point for the moment. It means that you cannot change its properties once it has been added to the map.<br/>
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary myfooter" id="closeInstructionButton">Close</button>
        </div>
      </div>
    </div>
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
