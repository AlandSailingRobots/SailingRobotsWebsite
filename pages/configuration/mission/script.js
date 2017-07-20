//***************************************************************************************
//
// 	Purpose:
//		This JS file is used to managed the selection of the mission, the display of the
//		waypoints / checkpoints and the update of the DB
//
//	Developer Notes:
//		As I am a beginner in JS development, this file is probably a complete mess, 
//		using at the same time 'regular' JS functions (to select element of the HTML page)
//		and JQuery functions. I assume that this should not be done. :p
//
//**************************************************************************************/

// jQuery version
// $('#missionSelection').on('change', function(){
// 	console.log("id ", $(this).children(':selected').attr('id'));
// 	document.getElementById("deleteMission").classList.remove('disabled');
// 	// alert($(this).children(':selected').attr("id"));
// });

// Without jQuery
document.getElementById('missionSelection').addEventListener('change', function(){
	var id_mission = $(this).children(':selected').attr('id')
	console.log("id ", id_mission);
	if (id_mission != 0 && document.getElementById("deleteMission").classList.contains('disabled'))
	{
		document.getElementById("deleteMission").classList.remove('disabled');	
	}
	if (id_mission == 0 && !(document.getElementById("deleteMission").classList.contains('disabled')))
	{
		document.getElementById("deleteMission").classList.add('disabled');	
	}
})

//document.getElementById("deleteMission").addEventListener("click", deleteMission)
$("#deleteMission").on('click', deleteMission);
function deleteMission()
{
	var selectedMission = $(this).children(':selected').attr('id'); //JQuery
	$('.modal').modal('show');

	// Cancel
	$('#cancelDeleteButton').on('click', function(){
			$('.modal').modal('hide');
		})
	// Confirm
	$('#confirmDeleteButton').on('click', function(){
			console.log('clicked Confirm !');
            $('#r').html('<img src="http://www.mediaforma.com/sdz/jquery/ajax-loader.gif">');
            // $('#r').load('include/deleteMission.php',selectedMission);
            // $.post("include/deleteMissionFromDB.php",
            // 		{
            // 			id_mission: selectedMission
            // 		},
            // 		function(data, status)
            // 		{
            // 			alert("Data: " + data + "\nStatus: " + status);
            // 		}
            // 	);
	          $.ajax({
	            type: 'POST',
	            url: 'include/deleteMissionFromDB.php',
	            data: 'id_mission=' + selectedMission,
	            timeout: 3000,
	            success: function(data) {
	              alert(data); },
	            error: function() {
	              alert('Fail !'); }
	          }); 
        });  

};


//***************************************************************************************
//
//				Test functions
//
//***************************************************************************************
function check() 
{
    var input = $('#missionSelection');
	var paragraph = document.getElementById('messageMission');
	var newlink0 = document.createElement('p');
	var res = input.children(':selected').attr('id');

	var newLinkText = document.createTextNode(' You clicked on the mission ' + res);


  	alert('La case cochée est la n°' + res);
  	paragraph.appendChild(newLinkText);
  	paragraph.style.display = 'inline-block';
}

$( document ).ready(function() {
    var mess = document.querySelectorAll('.messageMission');
   	mess[0].style.display = 'none'; 
});

