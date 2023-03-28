<?php /* Template Name: User Logout Page */
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
require $_SERVER['DOCUMENT_ROOT'] . '/wp-reviewconfig.php';

if(isset($_COOKIE['bb_userid']) || isset($_COOKIE['bb_UserData']))
{

    unset($_COOKIE['bb_userid']);
    unset($_COOKIE['bb_username']);
    unset($_COOKIE['bb_password']);
    unset($_COOKIE['bb_UserData']);
    unset($_COOKIE['bb_sessionhash']);
	    setcookie('bb_username','',0,'/',COOKIE_DOMAIN_NEW);
	    setcookie('bb_userid','',0,'/',COOKIE_DOMAIN_NEW);
	    setcookie('bb_password','',0,'/',COOKIE_DOMAIN_NEW);
	    setcookie('bb_UserData','',0,'/',COOKIE_DOMAIN_NEW);
	    setcookie('bb_sessionhash','',0,'/',COOKIE_DOMAIN_NEW);
		session_destroy();

	if(isset($_COOKIE['bb_pre_url']))
	{
		header("location: ".cookie_redirect($_COOKIE['bb_pre_url']));
	}
	else
	{
		header('location: /');
	}
}
else
{

}
?>
