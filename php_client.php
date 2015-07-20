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
	
	socket_set_option( $socket, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>3, "usec"=>0 ) );
		
	$ok = socket_bind( $socket, '0.0.0.0', 0 );
	if( $ok===false ) {
		echo "false  \r\n";
		echo "socket_bind() failed:reason:" . socket_strerror( socket_last_error( $socket ) )."\r\n";
		exit;
	}

	//$cmd = 'S[s001,open]';
	for( $i=0; $i<4; $i++ ) {
		$cmd = 'D[s001,23.5]';
		socket_sendto( $socket, $cmd, strlen($cmd), 0, 'www.swaytech.biz', $port ); 
		
		$cmd = 'I[s001]';
		socket_sendto( $socket, $cmd, strlen($cmd), 0, 'www.swaytech.biz', $port ); 
		
		$r = array( $socket );
		$num = socket_select( $r, $w=NULL, $e=NULL, 20 );
		if( $num===false )
			echo "socket_select() failed, reason: ".socket_strerror(socket_last_error())."\n";
		elseif( $num>0 ) {
			socket_recvfrom( $socket, $buf, 1000, 0, $to_ip, $to_port );
			echo "res-----".$buf."\r\n";
			$cmd = 'OK';
			socket_sendto( $socket, $cmd, strlen($cmd), 0, 'www.swaytech.biz', $port ); 
		}
	}
	
	socket_close( $socket );

?>