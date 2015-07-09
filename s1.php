<?php

set_time_limit(0); 
ob_implicit_flush(); 

/* handle signals */
//pcntl_signal(SIGTERM, 'sig_handler'); 
//pcntl_signal(SIGINT, 'sig_handler'); 
//pcntl_signal(SIGCHLD, 'sig_handler'); 

/* change this to your own host / port */ 
$__server_listening = true; 

$host = "192.168.1.24";
$port = 1091;

server_loop( $host, $port ); 

//--------------------------------------------------------------------------------------------
// 					funs
//--------------------------------------------------------------------------------------------
function interact($socket) { 
    /* TALK TO YOUR CLIENT */ 
	GLOBAL $host, $port; 
		
	socket_getpeername ( $socket , $r_ip, $r_port );
	echo "get client connection!\n"; 
	echo 'r_ip-'.$r_ip.'      r_port-'.$r_port."\n";
	
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
	
	socket_set_option( $sudp, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>6, "usec"=>0 ) );
	socket_set_option( $sudp, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>3, "usec"=>0 ) );
	
	$ok = socket_bind( $sudp, $host, $port );
	if( $ok===false ) {
		echo "false  \r\n";
		echo "UDP socket_bind() failed:reason:" . socket_strerror( socket_last_error($sudp) )."\r\n";
		exit;
	}

	$msg = "2";
	$sock_data = socket_write( $socket, $msg, strlen($msg) );
	//echo $sock_data."\n";
	sleep( 1 );
	
	$msg = "ABCDEFGHI1234567890";	
	echo $r_ip."      ".$r_port."\n";
	$sock_data = socket_sendto( $sudp, $msg, strlen($msg), 0, $r_ip, $r_port ); 
	
	$l_p = 0;
	socket_recvfrom( $sudp, $buf, 4, 0, $r_ip, $l_p );
	echo $buf."\n";
	echo "UDP send---".$sock_data."     msg---".$msg."\n";
		
	socket_close( $sudp ); 
} 

/** 
  * Creates a server socket and listens for incoming client connections 
  * @param string $address The address to listen on 
  * @param int $port The port to listen on 
  */ 
function server_loop($address, $port) { 

    GLOBAL $__server_listening; 

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

    if( ($ret=socket_bind($sock, $address, $port))<0 ) { 
        echo "failed to bind socket: ".socket_strerror($ret)."\n"; 
        exit(); 
    } 

    if( ($ret = socket_listen( $sock, 0 ))<0 )  { 
        echo "failed to listen to socket: ".socket_strerror($ret)."\n"; 
        exit(); 
    } 

    socket_set_nonblock( $sock ); 
    	
    echo "waiting for clients to connect\n"; 

    while( $__server_listening ) { 
        $connection = @socket_accept( $sock ); 
        if( $connection===false ) { 
            usleep(100); 
        }elseif( $connection>0 ) {
            handle_client( $sock, $connection ); 
        }else { 
            echo "error: ".socket_strerror($connection); 
            die; 
        } 
    } 
} 

//  Signal handler 
function sig_handler( $sig )  { 
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

// Handle a new client connection 
function handle_client( $ssock, $csock ) { 
    GLOBAL $__server_listening; 

    $pid = pcntl_fork();
	
    if( $pid==-1 ) { 
        /* fork failed */ 
        echo "fork failure!\n"; 
        die; 
    }elseif( $pid==0 ) { 
        /* child process */ 
        $__server_listening = false; 
		
		$l_ip = '';
		$l_port = 0;	
		socket_getsockname( $ssock, $l_ip, $l_port );		// 获取绑定的 ip、port
		echo "l_ip--".$l_ip."    l_port--".$l_port."\n";
			
        socket_close( $ssock ); 
        interact( $csock ); 
        socket_close( $csock ); 
		echo "END\n";
    }else
        socket_close( $csock ); 
} 
?>