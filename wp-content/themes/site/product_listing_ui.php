			<table id="mobiletable" align="center" class="table no-spacing" cellpadding="0"  cellspacing="0" border="0" style="text-align:center; border-collapse: collapse;display:none;">
			<thead></thead>
			<?php $i=0; 
			 foreach($Products as $Product) {
			if($Product->manufacturer_name!=''){  
				?>
				<tbody style="border: 1px solid black;display: inherit;">  	        
				  <tr class='clickable-row' align="center" data-href="<?php if($DEEPCATEGORIES == '1'){if(function_exists('cr_product_url')){ cr_product_url_outdoor_cat($Product); }}else{if(function_exists('cr_product_url')){ cr_product_url($Product); }}?>">
					<td style="width:25%;"><div class="imageBox"><img id="thumb-img-0" src="<?php if(function_exists('cr_product_image')){ echo cr_product_image($Product->product_image, 'product'); }?>" /></div></td>
					
					<td style="width:75%;"><ul class="list-unstyled">
					<li style="font-size: 15px;font-weight: bold;"><a style="color:black !important;" href="<?php if($DEEPCATEGORIES == '1'){if(function_exists('cr_product_url')){ cr_product_url_outdoor_cat($Product); }}else{if(function_exists('cr_product_url')){ cr_product_url($Product); }}?>"><?php echo $Product->manufacturer_name; ?>&nbsp;<?php echo $Product->product_name; ?></a></li>	
						<!--  Date Created and Last reviewed date  -->
						<!--<li>(<?php if($Product->date_created != ''){echo date('Y',strtotime($Product->date_created));}else{ echo "-";} ?><?php if($Product->total_reviews !=0 ){echo " - ".date('Y',strtotime($Product->reviewdate));}else{} ?>)</li>-->
					<?php 
					if($Product->total_reviews == 0){ 
					?>
						
						<li class="product_reviewButton" style="margin-top: 20px;"><span><a href="<?php if($DEEPCATEGORIES == '1'){if(function_exists('cr_product_url')){ cr_product_url_outdoor_cat($Product); }}else{if(function_exists('cr_product_url')){ cr_product_url($Product); }}?>">WRITE A REVIEW</a></span></li>
					<?php 
					}
					else
					{ 
					?>					
						<li><?php echo $Product->total_reviews; ?> Reviews</li>
						<li><?php echo round($Product->average_rating,1); ?> of 5</li>
						<?php if($_SESSION['sort']=="views"){ ?>
							<li>Views:&nbsp;<?php echo $Product->views; ?></li>
						<?php }else { ?>
							<li>Last Review On:&nbsp;<?php if($Product->reviewdate != ''){echo date('m/d/Y',strtotime($Product->reviewdate));}else{ echo "-";} ?> </li>
						<?php } ?>
						<li><?php $ratePerc = ($Product->average_rating/5)*100 ?>
						<div class="star-ratings-css-listing">
						  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
						  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
						</div>
						</li>
					<?php } ?>
					</ul></td></tr>
				</tbody>
				


<!-- Mobile adds  center aligned by siva -->
<?php if($i==20){ echo '<tbody style="display: inherit;"><tr style="display:table;    width: 100%;"><td colspan="2"><center>';if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Product listing Mobile Ad 300x250") ) : 
	 endif;echo'</center></td></tr></tbody>';}  ?>

<?php if($i==40){ echo '<tbody style="display: inherit;"><tr style="display:table;    width: 100%;"><td colspan="2"><center>'; if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Product listing Mobile Ad-1 300x250") ) : 
	endif;echo'</center></td></tr></tbody>';}  ?>
<!-- Mobile adds  center aligned by siva -->
	
	
	

			<?php $i++;} }?>	
			</table>
			
			<table id="desktoptable" class="table no-spacing" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;">
			<thead></thead>
				<?php foreach($Products as $Product) { 
				
				
				if($Product->manufacturer_name!=''){?>
				
				<tbody style="border: 1px solid black;">
				  <tr class='clickable-row' align="center" data-href="<?php if($DEEPCATEGORIES == '1'){if(function_exists('cr_product_url')){ cr_product_url_outdoor_cat($Product); }}else{if(function_exists('cr_product_url')){ cr_product_url($Product); }}?>">
					<td style="width:116px;"><div class="imageBox"><img id="thumb-img-0" src="<?php if(function_exists('cr_product_image')){echo cr_product_image($Product->product_image, 'product');} ?>" /></div></td>
		
					<td style="width:349px;">
						<ul class="list-unstyled">
							<li style="font-size: 15px;font-weight: bold;"><a style="color:black !important;" href="<?php if($DEEPCATEGORIES == '1'){if(function_exists('cr_product_url')){ cr_product_url_outdoor_cat($Product); }}else{if(function_exists('cr_product_url')){ cr_product_url($Product); }}?>"><?php echo $Product->manufacturer_name; ?>&nbsp;<?php echo $Product->product_name; ?></a></li>
						<!--  Date Created and Last reviewed date  -->
							<!--<li style="font-size: 15px;">(<?php if($Product->date_created != ''){echo date('Y',strtotime($Product->date_created));}else{ } ?><?php if($Product->total_reviews !=0 ){echo " - ".date('Y',strtotime($Product->reviewdate));}else{} ?>)</li>-->
						</ul>
					</td>
					<td style="width:150px;"><ul class="list-unstyled" <?php if($Product->total_reviews==0) { echo 'style="text-align:center;"'; } ?>>

					<?php if($Product->total_reviews==0) { ?>
							Be the First!
							<li class="product_reviewButton" style="margin-top: 5px;"><span><a href="<?php if($DEEPCATEGORIES == '1'){if(function_exists('cr_product_url')){ cr_product_url_outdoor_cat($Product); }}else{if(function_exists('cr_product_url')){ cr_product_url($Product); }}?>">WRITE A REVIEW</a></span></li>				
					<?php }else{ ?>
							<li><font style="color:#0275d8;"><?php echo $Product->total_reviews; ?> Reviews</font></li>
							
							<li><font style="font-weight:bold;"><?php echo round($Product->average_rating,1); ?> of 5</font></li>
							<?php if($_SESSION['sort']=="views"){ ?>
								<li>Views:&nbsp;<?php echo $Product->views; ?> </li>
							<?php }else { ?>
							
							<li>Last Review On:</li>
							<li><?php if($Product->reviewdate != ''){echo date('m/d/Y',strtotime($Product->reviewdate));}else{ echo "-";} ?> </li>
							<?php } ?>
					
							<li><?php $ratePerc = ($Product->average_rating/5)*100 ?>
							<div class="star-ratings-css-listing">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
							</li>
					<?php } ?>
					</ul></td></tr>
				</tbody>
	<?php }} ?>
			</table>
