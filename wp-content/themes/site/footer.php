<?php
global $NOADFOOTER, $pagename;
 include(__DIR__.'/../../../wp-config-extra.php');	?>
	</div><!-- end content -->
	<!--<div id="footer-separator" class="col-sm-12"></div>-->
<?php if($pagename != "RVF"){ ?>
<?php if($NOADFOOTER==0){ ?>
	<div id="footer" class="col-sm-12 hidden-md-down">

		 <div id="footer-ad">

			<?php if(Live_Ads()) { ?>
			<?php get_sidebar( 'bottom-leaderboard' ); ?>
		 <?php } else { ?>
			<a href="#"><img src="<?php bloginfo('template_url') ?>/images/footer-ad.png" /></a>
		<?php } ?>
		 </div>
	</div>
<?php } }?>

<?php if($pagename != "RVF"){ ?>

<?php
if($SITE_NAME)
{
	require_once(__DIR__.'/../../../wp-reviewconfig.php');

	$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

	$PSite = explode(":", get_option( 'PSiteName' ) );



		if(!isset($_SESSION['hotdeals']))
		{
			$query=mysqli_query($con,"SELECT LinkID, Link_Name, Graphic, REPLACE(Partner_Product_Text ,'|', ' ') as Partner_Product_Text, Sale_Price, Original_Price, cp.Partner_Graphic, c.valid, l.valid, c.expired FROM partner_campaigns c JOIN partner_links l  On c.CampaignID = l.CampaignID JOIN commerce_partners cp  On cp.PartnerID = c.PartnerID WHERE campaign_type = 25 And l.channelid = $PSite[0] and c.valid = 1 and l.valid = 1 and curdate() Between start_date And end_date order by rand() LIMIT 15;");
			while($res1=mysqli_fetch_assoc($query))
			{
					$_SESSION['hotdeals'][] = $res1;
			}
		}
}
?>



<?php
if(count($_SESSION['hotdeals']) >=3)
{ ?>


	<!-- From Session -->
<div class="hot-deals-module-v2">



<?php
$numbers = range(0,count($_SESSION['hotdeals'])-1);
shuffle($numbers);

for ($x = 0; $x <= 1; $x++)
{
	$rand_num = $numbers[$x];

				$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
				$pnewimg3 = $pcdn.$_SESSION['hotdeals'][$rand_num]['Partner_Graphic'];
				$newimg1 = explode('/',$_SESSION['hotdeals'][$rand_num]['Graphic']);
				$newimg2 = count($newimg1);
				$cdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/HotDeals/";
				$newimg3 = $cdn.$newimg1[$newimg2-1];






		global $crrp;

		switch ($crrp) {
			case "HOMEPAGE":
				$referrer="HD_Homepage_Top";
				break;
		}
?>

	<table id="mobiletable" class="new-hot-deals-sidebar floatleft" style="float: left;width: 100%;display:none;">
		<tbody>
			<tr class='clickable-row' target="_blank" data-href="/commerceredirect.html?linkid=<?php echo $_SESSION['hotdeals'][$rand_num]['LinkID']; ?>&referrer=<?php echo $referrer; ?>" style="height:130px;">
				<td valign="top" class="middlecol" style="    width: 100%;    padding-top: 10px;    padding-left: 10px; padding-bottom: 5px;">
					<h4 style="    font-size: 14px;    font-weight: bold;    color: #333333 !important;    padding: 0;    margin: 0 0 2px 0 !important;    min-height: 40px;">
						<?php echo $_SESSION['hotdeals'][$rand_num]['Partner_Product_Text']; ?>
					</h4>
					<div class="new-hot-deals-text" style="    font-size: 12px;    font-weight: bold;    color: #000;">
						<span class="old-price">(was <strike><?php echo $_SESSION['hotdeals'][$rand_num]['Original_Price']; ?></strike>)</span>
						<span class="hotdeals-price"><?php echo $_SESSION['hotdeals'][$rand_num]['Sale_Price']; ?></span>
						<br>
						<span class="hotdeal-link_name">
							<img class="hotdeals-logo" style="width: 110px;margin-top: 5px;" src="<?php echo $pnewimg3;?>">
						</span>
					</div>
				</td>
				<td valign="top" class="firstfirstcol" style="padding-right: 10px;padding-bottom: 10px;padding-top: 10px;vertical-align: top !important;width: 90px;position : relative;">
					<img class="new-hot-deals-img" style="    width: 80px;    height: 80px;    padding-left: 5px;" src="<?php echo $newimg3;?>">
					<br>
					<div class="hotdeal-buy-all-mer" style="position: absolute; bottom: 10px;">Buy Now</div>
				</td>
			</tr>
		</tbody>
	</table>

<?php
}
?>
</div>



<?php
} }
?>





