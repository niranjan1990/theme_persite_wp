<?php  


 require_once(__DIR__.'/../../../wp-config.php');
require_once(__DIR__.'/../../../wp-reviewconfig.php');
require_once(ABSPATH.'wp-content/themes/site/aws-ses-wp-mail-master/aws-ses-wp-mail.php'); 
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
	
	
	


		$sqlvm1 = mysqli_query($connection,"SELECT * FROM wp_options where option_name = 'aws_vm_subject'");
		$sqlvm2 = mysqli_query($connection,"SELECT * FROM wp_options where option_name = 'aws_vm_content'");
		$vm1=mysqli_fetch_array($sqlvm1);
		$vm2=mysqli_fetch_array($sqlvm2);
		if(  mysqli_query($connection, $sql))
		{
			
			 $to=$_GET['email'];
			//$subject="Confirmation Code";
			$subject=$vm1['option_value'];
			//$message="Hi, Your Activation Code is $pin";
			$message =str_replace('$pin',$dbcode,$vm2['option_value']);
			//$message=$vm2['option_value'];
		  $from_email = AWS_SES_WP_MAILINGADDRESS;
                    $from_name = AWS_SES_WP_MAILINGNAME;
                   $headers=array('From: '.$from_name.' <'.$from_email.'>');
                    $mail= wp_mail( $to, $subject, $message, $headers, $attachments = array() );

		} 

		echo 1;




 ?>
