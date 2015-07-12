function init() {
	$('#open_l').click( function() { open('l'); } );
	$('#open_w').click( function() { open('w'); } );
	$('#open_g').click( function() { open('g'); } );

	$('#close_l').click( function() { close('l'); } );
	$('#close_w').click( function() { close('w'); } );
	$('#close_g').click( function() { close('g'); } );

	$('#t_normal_set_a').click( function() { t_normal_set(); } );

	$('#msg_send').click( function() { msg_send(); } );

	// 获取设备正常范围值
	update_ths();

}

function update_ths() {

	$.post( 'my-php/dev_control/get_normal.php',{'gid':dev.gid},function(data) {
		var ths = JSON.parse( data );

		if( ths.tth1!==undefined ) {
			if( dev.t_normal_th1!=ths.tth1 ) {
				dev.t_normal_th1 = ths.tth1;
				$('#t_th1').val( ths.tth1);
			}
		}
		if( ths.tth2!==undefined ) {
			if( dev.t_normal_th2!=ths.tth2 ) {
				dev.t_normal_th2 = ths.tth2;
				$('#t_th2').val( ths.tth2);
			}
		}

	} );

	setTimeout( 'update_ths()', 6000 );
}

function open( para ) {
	var s = '';
	switch (para) {
		case 'l':
			s = 'ol';
			break;

		case 'w':
			s = 'ow';
			break;

		case 'g':
			s = 'og';
			break;

		default:
			return;
	}
	send_order( s, '' );
}

function close( para ) {

	var s = '';
	switch (para) {
		case 'l':
			s = 'cl';
			break;

		case 'w':
			s = 'cw';
			break;

		case 'g':
			s = 'cg';
			break;

		default:
			return;
	}

	send_order( s, '' );
}

function t_normal_set() {
	var val1 = $('#t_th1').val();
	var val2 = $('#t_th2').val();

	if( val1.length<=0 )
		val1 = 0;
	if( val2.length<=0 )
		val2 = 0;

	dev.t_normal_th1 = val1;
	dev.t_normal_th2 = val2;

	$.post( 'my-php/dev_control/set_normal.php',{'gid':dev.gid,'v_name':'t','th1':val1,'th2':val2} );
}

function msg_send() {
	var val = $('#msg').val();

	if( val.length<=0 )
		return;

	send_order( 'msg_send', val );
}

/*
	order - open, close, p_set, f_set, t_set
	最终指令为：S[guid,order,para]
*/
function send_order( order, para ) {

	var num_args = arguments.length;
	var d = new Date();

	//var order_str = 'S['+dev.gid+','+Math.round(d.getTime()/1000)+',';
	var order_str = 'S['+dev.gid+',';
	var res_h;

	switch( order ) {
		case 'ol':
		case 'cl':
			order_str = order_str + order + ']';
			res_h = $('#light');
			break;

		case 'ow':
		case 'cw':
			order_str = order_str + order + ']';
			res_h = $('#water');
			break;

		case 'og':
		case 'cg':
			order_str = order_str + order + ']';
			res_h = $('#gas');
			break;

		case 'msg_send':
			order_str += 'set m ' + arguments[1]+']';
			res_h = $('#msg_res');
			break;
	}

	$.post( 'my-php/dev_control/send_order.php', {'dev':dev.gid,'order':order_str}, function(data) {
		switch( data ) {
			case'OK':
				res_h.text('成功');
				res_h.delay(3000).fadeIn(function(){$(this).text('');});
				break;
			default:
				res_h.text('失败');
				res_h.delay(3000).fadeIn(function(){$(this).text('');});
				break;
		}
	}).fail(function() {
			res_h.text('失败');
			res_h.delay(3000).fadeIn(function(){$(this).text('');});
		});
}
