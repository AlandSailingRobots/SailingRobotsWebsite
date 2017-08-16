(function ()
{

	$('#editPasswordButton').on('click', function(){
		$('#editPasswordModal').modal('show');
	});

	$('#cancelEditPasswordButton').on('click', function(){
		$('#editPasswordModal').modal('hide');
	})

	$('#editProfileButton').on('click', function(){
		$('#avatar-modal').modal('show');
	});

})();