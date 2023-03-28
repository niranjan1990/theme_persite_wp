<?php 
include('wp-config.php');

$con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
// Check connection
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: ".DB_NAME."" . mysqli_connect_error();
}
else
{
	//echo "Wordpress ( Base DB - ".DB_NAME." ) Database Connected";
}
  
  
  
  
  

//SQL For Venice Product DB connection

$sql_vdb="SELECT * FROM wp_options where option_name = 'dbconnection'";
$result_vdb=mysqli_query($con,$sql_vdb);
$row_vdb=mysqli_fetch_array($result_vdb,MYSQLI_ASSOC);


$return_vdb = explode('|', $row_vdb["option_value"]);

  
/** The name of the database for WordPress */
define('DB_RNAME', $return_vdb[2]);

/** MySQL database username */
define('DB_RUSER', $return_vdb[0]);

/** MySQL database password */
define('DB_RPASSWORD', $return_vdb[1]);

/** MySQL hostname */
define('DB_RHOST', $return_vdb[3]);
















//SQL For cookie DB connection

$sql_cd="SELECT * FROM wp_options where option_name = 'cookie_domain'";
$result_cd=mysqli_query($con,$sql_cd);
$row_cd=mysqli_fetch_array($result_cd,MYSQLI_ASSOC);


define('COOKIE_DOMAIN_NEW', $row_cd['option_value']);

  

  
  
  
  
  
  
  
  
  
  
  
  

//SQL For vbulletin_domain DB connection

$sql_vd="SELECT * FROM wp_options where option_name = 'vbulletin_domain'";
$result_vd=mysqli_query($con,$sql_vd);
$row_vd=mysqli_fetch_array($result_vd,MYSQLI_ASSOC);


define('VBULLETINURL',$row_vd['option_value']);










/** START AWS SES WP MAIL CONFIG **/


$sql_fd="SELECT * FROM wp_options where option_name = 'aws_fromemail'";
$result_fd=mysqli_query($con,$sql_fd);
$row_fd=mysqli_fetch_array($result_fd,MYSQLI_ASSOC);

define('AWS_SES_WP_MAILINGADDRESS',$row_fd['option_value']);
define('AWS_SES_WP_MAIL_USE_INSTANCE_PROFILE', true);

//Friendly Name
$sql_fn="SELECT * FROM wp_options where option_name = 'aws_fname'";
$result_fn=mysqli_query($con,$sql_fn);
$row_fn=mysqli_fetch_array($result_fn,MYSQLI_ASSOC);
define('AWS_SES_WP_MAILINGNAME',$row_fn['option_value']);

//Mail Region
$sql_mr="SELECT * FROM wp_options where option_name = 'aws_mailregion'";
$result_mr=mysqli_query($con,$sql_mr);
$row_mr=mysqli_fetch_array($result_mr,MYSQLI_ASSOC);

define('AWS_SES_WP_MAIL_REGION',$row_mr['option_value']);

/** END AWS SES WP MAIL CONFIG **/ 





//SQL For google captcha

$sql_gc="SELECT * FROM wp_options where option_name = 'gglcptch_options'";
$result_gc=mysqli_query($con,$sql_gc);
$row_gc=mysqli_fetch_array($result_gc,MYSQLI_ASSOC);


$returnValue1 = explode(';', $row_gc["option_value"]);
$site_key = explode('"', $returnValue1[3]);
$secret_key = explode('"', $returnValue1[5]);


/** Google reCAPTCHA site key */
define('SITEKEY',  $site_key[1]);

/** Google reCAPTCHA secret key */
define('SECRETKETY', $secret_key[1]);

/** Google reCAPTCHA site key */
//define('SITEKEY', '6Lc2URsUAAAAALSMQ2czg7YVxwqPrfDx_aqKmrp1');

/** Google reCAPTCHA secret key */
//define('SECRETKETY', '6Lc2URsUAAAAAI7bugZn7tV8jWVdd4JbwoG5dQEz');







?>