</div><!-- end container -->
<?php if($pagename != "RVF"){ ?>
<?php if($NOADFOOTER==0){ ?>
<div class="onlymobile" style="position:relative;width:100%;margin-top:10px;margin-bottom:10px;">

	<center><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Sticky Mobile Ad-2 300x50") ) :
	 endif; ?></center>

</div>
<div id="footer-separator" class="col-sm-12" style="height:5px;"></div>
<div class="onlymobile" style="position:relative;width:100%;margin-top:10px;margin-bottom:10px;">
			<center><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Mobile Ad 300x50") ) :
	 endif; ?></center>
	</div>

<?php } }?>

<!--<?php if($DynamicFooter!=1){ ?>
<div id="subfooter" class="col-sm-12">
			<ul>

				<li><a href="/advertise-with-us.html">Advertise</a></li>
				<li><a href="/terms-of-use.html">Terms of Use</a></li>
				<li><a href="/privacy-policy.html">Privacy Policy</a></li>

			</ul>
			<p>(C) Copyright 1996-<?php echo date("Y"); ?>. All Rights Reserved.</p>
			<p><?php echo $SITE_NAME; ?>.com and the ConsumerReview Network are business units of Invenda Corporation</p>
			<p>Other Web Sites in the ConsumerReview Network:</p>
			<p style="    margin-bottom: 1.6em !important;"><a href="http://www.mtbr.com">mtbr.com</a> |

				<a href="http://www.roadbikereview.com">roadbikereview.com</a> |
				<a href="http://www.carreview.com">carreview.com</a> |
				<a href="http://www.photographyreview.com">photographyreview.com</a>
				|	<a href="http://www.audioreview.com">audioreview.com</a>
			</p>
		</div>
<?php }else{ ?>-->
<?php include(__DIR__ . "/footer_widget.php") ?>
<!--<?php } ?>-->

<div id="fb-root"></div>


<?php
global $pagename,$pagenamejquery;
 if($pagename!='PRD'|| $pagenamejquery=='RVF' || $pagename!='RVF'){ ?>
<!-- for menu selection -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<!-- Mobile menu script -->
<?php } ?>



<script>
    var $header = $('#reviewAppOO7-header .reviewAppOO7-inner'),
        $gamburger = $header.find('.gamburger'),
        $nav = $header.find('#reviewAppOO7-site-navigation'),
        openClass = 'open';

        $gamburger.click(function() {
            var $this = $(this);
            if($this.hasClass(openClass)) {
                $this.removeClass(openClass);
                $nav.slideUp();
            } else {
                $this.addClass(openClass);
                $nav.slideDown();
            }
        });
