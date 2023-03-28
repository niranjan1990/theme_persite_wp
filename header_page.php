<?php 
include( dirname( __FILE__ ) . '/wp-load.php' );
include(get_template_directory() . "/header_widget.php");
?>	
<?php if(isset($_GET['load_jquery']) && $_GET['load_jquery']=='yes'): ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	
	
	
<style>
.mobile-header {
     -webkit-overflow-scrolling: touch;
    display: none;
    position: relative;
    height: 54px;
    margin-bottom: 15px;
    z-index: 10;
}
.background-element {
    position: fixed;
    z-index: 2;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.mobile-header {
    display: none;
    position: relative;
    height: 54px;
    margin-bottom: 15px;
    z-index: 10;
}

.mobile-header  .fixed-menu {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 54px;
    padding: 0 64px;
    background: #1D1D20;
    z-index: 3;
}

.mobile-header .gamburger {
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 24px;
    height: 21px;
    margin: auto 0;

    border-top: 3px solid #fff;
    border-bottom: 3px solid #fff;
}
.mobile-header .gamburger:after,
.mobile-header .gamburger:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    height: 3px;
    margin: auto 0;
    background: #fff;
}
.mobile-header.active  .gamburger {
    border-color: transparent;
}
.mobile-header.active  .gamburger:after,
.mobile-header.active  .gamburger:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    transform: rotate(45deg);
}
.mobile-header.active  .gamburger:before {
    transform: rotate(-45deg);
}

.mobile-header .logo {
    display: block;
    max-width: 100%;
    height: 100%;
    padding: 5px 0;
    box-sizing: border-box;
}
.mobile-header .logo img {
    display: block;
    max-width: 100%;
    max-height: 100%;
    margin: 0 auto;
}

.mobile-header .search {
    position: absolute;
    right: 20px;
    top: 0;
    bottom: 0;
    width: 24px;
    height: 24px;
    margin: auto 0;
}
.mobile-header .search .button-search {
    width: 22px;
    height: 29px;
    background-image: url('data:image/svg+xml;utf8,');
    background-size: 100% 100%;
    cursor: pointer;
}
.mobile-header .search .wrap-input {
    position: fixed;
    display: block;
    top: 54px;
    left: 0;
    width: 0;
    height: 0;
    max-width: 320px;
    box-sizing: border-box;
    border: 4px solid #4CAF50;
    opacity: 0;
     transition: all 0.5s ease-out 0s;
}
.mobile-header .search .wrap-input form {
    height: 100%;
}
.mobile-header .search .wrap-input input {
    width: 100%;
    height: 100%;
    padding: 5px 10px;
    font-size: 18px;
}
.mobile-header .search.active {
    background: transparent;
}
.mobile-header .search.active .button-search svg {
    display: none;
}
.mobile-header .search.active .button-search:after,
.mobile-header .search.active .button-search:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    height: 4px;
    margin: auto 0;
    transform: rotate(45deg);
    background: #fff;
}
.mobile-header .search.active .button-search:before {
    transform: rotate(-45deg);
}
.mobile-header .search.active .wrap-input {
    width: 100%;
    height: 50px;
    opacity: 1;
}

.mobile-header .right-menu {
    position: fixed;
    top: 0;
    left: -215px;
    bottom: 0;
    width: 215px;
    padding-top: 68px;
    overflow: auto;
    transition: left 0.5s ease-out 0s;
    background: #1D1D20;
    overflow-x: hidden !important;
}
.mobile-header.active .right-menu {
    left: 0;
}
.mobile-header .right-menu a {
    display: block;
    padding: 12px 25px;
    text-transform: uppercase;
    font-family: Helvetica, Lucida Sans, Arial, Sans Serif;
    font-size: 16px;
    line-height: 20px;
    color: #fff;
}
.mobile-header .right-menu .current-menu-item a {
    font-weight: 600;
}

.mobile-header .right-menu .menu-item a:hover{
    /* background-color: #137211 !important; */
}
.mobile-header .right-menu .menu-nav {
    border-top: 4px solid coral;
}
.mobile-header .right-menu .menu-nav  a {
    font-size: 13px;
}
.mobile-header .right-menu .sub-menu {
    margin: 0;
}
.mobile-header .right-menu .sub-menu a {
    padding-left: 50px;
}

    </style>
