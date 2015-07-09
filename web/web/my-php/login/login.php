<?php

	if( !(isset($_POST['n']) && isset($_POST['p'])) )
		exit;
	
	$_POST['n'] = addslashes( $_POST['n'] );
	$_POST['p'] = addslashes( $_POST['p'] );
	
	$con = mysql_connect( "localhost", "root", "blue" );
	if ( !$con )
		die( 'Could not connect: ' . mysql_error() );

	mysql_query("SET NAMES 'utf8'", $con);
	
	$res = mysql_query( "SELECT passwd, type, valid FROM hx_k_db.user_t WHERE name='".$_POST['n']."'", $con );
	$row = mysql_fetch_array( $res );
	mysql_free_result ( $res );
	mysql_close( $con );
	
	if( $row[2]==0 )
		exit;
	
	$temp = md5( $_POST['n'].$_POST['p'] );
	if( $temp===$row[0] ) {
		$_SESSION['login'] = 1;
		if( $row[1]=='admin' ) {
			$_SESSION['admin'] = 1;
			echo 'devs_list.html?t=a';
		}
		else
			echo 'devs_list.html?t=n';
	}
?>