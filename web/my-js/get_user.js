function get_user() {
	
	$.post('my-php/get_user_info.php', function(data) {
		if( data.length>0 ) {
			var obj = $.parseJSON( data );
			user.name = obj.name;
			user.type = obj.admin;	
			
			if( user.type==0 ) {
				$('#admin_a').hide();
			}
			
			$('#logout_a').html(user.name+"-注销");
		}	
	} );
}

function logout() {
	
	$.post('my-php/logout.php', function() {
		location.href = 'login.html';
	} );
	
}