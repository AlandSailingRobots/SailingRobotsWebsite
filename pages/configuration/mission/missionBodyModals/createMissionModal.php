<?php
?>
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
                    <label class="col-md-4 control-label" for="Name">Name</label>
                    <input id="newMissionName" name="Name" placeholder="ex: Sailing Test" class="form-control input-md"
                           required="1" type="text">
                    <span class="help-block">Give the mission a name to help you find it in the list.</span>
                </div>

                <!-- Textarea -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="description">Description</label>
                    <textarea class="form-control" id="newMissionDescription" name="description"
                              placeholder="Optional"></textarea>
                    <span class="help-block">Give the mission a description to help you remember its purpose.</span>
                </div>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-primary myfooter" id="cancelCreateButton">Cancel</button>
                <button type="button" class="btn btn-success myfooter" id="confirmCreateButton">Create New Mission
                </button>
            </div>
        </div>
    </div>
</div>
