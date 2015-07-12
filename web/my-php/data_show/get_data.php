<?php
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
	
	name = 压力、流量、温度、阻力
	
	$_POST['t'] - ==0时，表示页面第一次读取数据
*/
//	$_POST['g1'] = 's001';
	
	if( !isset($_POST['g1']) )
		exit;
	
	if( !isset($_POST['t']) )
		$_POST['t'] = 0;

	if( $_POST['t']==0 )
		$_POST['t'] = get_today_start( 8 );					// 默认8时区
	
	$xml = '<xml>';

	$con = mysql_connect( "localhost", "root", "blue" );
	if ( !$con )
		die( 'Could not connect: ' . mysql_error() );

	mysql_query("SET NAMES 'utf8'", $con);
/*	
	$sql_str = "SELECT MAX(batch) FROM sp20150709_db.data_t WHERE dev_id='".$_POST['g1']."'";
	$res = mysql_query( $sql_str, $con );
	if( !empty($res) ) {
		$row = mysql_fetch_array( $res );
		$batch = $row[0];
		mysql_free_result ( $res );
	}
	else {
		echo '';
		return;
	}

	$xml .= "<d><n>压力</n>";
	$xml .= get_d( $_POST['g1'], "p", $_POST['t'], $batch, $con );
	$xml .= "</d>";
	 
	$xml .= "<d><n>流量</n>";
	$xml .= get_d( $_POST['g1'], "f", $_POST['t'], $batch, $con );
	$xml .= "</d>";
	
	$xml .= "<d><n>阻力</n>";
	$xml .= get_d( $_POST['g1'], "r", $_POST['t'], $batch, $con );
	$xml .= "</d>";
*/
	
	$xml .= "<d><n>温度</n>";
	$xml .= get_d( $_POST['g1'], "t", $_POST['t'], 0, $con );
	$xml .= "</d>";
	
	$xml .= '</xml>';
	mysql_close( $con );

	echo $xml."\r\n";
	
//----------------------------------------------------------------------------------------
function get_d( $dev_id, $v_name, $t, $batch, $con ) {
	$res_str = '';
	$sql_str = "SELECT value, time FROM sp20150709_db.data_t WHERE v_name='".$v_name."' AND dev_id='".$dev_id."' AND batch=".$batch." AND time>".$t." AND time<=".time()." ORDER BY time";
	$res = mysql_query( $sql_str, $con );
	if( empty($res) )
		return $res_str;
	
	while( $row = mysql_fetch_array($res) )
		$res_str .= '<v t='.$row[1].'>'.$row[0].'</v>';

	return $res_str;
}

// 计算设备所在地的当天起始UTC时间
function get_today_start( $tz ) {
	$t = time();
	$t = $t + $tz*3600;
	$year = gmdate("Y", $t);
	$mon = gmdate("m", $t);
	$day = gmdate("d", $t);
	$t = gmmktime(0,0,0,$mon,$day,$year);
	$t -= $tz*3600;
	return $t;
}
?>