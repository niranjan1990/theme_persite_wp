<?php

ob_start();
session_start(); 

require_once(__DIR__.'/../../../wp-config.php');
require_once(__DIR__.'/../../../wp-reviewconfig.php');
require_once(ABSPATH.'wp-content/themes/site/aws-ses-wp-mail-master/aws-ses-wp-mail.php'); 
$url = VBULLETINURL.'/wp_bulletin_registration.php';
if(isset($_POST['emailcap']) || $_POST['emailcap'] !="")
{
	die();
}
$fields = array(
	'username' => ($_POST['username']),
	'password' => ($_POST['password']),
	'email' => ($_POST['email']),
);


//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
$info = curl_getinfo($ch);
//execute post
 $result = curl_exec($ch);
 $res=json_decode($result);
//close connection
curl_close($ch);
if($res->flag=="failed"){
	// echo "<script type='text/javascript'>alert('".$res->msg."')</script>";
	
	echo $res->msg;
	
}else{
	
	$_SESSION['userid'] = $res->msg;
	$_SESSION['username'] = $res->username;
	$_SESSION['email'] = $_POST['email'];
	 function generatePIN($digits = 4){
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while($i < $digits){
			//generate a random number between 0 and 9.
			$pin .= mt_rand(0, 9);
			$i++;
		}
		return $pin;
	}
	 
	//If I want a 4-digit PIN code.
	$pin = generatePIN();
	
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 
		// Check connection
		if($link === false){
			die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		 
		// Attempt insert query execution
		$sql = "INSERT INTO wp_vbulltein_user_activation (userid,email,security_code,date_created ) VALUES ('".$res->msg."','".$res->email."','".$pin."','".date('Y-m-d H:i:s')."' )";
		$sqlvm1 = mysqli_query($link,"SELECT * FROM wp_options where option_name = 'aws_vm_subject'");
		$sqlvm2 = mysqli_query($link,"SELECT * FROM wp_options where option_name = 'aws_vm_content'");
		$vm1=mysqli_fetch_array($sqlvm1);
		$vm2=mysqli_fetch_array($sqlvm2);
		if(  mysqli_query($link, $sql))
		{
			
			 $to=$_POST['email'];
			//$subject="Confirmation Code";
			$subject=$vm1['option_value'];
			//$message="Hi, Your Activation Code is $pin";
			$message =str_replace('$pin',$pin,$vm2['option_value']);
			//$message=$vm2['option_value'];
		$from_email = AWS_SES_WP_MAILINGADDRESS;
     $from_name = AWS_SES_WP_MAILINGNAME;
      $headers=array('From: '.$from_name.' <'.$from_email.'>');
     $mail= wp_mail( $to, $subject, $message, $headers, $attachments = array() );
		} 
		else{
				echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
		}
		
 
		
	 
}
 
 
 



?>
