<div id="reviewAppOO7">
<div id="reviewAppOO7-header"  class="reviewAppOO7-hidden-md-down" style="position:relative;">
<?php $img="https://".$_SERVER['HTTP_HOST']."/wp-content/themes/site/images/logo.png";
list($width, $height, $type, $attr) = getimagesize($img);  $newwidth=$width."px";$newheight=$height."px";$margin=((90-$height)/2)."px"; ?>
        <div style="margin-left: 20px;margin-top:<?php echo $margin; ?>;height: 92px;position: absolute;width:<?php $newwidth; ?>"><a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_url') ?>/images/logo.png"/></a>
        </div>

<div class="reviewAppOO7-inner" style="width:calc(98% - <?php echo $newwidth; ?>) !important;float:right;">
            <nav id="reviewAppOO7-site-navigation" class="reviewAppOO7-main-navigation" role="navigation">
                <div id="reviewAppOO7-navigation"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'reviewAppOO7-nav-menu' ) ); ?></div>

            </nav>
            <div class="gamburger"></div>
            </div>
    </div>
</div>

<?php
include(__DIR__.'/../../../wp-load.php');
$mobile_menu_background = get_option("mobile_menu_background");
$TopLoginRegister = get_option("TopLoginRegister");
$headColor = get_option("");
$brandMenuColor = get_option("BrandMainColor");

$brandMenuInactiveColor = get_option("BrandSelectInactiveColor");
$brandMenuBGColor = get_option("BrandSelectBGColor");

?>

<style>
<?php echo $TopLoginRegister; ?>



@supports (-ms-ime-align:auto) {
	#reviewAppOO7 .sub-menu {
    margin: 23px 0 0 0px!important;
}
}

@supports (-ms-accelerator:true) {
	#reviewAppOO7 .sub-menu {
    margin: 23px 0 0 0px!important;
}
}


@media only screen and (max-width: 600px) {
    ul li.current-menu-item > a,ul.sub-menu li.current-menu-item  a
    {
        background-color: <?php  echo $mobile_menu_background; ?>;
    }
    ul li.current-menu-item > a,ul.sub-menu li.current-menu-item  a
    {
        border-top: none!important; border-bottom: none!important;
    }
    .mobile-header .right-menu .menu-item a:active
    {
        background-color:rgba(255, 255, 255, 0)!important;  text-decoration: none!important;
        text-decoration: none !important;
    }
    .mobile-header .right-menu .menu-item.current-menu-item a:active
    {
        background-color: #137211 !important;
        text-decoration: none !important;
    }
}
.reviewAppOO7-userid-exist{display:none;}
.ui-body-d .ui-link {
    font-weight:normal;
}
.ui-body-d .ui-link:visited, .ui-body-d .ui-link:hover, .ui-body-d .ui-link:active {
    color: #FFFFFF;
    text-decoration: none !important;
}
/*
.mobile-header .right-menu li.menu-item a:hover {
    background-color: inherit !important;
    text-decoration:none;
}
*/

.ui-mobile-viewport .navbar {
    padding:0;
}
ul#navtabs li li {
    width:auto;
}
.navtabs li.selected li a, .navbar_advanced_search li a {
    font-size:10px;
    }

.ui-mobile-viewport #vbflink_calendar {
    display:none;
}


