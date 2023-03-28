<?php /* Template Name: Success Page */ 
session_start();
get_header();
print_r($_SESSION);
print_r($_COOKIE);

//$this->query = "INSERT INTO reviews (		reviewerip, reviewer_email, productid, model, channelid, value_rating, 
					//overall_rating, customer_service, summary, reviewer_experience, 
					//similar_products, user_screen_name,	valid  
				//)
				//VALUES (
									
					//'$_SERVER[REMOTE_ADDR]', '$post[user_email]', '$post[ProductID]', 
					//'$post[model_reviewed]', '$post[PSite]', '$post[Value_Rating]', '$post[Overall_Rating]', 
					//'$post[Customer_Service]', '$post[Summary]', '$post[reviewer_experience]', 
					//'$post[Similar_Products]', '$post[user_name]', 1
				//)";
				
				//$this->db->query($this->query);
				mysql_query("insert into reviews(reviewerip,ProductID,model,channelid,Value_Rating,overall_rating,summary,reviewer_experience,vbulletinid)values('$_SERVER[REMOTE_ADDR]','$_POST[ProductID]','$_POST[pname]',1,'$_POST[Value_Rating]','$_POST[Overall_Rating]','$_POST[Summary]','$_POST[reviewer_experience]','$_COOKIE[bb_userid]')");
			
				/**
				* Get current review and QIK ratings from product to add new rating
				* and update average ratings for product
				*/
			 
				//$result 	= mysql_query("Select total_reviews, quickrating_count from products where productid = '$post[ProductID]'");
				//$Review = mysql_fetch_object($result);
				
				/*if($Review) $Review->total_reviews++;
					
				else $Review->total_reviews = 1;
				
				$result = mysql_query("Select sum(rate_value) as points from rating where foreignid = '$post[ProductID]'");
				$Quick_Points = mysql_fetch_object($result);
				
				if(!$Quick_Points) $Quick_Points->points = 0;
								
				$result	= mysql_query("Select sum(overall_rating) as points from reviews where productid = '$post[ProductID]'");
				$Review_Points	= mysql_fetch_object($result);
				
				if(!$Review_Points) $Review_Points->points = 0;
				
				$Newaverage_rating = round($Review_Points->points/$Review->total_reviews,2);
				
				$combinePoints	= $Review_Points->points + $Quick_Points->points;
				$combineRatings = $Review->total_reviews + $Review->quickrating_count;
				
				$combined_rating = round($combinePoints/$combineRatings, 2);
				
				mysql_query("UPDATE products SET total_reviews = '$Review->total_reviews', 
								average_rating = '$Newaverage_rating', combined_average = '$combined_rating' 
								WHERE productid = '$post[ProductID]'");*/
								
?>
<p class="subheadertext">Review successfully posted. Return to <a href="<?php echo $_POST['prul']; ?>"><u>product page</u></a>.</p>
<?php get_footer();
?>
