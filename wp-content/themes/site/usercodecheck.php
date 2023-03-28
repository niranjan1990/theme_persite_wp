<?php  
ob_start();
session_start(); 
global $wpdb,$GRclass;


require_once(__DIR__.'/../../../wp-reviewconfig.php');
require_once(__DIR__.'/../../../wp-config.php');
mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);
mysql_select_db(DB_RNAME);

 $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 

 if($connection === false){
			die("ERROR: Could not connect. " . mysqli_connect_error());
		}
	mysqli_select_db($connection, DB_NAME);
	$userid=$_GET['userid'];
	$username=$_GET['username'];
	$confirmcode=$_GET['confirmcode'];
	if($_GET['newsletter']=='1')
	{
		$newsletter='1';
	}
	else
	{
		$newsletter='0';
	}
	if($_GET['hotdealemail']=='1')
	{
		$hotdealemail='1';
	}
	else
	{
		$hotdealemail='0';
	}
	if($_GET['adminemail']=='1')
	{
		$adminemail='1';
	}
	else
	{
		$adminemail='0';
	}
	if($_GET['showemail']=='1')
	{
		$showemail='1';
	}
	else
	{
		$showemail='0';
	}
	if($_GET['receivefriendrequest']=='1')
	{
		$receivefriendrequest='1';
	}
	else
	{
		$receivefriendrequest='0';
	}
	
	
	
	$sql = "SELECT security_code FROM wp_vbulltein_user_activation WHERE userid = ".$userid."";
	$result = mysqli_query($connection,$sql);
	$row = mysqli_fetch_array($result);
	$dbcode=$row['security_code'];
	
	if($dbcode==$confirmcode)
	{
		$sqlupdate = mysqli_query($connection,"update wp_vbulltein_user_activation set newsletter='".$newsletter."',hotdealemail='".$hotdealemail."',date_activated='".date('Y-m-d H:i:s')."' where userid='".$userid."'");

		$url = VBULLETINURL.'/wp_bulletin_activation.php';
		$url1 = VBULLETINURL.'/wp_bulletin_setoptions.php';

		$fields = array(
			'userid' => $userid,
		);
		$fields1 = array(
			'userid' => $userid,
			'adminemail' => $adminemail,
			'showemail' => $showemail,
			'receivefriendrequest' => $receivefriendrequest,
		);


		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
		$info = curl_getinfo($ch);
		$result = curl_exec($ch);


		//open connection
		$ch1 = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch1,CURLOPT_URL, $url1);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch1,CURLOPT_POST, count($fields1));
		curl_setopt($ch1,CURLOPT_POSTFIELDS, $fields1);
		$info1 = curl_getinfo($ch1);
		$result1 = curl_exec($ch1);





		my_setcookie_example($userid,$username);

		echo "Correct";
		
		
		
		
	}
	else
	{
		echo "Verification Code Did Not Match";
	}




 ?>