</style>
<div class="mobile-header">
    <div class="fixed-menu">
        <div class="gamburger"></div>
        <a href="<?php echo "https://".$_SERVER['SERVER_NAME']."/"; ?>" class="logo">
            <img src="<?php bloginfo('template_url') ?>/images/logo.png" alt="Logo"/>
        </a>
        <div class="search">
            <div class="button-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#fff" d="M23.809 21.646l-6.205-6.205c1.167-1.605 1.857-3.579 1.857-5.711 0-5.365-4.365-9.73-9.731-9.73-5.365 0-9.73 4.365-9.73 9.73 0 5.366 4.365 9.73 9.73 9.73 2.034 0 3.923-.627 5.487-1.698l6.238 6.238 2.354-2.354zm-20.955-11.916c0-3.792 3.085-6.877 6.877-6.877s6.877 3.085 6.877 6.877-3.085 6.877-6.877 6.877c-3.793 0-6.877-3.085-6.877-6.877z"/></svg>
            </div>
            <div class="wrap-input">
                <form method="get" class="shiftnav-searchform" action="<?= site_url('/search.html') ?>">



                    <input type="text" required data-brand="111" size="30" class="shiftnav-search-input" placeholder="Enter Search Term..." name="q" value="" autocomplete="off" style="background: #f3f3f3;">



                </form>
            </div>
        </div>
    </div>
    <div class="right-menu">
        <?php
        $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        //echo $_SERVER["REQUEST_URI"];

        if(isset($_GET['logout']))
        {
				if(isset($_COOKIE['bb_userid']))
				{
					unset($_COOKIE['bb_userid']);
					unset($_COOKIE['bb_username']);
					//unset($_COOKIE['bb_userid']);
					unset($_COOKIE['bb_password']);
					//setcookie('bb_userid','',0,'/',COOKIE_DOMAIN_NEW);
					setcookie('bb_username','',0,'/',COOKIE_DOMAIN_NEW);
					setcookie('bb_userid','',0,'/',COOKIE_DOMAIN_NEW);
					setcookie('bb_password','',0,'/',COOKIE_DOMAIN_NEW);
					session_destroy();

					header("location: ".$_GET['logout']."");
				}
        }


if(isset($_GET['thirdparty']))
{
	echo "<div class='reviewAppOO7-loginpanel reviewAppOO7-userid-exist' style='' ><span style='font-size: 13px;color: #fff;padding: 12px 25px;text-transform: uppercase;'>Welcome <a class='reviewAppOO7-username' style='font-size: 13px;' href='".site_url('/user-profile.html') . "'>Guest</a> <a style='text-decoration: underline;font-size:13px' href='" . site_url("/logout.html") . "'>Logout</a></span></div>";
}
else if(isset($_COOKIE['bb_userid']) && isset($_COOKIE['bb_username']))
{
	echo "<div class='reviewAppOO7-loginpanel reviewAppOO7-userid-exist' style='' ><span style='font-size: 13px;color: #fff;padding: 12px 25px;text-transform: uppercase;'>Welcome <a class='reviewAppOO7-username' style='font-size: 13px;' href='".site_url('/user-profile.html') . "'>Guest</a> <a style='text-decoration: underline;font-size:13px' href='" . site_url("/logout.html") . "'>Logout</a></span></div>";
}
else
{
	echo "<div class='reviewAppOO7-loginpanel reviewAppOO7-userid-empty' style='' ><span><a href='". site_url('/user-login.html') ."' style='font-size:13px;'>Login</a></span>"; echo "<span><a href='" . site_url('/user-registration.html'). "' style='font-size:13px;'>Register</a></span></div> ";
}

        ?>
        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'menu-nav' ) ); ?>
    </div>
</div>
<!-- Mobile menu script -->
<?php if(isset($_GET['load_jquery']) && $_GET['load_jquery']=='yes'){ ?>
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

<?php } ?>







<!-- for mobile touch and drag -->

<style>
.no-touch .mobile-header .right-menu .menu-item:active > a, .no-touch .mobile-header .right-menu .menu-item:active > a,.mobile-header .right-menu .menu-item.over > a {
    background-color: <?php  echo $mobile_menu_background; ?>;
    background:<?php  echo $mobile_menu_background; ?>;
    text-decoration: none !important;
}


</style>


 <?php if(isset($_GET['load_jquery']) && $_GET['load_jquery']=='yes'){ ?>




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

<?php } ?>
<!-- for mobile touch and drag -->
