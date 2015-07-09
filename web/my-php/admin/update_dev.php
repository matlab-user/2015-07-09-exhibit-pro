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

	$sql_str = "UPDATE hx_k_db.dev_t SET name='".$_POST['name']."', valid=".$valid." WHERE gid='".$_POST['guid']."'";
	mysql_query( $sql_str, $con );
	$res = mysql_affected_rows();
	if( $res>0 )
		echo 'ok';
	else
		echo 'no';
	
	mysql_close( $con );
?>