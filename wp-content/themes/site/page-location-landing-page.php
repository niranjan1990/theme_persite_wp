<?php /* Template Name: Location Landing Page */ ?>

<?php
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';

if($SITE_NAME == "golfreview") {
    include(__DIR__ . "/page-location-landing-page.php.golfreview.inc");
}
else {
    include(__DIR__ . "/page-location-landing-page.php.mtbr.inc");
}
