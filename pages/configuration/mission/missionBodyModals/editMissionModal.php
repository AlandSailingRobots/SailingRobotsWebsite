<?php
?>
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
                    <label class="col-md-4 control-label" for="Name">Name</label>
                    <input id="editMissionName" name="Name" placeholder="Mariehamn 1" class="form-control input-md"
                           required type="text">
                    <span class="help-block">Give the mission a name.</span>
                </div>

                <!-- Textarea -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="description">Description</label>
                    <textarea class="form-control" id="editMissionDescription" name="description"
                              placeholder="Optional"></textarea>
                    <span class="help-block">Give the mission a description to help you remember its purpose.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary myfooter" id="cancelEditMissionButton">Cancel</button>
                <button type="submit" class="btn btn-success myfooter" id="confirmEditMissionButton">Edit Mission
                    Properties
                </button>
            </div>
        </div>
    </div>
</div>
