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

(function () {
    var missionName = "",   // Name of the mission
        missionDescription = "",   // Its description
        missionLastUse = "",   // Last use of the mission (clicked on load button)
        id_mission = 0;    // It id

    //*****************************************************************************
    //                                                                            *
    //                        Display A Mission                                   *
    //                                                                            *
    //*****************************************************************************

    // Hide the list if there is no point in the mission
    if (listOfPoints.childElementCount === 0) {
        listOfPoints.parentNode.style.display = "none";
    }

    // Hide the map while no mission is selected
    document.getElementById("myConfig").style.display = "none";

    // Read the selected mission
    $("#missionSelection").on("change", function () {
        id_mission = $(this).children(":selected").attr("id");
        handleMissionSelection(id_mission);
    });

    getMissionListFromDB(0);

    function getMissionListFromDB(id) {
        // Get the mission list in a JSON object
        $.ajax({
            type: "POST",
            url: "php/getMissionList.php",
            dataType: "json", // What is expected
            async: true,
            timeout: 3000,
            success: function (data) {
                displayMissionList(data, id);
            },
            error: function () {
                alert("Fail to get mission list!");
            }
        });
    }

    function displayMissionList(data, id) {
        var selectNode = document.getElementById("missionSelection");
        var optionNode = document.createElement("option");

        // Clean before writing again
        deleteAllChildren(selectNode);

        // First element - nothing is selected
        optionNode.setAttribute("id", "0");
        optionNode.appendChild(document.createTextNode("Choose a mission"));
        selectNode.appendChild(optionNode);

        // Force the focus on the 1st item
        if (id === 0) {
            handleMissionSelection(0);
            optionNode.selected = true;
        }

        // If there is any mission in the DB
        amount_of_missions = data.length;
        if (amount_of_missions === 0) {
            optionNode = document.createElement("option");
            optionNode.setAttribute("id", "-1"); // TODO : adapt test for activation of button
            optionNode.appendChild(document.createTextNode("You don't have any saved mission yet."));
            selectNode.appendChild(optionNode);
        } else {
            // Display the name of the mission
            for (var i = 0; i < amount_of_missions; i++) {
                data_id = data[i]["id"];
                data_name = data[i]["name"];
                optionNode = document.createElement("option", data_id);
                optionNode.setAttribute("id", data_id);
                optionNode.setAttribute("data_token", data_id);
                optionNode.appendChild(document.createTextNode(data_id + "- " + data_name));

                // This is used when the list of mission is refreshed after the edition of the mission properties
                if (id.toString() === data_id) {
                    optionNode.selected = true;
                }

                selectNode.appendChild(optionNode);
            }
        }
    }

    // This function handles the display of the button depending on which one is selected
    function handleMissionSelection(id_mission) {
        // Get the right point list, the name and the description of the mission
        if (id_mission > 0) {
            getMissionInfoFromDB(id_mission);
            getMissionPointFromDB(id_mission);
        } else {
            // Delete the previous name & description node when (if) the user goes
            // back to the first field in the selector.
            deleteAllChildren(document.getElementById("missionPresentation"));
        }

        // Update the delete button and the display of the map, as well as
        // the display of the button to save or discard change to the mission.
        if (id_mission > 0) {
            // Disable buttons
            document.getElementById("deleteMissionButton").classList.remove("disabled");
            document.getElementById("editMissionButton").classList.remove("disabled");

            // Confirmation Popup before deleting the selected mission
            // $("#deleteMissionButton").off('click', showDeleteConfirmationModal);

            // Hide buttons
            document.getElementById("saveMissionButton").classList.remove("hidden");
            document.getElementById("cancelMissionButton").classList.remove("hidden");
            document.getElementById("myConfig").style.display = "inline";
        }
        if (id_mission <= 0) {
            // Enable buttons
            document.getElementById("deleteMissionButton").classList.add("disabled");
            document.getElementById("editMissionButton").classList.add("disabled");

            // Confirmation Popup before deleting the selected mission
            // $("#deleteMissionButton").on('click', showDeleteConfirmationModal);

            // Display buttons
            document.getElementById("saveMissionButton").classList.add("hidden");
            document.getElementById("cancelMissionButton").classList.add("hidden");
            document.getElementById("myConfig").style.display = "none";
        }
    }

    //*****************************************************************************
    //                                                                            *
    //                          Mission Information                               *
    //                                                                            *
    //*****************************************************************************

    // Get the mission info in a JSON object
    function getMissionInfoFromDB(id_mission) {
        $.ajax({
            type: 'POST',
            url: 'php/getMissionInfoFromDB.php',
            data: {id_mission: id_mission},
            dataType: 'json', // What is expected
            async: true,
            timeout: 3000,
            success: function (data) {
                // I use the 'global' var within the IEFE
                missionName = data[0]["name"];
                missionDescription = data[0]["description"];
                missionLastUse = data[0]['last_use'];
                cleanMissionInfo();
                displayMissionInfo();
            },
            error: function () {
                alert('Fail to get mission info!');
            }
        });
    }

    // This function display the title and the description of the mission
    function displayMissionInfo() {
        var parentNode = document.getElementById('missionPresentation');

        var nameNode = document.createElement('h3'),
            descriptionNode = document.createElement('p');

        nameNode.appendChild(document.createTextNode(missionName));
        descriptionNode.appendChild(document.createTextNode(missionDescription));
        descriptionNode.setAttribute("id", "descriptionNode");

        parentNode.appendChild(nameNode);
        parentNode.appendChild(descriptionNode);

        if (missionLastUse != null) {
            var lastUseNode = document.createElement('p');
            lastUseNode.appendChild(document.createTextNode(
                'This mission has been loaded on ASPire for the last time on the ' + missionLastUse
            ));
            parentNode.appendChild(lastUseNode);
        }

    }

    // This function clean the display of the mission info
    function cleanMissionInfo() {
        var parentNode = document.getElementById('missionPresentation');
        deleteAllChildren(parentNode);
    }

    //*****************************************************************************
    //                                                                            *
    //                          Instructions                                      *
    //                                                                            *
    //*****************************************************************************

    $('#missionInstructionLink').on('click', function () {
        $('#instructionModal').modal('show');
    });

    $('#closeInstructionButton').on('click', function () {
        $('#instructionModal').modal('hide');
    });

    //*****************************************************************************
    //                                                                            *
    //                          Delete A Mission                                  *
    //                                                                            *
    //*****************************************************************************

    $("#deleteMissionButton").on('click', showDeleteConfirmationModal);

    // Try to remove the listener when the button is disabled.
    // KNOWN BUG : modal still display when button are disabled
    function showDeleteConfirmationModal() {
        $('#deleteConfirmationModal').modal('show');
    }

    // Cancel
    $('#cancelDeleteButton').on('click', function () {
        $('#deleteConfirmationModal').modal('hide');
    })

    // Confirm
    $('#confirmDeleteButton').on('click', deleteMission);

    // This function send an AJAX request to delete the selected mission from the DB
    function deleteMission() {
        var selectedMission = $('#missionSelection').children(':selected').attr('id');

        $.ajax({
            type: 'POST',
            url: 'php/deleteMissionFromDB.php',
            data: {id_mission: selectedMission},
            timeout: 3000,
            success: function (data) {
                alert(data);
                getMissionListFromDB(0);
            },
            error: function () {
                alert('Fail!');
            }
        });

        $('#deleteConfirmationModal').modal('hide');
    }

    //*****************************************************************************
    //                                                                            *
    //                      Create A New Mission                                  *
    //                                                                            *
    //*****************************************************************************

    $('#createMissionButton').on('click', function () {
        $('#createMissionModal').modal('show');
    });

    // Cancel
    $('#cancelCreateButton').on('click', function () {
        $('#createMissionModal').modal('hide');
    })

    // Confirm
    $('#confirmCreateButton').on('click', createMission);

    // This function sends an AJAX Query to create a new mission in the DB
    function createMission() {
        var nameF = $('#newMissionName').val(),
            descriptionF = $('#newMissionDescription').val();

        $.ajax({
            type: 'POST',
            url: 'php/insertMissionIntoDB.php',
            data: {name: nameF, description: descriptionF},
            timeout: 3000,
            success: function (data) {
                alert(data);
                getMissionListFromDB(0);
            },
            error: function () {
                alert('Fail creating mission!');
            }
        });

        $('#createMissionModal').modal('hide');
    }

    //*****************************************************************************
    //                                                                            *
    //                        Edit A Mission                                      *
    //                                                                            *
    //*****************************************************************************

    $('#editMissionButton').on('click', function () {
        $('#editMissionModal').modal('show');

        // Load values
        $('#editMissionName').val(missionName);
        $('#editMissionDescription').val(missionDescription);
    });

    // Cancel
    $('#cancelEditMissionButton').on('click', function () {
        $('#editMissionModal').modal('hide');
    });

    // Confirm
    $('#confirmEditMissionButton').on('click', function () {
        // Load the values and send an AJAX Query with the modification to make
        $.ajax({
            type: 'POST',
            url: 'php/updateMissionInfo.php',
            data: {
                name: $('#editMissionName').val(),
                description: $('#editMissionDescription').val(),
                id_mission: id_mission
            },
            timeout: 3000,
            success: function (data) {
                // alert(data);
                alert(data);
                getMissionInfoFromDB(id_mission);
                getMissionListFromDB(id_mission);
                $('#editMissionModal').modal('hide');
            },
            error: function () {
                alert('Fail!');
            }
        });
    });

    //*****************************************************************************
    //                                                                            *
    //                      Discard Changes in Mission                            *
    //                                                                            *
    //*****************************************************************************

    $('#cancelMissionButton').on('click', function () {
        // IMPROVEMENT : Only reload the righ <div> tags
        location.reload();
    });

    //*****************************************************************************
    //                                                                            *
    //                        Saving Mission in DB                                *
    //                                                                            *
    //*****************************************************************************

    $('#saveMissionButton').on('click', saveMissionIntoDB); // Mission in the other file

    //*****************************************************************************
    //                                                                            *
    //                            GPS CONVERTER                                   *
    //                                                                            *
    //*****************************************************************************

    // Allow people to convert GPS coordinates from DEG, MIN, SEC to DEG, DEC
    $('.convertGPSCoordinates').on('click', function () {
        if ($(this).attr('id') == "convertGPSCoordinatesNewPoint") {
            $('#createPointModal').modal('hide');
            $('#confirmGPSConverterButton').addClass('gpsNewPoint');
        }
        if ($(this).attr('id') == "convertGPSCoordinatesEditPoint") {
            $('#editPointModal').modal('hide');
            $('#confirmGPSConverterButton').addClass('gpsEditPoint');
        }

        $('#gpsConverterModal').modal('show');
    });

    $('#cancelGPSConverterButton').on('click', function () {
        $('#gpsConverterModal').modal('hide');
    });

    $('#confirmGPSConverterButton').on('click', function () {
        var latitude, longitude;

        // Conversion
        latitude = parseFloat($('#degLatitude').val()) +
            parseFloat($('#minLatitude').val()) / 60 +
            parseFloat($('#secLatitude').val()) / 3600;

        longitude = parseFloat($('#degLongitude').val()) +
            parseFloat($('#minLongitude').val()) / 60 +
            parseFloat($('#secLongitude').val()) / 3600;

        // Insertion of the converted values at the right place
        if ($(this).hasClass('gpsNewPoint')) {
            $('#gpsConverterModal').modal('hide');
            $('#newPointLatitude').val(latitude);
            $('#newPointLongitude').val(longitude);
            $('#createPointModal').modal('show');
        }
        if ($(this).hasClass('gpsEditPoint')) {
            $('#editPointLatitude').val(latitude);
            $('#editPointLongitude').val(longitude);
            $('#gpsConverterModal').modal('hide');
            $('#editPointModal').modal('show');
        }
    });

    //*****************************************************************************
    //                                                                            *
    //                             LOAD MISSION                                   *
    //                                                                            *
    //*****************************************************************************

    $('#loadMissionButton').on('click', function () {
        saveMissionIntoDB();
        // Use the 'global' var of the IEFE.
        loadMissionToBoat(id_mission);
    });

    function loadMissionToBoat(id_mission) {
        $.ajax({
            type: 'POST',
            url: 'php/loadMissionToBoat.php',
            data: {id_mission: id_mission},
            // dataType: 'json', // What is expected
            async: true,
            timeout: 3000,
            success: function (data) {
                alert(data);
            },
            error: function () {
                alert('Fail to load mission on the ASPire DB!');
            }
        });
    }

}());
