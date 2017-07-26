//***************************************************************************************
//
//  Purpose:
//      This JS file is used to managed the selection of the mission, the display of the
//      waypoints / checkpoints and the update of the DB
//
//  Developer Notes:
//      As I am a beginner in JS development, this file is probably a complete mess, 
//      using at the same time 'regular' JS functions (to select element of the HTML page)
//      and JQuery functions. I assume that this should not be done. :p
//
//**************************************************************************************/

(function ()
{
    //*****************************************************************************
    //                                                                            *
    //                      Delete A Mission                                      *
    //                                                                            *
    //*****************************************************************************

    // Update the delete button
    $('#missionSelection').on('change', function(){
        var id_mission = $(this).children(':selected').attr('id')
        console.log("id ", id_mission);
        if (id_mission != 0 && document.getElementById("deleteMissionButton").classList.contains('disabled'))
        {
            document.getElementById("deleteMissionButton").classList.remove('disabled'); 
            document.getElementById('myConfig').style.display = 'inline';   
        }
        if (id_mission == 0 && !(document.getElementById("deleteMissionButton").classList.contains('disabled')))
        {
            document.getElementById("deleteMissionButton").classList.add('disabled');   
            document.getElementById('myConfig').style.display = 'none';   
        }
    })



    // Confirmation Popup before deleting the selected mission
    $("#deleteMissionButton").on('click', function(){
        $('#deleteConfirmationModal').modal('show');

        // Cancel
        $('#cancelDeleteButton').on('click', function(){
                $('#deleteConfirmationModal').modal('hide');
            })
        // Confirm
        $('#confirmDeleteButton').on('click', deleteMission);
    })

    function deleteMission()
    {
        // This function send an AJAX request to delete the selected mission from the DB
        var selectedMission = $('#missionSelection').children(':selected').attr('id');

        $.ajax({
            type: 'POST',
            url: 'php/deleteMissionFromDB.php',
            data: {id_mission:selectedMission},
            timeout: 3000,
            success: function(data) {
                // alert(data);
                // $('#missionSelector').load(document.URL + ' #missionSelector', main); },
                location.reload(); },
            error: function() {
                alert('Fail !'); }
        });

        $('#deleteConfirmationModal').modal('hide');
    };  

    //*****************************************************************************
    //                                                                            *
    //                      Create A New Mission                                  *
    //                                                                            *
    //*****************************************************************************

    $('#createMissionButton').on('click', function(){
        $('#createMissionModal').modal('show');

        // Cancel
        $('#cancelCreateButton').on('click', function(){
                $('#createMissionModal').modal('hide');
            })

        // Confirm
        $('#confirmCreateButton').on('click', createMission);
    });

    function createMission()
    {
        console.warn('you clicked on the button !');
        var nameF        = $('#newMissionName').val(),
            descriptionF = $('#newMissionDescription').val();

        console.log('name : ' + nameF + ' | description : ' + descriptionF);
        $.ajax({
            type: 'POST',
            url: 'php/insertMissionIntoDB.php',
            data: {name:nameF ,decription:descriptionF},
            timeout: 3000,
            success: function(data) {
                // alert(data);
                // $('#missionSelector').load(document.URL + ' #missionSelector'); },
                // $('#right_col').load(document.URL + ' #right_col', main); },
                location.reload(); },
            error: function() {
                alert('Fail !'); }
        });

        $('#createMissionModal').modal('hide');
    }

    //*****************************************************************************
    //                                                                            *
    //                      Discard Changes in Mission                            *
    //                                                                            *
    //*****************************************************************************

    $('#cancelMissionButton').on('click', function()
        {
            location.reload();
        });

    //*****************************************************************************
    //                                                                            *
    //                        Saving Mission in DB                                *
    //                                                                            *
    //*****************************************************************************

    $('#saveMissionButton').on('click', saveMissionIntoDB());

    function saveMissionIntoDB()
    {

    }

}());
