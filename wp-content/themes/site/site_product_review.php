<?php
include(__DIR__."/../../../wp-config-extra.php");

if($SITE_NAME == "golfreview") {
    include(__DIR__ . "/site_product_review.golfreview.inc.php");
}
else {
    include(__DIR__ . "/site_product_review.mtbr.inc.php");
}
