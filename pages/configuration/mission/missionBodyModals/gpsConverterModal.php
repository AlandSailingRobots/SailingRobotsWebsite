<?php
?>
<div class="modal fade" id="gpsConverterModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 id="modalTitle" class="modal-title">GPS Coordinates Converter</h4>
            </div>
            <div class="modal-body">
                <!-- Text input-->
                <div class="form-group">
                    <h3> Latitude </h3>
                    <label class="col-md-4 control-label" for="Name">Degrees</label>
                    <input id="degLatitude" name="Name" class="form-control input-md" required type="text">
                    <label class="col-md-4 control-label" for="Minutes">Minutes</label>
                    <input id="minLatitude" name="Name" class="form-control input-md" required type="text">
                    <label class="col-md-4 control-label" for="Seconds">Seconds</label>
                    <input id="secLatitude" name="Name" class="form-control input-md" required type="text">
                </div>
                <div class="form-group">
                    <h3> Longitude </h3>
                    <label class="col-md-4 control-label" for="Name">Degrees</label>
                    <input id="degLongitude" name="Name" class="form-control input-md" required type="text">
                    <label class="col-md-4 control-label" for="Minutes">Minutes</label>
                    <input id="minLongitude" name="Name" class="form-control input-md" required type="text">
                    <label class="col-md-4 control-label" for="Seconds">Seconds</label>
                    <input id="secLongitude" name="Name" class="form-control input-md" required type="text">
                </div>
            </div>
            <p></p>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary myfooter" id="cancelGPSConverterButton">Cancel</button>
                <button type="button" class="btn btn-success myfooter" id="confirmGPSConverterButton">Convert GPS
                    Coordinates
                </button>
            </div>
        </div>
    </div>
</div>
