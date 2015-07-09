<?php
	
	if( !isset($_POST['name']) || !isset($_POST['type']) || !isset($_POST['key']) || !isset($_POST['valid']) ) {
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
	
	$sql_str = "UPDATE hx_k_db.user_t SET type='".$_POST['type']."', passwd=MD5('".$_POST['name'].$_POST['key']."'), valid=".$_POST['valid']." WHERE name='".$_POST['name']."'";
	mysql_query( $sql_str, $con );
	$res = mysql_affected_rows();
	if( $res>0 )
		echo 'ok';
	else
		echo 'no';
	
	mysql_close( $con );
?>