<?php
/*
	JSON 格式举例
	{"type":"admin","valid":"1"}
*/
	if( !isset($_POST['name']) ) {
		echo 'no';
		exit;
	}
	
	$con = mysql_connect( "localhost", "root", "blue" );
	if ( !$con )
		die( 'Could not connect: ' . mysql_error() );

	mysql_query("SET NAMES 'utf8'", $con);
	
	$res = "{";
	$sql_str = "SELECT type,valid FROM sp20150709_db.user_t WHERE name='".$_POST['name']."'";
	$r = mysql_query( $sql_str, $con );
	if( $r ) {
		$row = mysql_fetch_array( $r );
		if( !empty($row[0]) && !empty($row[1]) ) {
			$res .= '"type":"'.$row[0].'","valid":"'.$row[1].'"';
			$res .= "}";
			echo $res;
		}
		else
			echo 'no';
	}
	else
		echo 'no';
	
	mysql_close( $con );
?>