</script>
<script>
function reviewAppOO7Js() {
(function() {
    var cookies;
    function readCookie(name,c,C,i)
    {

        if(cookies){
                return cookies[name];
            }

        c = document.cookie.split('; ');
        cookies = {};

        for(i=c.length-1; i>=0; i--){
           C = c[i].split('=');
           cookies[C[0]] = C[1];
        }

        return cookies[name];
    }

    window.reviewAppOO7ReadCookie = readCookie; // or expose it however you want

    var jQuery = (typeof window.jQuery === 'undefined' || window.jQuery === null) ? $ : window.jQuery;
    jQuery(function () {
        if(window.reviewAppOO7ReadCookie('bb_userid')) {
            jQuery('.reviewAppOO7-userid-exist').show()
            jQuery('.reviewAppOO7-userid-empty').hide()
            jQuery('.reviewAppOO7-username').text(decodeURIComponent(window.reviewAppOO7ReadCookie('bb_username')).split("+").join(" "));
        }
        else {
            jQuery('.reviewAppOO7-userid-empty').show()
            jQuery('.reviewAppOO7-userid-exist').hide()
        }
        jQuery('.reviewAppOO7-loginpanel a').each(function() {
           var a = jQuery(this);
           var href = a.attr('href');
           if(href.search('/top_bar.php')!==-1) {
               a.attr('href', href.substr(0, href.search('=')) + '=' + window.location.href);
           }
           if(href.search('/header_page.php')!==-1) {
               a.attr('href', href.substr(0, href.search('=')) + '=' + window.location.href);
           }
        });
    });
})();
}
if(!window.jQuery)
    window.addEventListener("load",function(event) { reviewAppOO7Js(); },false);
else
    reviewAppOO7Js();

</script>


<script>
    var $mobileHeader = $('.mobile-header'),
        $gamburger = $mobileHeader.find('.gamburger'),
        $menu = $mobileHeader.find('.right-menu'),
        $search = $mobileHeader.find('.search'),
        $searchButton = $mobileHeader.find('.button-search'),
        activeClass = 'active',
        backgroundClickElementClass = 'background-element';

    $gamburger.click(function() {
        //Open-close mobile menu
        if($mobileHeader.hasClass(activeClass)) {
            removeBackgroundElement();
            $mobileHeader.removeClass(activeClass);
        } else {
            $search.removeClass(activeClass);
            addBackgroundElement();
            $mobileHeader.addClass(activeClass);
        }
    });
    $searchButton.click(function() {
        //Open-close mobile search
        if($search.hasClass(activeClass)) {
            removeBackgroundElement();
            $search.removeClass(activeClass);
        } else {
            $mobileHeader.removeClass(activeClass);
            addBackgroundElement();
            $search.addClass(activeClass);
        }
    });

    function addBackgroundElement() {
        // Element for close menu
        if($('.' + backgroundClickElementClass).length == 0) {
            $('body').append('<div class="' + backgroundClickElementClass + '"</div>');
            $('.' + backgroundClickElementClass).click(function() {
                removeBackgroundElement();
                $mobileHeader.removeClass(activeClass);
                $search.removeClass(activeClass);
            });
        }
    }
    function removeBackgroundElement() {
        $('.' + backgroundClickElementClass).remove();
    }
</script>

<script>
// detect touch
var closetclass="";
var dragging=false;
var highlighted;
var href;

if (!("ontouchstart" in document.documentElement)) {
    document.documentElement.className += " no-touch";
    }

$("body").on("touchmove", function(){
      dragging = true;
});

$('.menu-item a').on('touchstart', function(e) {
    highlighted = $(".current-menu-item");
    dragging = false;
    /*closetclass = $(this).closest('ul').attr("class");

    if(closetclass=="menu-nav")
    {
        $(this).parent().toggleClass('over');
    }
    else
    {
        $(this).parent().toggleClass('over');
        event.stopPropagation();
    }*/
    $(this).parent().addClass("over");
});





$('.menu-item a').on('touchend', function(e) {
    e.preventDefault();
    closetclass = $(this).closest('ul').attr("class");
    href = $(this).attr('href');
     if (dragging) {
        if (!($(this).parent().is('.current-menu-item'))){
            $(this).parent().removeClass("over");
        }
       /* if(closetclass=="menu-nav")
        {
            $(this).parent().toggleClass('over'); over
        }
        else
        {
            $(this).parent().toggleClass('over');
            event.stopPropagation();
        }*/
        return;

      } else {
        highlighted.removeClass("current-menu-item");

        // $(this).parent().addClass("current-menu-item");
       setTimeout(function(){ window.location.href = href; }, 100);

      }

      // wasn't a drag, just a tap
      // more code here
});

