<?php

	if( !isset($_POST['name']) || !isset($_POST['guid']) || !isset($_POST['valid']) ) {
		echo 'no';
		exit;
	}
	
	$con = mysql_connect( "localhost", "root", "blue" );
	if ( !$con )
		die( 'Could not connect: ' . mysql_error() );

	mysql_query("SET NAMES 'utf8'", $con);
	
	if( $_POST['valid']=='true' )
		$valid = 1;
	else
		$valid = 0;
	
	$sql_str = "INSERT INTO hx_k_db.dev_t (name,gid,valid) VALUES ('".$_POST['name']."','".$_POST['guid']."',".$valid." )";
	$res = mysql_query( $sql_str, $con );
	mysql_free_result ( $res );
		
	$res = mysql_query( "SELECT ROW_COUNT()", $con );
	if( !empty($res) ) {
		$row = mysql_fetch_array( $res );
		$mid = intval( $row[0] );
		if( $mid>0 )
			echo 'ok';
		else
			echo $row[0];
	}
	else
		echo 'no';
	
	mysql_close( $con );
?>