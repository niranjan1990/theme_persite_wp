<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('wp-reviewconfig.php');
mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);	
mysql_select_db(DB_RNAME);
$query=mysql_query('SELECT LinkID, Link_Name, Graphic, Partner_Product_Text, Sale_Price, Original_Price, cp.Partner_Graphic, c.valid, l.valid, c.expired FROM partner_campaigns c JOIN partner_links l  On c.CampaignID = l.CampaignID JOIN commerce_partners cp  On cp.PartnerID = c.PartnerID WHERE campaign_type = 25 And l.channelid = 1 and c.valid = 1 and l.valid = 1 and curdate() Between start_date And end_date order by rand() LIMIT 2;');
$data=[];
while ($res1=mysql_fetch_assoc($query))
{
$data[]='<strong>'.$res1['LinkID'].'</strong>';
}

//$data = '{sample: "<strong>Widget!</strong>"}'; // json string

 if(array_key_exists('callback', $_GET)){

       header('Content-Type: text/javascript; charset=utf8');
           header('Access-Control-Allow-Origin: *');
               header('Access-Control-Max-Age: 3628800');
                   header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

                       $callback = $_GET['callback'];
                           echo $callback.'('.json_encode(['sample'=>$data]).');';

 }else{
       // normal JSON string
           header('Content-Type: application/json; charset=utf8');
               echo $data;
 }