<?php endif ?>
<script>
(function() {
	
	var res;
	var newurl;
	var hostname = window.location.origin+"/";
	var menufound =0;
    var jQuery = (typeof window.jQuery === 'undefined' || window.jQuery === null) ? $ : window.jQuery;
    var menuBox = jQuery('#menu-menu_header');
    menuBox.find('.current-menu-item').addClass('menu-item');
    menuBox.find('.current-menu-item').removeClass('current-menu-item'); 
    menuBox.find('a').each(function () 
	{
        var jthis = jQuery(this);
        var aHref1 = jthis.attr('href');
        if(aHref1 === window.location.href) {
            jthis.closest('li').addClass('current-menu-item'); 
            jthis.closest('ul').closest('li').addClass('current-menu-item');
			menufound = '1';
			}
	});
		if(menufound == '0')
		{	
					
			menuBox.find('a').each(function () {
			var jthis = jQuery(this);
			var aHref1 = jthis.attr('href');
				// For reviews.domain.com
				res = window.location.href.toLowerCase().split("/");
				if(hostname.toLowerCase().indexOf("reviews") >= 0)
				{ 
					if(aHref1 === hostname)
					{
						jthis.closest('li').addClass('current-menu-item'); 
						jthis.closest('ul').closest('li').addClass('current-menu-item');
					}
				}
				else if(res[3] === "reviews")
				{	
					newurl = hostname+res[3]+"/";	
					if(aHref1 === newurl )
					{
						jthis.closest('li').addClass('current-menu-item'); 
						jthis.closest('ul').closest('li').addClass('current-menu-item');
					}
				}

				if(hostname.toLowerCase().indexOf("forums") >= 0)
				{
					if(aHref1 === hostname+"forum.php" || aHref1 === hostname )
					{
						jthis.closest('li').addClass('current-menu-item'); 
						jthis.closest('ul').closest('li').addClass('current-menu-item');
					}
				}
				
				if(hostname.toLowerCase().indexOf("classifieds") >= 0)
				{
					if(aHref1 === hostname+"index.php" || aHref1 === hostname )
					{
						jthis.closest('li').addClass('current-menu-item'); 
						jthis.closest('ul').closest('li').addClass('current-menu-item');
					}
				}
				
			});

		}

    //Loop thro mobile menu items
    
    var menuBox1 = jQuery('#menu-menu_header-1');
    menuBox1.find('.current-menu-item').addClass('menu-item');
    menuBox1.find('.current-menu-item').removeClass('current-menu-item'); 
    res = window.location.href.toLowerCase().split("/");
    menuBox1.find('a').each(function () 
	{
        var jthis = jQuery(this);
        var aHref1 = jthis.attr('href');
        if(aHref1 === window.location.href) {
            jthis.closest('li').addClass('current-menu-item'); 
            //jthis.closest('ul').closest('li').addClass('current-menu-item');
			menufound = '1';
			}
	});
		if(menufound == '0')
		{
			menuBox1.find('a').each(function () {
			var jthis = jQuery(this);
			var aHref1 = jthis.attr('href');
			
				if(hostname.toLowerCase().indexOf("reviews") >= 0)
				{
					if(aHref1 === hostname)
					{
						jthis.closest('li').addClass('current-menu-item'); 
						//jthis.closest('ul').closest('li').addClass('current-menu-item');
					}
				}else if(res[3] === "reviews"){	
					newurl = hostname+res[3]+"/";	
					if(aHref1 === newurl )
					{
						jthis.closest('li').addClass('current-menu-item'); 
						
					}
				}


				
				if(hostname.toLowerCase().indexOf("forums") >= 0)
				{
					if(aHref1 === hostname+"forum.php" || aHref1 === hostname )
					{
						jthis.closest('li').addClass('current-menu-item'); 
						//jthis.closest('ul').closest('li').addClass('current-menu-item');
					}
				}

				if(hostname.toLowerCase().indexOf("classifieds") >= 0)
				{
					if(aHref1 === hostname+"index.php" || aHref1 === hostname )
					{
						jthis.closest('li').addClass('current-menu-item'); 
						//jthis.closest('ul').closest('li').addClass('current-menu-item');
					}
				}

			});

		}
})();
</script>

<!-- #reviewAppOO7 generated at <?=  date(DATE_RFC2822)?>. -->

