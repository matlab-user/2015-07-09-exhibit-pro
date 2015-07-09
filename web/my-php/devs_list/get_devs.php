<?php
/*
	<xml>
		<d id='××'>
			<n>name</n>
			<v>valid</v>
		</d>
		<d id='××'>
			<n>name</n>
			<v>valid</v>
		</d>
	</xml>
*/

//	if( !isset($_POST['g1']) )
//		exit;

	$xml = '<xml>';
	
	$con = mysql_connect( "localhost", "root", "blue" );
	if ( !$con )
		die( 'Could not connect: ' . mysql_error() );

	mysql_query("SET NAMES 'utf8'", $con);
	
	$res = mysql_query( "SELECT gid, name, valid FROM hx_k_db.dev_t LIMIT 100", $con );
	while( $row = mysql_fetch_array( $res ) ) {
		$xml .= '<d id='.$row[0].'>';
		$xml .= '<n>'.$row[1].'</n>';
		$xml .= '<v>'.$row[2].'</v>';
		$xml .= '</d>';
	}
	$xml .= '</xml>';
		
	mysql_free_result ( $res );
	mysql_close( $con );

	echo $xml."\r\n";
?>