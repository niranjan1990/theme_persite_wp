<?php
if(is_numeric($_GET['id']) && is_numeric($_GET['rating'])) {
	global $GRclass;
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
	
	$return = $GRclass->cr_do_qik_rate($_GET['id'],$_GET['rating']);
	
	if($return) {	
		echo $return;
	} else {
		echo 0;
	}
} else {
	echo 'Invalid request.';
}
?>