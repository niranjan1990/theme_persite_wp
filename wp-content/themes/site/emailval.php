<?php
ob_start();
session_start(); 
global $wpdb,$GRclass;
require_once(__DIR__.'/../../../wp-reviewconfig.php');
require_once(__DIR__.'/../../../wp-config.php');
	 $url = VBULLETINURL.'/wp_bulletin_emailid.php?emailid='.$_GET['emailid'];
	 
	 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

    $output=curl_exec($ch);
 
    curl_close($ch);
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
				
			$encrypt = my_simple_crypt( trim($_GET['emailid']), 'e' );
   echo $output."|".$encrypt;