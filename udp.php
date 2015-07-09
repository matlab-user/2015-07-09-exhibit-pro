<?php

set_time_limit( 0 );
ob_implicit_flush();

$socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
if ( $socket === false ) {
    echo "socket_create() failed:reason:" . socket_strerror( socket_last_error() ) . "\n";
	exit;
}

$rval = socket_get_option($socket, SOL_SOCKET, SO_REUSEADDR);
if ($rval === false) {
    echo 'Unable to get socket option: '. socket_strerror(socket_last_error()) . PHP_EOL;
} else if ($rval !== 0) {
    echo 'SO_REUSEADDR is set on socket !' . PHP_EOL;
}

$ok = socket_bind( $socket, '127.0.0.1', 80 );
if ( $ok === false ) {
	echo "false  \r\n";
    echo "socket_bind() failed:reason:" . socket_strerror( socket_last_error( $socket ) )."\r\n";
	exit;
}
while ( true ) {
    $from = "";
    $port = 0;
    socket_recvfrom( $socket, $buf,1024, 0, $from, $port );
    echo $buf;
    usleep( 1000 );
}
?>