<?php
?>
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
                    If you want to create a new mission, click on the right button and fill the different fields of the
                    form.
                    Then, you can select the new mission in the selector.<br/>
                </p>
                <h3>2 - Prepare your mssion</h3>
                <p class="text-justify">
                    To add a point on the map, click wherever you would like to add it. Then you can choose whether you
                    want to add a waypoint or a checkpoint. <br/>
                    - A WAYPOINT is a point to help the boat with its route planning. If the boat does not exactly go
                    that point, that is not a problem.<br/>
                    - A CHECKPOINT is a point to which the boat will go to take measurements.<br/>
                    You can move a marker position by drag'n'droping it on the map. It will automatically update its
                    position on the page. But don't forget to save your changes before selecting another mission.<br/>
                </p>
                <h3>3 - Save your mission</h3>
                <p class="text-justify">
                    Click on the 'Save Mission' button to save the mission into the database of the server.<br/>
                </p>
                <h3>4 - Launch a mission</h3>
                <p class="text-justify">
                    To load a mission on ASPire, select your mission, and click on the 'Launch the Selected Mission'
                    button. It will make a copy of the list of the waypoint & checkpoint to another database, which is
                    synchronized with ASPire.<br/>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary myfooter" id="closeInstructionButton">Close</button>
            </div>
        </div>
    </div>
</div>
