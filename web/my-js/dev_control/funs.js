function init() {	
	$('#open_a').click( function() { open(); } );
	$('#close_a').click( function() { close(); } );
	$('#p_set_a').click( function() { p_set(); } );
	$('#t_set_a').click( function() { t_set(); } );
	$('#f_set_a').click( function() { f_set(); } );

	$('#p_normal_set_a').click( function() { p_normal_set(); } );
	$('#f_normal_set_a').click( function() { f_normal_set(); } );
	$('#t_normal_set_a').click( function() { t_normal_set(); } );

	// 获取设备正常范围值
	update_ths();
	
}

function update_ths() {
	
	$.post( 'my-php/dev_control/get_normal.php',{'gid':dev.gid},function(data) {
		var ths = JSON.parse( data );
		if( ths.pth1!==undefined ) {
			if( dev.p_normal_th1!=ths.pth1 ) {
				dev.p_normal_th1 = ths.pth1;
				$('#p_th1').val( ths.pth1);
			}
		}
		if( ths.pth2!==undefined ) {
			if( dev.p_normal_th2!=ths.pth2 ) { 
				dev.p_normal_th2 = ths.pth2;
				$('#p_th2').val( ths.pth2);
			}
		}
		
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
		
		if( ths.fth1!==undefined ) {
			if( dev.f_normal_th1!=ths.fth1 ) {
				dev.f_normal_th1 = ths.fth1;
				$('#f_th1').val( ths.fth1);
			}
		}
		if( ths.fth2!==undefined ) {
			if( dev.f_normal_th2!=ths.fth2 ) {
				dev.f_normal_th2 = ths.fth2;
				$('#f_th2').val( ths.fth2);
			}
		}
	
	} );
	
	setTimeout( 'update_ths()', 6000 );
}

function open() {
	send_order( 'open', '' );	
}

function close() {
	send_order( 'close', '' );
}

function p_set() {
	var val = $('#p_val').val();
	if( val.length>0 ) {
		send_order( 'p_set', val );
	}	
}

function t_set() {
	var val = $('#t_val').val();
	if( val.length>0 ) {
		send_order( 't_set', val );
	}
}

function f_set() {
	var val = $('#f_val').val();
	if( val.length>0 ) {
		send_order( 'f_set', val );
	}
}

function p_normal_set() {
	var val1 = $('#p_th1').val();
	var val2 = $('#p_th2').val();
	
	if( val1.length<=0 )
		val1 = 0;
	if( val2.length<=0 )
		val2 = 0;
	
	dev.p_normal_th1 = val1;
	dev.p_normal_th2 = val2;
	
	$.post( 'my-php/dev_control/set_normal.php',{'gid':dev.gid,'v_name':'p','th1':val1,'th2':val2} );
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

function f_normal_set() {
	var val1 = $('#f_th1').val();
	var val2 = $('#f_th2').val();
	
	if( val1.length<=0 )
		val1 = 0;
	if( val2.length<=0 )
		val2 = 0;
	
	dev.f_normal_th1 = val1;
	dev.f_normal_th2 = val2;
	
	$.post( 'my-php/dev_control/set_normal.php',{'gid':dev.gid,'v_name':'f','th1':val1,'th2':val2} );	
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
		case 'open':
			order_str = order_str + 'open]';
			res_h = $('#open_res');
			break;	
		case 'close':
			order_str = order_str + 'close]';
			res_h = $('#close_res');
			break;	
		case 'p_set':
			order_str += 'set p ' + arguments[1]+']';
			res_h = $('#p_res');
			break;
		case 't_set':
			order_str += 'set t ' + arguments[1]+']';
			res_h = $('#t_res');
			break;	
		case 'f_set':
			order_str += 'set f ' + arguments[1]+']';
			res_h = $('#f_res');
			break;
	}
	
	$.post( 'my-php/dev_control/send_order.php',{'dev':dev.gid,'order':order_str},function(data) {
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

