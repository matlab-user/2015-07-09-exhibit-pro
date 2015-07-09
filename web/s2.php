<?php
	set_time_limit(0); 
	ob_implicit_flush(); 

	/* handle signals */
	//pcntl_signal(SIGTERM, 'sig_handler'); 
	//pcntl_signal(SIGINT, 'sig_handler'); 
	//pcntl_signal(SIGCHLD, 'sig_handler'); 

	//$host = "192.168.1.24";
	$host = "0.0.0.0";
	$port = 1091;

	udp_server_loop( $host, $port ); 

//--------------------------------------------------------------------------------------------
// 					funs
//--------------------------------------------------------------------------------------------
function send_cmd( $sudp, $to_ip, $to_port ) {
 		
		$msg = "ABCDEFGHI1234567890";	
		$sock_data = socket_sendto( $sudp, $msg, strlen($msg), 0, $to_ip, $to_port ); 
		echo "UDP send---".$sock_data."     msg---".$msg."\n";
		
		$r = array( $sudp );
		$w = NULL;
		$e = NULL;
		
		$num = socket_select( $r, $w, $e, 10 );
		if( $num===false )
			echo "socket_select() failed, reason: ".socket_strerror(socket_last_error())."\n";
		elseif( $num>=0 ) {
			if( $num>0 ) {
				socket_recvfrom( $sudp, $buf, 4, 0, $to_ip, $to_port );
				echo "op_res---".$buf."\n";
			}
		}
} 

/** 
  * Creates a server socket and listens for incoming client connections 
  * @param string $address The address to listen on 
  * @param int $port The port to listen on 
  */ 
function udp_server_loop($address, $port) { 

    GLOBAL $host, $port; 

 	$sudp = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
	if( $sudp===false ) {
		echo "UDP socket_create() failed:reason:" . socket_strerror( socket_last_error() ) . "\n";
		exit();
	}
	
	$ok = socket_bind( $sudp, $host, $port );
	if( $ok===false ) {
		echo "UDP socket_bind() failed:reason:" . socket_strerror( socket_last_error($sudp) )."\r\n";
		exit();
	}
	
	socket_set_option( $sudp, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>6, "usec"=>0 ) );
	socket_set_option( $sudp, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>3, "usec"=>0 ) );
	
    socket_set_nonblock( $sudp ); 
    	
	$done = 0;
	echo "UDP server starting!\n";
	do {
		
		$r = array( $sudp );
		$w = NULL;
		$e = NULL;
	
		$num_changed_sockets = socket_select( $r, $w, $e, 20 );
		if( $num_changed_sockets===false ) {
			/* Error handling */
			echo "socket_select() failed, reason: ".socket_strerror(socket_last_error())."\n";
			socket_close( $sudp );
		}elseif( $num_changed_sockets>=0 ) {
			/* At least at one of the sockets something interesting happened */
			if( $num_changed_sockets>0 ) {
				$from = '';
				$r_port = 0;
				socket_recvfrom( $r[0], $buf, 12, 0, $from, $r_port );
				echo "sssss--".$buf."\n";
				echo "ffffff--".$from."      ".$r_port."\n";
				
				send_cmd( $sudp, $from, $r_port );
			}
		}
	}while(!$done);
	
	socket_close( $sudp );
} 

//  Signal handler 
function sig_handler( $sig ) { 
    switch($sig) { 
        case SIGTERM: 
        case SIGINT:
            exit(); 
        break; 

        case SIGCHLD: 
            pcntl_waitpid (-1, $status ); 
        break; 
    } 
} 
	
?>