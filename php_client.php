<?php

	set_time_limit( 0 );
	ob_implicit_flush();
	
	$port = 1982;
	
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

	$ok = socket_bind( $socket, '0.0.0.0', 0 );
	if( $ok===false ) {
		echo "false  \r\n";
		echo "socket_bind() failed:reason:" . socket_strerror( socket_last_error( $socket ) )."\r\n";
		exit;
	}

	//$cmd = 'S[s001,open]';
	$cmd = 'D[s001,123.5]';
	socket_sendto( $socket, $cmd, strlen($cmd), 0, '127.0.0.1', $port ); 
	
	socket_close( $socket );

?>