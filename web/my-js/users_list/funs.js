/*
	user[i]	.id
			.name
			.type
			.valid
*/
function data_info_xml_parser( responseTxt ) {
			
	var xml = $(responseTxt);
	if ( xml.length<=0 )
		return false;
	
	var ds = xml.find('d');
	$.each( ds, function(i,value) {
		var v = $(value);
		user[i] = new Object();
		user[i].id = v.attr('id');
		user[i].name = v.children('n').text();
		user[i].type = v.children('t').text();
		user[i].valid = parseInt(v.children('v').text());
	} );

	return true;
}

 function get_users_info() {
	$.post( 'my-php/users_list/get_users.php', function( data ) {
		data_info_xml_parser( data );	
		$('#num').text('用户总数:'+user.length+'人');
		if( $('#user_list').children().length<=0 )
			add_user();
		else {
			$('.remove_able').remove();
			add_user();
		}
	} );	
 }

 function add_user() {
	var table = $('#user_list');
	$.each(user,function(i,v) {
		var tr = $('<tr class="remove_able"></tr>');
		tr.attr('id', i);
		if(user[i].valid==0)
			tr.css('background','rgb(253,51,40)');
		table.append(tr);
		
		var th1 = $("<th class='name'></th>");
		th1.text( user[i].id );
		th1.attr('id', i);
		tr.append( th1 );
		
		var th2 = $("<th class='value'></th>");
		th2.text( user[i].name );
		th2.attr('id', i);
		tr.append( th2 );
		
		var th3 = $("<th class='value'></th>");
		th3.text( user[i].type );
		th3.attr('id', i);
		tr.append( th3 );
	} );
 }