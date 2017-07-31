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
    var missionName         = "",   // Name of the mission
        missionDescription  = "",   // Its description
        id_mission          = 0;    // It id

    //*****************************************************************************
    //                                                                            *
    //                        Display A Mission                                   *
    //                                                                            *
    //*****************************************************************************

    $('#missionSelection').on('change', function()
    {
        id_mission = $(this).children(':selected').attr('id');
        
        // Get the right point list, the name and the description of the mission
        if (id_mission != 0)
        {
            getMissionInfoFromDB(id_mission);
            // refreshMap();
            getMissionPointFromDB(id_mission);
        }

        // Update the delete button and the display of the map, as well as
        // the display of the button to save or discard change to the mission.
        if (id_mission != 0 && document.getElementById("deleteMissionButton").classList.contains('disabled'))
        {
            document.getElementById("deleteMissionButton").classList.remove('disabled'); 
            document.getElementById("editMissionButton").classList.remove('disabled'); 
            document.getElementById("saveMissionButton").classList.remove('hidden');
            document.getElementById("cancelMissionButton").classList.remove('hidden');
            document.getElementById('myConfig').style.display = 'inline';   
        }
        if (id_mission == 0 && !(document.getElementById("deleteMissionButton").classList.contains('disabled')))
        {
            document.getElementById("deleteMissionButton").classList.add('disabled');   
            document.getElementById("editMissionButton").classList.add('disabled');   
            document.getElementById("saveMissionButton").classList.add('hidden');
            document.getElementById("cancelMissionButton").classList.add('hidden');
            document.getElementById('myConfig').style.display = 'none';   
        }
    });

    function getMissionInfoFromDB(id_mission)
    {
        // Get the mission info in a JSON object
        $.ajax({
            type: 'POST',
            url: 'php/getMissionInfoFromDB.php',
            data: {id_mission:id_mission},
            dataType: 'json', // What is expected
            async: true,
            timeout: 3000,
            success: function(data) {
                // I use the 'global' var within the IEFE
                missionName = data[0]["name"];
                missionDescription = data[0]["description"];
                cleanMissionInfo();
                displayMissionInfo(); },
            error: function() {
                alert('Fail !'); }
        });
    }

    // This function display the title and the description of the mission
    function displayMissionInfo()
    {
        var parentNode = document.getElementById('missionPresentation');

        var nameNode = document.createElement('h3'),
            descriptionNode = document.createElement('p');

        nameNode.appendChild(document.createTextNode(missionName));
        descriptionNode.appendChild(document.createTextNode(missionDescription));
        descriptionNode.setAttribute("id", "descriptionNode");

        parentNode.appendChild(nameNode);
        parentNode.appendChild(descriptionNode);
    }

    // This function clean the display of the mission info
    function cleanMissionInfo()
    {
        var parentNode = document.getElementById('missionPresentation');
        
        // We can call the function everytime, it will check id there is stg
        // to be deleted before raising an error if it is not the case.
        if (parentNode.childElementCount > 0)
        {
            while (parentNode.firstChild) 
            {
                parentNode.removeChild(parentNode.firstChild);
            }
        }
    }

    //*****************************************************************************
    //                                                                            *
    //                      Delete A Mission                                      *
    //                                                                            *
    //*****************************************************************************

    // Confirmation Popup before deleting the selected mission
    $("#deleteMissionButton").on('click', function(){
        $('#deleteConfirmationModal').modal('show');

        // Cancel
        $('#cancelDeleteButton').on('click', function(){
                $('#deleteConfirmationModal').modal('hide');
            })

        // Confirm
        $('#confirmDeleteButton').on('click', deleteMission);
    });

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
    }

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
        // console.warn('you clicked on the button !');
        var nameF        = $('#newMissionName').val(),
            descriptionF = $('#newMissionDescription').val();

        // console.log('name : ' + nameF + ' | description : ' + descriptionF);
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
    //                        Edit A Mission                                      *
    //                                                                            *
    //*****************************************************************************

    $('#editMissionButton').on('click', function(){
        $('#editMissionModal').modal('show');
        
        // Load values
        $('#editMissionName').val(missionName);
        $('#editMissionDescription').val(missionDescription);

        // Cancel
        $('#cancelEditMissionButton').on('click', function(){
            $('#editMissionModal').modal('hide');
            // Clean values of the form
            // $('#editMissionName').val("");
            // $('#editMissionDescription').val("");
        })

    });

    // Confirm
    $('#confirmEditMissionButton').on('click', function(){
        // Load the values and send an AJAX Query with the modification to make
        $.ajax({
            type: 'POST',
            url: 'php/updateMissionInfo.php',
            data: {name:$('#editMissionName').val() ,description:$('#editMissionDescription').val(), id_mission:id_mission},
            timeout: 3000,
            success: function(data) {
                // alert(data);
                // $('#missionSelector').load(document.URL + ' #missionSelector'); },
                // $('#right_col').load(document.URL + ' #right_col', main); },
                // location.reload(); 
                alert('Saved !');
                getMissionInfoFromDB(id_mission);
                $('#editMissionModal').modal('hide');
            },
            error: function() {
                alert('Fail !'); }
        });
    })

    //*****************************************************************************
    //                                                                            *
    //                      Discard Changes in Mission                            *
    //                                                                            *
    //*****************************************************************************

    $('#cancelMissionButton').on('click', function()
        {
            // IMPROVEMENT : Only reload the righ <div> tags
            location.reload();
        });

    //*****************************************************************************
    //                                                                            *
    //                        Saving Mission in DB                                *
    //                                                                            *
    //*****************************************************************************

    $('#saveMissionButton').on('click', saveMissionIntoDB);

    // function saveMissionIntoDB()
    // {

    // }

}());
