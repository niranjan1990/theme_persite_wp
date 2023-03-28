<?php
session_start();
include "wp-config.php";
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'venice_mtbr';
/*$_SESSION['reviewer_experience']=$_POST['reviewer_experience'];
$_SESSION['model_reviewed']=$_POST['model_reviewed'];
$_SESSION['Summary']=$_POST['Summary'];
$_SESSION['Customer_Service']=$_POST['Customer_Service'];
$_SESSION['Similar_Products']=$_POST['Similar_Products'];
$_SESSION['Value_Rating']=$_POST['Value_Rating'];
$_SESSION['Overall_Rating']=$_POST['Overall_Rating'];*/
?>
			<?php //if($_POST) { 
					if(isset($_COOKIE['userid'])){
					echo $result = site_review_product_submit_review($_POST);
			 ?>
			<?php } //} 
			else { 
				echo $result = "http://mtbr.owshi.com/user-login.html";
			} ?>