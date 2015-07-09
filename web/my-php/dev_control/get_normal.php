<?php
	// 返回字符串 '{"pth1";"xx","pth2":"xx","tth1":"xx","tth2":"xx","fth1":"xx","fth2":"xx"}'
	
	if( !isset($_POST['gid']) )
		exit;
	
	$json = '{';
	
	$con = mysql_connect( "localhost", "root", "blue" );
	if ( !$con )
		die( 'Could not connect: ' . mysql_error() );

	mysql_query("SET NAMES 'utf8'", $con);
	
	$sql_str = "SELECT v_name, th1, th2 FROM hx_k_db.normal_t WHERE gid='".$_POST['gid']."'";
	$res = mysql_query( $sql_str, $con );
	if( empty($res) )
		return $res_str;
	
	while( $row = mysql_fetch_array($res) ) {
		$json .= '"'.$row[0].'th1'.'":'.'"'.$row[1].'",';
		$json .= '"'.$row[0].'th2'.'":'.'"'.$row[2].'",';
	}
	
	$json = substr( $json, 0, strlen($json)-1 ).'}';
	
	mysql_close( $con );
	
	echo $json;
?>