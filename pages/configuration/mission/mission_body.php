<?php
require_once('php/getMissionList.php');
?>
<div class="jumbotron">
    <h1>Mission Configuration</h1>
    <p>On this page, you can configure missions : create or delete a mission, add waypoints & checkpoints, load a
        mission on ASPire. <span class="btn btn-link" id="missionInstructionLink">More information</span></p>
</div>

<!-- MISSION SELECTION -->
<div class="row myrow" id="missionSelector">
    <div class="form-group col-xs-12 col-sm-offset-1 col-md-offset-0 col-md-6">
        <label for="mission">Please select your mission:</label>
        <select class="selectpicker" id="missionSelection" title="Not Selected" placeholder="mission"
                data-live-search="true">
            <option id="0" selected> Choose a mission</option>
        </select>
    </div>
    <button type="button" class="btn btn-default col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-0 col-md-5 disabled"
            id="editMissionButton">Edit Mission Properties
    </button>
</div>
<div class="row">
    <button type="button" class="btn btn-primary col-xs-12 col-sm-offset-1 col-sm-5 col-md-offset-0 col-md-5 "
            id="createMissionButton">Create New Mission
    </button>
    <button type="button" class="btn btn-danger col-xs-12 col-sm-5 col-md-offset-1 col-md-5 disabled"
            id="deleteMissionButton">Delete Mission
    </button>
</div>

<!-- BUTTON FOR CANCELLATION / SAVING -->
<div class="row">
    <button type="button" class="btn btn-success col-xs-12 col-sm-offset-1 col-sm-5 col-md-offset-0 col-md-5 hidden"
            id="saveMissionButton">Save Mission
    </button>
    <button type="button" class="btn btn-warning col-xs-12 col-sm-5 col-md-offset-1 col-md-offset-1 col-md-5 hidden "
            id="cancelMissionButton">Discard Changes
    </button>
</div>

<!-- DIV FOR MISSION NAME AND DESCRIPTION -->
<div id="missionPresentation" class="row col-xs-12 col-lg-11">
    <!-- Generated code with JS -->
</div>
<?php
//<!-- INSTRUCTION MODAL PART -->
include('missionBodyModals/instructionModal.php');
//<!-- DELETE CONFIRMATION MODAL PART -->
include('missionBodyModals/deleteConfirmationModal.php');
//<!-- ADD MISSION MODAL PART -->
include('missionBodyModals/createMissionModal.php');
//<!-- EDIT MISSION MODAL PART -->
include('missionBodyModals/editMissionModal.php');
//<!-- NEW POINT MODAL PART -->
include('missionBodyModals/createPointModal.php');
//    <!-- EDIT POINT MODAL PART -->
include('missionBodyModals/editPointModal.php');
//    <!-- GPS CONVERTER MODAL PART -->
include('missionBodyModals/gpsConverterModal.php')
?>
<br/>
<br/>
<div class="row top15" id="myConfig">
    <!-- HERE IS THE MAP -->
    <div id="map" class="pagination-centered col-xs-12 col-lg-5">
        <p>Here stands a Mapbox map (using Leaflet JS Library). If you don't see it, enable JS in your browser or update
            it.</p>
    </div>

    <!-- DISPLAY OF THE POINTS ON THE SIDE OF BELOW -->
    <div id="listPoint" class="panel panel-default col-xs-12 col-lg-offset-1 col-lg-5">
        <ul class="list-group" id="listOfPoints">
        </ul>
    </div>
    <button type="button"
            class="btn btn-info btn-lg col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-5"
            id="loadMissionButton">Load Mission on ASPire
    </button>
</div>