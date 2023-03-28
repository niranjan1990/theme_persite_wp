<?php  
error_reporting(E_ALL & ~E_NOTICE);
 require_once(__DIR__.'/../../../wp-config.php');
 $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 if($connection === false){
			die("ERROR: Could not connect. " . mysqli_connect_error());
		}
	mysqli_select_db($connection, DB_NAME);
	$userid=$_GET['userid'];
	$sql = "SELECT security_code FROM wp_vbulltein_user_activation WHERE userid = ".$userid."";
	$result = mysqli_query($connection,$sql);
	$row = mysqli_fetch_array($result);
	$dbcode=$row['security_code'];
	require_once(__DIR__.'/aws-ses-wp-mail-master/aws-ses-wp-mail.php'); 
			$to=$_GET['email'];
			$subject="Resending Confirmation Code";
			$message='Hi, Your Activation Code is '.$dbcode.'.';
			$mail= wp_mail( $to, $subject, $message, $headers = array(), $attachments = array() );
		echo "Activation Code has been re-sent";




 ?>