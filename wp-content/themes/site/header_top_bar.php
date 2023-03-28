<?php
global $template;

//For login/logout

//echo $_SERVER[REQUEST_URI];
if(strpos($_SERVER[REQUEST_URI], '/user-login.html') !== false || strpos($_SERVER[REQUEST_URI], '/user-registration.html') !== false || strpos($_SERVER[REQUEST_URI], '/logout.html') !== false || strpos($_SERVER[REQUEST_URI], '/user-profile.html') !== false || strpos($_SERVER[REQUEST_URI], '/user-profile-1.html') !== false  || strpos($_SERVER[REQUEST_URI], '/user-profile-2.html') !== false || strpos($_SERVER[REQUEST_URI], '/user-profile-3.html') !== false || strpos($_SERVER[REQUEST_URI], '/user-activation.html') !== false || strpos($_SERVER[REQUEST_URI], '/user-profile-4.html') !== false || strpos($_SERVER[REQUEST_URI], '-review.html') !== false || strpos($_SERVER[REQUEST_URI], '/user-requestpassword.html') !== false)
{

}
else
{
	$pre_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if($template == '404')
	{
	}
	else
	{
		setcookie( "bb_pre_url", "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", time()+60000, "/", COOKIE_DOMAIN_NEW );
	}
}
//echo htmlspecialchars($_COOKIE['pre_url']);






?>



<div id="reviewAppOO7" class="stickyBar" >

<div id="reviewAppOO7-top-bar" class="reviewAppOO7-hidden-md-down">
	<div class="reviewAppOO7-inner">
		<div class="reviewAppOO7-nw" style="width:33%">
				<div style="margin:none !important;">

					<?php
						$checkbox = $gp = explode('|',get_option('social_check'));
					?>


					<?php
					if($checkbox[1]==1)
					{
					?>

					<div style="float:left; padding: 2px;">
						<a href="<?php $gp = explode('|',get_option('social_links')); echo $gp[1]; ?>"><img style="height: 19px;" src="<?php bloginfo('template_url') ?>/images/insta.png" /></a>
					</div>
					<?php
					}
					else
					{

					}
					?>




					<?php
					if($checkbox[2]==1)
					{
					?>

					<div style="float:left; padding: 2px;">
						<a href="<?php $gp = explode('|',get_option('social_links')); echo $gp[2]; ?>"><img style="height: 19px;" src="<?php bloginfo('template_url') ?>/images/twitter.png" /></a>
					</div>
					<?php
					}
					else
					{

					}
					?>



					<?php
					if($checkbox[3]==1)
					{
					?>

					<div style="float:left; padding: 2px;">
						<a href="<?php $gp = explode('|',get_option('social_links')); echo $gp[3]; ?>"><img style="height: 19px;" src="<?php bloginfo('template_url') ?>/images/youtube.png" /></a>
					</div>
					<?php
					}
					else
					{

					}
					?>



					<?php
					if($checkbox[4]==1)
					{
					?>

					<div style="float:left; padding: 2px;">
						<a href="mailto:<?php $gp = explode('|',get_option('social_links')); echo $gp[4]; ?>"><img style="height: 19px;" src="<?php bloginfo('template_url') ?>/images/email.png" /></a>
					</div>
					<?php
					}
					else
					{

					}
					?>




					<?php
					if($checkbox[5]==1)
					{
					?>

					
					<?php
					}
					else
					{

					}
					?>
					<style>
					.reviewAppOO7-fbbutton{
						vertical-align: none !important;
					}
					</style>
					<?php
					if($checkbox[0]==1)
					{
					?>

					<div style="float:left;padding: 2px;">

						<iframe class="reviewAppOO7-fbbutton" src="https://www.facebook.com/plugins/like.php?href=<?php $fb = explode('|',get_option('social_links')); echo $fb[0]; ?>&layout=button_count&width=100&show_faces=false&action=like" scrolling="no" frameborder="0" style="vertical-align: middle; border:none; overflow:hidden; width:100px; height:21px;" allowtransparency="true" async="true"></iframe>
						<style>
						span{
							margin-bottom: 6px;
						}
						</style>
					</div>
					<?php
					}
					else
					{

					}
					?>

				</div>
		</div>
<!-- Login widget -->
<?php
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$new = explode("=",$actual_link);
$actual_link = end($new);


$color = get_option("TopLoginRegister");
echo "<style>".$color."</style>";

 if(isset($_GET['thirdparty']) && isset($_GET['username']))
{
echo "<div class='reviewAppOO7-loginpanel reviewAppOO7-userid-empty TopLoginRegister' style='float:left;padding:5px;width:32%;text-align:center' ><strong style='margin-top:;font-weight:bold;'>Welcome <a class='reviewAppOO7-username' href='".site_url('/user-profile.html') . "' >".$_GET['username']."</a>, <a style='text-decoration: underline;' href='" . site_url("/logout.html") . "'>Logout</a>
</strong></div>";

}else if(isset($_GET['thirdparty']))
{
echo "<div class='reviewAppOO7-loginpanel reviewAppOO7-userid-exist TopLoginRegister' style='float:left;padding:5px;width:32%;text-align:center' ><strong style='margin-top:;font-weight:bold;'>Welcome <a class='reviewAppOO7-username' href='".site_url('/user-profile.html') . "' >Guest</a>, <a style='text-decoration: underline;' href='" . site_url("/logout.html") . "'>Logout</a>
</strong></div>";

}else if(isset($_COOKIE['bb_userid']) && isset($_COOKIE['bb_username']))
{
echo "<div class='reviewAppOO7-loginpanel reviewAppOO7-userid-exist TopLoginRegister' style='float:left;padding:5px;width:32%;text-align:center' ><strong style='margin-top:;font-weight:bold;'>Welcome <a class='reviewAppOO7-username' href='".site_url('/user-profile.html') . "' >Guest</a>, <a style='text-decoration: underline;' href='" . site_url("/logout.html") . "'>Logout</a>
</strong></div>";
}
else
{
echo "<div class='reviewAppOO7-loginpanel reviewAppOO7-userid-empty TopLoginRegister' style='float:left;padding:5px;width:32%;text-align:center' ><strong style='font-weight:bold; '><a href='". site_url('/user-login.html') ."' >Login</a></strong>"; echo " / "; echo "<strong style='font-weight:bold; '><a href='" . site_url('/user-registration.html'). "' >Register</a></strong></div> ";

}


?>
<!-- Login widget -->
<div class="reviewAppOO7-rl reviewAppOO7-hidden-md-down" style="width:33%"><div class='reviewAppOO7-search_div'><form method="get" action="<?= site_url('/search.html') ?>"><input type="text" name="q" />  <input type="image" class="reviewAppOO7-search-icon" style="margin-left: 177px;width:22px !important;" src="<?php echo $PRODUCTIMAGE_CDNDOMAIN;?>/<?php echo $PRODUCTIMAGE_S3FOLDER; ?>/images/search_icon.png"></form></div></div>



	</div>
</div>




<?php

		include(__DIR__ .'/../../../wp-config-extra.php');

?>

	<style>@import url("<?php echo $CDN_DOMAIN."/".$S3_FOLDER; ?>/header-widget.css<?php echo $CSS_VERSION;?>");</style>
</div>




<style>
    .reviewAppOO7-loginpanel {
        display: none;
    }
</style>
