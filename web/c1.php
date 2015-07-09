<?php

set_time_limit(0); 
ob_implicit_flush(); 

/* change this to your own host / port */ 
$__server_listening = true; 

$host = "192.168.1.24";
$port = 1091;

$l_ip = '';
$l_port ='';

$res = connect_server( $host, $port ); 
if( $res==true ) {
	start_udp();
}

//--------------------------------------------------------------------------------------------
// 					funs
//--------------------------------------------------------------------------------------------
/** 
  * Creates a server socket and listens for incoming client connections 
  * @param string $address The address to listen on 
  * @param int $port The port to listen on 
  */ 
function connect_server($address, $port) { 

    GLOBAL $__server_listening, $l_ip, $l_port; 

    if( ($sock=socket_create(AF_INET, SOCK_STREAM, 0))<0 ) { 
        echo "failed to create socket: ".socket_strerror($sock)."\n"; 
        exit(); 
    } 
	
	socket_set_option( $sock, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>10, "usec"=>0 ) );
	socket_set_option( $sock, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>3, "usec"=>0 ) );
	$rval = socket_set_option( $sock, SOL_SOCKET, SO_REUSEADDR, 1 );
	if( $rval===false ) {
		echo 'Unable to get socket option: '. socket_strerror(socket_last_error()) . PHP_EOL;
	} elseif( $rval!==0 )
		//echo 'SO_REUSEADDR is set on socket !' . PHP_EOL;
	
    //socket_set_nonblock( $sock ); 
	
	$timeout = 10;
	$time = time();
    while( !@socket_connect($sock, $address, $port) ) {
		$err = socket_last_error($sock);
		if( $err==115||$err==114 ) {
			if( (time()-$time)>=$timeout ) {
				socket_close( $sock );
				//die("Connection timed out.\n");
				return false;
			}
			sleep(1);
			continue;
		}
		//die(socket_strerror($err) . "\n");
		return false;
	}
		
	socket_getsockname( $sock, $l_ip, $l_port );		// 获取绑定的 ip、port
	echo "l_ip--".$l_ip."    l_port--".$l_port."\n";
		
	$msg = "234";
	$sock_data = socket_write( $sock, $msg, strlen($msg) );
	$msg = socket_read( $sock, 9 );
	echo "msg--".$msg."\n";
	
	socket_close($sock);
	
	return true;
} 

function start_udp() { 
    /* TALK TO YOUR CLIENT */ 
	GLOBAL $l_ip, $l_port, $host, $port; 

	$sudp = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
	if( $sudp===false ) {
		echo "UDP socket_create() failed:reason:" . socket_strerror( socket_last_error() ) . "\n";
		exit;
	}
	
	$rval = socket_set_option( $sudp, SOL_SOCKET, SO_REUSEADDR, 1 );
	if( $rval===false ) {
		echo 'Unable to get UDP socket option: '. socket_strerror(socket_last_error()) . PHP_EOL;
	} else if ($rval !== 0) {
		//echo 'UDP SO_REUSEADDR is set on socket !' . PHP_EOL;
	}
	
	$ok = socket_bind( $sudp, $l_ip, $l_port );
	if( $ok===false ) {
		echo "false  \r\n";
		echo "UDP socket_bind() failed:reason:" . socket_strerror( socket_last_error($sudp) )."\r\n";
		exit;
	}
	
	socket_set_option( $sudp, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>6, "usec"=>0 ) );
	socket_set_option( $sudp, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>3, "usec"=>0 ) );
	
	socket_recvfrom( $sudp, $buf, 9, 0, $host, $port );
	echo "UDP recv---".$buf."\n";
		
	socket_close( $sudp ); 
} 
?>