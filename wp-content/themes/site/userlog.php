<?php
ob_start();
session_start(); 
global $wpdb,$GRclass;


require_once(__DIR__.'/../../../wp-reviewconfig.php');
require_once(__DIR__.'/../../../wp-config.php');
	

 if(isset($_POST['emailcap']) || $_POST['emailcap'] !="")
{
	die();
}
	 
	 $url = VBULLETINURL.'/wp_bulletin_login.php';
	$fields = array(
		'password' => ($_POST['password']),
		'email' => (trim($_POST['email'])),
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
 //print_r($result);
 $res=json_decode($result);
	 //echo $result;
	
$link= mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	 if($res->flag=="logged In")
	 {
		$qry = mysqli_query($link,"select * from wp_vbulltein_user_activation where userid='".$res->userid."'");
		$rowcount=mysqli_num_rows($qry);
		if($rowcount == 0)
		{
			$qry1 = mysqli_query($link,"INSERT INTO wp_vbulltein_user_activation (userid,email,security_code,date_created ) VALUES ('".$res->userid."','".$res->email."','".rand(1000,9999)."','".date('Y-m-d H:i:s')."' )");	
		}

		my_setcookie_example($res->userid,$res->username);
		$_SESSION['checkuser'] = 'True';
		echo $res->flag;
		//print_r($res->flag);		
	// exit();
		
		
	 }
	 else
	 {
		 if($res->flag=='Un-Activated Account.')
		 {
			$qry = mysqli_query($link,"select * from wp_vbulltein_user_activation where userid='".$res->userid."'");
			$rowcount=mysqli_num_rows($qry);
			if($rowcount == 0)
			{
				$qry1 = mysqli_query($link,"INSERT INTO wp_vbulltein_user_activation (userid,email,security_code ) VALUES ('".$res->userid."','".$res->email."','".rand(1000,9999)."' )");	
			}
			
			
			
			function my_simple_crypt( $string, $action = 'e' ) 
			{
				// you may change these values to your own
				$secret_key = 'my_simple_secret_key';
				$secret_iv = 'my_simple_secret_iv';
			 
				$output = false;
				$encrypt_method = "AES-256-CBC";
				$key = hash( 'sha256', $secret_key );
				$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
			 
				if( $action == 'e' ) {
					$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
				}
				else if( $action == 'd' ){
					$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
				}
			 
				return $output;
			}	
				
			$encrypt = my_simple_crypt( trim($res->email), 'e' );	


			
			echo $res->flag."|".$encrypt;
		 }
		 else
		 {
			echo $res->flag;
		 }
		// print_r($res->flag);	
		 //return("emailid or password not valid");
	 }
