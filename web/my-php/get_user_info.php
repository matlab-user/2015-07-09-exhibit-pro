<?php
/*
	{"name":"××","admin":"1"}
*/

	session_start();
	
	if( !isset($_SESSION['login']) || !isset($_SESSION['user']) )
		exit;
	
	if( !isset($_SESSION['admin']) )
		$if_admin = 0;
	else {
		if( $_SESSION['admin']==1 )
			$if_admin = 1;
		else
			$if_admin = 0;
	}
	
	$res = '{"name":"'.$_SESSION['user'].'","admin":"'.$if_admin.'"}';
	echo $res;
?>