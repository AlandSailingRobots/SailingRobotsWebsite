<?php
?>
<div class="modal fade" id="editPointModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 id="modalTitle" class="modal-title">Edit <span class="waypointOrCheckpoint"><span></span></span>
                </h4>
            </div>
            <div class="modal-body">
                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="Name">Name</label>
                    <input id="editPointName" name="Name" placeholder="Mariehamn 1" class="form-control input-md"
                           required type="text">
                    <span class="help-block">Give the <span
                            class="waypointOrCheckpoint"><span></span></span> a name.</span>
                </div>

                <!-- Textarea -->
                <div class="form-group">

                    <div class="col-xs-12 col-md-6">
                        <label class="col-md-4 control-label" for="latitude">Latitude</label>
                        <input type="number" class="form-control" id="editPointLatitude" name="" value=""
                               required="1"></input>
                        <span class="help-block">Give the latitude of the <span
                                class="waypointOrCheckpoint"><span></span></span>.</span>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <label class="col-md-4 control-label" for="longitude">Longitude</label>
                        <input type="number" class="form-control" id="editPointLongitude" name="longitude" value=""
                               required="1"></input>
                        <span class="help-block">Give the longitude of the <span
                                class="waypointOrCheckpoint"><span></span></span>.</span>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <label class="col-md-4 control-label" for="radius">Radius</label>
                        <input type="number" class="form-control" id="editPointRadius" name="radius" value=""
                               required="1"></input>
                        <span class="input-group-addon">meters</span>
                        <span class="help-block">Give the size of the <span
                                class="waypointOrCheckpoint"><span></span></span>.</span>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <label class="col-md-4 control-label" for="stay_time">Stay time</label>
                        <input type="number" class="form-control" id="editPointStay_time" name="stay_time" value=""
                               required="1"></input>
                        <span class="input-group-addon">minutes</span>
                        <span class="help-block">How long should the boat stay at the <span
                                class="waypointOrCheckpoint"><span></span></span>?</span>
                    </div>

                    <label class="col-md-4 control-label" for="declination">Declination</label>
                    <input type="number" class="form-control" id="editPointDeclination" name="declination" value=""
                           required="1"></input>
                    <span class="input-group-addon">degrees</span>
                    <br>

                    <!--                 <label class="col-md-4 control-label" for="isCheckpoint">Is Checkpoint ?</label>
                                    <div class="btn-group btn-toggle">
                                        <button id="isCheckpointButton" class="btn btn-default">Yes</button>
                                        <button id="isWaypointButton" class="btn btn-info">No</button>
                                    </div> -->

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info myfooter convertGPSCoordinates"
                        id="convertGPSCoordinatesEditPoint">Convert GPS Coordinates
                </button>
                <button type="button" class="btn btn-primary myfooter " id="cancelEditPointButton">Cancel</button>
                <button type="submit" class="btn btn-success myfooter" id="confirmEditPointButton">Edit <span
                        class="waypointOrCheckpoint"><span></span></span></button>
            </div>
        </div>
    </div>
</div>