//$('.mobile-header .menu-item a').click(function(e){
 //   $("li.menu-item").removeClass("current-menu-item");
 //   $(this).parent().addClass("current-menu-item");
//})


</script>
<!-- for mobile touch and drag -->





<script>
(function() {
    var jQuery = $;
    var menuBox = jQuery('#menu-menu_header');
    menuBox.find('a').each(function () {
        var jthis = jQuery(this);
        var aHref = jthis.attr('href');
		//alert(aHref.toLowerCase()+'|'+location.href.toLowerCase());
        if(aHref.toLowerCase()==location.href.toLowerCase()) {
            menuBox.find('.current-menu-item').removeClass('current-menu-item');
            jthis.closest('li').addClass('current-menu-item');
            jthis.closest('ul').closest('li').addClass('current-menu-item');
        }
    });
})();
</script>
<!-- for menu selection -->
<!-- Special jquery logic to select sub menu For components -->
<?php
//echo $_SERVER['REQUEST_URI'];
$full = explode("/",$_SERVER['REQUEST_URI']);
	global $categoryname;
if (count($full) > 2 && strpos($_SERVER['REQUEST_URI'], 'rurl') == false){
?>
<script>
(function() {
    var jQuery = $;
    var smenuBox = jQuery('.sub-menu');
    var matchcount = 0;
    smenuBox.find('a').each(function () {
        var jthis = jQuery(this);
        var aHref = jthis.attr('href');
		var temp = [];
		temp = aHref.split("/");
		var temp1 = [];
		var local = '<?php echo $full[1]; ?>';
		var tempnew = [];
		tempnew = String(temp[3]).split(".");
		if(temp.length > 3 || temp.length > 4)
		{
			if( tempnew[0] == local || tempnew[0]=='<?php echo $full[2]; ?>' )
			{
                        matchcount = matchcount + 1;
                        }
                }
    });
                       // console.log(matchcount);
    if (matchcount == 2){
    smenuBox.find('a').each(function () {
        var jthis = jQuery(this);
        var aHref = jthis.attr('href');
		var temp = [];
		temp = aHref.split("/");
		var temp1 = [];
		var local = '<?php echo $full[1]; ?>';
		var tempnew = [];
		tempnew = String(temp[3]).split(".");
		if(temp.length > 3 || temp.length > 4)
		{
			if( tempnew[0] == local || tempnew[0]=='<?php echo $full[2]; ?>' )
			{
                        //console.log(tempnew[0]);
                        //console.log(local);
				jthis.closest('li').addClass('current-menu-item');
				jthis.closest('ul').closest('li').addClass('current-menu-item');
			}
		}
    });
    }
})();
</script>
<?php
}
?>






<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>

<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>


<?php }else{ ?>

<script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
<?php } ?>
<script  src="<?php bloginfo('template_url') ?>/js/tether.min.js" ></script>
<script src="<?php bloginfo('template_url') ?>/js/bootstrap.min.js" ></script>


<script>(function(d, s, id) {

	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.async=true;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=230466627003041";
	fjs.parentNode.insertBefore(js, fjs);

}(document, 'script', 'facebook-jssdk'));

</script>


<?php

