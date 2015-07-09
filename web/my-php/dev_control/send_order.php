<?php

	session_start();
	
	set_time_limit( 6 );
	ob_implicit_flush();
	
//	$_POST['dev'] = 's001';
//	$_POST['order'] = 'wangdehui';
	
	if( !isset($_POST['dev']) || !isset($_POST['order']) )
		exit;
	
	$port = 1024;
	
	$socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
	if( $socket===false ) {
		echo "socket_create() failed:reason:" . socket_strerror( socket_last_error() ) . "\n";
		exit;
	}

	$rval = socket_get_option($socket, SOL_SOCKET, SO_REUSEADDR);
	if( $rval===false )
		echo 'Unable to get socket option: '. socket_strerror(socket_last_error()).PHP_EOL;
	elseif( $rval!==0 )
		echo 'SO_REUSEADDR is set on socket !'.PHP_EOL;
	socket_set_option( $socket, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>6, "usec"=>0 ) );

	$ok = socket_bind( $socket, '0.0.0.0', 0 );
	if( $ok===false ) {
		echo "false  \r\n";
		echo "socket_bind() failed:reason:" . socket_strerror( socket_last_error( $socket ) )."\r\n";
		exit;
	}

	$cmd = $_POST['order'];
	socket_sendto( $socket, $cmd, strlen($cmd), 0, '127.0.0.1', $port ); 
	
	$rev_num = socket_recvfrom( $socket, $buf, 20, 0, $lip='127.0.0.1', $port );
	$msg = 'FAIL';
	if( $rev_num==True ) {
		if( $buf==='OK' )
			$msg = 'OK';
	}
	socket_close( $socket );
	
	echo $msg;
?>