/*
	dev	.gid
		.name
		.p   	// 压力 
		.f		// 流量 
		.temp	// 温度
		.r		// 阻力
		.t		// 时间
		.p_plot
		.f_plot
		.temp_plot
		.r_plot
		.p_normal_th1
		.p_normal_th2
		.t_normal_th1
		.t_normal_th2
		.f_normal_th1
		.f_normal_th2
		.new_p		//最新压力值
		.new_f		
		.new_t
	
	user.name
		.type  1-admin
	注： 设备默认是按照 东八区 时区显示数据
*/
function init() {
	
	if( $('#tooltip').length<=0 ) {
		$('body').append( $('<div id="tooltip"></div>') );
		$('#tooltip').hide();
	}
	
	var p_flot = $('.fdiv');
	p_flot.css( {'outerWidth':'100%'} );
	p_flot.height( p_flot.width()*0.52 );
	
/*
	dev.p_plot = add_flot( 'p_flot' );
	dev.f_plot = add_flot( 'f_flot' );	
	dev.r_plot = add_flot( 'r_flot' );
*/
	dev.temp_plot = add_flot( 'temp_flot' );
	
	
	$(".fdiv").bind("plothover", function (event, pos, item) {
		if (item) {
			var x = item.datapoint[0],
				y = item.datapoint[1].toFixed(2);
			
			var d = new Date( x );   
			var hour = d.getUTCHours();     
			var minute = d.getUTCMinutes();     
			var date_str = hour+':'+minute+':'+d.getUTCSeconds();
			
			$("#tooltip").html( 'x= ' + date_str + " y= " + y)
				.css({top: item.pageY+5, left: item.pageX+5}).show();
		} else
			$("#tooltip").hide();
	});
	
	update();
	
	$('#alarm_close').click( function() { $('#alarm_div').hide(); } );
	$('#alarm_div').hide();
}

function update() {
	
	update_ths();
	
	var lt = dev.t[dev.t.length-1];
	$.post( 'my-php/data_show/get_data.php',{'g1':dev.gid,'t':lt}, function( data ) {
		if( parse_xml(data) ) {
			update_latest_value();
/*			
			update_flot( dev.p_plot, dev.p, dev.t );
			dev.p = [];
						
			update_flot( dev.f_plot, dev.f, dev.t );
			dev.f = [];
			
			update_flot( dev.r_plot, dev.r, dev.t );	
			dev.r = [];
*/			
			update_flot( dev.temp_plot, dev.temp, dev.t );
			dev.temp = [];
			
			var lt = dev.t[dev.t.length-1];
			dev.t = [];
			dev.t[0] = lt;
		}
	} );
	
	check_normal();
				
	setTimeout( "update()", 2*1000 );
}

function update_ths() {
	
	$.post( 'my-php/dev_control/get_normal.php',{'gid':dev.gid},function(data) {
		var ths = JSON.parse( data );
		if( ths.pth1!==undefined ) {
			if( dev.p_normal_th1!=ths.pth1 )
				dev.p_normal_th1 = parseFloat( ths.pth1 );
		}
		if( ths.pth2!==undefined ) {
			if( dev.p_normal_th2!=ths.pth2 )
				dev.p_normal_th2 = parseFloat( ths.pth2 );
		}
		
		if( ths.tth1!==undefined ) {
			if( dev.t_normal_th1!=ths.tth1 )
				dev.t_normal_th1 = parseFloat( ths.tth1 );
		}
		if( ths.tth2!==undefined ) {
			if( dev.t_normal_th2!=ths.tth2 )
				dev.t_normal_th2 = parseFloat( ths.tth2 );
		}
		
		if( ths.fth1!==undefined ) {
			if( dev.f_normal_th1!=ths.fth1 )
				dev.f_normal_th1 = parseFloat( ths.fth1 );
		}
		if( ths.fth2!==undefined ) {
			if( dev.f_normal_th2!=ths.fth2 )
				dev.f_normal_th2 = parseFloat( ths.fth2 );
		}
	} );
}

function check_normal() {

	var sig = 0;
	
	if( dev.new_t<=dev.t_normal_th1 || dev.new_t>=dev.t_normal_th2 ) {
		sig = 1;
		$('#t_alarm_show').text('温度异常');
	}
	else 
		$('#t_alarm_show').text('');
/*
	if( dev.new_p<=dev.p_normal_th1 || dev.new_p>=dev.p_normal_th2 ) {
		sig = 1;
		$('#p_alarm_show').text('压力异常');
	}
	else 
		$('#p_alarm_show').text('');
	
	if( dev.new_f<=dev.f_normal_th1 || dev.new_f>=dev.f_normal_th2 ) {
		sig = 1;
		$('#f_alarm_show').text('流量异常');
	}
	else 
		$('#f_alarm_show').text('');
*/	
	if( sig!=0 )
		$('#alarm_div').show();
	else
		$('#alarm_div').hide();
}

