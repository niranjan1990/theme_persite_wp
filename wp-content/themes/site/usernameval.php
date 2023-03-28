<?php
ob_start();
session_start(); 
global $wpdb,$GRclass;
require_once(__DIR__.'/../../../wp-reviewconfig.php');
require_once(__DIR__.'/../../../wp-config.php');
	 $url = VBULLETINURL.'/wp_bulletin_username.php?username='.$_GET['username'];
	 
	 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

    $output=curl_exec($ch);
 
    curl_close($ch);
    echo $output;