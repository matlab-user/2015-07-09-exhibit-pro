/*
	user.name
		.type
		.key
		.valid
		
	dev .guid
		.name
		.valid
*/
var user = new Object();
var dev = new Object();

function init() {
	$('#user_add').click( function() { user_add();} );
	$('#user_search').click( function() { user_search();} );
	$('#user_update').click( function() { user_update();} );
	
	$('#dev_add').click( function() { dev_add();} );
	$('#dev_search').click( function() { dev_search();} );
	$('#dev_update').click( function() { dev_update();} );
}

function get_user_info() {
	user.name = $('#user_name').val();
	user.type = $('#user_type').val();
	user.key = $('#user_key').val();
	user.valid = $('#user_valid')[0].checked;
}

function get_dev_info() {
	dev.guid = $('#dev_guid').val();
	dev.name = $('#dev_name').val();
	dev.valid = $('#dev_valid')[0].checked;
}

function user_add() {
	get_user_info();
	
	if( user.name==='' || user.key==='' )
		return;

	$.post('my-php/admin/add_user.php',{'name':user.name,'type':user.type,'key':user.key,'valid':user.valid},function(data) {
		//console.log( data );
		switch( data ) {
			case 'ok':
				$('#user_res').html('添加成功！');
				setTimeout("$('#user_res').html('')", 2*1000 );
				break;
			default:
				$('#user_res').html('添加失败！');
				setTimeout("$('#user_res').html('')", 2*1000 );
				break;
		}			
	} ).fail( function() { $('#user_res').html('添加失败！'); setTimeout("$('#user_res').html('')", 2*1000 );} );	
}

function user_search() {
	get_user_info();
	
	if( user.name==='' )
		return;
	
	$.post('my-php/admin/search_user.php',{'name':user.name},function(data) {
		//console.log( data );
		if( data!='no' ) {
			var obj = $.parseJSON( data );
			user.type = obj.type;
			user.valid = obj.valid;
			
			if( user.valid=="1" )
				$('#user_valid')[0].checked = true;
			if( user.valid=="0" )
				$('#user_valid')[0].checked = false;
			
			if( user.type=='admin' )
				$("#user_type")[0].options[0].selected = true;
			if( user.type=='normal' )
				$("#user_type")[0].options[1].selected = true;
			
			$('#user_res').html('查询完毕！');
			setTimeout("$('#user_res').html('')", 2*1000 );
		}
	} );	
}

function user_update() {
	get_user_info();
	
	if( user.name==='' || user.key==='' || user.valid==='' || user.type==='' )
		return;

	$.post('my-php/admin/update_user.php',{'name':user.name,'type':user.type,'key':user.key,'valid':user.valid},function(data) {
		//console.log( data );
		switch( data ) {
			case 'ok':
				$('#user_res').html('修改成功！');
				setTimeout("$('#user_res').html('')", 2*1000 );
				break;
			default:
				$('#user_res').html('修改失败！');
				setTimeout("$('#user_res').html('')", 2*1000 );
				break;
		}			
	} ).fail( function() { $('#user_res').html('修改失败！'); setTimeout("$('#user_res').html('')", 2*1000 );} );	
}

//------------------------------------------------------------------------------------------------------------------------------------------
// 				dev functions
//------------------------------------------------------------------------------------------------------------------------------------------
function dev_add() {
	get_dev_info();
	
	if( dev.guid==='' || dev.name==='' )
		return;

	$.post('my-php/admin/add_dev.php',{'name':dev.name,'guid':dev.guid,'valid':dev.valid},function(data) {
		//console.log( data );
		switch( data ) {
			case 'ok':
				$('#dev_res').html('设备添加成功！');
				setTimeout("$('#dev_res').html('')", 2*1000 );
				break;
			default:
				$('#dev_res').html('设备添加失败！');
				setTimeout("$('#dev_res').html('')", 2*1000 );
				break;
		}			
	} ).fail( function() { $('#dev_res').html('设备添加失败！'); setTimeout("$('#dev_res').html('')", 2*1000 );} );
}

function dev_search() {
	get_dev_info();
	
	if( dev.guid==='' )
		return;
	
	$('#dev_name').val('');
	
	$.post('my-php/admin/search_dev.php',{'guid':dev.guid},function(data) {
		//console.log( data );
		if( data!='no' ) {
			var obj = $.parseJSON( data );
			dev.name = obj.name;
			dev.valid = obj.valid;
			
			if( dev.valid=="1" )
				$('#dev_valid')[0].checked = true;
			if( dev.valid=="0" )
				$('#dev_valid')[0].checked = false;
			
			$("#dev_name").val( dev.name );
		}
		$('#dev_res').html('查询完毕！');
		setTimeout("$('#dev_res').html('')", 2*1000 );
	} );
	
}

function dev_update() {
	get_dev_info();
	
	if( dev.name==='' || dev.guid==='' || dev.valid==='' )
		return;

	$.post('my-php/admin/update_dev.php',{'name':dev.name,'guid':dev.guid,'valid':dev.valid},function(data) {
		console.log( data );
		switch( data ) {
			case 'ok':
				$('#dev_res').html('修改成功！');
				setTimeout("$('#dev_res').html('')", 2*1000 );
				break;
			default:
				$('#dev_res').html('修改失败！');
				setTimeout("$('#dev_res').html('')", 2*1000 );
				break;
		}			
	} ).fail( function() { $('#dev_res').html('修改失败！'); setTimeout("$('#dev_res').html('')", 2*1000 );} );	
	
}