function add_flot( flot_holder ) {

	var flot_color = new Array();
	flot_color[0] = '#0011FF';
	flot_color[1] = '#FF0000';
	flot_color[2] = '#00AA00';
	flot_color[3] = '#00AAFF';

	var label_str = '';
	var c_id = 0;
	
	switch( flot_holder ) {
		case 'p_flot':
			label_str = "压力";
			c_id = 0;
			break;
		case 'temp_flot':
			label_str = "温度";
			c_id = 1;
			break;
		case 'f_flot':
			label_str = "流量";
			c_id = 2;
			break;
		case 'r_flot':
			label_str = "阻力";
			c_id = 3;
			break;
	}
	
	var d = new Date();
	var st = d.getTime()/1000 + dev.tz*3600;	// 当前电站本地时间，秒为单位
	d.setTime( st*1000 );
	var now_UTC_day = d.getUTCDate();
	var now_UTC_month = d.getUTCMonth();
	var now_UTC_year = d.getUTCFullYear();
	
	var plot = $.plot( '#'+flot_holder, [ 
		{ data:[], label: label_str } 
	], {
		series: {
			lines: {
				show: true,
				lineWidth: 2,
			}
		},
		yaxis: {
			tickDecimals: 1
		},
		xaxis: {
			tickLength: 0,
			mode: "time",
			timeformat: "%m-%d %H:%M",
			minTickSize: [30, 'minute'],
			//min: Date.UTC(now_UTC_year,now_UTC_month,now_UTC_day,0,0),
			//max: Date.UTC(now_UTC_year,now_UTC_month,now_UTC_day,23,59),
		},
		shadowSize: 0,
		colors: [ flot_color[c_id] ],
		grid: {
			borderWidth: {
				top: 0,
				right: 0,
				bottom: 2,
				left: 2
			},
			hoverable: true,
			clickable: true
		}
	});
	
	return plot;
}

/*
	<xml>
		<d>
			<n>name</n>
			<v t='t1'>××</v>
			<v t='t2'>××</v>
			<v t='t3'>××</v>
			<v t='t4'>××</v>
		</d>
		<d>
			<n>name</n>
			<v t='t1'>××</v>
			<v t='t2'>××</v>
			<v t='t3'>××</v>
			<v t='t4'>××</v>
		</d>
	</xml>
*/
function parse_xml( responseTxt ) {

	var xml = $(responseTxt);
	if ( xml.length<=0 )
		return false;
	
	var ds = xml.find('d');
	$.each( ds, function(i,value) {
		var v = $(value);
		var sp = new Array();
		var vn = '';
		
		var mid = v.children();
		$.each( mid, function(index, dom) {
			var v1 = $( dom );
			if( index==0 )
				vn = v1.text();
			else {
				if(i==0)
					dev.t[index-1] = parseInt( v1.attr('t') );
				sp[index-1] = parseFloat( v1.text() );
			}
		} );
		
		switch( vn ) {
			case '压力':
				dev.p = sp;
				break;
			case '温度':
				dev.temp = sp;
				break;
			case '流量':
				dev.f = sp;
				break;
			case '阻力':
				dev.r = sp;
				break;
		}
	} );
	return true;
}

function update_flot( plot, data, t ) {

	var dataset = plot.getData();
	var series = dataset[0];
	var i = t.length, loop = data.length;
	
	if( i>loop )
		var mt = t.slice(i-loop, i-1);
	else
		var mt = t.slice(0);
	
	if ( loop>0 ) {
		for (i=0; i<loop; i++)
			series.data.push( [(mt[i]+dev.tz*3600)*1000,data[i]] );	
		plot.setData( [series] );
		plot.setupGrid();
		plot.draw();
	}
}

function update_latest_value() {
	var t = dev.t[dev.t.length-1];	
	var t_str = formatDate( t+dev.tz*3600 );
/*	
	var vl = dev.p.length;
	if( vl>0 ) {
		$('#pv').html( dev.p[vl-1].toFixed(2) );
		dev.new_p = dev.p[vl-1];
		$('#pvt').html( t_str );
	}
		
	vl = dev.f.length;
	if( vl>0 ) {
		$('#fv').html( dev.f[vl-1].toFixed(2) );
		dev.new_f = dev.f[vl-1];
		$('#fvt').html( t_str );
	}
				
	vl = dev.r.length;
	if( vl>0 ) {
		$('#rv').html( dev.r[vl-1].toFixed(2) );
		$('#rvt').html( t_str );
	}
*/	
	vl = dev.temp.length;
	if( vl>0 ) {
		$('#tv').html( dev.temp[vl-1].toFixed(2) );
		dev.new_t = dev.temp[vl-1];
		$('#tvt').html( t_str );
	}
}

// UTC - 单位为: 秒
function formatDate( UTC ) {
	var d = new Date( UTC*1000 );
	var year = d.getUTCFullYear();     
	var month = d.getUTCMonth() + 1;     
	var date = d.getUTCDate();     
	var hour = d.getUTCHours();     
	var minute = d.getUTCMinutes();     
	var second = d.getUTCSeconds();     
	return   year+"-"+month+"-"+date+"   "+hour+":"+minute+":"+second;     
}