<?php
/*
	$_POST['gid'] = 's001';
	$_POST['v_name'] = 'p';
	$_POST['th1'] = 12.5;
	$_POST['th2'] = 15.5;
*/	
	if( !isset($_POST['gid']) )
		exit;
	
	if( !isset($_POST['v_name']) )
		exit;
	
	$con = mysql_connect( "localhost", "root", "blue" );
	if ( !$con )
		die( 'Could not connect: ' . mysql_error() );

	mysql_query("SET NAMES 'utf8'", $con);
	
	$sql_str = "INSERT INTO sp20150709_db.normal_t SET v_name='".$_POST['v_name']."', th1=".$_POST['th1'].", th2=".$_POST['th2'].", gid='".$_POST['gid']."'";
	$res = mysql_query( $sql_str, $con );
	
	$sql_str = "UPDATE sp20150709_db.normal_t SET th1=".$_POST['th1'].", th2=".$_POST['th2']." WHERE gid='".$_POST['gid']."' AND v_name='".$_POST['v_name']."'";
	$res = mysql_query( $sql_str, $con );
	
	mysql_close( $con );
?>