if(isset($pagename) && $pagename=='PRD'){ ?>
<script type="text/javascript" src="<?php bloginfo('template_url') ?>/js/ekko-lightbox.min.js"></script>
<script type="text/javascript">
                        $(document).ready(function ($) {
                            // delegate calls to data-toggle="lightbox"
                            $(document).on('click', '[data-toggle="lightbox"]:not([data-gallery="navigateTo"]):not([data-gallery="example-gallery-11"])', function(event) {
                                event.preventDefault();
                                return $(this).ekkoLightbox({
                                    onShown: function() {
                                        if (window.console) {
                                            return console.log('Checking our the events huh?');
                                        }
                                    },
                                    onNavigate: function(direction, itemIndex) {
                                        if (window.console) {
                                            return console.log('Navigating '+direction+'. Current item: '+itemIndex);
                                        }
                                    }
                                });
                            });









                        });
                    </script>
<script type="text/javascript" src="<?php bloginfo('template_url') ?>/js/jquery.bxslider.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$('.productbxslider').bxSlider({
	 onSliderLoad: function(){
 $('.productbxslider').bxSlider({   });
$('.productbxslider').css('visibility','visible');
	 }
 });
});
</script>

<?php }?>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
	 if(this.hasAttribute("target")){
            window.open($(this).data("href"),$(this).data("target"));
        }
        else{
            window.document.location = $(this).data("href");
        }
    });
});
</script>


<?php wp_footer() ?>


	<script>
		$(document).ready(function() {
			if ($(window).width() <=650 )
			{
				//alert('Mobile');
				$("body").removeClass("home1");
			}
			else
			{
				//alert('desktop');
			}
		});
	</script>

<?php if($SITE_NAME && $REVIEWS==1 && is_home()){?>
<!--slider script-->
	<script  type="text/javascript" src="<?php bloginfo('template_url') ?>/js/jquery-1.7.2.min.js"></script>
	<script  type="text/javascript" src="<?php bloginfo('template_url') ?>/js/jquery.advancedSlider.min.js"></script>





<script type="text/javascript">

    jQuery(document).ready(function ($) {
        $('#lazy-loading-slider').advancedSlider({
             width: 615, height: 358,skin: 'minimal-small', shuffle: false, slideshowControls: true, slideshowControlsToggle: false, lightboxIcon: false, slideshow: true, effectType: 'fade', slideLoop: true, slideEasing: 'easeInOutExpo',initialEffect: false, shadow: false, timerAnimation: false, slideArrowsToggle: false, slideArrows: false, thumbnailWidth: 70,
												thumbnailHeight: 40,slideButtonsNumber: true,

            slideProperties: {
                0: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '120' },

                1: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '120' },

                2: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '120' },

                3: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '120' },

                4: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '120' },

                5: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '120' },

                6: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '120' },

                7: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '120' }


            }
        });
        $('#lazy-loading-slider-mobile').advancedSlider({
             width: '100%', height: 'auto', scaleType: 'proportionalFit', responsive:true, skin: 'minimal-small', shuffle: false,
            slideshowControls: true, slideshowControlsToggle: false, lightboxIcon: false, slideshow: true,
            effectType: 'fade', slideLoop: true, slideEasing: 'easeInOutExpo',initialEffect: false, shadow: false,
            timerAnimation: false, slideArrowsToggle: false, slideArrows: false, thumbnailWidth: 70,
												thumbnailHeight: 40,slideButtonsNumber: true,

            slideProperties: {
                0: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '90' },

                1: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '90' },

                2: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '90' },

                3: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '90' },

                4: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '90' },

                5: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '90' },

                6: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '90' },

                7: { captionHideEffect: 'fade', captionPosition: 'bottom', captionSize: '90' }


            }
        });

   });



</script>
<!--slider script end-->
<?php }?>
<!-- iframe-messenger  -->
<script type='text/javascript' src="https://static.verticalscope.com/vs_iframe-messenger/vs-iframe-messenger-v2.js"></script>
<script type="text/javascript">
	if (typeof googlefc == "object") {
		googlefc.callbackQueue.push(function() {
		switch (googlefc.getConsentStatus()) {
			case googlefc.ConsentStatusEnum.CONSENT_NOT_REQUIRED:
				vsCFExecuteNonEuTags();
				break;
			default:
				vsCFExecuteEuTags();
				break;
		}
		});
	} else {
		console.log("cf: googlefc not found.");
	}
</script>


</body>
</html>
